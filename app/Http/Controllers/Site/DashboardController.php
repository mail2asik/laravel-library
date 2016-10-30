<?php
/**
 * Dashboard Controller class
 *
 * @author Asik
 * @email  mail2asik@gmal.com
 */

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Repositories\UserRepository;
use helpers;

/**
 * Class DashboardController
 */
class DashboardController extends Controller
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
     * Dashboard page
     *
     * @param $request
     *
     * @return View
     */
    public function index(Request $request)
    {
        $user_session = helpers::getUser();

        $user = $this->user->getUserByUserUid($user_session['uid']);

        return view('user/dashboard/index',[
            'page_title'            => 'Dashboard',
            'no_of_books_borrowed'  => $user['no_of_books_borrowed'],
            'max_books_eligible'    => $user['max_books_eligible'],
        ]);
    }

}
