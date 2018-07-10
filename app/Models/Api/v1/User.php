<?php

namespace App\Models\Api\v1;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

/**
 * @SWG\Definition(
 *   title="User",
 *   required={"name", "email", "password"},
 *   @SWG\Property(
 *     title="id",
 *     property="id",
 *     type="integer",
 *   ),
 *   @SWG\Property(
 *     title="Name",
 *     property="name",
 *     type="string",
 *   ),
 *   @SWG\Property(
 *     title="Email",
 *     property="email",
 *     type="string",
 *   ),
 *   @SWG\Property(
 *     title="Password",
 *     property="password",
 *     type="string",
 *   ),
 *   @SWG\Property(
 *     title="Created date",
 *     property="created_at",
 *     type="string",
 *     format="date-time",
 *   ),
 *   @SWG\Property(
 *     title="Updated date",
 *     property="updated_at",
 *     type="string",
 *     format="date-time",
 *   ),
 * )
 */
class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
