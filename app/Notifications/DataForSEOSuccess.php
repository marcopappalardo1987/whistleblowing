<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class DataForSEOSuccess extends Notification
{
    use Queueable;

    protected $endpoint;
    protected $message;

    public function __construct($endpoint, $message)
    {
        $this->endpoint = $endpoint;
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => "Scraping completato con successo",
            'message' => "Scraping su Google Maps completato con successo per {$this->endpoint}: {$this->message}",
            'type' => 'success'
        ];
    }
} 