<?php

namespace App\Livewire;

use App\Models\Message;
use App\Models\User;
use Livewire\Component;

class ChatBox extends Component
{
    public $receiverId;
    public $body;
    public $messages = [];

    public function mount($receiverId = null)
    {
        $this->receiverId = $receiverId ?? User::role('panel_user')->first()?->id;
        $this->loadMessages();
    }

    public function loadMessages()
    {
        $this->messages = Message::where(function ($q) {
            $q->where('sender_id', auth()->id())
                ->where('receiver_id', $this->receiverId);
        })->orWhere(function ($q) {
            $q->where('sender_id', $this->receiverId)
                ->where('receiver_id', auth()->id());
        })->orderBy('created_at')->get();
    }

    public function sendMessage()
    {
        Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $this->receiverId,
            'body' => $this->body,
        ]);

        $this->body = '';
        $this->loadMessages();
    }

    public function render()
    {
        return view('livewire.chat-box');
    }
}
