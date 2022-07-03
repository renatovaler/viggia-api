<?php declare(strict_types=1);

namespace App\UI\User\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserPersonalInformationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        $inputUserId = intval( $this->input('id') );
        $routeUserId = $this->get('userId');
        return $routeUserId === $inputUserId;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'id' => ['required', 'numeric', 'exists:users,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->input('id'))
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
            'id.required' => __('É necessário informar o ID do usuário.'),
            'id.exists' => __('O usuário informado não existe em nosso banco de dados.'),
            'name.required' => __('É necessário informar um nome para o usuário.'),
            'email.required' => __('É necessário informar um e-mail para o usuário.'),
            'email.email' => __('É necessário informar um e-mail válido para o usuário.'),
            'email.unique' => __('Esse e-mail já está em uso por outro usuário.')
        ];
    }
}
