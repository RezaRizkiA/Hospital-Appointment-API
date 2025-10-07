<?php

namespace App\Http\Controllers;

use App\Http\Resources\SpecialistResource;
use App\Services\SpecialistService;
use Illuminate\Http\Request;

class SpecialistController extends Controller
{
    private $specialistService;

    public function __construct(SpecialistService $specialistService)
    {
        $this->specialistService = $specialistService;
    }

    public function index()
    {
        $fields = ['id', 'name', 'about', 'price', 'photo'];
        $specialists = $this->specialistService->getAll($fields);
        return response()->json(SpecialistResource::collection($specialists));
    }

    public function show($id)
    {
        $fields = ['id', 'name', 'about', 'price', 'photo'];
        $specialist = $this->specialistService->getById($id, $fields);
        return response()->json(new SpecialistResource($specialist));
    }
}
