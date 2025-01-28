<?php

namespace App\Http\Controllers;

use App\Models\Options;
use Illuminate\Container\Attributes\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log as FacadesLog;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;

class ApiDataForSEOControllerManager extends Controller
{
    public function view()
    {
        return view('api.settings.dataforseo');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'api_dataforseo_username' => 'required|string',
            'api_dataforseo_password' => 'required|string',
            'api_dataforseo_environment' => 'required|string',
        ]);

        $envFilePath = base_path('.env');
        if (file_exists($envFilePath)) {
            $envContent = file_get_contents($envFilePath);

            $envContent = str_replace(
                'DATAFORSEO_API_USERNAME="' . config('services.dataforseo.username') . '"',
                'DATAFORSEO_API_USERNAME="'. $validatedData['api_dataforseo_username'] .'"',
                $envContent
            );

            $envContent = str_replace(
                'DATAFORSEO_API_PASSWORD="' . config('services.dataforseo.password') . '"',
                'DATAFORSEO_API_PASSWORD="'. $validatedData['api_dataforseo_password'] .'"',
                $envContent
            );

            $envContent = str_replace(
                'DATAFORSEO_API_ENVIROMENT="' . config('services.dataforseo.environment') . '"',
                'DATAFORSEO_API_ENVIROMENT="'. $validatedData['api_dataforseo_environment'] .'"',
                $envContent
            );

            file_put_contents($envFilePath, $envContent);

            return redirect()->back()->with('success', 'Impostazioni aggiornate con successo.');
        } else {
            FacadesLog::error('.env file non trovato.');
            return redirect()->back()->with('error', 'Impossibile aggiornare le impostazioni: file .env non trovato.');
        }
    }

}
