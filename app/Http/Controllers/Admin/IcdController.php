<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Icd;
use Illuminate\Http\Request;

class IcdController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:master|admin|doctor')->except(['index', 'show', 'edit', 'destroy', 'update', 'store', 'create']);
    }

    public function index()
    {
        $icds = Icd::paginate(20);

        return view('admin.icd.index', compact('icds'));

    }

    public function create(){
        return view('admin.icd.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);
        Icd::create([
            'title' => $validated['title'],
        ]);
        return back()->withSuccess('icd Created Successfully!');
    }

    public function edit(icd $icd)
    {
        return view('admin.icd.edit', compact('icd'));
    }

    public function update(Request $request, Icd $icd)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);
        $icd->update([
            'title' => $validated['title'],
        ]);
        return redirect()->back()->withSuccess('icd Update Successfully!');
    }

    public function destroy(Icd $icd)
    {
        if( $icd->delete() ){
            return back()->withSuccess("icd deleted successfully!");
        }
        return back()->withWarning("Failed to delete User!");
    }
}
