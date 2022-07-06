<?php declare(strict_types=1);

namespace App\Domain\Vehicle\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Database\Factories\Vehicle\VehicleLocalizationFactory;

class VehicleLocalization extends Model
{
    use SoftDeletes;
	use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'vehicle_localizations';
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'license_plate',
        'localization_latitude',
        'localization_longitude',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'localized_at' => 'datetime',
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
    */
    protected static function newFactory(): Factory
    {
        return VehicleLocalizationFactory::new();
    }

    /**
     * Find a vehicle localization instance by the given ID or fail.
     *
     * @param  int  $id
     * @return self
     */
    public static function findVehicleLocalizationByIdOrFail(int $id): self
    {
        return static::where('id', $id)->firstOrFail();
    }

    /**
     * Exclui determinadi avistamento
	 * A busca é feita por ID.
     *
     * @param  int $id
     * @return bool
     */
    public function removeVehicleLocalization($id): bool
    {
        return $this->findVehicleLocalizationByIdOrFail($id)->delete();
    }
}
