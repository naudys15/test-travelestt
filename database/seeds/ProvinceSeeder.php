<?php

use Illuminate\Database\Seeder;
use Travelestt\Models\Country;
use Travelestt\Models\Province;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = 'database/sql/provinces.sql';
        DB::unprepared(file_get_contents($path));
        $provinces = Province::all();
        foreach ($provinces as $province) {
            $country = Country::find($province->id);
            $province->country()->associate($country);
            $province->save();
        }
    }
}
