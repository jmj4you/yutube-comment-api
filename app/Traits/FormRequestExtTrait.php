<?php

namespace App\Traits;

trait FormRequestExtTrait
{
   /**
     * Customize the error response for failed validation.
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new \Illuminate\Validation\ValidationException($validator, response()->json([
            'errors' => $validator->errors(),
        ], 422));
    }
}
