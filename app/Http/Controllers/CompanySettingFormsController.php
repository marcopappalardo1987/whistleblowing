<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WbFormBuilder;
use App\Models\WbUserFormsBuilder;
use Illuminate\Support\Facades\Auth;

class CompanySettingFormsController extends Controller
{
    public function showRelatedForms()
    {
        // Get all users with the 'owner' role
        $ownerUsers = \App\Models\User::whereHas('roles', function($query) {
            $query->where('name', 'owner');
        })->pluck('id')->toArray();

        $forms_notice = WbFormBuilder::where(function($query) use ($ownerUsers) {
                $query->whereIn('user_id', $ownerUsers)
                      ->orWhere('user_id', Auth::id());
            })
            ->where('location', 'notice')
            ->get();

        
        $forms_appointment = WbFormBuilder::where(function($query) use ($ownerUsers) {
                $query->whereIn('user_id', $ownerUsers)
                      ->orWhere('user_id', Auth::id());
            })
            ->where('location', 'appointment')
            ->get();

        $selectedNoticeId = WbUserFormsBuilder::where('user_id', Auth::id())
            ->where('location', 'notice')
            ->value('wb_form_builder_id');

        $selectedAppointmentId = WbUserFormsBuilder::where('user_id', Auth::id())   
            ->where('location', 'appointment')
            ->value('wb_form_builder_id');

        return view('company.users-forms-relations', compact('forms_notice', 'forms_appointment', 'selectedNoticeId', 'selectedAppointmentId'));
    }

    public function storeRelatedForms(Request $request)
    {
        $request->validate([
            'wb_form_notice_id' => 'nullable|integer',
            'wb_form_appointment_id' => 'nullable|integer',
        ]);

        $user_id = Auth::id();

        if($request->wb_form_notice_id) {
            $data['notice'] = $request->wb_form_notice_id;
        }
        if($request->wb_form_appointment_id) {
            $data['appointment'] = $request->wb_form_appointment_id;
        }

        foreach ($data as $key => $value) {
            WbUserFormsBuilder::create([
                'user_id' => $user_id,
                'location' => $key,
                'wb_form_builder_id' => $value,
            ]);
        }

        return redirect()->route('company.users-forms-relations')->with('success', 'Form associati con successo!');
    }
}

