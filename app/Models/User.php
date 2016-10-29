<?php
/**
 * User Model Class
 *
 * @author Asik
 * @email  mail2asik@gmal.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Request;
use Cartalyst\Sentinel\Users\EloquentUser AS CartalystUser;
use Webpatser\Uuid\Uuid;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Carbon\Carbon;

/**
 * User Model Class
 */
class User extends CartalystUser
{
    use SoftDeletes;

    public $fillable = ['uid', 'first_name', 'last_name', 'email', 'password', 'gender', 'dob'];

    protected $appends = ['is_activated', 'age'];

    /**
     * Hides these data from being displayed
     */
    protected $hidden = [
        'id',
        'password',
        'last_login',
        'deleted_at'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function api()
    {
        return $this->belongsTo('App\Models\Api');
    }

    /**
     * Attach is_activated
     *
     * @return array
     */
    public function getIsActivatedAttribute()
    {
        $activation = Activation::completed($this);
        return !empty($activation['completed']) ? $activation['completed'] : false;
    }

    /**
     * Attach age
     *
     * @return integer
     */
    public function getAgeAttribute()
    {
        $dob = $this->attributes['dob'];
        return ($dob != '0000-00-00') ? Carbon::parse($dob)->age : '';
    }

    /**
     * Change DOB format as "mm/dd/yyyy"
     *
     * @return string
     */
    public function getDobAttribute()
    {
        $dob = $this->attributes['dob'];
        return ($dob != '0000-00-00') ? Carbon::parse($dob)->format('m/d/Y') : '';
    }
}
