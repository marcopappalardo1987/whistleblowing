<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class ScrapeListContent extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'scrape_list_content';

    protected $fillable = [
        'scrape_list_id',
        'company',
        'domain',
        'city',
        'email'
    ];

    /**
     * Get the emails attribute.
     *
     * @param  string  $value
     * @return array
     */
    public function getEmailAttribute($value)
    {
        if (empty($value)) {
            return [];
        }

        try {
            // Prova a deserializzare
            if (is_string($value)) {
                $unserialized = @unserialize($value);
                if ($unserialized !== false) {
                    return $unserialized;
                }
            }

            // Se è già un array, restituiscilo
            if (is_array($value)) {
                return $value;
            }

            // Se è una singola email, restituiscila in un array
            if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
                return [$value];
            }

            // In caso di fallimento, restituisci array vuoto
            return [];
        } catch (Exception $e) {
            Log::error('Email attribute error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Set the emails attribute.
     *
     * @param  mixed  $value
     * @return void
     */
    public function setEmailAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['email'] = serialize([]);
            return;
        }

        // Se è una stringa singola (email)
        if (is_string($value)) {
            // Se sembra già una stringa serializzata, non ri-serializzare
            if (preg_match('/^a:\d+:{.*}$/', $value)) {
                $this->attributes['email'] = $value;
                return;
            }
            // Altrimenti, converti in array e serializza
            $this->attributes['email'] = serialize([$value]);
            return;
        }

        // Se è un array, serializzalo
        if (is_array($value)) {
            $this->attributes['email'] = serialize($value);
            return;
        }

        // In altri casi, salva array vuoto serializzato
        $this->attributes['email'] = serialize([]);
    }

    /**
     * Get content by list ID and user ID
     *
     * @param int $listId
     * @param int $userId
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public static function getContentByListIdAndUserId($listId, $userId, $paginate = 100)
    {
        return self::where('scrape_list_id', $listId)
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate($paginate);
    }

    /**
     * Update content by ID
     */
    public static function updateContent($id, array $data)
    {
        return self::where('id', $id)->update($data);
    }

    /**
     * Get content by list ID
     */
    public static function getContentByListId($listId, $paginate = 100)
    {
        return self::where('scrape_list_id', $listId)
            ->orderBy('created_at', 'desc')
            ->paginate($paginate);
    }

    public static function getNumberOfContentByListId($listId)
    {
        return self::where('scrape_list_id', $listId)->count();
    }

    /**
     * Relationship with ScrapeList
     */
    public function scrapeList()
    {
        return $this->belongsTo(ScrapeList::class, 'scrape_list_id');
    }

    /**
     * Bulk delete content by IDs
     *
     * @param array $ids
     * @return bool
     */
    public static function bulkDelete(array $ids)
    {
        try {
            return self::whereIn('id', $ids)->delete();
        } catch (\Exception $e) {
            Log::error('Error bulk deleting content: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Create a new content entry
     *
     * @param array $data
     * @return ScrapeListContent|false
     */
    public static function createContent(array $data)
    {
        try {
            $content = new self();
            $content->scrape_list_id = $data['scrape_list_id'];
            $content->company = $data['company'] ?? null;
            $content->domain = $data['domain'] ?? null;
            $content->borough = $data['borough'] ?? null;
            $content->address = $data['address'] ?? null;
            $content->city = $data['city'] ?? null;
            $content->post_code = $data['post_code'] ?? null;
            $content->region = $data['region'] ?? null;
            $content->country_code = $data['country_code'] ?? null;
            $content->email = $data['email'] ?? null;
            $content->phone = $data['phone'] ?? null;
            $content->categories = $data['categories'] ?? null;
            
            $content->save();
            
            return $content;
        } catch (\Exception $e) {
            Log::error('Error creating content: ' . $e->getMessage());
            return false;
        }
    }
} 