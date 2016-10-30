<?php
/**
 * Class ReportRepository
 *
 * @author Asik
 * @email  mail2asik@gmal.com
 */

namespace App\Repositories;

use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Book;
use App\Models\MemberBook;

/**
 * Class ReportRepository
 *
 * All reports related methods.
 */
class ReportRepository
{
    /**
     * Get a list of books
     *
     * @param array  $params
     *
     * @return array
     *
     * @throws \Exception
     */
    public function getBooksReports($params)
    {
        try {
            $userBooks = MemberBook::select([
                'users.first_name',
                'users.last_name',
                'users.uid as user_uid',
                'books.*',
                'books.uid as book_uid',
                'memberbooks.*',
                'memberbooks.uid as memberbook_uid'
            ])
                ->join('books', 'memberbooks.book_id', '=', 'books.id')
                ->join('users', 'memberbooks.user_id', '=', 'users.id')
                ->orderBy('memberbooks.created_at', 'desc');

            // Search by keywords
            if (!empty($params['search_by_keywords'])) {
                $userBooks = $userBooks->where(function ($q) use ($params) {
                    $q->where('books.title', 'like', '%' . $params['search_by_keywords'] . '%');
                    $q->orWhere('books.author', 'like', '%' . $params['search_by_keywords'] . '%');
                    $q->orWhere('books.isbn', 'like', '%' . $params['search_by_keywords'] . '%');

                    $q->orWhere('users.first_name', 'like', '%' . $params['search_by_keywords'] . '%');
                    $q->orWhere('users.last_name', 'like', '%' . $params['search_by_keywords'] . '%');
                });
            }

            // Search by book
            if (!empty($params['book_uid'])) {
                $userBooks = $userBooks->where('books.uid', $params['book_uid']);
            }

            // Search by user
            if (!empty($params['user_uid'])) {
                $userBooks = $userBooks->where('users.uid', $params['user_uid']);
            }

            return $userBooks->paginate($params['limit'])->toArray();
        } catch (\Exception $e) {
            Log::error(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'Unknown Exception thrown ReportRepository@getBooksReports', [
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
     * Get a total number of members
     *
     *
     * @return integer
     */
    public function getTotalMembers()
    {
        $members = User::select('users.id')
            ->join('role_users', 'users.id', '=', 'role_users.user_id')
            ->join('roles', 'role_users.role_id', '=', 'roles.id')
            ->where('roles.slug', 'member');

        return $members->count();
    }

    /**
     * Get a total number of books
     *
     * @return integer
     */
    public function getTotalBooks()
    {
        $books = Book::select('quantity');

        return $books->sum('quantity');
    }

    /**
     * Get a total number of books borrowed
     *
     * @return integer
     */
    public function getTotalBooksBorrowed()
    {
        $booksBorrowed = User::select('no_of_books_borrowed');

        return $booksBorrowed->sum('no_of_books_borrowed');
    }
}