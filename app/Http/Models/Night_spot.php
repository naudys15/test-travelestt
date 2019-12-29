<?php

namespace Travelestt\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Night_spot extends Model
{
    use SoftDeletes;

    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';
    const DELETED_AT = 'deleted';
    /**
     * Modelo night_spot, donde se almacenan los sitios nocturnos
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'nightspot';
    protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $fillable = [
        'slug',
        'latitude',
        'longitude',
        'phonenumber',
        // 'duration_value',
        // 'id',
        'id',
        'media',
        // 'status',
        // 'created',
        // 'updated',
        'addedby',
        'updateby'
    ];

    //Relación con ciudad
    public function city()
    {
        return $this->belongsTo('Travelestt\Models\City', 'id', 'id');
    }

    //Relación características de los sitios nocturnos
    public function characteristics()
    {
        return $this->hasMany('Travelestt\Models\Night_spot_characteristic', 'id', 'id');
    }

    //Relación traducciones de los sitios nocturnos
    public function translations()
    {
        return $this->hasMany('Travelestt\Models\Night_spot_translation', 'id', 'id');
    }

    // Relacion con valoraciones de sitios nocturnos
    public function valoration()
    {
        return $this->hasMany('Travelestt\Models\Night_spot_valoration', 'id', 'id');
    }

    //Búsqueda por slug
    public function scopeBySlug($query, $slug)
    {
        return $query->where('slug', '=', $slug);
    }

    //Búsqueda por ciudad
    public function scopeByCity($query, $city)
    {
        if (intval($city) != 0) {
            return $query->join('city', 'city.id', '=', 'nightspot.id')
                        ->where('city.id', '=', $city);
        } else {
            return $query->join('city', 'city.id', '=', 'nightspot.id')
                        ->where('slug', '=', $city);
        }
    }

    //Búsqueda por filtros de caracteristicas de categorias
    public function scopeByCharacteristics($query, $types)
    {
        if (is_null($types)) {
            return $query;
        } else {
            if (count($types) == 1) {
                return $query->join('nightspotcharacteristic', 'nightspotcharacteristic.id', '=', 'nightspot.id')
                            ->join('characteristicentity', 'characteristicentity.id', '=', 'nightspotcharacteristic.id')
                            ->where('characteristicentity.name', '=', $types);
            } elseif (count($types) > 1) {
                return $query->join('nightspotcharacteristic', 'nightspotcharacteristic.id', '=', 'nightspot.id')
                            ->join('characteristicentity', 'characteristicentity.id', '=', 'nightspotcharacteristic.id')
                            ->whereIn('characteristicentity.name', $types);
            }
            
        }
    }

    //Formateo de las consultas de los sitios nocturnos
    public function scopeFormatNightSpot($query)
    {
        return $query->select('nightspot.id', 'slug as slug', 'latitude as latitude', 'longitude as longitude', 'media as media', 'status as status', 'phonenumber as phonenumber', 'created as created', 'updated as updated', 'addedby as added_by', 'updateby as updated_by', 'outstanding as outstanding', 'nightspot.id')
                        ->with(['city' => function($query) {
                            $query->join('province', 'province.id', '=', 'city.id')
                                    ->select('id', 'name as name_city', 'slug as slug_city', 'province.id as id_province', 'province.name as name_province');
                        }])
                        ->with(['characteristics' => function($query) {
                            $query
                                ->join('characteristicentity', 'characteristicentity.id', '=', 'nightspotcharacteristic.id')
                                ->select('id', 'characteristicentity.name as name');
                        }])
                        ->with(['translations' => function($query) {
                            $query
                                ->join('language', 'language.id', '=', 'nightspottranslation.id')
                                ->join('languagefield', 'languagefield.id', '=', 'nightspottranslation.id')
                                ->select('id', 'language.name as language', 'languagefield.name as language_field', 'nightspottranslation.content as content');
                        }])
                        ->with(['valoration' => function($query) {
                            $query
                                ->select('nightspotvaloration.id', DB::raw('ROUND(AVG(nightspotvaloration.rating), 1) as average, COUNT(nightspotvaloration.id) as ratings'))
                                ->where('status', '=', 1)
                                ->groupBy('nightspotvaloration.id');
                        }]);
    }    

    //Parseo de las traducciones
    public function parseNightSpotToFront($night_spots)
    {
        $actual_translation = '';
        $actual_translation_abbr = '';
        for ($i = 0; $i < count($night_spots); $i++) {
            $new_night_spot = $night_spots[$i];
            
            $translations = [];
            $new_night_spot->media = unserialize($new_night_spot->media);
            foreach ($new_night_spot->translations as $key => $translation) {
                if ($actual_translation != $translation->language) {
                    $actual_translation = $translation->language;
                    if ($actual_translation == "spanish") {
                        $actual_translation_abbr = "es";
                    } elseif ($actual_translation == "english") {
                        $actual_translation_abbr = "en";
                    } elseif ($actual_translation == "french") {
                        $actual_translation_abbr = "fr";
                    }
                }
                if ($new_night_spot->lang != null && $new_night_spot->lang == $actual_translation_abbr) {
                    $order_options = [
                        'name'
                    ];
                    foreach ($order_options as $option) {
                        if ($option == $translation->language_field) {
                            if ($option == 'name') {
                                $new_night_spot->name = $translation->content;
                            }
                        }
                    }
                }
                $translations[$actual_translation_abbr][$translation->language_field] = $translation->content;
                unset($new_night_spot['translations'][$key]);
            }
            unset($new_night_spot->translations);
            $new_night_spot->translations = $translations;

            $characteristics = [];
            $count_characteristics = 0;
            foreach ($new_night_spot->characteristics as $key => $characteristic) {
                $characteristics[$count_characteristics] = $characteristic->name;
                unset($new_night_spot['characteristics'][$key]);
                $count_characteristics++;
            }
            unset($new_night_spot->characteristics);
            $new_night_spot->characteristics = $characteristics;

            $city = [];
            $city['id'] = $new_night_spot->city->id;
            $city['name'] = $new_night_spot->city->name_city;
            $city['slug'] = $new_night_spot->city->slug_city;
            $province = [];
            $province['id'] = $new_night_spot->city->id_province;
            $province['name'] = $new_night_spot->city->name_province;
            unset($new_night_spot->city);

            $location = [
                "latitude" => $new_night_spot->latitude,
                "longitude" => $new_night_spot->longitude,
                "city" => $city,
                "province" => $province
            ];

            $new_night_spot->location = $location;
            unset($new_night_spot->latitude);
            unset($new_night_spot->longitude);

            $new_night_spot->id = $new_night_spot->id;

            $new_night_spot->valorations = (!empty($new_night_spot->valoration[0])) ? array( 'ratings' => $new_night_spot->valoration[0]->ratings, 'average' => $new_night_spot->valoration[0]->average) : NULL;

            unset($new_night_spot->id);
            unset($new_night_spot->id);
            unset($new_night_spot->valoration);

            $night_spots[$i] = $new_night_spot;
        }
        return $night_spots;
    }
}
