<?php

namespace App\Livewire\Tickets;

use App\Models\Ticket;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class TicketShow extends Component
{
    public Ticket $ticket;
    public string $reply = '';

    public function mount(Ticket $ticket): void
    {
        $this->authorize('view', $ticket);
        $this->ticket = $ticket;
    }

    public function sendReply(): void
    {
        $this->authorize('reply', $this->ticket);
        $this->validate(['reply' => ['required', 'string', 'max:5000']]);

        $this->ticket->replies()->create([
            'user_id' => auth()->id(),
            'body'    => $this->reply,
        ]);

        // A customer reply reopens; an agent reply marks pending-on-customer
        $isAgent = auth()->user()->can('tickets.manage') && auth()->id() !== $this->ticket->user_id;
        if ($this->ticket->status !== 'resolved') {
            $this->ticket->update(['status' => $isAgent ? 'pending' : 'open']);
        }

        $this->reply = '';
        $this->ticket->refresh();
    }

    public function setStatus(string $status): void
    {
        $this->authorize('manage', $this->ticket);

        if (in_array($status, ['open', 'pending', 'resolved'], true)) {
            $this->ticket->update(['status' => $status]);
            activity('tickets')->causedBy(auth()->user())->performedOn($this->ticket)->log("Ticket marked {$status}");
        }
    }

    public function assignTo(?int $userId): void
    {
        $this->authorize('manage', $this->ticket);
        $this->ticket->update(['assigned_to' => $userId ?: null]);
    }

    public function render()
    {
        return view('livewire.tickets.ticket-show', [
            'agents' => User::permission('tickets.manage')->orderBy('name')->get(['id', 'name']),
        ])->title($this->ticket->reference);
    }
}
