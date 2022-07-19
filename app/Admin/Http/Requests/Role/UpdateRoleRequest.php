<?php declare(strict_types=1);

namespace App\Admin\Http\Requests\Role;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
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
            'id' => ['required', 'numeric', 'exists:roles,id'],
            'name' => ['required', 'string', 'max:255','exists:roles,name'],
            'description' => ['required', 'string', 'max:255']
        ];
    }
}
