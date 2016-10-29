<?php
/**
 * Auth Controller class
 *
 * @author Asik
 * @email  mail2asik@gmal.com
 */

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Redirect;
use App\Repositories\AuthRepository;
use App\Repositories\UserRepository;
use Cartalyst\Sentinel\Sentinel;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Illuminate\Support\Facades\Session;
use helpers;

/**
 * Class AuthController
 */
class AuthController extends Controller
{
    /**
     * Constructor
     *
     * @param AuthRepository    $auth
     * @param UserRepository    $user
     * @param Sentinel          $sentinel
     */
    public function __construct(AuthRepository $auth, UserRepository $user, Sentinel $sentinel)
    {
        parent::__construct();

        $this->auth     = $auth;
        $this->user     = $user;
        $this->sentinel = $sentinel;
    }

    /**
     * Login page
     *
     * @param $request
     *
     * @return View
     */
    public function login(Request $request)
    {
        return view('user/auth/login',[
            'page_title' => config('app.site_name'). ' - Login',
            'page_name' => 'login'
        ]);
    }

    /**
     * Submit a web login
     *
     * @param Request $request
     *
     * @return Redirect
     */
    public function doLogin(Request $request)
    {
        try {
            // Parse out non param data from incoming request
            $params = $request->except('_url');

            $result = $this->auth->doLogin($params);

            // Save session
            helpers::saveUser($result);

            return $this->redirectToIntendedPage($request, $result['role']['slug']);

        } catch (\Exception $e) {
            $error = helpers::errorMessage($e->getMessage());
            return Redirect::to('login')->withInput()->with('flash_message', [
                'status'        => 'fail',
                'code'          => $e->getCode(),
                'message'       => $error['message'],
                'error_fields'  => $error['error']
            ]);
        }
    }

    /**
     * Logout
     *
     * @return Redirect
     */
    public function logout()
    {
        try {
            $user = $this->sentinel->check();
            if (!empty($user)) {
                $this->sentinel->logout($user, true);
            }

            Session::flush();

            return Redirect::to('/');
        } catch (\Exception $e) {
            return Response::error('Failed to authenticate', $e->getMessage(), $e->getCode(), 401);
        }
    }

    /**
     * Show activation page after successful registration
     *
     * @param $request
     *
     * @return View
     */
    public function activation(Request $request)
    {
        return view('user/auth/activation',[
            'page_title'    => config('app.site_name'). ' - Activate Your Account',
            'page_name'     => 'activation'
        ]);
    }

    /**
     * Activate user account
     *
     * @param Request $request
     *
     * @return Redirect
     */
    public function doActivate(Request $request)
    {
        try {
            // Parse out non param data from incoming request
            $params = $request->except('_url');

            $result = $this->auth->completeActivation($params['user_uid'], $params['activation_code']);

            return Redirect::to('login')->withInput()->with('flash_message', [
                'status'  => 'success',
                'message' => 'Your account has been activated'
            ]);

        } catch (\Exception $e) {
            $error = helpers::errorMessage($e->getMessage());
            return Redirect::to('login')->withInput()->with('flash_message', [
                'status'        => 'fail',
                'code'          => $e->getCode(),
                'message'       => $error['message'],
                'error_fields'  => $error['error']
            ]);
        }
    }

    /**
     * Forgot password page
     *
     * @param $request
     *
     * @return View
     */
    public function forgot(Request $request)
    {
        return view('user/auth/forgot_password',[
            'page_title'    => config('app.site_name'). ' - Forgot Password',
            'page_name'     => 'forgot_password'
        ]);
    }

