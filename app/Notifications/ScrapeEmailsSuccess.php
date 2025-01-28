<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class ScrapeEmailsSuccess extends Notification
{
    use Queueable;

    protected $domain;
    protected $emailsCount;

    public function __construct($domain, $emailsCount)
    {
        $this->domain = $domain;
        $this->emailsCount = $emailsCount;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Scraping email completato',
            'message' => "Lo scraping di {$this->domain} è stato completato. Trovate {$this->emailsCount} email.",
            'type' => 'success'
        ];
    }
} 