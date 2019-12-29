<?php

namespace Travelestt\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coast extends Model
{
    use SoftDeletes;

    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';
    const DELETED_AT = 'deleted';
    /**
     * Modelo coast, donde se almacenan las playas o calas
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'coast';
    protected $primaryKey = 'id';
    // public $timestamps = false;

    protected $fillable = [
        'slug',
        'latitude',
        'longitude',
        'id',
        'media',
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

    //Relación características de las playas
    public function characteristics()
    {
        return $this->hasMany('Travelestt\Models\Coast_characteristic', 'id', 'id');
    }

    //Relación traducciones de las playas
    public function translations()
    {
        return $this->hasMany('Travelestt\Models\Coast_translation', 'id', 'id');
    }

    // Relacion con valoraciones de playas
    public function valoration()
    {
        return $this->hasMany('Travelestt\Models\Coast_valoration', 'id', 'id');
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
            return $query->join('city', 'city.id', '=', 'coast.id')
                        ->where('city.id', '=', $city);
        } else {
            return $query->join('city', 'city.id', '=', 'coast.id')
                        ->where('slug', '=', $city);
        }
    }

    //Búsqueda por filtros de caracteristicas de categorias
    public function scopeByCharacteristics($query, $extras)
    {
        if ($extras == null) {
            return $query;
        } else {
            if (count($extras) == 1) {
                return $query->join('coastcharacteristic', 'coastcharacteristic.id', '=', 'coast.id')
                            ->join('characteristicentity', 'characteristicentity.id', '=', 'coastcharacteristic.id')
                            ->where('characteristicentity.name', '=', $extras);
            } elseif (count($extras) > 1) {
                return $query->join('coastcharacteristic', 'coastcharacteristic.id', '=', 'coast.id')
                            ->join('characteristicentity', 'characteristicentity.id', '=', 'coastcharacteristic.id')
                            ->whereIn('characteristicentity.name', $extras);
            }
            
        }
    }

    //Formateo de las consultas de las playas
    public function scopeFormatCoast($query)
    {
        return $query->select('coast.id', 'slug as slug', 'latitude as latitude', 'longitude as longitude', 'media as media', 'status as status', 'created as created', 'updated as updated', 'addedby as added_by', 'updateby as updated_by', 'outstanding as outstanding', 'coast.id')
                        ->with(['city' => function($query) {
                            $query->join('province', 'province.id', '=', 'city.id')
                                ->select('id', 'name as name_city', 'slug as slug_city', 'province.id as id_province', 'province.name as name_province');
                        }])
                        ->with(['characteristics' => function($query) {
                            $query
                                ->join('characteristicentity', 'characteristicentity.id', '=', 'coastcharacteristic.id')
                                ->select('id', 'characteristicentity.name as name');
                        }])
                        ->with(['translations' => function($query) {
                            $query
                                ->join('language', 'language.id', '=', 'coasttranslation.id')
                                ->join('languagefield', 'languagefield.id', '=', 'coasttranslation.id')
                                ->select('id', 'language.name as language', 'languagefield.name as language_field', 'coasttranslation.content as content');
                        }])
                        ->with(['valoration' => function($query) {
                            $query
                                ->select('coastvaloration.id', DB::raw('ROUND(AVG(coastvaloration.rating), 1) as average, COUNT(coastvaloration.id) as ratings'))
                                ->where('status', '=', 1)
                                ->groupBy('coastvaloration.id');
                        }]);
    }

    //Parseo de las traducciones
    public function parseCoastToFront($coasts, $name = NULL)
    {
        $actual_translation = '';
        $actual_translation_abbr = '';
        for ($i = 0; $i < count($coasts); $i++) {
            $new_coast = $coasts[$i];

            $translations = [];
            $new_coast->media = unserialize($new_coast->media);
            foreach ($new_coast->translations as $key => $translation) {
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
                if ($name != NULL) {
                    if ($name == $actual_translation_abbr) {
                        $order_options = [
                            'name'
                        ];
                        foreach ($order_options as $option) {
                            if ($option == $translation->language_field) {
                                if ($option == 'name') {
                                    $new_coast->name = $translation->content;
                                }
                            }
                        }
                    }
                }
                $translations[$actual_translation_abbr][$translation->language_field] = $translation->content;
                unset($new_coast['translations'][$key]);
            }
            unset($new_coast->translations);
            $new_coast->translations = $translations;

            $characteristics = [];
            foreach ($new_coast->characteristics as $key => $characteristic) {
                $compareCharacteristic = Characteristic_entity::where('name', '=', $characteristic->name)->first();
                $categoryCharacteristic = Characteristic_category::where('id', '=', $compareCharacteristic->id)->first();
                $nameCategory = $categoryCharacteristic->name;
                $characteristics[$nameCategory][] = $characteristic->name;
                unset($new_coast['characteristics'][$key]);
            }
            unset($new_coast->characteristics);
            $new_coast->characteristics = $characteristics;

            $city = [];
            $city['id'] = $new_coast->city->id;
            $city['name'] = $new_coast->city->name_city;
            $city['slug'] = $new_coast->city->slug_city;
            $province = [];
            $province['id'] = $new_coast->city->id_province;
            $province['name'] = $new_coast->city->name_province;
            unset($new_coast->city);

            $location = [
                "latitude" => $new_coast->latitude,
                "longitude" => $new_coast->longitude,
                "city" => $city,
                "province" => $province
            ];

            $new_coast->location = $location;
            unset($new_coast->latitude);
            unset($new_coast->longitude);

            $new_coast->id = $new_coast->id;

            $new_coast->valorations = (!empty($new_coast->valoration[0])) ? array( 'ratings' => $new_coast->valoration[0]->ratings, 'average' => $new_coast->valoration[0]->average) : NULL;

            unset($new_coast->id);
            unset($new_coast->id);
            unset($new_coast->valoration);

            $coasts[$i] = $new_coast;
        }
        return $coasts;
    }
}
