<?php

namespace App\Models\Core;

use App\Events\Subscription\NewCompanySubscribed;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\VerifyEmail;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jenssegers\Mongodb\Eloquent\HybridRelations;
use App\Helpers\Connection;
use App\Models\Client\Employee;
use App\Traits\Subscribable;


class User extends Authenticatable implements MustVerifyEmail, JWTSubject
{
    use Notifiable, SoftDeletes, HybridRelations, Subscribable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'company_id',
        'role_id',
        'address',
        'country',
        'city',
        'zip'
    ];
//	protected static $logAttributes = ['first_name', 'last_name', 'email', 'password', 'company_id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

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

    public function subscription()
    {
        return $this->hasMany('App\Models\Core\Subscription');
    }

    public function primarySubscription()
    {
        return $this->subscription()->primary();
    }

    public function activeSubscription()
    {
        return $this->subscription()->activePlan();
    }

    public function activePrimarySubscription()
    {
        return $this->subscription()->activePrimary();
    }

    public function role()
    {
        return $this->belongsTo('App\Models\Core\UserRole');
    }

    public static function isSubscriber($user = null)
    {
        if (is_null($user)) {
            $user = auth()->user();
        }
        return $user->role->level->name === config('auth.level.client');
    }

    public static function isSu($user = null)
    {
        if (is_null($user)) {
            $user = auth()->user();
        }
        return $user->role->level->name === config('auth.level.super');
    }

    /**
     * Define user level is LORD (super or admin)
     *
     * @param $user
     * @return boolean
     */
    public static function isLord($user = null)
    {
        if (is_null($user)) {
            $user = auth()->user();
        }
        return $user->role->level->name != config('auth.level.client');
    }

    public static function isClient($user = null)
    {
        if (is_null($user)) {
            $user = auth()->user();
        }
        return $user->role->level->name == config('auth.level.client');
    }

    public static function isAdmin($user = null)
    {
        if (is_null($user)) {
            $user = auth()->user();
        }
        return $user->role->level->name == config('auth.level.admin');
    }

    public static function resolveRolevel($user = null)
    {
        if (is_null($user)) {
            $user = auth()->user();
        }
        return strtoupper($user->role->level->name . '.' . $user->role->name);
    }

    public static function identify($user = null)
    {
        $company = null;
        if (is_null($user)) {
            $user = auth()->user();
        }
        if (!$user->isLord()) {
            $company = auth()->user()->company->name;
        }
        $user = [
            'email' => auth()->user()->email,
            'rolevel' => $user->resolveRolevel(),
            'verify_at' => auth()->user()->email_verified_at,
            'is_active' => $user->isActive(),
            'company' => $company,
            'is_subscriber' => $user->isSubscriber()
        ];
        return $user;
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail($this));
    }

    public function isActive()
    {
        $status = false;
        if ($this->deactivation_at == null) {
            if ($this->email_verified_at == null) {
                $status = false;
            } else {
                $status = true;
            }
        }
        return $status;
    }

    public static function moduleType($userId = null, $typeModule = null)
    {
        $user = User::join('subscriptions', 'subscriptions.user_id', '=', 'users.id')
            ->join('plans', 'subscriptions.plan_id', '=', 'plans.id')
            ->join('modules', 'modules.plan_id', '=', 'plans.id');
    }


    public static function authTokenFormat($token)
    {
        return [
            'access_token' => $token,
            'role_level' => self::resolveRolevel(),
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'verified_at' => auth()->user()->email_verified_at
        ];
    }

    public static function fPremiumModule($userId = null, $typeModule = null)
    {
        $user = User::join('subscriptions', 'subscriptions.user_id', '=', 'users.id')
            ->join('plans', 'subscriptions.plan_id', '=', 'plans.id')
            ->join('plan_packages', 'plan_packages.plan_id', '=', 'plans.id')
            ->join('modules', 'modules.id', '=', 'plan_packages.module_id');

		# dengan kondisi user_id dan typeModule ada
        if ($userId && $typeModule != null) {
            $user = $user->where('users.id', $userId)
                ->where('modules.is_premium', $typeModule);
        }
		# dengan kondisi hanya user_id
		# digunakan untuk mencari semua module yang digunakan oleh user tsb
        elseif ($userId) {
            $user = $user->where('users.id', $userId);
        }
		# dengan kondisi hanyan typeModule
		# digunakan untuk mencari semua user yang menggunakan module tsb
        elseif ($typeModule != null || $typeModule == is_numeric(0)) {
            $user = $user->where('modules.is_premium', $typeModule);
        } else {

        }

        return $user;
    }
}
