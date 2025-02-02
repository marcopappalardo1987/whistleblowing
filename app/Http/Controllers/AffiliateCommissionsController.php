<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AffiliateCommission;

class AffiliateCommissionsController extends Controller
{
    public function index()
    {
        $commissions = AffiliateCommission::orderBy('level', 'asc')->get();
        return view('affiliate-commissions.index', compact('commissions'));
    }

    public function create()
    {
        return view('affiliate-commissions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'level' => 'required|integer|min:0',
            'commission' => 'required|numeric|min:0|max:99999999.99'
        ]);

        // Utilizza il metodo del modello per gestire la creazione o l'aggiornamento
        AffiliateCommission::createOrUpdateCommission($validated);

        return redirect()->route('affiliate.settings.commissions')
            ->with('success', 'Commissione affiliato creata con successo.');
    }

    public function show()
    {
        $affiliateCommission = AffiliateCommission::all();
        return view('affiliate.commissions', compact('affiliateCommission'));
    }

    public function edit($id)
    {
        $affiliateCommission = AffiliateCommission::getCommissionById($id);
        return view('affiliate.edit-commission', compact('affiliateCommission'));
    }

    public function update()
    {
        
    }

    public function destroy($id)
    {
        $deleted = AffiliateCommission::deleteCommissionById($id);

        if ($deleted) {
            return redirect()->route('affiliate.settings.commissions')
                ->with('success', 'Commissione affiliato eliminata con successo.');
        } else {
            return redirect()->route('affiliate.settings.commissions')
                ->with('error', 'Commissione affiliato non trovata.');
        }
    }
}
