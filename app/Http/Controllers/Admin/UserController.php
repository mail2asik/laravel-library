<?php
/**
 * User Controller class
 *
 * @author Asik
 * @email  mail2asik@gmal.com
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use helpers;
use Illuminate\Support\Facades\Session;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\UserRepository;
use Illuminate\View\View;

/**
 * Class UserController
 */
class UserController extends Controller
{
    /**
     * Constructor
     *
     * @param UserRepository    $user
     */
    public function __construct(UserRepository $user)
    {
        parent::__construct();

        $this->user     = $user;
    }

    /**
     * Get list of users
     *
     * @param Request $request
     *
     * @return View
     */
    public function index(Request $request)
    {
        try {
            $user = helpers::getUser();

            // Prepare Params
            $params['search_by_keywords'] = $request->get('search_by_keywords', '');
            $params['page']               = $request->get('page', 1);
            $params['limit']              = $request->get('limit', 10);
            $params['role']               = $request->get('role', '');

            // Check if someone send wrong limit purposely
            $allowed_params = [5, 10, 25, 50, 100];
            if (!in_array($params['limit'], $allowed_params)) {
                $params['limit'] = 10;
            }

            // Check if someone pass wrong page purposely
            if ($params['page'] != 1 && !filter_var($params['page'], FILTER_VALIDATE_INT)) {
                $params['page'] = 1;
            }

            // Save the last page requested
            $page_url = '/admin/users?page=' . $params['page'] . '&limit=' . $params['limit'] . '&role=' . $params['role'] . '&search_by_keywords=' . urlencode($params['search_by_keywords']);
            Session::put('page.users', url($page_url));

            // If ajax request, return specific page response
            if ($request->ajax()) {

                // Get response
                $response = $this->getUsers($params);

                return $response;
            }

            // Render users page layout
            $params['page_title'] = 'Members';
            $params['page_name']  = 'members';

            return view('admin/users/index', $params);
        } catch (\Exception $e) {
            return Redirect::to(helpers::getNotFoundPageURL());
        }
    }

    /**
     * Ajax: Get list of users
     *
     * @param array $params
     *
     * @return View
     */
    public function getUsers($params)
    {
        try {
            $user = helpers::getUser();

            $users = $this->user->getAllUsers($params);

            if (!empty($users['data'])) {

                //Generate paginator using Laravel paginator class
                $paginator          = new LengthAwarePaginator($users['data'], $users['total'], $users['per_page'],
                    $params['page'], ['path' => '/admin/users']);
                $paginator          = $paginator->appends($params);
                $users['paginator'] = $paginator;
            }

            return view('admin/users/list', $users);
        } catch (\Exception $e) {
            return Response::json(['error' => true]);
        }
    }

    /**
     * Create user
     *
     * @param $request
     *
     * @return View
     */
    public function create(Request $request)
    {
        $user = helpers::getUser();

        return view('admin/users/create', [
            'page_title' => 'Add Member',
            'page_name'  => 'add_member'
        ]);
    }

    /**
     * Submit create user request
     *
     * @param Request $request
     *
     * @return Redirect
     */
    public function doCreateUser(Request $request)
    {
        try {
            $user = helpers::getUser();

            // Parse out non param data from incoming request
            $params = $request->except('_url');

            // We can reuse this controller for future for adding anothe role users
            $params['role'] = 'member';

            $result = $this->user->create($params);

            return Redirect::to('admin/users/create')->with('flash_message', [
                'status'  => 'success',
                'message' => 'Member created successfully'
            ]);
        } catch (\Exception $e) {
            $error = helpers::errorMessage($e->getMessage());
            return Redirect::to('admin/users/create')->withInput()->with('flash_message', [
                'status'       => 'fail',
                'code'         => $e->getCode(),
                'message'      => $error['message'],
                'error_fields' => $error['error']
            ]);
        }
    }

    /**
     * Update user
     *
     * @param string  $user_uid
     * @param Request $request
     *
     * @return View
     */
    public function update($user_uid, Request $request)
    {
        $user = $this->user->getUserByUserUid($user_uid);

        return view('admin/users/update', [
            'page_title' => 'Edit Member',
            'page_name'  => 'edit_member',
            'user'       => $user
        ]);
    }

    /**
     * Submit update user request
     *
     * @param string  $user_uid
     * @param Request $request
     *
     * @return Redirect
     */
    public function doUpdateUser($user_uid, Request $request)
    {
        try {
            // Parse out non param data from incoming request
            $params = $request->except('_url');

            // We can reuse this controller for future for adding another role users
            $params['role'] = 'member';

            $user = $this->user->update($params, $user_uid);

            return Redirect::to('admin/users/update/' . $user_uid)->with('flash_message', [
                'status'  => 'success',
                'message' => 'Member updated successfully'
            ]);
        } catch (\Exception $e) {
            $error = helpers::errorMessage($e->getMessage());
            return Redirect::to('admin/users/update/' . $user_uid)->withInput()->with('flash_message', [
                'status'       => 'fail',
                'code'         => $e->getCode(),
                'message'      => $error['message'],
                'error_fields' => $error['error']
            ]);
        }
    }

    /**
     * Delete a member
     *
     * @param string  $user_uid
     *
     * @return Redirect
     */
    public function delete($user_uid)
    {
        try {
            $user = $this->user->delete($user_uid);

            return Redirect::to('admin/users')->with('flash_message', [
                'status'  => 'success',
                'message' => 'Member has been deleted successfully'
            ]);
        } catch (\Exception $e) {
            $error = helpers::errorMessage($e->getMessage());
            return Redirect::to('admin/users/update/' . $user_uid)->withInput()->with('flash_message', [
                'status'       => 'fail',
                'code'         => $e->getCode(),
                'message'      => $error['message'],
                'error_fields' => $error['error']
            ]);
        }
    }
}
