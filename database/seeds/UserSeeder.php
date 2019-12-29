<?php

use Illuminate\Database\Seeder;
use Travelestt\Models\User;
use Travelestt\Models\City;
use Travelestt\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::where('name', '=', 'admin')->first();
        $city = City::where('slug', '=', 'alcoy')->first();
        $user = new User();
        $user->id = 1;
        $user->firstname = 'Administrador';
        $user->lastname = '';
        $user->id = $city->id;
        $user->phonenumber = '584261234567';
        $user->email = 'administrator@example.com';
        $user->password = Hash::make('admin');
        $user->key = '';
        $user->status = 1;
        $user->id = $role->id;
        $user->role()->associate($role);
        $user->city()->associate($city);
        $user->save();

        $role = Role::where('name', '=', 'client')->first();
        $city = City::where('slug', '=', 'alcoy')->first();
        $user = new User();
        $user->id = 2;
        $user->firstname = 'Usuario';
        $user->lastname = '';
        $user->id = $city->id;
        $user->phonenumber = '584261234567';
        $user->email = 'usuario@example.com';
        $user->password = Hash::make('usuario');
        $user->key = '';
        $user->status = 1;
        $user->id = $role->id;
        $user->role()->associate($role);
        $user->city()->associate($city);
        $user->save();
    }
}
