<?php

use Illuminate\Database\Seeder;
use Travelestt\Models\Unit;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $unit = new Unit();
        $unit->id = 1;
        $unit->name = "horas";
        $unit->description = 'Horas';
        $unit->save();

        $unit = new Unit();
        $unit->id = 2;
        $unit->name = "minutos";
        $unit->description = 'Minutos';
        $unit->save();

    }
}
