<?php

namespace App\Livewire\Tickets;

use App\Models\Ticket;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class TicketsIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = 'all';

    public bool $showForm = false;
    public string $subject = '';
    public string $body = '';
    public string $priority = 'medium';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    public function save(): void
    {
        $this->validate([
            'subject'  => ['required', 'string', 'max:200'],
            'body'     => ['required', 'string', 'max:5000'],
            'priority' => ['required', 'in:low,medium,high,urgent'],
        ]);

        $ticket = Ticket::create([
            'subject'  => $this->subject,
            'body'     => $this->body,
            'priority' => $this->priority,
            'user_id'  => auth()->id(),
        ]);

        activity('tickets')->causedBy(auth()->user())->performedOn($ticket)->log('Ticket opened');
        $this->reset('showForm', 'subject', 'body', 'priority');

        $this->redirectRoute('tickets.show', $ticket, navigate: false);
    }

    public function render()
    {
        $isAgent = auth()->user()->can('tickets.manage');

        $tickets = Ticket::query()
            ->with(['user', 'agent'])
            ->withCount('replies')
            ->when(! $isAgent, fn ($q) => $q->where('user_id', auth()->id()))
            ->when($this->statusFilter !== 'all', fn ($q) => $q->where('status', $this->statusFilter))
            ->when($this->search, fn ($q) => $q->where(fn ($q) => $q
                ->where('subject', 'like', "%{$this->search}%")
                ->orWhere('reference', 'like', "%{$this->search}%")))
            ->latest()
            ->paginate(10);

        return view('livewire.tickets.tickets-index', [
            'tickets' => $tickets,
            'isAgent' => $isAgent,
            'counts'  => [
                'open'     => Ticket::when(! $isAgent, fn ($q) => $q->where('user_id', auth()->id()))->where('status', 'open')->count(),
                'pending'  => Ticket::when(! $isAgent, fn ($q) => $q->where('user_id', auth()->id()))->where('status', 'pending')->count(),
                'resolved' => Ticket::when(! $isAgent, fn ($q) => $q->where('user_id', auth()->id()))->where('status', 'resolved')->count(),
            ],
        ])->title('Support Tickets');
    }
}
