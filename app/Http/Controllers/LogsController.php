<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LogsController extends Controller
{
    
    public function view()
    {
        return view('logs.index');
    }

    public function laravel()
    {
        return view('logs.laravel');
    }

    public function clearLaravelLog()
    {
        $logFile = storage_path('logs/laravel.log');
        file_put_contents($logFile, '');
        return redirect()->route('logs.laravel')->with('success', 'Il file di log è stato cancellato con successo.');
    }

    public function worker()
    {
        return view('logs.worker');
    }

    public function clearWorkerLog()
    {
        $logFile = storage_path('logs/worker.log');
        file_put_contents($logFile, '');
        return redirect()->route('logs.worker')->with('success', 'Il file di log è stato cancellato con successo.');
    }

}
