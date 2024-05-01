<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function first(Request $request): \Illuminate\Http\JsonResponse
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

}


