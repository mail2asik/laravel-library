<?php
/**
 * Report Controller class
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
use App\Repositories\ReportRepository;
use Illuminate\View\View;

/**
 * Class ReportController
 */
class ReportController extends Controller
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
     * Get list of books transaction
     *
     * @param Request $request
     *
     * @return View
     */
    public function index(Request $request)
    {
        try {
            // Prepare Params
            $params['search_by_keywords'] = $request->get('search_by_keywords', '');
            $params['page']               = $request->get('page', 1);
            $params['limit']              = $request->get('limit', 10);
            $params['user_uid']           = $request->get('user_uid', '');
            $params['book_uid']           = $request->get('book_uid', '');

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
            $page_url = '/admin/books?page=' . $params['page'] . '&limit=' . $params['limit'] . '&user_uid=' . $params['user_uid'] . '&book_uid=' . $params['book_uid'] . '&search_by_keywords=' . urlencode($params['search_by_keywords']);
            Session::put('page.books', url($page_url));

            // If ajax request, return specific page response
            if ($request->ajax()) {

                // Get response
                $response = $this->getBooksReports($params);

                return $response;
            }

            // Render books page layout
            $params['page_title'] = 'Reports';
            $params['page_name']  = 'reports';

            return view('admin/reports/index', $params);
        } catch (\Exception $e) {
            dd($e);
            return Redirect::to(helpers::getNotFoundPageURL());
        }
    }

    /**
     * Ajax: Get list of books reports
     *
     * @param array $params
     *
     * @return View
     */
    public function getBooksReports($params)
    {
        try {
            $reports = $this->report->getBooksReports($params);

            if (!empty($reports['data'])) {

                //Generate paginator using Laravel paginator class
                $paginator          = new LengthAwarePaginator($reports['data'], $reports['total'], $reports['per_page'],
                    $params['page'], ['path' => '/admin/reports']);
                $paginator          = $paginator->appends($params);
                $reports['paginator'] = $paginator;
            }

            return view('admin/reports/list', $reports);
        } catch (\Exception $e) {
            dd($e);
            return Response::json(['error' => true]);
        }
    }
}