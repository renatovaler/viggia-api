<?php declare(strict_types=1);

namespace App\UI\Vehicle\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateVehicleLocalizationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        $inputVehicleId = (int) $this->input('id');
        $routeVehicleId = (int) $this->get('id');
        return $routeVehicleId === $inputVehicleId;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'id' => ['required', 'numeric', 'exists:vehicle_localizations,id'],			
            'license_plate' => ['required', 'min:7', 'max:7', 'alpha_num'],
            'localization_latitude' => ['required', 'between:-90,90'],
            'localization_longitude' => ['required', 'between:-180,180'],
            'localized_at' => ['required', 'date_format:Y-m-d H:i:s'],
        ];
    }
}
