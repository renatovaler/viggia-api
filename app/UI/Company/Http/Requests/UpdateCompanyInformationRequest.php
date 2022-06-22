<?php declare(strict_types=1);

namespace App\UI\Company\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyInformationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return ( auth()->user()->id == auth()->user()->currentCompany->owner_user_id );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'id' => ['required', 'numeric', 'exists:companies,id'],
            'name' => ['required', 'string', 'max:255']
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, mixed>
     */
    public function messages(): array
    {
        return [
            'id.required' => __('É necessário informar o ID da empresa.'),
            'id.exists' => __('A empresa informada não existe em nosso banco de dados.'),
            'name.required' => __('É necessário informar um nome para a empresa.')
        ];
    }
}
