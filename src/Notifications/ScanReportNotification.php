<?php

namespace xqus\laraSec\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ScanReportNotification extends Notification
{
    public $alerts;
    public $updates;

    public function __construct(array $alerts, array $updates)
    {
        $this->alerts = $alerts;
        $this->updates = $updates;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
          ->subject('laraSec report')
          ->markdown('larasec::mail.scanReport', [
              'alerts'  => $this->alerts,
              'updates' => $this->updates,
          ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
