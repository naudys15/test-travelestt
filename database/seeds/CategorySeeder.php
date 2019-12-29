<?php

use Illuminate\Database\Seeder;
use Travelestt\Models\Characteristic_category;
use Travelestt\Models\Entity;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $entity = Entity::where('name', '=', 'coast')->first();
        $cat = new Characteristic_category();
        $cat->id = 1;
        $cat->name = 'type_sand';
        $cat->description = 'Type sand';
        $cat->render = 'radio';
        $cat->id = $entity->id;
        $cat->save();
        $cat->entity()->associate($entity);
        
        $entity = Entity::where('name', '=', 'coast')->first();
        $cat = new Characteristic_category();
        $cat->id = 2;
        $cat->name = 'extras';
        $cat->description = 'Extras';
        $cat->render = 'checkbox';
        $cat->id = $entity->id;
        $cat->save();
        $cat->entity()->associate($entity);

        $cat = new Characteristic_category();
        $cat->id = 3;
        $cat->name = 'stamps';
        $cat->description = 'Stamps';
        $cat->render = 'checkbox';
        $cat->id = $entity->id;
        $cat->save();
        $cat->entity()->associate($entity);

        $entity = Entity::where('name', '=', 'museum')->first();
        $cat = new Characteristic_category();
        $cat->id = 4;
        $cat->name = 'types';
        $cat->description = 'Types';
        $cat->render = 'checkbox';
        $cat->id = $entity->id;
        $cat->save();
        $cat->entity()->associate($entity);

        $entity = Entity::where('name', '=', 'night_spot')->first();
        $cat = new Characteristic_category();
        $cat->id = 5;
        $cat->name = 'types';
        $cat->description = 'Types';
        $cat->render = 'checkbox';
        $cat->id = $entity->id;
        $cat->save();
        $cat->entity()->associate($entity);

        $entity = Entity::where('name', '=', 'street_market')->first();
        $cat = new Characteristic_category();
        $cat->id = 6;
        $cat->name = 'types';
        $cat->description = 'Types';
        $cat->render = 'checkbox';
        $cat->id = $entity->id;
        $cat->save();
        $cat->entity()->associate($entity);

        $entity = Entity::where('name', '=', 'route')->first();
        $cat = new Characteristic_category();
        $cat->id = 7;
        $cat->name = 'types';
        $cat->description = 'Types';
        $cat->render = 'radio';
        $cat->id = $entity->id;
        $cat->save();
        $cat->entity()->associate($entity);

        $entity = Entity::where('name', '=', 'route')->first();
        $cat = new Characteristic_category();
        $cat->id = 8;
        $cat->name = 'level';
        $cat->description = 'Level';
        $cat->render = 'radio';
        $cat->id = $entity->id;
        $cat->save();
        $cat->entity()->associate($entity);

        $entity = Entity::where('name', '=', 'festival')->first();
        $cat = new Characteristic_category();
        $cat->id = 9;
        $cat->name = 'types';
        $cat->description = 'Types';
        $cat->render = 'radio';
        $cat->id = $entity->id;
        $cat->save();
        $cat->entity()->associate($entity);

        $entity = Entity::where('name', '=', 'natural_space')->first();
        $cat = new Characteristic_category();
        $cat->id = 10;
        $cat->name = 'types';
        $cat->description = 'Types';
        $cat->render = 'radio';
        $cat->id = $entity->id;
        $cat->save();
        $cat->entity()->associate($entity);

        $entity = Entity::where('name', '=', 'experience')->first();
        $cat = new Characteristic_category();
        $cat->id = 11;
        $cat->name = 'types';
        $cat->description = 'Types';
        $cat->render = 'radio';
        $cat->id = $entity->id;
        $cat->save();
        $cat->entity()->associate($entity);
    }
}
