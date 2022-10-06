<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Advice;
use App\Models\PatientHistory;
use Illuminate\Http\Request;

class AdviceController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:master|admin|doctor')->except(['index', 'show', 'edit', 'destroy', 'update', 'store', 'create']);
    }

    public function index()
    {
        $advices = Advice::paginate(20);
        return view('admin.advice.index', compact('advices'));

    }

    public function create(){
        return view('admin.advice.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);
        Advice::create([
            'title' => $validated['title'],
        ]);
        return back()->withSuccess('Advice Created Successfully!');
    }

    public function edit(Advice $advice)
    {
        return view('admin.advice.edit', compact('advice'));
    }

    public function update(Request $request, Advice $advice)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);
        $advice->update([
            'title' => $validated['title'],
        ]);
        return redirect()->back()->withSuccess('Advice Update Successfully!');
    }

    public function destroy(Advice $advice)
    {
        if( $advice->delete() ){
            return back()->withSuccess("Advice deleted successfully!");
        }
        return back()->withWarning("Failed to delete User!");
    }
}
