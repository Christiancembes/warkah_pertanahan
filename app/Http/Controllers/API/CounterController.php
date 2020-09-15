<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CounterController extends Controller
{
    public $successStatus = 200;
    
    public function __construct()
    {
        $this->counterService = \App::make('\App\Services\Counter\CounterServiceInterface');
    }

    public function index(Request $request)
    {
        $results = $this->counterService->getAll();

        return response()->json($results, $this->successStatus);
    }
}
