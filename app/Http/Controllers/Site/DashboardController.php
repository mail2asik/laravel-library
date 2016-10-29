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

/**
 * Class DashboardController
 */
class DashboardController extends Controller
{
    /**
     * Dashboard page
     *
     * @param $request
     *
     * @return View
     */
    public function index(Request $request)
    {
        return view('user/dashboard/index',[
            'page_title' => 'Dashboard',
        ]);
    }

}
