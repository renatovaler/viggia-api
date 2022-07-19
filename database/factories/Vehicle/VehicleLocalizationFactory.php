<?php

namespace Database\Factories\Vehicle;

use Illuminate\Support\Str;
use App\Vehicle\Models\VehicleLocalization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Vehicle\Models\VehicleLocalization>
 */
class VehicleLocalizationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = VehicleLocalization::class;

    protected $points = [
        [-20.758829008747448, -51.72219608974641],
        [-20.79286960324003, -51.66330042199748],
        [-20.793025515627804, -51.661215740668275],
        [-20.791310470501752, -51.66029848088343],
        [-20.79474054126021, -51.663133647491144],
        [-20.81348127894471, -51.68282255695913],
        [-20.795013319576437, -51.69095104302494],
        [-20.793485409106626, -51.69381119258655],
        [-20.788762678928194, -51.70168589008087],
        [-20.784248166337647, -51.70031153249932],
        [-20.78358834147512, -51.70005151899171],
        [-20.78188667502405, -51.707554768491],
        [-20.78535944332819, -51.70361741974385],
        [-20.794318816704465, -51.70361742002449],
        [-20.792895075838356, -51.678878982484086],
        [-20.78199085933113, -51.669518493009704],
        [-20.79074207640111, -51.6644668002775],
        [-20.792756173575857, -51.66220096750791],
        [-20.791228240249445, -51.66628689545308],
        [-20.79136714391842, -51.670001375403224],
        [-20.801228977710903, -51.67107857452488],
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $latlong = $this->points[array_rand($this->points, 1)];
        return [
            'license_plate' => Str::random(7),
			'localization_latitude' => $latlong[0],
			'localization_longitude' => $latlong[1],
			'localized_at' => now(),
        ];
    }
}
