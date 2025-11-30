<?php

namespace App\Modules\Identity\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        // Drop empty password fields so they aren't validated or updated.
        if ($this->has('password') && ($this->input('password') === '' || $this->input('password') === null)) {
            $this->replace($this->except('password'));
            $this->replace($this->except('password_confirmation'));
        }
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'password' => ['sometimes', 'nullable', 'string', Password::min(8), 'confirmed'],
        ];
    }
}
