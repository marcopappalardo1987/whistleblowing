<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DfsApiTask extends Model
{
    protected $table = 'dfs_api_task'; // Specify the table name if it differs from the default

    protected $fillable = ['task_id', 'task_type', 'status_code', 'status_message', 'user_id', 'scrape_list_id'];

    /**
     * Get all tasks.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function getAllTasks()
    {
        return self::all();
    }

    /**
     * Get a task by its ID.
     *
     * @param int $id
     * @return static|null
     */
    public static function getTaskById(int $id)
    {
        return self::find($id);
    }

    /**
     * Get a task by its task ID.
     *
     * @param string $taskId
     * @return static|null
     */
    public static function getTaskByTaskId(string $taskId)
    {
        return self::where('task_id', $taskId)->first();
    }

    /**
     * Create a new task or update the existing one if the task ID already exists.
     *
     * @param array $data
     * @return static
     */
    public static function createOrUpdateTask(array $data)
    {
        $task = self::where('task_id', $data['task_id'])->first();
        
        if ($task) {
            // Update existing task
            $task->update($data);
            return $task;
        } else {
            // Create new task
            return self::create($data);
        }
    }

    /**
     * Update an existing task.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public static function updateTask(int $id, array $data): bool
    {
        $task = self::find($id);
        if ($task) {
            return $task->update($data);
        }
        return false;
    }

    /**
     * Delete a task by its ID.
     *
     * @param int $id
     * @return bool|null
     */
    public static function deleteTask(int $id)
    {
        $task = self::find($id);
        return $task ? $task->delete() : null;
    }

    /**
     * Delete a task by its task ID.
     *
     * @param string $taskId
     * @return bool|null
     */
    public static function deleteTaskByTaskId(string $taskId)
    {
        $task = self::where('task_id', $taskId)->first();
        return $task ? $task->delete() : null;
    }

}
