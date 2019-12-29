<?php

namespace Travelestt\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Festival extends Model
{
    use SoftDeletes;

    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';
    const DELETED_AT = 'deleted';
    /**
     * Modelo festival, donde se almacenan las playas o calas
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'festival';
    protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $fillable = [
        'slug',
        'latitude',
        'longitude',
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

    //Relación características de los festivales
    public function characteristics()
    {
        return $this->hasMany('Travelestt\Models\Festival_characteristic', 'id', 'id');
    }

    //Relación traducciones de los festivales
    public function translations()
    {
        return $this->hasMany('Travelestt\Models\Festival_translation', 'id', 'id');
    }

    // Relacion con valoraciones de festivales
    public function valoration()
    {
        return $this->hasMany('Travelestt\Models\Festival_valoration', 'id', 'id');
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
            return $query->join('city', 'city.id', '=', 'festival.id')
                    ->where('city.id', '=', $city);
        } else {
            return $query->join('city', 'city.id', '=', 'festival.id')
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
                return $query->join('festivalcharacteristic', 'festivalcharacteristic.id', '=', 'festival.id')
                            ->join('characteristicentity', 'characteristicentity.id', '=', 'festivalcharacteristic.id')
                            ->where('characteristicentity.name', '=', $extras);
            } elseif (count($extras) > 1) {
                return $query->join('festivalcharacteristic', 'festivalcharacteristic.id', '=', 'festival.id')
                            ->join('characteristicentity', 'characteristicentity.id', '=', 'festivalcharacteristic.id')
                            ->whereIn('characteristicentity.name', $extras);
            }
            
        }
    }

    //Formateo de las consultas de los festivales
    public function scopeFormatFestival($query)
    {
        return $query->select('festival.id', 'slug as slug', 'latitude as latitude', 'longitude as longitude', 'media as media', 'status as status', 'created as created', 'updated as updated', 'addedby as added_by', 'updateby as updated_by', 'outstanding as outstanding', 'festival.id')
                        ->with(['city' => function($query) {
                            $query->join('province', 'province.id', '=', 'city.id')
                            ->select('id', 'name as name_city', 'slug as slug_city', 'province.id as id_province', 'province.name as name_province');
                        }])
                        ->with(['characteristics' => function($query) {
                            $query
                                ->join('characteristicentity', 'characteristicentity.id', '=', 'festivalcharacteristic.id')
                                ->select('id', 'characteristicentity.name as name');
                        }])
                        ->with(['translations' => function($query) {
                            $query
                                ->join('language', 'language.id', '=', 'festivaltranslation.id')
                                ->join('languagefield', 'languagefield.id', '=', 'festivaltranslation.id')
                                ->select('id', 'language.name as language', 'languagefield.name as language_field', 'festivaltranslation.content as content');
                        }])
                        ->with(['valoration' => function($query) {
                            $query
                                ->select('festivalvaloration.id', DB::raw('ROUND(AVG(festivalvaloration.rating), 1) as average, COUNT(festivalvaloration.id) as ratings'))
                                ->where('status', '=', 1)
                                ->groupBy('festivalvaloration.id');
                        }]);
    }

    //Parseo de las traducciones
    public function parseFestivalToFront($festivals)
    {
        $actual_translation = '';
        $actual_translation_abbr = '';
        for ($i = 0; $i < count($festivals); $i++) {
            $new_festival = $festivals[$i];
            
            $translations = [];
            $new_festival->media = unserialize($new_festival->media);
            foreach ($new_festival->translations as $key => $translation) {
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
                if ($new_festival->lang != null && $new_festival->lang == $actual_translation_abbr) {
                    $order_options = [
                        'name'
                    ];
                    foreach ($order_options as $option) {
                        if ($option == $translation->language_field) {
                            if ($option == 'name') {
                                $new_festival->name = $translation->content;
                            }
                        }
                    }
                }
                $translations[$actual_translation_abbr][$translation->language_field] = $translation->content;
                unset($new_festival['translations'][$key]);
            }
            unset($new_festival->translations);
            $new_festival->translations = $translations;

            $characteristics = [];
            $count_characteristics = 0;
            foreach ($new_festival->characteristics as $key => $characteristic) {
                $characteristics[$count_characteristics] = $characteristic->name;
                unset($new_festival['characteristics'][$key]);
                $count_characteristics++;
            }
            unset($new_festival->characteristics);
            $new_festival->characteristics = $characteristics;

            $city = [];
            $city['id'] = $new_festival->city->id;
            $city['name'] = $new_festival->city->name_city;
            $city['slug'] = $new_festival->city->slug_city;
            $province = [];
            $province['id'] = $new_festival->city->id_province;
            $province['name'] = $new_festival->city->name_province;
            unset($new_festival->city);

            $location = [
                "latitude" => $new_festival->latitude,
                "longitude" => $new_festival->longitude,
                "city" => $city,
                "province" => $province
            ];

            $new_festival->location = $location;
            unset($new_festival->latitude);
            unset($new_festival->longitude);

            $new_festival->id = $new_festival->id;

            $new_festival->valorations = (!empty($new_festival->valoration[0])) ? array( 'ratings' => $new_festival->valoration[0]->ratings, 'average' => $new_festival->valoration[0]->average) : NULL;

            unset($new_festival->id);
            unset($new_festival->id);
            unset($new_festival->valoration);

            $festivals[$i] = $new_festival;
        }
        return $festivals;
    }
}