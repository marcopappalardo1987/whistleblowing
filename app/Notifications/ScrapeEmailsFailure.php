<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class ScrapeEmailsFailure extends Notification
{
    use Queueable;

    protected $domain;
    protected $error;

    public function __construct($domain, $error)
    {
        $this->domain = $domain;
        $this->error = $error;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Errore nello scraping email',
            'message' => "Si è verificato un errore durante lo scraping di {$this->domain}: {$this->error}",
            'type' => 'error'
        ];
    }
} 