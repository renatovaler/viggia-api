<?php declare(strict_types=1);

namespace App\UI\Admin\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

use App\Domain\User\Rules\PasswordValidationRule;

class UpdateUserPasswordRequest extends FormRequest
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
            'id' => ['required', 'numeric', 'exists:users,id'],
            'password' => ['required', 'string', new PasswordValidationRule, 'confirmed'],
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
            'id.exists' =>  __('O usuário informado não existe em nosso banco de dados.'),
            'password.required' =>  __('É necessário informar a confirmação da nova senha.'),
            'password.confirmed' =>  __('A senha digitada no campo "nova senha" não confere com a digitada no campo de "confirmação da senha".'),
        ];
    }
}
