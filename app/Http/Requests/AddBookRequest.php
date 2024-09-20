<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AddBookRequest extends FormRequest
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
            'title' => ['required'],
            'isbn' => ['required', 'unique:books,isbn'],
            'publication_year' => ['nullable', 'max:6'],  
            'location' => ['nullable', 'unique:books,location', 'max:6'],  
            'cover' => ['nullable', 'file', 'mimes:jpg', 'max:5120']
        ];
    }
    
    public function messages(): array
    {
        return [
            'title.required' => 'El Titulo es obligatorio',
            'isbn.required' => 'El ISBN es obligatorio',
            'isbn.unique' => 'El libro con ese ISBN ya existe',
            'publication_year.max' => 'El año de publicación no puede tener más de 6 caracteres',
            'location.max' => 'La ubicación no puede tener más de 6 caracteres',
            'location.unique' => 'Un libro ya tiene esa ubicación',
            'cover.file' => 'La portada debe ser un archivo',
            'cover.mimes' => 'La portada debe ser un archivo de tipo jpg',
            'cover.max' => 'La Portada no debe superar los 5MB'
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
