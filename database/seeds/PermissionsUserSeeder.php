<?php

use Illuminate\Database\Seeder;
use Travelestt\Models\Permission_user;

class PermissionsUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 84; $i++) {
            $permissions_user = new Permission_user();
            $permissions_user->id = $i;
            $permissions_user->id = 1;
            $permissions_user->id = $i;
            $permissions_user->id = 3;
            $permissions_user->save();
        }

        for ($i = 1; $i <= 84; $i++) {
            $permissions_user = new Permission_user();
            $permissions_user->id = ($i+84);
            $permissions_user->id = 2;
            $permissions_user->id = $i;
            $permissions_user->id = 1;
            $permissions_user->save();
        }
    }
}
