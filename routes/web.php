<?php

use App\Http\Controllers\Billing\BillingController;
use App\Http\Controllers\Billing\InvoiceController;
use App\Http\Controllers\AuthPreviewController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocsController;
use App\Http\Controllers\Installer\InstallController;
use App\Http\Controllers\LockScreenController;
use App\Http\Controllers\UiKitController;
use App\Livewire\ApiKeys\ApiKeysPage;
use App\Livewire\Faq\FaqPage;
use App\Livewire\Integrations\IntegrationsPage;
use App\Livewire\Calendar\CalendarPage;
use App\Livewire\Chat\ChatPage;
use App\Livewire\Files\FileManager;
use App\Livewire\Kanban\Board;
use App\Livewire\Mail\Inbox;
use App\Livewire\Projects\ProjectsIndex;
use App\Livewire\Tickets\TicketShow;
use App\Livewire\Tickets\TicketsIndex;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Livewire\Activity\ActivityTable;
use App\Livewire\Users\UsersTable;
use Illuminate\Support\Facades\Route;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

/*
|--------------------------------------------------------------------------
| Installer (pre-database — runs without session or CSRF middleware)
|--------------------------------------------------------------------------
*/
Route::withoutMiddleware([EncryptCookies::class, AddQueuedCookiesToResponse::class, StartSession::class, ShareErrorsFromSession::class, ValidateCsrfToken::class])
    ->group(function () {
        Route::get('/install', [InstallController::class, 'show']);
        Route::post('/install', [InstallController::class, 'run']);
    });

/*
|--------------------------------------------------------------------------
| Nexus SaaS — Web Routes (Phases 1–2)
|--------------------------------------------------------------------------
| Auth routes (login/register/reset/verify/2FA) are registered by Fortify.
*/

Route::get('/', fn () => redirect()->route('dashboard'));

Route::middleware(['auth', 'verified'])->group(function () {

    // Lock screen (reachable while locked)
    Route::get('/lock', [LockScreenController::class, 'show'])->name('lock');
    Route::post('/lock', [LockScreenController::class, 'engage'])->name('lock.engage');
    Route::post('/unlock', [LockScreenController::class, 'release'])->name('lock.release');

    Route::middleware(['unlocked', 'lastseen'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/crm', [DashboardController::class, 'crm'])->name('dashboard.crm');
        Route::get('/dashboard/analytics', [DashboardController::class, 'analytics'])->name('dashboard.analytics');

        // ---- Phase 2: account -------------------------------------------
        Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
        Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
        Route::post('/profile/notifications', [ProfileController::class, 'updateNotifications'])->name('profile.notifications');
        Route::post('/profile/sessions/logout-others', [ProfileController::class, 'logoutOtherSessions'])->name('profile.sessions.logout-others');

        // ---- Phase 2: administration ------------------------------------
        Route::get('/users', UsersTable::class)->name('users')
            ->middleware('can:viewAny,App\Models\User');
        Route::get('/activity-logs', ActivityTable::class)->name('activity-logs')
            ->middleware('can:activity.view');

        // ---- Phase 3: billing -------------------------------------------
        Route::view('/pricing', 'billing.pricing')->name('pricing');
        Route::middleware('can:billing.manage')->group(function () {
            Route::get('/billing', [BillingController::class, 'show'])->name('billing');
            Route::post('/billing/subscribe', [BillingController::class, 'subscribe'])->name('billing.subscribe');
            Route::post('/billing/cancel', [BillingController::class, 'cancel'])->name('billing.cancel');
            Route::post('/billing/resume', [BillingController::class, 'resume'])->name('billing.resume');
            Route::get('/billing/portal', [BillingController::class, 'portal'])->name('billing.portal');
            Route::get('/invoices', [InvoiceController::class, 'index'])->name('billing.invoices');
            Route::get('/invoices/{invoiceId}', [InvoiceController::class, 'show'])->name('billing.invoices.show');
            Route::get('/invoices/{invoiceId}/download', [InvoiceController::class, 'download'])->name('billing.invoices.download');
        });
        // ---- Phase 4A: projects, kanban, tickets ------------------------
        Route::get('/projects', ProjectsIndex::class)->name('projects');
        Route::get('/kanban', Board::class)->name('kanban');
        Route::get('/tickets', TicketsIndex::class)->name('tickets');
        Route::get('/tickets/{ticket}', TicketShow::class)->name('tickets.show');

        // ---- Phase 4B: communication & storage --------------------------
        Route::get('/chat', ChatPage::class)->name('chat');
        Route::get('/mail', Inbox::class)->name('mail');
        Route::get('/calendar', CalendarPage::class)->name('calendar');
        Route::get('/files', FileManager::class)->name('files');
        // ---- Phase 5: platform ------------------------------------------
        Route::get('/api-keys', ApiKeysPage::class)->name('api-keys');
        Route::get('/integrations', IntegrationsPage::class)->name('integrations')->middleware('can:settings.manage');
        Route::get('/faq', FaqPage::class)->name('faq');
        Route::view('/blank', 'blank')->name('blank');
        Route::get('/search', SearchController::class)->name('search');
        Route::get('/documentation', [DocsController::class, 'show'])->name('docs');
        Route::get('/auth-preview/{page}', [AuthPreviewController::class, 'show'])->name('auth-preview');
        Route::get('/ui/{page}', [UiKitController::class, 'show'])->name('ui.show');
        Route::get('/error-preview/{code}', function (string $code) {
            abort_unless(in_array($code, ['404', '500', '503'], true), 404);

            return response()->view("errors.{$code}", [], (int) $code);
        })->name('error-preview');
    });
});
