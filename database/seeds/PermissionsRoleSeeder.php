<?php

use Illuminate\Database\Seeder;
use Travelestt\Models\Permission_role;

class PermissionsRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 84; $i++) {
            $permissions_role = new Permission_role();
            $permissions_role->id = $i;
            $permissions_role->id = 1;
            $permissions_role->id = $i;
            $permissions_role->id = 3;
            $permissions_role->save();
        }

        for ($i = 1; $i <= 84; $i++) {
            $permissions_role = new Permission_role();
            $permissions_role->id = ($i+84);
            $permissions_role->id = 2;
            $permissions_role->id = $i;
            $permissions_role->id = 1;
            $permissions_role->save();
        }
    }
}
