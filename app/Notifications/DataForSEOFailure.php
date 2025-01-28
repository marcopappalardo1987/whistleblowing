<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class DataForSEOFailure extends Notification
{
    use Queueable;

    protected $endpoint;
    protected $status;
    protected $error;

    public function __construct($endpoint, $status, $error)
    {
        $this->endpoint = $endpoint;
        $this->status = $status;
        $this->error = $error;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Scraping fallito',
            'message' => "Scraping su Google Maps fallito per {$this->endpoint}. Status: {$this->status}. Error: {$this->error}",
            'type' => 'error'
        ];
    }
} 