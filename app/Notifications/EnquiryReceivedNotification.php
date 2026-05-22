<?php

namespace App\Notifications;

use App\Models\Enquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EnquiryReceivedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Enquiry $enquiry) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('New contact enquiry from '.$this->enquiry->name)
            ->greeting('New enquiry received')
            ->line('**Name:** '.$this->enquiry->name)
            ->line('**Email:** '.$this->enquiry->email);

        if ($this->enquiry->service) {
            $mail->line('**Service:** '.$this->enquiry->service.($this->enquiry->variant ? ' ('.$this->enquiry->variant.')' : ''));
        }

        if ($this->enquiry->tier) {
            $mail->line('**Package:** '.$this->enquiry->tier);
        }

        if ($this->enquiry->intent) {
            $mail->line('**Intent:** '.$this->enquiry->intent);
        }

        if ($this->enquiry->estimate_summary) {
            $mail->line('**Estimate:** '.$this->enquiry->estimate_summary);
        }

        return $mail
            ->line('**Message:**')
            ->line($this->enquiry->message)
            ->action('View in admin', url('/admin/enquiries'));
    }
}
