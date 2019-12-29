<?php

use Illuminate\Database\Seeder;
use Travelestt\Models\Language;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $lang = new Language();
        $lang->id = 1;
        $lang->name = "spanish";
        $lang->description = 'Español';
        $lang->save();

        $lang = new Language();
        $lang->id = 2;
        $lang->name = "english";
        $lang->description = 'Inglés';
        $lang->save();

        $lang = new Language();
        $lang->id = 3;
        $lang->name = "french";
        $lang->description = 'Francés';
        $lang->save();
    }
}
