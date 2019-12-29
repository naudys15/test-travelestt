<?php

use Illuminate\Database\Seeder;
use Travelestt\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = new Role();
        $role->id = 1;
        $role->name = "admin";
        $role->description = 'Administrador';
        $role->save();

        $role = new Role();
        $role->id = 2;
        $role->name = "client";
        $role->description = 'Cliente';
        $role->save();
    }
}
