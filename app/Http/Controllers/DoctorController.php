<?php

namespace App\Http\Controllers;

use App\Http\Requests\DoctorRequest;
use App\Http\Resources\DoctorResource;
use App\Services\DoctorService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DoctorController extends Controller
{
    private $doctorService;

    public function __construct(DoctorService $doctorService)
    {
        $this->doctorService = $doctorService;
    }

    public function index(){
        $fields = ['id', 'name', 'photo', 'yoe', 'specialist_id', 'hospital_id',];
        $doctor = $this->doctorService->getAll($fields);
        return response()->json(DoctorResource::collection($doctor));
    }

    public function show(int $id)
    {
        try {
            $fields = ['*'];
            $doctor = $this->doctorService->getById($id, $fields);
            return response()->json(new DoctorResource($doctor));
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Doctor not found'], 404);
        }
    }

    public function store(DoctorRequest $request)
    {
        $doctor = $this->doctorService->create($request->validated());
        return response()->json(new DoctorResource($doctor), 201);
    }

    public function update(DoctorRequest $request, int $id)
    {
        try {
            $doctor = $this->doctorService->update($id, $request->validated());
            return response()->json(new DoctorResource($doctor));
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Doctor not found'], 404);
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->doctorService->delete($id);
            return response()->json([
                'message' => 'Doctor deleted successfully',
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Doctor not found'], 404);
        }
    }

    public function filterBySpecialistAndHospital(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'specialist_id' => 'required|exists:specialists,id',
            'hospital_id' => 'required|exists:hospitals,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        } 
        $specialistId = $request->input('specialist_id');
        $hospitalId = $request->input('hospital_id');
        $doctors = $this->doctorService->filterBySpecialistAndHospital($specialistId, $hospitalId);
        if ($doctors->isEmpty()) {
            return response()->json(['message' => 'No doctors found for the specified specialist and hospital'], 404);
        }
        return response()->json(DoctorResource::collection($doctors), 200);
    }

    public function availableSlots(int $doctorId)
    {
        try {
            $availableSlots = $this->doctorService->availableSlots($doctorId);
            return response()->json($availableSlots, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Doctor not found'], 404);
        }
    }
}
