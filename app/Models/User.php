<?php

namespace App\Models;

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

            if ( empty($get) ) $value;

            $get = trim( $get );

            return $get == 'false' || $get == 'true' ? filter_var( $get, FILTER_VALIDATE_BOOLEAN ) : $get;

        } elseif ($update === true) {
            $meta = $user_meta->where('key', $key)->first();

            if (empty($meta)) {
                $this->user_metas()->createMany([
                    [ 'key' => $key, 'value' => $value ]
                ]);
            } else {
                $meta->key    = $key;
                $meta->value  = $value;
                $meta->save();
            }

            return true;
        }

        return false;
    }

    public function getNameAttribute()
    {
        return $this->meta( 'first_name' ) . ' '. $this->meta( 'last_name' );
    }

    public function getVerifiedAttribute()
    {
        return $this->meta( 'verified', false );
    }

    public function getIsAdminAttribute()
    {
        return $this->meta( 'role', 'user' ) === 'admin';
    }

    public function setPasswordAttribute( string $password )
    {
        $this->attributes['password'] = Hash::needsRehash($password) ? Hash::make($password) : $password;
    }

    public function photoUrl(array $attributes)
    {
        $photo = $this->meta( 'photo', '' );

        // return $photo ? URL::to(App::make(Server::class)->fromPath($this->photo, $attributes)) : $this->gravatar( $attributes );

        return $photo ? url(app(Server::class)->fromPath($photo, $attributes)) : $this->gravatar( $attributes );
    }

    public function gravatar( array $attributes )
    {
        $size = intval( $attributes['w'] ?? 128 );
        $url = 'https://www.gravatar.com/avatar/';
        $url .= md5( strtolower( trim( $this->email ) ) );
        $url .= "?s=$size&d=identicon&r=g";

        return $url;
    }

    private function generateGravatar()
    {
        $defaults = [ '404', 'mp', 'identicon', 'monsterid', 'wavatar'];

        shuffle($defaults);

        $d = $defaults[0];
        $url = 'https://www.gravatar.com/avatar/';
        $url .= md5( strtolower( trim( $this->email ) ) );
        $url .= "?s=128&d=$d&r=g";

        $this->meta('photo', $url, true);
    }

    // public function scopeOrderByName($query)
    // {
    //     $query->orderBy('last_name')->orderBy('first_name');
    // }

    // public function scopeWhereRole($query, $role)
    // {
    //     switch ($role) {
    //         case 'user': return $query->where('admin', false);
    //         case 'admin': return $query->where('admin', true);
    //     }
    // }

    // public function scopeFilter($query, array $filters)
    // {
    //     $query->when($filters['search'] ?? null, function ($query, $search) {
    //         $query->where(function ($query) use ($search) {
    //             $query->where('first_name', 'like', '%'.$search.'%')
    //                 ->orWhere('last_name', 'like', '%'.$search.'%')
    //                 ->orWhere('email', 'like', '%'.$search.'%');
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
}
