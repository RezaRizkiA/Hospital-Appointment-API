<?php

namespace App\Http\Controllers;

use App\Http\Resources\SpecialistResource;
use App\Services\SpecialistService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
        $fields = ['id', 'name', 'price', 'photo'];
        $specialists = $this->specialistService->getAll($fields);
        return response()->json(SpecialistResource::collection($specialists));
    }

    public function show($id)
    {
        try {
            $fields = ['*'];
            $specialist = $this->specialistService->getById($id, $fields);
            return response()->json(new SpecialistResource($specialist));
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Specialist not found'], 404);
        }
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'about' => 'nullable|string',
            'price' => 'required|numeric',
            'photo' => 'nullable|image|max:2048',
        ]);
        $specialist = $this->specialistService->create($data);
        return response()->json(new SpecialistResource($specialist), 201);
    }
}
