<?php declare(strict_types=1);

namespace App\UI\Vehicle\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VehicleLocalizationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request): array
    {
        return [
			'id' => $this->id,
			'license_plate' => $this->licensePlate,
			'localization_latitude' => $this->localizationLatitude,
			'localization_longitude' => $this->localizationLongitude,
			'localized_at' => $this->localizedAt
		];
    }
}
