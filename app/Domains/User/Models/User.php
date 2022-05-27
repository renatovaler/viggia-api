<?php declare(strict_types=1);

namespace App\Domains\User\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Database\Factories\User\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Define custom factory to the model
     *
     * @return \Database\Factories\User\UserFactory
     */
    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }

	/*
    |--------------------------------------------------------------------------
	| USER SECTION
	| Contain functions related to user
	| Related model: this
    |--------------------------------------------------------------------------
	*/

    /**
     * Find a user instance by the given email address.
     *
     * @param  string  $email
     * @return self
     */
    public static function findUserByEmail(string $email): self
    {
        return static::where('email', $email)->first();
    }

    /**
     * Find a user instance by the given ID.
     *
     * @param  int  $id
     * @return self
     */
    public static function findUserById(int $id): self
    {
        return static::where('id', $id)->first();
    }

    /**
     * Find a user instance by the given ID or fail.
     *
     * @param  int  $id
     * @return self
     */
    public static function findUserByIdOrFail(int $id): self
    {
        return static::where('id', $id)->firstOrFail();
    }

    /**
     * Find a user instance by the given email address or fail.
     *
     * @param  string  $email
     * @return self
     */
    public static function findUserByEmailOrFail(string $email): self
    {
        return static::where('email', $email)->firstOrFail();
    }
}
