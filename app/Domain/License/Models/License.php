<?php declare(strict_types=1);

namespace App\Domain\License\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;
use Database\Factories\License\LicenseFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class License extends Model
{
    use SoftDeletes;
	use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'licenses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slave_key',
        'user_id',
        'company_id',
        'company_branch_id',
        'runtime_key_used_to_activate',
        'token_used_to_activate'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'activated_at' => 'datetime',
        'deactivated_at' => 'datetime',
    ];

    /**
     * Verifica se a licença está ATIVA
     *
     * @param  int $licenseId
     * @return bool
    */
    public function IsActive(int $licenseId): bool
    {
       return (
                static::where('id', $licenseId)
                ->whereNotNull('activated_at')
                ->whereNull('deactivated_at')
            ) > 0;
    }

    /**
     * Verifica se a licença já foi ATIVADA
     *
     * @param  int $licenseId
     * @return bool
    */
    public function hasBeenActivated(int $licenseId): bool
    {
       return ( static::where('id', $licenseId)->whereNotNull('activated_at') ) > 0;
    }

    /**
     * Verifica se a licença já foi DESATIVADA
     *
     * @param  int $licenseId
     * @return bool
    */
    public function hasBeenDeactivated(int $licenseId): bool
    {
       return (static::where('id', $licenseId)->whereNotNull('deactivated_at') ) > 0;
    }

    /**
     * Retorna informações de determinada licença.
	 * A busca é feita por ID.
     *
     * @param  int $licenseId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function licenseById($licenseId): Collection
    {
        return $this->where('id', $licenseId)->firstOrFail();
    }

    /**
     * Exclui determinada licença
	 * A busca é feita por ID.
     *
     * @param  int $licenseId
     * @return bool
     */
    public function removeLicense($licenseId): bool
    {
        return $this->licenseById($licenseId)->delete();
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
    */
    protected static function newFactory(): Factory
    {
        return LicenseFactory::new();
    }
}
