<?php

namespace App\Http\Controllers;

use App\Http\Requests\HospitalRequest;
use App\Http\Resources\HospitalResource;
use App\Services\HospitalService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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

    public function show(int $id)
    {
        try {
            $fields = ['*'];
            $hospital = $this->hospitalService->getById($id, $fields);
            return response()->json(new HospitalResource($hospital));
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Hospital not found'], 404);
        }
    }

    public function store(HospitalRequest $request)
    {
        $hospital = $this->hospitalService->create($request->validated());
        return response()->json(new HospitalResource($hospital), 201);
    }
}
