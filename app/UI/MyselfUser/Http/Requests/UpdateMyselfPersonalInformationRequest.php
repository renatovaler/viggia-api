<?php declare(strict_types=1);

namespace App\UI\MyselfUser\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMyselfPersonalInformationRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->id)
            ]
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
            'name.required' => __('É necessário informar um nome para o usuário.'),
            'email.required' => __('É necessário informar um e-mail para o usuário.'),
            'email.email' => __('É necessário informar um e-mail válido para o usuário.'),
            'email.unique' => __('Esse e-mail já está em uso por outro usuário.')
        ];
    }
}
