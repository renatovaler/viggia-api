<?php

namespace App\UI\Http\Company\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyInformationRequest extends FormRequest
{
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'id' => ['required', 'numeric', 'exists:companies,id'],
            'name' => ['required', 'string', 'max:255']
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'id.required' => 'É necessário informar o ID da empresa.',
            'id.exists' => 'A empresa informada não existe em nosso banco de dados.',
            'name.required' => 'É necessário informar um nome para a empresa.'
        ];
    }
}
