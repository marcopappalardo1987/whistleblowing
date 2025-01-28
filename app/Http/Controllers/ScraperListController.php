<?php

namespace App\Http\Controllers;

use App\Models\ScrapeList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ScraperListController extends Controller
{
    public function store(Request $request)
    {
        $data['list_name'] = $request->list_name;
        $data['user_id'] = $request->user_id;

        $scrapeList = ScrapeList::createScrapeList($data);
        
        if ($scrapeList) {
            return redirect()->route('scraper.list.manage')->with('success', __('Lista aggiunta con successo'));
        } else {
            return redirect()->route('scraper.list.manage')->with('error', __('Errore durante l\'aggiunta della lista'));
        }
    }

    public static function getListByUserId($userId)
    {
        $lists = ScrapeList::getListsByUserId($userId);
        return view('scraper.list.manage', compact('lists'));
    }

    public static function getListById($listId)
    {
        $list = ScrapeList::getListByIdandUserId($listId, Auth::id());
        return view('scraper.list.view', compact('list'));
    }

    public static function editList($listId)
    {
        $list = ScrapeList::getListByIdandUserId($listId, Auth::id());
        return view('scraper.list.edit', compact('list'));
    }

    public static function updateList(Request $request)
    {
        unset($request['_token']);
        $data['list_name'] = $request->list_name;
        $data['user_id'] = $request->user_id;
        $data['list_id'] = $request->list_id;

        $list = ScrapeList::updateScrapeList($data['list_id'], $data);

        if ($list) {
            return redirect()->route('scraper.list.edit', $data['list_id'])->with('success', __('Lista aggiornata con successo'));
        } else {
            return redirect()->route('scraper.list.edit', $data['list_id'])->with('error', __('Errore durante l\'aggiornamento della lista'));
        }
    }

    public static function delete($listId)
    {
        try {
            $result = ScrapeList::deleteScrapeList($listId);
            
            if ($result) {
                return redirect()
                    ->route('scraper.list.manage')
                    ->with('success', __('Lista e contenuto associato eliminati con successo'));
            }
            
            return redirect()
                ->route('scraper.list.manage')
                ->with('error', __('Errore durante l\'eliminazione della lista'));
                
        } catch (\Exception $e) {
            Log::error('Error in delete list controller: ' . $e->getMessage());
            return redirect()
                ->route('scraper.list.manage')
                ->with('error', __('Si è verificato un errore durante l\'eliminazione della lista'));
        }
    }
}

