<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        try {
            $availableLocales = ['en', 'it', 'es', 'fr']; // Lingue supportate
            $locale = $request->segment(1); // Ottieni la prima parte dell'URL
            
            // Se la lingua non è valida, imposta la lingua predefinita
            if(in_array($request->get('locale'), $availableLocales)){
                $locale = $request->get('locale');
            }elseif(!in_array($locale, $availableLocales)) { 
                if (isset($_COOKIE['wb_locale']) && in_array($_COOKIE['wb_locale'], $availableLocales)) {
                    $locale = $_COOKIE['wb_locale'];
                } else {
                    $locale = 'it'; // Lingua predefinita
                }
            }
            
            // Imposta la lingua dell'applicazione
            App::setLocale($locale);
            Session::put('locale', $locale);
            // Memorizza la lingua nei cookie per 1 anno
            setcookie('wb_locale', $locale, time() + (365 * 24 * 60 * 60), "/"); // 365 giorni = 1 anno

            return $next($request);

        } catch (\Exception $e) {
            Log::error('Errore nel middleware SetLocale: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            // Imposta la lingua predefinita in caso di errore
            App::setLocale('it');
            return $next($request);
        }
    }

}
