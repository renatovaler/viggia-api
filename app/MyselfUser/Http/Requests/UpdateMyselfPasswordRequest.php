<?php declare(strict_types=1);

namespace App\MyselfUser\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\User\Rules\PasswordValidationRule;
use App\User\Rules\CurrentPasswordValidationRule;

class UpdateMyselfPasswordRequest extends FormRequest
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
            'current_password' => ['required', new CurrentPasswordValidationRule],
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
            'current_password.required' =>  __('É necessário informar a senha atual.'),
            'password.required' =>  __('É necessário informar a confirmação da nova senha.'),
            'password.confirmed' =>  __('A senha digitada no campo "nova senha" não confere com a digitada no campo de "confirmação da senha".'),
        ];
    }
}
