<?php

namespace App\Http\Requests;

use App\Traits\FormRequestExtTrait;
use Illuminate\Foundation\Http\FormRequest;

class StoreReplyRequest extends FormRequest
{
    use FormRequestExtTrait;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'content' => 'required|string|max:1000',
            'user_id' => 'required|exists:users,id',
            'reply_to' => 'nullable|exists:users,id',
        ];
    }

    /**
     * Customize the error messages for validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'content.required' => 'The reply content is required.',
            'content.string' => 'The reply content must be a string.',
            'content.max' => 'The reply content may not be greater than 1000 characters.',
            'user_id.required' => 'A user ID is required.',
            'user_id.exists' => 'The specified user does not exist.',
            'reply_to.exists' => 'The user you are replying to does not exist.',
        ];
    }
}
