<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ChatMessageNotification extends Notification
{
    private $message;
    private $user;

    public function __construct($message, $user)
    {
        $this->message = $message;
        $this->user = $user;
    }

    public function via($notifiable)
    {
        return ['database']; // Уведомление будет храниться в базе данных
    }

    public function toDatabase($notifiable)
    {
        return [
            'user_id' => $this->user->id,
            'message' => $this->message,
            'chat_id' => $this->message->chat_id,
        ];
    }
}
