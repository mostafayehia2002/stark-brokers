<?php

namespace App\Http\Requests;

use App\Traits\HttpResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UserLoginRequest extends FormRequest
{
    use HttpResponse;
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
            //
            'phone' => ['required','regex:/^(\+966\d{9}|\+20\d{10})$/'],
            'type' => 'required|in:owner,renter',
        ];
    }



    protected function failedValidation(Validator|\Illuminate\Contracts\Validation\Validator $validator)
    {
        $errors = $validator->errors()->getMessages();
        if ($this->is('api/*')) {

            throw new ValidationException($validator,
                $this->returnValidationError(422, formatErrors($errors))
            );
        }
        parent::failedValidation($validator);

    }
}
