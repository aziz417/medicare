<?php

namespace App\Http\Controllers\API\V2;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\ApiResponse;
use App\Http\Controllers\Controller;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $doctors = User::whereRole('doctor')
                ->when($request->search, function($query)use($request){
                    $query->whereLike(['name', 'email', 'department.name', 'meta.meta_value'], "%{$request->search}%");
                })
                ->when($request->department, function($query)use($request){
                    $query->where('department_id', $request->department);
                })
                // ->withCount('reviews')->orderBy('reviews_count', 'desc')
                ->withCount(['reviews as reviews_count' => function($query) {
                    $query->select(DB::raw('coalesce(avg(rating),0)'));
                }])->orderByDesc('reviews_count')

                ->with('charges', 'department', 'reviews')->paginate(15);

        return new ApiResponse([
            'items' => $doctors->map(function($item){
                return [
                    'doctor' => $item->getPublicData(),
                    'department' => $item->department->getPublicData() ?? [],
                    'reviews' => $item->reviews->map(function($review){
                        return $review->getPublicData();
                    }),
                    'charges' => $this->mapCharges($item->charges),
                ];
            }),
            'next' => $doctors->nextPageUrl()
        ]);
    }

    public function show(User $doctor, Request $request)
    {
        if( ! $doctor->isRole('doctor') ){
            abort(404);
        }
        return new ApiResponse([
            'doctor' => $doctor->getPublicData(),
            'department' => $doctor->department->getPublicData() ?? [],
            'reviews' => $doctor->reviews->map(function($item){
                return $item->getPublicData();
            }),
            'charges' => $this->mapCharges($doctor->charges),
        ]);
    }

    private function mapCharges($charges)
    {
        $data = [];
        foreach ($charges as $item) {
            $data[$item->type] = $item->amount;
        }
        return $data;
    }
}
