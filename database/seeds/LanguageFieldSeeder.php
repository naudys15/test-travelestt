<?php

use Illuminate\Database\Seeder;
use Travelestt\Models\Language_field;

class LanguageFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $lafi = new Language_field();
        $lafi->id = 1;
        $lafi->name = "name";
        $lafi->description = 'Nombre';
        $lafi->save();

        $lafi = new Language_field();
        $lafi->id = 2;
        $lafi->name = "address";
        $lafi->description = 'Dirección';
        $lafi->save();

        $lafi = new Language_field();
        $lafi->id = 3;
        $lafi->name = "short_description";
        $lafi->description = 'Descripción corta';
        $lafi->save();

        $lafi = new Language_field();
        $lafi->id = 4;
        $lafi->name = "long_description";
        $lafi->description = 'Descripción larga';
        $lafi->save();

        $lafi = new Language_field();
        $lafi->id = 5;
        $lafi->name = "meta_title";
        $lafi->description = 'Título del metadato';
        $lafi->save();

        $lafi = new Language_field();
        $lafi->id = 6;
        $lafi->name = "meta_description";
        $lafi->description = 'Descripción del metadato';
        $lafi->save();
        
        $lafi = new Language_field();
        $lafi->id = 7;
        $lafi->name = "station_name";
        $lafi->description = 'Nombre de la estación';
        $lafi->save();

        $lafi = new Language_field();
        $lafi->id = 8;
        $lafi->name = "station_description";
        $lafi->description = 'Descripción de la estación';
        $lafi->save();
    }
}
