<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;

class Options extends Model
{
    protected $table = 'options'; // Specify the table name if it differs from the default

    // Add option_name to fillable property to allow mass assignment
    protected $fillable = ['option_name', 'option_value'];

    /**
     * Get the option by name.
     *
     * @param string $name
     * @return mixed
     */
    public static function getOptionByName(string $name)
    {
        return self::where('option_name', $name)->first();
    }

    /**
     * Get the option value by name.
     *
     * @param string $name
     * @return mixed
     */
    public static function getOptionValueByName(string $name)
    {
        $option = self::where('option_name', $name)->first();
        return $option ? $option->option_value : null;
    }

    public static function setOptionByName(string $name, string $value): bool
    {
        $option = self::where('option_name', $name)->first();
    
        if ($option) {
            // Update existing option
            $option->option_value = $value;
            return $option->save() ? true : false; // Ensure return type is bool
        } else {
            // Create new option
            return self::create([
                'option_name' => $name,
                'option_value' => $value,
            ]) ? true : false; // Ensure return type is bool
        }
    }

    /**
     * Delete the option by name.
     *
     * @param string $name
     * @return bool
     */
    public static function deleteOptionByName(string $name): bool
    {
        return self::where('option_name', $name)->delete() > 0; // Return true if any rows were deleted
    }
}
