<?php
/**
 * MemberBook Model Class
 *
 * @author Asik
 * @email  mail2asik@gmal.com
 */

namespace App\Models;

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
}