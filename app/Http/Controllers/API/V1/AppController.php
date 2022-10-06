<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Resources\ApiResponse;
use App\Http\Controllers\Controller;

class AppController extends Controller
{
    public function index()
    {   
        return new ApiResponse([
            'name' => config('app.name', 'MedicsBD'),
            'version' => config('app.version', 'v1.0.0')
        ]);
    }
}
