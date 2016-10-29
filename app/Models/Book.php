<?php
/**
 * BMemberbookook Model Class
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
class Book extends Model
{
    use SoftDeletes;

    protected $hidden = ['id', 'deleted_at'];
}