<?php

namespace App\Jobs;

use App\Http\Controllers\ApiDataForSEOSerpGoogleMaps;
use App\Models\User;
use App\Notifications\DataForSEOSuccess;
use App\Notifications\DataForSEOFailure;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DataForSEOApiCallJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $endpoint;
    protected $payload;
    protected $method;
    protected $timeout;
    protected $userId;

    /**
     * Create a new job instance.
     */
    public function __construct(string $endpoint, array $payload, string $method, int $userId, int $timeout = 30)
    {
        $this->endpoint = $endpoint;
        $this->payload = $payload;
        $this->method = $method;
        $this->timeout = $timeout;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Eseguire la chiamata all'API
            $response = Http::withBasicAuth(
                config('services.dataforseo.username'),
                config('services.dataforseo.password')
            )
            ->timeout($this->timeout)
            ->{$this->method}($this->endpoint, $this->payload);

            if ($response->failed()) {
                Log::error('API call failed', [
                    'endpoint' => $this->endpoint,
                    'response' => $response->body(),
                    'status' => $response->status()
                ]);

                // Notifica errore
                if ($this->userId) {
                    $user = User::find($this->userId);
                    $user->notify(new DataForSEOFailure(
                        $this->endpoint,
                        $response->status(),
                        $response->body()
                    ));
                }
            } else {
                if(str_contains($this->endpoint, '/v3/serp/google/maps/task_post')) {
                    ApiDataForSEOSerpGoogleMaps::generateTaskFromResponse($response, $this->userId);
                    
                    // Notifica successo
                    if ($this->userId) {
                        $user = User::find($this->userId);
                        $user->notify(new DataForSEOSuccess(
                            $this->endpoint,
                            'Task creato con successo'
                        ));
                    }
                }
            }
        } catch (Exception $e) {
            // Gestione delle eccezioni in caso di errori di connessione o altri problemi
            Log::error('API call exception', [
                'endpoint' => $this->endpoint,
                'error' => $e->getMessage()
            ]);

            // Notifica errore
            if ($this->userId) {
                $user = User::find($this->userId);
                $user->notify(new DataForSEOFailure(
                    $this->endpoint,
                    'Exception',
                    $e->getMessage()
                ));
            }
        }
    }
}
