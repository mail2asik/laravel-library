<?php
/**
 * Class UserRepository
 *
 * @author Asik
 * @email  mail2asik@gmal.com
 */

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Cache;
use Webpatser\Uuid\Uuid;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Illuminate\Support\Facades\Log;
use Cartalyst\Sentinel\Hashing\NativeHasher;
use Carbon\Carbon;
use helpers;

/**
 * Class UserRepository
 *
 * All User methods.
 */
class UserRepository
{
    /**
     * This is a Repository that depends on a Model
     */
    use ModelTrait;

    /**
     * @param User              $user
     * @param ApiRepository     $api
     */
    public function __construct(
        User $user,
        ApiRepository $api
    ) {
        $this->setModel($user);
        $this->api        = $api;
    }

    /**
     * Get a user by uid
     *
     * @param $user_uid
     *
     * @return User
     *
     * @throws \Exception
     */
    public function getUserByUserUid($user_uid)
    {
        try {
            $key    = 'getUserByUserUid' . $user_uid;
            $expire = 30;

            if (Cache::has($key)) {
                return Cache::get($key);
            }

            $user = $this->getModel()->where('uid', $user_uid)->first();

            if (empty($user)) {
                throw new \Exception('User not found');
            }

            return $user;
        } catch (\Exception $e) {
            Log::error(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'Unknown Exception thrown UserRepository@getUserByUserUid', [
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
     * Get a user by id
     *
     * @param $user_id
     *
     * @return User
     *
     * @throws \Exception
     */
    public function getUserByUserId($user_id)
    {
        try {
            $key    = 'getUserByUserid' . $user_id;
            $expire = 30;

            if (Cache::has($key)) {
                return Cache::get($key);
            }

            $user = $this->getModel()->where('id', $user_id)->first();

            if (empty($user)) {
                throw new \Exception('User not found');
            }

            return $user;
        } catch (\Exception $e) {
            Log::error(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'Unknown Exception thrown UserRepository@getUserByUserId', [
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
     * Registers a User
     *
     * @param array $params
     *
     * @return array
     *
     * @throws \Exception
     */
    public function create($params)
    {
        try {

            // Validation
            $requirements = [
                'first_name' => 'required|min:3',
                'last_name'  => 'required|min:3',
                'email'      => 'required|email|unique:users',
                'password'   => 'required|min:6',
                'gender'     => 'required',
                'dob'        => 'required'
            ];
            $messages = [
                'email.unique' => 'The email has already been taken',
            ];
            $validator = \Validator::make($params, $requirements, $messages);
            if ($validator->fails()) {
                throw new \Exception($validator->messages());
            }

            // Check the role
            if (is_null($role = Sentinel::findRoleBySlug($params['role']))) {
                throw new \Exception('No such role exists');
            }

            // register a user
            $credentials = [
                'email'    => $params['email'],
                'password' => $params['password']
            ];
            if (!$user = Sentinel::register($credentials)) {
                throw new \Exception('Unknown error, failed to register user');
            }

            //Create api
            $api = $this->api->create();

            // Update users table
            $user->uid          = Uuid::generate(4);
            $user->api_id       = $api->id;
            $user->first_name   = $params['first_name'];
            $user->last_name    = $params['last_name'];
            $user->gender       = $params['gender'];
            $user->dob          = Carbon::parse($params['dob'])->format('Y-m-d');
            $user->save();

            // Assign user to role
            $role->users()->attach($user->id);

            // Activation
            $activation = Activation::create($user);
            $params['activation_url'] = getenv('SITE_URL') . '/activate?user_uid=' . $user->uid . '&activation_code=' . $activation['code'];
            if (isset($params['activation']) && $params['activation'] == '2') {
                Activation::complete($user, $activation['code']);
                Event::fire('mailActivatedAccount', [
                    'params' => $params
                ]);
            } else {
                Event::fire('mailActivateAccount', [
                    'params' => $params
                ]);
            }

            return $user;
        } catch (\Exception $e) {
            Log::error(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'Unknown Exception thrown UserRepository@create', [
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
     * Update a user
     *
     * @param array     $params
     * @param string    $user_uid
     *
     * @return array
     *
     * @throws \Exception
     */
    public function update($params, $user_uid)
    {
        try {
            //Get user details
            $user = $this->getUserByUserUid($user_uid);

            // Validation
            $requirements = [
                'first_name' => 'required|min:3',
                'last_name'  => 'required|min:3',
                'email'      => 'sometimes|required|email|unique:users,email,' . $user_uid . ',uid',
                'password'   => 'sometimes|min:6',
                'gender'     => 'required',
                'dob'        => 'required',
                'activation' => 'sometimes',
            ];
            $validator = \Validator::make($params, $requirements);
            if ($validator->fails()) {
                throw new \Exception($validator->messages());
            }

            // Update email or password
            if (isset($params['email']) || (isset($params['password']) && !empty($params['password'])) || (isset($params['activation']) && !empty($params['password']))) {

                if (isset($params['email']) && !empty($params['email'])) {
                    $params['email'] = $params['email'];
                } else {
                    $params['email'] = $user['email'];
                }

                if (isset($params['activation'])) {

                    Activation::remove($user);
                    $activation = Activation::create($user);
                    $params['activation_url'] = getenv('SITE_URL') . '/activate?user_uid=' . $user->uid . '&activation_code=' . $activation['code'];
                    if (isset($params['activation']) && $params['activation'] == '2') {
                        Activation::complete($user, $activation['code']);
                        Event::fire('mailActivatedAccount', [
                            'params' => $params
                        ]);
                    } else {
                        Event::fire('mailActivateAccount', [
                            'params' => $params
                        ]);
                    }
                }

                if (isset($params['password']) && !empty($params['password'])) {
                    $sentinelHasher = new NativeHasher;
                    $params['password'] = $sentinelHasher->hash($params['password']);
                }
            }

            // Update
            $user->first_name   = $params['first_name'];
            $user->last_name    = $params['last_name'];
            $user->gender       = $params['gender'];
            $user->dob          = Carbon::parse($params['dob'])->format('Y-m-d');
            $user->update();

            return $user;
        } catch (\Exception $e) {
            Log::error(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'Unknown Exception thrown UserRepository@update', [
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
     * Get a list of users
     *
     * @param array  $params
     *
     * @return array
     *
     * @throws \Exception
     */
    public function getAllUsers($params)
    {
        try {
            $users = $this->getModel()
                ->select('users.*')
                ->join('role_users', 'users.id', '=', 'role_users.user_id')
                ->join('roles', 'role_users.role_id', '=', 'roles.id')
                ->where('roles.slug', 'member')
                ->orderBy('users.created_at', 'desc');

            // Search by keywords
            if (!empty($params['search_by_keywords'])) {
                $users->where(function ($q) use($params) {
                    $q->where('email', 'like', '%'.$params['search_by_keywords'].'%');
                    $q->orWhere('first_name', 'like', '%'.$params['search_by_keywords'].'%');
                    $q->orWhere('last_name', 'like', '%'.$params['search_by_keywords'].'%');
                });
            }

            return $users->with(['roles'])->paginate($params['limit'])->toArray();
        } catch (\Exception $e) {
            Log::error(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'Unknown Exception thrown UserRepository@getAllUsers', [
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
     * Delete a user
     *
     * @param string $user_uid
     *
     * @return boolean
     *
     * @throws \Exception
     */
    public function delete($user_uid)
    {
        try {
            //Get user details
            $user = $this->getUserByUserUid($user_uid);


            // Check if any books not returned yet
            if ($user['no_of_books_borrowed'] > 0) {
                throw new \Exception('You can not delete this user due to he/she not returned book(s) yet');
            }

            // Delete user
            $user->delete();

            return true;
        } catch (\Exception $e) {
            Log::error(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'Unknown Exception thrown UserRepository@delete', [
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
     * Increase user borrow count by one whenever he/she borrow a book
     *
     * @return User
     *
     * @throws \Exception
     */
    public function increaseBooksBorrowCount($user)
    {
        try {
            // Update
            $user->no_of_books_borrowed   += 1;
            $user->update();

            return $user;
        } catch (\Exception $e) {
            Log::error(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'Unknown Exception thrown UserRepository@increaseUserBorrowCountById', [
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
     * Decrease user borrow count by one whenever he/she returned a book
     *
     * @return integer
     *
     * @throws \Exception
     */
    public function decreaseBooksBorrowCount($user)
    {
        try {
            // Update
            $user->no_of_books_borrowed   -= 1;
            $user->update();

            return $user;
        } catch (\Exception $e) {
            Log::error(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'Unknown Exception thrown UserRepository@decreaseUserBorrowCountByUserId', [
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
     * Renew user session by user id
     *
     * @param string $user_uid
     * @return User
     *
     * @throws \Exception
     */
    public function renewUserSessionByUserId($user_uid)
    {
        try {
            // Get user details
            $user = $this->getUserByUserUid($user_uid);

            // Update
            $user_info           = $user->toArray();
            $user_info['role']   = $user->roles()->first()->toArray();

            // Save session
            helpers::saveUser($user_info);

            return $user_info;
        } catch (\Exception $e) {
            Log::error(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'Unknown Exception thrown UserRepository@renewUserSessionByUserId', [
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
     * Check if member borrowed book already
     *
     * @param User $user
     * @return boolean
     *
     * @throws \Exception
     */
    public function isUserEligibleToBorrowBook($user)
    {
        if ((int)$user['max_books_eligible'] <= (int)$user['no_of_books_borrowed']) {
            return false;
        }

        return true;
    }
}