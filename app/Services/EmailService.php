<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\GenericEmail;
use InvalidArgumentException;

class EmailService
{
    /**
     * Lista dei template email disponibili
     */
    protected const EMAIL_TEMPLATES = [
        'welcome' => [
            'view' => 'emails.welcome',
            'subject' => 'Benvenuto!'
        ],
        'company_data_updated' => [
            'view' => 'emails.company-data-updated',
            'subject' => 'Dati Aziendali Aggiornati'
        ],
        // Aggiungi altri template qui
    ];

    /**
     * Invia una email usando un template predefinito (in coda)
     *
     * @param string $templateName Nome del template da utilizzare
     * @param array $data Dati da passare al template
     * @param string|array $to Destinatario/i dell'email
     * @param array $options Opzioni aggiuntive (cc, bcc, etc.)
     * @return bool
     * @throws InvalidArgumentException
     */
    public function send(string $templateName, array $data, $to, array $options = []): bool
    {
        if (!array_key_exists($templateName, self::EMAIL_TEMPLATES)) {
            Log::error("Email Service: Template non trovato", [
                'template' => $templateName
            ]);
            throw new InvalidArgumentException("Template email '$templateName' non trovato.");
        }

        $template = self::EMAIL_TEMPLATES[$templateName];

        try {
            $mailable = new GenericEmail(
                view: $template['view'],
                subject: $template['subject'],
                data: $data
            );

            Mail::to($to)
                ->cc($options['cc'] ?? [])
                ->bcc($options['bcc'] ?? [])
                ->queue($mailable);

            return true;
        } catch (\Exception $e) {
            Log::error("Email Service: Errore", [
                'message' => $e->getMessage(),
                'template' => $templateName,
                'to' => $to,
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }
} 