<?php
/**
 * Setting Controller class
 *
 * @author Asik
 * @email  mail2asik@gmal.com
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Repositories\AuthRepository;
use App\Repositories\UserRepository;
use helpers;

/**
 * Class SettingController
 */
class SettingController extends Controller
{
    /**
     * Constructor
     *
     * @param AuthRepository    $auth
     * @param UserRepository    $user
     */
    public function __construct(AuthRepository $auth, UserRepository $user)
    {
        parent::__construct();

        $this->auth     = $auth;
        $this->user     = $user;
    }

    /**
     * Password change page
     *
     * @param Request $request
     *
     * @return View
     */
    public function changePassword(Request $request)
    {
        return view('admin/settings/change_password',[
            'page_title' => 'Change Password',
            'page_name' => 'change_password'
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

            return Redirect::to('admin/change-password')->withInput()->with('flash_message', [
                'status'  => 'success',
                'message' => 'Password updated successfully'
            ]);

        } catch (\Exception $e) {
            $error = helpers::errorMessage($e->getMessage());
            return Redirect::to('admin/change-password')->withInput()->with('flash_message', [
                'status'  => 'fail',
                'code'  => $e->getCode(),
                'message' => $error['message'],
                'error_fields' => $error['error']
            ]);
        }
    }

    /**
     * Profile change page
     *
     * @param Request $request
     *
     * @return View
     */
    public function changeProfile(Request $request)
    {
        return view('admin/settings/change_profile',[
            'page_title' => 'Profile',
            'page_name' => 'profile'
        ]);
    }

    /**
     * Submit change profile request
     *
     * @param Request $request
     *
     * @return Redirect
     */
    public function doChangeProfile(Request $request)
    {
        try {
            $user = helpers::getUser();

            // Parse out non param data from incoming request
            $params = $request->except('_url');

            $result = $this->user->update($request->all(), $user['uid'], $user['auth']['api_key']);

            // Update session
            helpers::saveUser($result);

            return Redirect::to('admin/change-profile')->with('flash_message', [
                'status'  => 'success',
                'message' => 'Profile updated successfully'
            ]);

        } catch (\Exception $e) {
            $error = helpers::errorMessage($e->getMessage());
            return Redirect::to('admin/change-profile')->withInput()->with('flash_message', [
                'status'  => 'fail',
                'code'  => $e->getCode(),
                'message' => $error['message'],
                'error_fields' => $error['error']
            ]);
        }
    }

}
