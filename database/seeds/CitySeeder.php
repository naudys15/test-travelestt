<?php

use Illuminate\Database\Seeder;
use Travelestt\Models\Country;
use Travelestt\Models\Province;
use Travelestt\Models\City;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = 'database/sql/cities.sql';
        DB::unprepared(DB::raw(file_get_contents($path)));
        $cities = City::all();
        foreach ($cities as $city) {
            $country = Country::find($city->id);
            $province = Province::find($city->id);
            $city->country()->associate($country);
            $city->province()->associate($province);
            $city->save();
        }
    }
}
