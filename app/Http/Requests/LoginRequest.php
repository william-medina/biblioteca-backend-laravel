<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator; 
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required']
        ];
    }
    
    public function messages(): array
    {
        return [
            'email.required' => 'El Email es obligatorio',
            'email.email' => 'El email no es válido',
            'email.exists' => 'Esa cuenta no existe',
            'password.required' => 'El Password es obligatorio'
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator)
    {
        // Obtener el primer mensaje de error
        $firstErrorMessage = collect($validator->errors()->getMessages())
        ->flatten()
        ->first();

        throw new HttpResponseException(response()->json([
            'error' => $firstErrorMessage
        ], 422));
    }
}
