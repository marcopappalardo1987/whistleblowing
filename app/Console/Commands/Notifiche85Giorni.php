<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\WbReport;
use App\Models\Investigator;
use App\Services\EmailService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class Notifiche85Giorni extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:notifiche85-giorni';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $emailService;

    // Status per la notifica a 85 giorni
    private $notify_85_days_statuses = [
        'submitted',
        'read',
        'replied',
        'user_replied',
        'waiting_for_information',
        'expiring',
        'expired',
        'reopened',
    ];

    public function __construct(EmailService $emailService)
    {
        parent::__construct();
        $this->emailService = $emailService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $fiveDaysAgo = Carbon::now()->subDays(85);
        
        $query = WbReport::
            whereIn('status', $this->notify_85_days_statuses)
            ->whereHas('forms', function($query) use ($fiveDaysAgo) {
                $query->whereDate('created_at', '=', $fiveDaysAgo);
            });

        $reports = $query->get();

        if($reports->count() > 0){
            foreach ($reports as $report) {
                $daysPassed = 85;

                // Trova gli investigatori associati
                $investigators = Investigator::where('branch_id', $report->branch_id)
                                            ->where('status', 'active')
                                            ->get();

                // Invio notifica a 85 giorni
                $this->reportConfermaRicezioneScaduta($report, $daysPassed, $report->created_at->format('d/m/Y H:i'), $investigators);

            }
        }
    
        $this->info('Report status update completed');
    }

    public function reportConfermaRicezioneScaduta($report, $daysPassed, $lastUpdate, $investigators)
    {
        if (!in_array($report->status, $this->notify_85_days_statuses)) {
            return;
        }

        $emailSent = $this->sendEmailStatus($investigators, $report, 'reminder_85_days', $daysPassed, $lastUpdate);
        
        if (!$emailSent) {
            Log::info("Email notification failed to send for Report #{$report->id} (85 days reminder)");
        }else{
            $this->info("Email notification sent to investigators for Report #{$report->id} (85 days reminder)");
        }
        
        $report->status = 'expiring';
        $saveSuccess = $report->save();
        if($saveSuccess){
            $this->info("Report #{$report->id} status updated to 'expiring'");
        }else{
            Log::error("Failed to update status for Report #{$report->id} to 'expiring'");
        }
    }

    public function sendEmailStatus($investigators, $report, $emailTemplate, $daysPassed, $lastUpdate){
        $emailSent = false; // Initialize a variable to track if any email was sent
        foreach ($investigators as $investigator) {
            try {
                // Prepara i dati per l'email
                $emailData = [
                    'report_id' => $report->id,
                    'investigator_name' => $investigator->name,
                    'days_inactive' => $daysPassed,
                    'last_update' => $lastUpdate
                ];

                // Invia l'email
                $this->emailService->send(
                    $emailTemplate,
                    $emailData,
                    $investigator->email
                );

                $emailSent = true; // Set to true if email is sent successfully

            } catch (\Exception $e) {
                Log::error("Failed to send email notification for Report #{$report->id} to {$investigator->email}: " . $e->getMessage());
            }
        }
        return $emailSent; // Return true if at least one email was sent, false otherwise
    }
}
