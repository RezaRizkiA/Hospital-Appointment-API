<?php

namespace App\Http\Controllers;

use App\Http\Resources\HospitalResource;
use App\Services\HospitalService;
use Illuminate\Http\Request;

class HospitalController extends Controller
{
    private $hospitalService;

    public function __construct(HospitalService $hospitalService)
    {
        $this->hospitalService = $hospitalService;
    }

    public function index()
    {
        $fields = ['id', 'name', 'photo', 'address', 'city', 'phone'];
        $hospitals = $this->hospitalService->getAll($fields);
        return response()->json(HospitalResource::collection($hospitals));
    }
}
