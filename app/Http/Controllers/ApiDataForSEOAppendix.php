<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiDataForSEOAppendix extends Controller
{
    public function getAppendixErrors(Request $request)
    {
        $login = config('services.dataforseo.username'); // Sostituisci con le tue credenziali
        $password = config('services.dataforseo.password'); // Sostituisci con le tue credenziali
        $cred = base64_encode("{$login}:{$password}");

        $limit = $request->input('limit', 10); // Limite predefinito a 10
        $offset = $request->input('offset', 0); // Offset predefinito a 0

        $payload = [
            [
                "limit" => $limit,
                "offset" => $offset,
            ]
        ];

        $response = Http::withHeaders([
            'Authorization' => "Basic {$cred}",
            'Content-Type' => 'application/json',
        ])->post('https://api.dataforseo.com/v3/dataforseo_labs/errors', $payload);

        if ($response->successful()) {
            $errors = $response->json();
            return view('logs.DataForSEOErrors', ['errors' => $errors]);
        } else {
            return response()->json(['error' => 'Errore nella chiamata API'], $response->status());
        }
    }
}
