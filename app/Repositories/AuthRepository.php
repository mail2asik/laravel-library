<?php
/**
 * Class AuthRepository
 *
 * @author Asik
 * @email  mail2asik@gmal.com
 */

namespace App\Repositories;

use App\Models\Api;
use App\Models\User;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Cartalyst\Sentinel\Laravel\Facades\Reminder;
use Cartalyst\Sentinel\Hashing\CallbackHasher;
use Cartalyst\Sentinel\Hashing\NativeHasher;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Log;
use App\Exceptions\AuthenticationException;

/**
 * Class AuthRepository
 *
 * All authentication methods.
 */
class AuthRepository
{
    /**
     * @param UserRepository     $user
     */
    public function __construct(UserRepository $user) {
        $this->user = $user;
    }

    /**
     * Do login
     *
     * @param array  $input
     *
     * @return array
     *
     * @throws \Exception
     */
    public function doLogin($input)
    {
        try {
            $requirements = [
                'email'      => 'required|email',
                'password'   => 'required|min:6'
            ];

            $validator = \Validator::make($input, $requirements);

            if ($validator->fails()) {
                throw new \Exception($validator->messages(), 40002001);
            }

            $credentials = [
                "email"    => $input['email'],
                "password" => $input['password']
            ];

            if (!$user = Sentinel::authenticate($credentials)) {
                throw new \Exception("An email password combination might be incorrect", 40101002);
            }

            $user_info                 = $user->toArray();
            $user_info['auth']         = $user->api()->first()->toArray();
            $user_info['role']         = $user->roles()->first()->toArray();

            return $user_info;
        } catch (\Exception $e) {
            Log::error(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'Unknown Exception thrown AuthRepository@doLogin', [
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
     * Completes the activation process
     *
     * @param string $user_uid
     * @param string $activation_code
     *
     * @return boolean
     *
     * @throws \Exception
     */
    public function completeActivation($user_uid, $activation_code)
    {
        try {
            $user = User::where('uid', $user_uid)->first();

            if (!$user) {
                throw new \Exception('User not found', 40102001);
            }

            if (!Activation::complete($user, $activation_code)) {
                throw new \Exception('Missing or invalid activation code', 40301004);
            }

            return true;
        } catch (\Exception $e) {
            Log::error(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'Unknown Exception thrown AuthRepository@completeActivation', [
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
     * Sends a password reminder
     *
     * @param Request $input
     *
     * @return boolean
     *
     * @throws \Exception
     */
    public function passwordReminder($input)
    {
        try {
            $requirements = [
                'email'      => 'required|email'
            ];

            $validator = \Validator::make($input, $requirements);
            if ($validator->fails()) {
                throw new \Exception($validator->messages(), 40001005);
            }

            // Get User by the email
            $user = User::where('email', $input['email'])->first();

            if (!$user) {
                throw new \Exception('Email does not exist', 40102002);
            }

            $reminder = Reminder::create($user);

            // Send reminder email
            Event::fire('mailPasswordReminder', [
                'email' => $user['email'],
                'name'  => $user['first_name'],
                'url'   => getenv('SITE_URL') . '/reset-password?user_uid=' . $user->uid . '&token=' . $reminder['code']
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'Unknown Exception thrown AuthRepository@passwordReminder', [
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
     * Reset password based on token (code)
     *
     * @param array  $params
     *
     * @return boolean
     *
     * @throws \Exception
     */
    public function passwordReset($params)
    {
        try {
            $user = $this->user->getUserByUserUid($params['user_uid']);

            // Check if password and confirm password matches
            if ($params['password'] !== $params['confirm_password']) {
                throw new \Exception('New Passwords does not match', 40001006);
            }

            if (!Reminder::complete($user, $params['token'], $params['password'])) {
                throw new \Exception('Expired or invalid token', 40301007);
            }

            return true;
        } catch (\Exception $e) {
            Log::error(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'Unknown Exception thrown AuthRepository@passwordReset', [
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
     * Change password
     *
     * @param array $params
     * @param string $api_key
     *
     * @return void
     *
     * @throws \Exception
     */
    public function passwordChange($params, $api_key)
    {
        try {
            $user = $this->user->getUserByApiKey($api_key);

            $credentials = [
                "email"    => $user['email'],
                "password" => $params['password']
            ];

            if (!$user = Sentinel::authenticate($credentials)) {
                $error['password'] = "Incorrect password";
                throw new \Exception(json_encode($error), 40001008);
            }

            $requirements = ['new_password' => 'required|min:6'];
            $validator    = Validator::make($params, $requirements);
            if ($validator->fails()) {
                $messages = $validator->messages();
                throw new \Exception($messages, 40001008);
            }

            // Check if password and confirm password matches
            if ($params['new_password'] !== $params['confirm_password']) {
                $error['confirm_password'] = "New Passwords does not match";
                throw new \Exception(json_encode($error), 40001008);
            }

            $sentinelHasher = new NativeHasher;
            $user->password = $sentinelHasher->hash($params['new_password']);
            $user->save();
        } catch (\Exception $e) {
            Log::error(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'Unknown Exception thrown AuthRepository@passwordChange', [
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