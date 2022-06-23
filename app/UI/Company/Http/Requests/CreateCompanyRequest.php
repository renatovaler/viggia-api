<?php declare(strict_types=1);

namespace App\UI\Company\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }
	
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'numeric', 'exists:users,id'],
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
            'user_id.required' => __('É necessário informar o ID do usuário que será proprietário da empresa.'),
            'user_id.exists' => __('O usuário informado não existe em nosso banco de dados.'),
            'name.required' => __('É necessário informar um nome para a empresa.')
        ];
    }
}
