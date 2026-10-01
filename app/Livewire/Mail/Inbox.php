<?php

namespace App\Livewire\Mail;

use App\Models\MailMessage;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Inbox extends Component
{
    use WithPagination;

    #[Url]
    public string $folder = 'inbox';   // inbox, sent, starred, trash

    public ?int $openId = null;
    public string $search = '';

    public bool $showCompose = false;
    public ?int $composeTo = null;
    public string $composeSubject = '';
    public string $composeBody = '';

    public function setFolder(string $folder): void
    {
        if (in_array($folder, ['inbox', 'sent', 'starred', 'trash'], true)) {
            $this->folder = $folder;
            $this->openId = null;
            $this->resetPage();
        }
    }

    public function open(int $id): void
    {
        $mail = $this->visible()->findOrFail($id);
        if ($mail->to_user_id === auth()->id() && ! $mail->read_at) {
            $mail->update(['read_at' => now()]);
        }
        $this->openId = $id;
    }

    public function toggleStar(int $id): void
    {
        $mail = MailMessage::where('to_user_id', auth()->id())->findOrFail($id);
        $mail->update(['starred_by_recipient' => ! $mail->starred_by_recipient]);
    }

    public function moveToTrash(int $id): void
    {
        $mail = $this->visible()->findOrFail($id);
        $mail->update($mail->to_user_id === auth()->id() ? ['deleted_by_recipient' => true] : ['deleted_by_sender' => true]);
        $this->openId = null;
    }

    public function send(): void
    {
        $this->validate([
            'composeTo'      => ['required', 'exists:users,id'],
            'composeSubject' => ['required', 'string', 'max:200'],
            'composeBody'    => ['required', 'string', 'max:10000'],
        ], [], ['composeTo' => 'recipient', 'composeSubject' => 'subject', 'composeBody' => 'message']);

        MailMessage::create([
            'from_user_id' => auth()->id(),
            'to_user_id'   => $this->composeTo,
            'subject'      => $this->composeSubject,
            'body'         => $this->composeBody,
        ]);

        $this->reset('showCompose', 'composeTo', 'composeSubject', 'composeBody');
        $this->setFolder('sent');
        session()->flash('status', 'Message sent.');
    }

    private function visible()
    {
        $me = auth()->id();

        return match ($this->folder) {
            'sent'    => MailMessage::where('from_user_id', $me)->where('deleted_by_sender', false),
            'starred' => MailMessage::where('to_user_id', $me)->where('deleted_by_recipient', false)->where('starred_by_recipient', true),
            'trash'   => MailMessage::where(fn ($q) => $q
                            ->where(fn ($q) => $q->where('to_user_id', $me)->where('deleted_by_recipient', true))
                            ->orWhere(fn ($q) => $q->where('from_user_id', $me)->where('deleted_by_sender', true))),
            default   => MailMessage::where('to_user_id', $me)->where('deleted_by_recipient', false),
        };
    }

    public function render()
    {
        $messages = $this->visible()
            ->with(['from', 'to'])
            ->when($this->search, fn ($q) => $q->where('subject', 'like', "%{$this->search}%"))
            ->latest()
            ->paginate(12);

        return view('livewire.mail.inbox', [
            'messages'    => $messages,
            'openMail'    => $this->openId ? $this->visible()->with(['from', 'to'])->find($this->openId) : null,
            'unreadCount' => MailMessage::where('to_user_id', auth()->id())->where('deleted_by_recipient', false)->whereNull('read_at')->count(),
            'members'     => User::where('id', '!=', auth()->id())->orderBy('name')->get(['id', 'name']),
        ])->title('Mailbox');
    }
}
