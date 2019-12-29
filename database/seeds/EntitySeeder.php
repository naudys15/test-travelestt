<?php

use Illuminate\Database\Seeder;
use Travelestt\Models\Entity;

class EntitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ent = new Entity();
        $ent->id = 1;
        $ent->name = 'coast';
        $ent->description = 'Playa o Cala';
        $ent->save();

        $ent = new Entity();
        $ent->id = 2;
        $ent->name = 'festival';
        $ent->description = 'Festival';
        $ent->save();
        
        $ent = new Entity();
        $ent->id = 3;
        $ent->name = 'museum';
        $ent->description = 'Museo';
        $ent->save();
        
        $ent = new Entity();
        $ent->id = 4;
        $ent->name = 'night_spot';
        $ent->description = 'Sitio nocturno';
        $ent->save();

        $ent = new Entity();
        $ent->id = 5;
        $ent->name = 'point_of_interest';
        $ent->description = 'Punto de interés';
        $ent->save();

        $ent = new Entity();
        $ent->id = 6;
        $ent->name = 'street_market';
        $ent->description = 'Mercadillo';
        $ent->save();

        $ent = new Entity();
        $ent->id = 7;
        $ent->name = 'route';
        $ent->description = 'Ruta';
        $ent->save();

        $ent = new Entity();
        $ent->id = 8;
        $ent->name = 'user';
        $ent->description = 'Usuario';
        $ent->save();

        $ent = new Entity();
        $ent->id = 9;
        $ent->name = 'role';
        $ent->description = 'Rol';
        $ent->save();
        
        $ent = new Entity();
        $ent->id = 10;
        $ent->name = 'module';
        $ent->description = 'Módulo';
        $ent->save();
        
        $ent = new Entity();
        $ent->id = 11;
        $ent->name = 'sub_module';
        $ent->description = 'Submódulo';
        $ent->save();

        $ent = new Entity();
        $ent->id = 12;
        $ent->name = 'range_sub_module';
        $ent->description = 'Rango de submódulo';
        $ent->save();

        $ent = new Entity();
        $ent->id = 13;
        $ent->name = 'entity';
        $ent->description = 'Entidad del sistema';
        $ent->save();

        $ent = new Entity();
        $ent->id = 14;
        $ent->name = 'category_entity';
        $ent->description = 'Categoría de la entidad';
        $ent->save();

        $ent = new Entity();
        $ent->id = 15;
        $ent->name = 'characteristic_entity';
        $ent->description = 'Característica de la entidad';
        $ent->save();

        $ent = new Entity();
        $ent->id = 16;
        $ent->name = 'country';
        $ent->description = 'País';
        $ent->save();
        
        $ent = new Entity();
        $ent->id = 17;
        $ent->name = 'province';
        $ent->description = 'Provincia';
        $ent->save();
        
        $ent = new Entity();
        $ent->id = 18;
        $ent->name = 'city';
        $ent->description = 'Ciudad';
        $ent->save();

        $ent = new Entity();
        $ent->id = 19;
        $ent->name = 'show';
        $ent->description = 'Espectaculo';
        $ent->save();

        $ent = new Entity();
        $ent->id = 20;
        $ent->name = 'natural_space';
        $ent->description = 'Espacio natural';
        $ent->save();

        $ent = new Entity();
        $ent->id = 21;
        $ent->name = 'experience';
        $ent->description = 'Experiencia multiaventura';
        $ent->save();
    }
}
