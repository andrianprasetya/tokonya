<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerificationUserNotify  extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var
     */
    private $account;

    public function __construct($account)
    {
        $this->account = $account;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $user = $this->account;

        return (new MailMessage)
            ->subject(config('app.name') . ' - ' . trans('notifications.subject.verification-customer'))
            ->view('mail.verification-customer', compact('user'));
    }
}
