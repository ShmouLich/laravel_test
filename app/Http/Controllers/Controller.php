<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\WorkDayService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function handleGetRequest(Request $request): \Illuminate\Http\JsonResponse
    {

        return response()->json(['message' => 'You are accessing my first endpoint.'], 200);
    }

    public function handlePostRequest(Request $request): \Illuminate\Http\JsonResponse
    {

        $validator = Validator::make($request->all(), User::rules());

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $data = $request->all();

        return response()->json(['message' => 'POST request processed successfully', 'user' => $data], 200);
    }

    public function isWorkDay(): \Illuminate\Http\JsonResponse
    {
        $workDayService = new WorkDayService();
        $isWorkDay = $workDayService->isWorkDay(now());


        if ($isWorkDay) {
            return response()->json(['message' => 'Dnes je pracovni den.'], 200);
        } else {
            return response()->json(['message' => 'Dnes je volno.'], 200);
        }
    }

    public function getDuration(Request $request): \Illuminate\Http\JsonResponse {

        $workDayService = new WorkDayService();

        $request->validate([
            'startDate' => 'required|date_format:Y-m-d',
            'durationMinutes' => 'required|integer|min:1',
            'considerHolidays' => 'required|boolean',
            'workTimeStart' => 'required|date_format:H:i:s',
            'workTimeEnd' => 'required|date_format:H:i:s',
        ]);

        // Extract data from the request
        $startDate = $request->input('startDate');
        $durationMinutes = $request->input('durationMinutes');
        $considerHolidays = $request->input('considerHolidays');
        $workTimeStart = $request->input('workTimeStart');
        $workTimeEnd = $request->input('workTimeEnd');

        $completionDate = $workDayService->calculateCompletionDate($startDate, $durationMinutes, $considerHolidays, $workTimeStart, $workTimeEnd);

        return response()->json([
                'completionDate' => $completionDate,
            ], 200);
    }

}


