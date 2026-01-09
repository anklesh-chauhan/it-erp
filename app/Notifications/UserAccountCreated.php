<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class UserAccountCreated extends Notification
{
    use Queueable;

    public function __construct(
        public string $password
    ) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Login Credentials')
            ->greeting('Hello ' . $notifiable->name)
            ->line('Your login account has been created.')
            ->line('Email: ' . $notifiable->email)
            ->line('Temporary Password: ' . $this->password)
            ->line('Please change your password after first login.')
            ->action('Login', url('/login'))
            ->line('Thank you.');
    }
}
