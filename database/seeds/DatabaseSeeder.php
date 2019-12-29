<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $folders = [
            'assets/images/coasts',
            'assets/images/festivals',
            'assets/images/museums',
            'assets/images/night_spots',
            'assets/images/street_markets',
            'assets/images/points_of_interest',
            'assets/images/routes',
            'assets/images/users',
            'assets/images/cities',
        ];
        foreach ($folders as $folder) {
            Storage::deleteDirectory($folder);
            Storage::disk('local')->makeDirectory($folder);
        }
        //Entidades
        $this->call(EntitySeeder::class);
        // //Categorías de las características
        $this->call(CategorySeeder::class);
        // //Características de las entidades
        $this->call(CharacteristicEntitySeeder::class);
        // //Lenguajes
        $this->call(LanguageSeeder::class);
        // //Campos susceptibles a idiomas
        $this->call(LanguageFieldSeeder::class);
        // //Unidades de duración de las unidades
        $this->call(UnitSeeder::class);
        // Paises
        $this->call(CountrySeeder::class);
        // Estados o provincias
        $this->call(ProvinceSeeder::class);
        //Ciudades
        $this->call(CitySeeder::class);
        //Roles
        $this->call(RoleSeeder::class);
        //Módulos
        $this->call(ModuleSeeder::class);
        //Sub-módulos
        $this->call(SubModuleSeeder::class);
        //Rango de los Sub-módulos
        $this->call(RangeSubModuleSeeder::class);
        //Usuarios
        $this->call(UserSeeder::class);
        //Permisos de rol
        $this->call(PermissionsRoleSeeder::class);
        //Permisos de un Usuario
        $this->call(PermissionsUserSeeder::class);
    }
}
