<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\AffiliateEarning;

class AffiliateEarningController extends Controller
{
    public function show(Request $request)
    {
        $query = AffiliateEarning::where('affiliate_id', auth()->user()->id);

        // Filtraggio per utente
        if ($request->has('filter_user')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->input('filter_user') . '%');
            });
        }

        // Filtraggio per range di date
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [$request->input('start_date') . ' 00:00:00', $request->input('end_date') . ' 23:59:59']);
        } else {
            // Se le date non sono selezionate, seleziona tutto il mese corrente
            $startOfMonth = now()->startOfMonth()->toDateTimeString();
            $endOfMonth = now()->endOfMonth()->toDateTimeString();
            $query->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
        }

        // Ordinamento
        $sortField = $request->input('sort_field', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        if ($sortField !== 'user.name') { // Escludi il nome dell'azienda
            $query->orderBy($sortField, $sortOrder);
        }

        $earnings = $query->paginate(10);
        $totalEarnings = $this->totalEarnings($request);

        return view('affiliate.private-area.earnings', compact('earnings', 'totalEarnings'));
    }

    public function totalEarnings(Request $request)
    {
        $query = AffiliateEarning::where('affiliate_id', auth()->user()->id);

        // Filtraggio per utente
        if ($request->has('filter_user')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->input('filter_user') . '%');
            });
        }

        // Filtraggio per range di date
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [$request->input('start_date') . ' 00:00:00', $request->input('end_date') . ' 23:59:59']);
        } else {
            // Se le date non sono selezionate, seleziona tutto il mese corrente
            $startOfMonth = now()->startOfMonth()->toDateTimeString();
            $endOfMonth = now()->endOfMonth()->toDateTimeString();
            $query->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
        }

        // Calcolo della somma dei guadagni
        $total = $query->sum('commission');

        return $total;
    }

}
