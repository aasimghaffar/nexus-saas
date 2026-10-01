<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    public function view(User $user, Ticket $ticket): bool
    {
        return $user->id === $ticket->user_id || $user->can('tickets.manage');
    }

    public function reply(User $user, Ticket $ticket): bool
    {
        return $this->view($user, $ticket);
    }

    public function manage(User $user, Ticket $ticket): bool
    {
        return $user->can('tickets.manage');
    }
}
