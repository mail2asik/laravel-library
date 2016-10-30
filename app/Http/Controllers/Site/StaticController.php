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
     * Services page
     *
     * @param $request
     *
     * @return View
     */
    public function services(Request $request)
    {
        return view('user.static.services',[
            'page_title' => config('app.site_name'). ' - Services',
            'page_name' => 'services'
        ]);
    }

    /**
     * Winners page
     *
     * @param $request
     *
     * @return View
     */
    public function winners(Request $request)
    {
        return view('user.static.winners',[
            'page_title' => config('app.site_name'). ' - Winners',
            'page_name' => 'winners'
        ]);
    }

    /**
     * Jobs page
     *
     * @param $request
     *
     * @return View
     */
    public function jobs(Request $request)
    {
        return view('user.static.jobs',[
            'page_title' => config('app.site_name'). ' - Jobs',
            'page_name' => 'jobs'
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
