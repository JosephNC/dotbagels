<?php

namespace App\Models;

use App\Notifications\Auth\VerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use League\Glide\Server;

class User extends Authenticatable implements MustVerifyEmail
{
    use SoftDeletes, Notifiable;

    protected $guarded = [];

    protected $perPage = 10;

    public function resolveRouteBinding($value, $field = null)
    {
        return in_array(SoftDeletes::class, class_uses($this))
            ? $this->where($this->getRouteKeyName(), $value)->withTrashed()->first()
            : parent::resolveRouteBinding($value);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function user_metas()
    {
        return $this->hasMany(UserMeta::class);
    }

    public function rewards()
    {
        return $this->hasMany(Reward::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function meta($key, $value = '', $update = false)
    {
        $user_meta = $this->user_metas();

        if ($update === false) {
            $get = $user_meta->where('key', $key)->value('value');

            if (empty($get)) $value;

            $get = trim($get);

            return $get == 'false' || $get == 'true' ? filter_var($get, FILTER_VALIDATE_BOOLEAN) : (is_json($get) ? to_array($get) : $get);
        } elseif ($update === true) {
            $meta = $user_meta->where('key', $key)->first();
            $value = is_bool($value) ? var_export($value, true) : (is_array($value) || is_object($value) ? json_encode($value) : $value);

            if (empty($meta)) {
                $this->user_metas()->create( compact( 'key', 'value' ) );
            } else {
                $meta->value  = $value;
                $meta->save();
            }

            return true;
        }

        return false;
    }

    /**
     * Determine if user is an admin
     *
     * @return bool
     */
    public function getIsAdminAttribute(): bool
    {
        return strtolower($this->meta('role', 'user')) === 'admin';
    }

    /**
     * Determine if user is an agent
     *
     * @return bool
     */
    public function getIsAgentAttribute(): bool
    {
        return strtolower($this->meta('role', 'user')) === 'agent';
    }

    public function getUuidAttribute(): string
    {
        $uuid = $this->meta('uuid', '');

        if (empty($uuid)) {
            $gen = generate_uuid();
            $this->meta('uuid', $gen, true);
            $uuid = $gen;
        }

        return $uuid;
    }

    public function getNameAttribute()
    {
        return $this->meta('first_name') . ' ' . $this->meta('last_name');
    }

    public function getVerifiedAttribute()
    {
        return $this->meta('verified', false);
    }

    public function setPasswordAttribute(string $password)
    {
        $this->attributes['password'] = Hash::needsRehash($password) ? Hash::make($password) : $password;
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
    }

    public function photoUrl(array $attributes)
    {
        $photo = $this->meta('photo', '');

        // return $photo ? URL::to(App::make(Server::class)->fromPath($this->photo, $attributes)) : $this->gravatar( $attributes );

        return $photo ? url(app(Server::class)->fromPath($photo, $attributes)) : $this->gravatar($attributes);
    }

    public function gravatar(array $attributes)
    {
        $size = intval($attributes['w'] ?? 128);
        $url = 'https://www.gravatar.com/avatar/';
        $url .= md5(strtolower(trim($this->email)));
        $url .= "?s=$size&d=identicon&r=g";

        return $url;
    }

    private function generateGravatar()
    {
        $defaults = ['404', 'mp', 'identicon', 'monsterid', 'wavatar'];

        shuffle($defaults);

        $d = $defaults[0];
        $url = 'https://www.gravatar.com/avatar/';
        $url .= md5(strtolower(trim($this->email)));
        $url .= "?s=128&d=$d&r=g";

        $this->meta('photo', $url, true);
    }

    // public function scopeOrderByName($query)
    // {
    //     $query->where(function ($query) {
    //         $query->select('value')
    //             ->from('user_metas')
    //             ->whereColumn('user_metas.user_id', 'users.id')
    //             ->limit(1);
    //     });

    //     // $query->whereExists(function ($query) {
    //     //     $query->select(\Illuminate\Support\Facades\DB::raw(1))
    //     //     ->from('user_metas')
    //     //         ->whereColumn('user_metas.user_id', 'users.id');
    //     // });

    //     // $query->select(\DB::raw('count(*) as user_count, status'));

    //     // $query->join('user_metas', function ($join) {
    //     //     $join->on('users.id', '=', 'user_metas.user_id')
    //     //     ->where('user_metas.key', 'last_name')
    //     //     ->select('user_metas.key AS last_name' );
    //     //     // ->get( [ 'value as last_name' ] );
    //     // });

    //     // return $query->orderBy('last_name')->orderBy('first_name');
    // }

    // public function scopeWhereRole($query, $role)
    // {
    //     return $query->join('user_metas', function ($join) use ( $role ) {
    //         $q = $join->on('users.id', '=', 'user_metas.user_id');

    //         switch ($role) {
    //             case 'user':
    //                 $q = $q->where('user_metas.key', '=', 'user');
    //                 break;
    //             case 'admin':
    //                 $q = $q->where('user_metas.key', '=', 'admin');
    //                 break;
    //         }

    //         return $q;
    //     });

    //     // switch ($role) {
    //     //     case 'user': return $query->where('admin', false);
    //     //     case 'admin': return $query->where('admin', true);
    //     // }
    // }


    // public function scopeWhereRole($query, $role)
    // {
    //     // return $query->join('user_metas', function ($join) use ($role) {
    //     //     $q = $join->on('users.id', '=', 'user_metas.user_id');

    //     //     switch ($role) {
    //     //         case 'user':
    //     //             $q = $q->where('user_metas.key', '=', 'user');
    //     //             break;
    //     //         case 'admin':
    //     //             $q = $q->where('user_metas.key', '=', 'admin');
    //     //             break;
    //     //     }

    //     //     return $q;
    //     // });

    //     $role = !array_key_exists($role, $this->roles) ? 'user' : $role;

    //     return $query->where(function ($query) {
    //         $query->select('value')
    //             ->from('user_metas')
    //             ->whereColumn([
    //                 ['user_metas.user_id', 'users.id'],
    //                 ['user_metas.key', 'role'],
    //             ])->first();
    //     }, $role);
    // }

    // public function scopeFilter($query, array $filters)
    // {
    //     $query->when($filters['search'] ?? null, function ($query, $search) {
    //         $query->where(function ($query) use ($search) {
    //             $query->where('first_name', 'like', "%$search%")
    //                 ->orWhere('last_name', 'like', "%$search%")
    //                 ->orWhere('email', 'like', "%$search%");
    //         });
    //     })->when($filters['role'] ?? null, function ($query, $role) {
    //         $query->whereRole($role);
    //     })->when($filters['trashed'] ?? null, function ($query, $trashed) {
    //         if ($trashed === 'with') {
    //             $query->withTrashed();
    //         } elseif ($trashed === 'only') {
    //             $query->onlyTrashed();
    //         }
    //     });
    // }

    public function scopeMergeNameRoleForFilter($query)
    {
        // $query
        //     ->join('user_metas as f_um', 'users.id', '=', 'f_um.user_id')
        //     ->select("users.*, GROUP_CONCAT(DISTINCT f_um.value) as first_name")
        //     ->where('f_um.key', '=', "first_name")
        //     ->join('user_metas as l_um', 'users.id', '=', 'l_um.user_id')
        //     ->select("GROUP_CONCAT(DISTINCT l_um.value) as last_name")
        //     ->Where('l_um.key', '=', "last_name")
        //     ->join('user_metas as r_um', 'users.id', '=', 'r_um.user_id')
        //     ->select("GROUP_CONCAT(DISTINCT r_um.value) as role")
        //     ->Where('r_um.key', '=', "role");

        // $f_um = UserMeta::select('user_id, GROUP_CONCAT(DISTINCT value) as first_name')
        //     ->where('key', 'first_name')
        //     ->groupBy('user_id');

        // $query->join('user_metas as f_um', function ($join) {
        //     $join->on('users.id', '=', 'f_um.user_id')->select('value as first_name')
        //         ->where('f_um.key', 'first_name');
        // })->join('user_metas as l_um', function ($join) {
        //     $join->on('users.id', '=', 'l_um.user_id')->select('user_id', 'value as last_name')
        //         ->where('l_um.key', 'last_name');
        // })->join('user_metas as r_um', function ($join) {
        //     $join->on('users.id', '=', 'r_um.user_id')->select('user_id', 'value as role')
        //         ->where('r_um.key', 'role');
        // });


        $f_um = UserMeta::select('user_id', 'value as first_name')
            ->where('key', '=', 'first_name');

        $l_um = UserMeta::select('user_id', 'value as last_name')
            ->where('key', 'last_name');

        $r_um = UserMeta::select('user_id', 'value as role')
            ->where('key', 'role');

        $query->joinSub($f_um, 'f_um', function ($join) {
            $join->on('users.id', '=', 'f_um.user_id');
        })->joinSub($l_um, 'l_um', function ($join) {
            $join->on('users.id', '=', 'l_um.user_id');
        })->joinSub($r_um, 'r_um', function ($join) {
            $join->on('users.id', '=', 'r_um.user_id');
        });
    }

    public function scopeOrderByName($query)
    {
        $query->orderBy('last_name')->orderBy('first_name');
    }

    public function scopeWhereRole($query, $role)
    {
        if ($role == 'all') return $query;

        $query->where('role', $role);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('first_name', 'like', "%$search%")
                    ->orWhere('last_name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            });
        })->when($filters['role'] ?? null, function ($query, $role) {
            $query->whereRole($role);
        })->when($filters['trashed'] ?? 'with', function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });
    }

    // public function scopeFilter($query, array $filters)
    // {
    //     $query->when($filters['search'] ?? null, function ($query, $search) {
    //         $query->where(function ($query) {
    //             $query->join('user_metas', 'users.id', '=', 'user_metas.user_id')
    //                 ->select("users.*, GROUP_CONCAT(DISTINCT user_metas.value SEPARATOR ' ') as name")
    //                 ->where('user_metas.key', '=', "first_name")
    //                 ->orWhere('user_metas.key', '=', "last_name");
    //         })->where('email', 'like', "%$search%")
    //             ->orWhere('name', 'like', "%$search%");
    //     })->when($filters['role'] ?? null, function ($query, $role) {
    //         $query->whereRole($role);
    //     })->when($filters['trashed'] ?? null, function ($query, $trashed) {
    //         if ($trashed === 'with') {
    //             $query->withTrashed();
    //         } elseif ($trashed === 'only') {
    //             $query->onlyTrashed();
    //         }
    //     });
    // }
}
