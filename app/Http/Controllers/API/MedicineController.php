<?php

namespace App\Http\Controllers\API;

use App\Models\Medicine;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MedicineController extends Controller
{
    public function search(Request $request)
    {
        $medicines = Medicine::when($request->search, function($query)use($request){
            $query->whereLike(['name', 'category'], "%{$request->search}%");
        })->get(['id', 'name', 'type', 'category']);

        return response()->json($medicines);
    }
}
