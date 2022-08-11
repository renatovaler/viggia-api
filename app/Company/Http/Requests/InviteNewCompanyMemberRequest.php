<?php declare(strict_types=1);

namespace App\Company\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InviteNewCompanyMemberRequest extends FormRequest
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
            'company_id' => ['required', 'numeric', 'exists:companies,id'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'roles' => ['required', 'array'],
            'roles.*' => ['required', 'numeric', 'exists:roles,id'],
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
            'company_id.required' => __('É necessário informar o ID da empresa.'),
            'company_id.numeric' => __('O ID da empresa deve ser numérico.'),
            'company_id.exists' => __('A empresa informada não existe em nosso banco de dados.'),
            'email.required' => __('É necessário informar um e-mail para o usuário.'),
            'email.email' => __('É necessário informar um e-mail válido para o usuário.'),
            'roles.required' => __('É necessário informar as permissões do usuário.'),
            'roles.array' => __('O conjunto de permissões deve ser enviado como uma matriz.'),
            'roles.*.required' => __('É necessário informar as permissões do usuário.'),
            'roles.*.numeric' => __('O ID da permissão deve ser numérico.'),
            'roles.*.exists' => __('A permissão informada não existe em nosso banco de dados.'),
        ];
    }
}
