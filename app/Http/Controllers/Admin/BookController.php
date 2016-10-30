<?php
/**
 * Book Controller class
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
use App\Repositories\BookRepository;
use Illuminate\View\View;

/**
 * Class BookController
 */
class BookController extends Controller
{
    /**
     * Constructor
     *
     * @param BookRepository    $book
     */
    public function __construct(BookRepository $book)
    {
        parent::__construct();

        $this->book     = $book;
    }

    /**
     * Get list of books
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
            $page_url = '/admin/books?page=' . $params['page'] . '&limit=' . $params['limit'] . '&search_by_keywords=' . urlencode($params['search_by_keywords']);
            Session::put('page.books', url($page_url));

            // If ajax request, return specific page response
            if ($request->ajax()) {

                // Get response
                $response = $this->getBooks($params);

                return $response;
            }

            // Render books page layout
            $params['page_title'] = 'Books';
            $params['page_name']  = 'books';

            return view('admin/books/index', $params);
        } catch (\Exception $e) {
            return Redirect::to(helpers::getNotFoundPageURL());
        }
    }

    /**
     * Ajax: Get list of books
     *
     * @param array $params
     *
     * @return View
     */
    public function getBooks($params)
    {
        try {
            $books = $this->book->getAllBooks($params);

            if (!empty($books['data'])) {

                //Generate paginator using Laravel paginator class
                $paginator          = new LengthAwarePaginator($books['data'], $books['total'], $books['per_page'],
                    $params['page'], ['path' => '/admin/books']);
                $paginator          = $paginator->appends($params);
                $books['paginator'] = $paginator;
            }

            return view('admin/books/list', $books);
        } catch (\Exception $e) {
            return Response::json(['error' => true]);
        }
    }

    /**
     * Create book
     *
     * @param $request
     *
     * @return View
     */
    public function create(Request $request)
    {
        return view('admin/books/create', [
            'page_title' => 'Add Book',
            'page_name'  => 'add_book'
        ]);
    }

    /**
     * Create a book (Submission)
     *
     * @param Request $request
     *
     * @return Redirect
     */
    public function doCreateBook(Request $request)
    {
        try {
            // Parse out non param data from incoming request
            $params = $request->except('_url');

            $result = $this->book->create($params);

            return Redirect::to('admin/books/create')->with('flash_message', [
                'status'  => 'success',
                'message' => 'Book created successfully'
            ]);
        } catch (\Exception $e) {
            $error = helpers::errorMessage($e->getMessage());
            return Redirect::to('admin/books/create')->withInput()->with('flash_message', [
                'status'       => 'fail',
                'code'         => $e->getCode(),
                'message'      => $error['message'],
                'error_fields' => $error['error']
            ]);
        }
    }

    /**
     * Update a book
     *
     * @param string  $book_uid
     *
     * @return View
     */
    public function update($book_uid)
    {
        $book = $this->book->getBookByUid($book_uid);

        return view('admin/books/update', [
            'page_title' => 'Edit Book',
            'page_name'  => 'edit_book',
            'book'       => $book
        ]);
    }

    /**
     * Update a book (Submission)
     *
     * @param string  $book_uid
     * @param Request $request
     *
     * @return Redirect
     */
    public function doUpdateBook($book_uid, Request $request)
    {
        try {
            // Parse out non param data from incoming request
            $params = $request->except('_url');

            $book = $this->book->update($params, $book_uid);

            return Redirect::to('admin/books/update/' . $book_uid)->with('flash_message', [
                'status'  => 'success',
                'message' => 'Book updated successfully'
            ]);
        } catch (\Exception $e) {
            $error = helpers::errorMessage($e->getMessage());
            return Redirect::to('admin/books/update/' . $book_uid)->withInput()->with('flash_message', [
                'status'       => 'fail',
                'code'         => $e->getCode(),
                'message'      => $error['message'],
                'error_fields' => $error['error']
            ]);
        }
    }

    /**
     * Delete a book
     *
     * @param string  $book_uid
     *
     * @return Redirect
     */
    public function delete($book_uid)
    {
        try {
            $book = $this->book->delete($book_uid);

            return Redirect::to('admin/books')->with('flash_message', [
                'status'  => 'success',
                'message' => 'Book has been deleted successfully'
            ]);
        } catch (\Exception $e) {
            $error = helpers::errorMessage($e->getMessage());
            return Redirect::to('admin/books/update/' . $book_uid)->withInput()->with('flash_message', [
                'status'       => 'fail',
                'code'         => $e->getCode(),
                'message'      => $error['message'],
                'error_fields' => $error['error']
            ]);
        }
    }

    /**
     * Collect a book
     *
     * @param string $book_uid
     * @param string $user_uid
     *
     * @return Redirect
     */
    public function collectBook($book_uid, $user_uid)
    {
        try {
            $book = $this->book->returnBook($book_uid, $user_uid);

            return Redirect::back()->with('flash_message', [
                'status'  => 'success',
                'message' => 'Book has been collected successfully'
            ]);
        } catch (\Exception $e) {
            $error = helpers::errorMessage($e->getMessage());
            return Redirect::back()->withInput()->with('flash_message', [
                'status'       => 'fail',
                'code'         => $e->getCode(),
                'message'      => $error['message'],
                'error_fields' => $error['error']
            ]);
        }
    }
}
