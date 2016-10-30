<?php
/**
 * MemberBook Model Class
 *
 * @author Asik
 * @email  mail2asik@gmal.com
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * MemberBook Model Class
 */
class MemberBook extends Model
{
    use SoftDeletes;

    protected $table = 'memberbooks';

    protected $hidden = ['id', 'deleted_at'];

    protected $appends = ['fine'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function user()
    {
        return $this->hasMany('App\Models\User');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function book()
    {
        return $this->hasMany('App\Models\Book');
    }

    /**
     * Calculate fine based on return date
     * Failure to return a book before expiry will cause a Fine to be charged to the Member @ $2 per day
     *
     * @return integer
     */
    public function getFineAttribute()
    {
        $returned_at = ($this->attributes['returned_at'] != '0000-00-00 00:00:00') ? Carbon::parse($this->attributes['returned_at']) : Carbon::now();
        $ended_at    = Carbon::parse($this->attributes['ended_at']);

        if ($ended_at >= $returned_at) {
            return 0;
        }

        $extra_days = $ended_at->diffInDays($returned_at);

        return ($extra_days * config('constants.fine_per_day'));
    }
}