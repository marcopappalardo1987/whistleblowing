<?php

namespace App\Http\Controllers;

use App\Models\AffiliateCommission;
use App\Models\WbReport;
use App\Models\CompanyData;
use App\Models\Investigator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $role = $user->getRoleNames()->first();
        
        switch ($role) {
            case 'investigatore':
                return $this->dashboardInvestigator();
            case 'azienda':
                return $this->dashboardCompany();
            case 'affiliato':
                return $this->dashboardAffiliate();
            case 'owner':
                return $this->dashboardOwner();
            default:
                return $this->dashboardAdmin();
        }
    }

    public function dashboardInvestigator()
    {
        $investigator = Investigator::where('investigator_id', Auth::user()->id)->first();
        
        $reports = WbReport::where('company_id', $investigator->company_id)
            ->where('branch_id', $investigator->branch_id)
            ->get();

        
        return view('investigator.index', compact('investigator', 'reports'));
    }

    public function dashboardCompany()
    {
        return view('dashboard');
    }

    public function dashboardAdmin()
    {
        return view('dashboard');
    }

    public function dashboardAffiliate()
    {
        $commissions = AffiliateCommission::all();
        return view('affiliate.dashboard', compact('commissions'));
    }

    public function dashboardOwner()
    {
        return view('dashboard');
    }
}
