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

    public $fillable = ['uid', 'first_name', 'last_name', 'email', 'password', 'gender', 'dob', 'no_of_books_borrowed'];

    protected $appends = ['is_activated', 'age', 'max_books_eligible'];

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
     * Change DOB format as "mm/dd/yyyy"
     *
     * @return string
     */
    public function getDobAttribute()
    {
        $dob = $this->attributes['dob'];
        return ($dob != '0000-00-00') ? Carbon::parse($dob)->format('m/d/Y') : '';
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
     * Calculate number of books eligible based on age
     * Each Member can loan a maximum of 6 books
     * Each Junior Member (age <= 12 years) can loan a maximum of 3 books
     *
     * @return integer
     */
    public function getMaxBooksEligibleAttribute()
    {
        $dob = $this->attributes['dob'];
        $age = ($dob != '0000-00-00') ? Carbon::parse($dob)->age : '';

        if (empty($age)) {
            return 0;
        }

        if ($age <= 12) {
            return 3;
        }

        return 6;
    }
}
