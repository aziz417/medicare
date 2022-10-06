<?php

namespace App\Http\Controllers\API\V2;

use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Resources\ApiResponse;
use App\Http\Controllers\Controller;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $departments = Department::when($request->search, function($query)use($request){
                    $query->whereLike('name', "%{$request->search}%");
                })->paginate(15);
        return new ApiResponse([
            'items' => $departments->map(function($item){
                return $item->getPublicData();
            }),
            'next' => $departments->nextPageUrl()
        ]);
    }

    public function withUser(Department $department)
    {
        return new ApiResponse([
            'department' => $department->getPublicData(),
            'doctors' => $department->doctors->map(function($item){
                return [
                    'info' => $item->getPublicData(),
                    'charges' => $this->mapCharges($item->charges),
                ];
            })
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
