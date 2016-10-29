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
     * @param Book  $book
     */
    public function __construct(Book $book) {
        $this->setModel($book);
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

            // Update users table
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
                    ->orWhere('isbn', 'like', '%'.$params['search_by_keywords'].'%')
                    ->orWhere('shelf_location', 'like', '%'.$params['search_by_keywords'].'%');
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
}