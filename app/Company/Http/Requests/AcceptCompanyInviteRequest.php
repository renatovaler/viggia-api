<?php declare(strict_types=1);

namespace App\Company\Http\Requests;

use App\User\Models\User;
use App\Company\Models\CompanyInvitation;

use Carbon\Carbon;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password as PasswordRules;

class AcceptCompanyInviteRequest extends FormRequest
{
    /**
     * Invite data
     */
    protected $invite;

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
        $doesntExist = (new User())->where('email', $this->input('email'))->doesntExist();

        $rules = [
            'token' => [
                'required',
                'uuid',
                'exists:company_invitations,token'
            ]
        ];

        if($doesntExist) {
            $rules['name'] = ['required', 'string', 'max:255'];
            $rules['password'] = ['required', 'confirmed', PasswordRules::defaults()];
        }

        return $rules;
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Força o validador a verificar as regras definidas no método rules() antes de continuar
            if ($validator->failed()) return;

            $invite = (new CompanyInvitation())->where('token', $this->input('token'))->first();
            $now = Carbon::now();
                $expiresIn = Carbon::parse($invite->expires_in);

                if ( true === ($expiresIn < $now) ) {
                    $validator->errors()->add(
                        'invite_expired', __('This invite has been expired!')
                    );
                }
        });
    }
}
