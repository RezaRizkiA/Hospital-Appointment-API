<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use App\Services\HospitalSpecialistService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HospitalSpecialistController extends Controller
{
    private $hospitalSpecialistService;

    public function __construct(HospitalSpecialistService $hospitalSpecialistService)
    {
        $this->hospitalSpecialistService = $hospitalSpecialistService;
    }

    public function attach(Request $request, Hospital $hospital)
    {
        $validator = Validator::make($request->all(), [
            'specialist_id' => 'required|exists:specialists,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $result = $this->hospitalSpecialistService->attachSpecialistToHospital($hospital, $request->input('specialist_id'));
        return response()->json([
            'message' => $result['message']
        ], $result['code']);
    }
}
