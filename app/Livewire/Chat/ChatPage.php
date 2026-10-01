<?php

namespace App\Livewire\Chat;

use App\Models\ChatMessage;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.app')]
class ChatPage extends Component
{
    #[Url]
    public ?int $with = null;

    public string $message = '';
    public string $contactSearch = '';

    public function mount(): void
    {
        $this->with ??= User::where('id', '!=', auth()->id())->orderBy('name')->value('id');
    }

    public function openConversation(int $userId): void
    {
        $this->with = $userId;
        $this->markRead();
    }

    public function send(): void
    {
        $this->validate([
            'message' => ['required', 'string', 'max:2000'],
            'with'    => ['required', 'exists:users,id'],
        ]);

        ChatMessage::create([
            'sender_id'    => auth()->id(),
            'recipient_id' => $this->with,
            'body'         => $this->message,
        ]);

        $this->message = '';
        $this->dispatch('chat-scroll-bottom');
    }

    private function markRead(): void
    {
        if ($this->with) {
            ChatMessage::where('sender_id', $this->with)
                ->where('recipient_id', auth()->id())
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
        }
    }

    public function render()
    {
        $me = auth()->id();
        $this->markRead();

        $contacts = User::where('id', '!=', $me)
            ->when($this->contactSearch, fn ($q) => $q->where('name', 'like', "%{$this->contactSearch}%"))
            ->orderBy('name')
            ->get()
            ->map(function (User $user) use ($me) {
                $last = ChatMessage::between($me, $user->id)->latest()->first();
                $unread = ChatMessage::where('sender_id', $user->id)->where('recipient_id', $me)->whereNull('read_at')->count();
                $user->setAttribute('lastMessage', $last?->body);
                $user->setAttribute('lastAt', $last?->created_at);
                $user->setAttribute('unread', $unread);

                return $user;
            })
            ->sortByDesc(fn ($u) => $u->lastAt?->timestamp ?? 0)
            ->values();

        $thread = $this->with
            ? ChatMessage::between($me, $this->with)->with('sender')->oldest()->limit(200)->get()
            : collect();

        return view('livewire.chat.chat-page', [
            'contacts' => $contacts,
            'thread'   => $thread,
            'partner'  => $this->with ? User::find($this->with) : null,
        ])->title('Team Chat');
    }
}
