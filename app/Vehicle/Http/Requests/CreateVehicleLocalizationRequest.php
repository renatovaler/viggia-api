<?php declare(strict_types=1);

namespace App\Vehicle\Http\Requests;

use Illuminate\Support\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class CreateVehicleLocalizationRequest extends FormRequest
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
    * Prepare the data for validation.
    *
    * @return void
    */
    protected function prepareForValidation()
    {
        // remove non-alphanumeric characters
        $licensePlate = (
            !is_null( $this->input('license_plate') ) ?
            preg_replace( '/[^a-z0-9 ]/i', '', $this->input('license_plate') ) :
            null
        );

        // convert value to float
        $localizationLatitude = (
            !is_null( $this->input('localization_latitude') ) ?
            floatval ( $this->input('localization_latitude') ) :
            null
        );

        // convert value to float
        $localizationLongitude = (
            !is_null( $this->input('localization_longitude') ) ?
            floatval ( $this->input('localization_longitude') ) :
            null
        );

        // convert value to Y-m-d H:i:s date format
        $localizatedAt = (
            !is_null( $this->input('localized_at') ) ?
            Carbon::parse( $this->input('localized_at') )->format('Y-m-d H:i:s') :
            null
        );

        // Merge request params
        $this->merge([
            'license_plate' => $licensePlate,
            'localization_latitude' => $localizationLatitude,
            'localization_longitude' => $localizationLongitude,
            'localized_at' => $localizatedAt
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
			// Para saber qual usuário cadastrou e garantir a legitimidade da informações
            'user_id' => ['required', 'numeric', 'exists:users,id'],
            'license_plate' => ['required', 'min:7', 'max:7', 'alpha_num'],
            'localization_latitude' => ['required', 'between:-90,90'],
            'localization_longitude' => ['required', 'between:-180,180'],
            'localized_at' => ['required', 'date_format:Y-m-d H:i:s'],
        ];
    }
}
