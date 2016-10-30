<?php
/**
 * Dashboard Controller class
 *
 * @author Asik
 * @email  mail2asik@gmal.com
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Repositories\ReportRepository;

/**
 * Class DashboardController
 */
class DashboardController extends Controller
{
    /**
     * Constructor
     *
     * @param ReportRepository    $report
     */
    public function __construct(ReportRepository $report)
    {
        parent::__construct();
        $this->report     = $report;
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
        return view('admin/dashboard/index',[
            'page_title'                => 'Dashboard',
            'number_of_members'         => $this->report->getTotalMembers(),
            'number_of_books'           => $this->report->getTotalBooks(),
            'number_of_books_borrowed'  => $this->report->getTotalBooksBorrowed()
        ]);
    }

}
