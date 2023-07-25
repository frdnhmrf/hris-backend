<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'gender' => 'nullable|string|in:MALE,FEMALE',
            'age' => 'nullable|integer',
            'email' => 'nullable|email|unique:employees,email',
            'phone' => 'nullable|string|max:255',
            'team_id' => 'required|exists:teams,id|integer',
            'role_id' => 'required|exists:roles,id|integer',
        ];
    }
}
