<?php

use Illuminate\Database\Seeder;
use Travelestt\Models\Entity;
use Travelestt\Models\Module;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $entity = Entity::where('name', '=', 'coast')->first();
        $module = new Module();
        $module->id = 1;
        $module->name = "coast";
        $module->description = 'Playa o cala';
        $module->entity()->associate($entity);
        $module->save();

        $entity = Entity::where('name', '=', 'festival')->first();
        $module = new Module();
        $module->id = 2;
        $module->name = "festival";
        $module->description = 'Festival';
        $module->entity()->associate($entity);
        $module->save();

        $entity = Entity::where('name', '=', 'museum')->first();
        $module = new Module();
        $module->id = 3;
        $module->name = "museum";
        $module->description = 'Museo';
        $module->entity()->associate($entity);
        $module->save();

        $entity = Entity::where('name', '=', 'night_spot')->first();
        $module = new Module();
        $module->id = 4;
        $module->name = "night_spot";
        $module->description = 'Sitio nocturno';
        $module->entity()->associate($entity);
        $module->save();

        $entity = Entity::where('name', '=', 'street_market')->first();
        $module = new Module();
        $module->id = 5;
        $module->name = "street_market";
        $module->description = 'Mercadillo';
        $module->entity()->associate($entity);
        $module->save();

        $entity = Entity::where('name', '=', 'point_of_interest')->first();
        $module = new Module();
        $module->id = 6;
        $module->name = "point_of_interest";
        $module->description = 'Punto de interés';
        $module->entity()->associate($entity);
        $module->save();

        $entity = Entity::where('name', '=', 'route')->first();
        $module = new Module();
        $module->id = 7;
        $module->name = "route";
        $module->description = 'Ruta';
        $module->entity()->associate($entity);
        $module->save();

        $entity = Entity::where('name', '=', 'user')->first();
        $module = new Module();
        $module->id = 8;
        $module->name = "users";
        $module->description = 'Usuarios';
        $module->entity()->associate($entity);
        $module->save();

        $entity = Entity::where('name', '=', 'role')->first();
        $module = new Module();
        $module->id = 9;
        $module->name = "roles";
        $module->description = 'Roles';
        $module->entity()->associate($entity);
        $module->save();

        $entity = Entity::where('name', '=', 'module')->first();
        $module = new Module();
        $module->id = 10;
        $module->name = "modules";
        $module->description = 'Módulos';
        $module->entity()->associate($entity);
        $module->save();

        $entity = Entity::where('name', '=', 'sub_module')->first();
        $module = new Module();
        $module->id = 11;
        $module->name = "sub_modules";
        $module->description = 'Submódulos';
        $module->entity()->associate($entity);
        $module->save();

        $entity = Entity::where('name', '=', 'range_sub_module')->first();
        $module = new Module();
        $module->id = 12;
        $module->name = "range_sub_modules";
        $module->description = 'Rango de submódulos';
        $module->entity()->associate($entity);
        $module->save();

        $entity = Entity::where('name', '=', 'entity')->first();
        $module = new Module();
        $module->id = 13;
        $module->name = "entities";
        $module->description = 'Entidades';
        $module->entity()->associate($entity);
        $module->save();

        $entity = Entity::where('name', '=', 'category_entity')->first();
        $module = new Module();
        $module->id = 14;
        $module->name = "categories_entities";
        $module->description = 'Categorías de las entidades';
        $module->entity()->associate($entity);
        $module->save();

        $entity = Entity::where('name', '=', 'characteristic_entity')->first();
        $module = new Module();
        $module->id = 15;
        $module->name = "characteristics_entities";
        $module->description = 'Características de las entidades';
        $module->entity()->associate($entity);
        $module->save();

        $entity = Entity::where('name', '=', 'country')->first();
        $module = new Module();
        $module->id = 16;
        $module->name = "countries";
        $module->description = 'Paises';
        $module->entity()->associate($entity);
        $module->save();

        $entity = Entity::where('name', '=', 'province')->first();
        $module = new Module();
        $module->id = 17;
        $module->name = "provinces";
        $module->description = 'Provincias';
        $module->entity()->associate($entity);
        $module->save();

        $entity = Entity::where('name', '=', 'city')->first();
        $module = new Module();
        $module->id = 18;
        $module->name = "cities";
        $module->description = 'Ciudades';
        $module->entity()->associate($entity);
        $module->save();

        $entity = Entity::where('name', '=', 'show')->first();
        $module = new Module();
        $module->id = 19;
        $module->name = "shows";
        $module->description = 'Espectaculos';
        $module->entity()->associate($entity);
        $module->save();

        $entity = Entity::where('name', '=', 'natural_space')->first();
        $module = new Module();
        $module->id = 20;
        $module->name = "natural_spaces";
        $module->description = 'Espacios naturales';
        $module->entity()->associate($entity);
        $module->save();

        $entity = Entity::where('name', '=', 'experience')->first();
        $module = new Module();
        $module->id = 21;
        $module->name = "experiences";
        $module->description = 'Experiencias multiaventuras';
        $module->entity()->associate($entity);
        $module->save();
    }
}
