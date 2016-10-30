<?php
/**
 * Class BookSeeder
 *
 * @author Asik
 * @email  mail2asik@gmal.com
 */

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BookRepository;
use Illuminate\Support\Facades\File;


/**
 * Class BookSeeder
 *
 * Seeds the test book details.
 */
class BookSeeder extends Seeder
{
    public function __construct(BookRepository $book) {
        $this->book         = $book;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get sandbox accounts and merge
        $books = File::getRequire(base_path('/database/seeds/BookSeeder.sandboxBooks.php'));

        // Initial Processing of user seeding
        foreach ($books as $k => $v) {
            $this->createBook($v);
        }
    }

    public function createBook($param)
    {
        // Create the user
        $book = $this->book->create($param);
    }
}
