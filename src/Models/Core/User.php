<?php

namespace App\Models\Core;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use MongoDB\Client as MoClient;
use MongoDB\Database as MoDB;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jenssegers\Mongodb\Eloquent\HybridRelations;
use App\Helpers\Connection;
use App\Models\Client\Employee;
use Spatie\Activitylog\Traits\LogsActivity;


class User extends Authenticatable implements JWTSubject
{
	use Notifiable, SoftDeletes, HybridRelations, LogsActivity;

	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'first_name', 'last_name', 'email', 'password', 'company_id'
	];
	protected static $logAttributes = ['first_name', 'last_name', 'email', 'password', 'company_id'];


	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token',
	];

	/**
	 * Initialize DB, give db some required data
	 *
	 * @param User $user
	 * @return void
	 */
	public function initDB(User $user = null)
	{
		$new = new MoClient(
			$uri = null,
			$uriOptions = [],
			$driverOptions = []
		);
		$manager = $new->getManager();
		if ($user == null) {
			$user = $this;
		}
		// return $user->company;
		$newdb = new MoDB($manager, 'cl_' . $user->company->id);
		// create dummy collection, make sure db build success, removed after other schema build
		$newdb->createCollection('dummy');
		if (Connection::setConnectionToClient($user)) {
			Employee::buildSchema();
			$newdb->dropCollection('dummy');
			return $newdb->listCollections();
		}
		return false;

	}

	public function getJWTIdentifier()
	{
		return $this->getKey();
	}

	public function getJWTCustomClaims()
	{
		return [];
	}

	public function employee()
	{
		return $this->hasOne('App\Models\Client\Employee');
	}

	public function company()
	{
		return $this->belongsTo('App\Models\Core\Company');
	}

	public function role()
    {
        return $this->belongsTo('App\Models\Core\UserRole');
    }

	public static function isSubscribe()
    {
        return true;
    }

    public function isSu()
    {
        return $this->role->level->name === 'superadmin';
    }

}
