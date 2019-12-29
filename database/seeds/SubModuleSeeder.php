<?php

use Illuminate\Database\Seeder;
use Travelestt\Models\Module;
use Travelestt\Models\Sub_module;

class SubModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $module = Module::where('id', '=', 1)->first();
        
        $sub_module = new Sub_module();
        $sub_module->id = 1;
        $sub_module->name = "coast_create";
        $sub_module->description = 'Creación de playas';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $sub_module = new Sub_module();
        $sub_module->id = 2;
        $sub_module->name = "coast_update";
        $sub_module->description = 'Actualización de playas';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $sub_module = new Sub_module();
        $sub_module->id = 3;
        $sub_module->name = "coast_read";
        $sub_module->description = 'Leer playas';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $sub_module = new Sub_module();
        $sub_module->id = 4;
        $sub_module->name = "coast_delete";
        $sub_module->description = 'Borrar playas';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $module = Module::where('id', '=', 2)->first();

        $sub_module = new Sub_module();
        $sub_module->id = 5;
        $sub_module->name = "festival_create";
        $sub_module->description = 'Creación de festivales';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $sub_module = new Sub_module();
        $sub_module->id = 6;
        $sub_module->name = "festival_update";
        $sub_module->description = 'Actualización de festivales';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $sub_module = new Sub_module();
        $sub_module->id = 7;
        $sub_module->name = "festival_read";
        $sub_module->description = 'Leer festivales';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $sub_module = new Sub_module();
        $sub_module->id = 8;
        $sub_module->name = "festival_delete";
        $sub_module->description = 'Borrar festivales';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $module = Module::where('id', '=', 3)->first();

        $sub_module = new Sub_module();
        $sub_module->id = 9;
        $sub_module->name = "museum_create";
        $sub_module->description = 'Creación de museos';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $sub_module = new Sub_module();
        $sub_module->id = 10;
        $sub_module->name = "museum_update";
        $sub_module->description = 'Actualización de museos';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $sub_module = new Sub_module();
        $sub_module->id = 11;
        $sub_module->name = "museum_read";
        $sub_module->description = 'Leer museos';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $sub_module = new Sub_module();
        $sub_module->id = 12;
        $sub_module->name = "museum_delete";
        $sub_module->description = 'Borrar museos';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $module = Module::where('id', '=', 4)->first();

        $sub_module = new Sub_module();
        $sub_module->id = 13;
        $sub_module->name = "night_spot_create";
        $sub_module->description = 'Creación de sitios nocturnos';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $sub_module = new Sub_module();
        $sub_module->id = 14;
        $sub_module->name = "night_spot_update";
        $sub_module->description = 'Actualización de sitios nocturnos';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $sub_module = new Sub_module();
        $sub_module->id = 15;
        $sub_module->name = "night_spot_read";
        $sub_module->description = 'Leer sitios nocturnos';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $sub_module = new Sub_module();
        $sub_module->id = 16;
        $sub_module->name = "night_spot_delete";
        $sub_module->description = 'Borrar sitios nocturnos';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $module = Module::where('id', '=', 5)->first();

        $sub_module = new Sub_module();
        $sub_module->id = 21;
        $sub_module->name = "street_market_create";
        $sub_module->description = 'Creación de mercadillos';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $sub_module = new Sub_module();
        $sub_module->id = 22;
        $sub_module->name = "street_market_update";
        $sub_module->description = 'Actualización de mercadillos';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $sub_module = new Sub_module();
        $sub_module->id = 23;
        $sub_module->name = "street_market_read";
        $sub_module->description = 'Leer mercadillos';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $sub_module = new Sub_module();
        $sub_module->id = 24;
        $sub_module->name = "street_market_delete";
        $sub_module->description = 'Borrar mercadillos';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);
        

        $module = Module::where('id', '=', 6)->first();

        $sub_module = new Sub_module();
        $sub_module->id = 17;
        $sub_module->name = "point_of_interest_create";
        $sub_module->description = 'Creación de puntos de interés';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $sub_module = new Sub_module();
        $sub_module->id = 18;
        $sub_module->name = "point_of_interest_update";
        $sub_module->description = 'Actualización de puntos de interés';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $sub_module = new Sub_module();
        $sub_module->id = 19;
        $sub_module->name = "point_of_interest_read";
        $sub_module->description = 'Leer puntos de interés';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $sub_module = new Sub_module();
        $sub_module->id = 20;
        $sub_module->name = "point_of_interest_delete";
        $sub_module->description = 'Borrar puntos de interés';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $module = Module::where('id', '=', 7)->first();

        $sub_module = new Sub_module();
        $sub_module->id = 25;
        $sub_module->name = "route_create";
        $sub_module->description = 'Creación de rutas';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $sub_module = new Sub_module();
        $sub_module->id = 26;
        $sub_module->name = "route_update";
        $sub_module->description = 'Actualización de rutas';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $sub_module = new Sub_module();
        $sub_module->id = 27;
        $sub_module->name = "route_read";
        $sub_module->description = 'Leer rutas';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $sub_module = new Sub_module();
        $sub_module->id = 28;
        $sub_module->name = "route_delete";
        $sub_module->description = 'Borrar rutas';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $module = Module::where('id', '=', 8)->first();

        $sub_module = new Sub_module();
        $sub_module->id = 29;
        $sub_module->name = "create";
        $sub_module->description = 'Creación de usuarios';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $sub_module = new Sub_module();
        $sub_module->id = 30;
        $sub_module->name = "update";
        $sub_module->description = 'Actualización de usuarios';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $sub_module = new Sub_module();
        $sub_module->id = 31;
        $sub_module->name = "read";
        $sub_module->description = 'Leer usuarios';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $sub_module = new Sub_module();
        $sub_module->id = 32;
        $sub_module->name = "delete";
        $sub_module->description = 'Borrar usuarios';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $module = Module::where('id', '=', 9)->first();

        $sub_module = new Sub_module();
        $sub_module->id = 33;
        $sub_module->name = "create";
        $sub_module->description = 'Creación de roles';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $sub_module = new Sub_module();
        $sub_module->id = 34;
        $sub_module->name = "update";
        $sub_module->description = 'Actualización de roles';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $sub_module = new Sub_module();
        $sub_module->id = 35;
        $sub_module->name = "read";
        $sub_module->description = 'Leer roles';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $sub_module = new Sub_module();
        $sub_module->id = 36;
        $sub_module->name = "delete";
        $sub_module->description = 'Borrar roles';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $module = Module::where('id', '=', 10)->first();

        $sub_module = new Sub_module();
        $sub_module->id = 37;
        $sub_module->name = "module_create";
        $sub_module->description = 'Creación de módulos';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $sub_module = new Sub_module();
        $sub_module->id = 38;
        $sub_module->name = "module_update";
        $sub_module->description = 'Actualización de módulos';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $sub_module = new Sub_module();
        $sub_module->id = 39;
        $sub_module->name = "module_read";
        $sub_module->description = 'Leer módulos';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $sub_module = new Sub_module();
        $sub_module->id = 40;
        $sub_module->name = "module_delete";
        $sub_module->description = 'Borrar módulos';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $module = Module::where('id', '=', 11)->first();

        $sub_module = new Sub_module();
        $sub_module->id = 41;
        $sub_module->name = "submodule_create";
        $sub_module->description = 'Creación de submódulos';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $sub_module = new Sub_module();
        $sub_module->id = 42;
        $sub_module->name = "submodule_update";
        $sub_module->description = 'Actualización de submódulos';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $sub_module = new Sub_module();
        $sub_module->id = 43;
        $sub_module->name = "submodule_read";
        $sub_module->description = 'Leer submódulos';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $sub_module = new Sub_module();
        $sub_module->id = 44;
        $sub_module->name = "submodule_delete";
        $sub_module->description = 'Borrar submódulos';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $module = Module::where('id', '=', 12)->first();

        $sub_module = new Sub_module();
        $sub_module->id = 45;
        $sub_module->name = "range_create";
        $sub_module->description = 'Creación de rango de submódulos';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $sub_module = new Sub_module();
        $sub_module->id = 46;
        $sub_module->name = "range_update";
        $sub_module->description = 'Actualización de rango de submódulos';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $sub_module = new Sub_module();
        $sub_module->id = 47;
        $sub_module->name = "range_read";
        $sub_module->description = 'Leer rango de submódulos';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $sub_module = new Sub_module();
        $sub_module->id = 48;
        $sub_module->name = "range_delete";
        $sub_module->description = 'Borrar rango de submódulos';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $module = Module::where('id', '=', 13)->first();

        $sub_module = new Sub_module();
        $sub_module->id = 49;
        $sub_module->name = "entity_create";
        $sub_module->description = 'Creación de entidades';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $sub_module = new Sub_module();
        $sub_module->id = 50;
        $sub_module->name = "entity_update";
        $sub_module->description = 'Actualización de entidades';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $sub_module = new Sub_module();
        $sub_module->id = 51;
        $sub_module->name = "entity_read";
        $sub_module->description = 'Leer entidades';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $sub_module = new Sub_module();
        $sub_module->id = 52;
        $sub_module->name = "entity_delete";
        $sub_module->description = 'Borrar entidades';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $module = Module::where('id', '=', 14)->first();

        $sub_module = new Sub_module();
        $sub_module->id = 53;
        $sub_module->name = "category_create";
        $sub_module->description = 'Creación de categorías de las entidades';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $sub_module = new Sub_module();
        $sub_module->id = 54;
        $sub_module->name = "category_update";
        $sub_module->description = 'Actualización de categorías de las entidades';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $sub_module = new Sub_module();
        $sub_module->id = 55;
        $sub_module->name = "category_read";
        $sub_module->description = 'Leer categorías de las entidades';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $sub_module = new Sub_module();
        $sub_module->id = 56;
        $sub_module->name = "category_delete";
        $sub_module->description = 'Borrar categorías de las entidades';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $module = Module::where('id', '=', 15)->first();

        $sub_module = new Sub_module();
        $sub_module->id = 57;
        $sub_module->name = "characteristic_create";
        $sub_module->description = 'Creación de características de las entidades';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $sub_module = new Sub_module();
        $sub_module->id = 58;
        $sub_module->name = "characteristic_update";
        $sub_module->description = 'Actualización de características de las entidades';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $sub_module = new Sub_module();
        $sub_module->id = 59;
        $sub_module->name = "characteristic_read";
        $sub_module->description = 'Leer características de las entidades';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $sub_module = new Sub_module();
        $sub_module->id = 60;
        $sub_module->name = "characteristic_delete";
        $sub_module->description = 'Borrar características de las entidades';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $module = Module::where('id', '=', 16)->first();

        $sub_module = new Sub_module();
        $sub_module->id = 61;
        $sub_module->name = "country_create";
        $sub_module->description = 'Creación de paises';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $sub_module = new Sub_module();
        $sub_module->id = 62;
        $sub_module->name = "country_update";
        $sub_module->description = 'Actualización de paises';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $sub_module = new Sub_module();
        $sub_module->id = 63;
        $sub_module->name = "country_read";
        $sub_module->description = 'Leer paises';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $sub_module = new Sub_module();
        $sub_module->id = 64;
        $sub_module->name = "country_delete";
        $sub_module->description = 'Borrar paises';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $module = Module::where('id', '=', 17)->first();

        $sub_module = new Sub_module();
        $sub_module->id = 65;
        $sub_module->name = "province_create";
        $sub_module->description = 'Creación de provincias';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $sub_module = new Sub_module();
        $sub_module->id = 66;
        $sub_module->name = "province_update";
        $sub_module->description = 'Actualización de provincias';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $sub_module = new Sub_module();
        $sub_module->id = 67;
        $sub_module->name = "province_read";
        $sub_module->description = 'Leer provincias';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $sub_module = new Sub_module();
        $sub_module->id = 68;
        $sub_module->name = "province_delete";
        $sub_module->description = 'Borrar provincias';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $module = Module::where('id', '=', 18)->first();

        $sub_module = new Sub_module();
        $sub_module->id = 69;
        $sub_module->name = "create";
        $sub_module->description = 'Creación de ciudades';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $sub_module = new Sub_module();
        $sub_module->id = 70;
        $sub_module->name = "update";
        $sub_module->description = 'Actualización de ciudades';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $sub_module = new Sub_module();
        $sub_module->id = 71;
        $sub_module->name = "read";
        $sub_module->description = 'Leer ciudades';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $sub_module = new Sub_module();
        $sub_module->id = 72;
        $sub_module->name = "delete";
        $sub_module->description = 'Borrar ciudades';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $module = Module::where('id', '=', 19)->first();

        $sub_module = new Sub_module();
        $sub_module->id = 73;
        $sub_module->name = "create";
        $sub_module->description = 'Creación de espectaculos';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $sub_module = new Sub_module();
        $sub_module->id = 74;
        $sub_module->name = "update";
        $sub_module->description = 'Actualización de espectaculos';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $sub_module = new Sub_module();
        $sub_module->id = 75;
        $sub_module->name = "read";
        $sub_module->description = 'Leer espectaculos';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $sub_module = new Sub_module();
        $sub_module->id = 76;
        $sub_module->name = "delete";
        $sub_module->description = 'Borrar espectaculos';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $module = Module::where('id', '=', 20)->first();

        $sub_module = new Sub_module();
        $sub_module->id = 77;
        $sub_module->name = "natural_space_create";
        $sub_module->description = 'Creación de espacios naturales';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $sub_module = new Sub_module();
        $sub_module->id = 78;
        $sub_module->name = "natural_space_update";
        $sub_module->description = 'Actualización de espacios naturales';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $sub_module = new Sub_module();
        $sub_module->id = 79;
        $sub_module->name = "natural_space_read";
        $sub_module->description = 'Leer espacios naturales';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $sub_module = new Sub_module();
        $sub_module->id = 80;
        $sub_module->name = "natural_space_delete";
        $sub_module->description = 'Borrar espacios naturales';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $module = Module::where('id', '=', 21)->first();

        $sub_module = new Sub_module();
        $sub_module->id = 81;
        $sub_module->name = "experience_create";
        $sub_module->description = 'Creación de experiencias multiaventura';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $sub_module = new Sub_module();
        $sub_module->id = 82;
        $sub_module->name = "experience_update";
        $sub_module->description = 'Actualización de experiencias multiaventura';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $sub_module = new Sub_module();
        $sub_module->id = 83;
        $sub_module->name = "experience_read";
        $sub_module->description = 'Leer experiencias multiaventura';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);

        $sub_module = new Sub_module();
        $sub_module->id = 84;
        $sub_module->name = "experience_delete";
        $sub_module->description = 'Borrar experiencias multiaventura';
        $sub_module->id = $module->id;
        $sub_module->save();
        $sub_module->module()->associate($module);
    }
}
