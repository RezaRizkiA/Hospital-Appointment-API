<?php

namespace App\Http\Controllers;

use App\Http\Requests\SpecialistRequest;
use App\Http\Resources\SpecialistResource;
use App\Services\SpecialistService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SpecialistController extends Controller
{
    private $specialistService;

    public function __construct(SpecialistService $specialistService)
    {
        $this->specialistService = $specialistService;
    }

    public function index()
    {
        $fields = ['id', 'name', 'photo', 'price'];
        $specialists = $this->specialistService->getAll($fields);
        return response()->json(SpecialistResource::collection($specialists));
    }

    public function show(int $id)
    {
        try {
            $fields = ['*'];
            $specialist = $this->specialistService->getById($id, $fields);
            return response()->json(new SpecialistResource($specialist));
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Specialist not found'], 404);
        }
    }

    public function store(SpecialistRequest $request)
    {
        $specialist = $this->specialistService->create($request->validated());
        return response()->json(new SpecialistResource($specialist), 201);
    }

    public function update(SpecialistRequest $request, int $id)
    {
        try {
            $specialist = $this->specialistService->update($id, $request->validated());
            return response()->json(new SpecialistResource($specialist));
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Specialist not found'], 404);
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->specialistService->delete($id);
            return response()->json([
                'message' => 'Specialist deleted successfully',
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Specialist not found'
            ], 404);
        }
    }
}
