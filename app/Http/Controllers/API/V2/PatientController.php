<?php

namespace App\Http\Controllers\API\V2;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\ApiResponse;
use App\Http\Controllers\Controller;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $patients = User::whereRole(['patient', 'user'])
                ->when($request->search, function($query)use($request){
                    $query->whereLike(['name', 'email'], "%{$request->search}%");
                })
                ->whereNull('submember_of')
                ->with('subMembers')
                ->paginate(15);

        return new ApiResponse([
            'items' => $patients->map(function($item){
                return [
                    'user' => $item->getPublicData('meta'),
                    'sub_members' => $item->subMembers->map(function($item){
                        return $item->getPublicData();
                    }),
                ];
            }),
            'next' => $patients->nextPageUrl()
        ]);
    }

    public function show(User $patient, Request $request)
    {
        if( ! $patient->isRole(['patient', 'user']) ){
            abort(404);
        }
        return new ApiResponse([
            'user' => $patient->getPublicData('meta'),
            'sub_members' => $patient->subMembers->map(function($item){
                return $item->getPublicData();
            }),
        ]);
    }

}