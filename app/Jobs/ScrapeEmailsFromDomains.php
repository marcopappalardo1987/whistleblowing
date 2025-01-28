<?php

namespace App\Jobs;

use App\Models\ScrapeListContent;
use App\Models\User;
use App\Notifications\ScrapeEmailsSuccess;
use App\Notifications\ScrapeEmailsFailure;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\Process\Exception\ProcessTimedOutException;

class ScrapeEmailsFromDomains implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 600; // 10 minuti in secondi

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    private const DELAY_BETWEEN_REQUESTS_MS = 200;

    protected $domains;
    protected $listId;
    protected $checkpoint;
    protected $userId;

    /**
     * Create a new job instance.
     */
    public function __construct(array $domains, int $listId, $checkpoint = null, int $userId = null)
    {
        $this->domains = $domains;
        $this->listId = $listId;
        $this->checkpoint = $checkpoint;
        $this->userId = $userId;
    }

    /**
     * Calculate the number of seconds to wait before retrying the job.
     *
     * @return array
     */
    public function backoff()
    {
        return [60, 300, 600]; // Attende 1 minuto, poi 5 minuti, poi 10 minuti tra i tentativi
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Filtra i domini già processati
        if ($this->checkpoint) {
            $this->domains = array_filter(
                $this->domains,
                function($domain, $contentId) {
                    return !isset($this->checkpoint['processed_domains'][$contentId]);
                },
                ARRAY_FILTER_USE_BOTH
            );
        } else {
            $this->checkpoint = [
                'processed_domains' => [],
                'current_domain' => null,
                'scanned_urls' => [],
                'found_emails' => []
            ];
        }

        foreach ($this->domains as $contentId => $domain) {
            try {
                Log::info("=== INIZIO SCRAPING DOMINIO: {$domain} ===");
                
                $this->checkpoint['current_domain'] = [
                    'domain' => $domain,
                    'content_id' => $contentId
                ];
                
                $emails = $this->scrapeEmailFromDomain(
                    $domain, 
                    $this->checkpoint['scanned_urls'][$domain] ?? [],
                    $this->checkpoint['found_emails'][$domain] ?? []
                );
                
                if ($emails) {
                    // Limita le email per rispettare VARCHAR(255)
                    $limitedEmails = $this->limitSerializedEmails($emails);
                    
                    // Serializza l'array di email limitato
                    $serializedEmails = serialize($limitedEmails);

                    // Aggiorna il contenuto con le email serializzate
                    ScrapeListContent::updateContent($contentId, [
                        'email' => $serializedEmails
                    ]);

                    // Log delle email memorizzate
                    Log::info("Email memorizzate per il dominio {$domain}: " . implode(', ', $limitedEmails));
                    if (count($limitedEmails) < count($emails)) {
                        Log::warning("Alcune email sono state omesse per rispettare il limite di VARCHAR(255). Memorizzate {$limitedEmails} su " . count($emails));
                    }

                    // Notifica successo
                    if ($this->userId) {
                        $user = User::find($this->userId);
                        $user->notify(new ScrapeEmailsSuccess($domain, count($limitedEmails)));
                    }
                }
 
                // Marca il dominio come completato
                $this->checkpoint['processed_domains'][$contentId] = true;
                unset($this->checkpoint['current_domain']);
                
                Log::info("=== FINE SCRAPING DOMINIO: {$domain} ===\n");
            } catch (\Exception $e) {
                if ($e instanceof ProcessTimedOutException || 
                    strpos($e->getMessage(), 'timeout') !== false) {
                    Log::warning("Timeout durante lo scraping di {$domain}, salvo il checkpoint e riprendo dopo");
                    
                    self::dispatch(
                        $this->domains, 
                        $this->listId, 
                        $this->checkpoint,
                        $this->userId
                    )->delay(now()->addMinutes(1));
                    
                    return;
                }

                // Notifica errore
                if ($this->userId) {
                    $user = User::find($this->userId);
                    $user->notify(new ScrapeEmailsFailure($domain, $e->getMessage()));
                }

                Log::error("Errore durante lo scraping del dominio {$domain}", [
                    'error' => $e->getMessage(),
                    'content_id' => $contentId
                ]);
            }
        }
    }

    private function scrapeEmailFromDomain($domain, $previouslyScannedUrls = [], $previousEmails = [])
    {
        try {
            Log::info("=== DETTAGLIO SCANSIONE ===");
            Log::info("Dominio target: {$domain}");
            
            $domain = $this->normalizeDomain($domain);
            if (!$domain) {
                Log::warning("Dominio non valido dopo la normalizzazione: {$domain}");
                return null;
            }

            $client = new Client([
                'timeout' => 10,
                'verify' => false,
                'http_errors' => false,
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
                ]
            ]);

            $emails = $previousEmails;
            $scannedUrls = $previouslyScannedUrls;
            $urlsToScan = [];
            $foundUrls = [];
            
            $homeUrl = "https://{$domain}";
            
            if (!in_array($homeUrl, $scannedUrls)) {
                Log::info("Scansione della home page: {$homeUrl}");
                $response = $client->get($homeUrl);
                
                if ($response->getStatusCode() === 200) {
                    $html = (string) $response->getBody();
                    
                    // Estrai email dalla home page
                    $foundEmails = $this->extractEmailsFromHtml($html);
                    if (!empty($foundEmails)) {
                        Log::info("Email trovate nella home page: " . count($foundEmails));
                        foreach ($foundEmails as $email) {
                            Log::info("- {$email}");
                        }
                        $emails = array_merge($emails, $foundEmails);
                    } else {
                        Log::info("Nessuna email trovata nella home page");
                    }
                    
                    // Estrai tutti gli URL dalla home page
                    preg_match_all('/<a[^>]+href=([\'"])(.*?)\1/i', $html, $matches);
                    if (!empty($matches[2])) {
                        Log::info("URL trovati nella home page: " . count($matches[2]));
                        foreach ($matches[2] as $url) {
                            $normalizedUrl = $this->normalizeLink($url, $domain, $homeUrl);
                            if ($normalizedUrl && !in_array($normalizedUrl, $foundUrls)) {
                                Log::info("- URL aggiunto alla coda: {$normalizedUrl}");
                                $foundUrls[] = $normalizedUrl;
                                $urlsToScan[] = $normalizedUrl;
                            }
                        }
                    }
                    
                    $scannedUrls[] = $homeUrl;
                } else {
                    Log::warning("Impossibile accedere alla home page. Status code: " . $response->getStatusCode());
                }
            }

            // Scansione ricorsiva degli URL trovati
            $maxUrls = 30; // Limite massimo di URL da scansionare
            $scannedCount = 0;

            Log::info("\n=== INIZIO SCANSIONE RICORSIVA ===");
            Log::info("URL da scansionare: " . count($urlsToScan));
            Log::info("Limite massimo pagine: {$maxUrls}");

            while (!empty($urlsToScan) && $scannedCount < $maxUrls) {
                $currentUrl = array_shift($urlsToScan);
                
                if (in_array($currentUrl, $scannedUrls)) {
                    Log::info("URL già scansionato, skip: {$currentUrl}");
                    continue;
                }

                try {
                    Log::info('Scansione pagina ' . ($scannedCount + 1) . '/' . $maxUrls . ': ' . $currentUrl);
                    $response = $client->get($currentUrl);
                    
                    if ($response->getStatusCode() === 200) {
                        $html = (string) $response->getBody();
                        
                        // Estrai email dalla pagina corrente
                        $foundEmails = $this->extractEmailsFromHtml($html);
                        if (!empty($foundEmails)) {
                            Log::info("Email trovate in questa pagina: " . count($foundEmails));
                            foreach ($foundEmails as $email) {
                                Log::info("- {$email}");
                            }
                            $emails = array_merge($emails, $foundEmails);
                        }
                        
                        // Estrai nuovi URL dalla pagina corrente
                        preg_match_all('/<a[^>]+href=([\'"])(.*?)\1/i', $html, $matches);
                        $newUrlsCount = 0;
                        if (!empty($matches[2])) {
                            foreach ($matches[2] as $url) {
                                $normalizedUrl = $this->normalizeLink($url, $domain, $currentUrl);
                                if ($normalizedUrl && !in_array($normalizedUrl, $foundUrls)) {
                                    $foundUrls[] = $normalizedUrl;
                                    $urlsToScan[] = $normalizedUrl;
                                    $newUrlsCount++;
                                }
                            }
                            Log::info("Nuovi URL trovati in questa pagina: {$newUrlsCount}");
                        }
                        
                        $scannedUrls[] = $currentUrl;
                        $scannedCount++;
                        
                        // Salva il progresso nel checkpoint
                        $this->checkpoint['scanned_urls'][$domain] = $scannedUrls;
                        $this->checkpoint['found_emails'][$domain] = $emails;
                        
                        usleep(self::DELAY_BETWEEN_REQUESTS_MS * 1000);
                    } else {
                        Log::warning("Stato HTTP non valido per {$currentUrl}: " . $response->getStatusCode());
                    }
                } catch (\Exception $e) {
                    Log::warning("Errore durante la scansione di {$currentUrl}: " . $e->getMessage());
                    continue;
                }
            }

            // Rimuovi duplicati e filtra email valide
            $emails = array_unique($emails);
            Log::info("\nEmail trovate (prima del filtraggio): " . count($emails));
            
            $emails = array_filter($emails, function($email) {
                return filter_var($email, FILTER_VALIDATE_EMAIL);
            });
            
            Log::info("\n=== RIEPILOGO SCANSIONE ===");
            Log::info("Dominio: {$domain}");
            Log::info("Pagine scansionate: " . count($scannedUrls));
            Log::info("URL totali trovati: " . count($foundUrls));
            Log::info("Email valide trovate: " . count($emails));
            
            if (!empty($emails)) {
                Log::info("\nElenco email valide trovate:");
                foreach ($emails as $index => $email) {
                    Log::info(($index + 1) . ". {$email}");
                }
            }
            
            return !empty($emails) ? $emails : null;

        } catch (\Exception $e) {
            Log::error("Errore nello scraping del dominio {$domain}: " . $e->getMessage());
            throw $e;
        }
    }

    private function scanPageAndGetLinks($client, $url, $domain, &$emails)
    {
        try {
            Log::info("Tentativo di accesso a: {$url}");
            $response = $client->get($url);
            
            if ($response->getStatusCode() !== 200) {
                Log::warning("Stato HTTP non valido per {$url}: " . $response->getStatusCode());
                return [];
            }

            Log::info("Pagina scaricata con successo: {$url}");
            $html = (string) $response->getBody();
            
            $foundEmails = $this->extractEmailsFromHtml($html);
            if (!empty($foundEmails)) {
                Log::info("Email trovate in {$url}: " . count($foundEmails));
                $emails = array_merge($emails, $foundEmails);
            } else {
                Log::info("Nessuna email trovata in {$url}");
            }

            $internalLinks = $this->extractInternalLinks($html, $domain, $url);
            Log::info("Link interni trovati in {$url}: " . count($internalLinks));
            
            return $internalLinks;
        } catch (RequestException $e) {
            Log::error("Errore durante l'accesso a {$url}: " . $e->getMessage());
            return [];
        }
    }

    private function extractInternalLinks($html, $domain, $baseUrl)
    {
        $internalLinks = [];
        
        preg_match_all('/<a[^>]+href=([\'"])(.*?)\1/i', $html, $matches);
        
        if (empty($matches[2])) {
            Log::info("Nessun link trovato in: {$baseUrl}");
            return [];
        }

        foreach ($matches[2] as $link) {
            $link = $this->normalizeLink($link, $domain, $baseUrl);
            
            if ($link && strpos($link, "https://{$domain}") === 0) {
                $internalLinks[] = $link;
            }
        }

        return array_unique($internalLinks);
    }

    private function normalizeLink($link, $domain, $baseUrl)
    {
        // Ignora link javascript: o mailto:
        if (preg_match('/^(javascript:|mailto:|tel:)/i', $link)) {
            return null;
        }

        // Ignora file di immagini e documenti
        if (preg_match('/\.(jpg|jpeg|png|gif|bmp|webp|avif|svg|eps|ai|pdf|doc|docx|xls|xlsx|ppt|pptx|zip|rar|mp3|mp4|avi|mov)$/i', $link)) {
            Log::info("Link ignorato (file non web): {$link}");
            return null;
        }

        // Rimuovi anchor e parametri query
        $link = preg_replace('/#.*$/', '', $link);
        $link = preg_replace('/\?.*$/', '', $link);
        
        // Se il link è relativo, convertilo in assoluto
        if (strpos($link, 'http') !== 0) {
            if (strpos($link, '/') === 0) {
                $link = "https://{$domain}" . $link;
            } else {
                $link = dirname($baseUrl) . '/' . $link;
            }
        }

        // Verifica che il link sia valido
        if (!filter_var($link, FILTER_VALIDATE_URL)) {
            return null;
        }

        // Estrai il dominio dal link
        $linkDomain = parse_url($link, PHP_URL_HOST);
        if (!$linkDomain) {
            return null;
        }

        // Rimuovi www. dal dominio per il confronto
        $linkDomain = preg_replace('/^www\./i', '', $linkDomain);
        $baseDomain = preg_replace('/^www\./i', '', $domain);

        // Verifica che il dominio sia lo stesso
        if ($linkDomain !== $baseDomain) {
            Log::info("Link ignorato (dominio diverso): {$link}");
            return null;
        }
        
        return $link;
    }

    private function normalizeDomain($domain)
    {
        // Rimuovi http:// o https:// se presenti
        $domain = preg_replace('#^https?://#', '', $domain);
        // Rimuovi www. se presente
        $domain = preg_replace('#^www\.#', '', $domain);
        // Rimuovi tutto dopo il primo /
        $domain = explode('/', $domain)[0];
        // Rimuovi spazi e converti in minuscolo
        return trim(strtolower($domain));
    }

    private function extractEmailsFromHtml($html)
    {
        $emails = [];
        
        // Pattern regex più stringente per le email
        $pattern = '/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/';
        
        // Cerca nel testo HTML
        preg_match_all($pattern, $html, $matches);
        
        // Cerca anche in attributi mailto:
        preg_match_all('/mailto:([^"\'>\s]+)/', $html, $mailtoMatches);
        
        $allEmails = [];
        if (!empty($matches[0])) {
            $allEmails = array_merge($allEmails, $matches[0]);
        }
        
        if (!empty($mailtoMatches[1])) {
            $allEmails = array_merge($allEmails, $mailtoMatches[1]);
        }

        // Pattern per identificare estensioni di file comuni
        $fileExtensionsPattern = '/\.(png|jpe?g|gif|bmp|webp|tiff|svg|ico|pdf|doc[x]?|xls[x]?|ppt[x]?|zip|rar|7z|tar|gz|mp[34]|wav|avi|mov|mp4|webm|mkv)$/i';

        // Array temporaneo per tenere traccia delle email già processate (case-insensitive)
        $processedEmails = [];

        // Filtra e valida le email
        foreach ($allEmails as $email) {
            // Rimuovi eventuali spazi o caratteri non validi e converti in minuscolo
            $email = strtolower(trim($email));
            
            // Salta se questa email (case-insensitive) è già stata processata
            if (in_array($email, $processedEmails)) {
                Log::info("Email duplicata (case-insensitive) scartata: {$email}");
                continue;
            }
            
            // Salta se sembra essere un file
            if (preg_match($fileExtensionsPattern, $email)) {
                Log::info("Email scartata (sembra un file): {$email}");
                continue;
            }

            // Salta email di esempio o test
            if (
                stripos($email, 'mydomain') !== false ||
                stripos($email, 'example') !== false ||
                stripos($email, 'yourdomain') !== false ||
                stripos($email, 'domain.com') !== false ||
                stripos($email, 'mysite') !== false ||
                stripos($email, 'sample') !== false ||
                stripos($email, 'test') !== false
            ) {
                Log::info("Email scartata (email di esempio): {$email}");
                continue;
            }

            // Verifica ulteriori pattern sospetti
            if (
                // Salta se contiene dimensioni di immagini (es: 500x383)
                preg_match('/\d+x\d+/', $email) ||
                // Salta se contiene @2x o @3x (comuni in immagini retina)
                preg_match('/@[23]x/', $email) ||
                // Salta se contiene timestamp nel nome
                preg_match('/^\d{8,}/', $email) ||
                // Salta se il nome è troppo lungo (probabilmente non è una email reale)
                strlen($email) > 100 ||
                // Salta se la parte locale contiene troppi caratteri esadecimali consecutivi
                preg_match('/[a-f0-9]{32,}/i', $email) ||
                // Salta se sembra un ID o token di servizio
                preg_match('/^[a-f0-9]{20,}@/i', $email) ||
                // Salta se il dominio sembra essere di servizi tecnici
                preg_match('/@(sentry\.io|logs\.|monitoring\.|metrics\.|trace\.|debug\.)/', $email) ||
                // Salta se la parte locale sembra essere un hash
                preg_match('/^[a-f0-9]{8,}@/i', $email) ||
                // Salta se contiene troppe cifre consecutive
                preg_match('/\d{10,}/', $email)
            ) {
                Log::info("Email scartata (pattern sospetto): {$email}");
                continue;
            }
            
            // Verifica che sia una email valida
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                // Verifica che il dominio abbia un record MX valido
                $domain = substr(strrchr($email, "@"), 1);
                if (checkdnsrr($domain, "MX")) {
                    $emails[] = $email;
                    $processedEmails[] = $email;
                    Log::info("Email valida trovata: {$email}");
                } else {
                    Log::info("Email scartata (dominio non valido): {$email}");
                }
            }
        }
        
        return array_unique($emails);
    }

    /**
     * Limita l'array di email per rispettare il limite VARCHAR(255)
     *
     * @param array $emails
     * @return array
     */
    private function limitSerializedEmails(array $emails): array
    {
        $maxLength = 255;
        $limitedEmails = [];
        $currentLength = 0;
        
        // Calcola la lunghezza base della serializzazione di un array vuoto
        $baseLength = strlen(serialize([]));
        $currentLength = $baseLength;

        foreach ($emails as $email) {
            // Calcola quanto spazio occuperebbe questa email nell'array serializzato
            $testArray = $limitedEmails;
            $testArray[] = $email;
            $newLength = strlen(serialize($testArray));
            
            // Se aggiungendo questa email superiamo il limite, fermiamoci
            if ($newLength > $maxLength) {
                Log::info("Raggiunto limite VARCHAR(255). Email rimanenti omesse.");
                break;
            }
            
            $limitedEmails[] = $email;
            $currentLength = $newLength;
        }

        return $limitedEmails;
    }
} 