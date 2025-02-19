<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BranchController extends Controller
{
    public function add()
    {
        $maxBranches = Auth::user()->maxBranches();
        $branchesCount = Auth::user()->branchesCount();
        $canAddBranch = Auth::user()->canAddBranch();
        $branches = Branch::where('company_id', Auth::user()->id)->get();

        return view('branch.add', compact('maxBranches', 'branchesCount', 'canAddBranch', 'branches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'branch_name' => 'required|string|max:255',
        ]);

        $branch = new Branch();
        $branch->name = $request->branch_name;
        $branch->company_id = Auth::user()->id;
        $branch->save();

        return redirect()->route('branch.list')->with('success', 'Branch aggiunto con successo!');

        dd($request->all());
    }

    public function list()
    {
        $maxBranches = Auth::user()->maxBranches();
        $branchesCount = Auth::user()->branchesCount();
        $canAddBranch = Auth::user()->canAddBranch();
        $branches = Branch::where('company_id', Auth::user()->id)->get();
        return view('branch.list', compact('branches', 'maxBranches', 'branchesCount', 'canAddBranch'));
    }

    public function edit($id)
    {
        $branch = Branch::find($id);
        return view('branch.edit', compact('branch'));
    }

    public function update(Request $request, $id)
    {
        $branch = Branch::find($id);
        $branch->name = $request->branch_name;
        $branch->save();
        return redirect()->route('branch.list')->with('success', 'Branch modificato con successo!');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        if(Auth::user()->branchesCount() <= 1){
            return redirect()->route('branch.list')->with('error', 'Non puoi eliminare il tuo unico branch!');
        }
        $branch = Branch::find($id);
        $branch->delete();
        return redirect()->route('branch.list')->with('success', 'Branch eliminato con successo!');
    }
}
