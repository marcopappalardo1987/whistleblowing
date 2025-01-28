<?php

namespace App\Http\Controllers;

use App\Jobs\ScrapeEmailsFromDomains;
use App\Models\ScrapeList;
use App\Models\ScrapeListContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class ScrapeListContentController extends Controller
{
    public function view($listId)
    { 
        $list = ScrapeList::getListByIdandUserId($listId, Auth::id());
        $listContent = ScrapeListContent::getContentByListId($listId);
        $numberOfContent = ScrapeListContent::getNumberOfContentByListId($listId);

        dd($numberOfContent);

        if ($list) {
            return view('scraper.list.content.view', compact('list', 'listContent', 'numberOfContent'));
        }

        return response()->json(['error' => 'Lista non trovata o accesso negato'], 404);
    }

    public function add($listId)
    {
        $list = ScrapeList::getListByIdandUserId($listId, Auth::id());

        if ($list) {
            return view('scraper.list.content.add', compact('list'));
        }

        return response()->json(['error' => 'Lista non trovata o accesso negato'], 404);
    }

    public function store(Request $request)
    {
        $request->validate([
            'scrape_list_id' => 'required|exists:scrape_lists,id',
            'company' => 'nullable|string|max:255',
            'domain' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
        ]);

        $content = new ScrapeListContent();
        $content->scrape_list_id = $request->scrape_list_id;
        $content->company = $request->input('company');
        $content->domain = $request->input('domain');
        $content->city = $request->input('city');
        $content->save();

        return redirect()
            ->route('scraper.list.content.view', ['list_id' => $request->scrape_list_id])
            ->with('success', 'Contenuto aggiunto con successo');
    }

    public function edit($contentId)
    {
        $content = ScrapeListContent::findOrFail($contentId);
        $list = ScrapeList::getListByIdandUserId($content->scrape_list_id, Auth::id());

        if ($list) {
            return view('scraper.list.content.edit', compact('content', 'list'));
        }

        return response()->json(['error' => 'Contenuto non trovato o accesso negato'], 404);
    }

    public function update(Request $request, $contentId)
    {
        $request->validate([
            'company' => 'nullable|string|max:255',
            'domain' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
        ]);

        $content = ScrapeListContent::findOrFail($contentId);
        $list = ScrapeList::getListByIdandUserId($content->scrape_list_id, Auth::id());

        if ($list) {
            $content->company = $request->input('company');
            $content->domain = $request->input('domain');
            $content->city = $request->input('city');
            $content->save();

            return redirect()
                ->route('scraper.list.content.view', ['list_id' => $content->scrape_list_id])
                ->with('success', 'Contenuto aggiornato con successo');
        }

        return response()->json(['error' => 'Contenuto non trovato o accesso negato'], 404);
    }

    public function delete($contentId)
    {
        $content = ScrapeListContent::findOrFail($contentId);
        $list = ScrapeList::getListByIdandUserId($content->scrape_list_id, Auth::id());

        if ($list) {
            $content->delete();

            return redirect()
                ->route('scraper.list.content.view', ['list_id' => $content->scrape_list_id])
                ->with('success', 'Contenuto eliminato con successo');
        }

        return response()->json(['error' => 'Contenuto non trovato o accesso negato'], 404);
    }

    public function bulkActions()
    {
        try {
            $postData = request()->all();
            Log::info('Bulk action received', ['postData' => $postData]);
            
            if (!isset($postData['action'])) {
                Log::error('No action specified in bulk request');
                return redirect()->back()->with('error', 'Nessuna azione specificata');
            }

            Log::info('Processing bulk action', ['action' => $postData['action']]);
            
            switch ($postData['action']) {
                case 'delete_all':
                    Log::info('Executing bulk delete action');
                    return $this->bulkDelete($postData);
                case 'scan_emails':
                    Log::info('Executing bulk email scraping action');
                    return $this->bulkEmailScraping($postData);
                default:
                    Log::warning('Invalid bulk action requested', ['action' => $postData['action']]);
                    return redirect()->back()->with('error', 'Azione non valida');
            }
        } catch (\Exception $e) {
            Log::error('Error in bulk actions', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Si è verificato un errore durante l\'elaborazione');
        }
    }

    public function bulkDelete($postData)
    {
        try {
            if (!isset($postData['selected_ids']) || empty($postData['selected_ids'])) {
                return redirect()->back()->with('error', 'Nessun elemento selezionato');
            }

            $deleted = ScrapeListContent::bulkDelete($postData['selected_ids']);
            if ($deleted) {
                return redirect()->route('scraper.list.content.view', ['list_id' => $postData['list_id']])
                    ->with('success', 'Contenuto eliminato con successo');
            } else {
                return redirect()->route('scraper.list.content.view', ['list_id' => $postData['list_id']])
                    ->with('error', 'Si è verificato un errore durante l\'eliminazione del contenuto');
            }
        } catch (\Exception $e) {
            Log::error('Error occurred while deleting content: ' . $e->getMessage());
            return redirect()->route('scraper.list.content.view', ['list_id' => $postData['list_id']]) 
                ->with('error', 'Si è verificato un errore durante l\'eliminazione del contenuto');
        }
    }

    public function bulkEmailScraping($postData)
    {
        Log::info('Starting bulk email scraping', ['postData' => $postData]);
        
        $domainsToScrape = [];
        
        foreach($postData['selected_ids'] as $contentId) {
            if (!empty($postData['domains'][$contentId])) {
                $domain = $postData['domains'][$contentId];
                $domainsToScrape[$contentId] = $domain;
                Log::info('Added domain for scraping', [
                    'contentId' => $contentId,
                    'domain' => $domain
                ]);
            } else {
                Log::warning('Empty domain found for content', ['contentId' => $contentId]);
            }
        }

        if (!empty($domainsToScrape)) {
            Log::info('Dispatching email scraping job', [
                'domains' => $domainsToScrape,
                'listId' => $postData['list_id']
            ]);
            
            ScrapeEmailsFromDomains::dispatch($domainsToScrape, $postData['list_id']);

            Log::info('Email scraping job dispatched successfully');
            return redirect()->route('scraper.list.content.view', ['list_id' => $postData['list_id']])
                ->with('success', 'Lo scraping delle email è stato avviato. Riceverai una notifica quando sarà completato.');
        }

        Log::warning('No valid domains found for scraping');
        return redirect()->route('scraper.list.content.view', ['list_id' => $postData['list_id']])
            ->with('error', 'Nessun dominio valido selezionato per lo scraping.');
    }

    /**
     * Get content by list ID and user ID
     *
     * @param int $listId
     * @param int $userId
     * @return \Illuminate\View\View
     */
    public function getContentByListIdAndUserId($listId, $userId)
    {
        $list = ScrapeList::getListByIdandUserId($listId, $userId);
        $listContent = ScrapeListContent::getContentByListId($listId);
        $numberOfContent = ScrapeListContent::getNumberOfContentByListId($listId);

        if ($list) {
            return view('scraper.list.content.view', compact('list', 'listContent', 'numberOfContent'));
        }

        return response()->json(['error' => 'Lista non trovata o accesso negato'], 404);
    }

    /**
     * Store or update content from edit form
     *
     * @param Request $request
     * @param int $listId
     * @param int|null $contentId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeFromForm(Request $request, $listId, $contentId = null)
    {
        $request->validate([
            'company' => 'nullable|string|max:255',
            'domain' => 'nullable|string|max:255',
            'borough' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255', 
            'city' => 'nullable|string|max:255',
            'post_code' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'country_code' => 'nullable|string|max:255',
            'email' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'categories' => 'nullable|string|max:255',
            'scrape_list_id' => 'required|exists:scrape_lists,id'
        ]);

        try {
            if ($contentId) {
                $content = ScrapeListContent::findOrFail($contentId);
                $list = ScrapeList::getListByIdandUserId($content->scrape_list_id, Auth::id());

                if (!$list) {
                    return redirect()->back()->with('error', 'Accesso negato');
                }
            } else {
                $content = new ScrapeListContent();
            }

            $content->scrape_list_id = $request->scrape_list_id;
            $content->company = $request->input('company');
            $content->domain = $request->input('domain');
            $content->borough = $request->input('borough');
            $content->address = $request->input('address');
            $content->city = $request->input('city');
            $content->post_code = $request->input('post_code');
            $content->region = $request->input('region');
            $content->country_code = $request->input('country_code');
            $content->email = $request->input('email');
            $content->phone = $request->input('phone');
            $content->categories = $request->input('categories');
            $content->save();

            return redirect()
                ->route('scraper.list.content.view', ['list_id' => $request->scrape_list_id])
                ->with('success', 'Contenuto salvato con successo');

        } catch (\Exception $e) {
            Log::error('Error saving content: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Si è verificato un errore durante il salvataggio')
                ->withInput();
        }
    }

    /**
     * Show the form for adding content to a list.
     *
     * @param int $listId
     * @param int $userId
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function addContentToList($listId, $userId)
    {
        $list = ScrapeList::getListByIdandUserId($listId, $userId);

        if ($list) {
            return view('scraper.list.content.add', compact('list'));
        }

        return response()->json(['error' => 'Lista non trovata o accesso negato'], 404);
    }

    public function exportCsv($listId)
    {
        $listContent = ScrapeListContent::getContentByListId($listId, 100000000);
        
        // Ottieni i dettagli della lista
        $list = ScrapeList::find($listId);
        if (!$list) {
            return redirect()->back()->with('error', 'Lista non trovata');
        }

        // Sanitizza il nome della lista per il nome del file
        $safeListName = preg_replace('/[^a-z0-9]+/', '-', strtolower($list->list_name));
        
        // Genera un nome file con nome lista, ID e timestamp
        $filename = sprintf(
            '%s_list-%d_%s.csv',
            $safeListName,
            $listId,
            date('Y-m-d_His')
        );

        // Definisci l'intestazione del CSV
        $csvHeader = [
            'id', 'company', 'domain', 'borough', 'address', 'city', 
            'post_code', 'region', 'country_code', 'email', 'phone', 
            'categories', 'scrape_list_id', 'created_at', 'updated_at'
        ];

        // Apri un buffer di output
        $output = fopen('php://temp', 'r+');

        // Scrivi l'intestazione nel CSV
        fputcsv($output, $csvHeader);

        // Scrivi i dati nel CSV
        foreach ($listContent as $content) {
            $contentArray = $content->toArray();
            
            // Ottieni l'array di email
            $emails = [];
            if (is_string($contentArray['email'])) {
                try {
                    $emails = unserialize($contentArray['email']);
                } catch (\Exception $e) {
                    $emails = [$contentArray['email']];
                }
            } elseif (is_array($contentArray['email'])) {
                $emails = $contentArray['email'];
            }

            // Salta se non ci sono email
            if (empty($emails)) {
                $rowData = [
                    $contentArray['id'],
                    $contentArray['company'],
                    $contentArray['domain'],
                    $contentArray['borough'],
                    $contentArray['address'],
                    $contentArray['city'],
                    $contentArray['post_code'],
                    $contentArray['region'],
                    $contentArray['country_code'],
                    '', // email vuota
                    $contentArray['phone'],
                    $contentArray['categories'],
                    $contentArray['scrape_list_id'],
                    $contentArray['created_at'],
                    $contentArray['updated_at']
                ];
                fputcsv($output, $rowData);
                continue;
            }

            // Per ogni email, crea una nuova riga con tutti gli altri dati duplicati
            foreach ($emails as $email) {
                $rowData = [
                    $contentArray['id'],
                    $contentArray['company'],
                    $contentArray['domain'],
                    $contentArray['borough'],
                    $contentArray['address'],
                    $contentArray['city'],
                    $contentArray['post_code'],
                    $contentArray['region'],
                    $contentArray['country_code'],
                    $email,
                    $contentArray['phone'],
                    $contentArray['categories'],
                    $contentArray['scrape_list_id'],
                    $contentArray['created_at'],
                    $contentArray['updated_at']
                ];
                fputcsv($output, $rowData);
            }
        }

        // Riavvolgi il buffer
        rewind($output);

        // Ottieni il contenuto del buffer
        $csvContent = stream_get_contents($output);

        // Chiudi il buffer di output
        fclose($output);

        // Restituisci il CSV come download
        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

} 