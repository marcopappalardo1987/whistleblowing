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
            'view' => 'emails.company.welcome',
            'subject' => 'Benvenuto!'
        ],
        'admin_welcome' => [
            'view' => 'emails.company.admin-welcome',
            'subject' => 'Nuovo utente registrato'
        ],
        'company_data_updated' => [
            'view' => 'emails.company.data-updated',
            'subject' => 'Dati Aziendali Aggiornati'
        ],
        'investigator_invitation' => [
            'view' => 'emails.investigator.invitation',
            'subject' => 'Invito come Investigatore'
        ],
        'investigator_registration' => [
            'view' => 'emails.investigator.registration',
            'subject' => 'Registrazione completa come Investigatore'
        ],
        'reminder_5_days' => [
            'view' => 'emails.report.status.reminder_5_days',
            'subject' => 'Report in attesa di aggiornamento'
        ],
        'reminder_7_days' => [
            'view' => 'emails.report.status.reminder_7_days',
            'subject' => 'Report in attesa di aggiornamento'
        ],
        'reminder_85_days' => [
            'view' => 'emails.report.status.reminder_85_days',
            'subject' => 'Report in attesa di aggiornamento'
        ],
        'reminder_90_days' => [
            'view' => 'emails.report.status.reminder_90_days',
            'subject' => 'Report in attesa di aggiornamento'
        ],
        'new_report_notification' => [
            'view' => 'emails.report.new-report-notification',
            'subject' => 'Nuova segnalazione ricevuta'
        ],
        'report_reply_notification' => [
            'view' => 'emails.report.report-reply-notification',
            'subject' => 'Nuova risposta alla segnalazione'
        ],
        'welcome_affiliate' => [
            'view' => 'emails.affiliate.welcome-affiliate',
            'subject' => 'Benvenuto nel Programma di Affiliazione'
        ],
        'admin_welcome_affiliate' => [
            'view' => 'emails.affiliate.admin-welcome-affiliate',
            'subject' => 'Nuovo Affiliato Registrato'
        ],
        'payment_success' => [
            'view' => 'emails.payment.payment-success',
            'subject' => 'Conferma di Pagamento'
        ],
        'admin_payment_success' => [
            'view' => 'emails.payment.admin-payment-success',
            'subject' => 'Nuovo pagamento'
        ],
        'subscription_cancelled' => [
            'view' => 'emails.subscription.cancelled',
            'subject' => 'Ci dispiace vederti andare'
        ],
        'admin_subscription_cancelled' => [
            'view' => 'emails.subscription.admin-cancelled',
            'subject' => 'Abbonamento Cancellato'
        ]
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