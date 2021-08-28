<?php

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $permissions = config('permissions');

        foreach($permissions as $key => $permission) {

            foreach($permission as $perm) {

                Permission::updateOrCreate([
                    'name'  => $perm['key'],
                    'guard_name'    => 'backpack',
                ],
                [
                    'label'     => $perm['label']
                ]);

            }

        }

    }
}
