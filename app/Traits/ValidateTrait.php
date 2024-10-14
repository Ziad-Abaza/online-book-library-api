<?php

namespace App\Traits;

use Illuminate\Support\Facades\Validator;

trait ValidateTrait
{
    /*
    |--------------------------------------------------------------------------
    | Validate Request Data
    |--------------------------------------------------------------------------
    */
    public function validateRequestData($request, $rules)
    {
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        return null;  
    }
}