    /**
     * Submit forgot password request
     *
     * @param Request $request
     *
     * @return Redirect
     */
    public function doForgotPassword(Request $request)
    {
        try {
            // Parse out non param data from incoming request
            $params = $request->except('_url');

            $result = $this->auth->passwordReminder($params);

            return Redirect::to('forgot')->withInput()->with('flash_message', [
                'status'  => 'success',
                'message' => 'Password reset instructions have been sent to your email'
            ]);

        } catch (\Exception $e) {
            $error = helpers::errorMessage($e->getMessage());
            return Redirect::to('forgot')->withInput()->with('flash_message', [
                'status'        => 'fail',
                'code'          => $e->getCode(),
                'message'       => $error['message'],
                'error_fields'  => $error['error']
            ]);
        }
    }

    /**
     * Password reset page
     *
     * @param $request
     *
     * @return View
     */
    public function resetPassword(Request $request)
    {
        return view('user/auth/reset_password',[
            'page_title' => config('app.site_name'). ' - Forgot Password',
            'page_name'  => 'reset_password',
            'user_uid'   => $request->get('user_uid'),
            'token'      => $request->get('token')
        ]);
    }

    /**
     * Submit reset password request
     *
     * @param Request $request
     *
     * @return Redirect
     */
    public function doResetPassword(Request $request)
    {
        try {
            // Parse out non param data from incoming request
            $params = $request->except('_url');

            $result = $this->auth->passwordReset($params);

            return Redirect::to('login')->withInput()->with('flash_message', [
                'status'  => 'success',
                'message' => 'Password updated successfully'
            ]);

        } catch (\Exception $e) {
            $error = helpers::errorMessage($e->getMessage());
            return Redirect::to('reset-password?user_uid='.$request->get('user_uid').'&token='.$request->get('token'))->withInput()->with('flash_message', [
                'status'        => 'fail',
                'code'          => $e->getCode(),
                'message'       => $error['message'],
                'error_fields'  => $error['error']
            ]);
        }
    }

    /**
     * Password change page
     *
     * @param $request
     *
     * @return View
     */
    public function changePassword(Request $request)
    {
        return view('user/auth/change_password',[
            'page_title' => config('app.site_name'). ' - Change Password',
            'page_name'  => 'change_password'
        ]);
    }

    /**
     * Submit change password request
     *
     * @param Request $request
     *
     * @return Redirect
     */
    public function doChangePassword(Request $request)
    {
        try {
            $user = helpers::getUser();

            // Parse out non param data from incoming request
            $params = $request->except('_url');

            $result = $this->auth->passwordChange($params, $user['auth']['api_key']);

            return Redirect::to('change-password')->withInput()->with('flash_message', [
                'status'  => 'success',
                'message' => 'Password updated successfully'
            ]);

        } catch (\Exception $e) {
            $error = helpers::errorMessage($e->getMessage());
            return Redirect::to('change-password')->withInput()->with('flash_message', [
                'status'  => 'fail',
                'code'  => $e->getCode(),
                'message' => $error['message'],
                'error_fields' => $error['error']
            ]);
        }
    }

    /**
     * Redirect user to intended page or Dashboard page if there is no intend in session
     *
     * @param Request @request
     * @param string $role
     *
     * @return Redirect
     */
    public function redirectToIntendedPage($request, $role)
    {
        if ($request->session()->has('intended_url')) {
            //Redirect to intended url
            $intended_url = substr($request->session()->get('intended_url'), strlen(url()));

            //Remove _url fromm params
            $intended_url = explode('?', $intended_url);
            if (isset($intended_url[1])) {
                $get_params = $intended_url[1];
                parse_str($get_params, $get_params_arr);

                if (array_key_exists('_url', $get_params_arr)) {
                    unset($get_params_arr['_url']);
                }
            }

            // Concatenate the url
            if (isset($get_params_arr) && count($get_params_arr)) {
                $url_redirect = $intended_url[0] . '?' . http_build_query($get_params_arr);
            } else {
                $url_redirect = $intended_url[0];
            }

            $request->session()->forget('intended_url');

            return Redirect::to($url_redirect);
        } else {
            if ($role != 'member') {
                return Redirect::to('admin/dashboard');
            } else {
                return Redirect::to('dashboard');
            }
        }
    }
}
