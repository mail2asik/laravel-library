<?php
/**
 * Class ApiRepository
 *
 * @author Asik
 * @email  mail2asik@gmal.com
 */

namespace App\Repositories;

use App\Models\Api;
use Illuminate\Support\Facades\DB;
use Webpatser\Uuid\Uuid;
use Illuminate\Support\Facades\Cache;
use Exception;

/**
 * Class ApiRepository
 *
 * All Api methods.
 */
class ApiRepository
{
    /**
     * This is a Repository that depends on a Model
     */
    use ModelTrait;

    /**
     * @param Api $api
     */
    public function __construct(Api $api)
    {
        $this->setModel($api);
    }

    /**
     * Create Api
     *
     * @params array $params
     *
     * @return Api
     *
     * @throws \Exception
     */
    public function create()
    {
        try {
            $api             = new Api;
            $api->api_key    = str_replace('-', '', Uuid::generate(4));
            $api->api_secret = str_random(64);
            $api->status     = 'ACTIVE';
            $api->save();

            $api = $this->getModel()->find($api->id);

            return $api;
        } catch(\Exception $e) {
            Log::error(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'Unknown Exception thrown ApiRepository@create', [
                'exception_type' => get_class($e),
                'message'        => $e->getMessage(),
                'code'           => $e->getCode(),
                'line_no'        => $e->getLine(),
                'params'         => func_get_args()
            ]);

            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Get Api by user_id
     *
     * @param int $user_id
     *
     * @return Api
     *
     * @throws \Exception
     */
    public function getApiByUserId($user_id)
    {
        try {
            $key    = __FUNCTION__ . $user_id;
            $expire = 30;

            if (Cache::has($key)) {
                return Cache::get($key);
            }

            $api = $this->getModel()->whereHas('user', function ($q) use ($user_id) {
                $q->where('id', $user_id);
            })->first();

            if (empty($api)) {
                throw new \Exception('Api does not exist by user_id', 40003999);
            }

            Cache::put($key, $api, $expire);

            return $api;
        } catch(\Exception $e) {
            Log::error(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'Unknown Exception thrown ApiRepository@getApiByUserId', [
                'exception_type' => get_class($e),
                'message'        => $e->getMessage(),
                'code'           => $e->getCode(),
                'line_no'        => $e->getLine(),
                'params'         => func_get_args()
            ]);

            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }
}