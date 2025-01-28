<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class ScrapeList extends Model
{
    protected $table = 'scrape_lists'; // Specify the table name if it differs from the default

    protected $fillable = ['list_name', 'user_id']; // Allow mass assignment for these fields

    public $timestamps = false; // Disables automatic timestamps

    /**
     * Get all scrape lists.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function getAllScrapeLists()
    {
        return self::all();
    }

    /**
     * Get a scrape list by its ID.
     *
     * @param int $id
     * @return static|null
     */
    public static function getScrapeListById(int $id)
    {
        return self::find($id);
    }

    /**
     * Get all scrape lists for a specific user by their user ID.
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function getListsByUserId($userId)
    {
        return self::where('user_id', $userId)->get();
    }

    /**
     * Create a new scrape list.
     *
     * @param array $data
     * @return static|null
     */
    public static function createScrapeList(array $data)
    {
        try {
            return self::create($data);
        } catch (\Exception $e) {
            Log::error('Error creating scrape list: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Update an existing scrape list.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public static function updateScrapeList(int $id, array $data): bool
    {
        $scrapeList = self::find($id);
        if ($scrapeList) {
            return $scrapeList->update($data);
        }
        return false;
    }

    /**
     * Delete a scrape list by its ID.
     *
     * @param int $id
     * @return bool|null
     */
    public static function deleteScrapeList(int $id)
    {
        try {
            $scrapeList = self::find($id);
            if (!$scrapeList) {
                return null;
            }

            $userRole = null;
            if (Auth::check()) {
                $userRole = Auth::user()->hasRole('admin');
            }

            if ($scrapeList->user_id == Auth::id() || $userRole) {
                // Elimina prima tutto il contenuto associato alla lista
                ScrapeListContent::where('scrape_list_id', $id)->delete();
                
                // Poi elimina la lista
                return $scrapeList->delete();
            }
            
            return null;
        } catch (\Exception $e) {
            Log::error('Error deleting scrape list: ' . $e->getMessage());
            return null;
        }
    }

    public static function getListByIdandUserId($listId, $userId)
    {
        $list = self::where('id', $listId)->where('user_id', $userId)->first();
        return $list ?? null;
    }

    
}
