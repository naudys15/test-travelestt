<?php

use Illuminate\Database\Seeder;
use Travelestt\Models\Characteristic_category;
use Travelestt\Models\Characteristic_entity;

class CharacteristicEntitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $category = Characteristic_category::where('id', '=', 1)->first();
        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 1;
        $characteristic_entity->id = 1;
        $characteristic_entity->name = 'white_sand';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 2;
        $characteristic_entity->id = 1;
        $characteristic_entity->name = 'yellow_sand';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 3;
        $characteristic_entity->id = 1;
        $characteristic_entity->name = 'green_sand';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 4;
        $characteristic_entity->id = 1;
        $characteristic_entity->name = 'black_sand';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 5;
        $characteristic_entity->id = 1;
        $characteristic_entity->name = 'fine_stone';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 6;
        $characteristic_entity->id = 1;
        $characteristic_entity->name = 'thick_stone';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $category = Characteristic_category::where('id', '=', 2)->first();

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 7;
        $characteristic_entity->id = 2;
        $characteristic_entity->name = 'feet_lavatory';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 8;
        $characteristic_entity->id = 2;
        $characteristic_entity->name = 'showers';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 9;
        $characteristic_entity->id = 2;
        $characteristic_entity->name = 'lifeguards';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 10;
        $characteristic_entity->id = 2;
        $characteristic_entity->name = 'handicapped_access';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 11;
        $characteristic_entity->id = 2;
        $characteristic_entity->name = 'watersports';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 12;
        $characteristic_entity->id = 2;
        $characteristic_entity->name = 'chiringuito';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $category = Characteristic_category::where('id', '=', 3)->first();

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 13;
        $characteristic_entity->id = 3;
        $characteristic_entity->name = 'iso_14001';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 14;
        $characteristic_entity->id = 3;
        $characteristic_entity->name = 'iso_9001';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 15;
        $characteristic_entity->id = 3;
        $characteristic_entity->name = 'iso_17001';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 16;
        $characteristic_entity->id = 3;
        $characteristic_entity->name = 'blue_flag';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 17;
        $characteristic_entity->id = 3;
        $characteristic_entity->name = 'qualitur_flag';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $category = Characteristic_category::where('id', '=', 4)->first();

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 18;
        $characteristic_entity->id = 4;
        $characteristic_entity->name = 'classic';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 19;
        $characteristic_entity->id = 4;
        $characteristic_entity->name = 'modernist';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 20;
        $characteristic_entity->id = 4;
        $characteristic_entity->name = 'contemporary';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 21;
        $characteristic_entity->id = 4;
        $characteristic_entity->name = 'anthropological';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 22;
        $characteristic_entity->id = 4;
        $characteristic_entity->name = 'decorative_arts';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);
        
        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 23;
        $characteristic_entity->id = 4;
        $characteristic_entity->name = 'fine_arts';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 24;
        $characteristic_entity->id = 4;
        $characteristic_entity->name = 'natural_sciences';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 25;
        $characteristic_entity->id = 4;
        $characteristic_entity->name = 'technological_scientist';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 26;
        $characteristic_entity->id = 4;
        $characteristic_entity->name = 'ethnographic';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 27;
        $characteristic_entity->id = 4;
        $characteristic_entity->name = 'historical';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 28;
        $characteristic_entity->id = 4;
        $characteristic_entity->name = 'maritime_and_naval';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 29;
        $characteristic_entity->id = 4;
        $characteristic_entity->name = 'military';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 30;
        $characteristic_entity->id = 4;
        $characteristic_entity->name = 'musical';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $category = Characteristic_category::where('id', '=', 5)->first();
        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 31;
        $characteristic_entity->id = 5;
        $characteristic_entity->name = 'discotheque';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 32;
        $characteristic_entity->id = 5;
        $characteristic_entity->name = 'pub';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 33;
        $characteristic_entity->id = 5;
        $characteristic_entity->name = 'irish_pub';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 34;
        $characteristic_entity->id = 5;
        $characteristic_entity->name = 'chill_out';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 35;
        $characteristic_entity->id = 5;
        $characteristic_entity->name = 'dance_room';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 36;
        $characteristic_entity->id = 5;
        $characteristic_entity->name = 'lounge_bar';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 37;
        $characteristic_entity->id = 5;
        $characteristic_entity->name = 'thematic';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 38;
        $characteristic_entity->id = 5;
        $characteristic_entity->name = 'karaoke';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 39;
        $characteristic_entity->id = 5;
        $characteristic_entity->name = 'cocktail';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 40;
        $characteristic_entity->id = 5;
        $characteristic_entity->name = 'american';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 41;
        $characteristic_entity->id = 5;
        $characteristic_entity->name = 'canteen';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 42;
        $characteristic_entity->id = 5;
        $characteristic_entity->name = 'lobby_bar';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 43;
        $characteristic_entity->id = 5;
        $characteristic_entity->name = 'chiringuito';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $category = Characteristic_category::where('id', '=', 6)->first();
        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 44;
        $characteristic_entity->id = 6;
        $characteristic_entity->name = 'gastronomic';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 45;
        $characteristic_entity->id = 6;
        $characteristic_entity->name = 'rastro';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 46;
        $characteristic_entity->id = 6;
        $characteristic_entity->name = 'antiques';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 47;
        $characteristic_entity->id = 6;
        $characteristic_entity->name = 'of_supplies';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 48;
        $characteristic_entity->id = 6;
        $characteristic_entity->name = 'traditional';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 49;
        $characteristic_entity->id = 6;
        $characteristic_entity->name = 'street_food_market';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 50;
        $characteristic_entity->id = 7;
        $characteristic_entity->name = 'hiker';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 51;
        $characteristic_entity->id = 7;
        $characteristic_entity->name = 'btt';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 52;
        $characteristic_entity->id = 7;
        $characteristic_entity->name = 'highway';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 53;
        $characteristic_entity->id = 8;
        $characteristic_entity->name = 'easy';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 54;
        $characteristic_entity->id = 8;
        $characteristic_entity->name = 'moderate';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 55;
        $characteristic_entity->id = 8;
        $characteristic_entity->name = 'hard';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 56;
        $characteristic_entity->id = 10;
        $characteristic_entity->name = 'national_parks';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 57;
        $characteristic_entity->id = 10;
        $characteristic_entity->name = 'natural_parks';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 58;
        $characteristic_entity->id = 10;
        $characteristic_entity->name = 'natural_reserves';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 59;
        $characteristic_entity->id = 10;
        $characteristic_entity->name = 'marine_protected_areas';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 60;
        $characteristic_entity->id = 10;
        $characteristic_entity->name = 'natural_monuments';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 61;
        $characteristic_entity->id = 10;
        $characteristic_entity->name = 'protected_landscapes';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 62;
        $characteristic_entity->id = 10;
        $characteristic_entity->name = 'other_protection_figures';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 63;
        $characteristic_entity->id = 10;
        $characteristic_entity->name = 'areas_of_special_protection_for_birds';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 64;
        $characteristic_entity->id = 10;
        $characteristic_entity->name = 'places_of_community_importance';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 65;
        $characteristic_entity->id = 10;
        $characteristic_entity->name = 'wetlands_of_international_importance';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 66;
        $characteristic_entity->id = 10;
        $characteristic_entity->name = 'natural_heritage_of_humanity';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 67;
        $characteristic_entity->id = 10;
        $characteristic_entity->name = 'protected_areas_of_the_convention_for_the_protection_of_the_marine_environment_of_the_northeast_atlantic';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 68;
        $characteristic_entity->id = 10;
        $characteristic_entity->name = 'specially_protected_areas_of_importance_for_the_mediterranean';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 69;
        $characteristic_entity->id = 10;
        $characteristic_entity->name = 'geoparks';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 70;
        $characteristic_entity->id = 10;
        $characteristic_entity->name = 'biosphere_reserves';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 71;
        $characteristic_entity->id = 10;
        $characteristic_entity->name = 'biogenetic_reserves_of_the_council_of_europe';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);

        $characteristic_entity = new Characteristic_entity();
        $characteristic_entity->id = 72;
        $characteristic_entity->id = 10;
        $characteristic_entity->name = 'marine_reserves';
        $characteristic_entity->save();
        $characteristic_entity->category()->associate($category);
    }
}
