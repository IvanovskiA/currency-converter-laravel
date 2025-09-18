<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConvertCurrencyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'from'   => ['required', 'string', 'size:3'],
            'to'     => ['required', 'string', 'size:3'],
            'amount' => ['required', 'numeric', 'min:0.01'],
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'from' => strtoupper($this->input('from')),
            'to'   => strtoupper($this->input('to')),
        ]);
    }
}
