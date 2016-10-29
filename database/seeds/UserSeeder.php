<?php
/**
 * Class UserSeeder
 *
 * @author Asik
 * @email  mail2asik@gmal.com
 */

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use App\Models\Api;
use App\Repositories\ApiRepository;
use App\Models\Country;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Repositories\AuthRepository;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;


/**
 * Class UserSeeder
 *
 * Seeds the different sandbox user.
 */
class UserSeeder extends Seeder
{
    public function __construct(
        UserRepository $user,
        AuthRepository $auth,
        ApiRepository $api
    ) {
        $this->user         = $user;
        $this->auth         = $auth;
        $this->api          = $api;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get sandbox accounts and merge
        $user_sandbox = File::getRequire(base_path('/database/seeds/UserSeeder.sandboxAccounts.php'));

        // Disable mailer
        Mail::pretend(true);

        // Initial Processing of user seeding
        foreach ($user_sandbox as $k => $v) {
            $this->createUser($v);
        }
    }

    public function createUser($param)
    {
        // Create the user
        $user = $this->user->create($param['data']);

        // Update user uid
        $user->uid = $param['uid'];
        $user->save();

        // Update and use this api keys
        $api             = $this->api->getApiByUserId($user->id);
        $api->api_key    = $param['api_key'];
        $api->api_secret = $param['api_secret'];
        $api->save();

        // Activate
        if ($param['is_activated'] == 1) {
            // Activate this user
            $activation_code = $user->activations()->first()->code;
            $this->auth->completeActivation($user->uid, $activation_code);
        }
    }
}
