<?php

namespace Develoopin\Subscriptions\Models\Core;


use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helpers\Connection;
use App\Models\Client\Employee;


class User extends Authenticatable
{
	use Notifiable, SoftDeletes;

	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'first_name','last_name', 'email', 'password', 'company_id'
	];

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
		$newdb = new MoDB($manager, 'cl_'.$user->company->id);
		// create dummy collection, make sure db build success, removed after other schema build
		$newdb->createCollection('dummy');
		if (Connection::setConnectionToClient($user)) {
			Employee::buildSchema();
			$newdb->dropCollection('dummy');
			return $newdb->listCollections();
		}
		return false;

	}

	public function employee()
	{
		return $this->hasOne('App\Models\Client\Employee');
	}

	public function company()
	{
		return $this->belongsTo('App\Models\Core\Company');
	}

}
