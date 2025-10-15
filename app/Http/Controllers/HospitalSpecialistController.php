<?php

namespace App\Http\Controllers;

use App\Http\Requests\HospitalSpecialistRequest;
use App\Http\Resources\HospitalSpecialistResource;
use App\Models\Hospital;
use App\Services\HospitalService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HospitalSpecialistController extends Controller
{
    private $hospitalService;

    public function __construct(HospitalService $hospitalService)
    {
        $this->hospitalService = $hospitalService;
    }

    public function attach(HospitalSpecialistRequest $request, int $hospitalId)
    {
        try {
            $validatedData = $request->validated();
            $specialistId = $validatedData['specialist_id'];
            $this->hospitalService->attachSpecialist($hospitalId, $specialistId);
            return response()->json(['message' => 'Specialist attached successfully.']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Hospital or Specialist not found.'], 404);
        }
    }

    public function detach(int $hospitalId, int $specialistId)
    {
        try {
            $this->hospitalService->detachSpecialist($hospitalId, $specialistId);
            return response()->json(['message' => 'Specialist detached successfully.']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Hospital or Specialist not found.'], 404);
        }
    }
}
