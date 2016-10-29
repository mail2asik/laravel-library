<?php
/**
 * Class BookRepository
 *
 * @author Asik
 * @email  mail2asik@gmal.com
 */

namespace App\Repositories;

use App\Models\Book;
use Illuminate\Support\Facades\Cache;
use Webpatser\Uuid\Uuid;
use Illuminate\Support\Facades\Log;
use App\Models\MemberBook;
use helpers;
use Carbon\Carbon;

/**
 * Class BookRepository
 *
 * All Book methods.
 */
class BookRepository
{
    /**
     * This is a Repository that depends on a Model
     */
    use ModelTrait;

    /**
     * @param Book              $book
     * @param UserRepository    $user
     */
    public function __construct(Book $book, UserRepository $user) {
        $this->setModel($book);
        $this->user = $user;
    }

    /**
     * Get a book by uid
     *
     * @param $book_uid
     *
     * @return User
     *
     * @throws \Exception
     */
    public function getBookByUid($book_uid)
    {
        try {
            $book = $this->getModel()->where('uid', $book_uid)->first();

            if (empty($book)) {
                throw new \Exception('Book not found');
            }

            return $book;
        } catch (\Exception $e) {
            Log::error(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'Unknown Exception thrown BookRepository@getBookByUid', [
                'exception_type' => get_class($e),
                'message'        => $e->getMessage(),
                'code'           => $e->getCode(),
                'line_no'        => $e->getLine(),
                'params'         => func_get_args()
            ]);

            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Create a book
     *
     * @param array $params
     *
     * @return array
     *
     * @throws \Exception
     */
    public function create($params)
    {
        try {

            // Validation
            $requirements = [
                'title'            => 'required|min:3|unique:books',
                'author'           => 'required|min:3',
                'isbn'             => 'required',
                'quantity'         => 'required',
                'shelf_location'   => 'required'
            ];
            $messages = [
                'title.unique' => 'The book has been added already',
            ];
            $validator = \Validator::make($params, $requirements, $messages);
            if ($validator->fails()) {
                throw new \Exception($validator->messages());
            }

            // Store data
            $book                  = $this->getModel();
            $book->uid             = Uuid::generate(4);
            $book->title           = $params['title'];
            $book->author          = $params['author'];
            $book->isbn            = $params['isbn'];
            $book->quantity        = $params['quantity'];
            $book->shelf_location  = $params['shelf_location'];
            $book->save();

            return $book;
        } catch (\Exception $e) {
            Log::error(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'Unknown Exception thrown BookRepository@create', [
                'exception_type' => get_class($e),
                'message'        => $e->getMessage(),
                'code'           => $e->getCode(),
                'line_no'        => $e->getLine(),
                'params'         => func_get_args()
            ]);

            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Update a book
     *
     * @param array     $params
     * @param string    $book_uid
     *
     * @return array
     *
     * @throws \Exception
     */
    public function update($params, $book_uid)
    {
        try {
            //Get user details
            $book = $this->getBookByUid($book_uid);

            // Validation
            $requirements = [
                'title'           => 'required|min:3|unique:books,title,'. $book_uid . ',uid',
                'author'          => 'required|min:3',
                'isbn'            => 'required',
                'quantity'        => 'required',
                'shelf_location'  => 'required'
            ];
            $validator = \Validator::make($params, $requirements);
            if ($validator->fails()) {
                throw new \Exception($validator->messages());
            }

            // Update
            $book->title          = $params['title'];
            $book->author         = $params['author'];
            $book->isbn           = $params['isbn'];
            $book->quantity       = $params['quantity'];
            $book->shelf_location = $params['shelf_location'];
            $book->update();

            return $book;
        } catch (\Exception $e) {
            Log::error(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'Unknown Exception thrown BookRepository@update', [
                'exception_type' => get_class($e),
                'message'        => $e->getMessage(),
                'code'           => $e->getCode(),
                'line_no'        => $e->getLine(),
                'params'         => func_get_args()
            ]);

            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Get a list of books
     *
     * @param array  $params
     *
     * @return array
     *
     * @throws \Exception
     */
    public function getAllBooks($params)
    {
        try {
            $books = $this->getModel();

            // Search by keywords
            if (!empty($params['search_by_keywords'])) {
                $books = $books->where('title', 'like', '%'.$params['search_by_keywords'].'%')
                    ->orWhere('author', 'like', '%'.$params['search_by_keywords'].'%')
                    ->orWhere('isbn', 'like', '%'.$params['search_by_keywords'].'%');
            }

            return $books->paginate($params['limit'])->toArray();
        } catch (\Exception $e) {
            Log::error(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'Unknown Exception thrown BookRepository@getAllBooks', [
                'exception_type' => get_class($e),
                'message'        => $e->getMessage(),
                'code'           => $e->getCode(),
                'line_no'        => $e->getLine(),
                'params'         => func_get_args()
            ]);

            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Get a list of user books
     *
     * @param array  $params
     *
     * @return array
     *
     * @throws \Exception
     */
    public function getUserBooks($params)
    {
        try {
            // Get user details
            $user = $this->user->getUserByUserUid($params['user_uid']);

            $userBooks = Book::select(['books.*', 'memberbooks.*', 'books.uid as book_uid', 'memberbooks.uid as memberbook_uid'])
                ->join('memberbooks', 'books.id', '=', 'memberbooks.book_id')
                ->where('memberbooks.user_id', $user['id']);

            // Search by keywords
            if (!empty($params['search_by_keywords'])) {
                $userBooks = $userBooks->where('title', 'like', '%'.$params['search_by_keywords'].'%')
                    ->orWhere('author', 'like', '%'.$params['search_by_keywords'].'%')
                    ->orWhere('isbn', 'like', '%'.$params['search_by_keywords'].'%');
            }

            return $userBooks->paginate($params['limit'])->toArray();
        } catch (\Exception $e) {
            Log::error(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'Unknown Exception thrown BookRepository@getUserBooks', [
                'exception_type' => get_class($e),
                'message'        => $e->getMessage(),
                'code'           => $e->getCode(),
                'line_no'        => $e->getLine(),
                'params'         => func_get_args()
            ]);

            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Delete a book
     *
     * @param string $book_uid
     *
     * @return boolean
     *
     * @throws \Exception
     */
    public function delete($book_uid)
    {
        try {
            //Get book details
            $book = $this->getBookByUid($book_uid);

            // Delete book
            $book->delete();

            return true;
        } catch (\Exception $e) {
            Log::error(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'Unknown Exception thrown BookRepository@delete', [
                'exception_type' => get_class($e),
                'message'        => $e->getMessage(),
                'code'           => $e->getCode(),
                'line_no'        => $e->getLine(),
                'params'         => func_get_args()
            ]);

            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Borrow a book
     *
     * @param string $book_uid
     * @param string $user_uid
     *
     * @return boolean
     *
     * @throws \Exception
     */
    public function borrowBook($book_uid, $user_uid)
    {
        try {
            // Get user details
            $user = $this->user->getUserByUserUid($user_uid);

            //Get book details
            $book = $this->getBookByUid($book_uid);

            // Check quantity
            if ($book['quantity'] <= $book['no_of_books_loan']) {
                throw new \Exception('This books is not available currently. Please check later.');
            }

            // Check if user have a eligible to borrow books
            if (!$this->user->isUserEligibleToBorrowBook()) {
                throw new \Exception('You are exceeded the limit to borrow additional books');
            }

            // Check if book already borrowed
            if ($this->isBookBorrowedAlready($book['id'], $user['id'])) {
                throw new \Exception('You have borrowed this book already');
            }

            // Store data
            $memberBook             = new MemberBook();
            $memberBook->uid        = Uuid::generate(4);
            $memberBook->user_id    = $user['id'];
            $memberBook->book_id    = $book['id'];
            $memberBook->started_at = Carbon::now()->toDateTimeString();
            $memberBook->ended_at   = Carbon::now()->addDays(config('constants.book_loan_duration_in_days'))->toDateTimeString();
            $memberBook->status     = config('constants.member_book_statuses')[0];
            $memberBook->save();

            // Increase user books borrow count
            $this->user->increaseBooksBorrowCount($user);

            // Increase books loan count
            $this->increaseBooksLoanCount($book);

            return true;
        } catch (\Exception $e) {
            Log::error(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'Unknown Exception thrown BookRepository@delete', [
                'exception_type' => get_class($e),
                'message'        => $e->getMessage(),
                'code'           => $e->getCode(),
                'line_no'        => $e->getLine(),
                'params'         => func_get_args()
            ]);

            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Return a book
     *
     * @param string $book_uid
     * @param string $user_uid
     *
     * @return boolean
     *
     * @throws \Exception
     */
    public function returnBook($book_uid, $user_uid)
    {
        try {
            // Get user details
            $user = $this->user->getUserByUserUid($user_uid);

            //Get book details
            $book = $this->getBookByUid($book_uid);

            // Check if book already borrowed
            $memberBook = $this->getBorrowedBook($book['id'], $user['id']);
            if (!$memberBook) {
                throw new \Exception('You had not borrowed this book before');
            }

            // Update data
            $memberBook->status = config('constants.member_book_statuses')[1];
            $memberBook->save();

            // Decrease user books borrow count
            $this->user->decreaseBooksBorrowCount($user);

            // Decrease books loan count
            $this->decreaseBooksLoanCount($book);

            return true;
        } catch (\Exception $e) {
            Log::error(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'Unknown Exception thrown BookRepository@delete', [
                'exception_type' => get_class($e),
                'message'        => $e->getMessage(),
                'code'           => $e->getCode(),
                'line_no'        => $e->getLine(),
                'params'         => func_get_args()
            ]);

            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Check if member borrowed book already
     *
     * @param string $book_id
     * @param string $user_id
     *
     * @return boolean
     *
     * @throws \Exception
     */
    protected function isBookBorrowedAlready($book_id, $user_id)
    {
        $book = MemberBook::where('book_id', $book_id)
            ->where('user_id', $user_id)
            ->where('status', config('constants.member_book_statuses')[0])
            ->first();
        if (!empty($book)) {
            return true;
        }

        return false;
    }

    /**
     * Get member borrowed book
     *
     * @param string $book_id
     * @param string $user_id
     *
     * @return mixed
     *
     * @throws \Exception
     */
    protected function getBorrowedBook($book_id, $user_id)
    {
        $book = MemberBook::where('book_id', $book_id)
            ->where('user_id', $user_id)
            ->where('status', config('constants.member_book_statuses')[0])
            ->first();
        if (!empty($book)) {
            return $book;
        }

        return false;
    }

    /**
     * Increase book loan count whenever member borrow a book
     *
     * @param Book $book
     * @return Book
     *
     * @throws \Exception
     */
    public function increaseBooksLoanCount($book)
    {
        try {
            // Update
            $book->no_of_books_loan   += 1;
            $book->update();

            return $book;
        } catch (\Exception $e) {
            Log::error(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'Unknown Exception thrown BookRepository@increaseBooksLoanCount', [
                'exception_type' => get_class($e),
                'message'        => $e->getMessage(),
                'code'           => $e->getCode(),
                'line_no'        => $e->getLine(),
                'params'         => func_get_args()
            ]);

            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Decrease book loan count by one whenever he/she returned a book
     *
     * @param Book $book
     * @return Book
     *
     * @throws \Exception
     */
    public function decreaseBooksLoanCount($book)
    {
        try {
            // Update
            $book->no_of_books_loan   -= 1;
            $book->update();

            return $book;
        } catch (\Exception $e) {
            Log::error(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'Unknown Exception thrown BookRepository@decreaseBooksLoanCount', [
                'exception_type' => get_class($e),
                'message'        => $e->getMessage(),
                'code'           => $e->getCode(),
                'line_no'        => $e->getLine(),
                'params'         => func_get_args()
            ]);

            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

}