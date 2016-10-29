<?php
/**
 * Class RoleSeeder
 *
 * @author Asik
 * @email  mail2asik@gmal.com
 */

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

/**
 * Class RoleSeeder
 *
 * Seeds the different user roles.
 * permissions: [internal] indicates that the user may access data as another
 */
class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->createRoles();
    }

    public function createRoles()
    {
        Sentinel::getRoleRepository()->createModel()->create([
            'name'        => 'Admin',
            'slug'        => 'admin',
            'permissions' => [
            ]
        ]);

        Sentinel::getRoleRepository()->createModel()->create([
            'name'        => 'Member',
            'slug'        => 'member',
            'permissions' => [
            ]
        ]);
    }
}
