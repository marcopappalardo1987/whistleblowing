<?php

namespace App\Http\Controllers;

use App\Models\DfsApiTask;
use App\Models\ScrapeListContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ApiDataForSEOSerpGoogleMaps extends Controller 
{
    /**
     * Invia un task all'API DataForSEO.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postTask(Request $request)
    {
        $status = config('services.dataforseo.environment');
        $userId = Auth::id() ?? null;

        // Formatta il payload come array di tasks
        $payload = [
            [  // Nota: il payload deve essere un array che contiene array di tasks
                "language_name" => $request->language_name,
                "keyword" => $request->keyword,
                "priority" => (int)$request->priority,
                "depth" => (int)$request->depth,
                "device" => 'desktop', // Mobile mode will get only 20 results
                "os" => 'windows',
                "search_this_area" => (bool)$request->has('search_this_area'),
                "search_places" => (bool)$request->has('search_places'),
                "postback_url" => route('scraper.google.maps.postback'),
                "postback_data" => 'advanced',
                "tag" => 'list_id:'.$request->list_id,
            ]
        ];

        if($request->has('location_coordinate') && $request->location_coordinate !== null){
            $payload[0]['location_coordinate'] = $request->location_coordinate;
        }elseif($request->has('location_name') && $request->location_name !== null){
            $payload[0]['location_name'] = $request->location_name;
        }elseif($request->has('location_code') && $request->location_code !== null){
            $payload[0]['location_code'] = $request->location_code;
        }else{
            $messages['error'] = __("Devi selezionare un luogo.");
            return redirect()->route('scraper.google.maps')->with($messages);
        }

        if($request->has('max_crawl_pages')){
            $payload[0]['max_crawl_pages'] = (int)$request->max_crawl_pages;
        }

        // Aggiungi i campi opzionali se presenti
        if ($request->filled('tag')) {
            $payload[0]['tag'] = $request->tag;
        }
        
        if ($request->filled('pingback_url')) {
            $payload[0]['pingback_url'] = $request->pingback_url;
        }

        $endpoint = "https://$status.dataforseo.com/v3/serp/google/maps/task_post";
        $method = 'post';

        dispatch(new \App\Jobs\DataForSEOApiCallJob($endpoint, $payload, $method, $userId));

        $messages['success'] = __("Task inviato con successo.");
        return redirect()->route('scraper.google.maps')->with($messages);
    }

    public function postback(Request $request)
    {
        $allowedIps = ['144.76.154.130', '144.76.153.113', '144.76.153.106', '94.130.155.89', '178.63.193.217', '94.130.93.29'];
        $requestIp = $request->ip();

        if(!in_array($requestIp, $allowedIps)){
            Log::info('IP non accettato', ['ip' => $requestIp]);
            return response()->json(['message' => 'IP non accettato'], 403);
        }

        $decodedRequest = gzdecode($request->getContent());
        $decodedArray = json_decode($decodedRequest, true); // Decode JSON to array
        $tasks = $decodedArray['tasks'];
        
        foreach($tasks as $task){
            try {
                $savedTasks = DfsApiTask::getTaskByTaskId($task['id']);
                
                if (!$savedTasks) {
                    Log::error('Task non trovato nel database', [
                        'task_id' => $task['id']
                    ]);
                    continue;
                }

                $result = DfsApiTask::createOrUpdateTask([
                    'task_id' => $task['id'],
                    'task_type' => $savedTasks->task_type,
                    'status_code' => $task['status_code'], 
                    'status_message' => $task['status_message'],
                    'user_id' => $savedTasks->user_id,
                    'scrape_list_id' => $savedTasks->scrape_list_id
                ]);

                foreach($task['result'] as $result){
                    foreach($result['items'] as $item){
                        $content = [
                            'company' => $item['title'],
                            'domain' => $item['domain'] ?? '',
                            'borough' => $item['address_info']['borough'] ?? '',
                            'address' => $item['address_info']['address'] ?? '',
                            'city' => $item['address_info']['city'] ?? '',
                            'post_code' => $item['address_info']['zip'] ?? '',
                            'region' => $item['address_info']['region'] ?? '',
                            'country_code' => $item['address_info']['country_code'] ?? '',
                            'phone' => $item['phone'] ?? '',
                            'categories' => $item['category'] ?? '',
                            'scrape_list_id' => $savedTasks->scrape_list_id
                        ];

                        $existingContent = ScrapeListContent::where('company', $content['company'])
                            ->where('domain', $content['domain'])
                            ->where('scrape_list_id', $content['scrape_list_id'])
                            ->first();
                            
                        if (!$existingContent) {
                            ScrapeListContent::createContent($content);
                        } else {
                            Log::info('Contenuto duplicato trovato', [
                                'company' => $content['company'],
                                'domain' => $content['domain'],
                                'scrape_list_id' => $content['scrape_list_id']
                            ]);
                        }
                    }
                }

                if (!$result) {
                    Log::error('Errore durante il salvataggio/aggiornamento del task', [
                        'task_id' => $task['id'],
                        'task_data' => $task
                    ]);
                }

            } catch (\Exception $e) {
                Log::error('Errore durante l\'elaborazione del task', [
                    'task_id' => $task['id'],
                    'error' => $e->getMessage(),
                    'stack_trace' => $e->getTraceAsString()
                ]);
            }
        }

    }

    public static function generateTaskFromResponse($response, $userId)
    {
        $responseArray = json_decode($response, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('JSON decode error', ['error' => json_last_error_msg(), 'response' => $response]);
            return;
        }

        if (!isset($responseArray['tasks'])) {
            Log::error('No tasks found in response', ['response' => $responseArray]);
            return;
        }

        $tasks = $responseArray['tasks'];
        foreach($tasks as $task){
            try {
                $tag = $task['data']['tag'] ?? null;
                
                // Extract list_id from tag string
                $listId = null;
                if ($tag && preg_match('/list_id:(\d+)/', $tag, $matches)) {
                    $listId = $matches[1];
                } else {
                    Log::warning('No list ID found in tag');
                }
                
                $data = [
                    'task_id' => $task['id'],
                    'task_type' => 'google_maps_scraping', 
                    'status_code' => $task['status_code'],
                    'status_message' => $task['status_message'],
                    'user_id' => $userId,
                    'scrape_list_id' => $listId
                ];
                
                DfsApiTask::createOrUpdateTask($data);
                
            } catch (\Exception $e) {
                Log::error('Error processing task', [
                    'error' => $e->getMessage(),
                    'task' => $task
                ]);
            }
        }

    }

}
