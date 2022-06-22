<?php declare(strict_types=1);

namespace App\Domain\Role\Models;

use Illuminate\Database\Eloquent\Model;
use Database\Factories\Role\RoleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'roles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description'
    ];

    /**
     * Search for a function by name and check if it exists
     *
     * @param string $role
     * @return bool
    */
    public function hasRoleByName(string $role): bool
    {
        return static::where('name', $role)->count() > 0;
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
    */
    protected static function newFactory(): Factory
    {
        return RoleFactory::new();
    }
}
