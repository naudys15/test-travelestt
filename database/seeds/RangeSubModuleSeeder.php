<?php

use Illuminate\Database\Seeder;
use Travelestt\Models\Range_sub_module;

class RangeSubModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $range = new Range_sub_module();
        $range->id = 1;
        $range->name = "none";
        $range->description = 'Nadie';
        $range->save();

        $range = new Range_sub_module();
        $range->id = 2;
        $range->name = "self";
        $range->description = 'Mismo usuario';
        $range->save();

        $range = new Range_sub_module();
        $range->id = 3;
        $range->name = "all";
        $range->description = 'Todos los usuarios';
        $range->save();
    }
}
