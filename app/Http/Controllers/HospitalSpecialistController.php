<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use App\Services\HospitalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HospitalSpecialistController extends Controller
{
    private $hospitalService;

    public function __construct(HospitalService $hospitalService)
    {
        $this->hospitalService = $hospitalService;
    }

    public function attach(Request $request, int $hospitalId)
    {
        $validator = Validator::make($request->all(), [
            'specialist_id' => 'required|exists:specialists,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $result = $this->hospitalService->attachSpecialist($hospitalId, $request->input('specialist_id'));
        return response()->json(new HospitalSpecialistResource($result));
    }
}
