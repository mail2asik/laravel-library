<?php
/**
 * Public Controller class
 *
 * @author Asik
 * @email  mail2asik@gmal.com
 */

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

/**
 * Class PublicController
 */
class StaticController extends Controller
{
    /**
     * Home page
     *
     * @param $request
     *
     * @return View
     */
    public function home(Request $request)
    {
        return view('user.static.home',[
            'page_title' => config('app.site_name'). ' - Home',
            'page_name' => 'home'
        ]);
    }

    /**
     * About us page
     *
     * @param $request
     *
     * @return View
     */
    public function aboutUs(Request $request)
    {
        return view('user.static.about-us',[
            'page_title' => config('app.site_name'). ' - About Us',
            'page_name' => 'aboutUs'
        ]);
    }

    /**
     * Not found page
     *
     * @param $request
     *
     * @return View
     */
    public function notFound(Request $request)
    {
        abort(404);
    }

}
