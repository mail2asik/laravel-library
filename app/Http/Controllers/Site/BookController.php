<?php
/**
 * Book Controller class
 *
 * @author Asik
 * @email  mail2asik@gmal.com
 */

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Repositories\BookRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Response;
use helpers;
use App\Repositories\UserRepository;

/**
 * Class BookController
 */
class BookController extends Controller
{
    /**
     * Constructor
     *
     * @param BookRepository    $book
     * @param UserRepository    $user
     */
    public function __construct(BookRepository $book, UserRepository $user)
    {
        parent::__construct();
        $this->book     = $book;
        $this->user     = $user;
    }

    /**
     * Home page
     *
     * @param $request
     *
     * @return View
     */
    public function home(Request $request)
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
            $page_url = '/?page=' . $params['page'] . '&limit=' . $params['limit'] . '&search_by_keywords=' . urlencode($params['search_by_keywords']);
            Session::put('page.public_books', url($page_url));

            // If ajax request, return specific page response
            if ($request->ajax()) {

                // Get response
                $response = $this->getBooks($params);

                return $response;
            }

            // Render books page layout
            $params['page_title'] = config('app.site_name'). ' - Home';
            $params['page_name']  = 'books';

            return view('user/books/index', $params);
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
                    $params['page'], ['path' => '/']);
                $paginator          = $paginator->appends($params);
                $books['paginator'] = $paginator;
            }

            return view('user/books/list', $books);
        } catch (\Exception $e) {
            return Response::json(['error' => true]);
        }
    }

    /**
     * Borrow a book
     *
     * @param string $book_uid
     *
     * @return Redirect
     */
    public function borrowBook($book_uid)
    {
        try {
            // Get user details
            $user = helpers::getUser();

            $book = $this->book->borrowBook($book_uid, $user['uid']);

            // Renew session to update "no_of_books_borrowed"
            // So that, our system will not allow user to borrow more than the eligible
            // $this->user->renewUserSessionByUserId($user['uid']);

            return Redirect::to('books')->with('flash_message', [
                'status'  => 'success',
                'message' => 'Book has been borrowed successfully'
            ]);
        } catch (\Exception $e) {
            $error = helpers::errorMessage($e->getMessage());
            return Redirect::to('books')->withInput()->with('flash_message', [
                'status'       => 'fail',
                'code'         => $e->getCode(),
                'message'      => $error['message'],
                'error_fields' => $error['error']
            ]);
        }
    }

    /**
     * Return a book
     *
     * @param string $book_uid
     *
     * @return Redirect
     */
    public function returnBook($book_uid)
    {
        try {
            // Get user details
            $user = helpers::getUser();

            $book = $this->book->returnBook($book_uid, $user['uid']);

            // Renew session to update "no_of_books_borrowed"
            // So that, our system will allow user to borrow if he/she has eligible
            // $this->user->renewUserSessionByUserId($user['uid']);

            return Redirect::back()->with('flash_message', [
                'status'  => 'success',
                'message' => 'Book has been returned successfully'
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

    /**
     * Get a member books
     *
     * @param $request
     *
     * @return View
     */
    public function books(Request $request)
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
            $page_url = 'books/?page=' . $params['page'] . '&limit=' . $params['limit'] . '&search_by_keywords=' . urlencode($params['search_by_keywords']);
            Session::put('page.user_books', url($page_url));

            // If ajax request, return specific page response
            if ($request->ajax()) {

                // Get response
                $response = $this->getUserBooks($params);

                return $response;
            }

            // Render books page layout
            $params['page_title'] = config('app.site_name'). ' - Books';
            $params['page_name']  = 'My Books';

            return view('user/books/userBooks', $params);
        } catch (\Exception $e) {
            return Redirect::to(helpers::getNotFoundPageURL());
        }
    }

    /**
     * Ajax: Get list of member books
     *
     * @param array $params
     *
     * @return View
     */
    public function getUserBooks($params)
    {
        try {
            // Get user details
            $user              = helpers::getUser();
            $params['user_uid'] = $user['uid'];

            $userBooks = $this->book->getUserBooks($params);

            if (!empty($userBooks['data'])) {

                //Generate paginator using Laravel paginator class
                $paginator              = new LengthAwarePaginator($userBooks['data'], $userBooks['total'], $userBooks['per_page'],
                    $params['page'], ['path' => '/books']);
                $paginator              = $paginator->appends($params);
                $userBooks['paginator'] = $paginator;
            }

            return view('user/books/userBooksList', $userBooks);
        } catch (\Exception $e) {
            return Response::json(['error' => true]);
        }
    }

}
