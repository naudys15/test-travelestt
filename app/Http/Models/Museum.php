<?php

namespace Travelestt\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Museum extends Model
{
    use SoftDeletes;

    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';
    const DELETED_AT = 'deleted';
    /**
     * Modelo museum, donde se almacenan los museos
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'museum';
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

    //Relación características de los museos
    public function characteristics()
    {
        return $this->hasMany('Travelestt\Models\Museum_characteristic', 'id', 'id');
    }

    //Relación traducciones de los museos
    public function translations()
    {
        return $this->hasMany('Travelestt\Models\Museum_translation', 'id', 'id');
    }

    // Relacion con valoraciones de museos
    public function valoration()
    {
        return $this->hasMany('Travelestt\Models\Museum_valoration', 'id', 'id');
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
            return $query->join('city', 'city.id', '=', 'museum.id')
                        ->where('city.id', '=', $city);
        } else {
            return $query->join('city', 'city.id', '=', 'museum.id')
                        ->where('slug', '=', $city);
        }
    }

    //Búsqueda por tipos
    public function scopeByCharacteristics($query, $types)
    {
        if (is_null($types)) {
            return $query;
        } else {
            if (count($types) == 1) {
                return $query->join('museumcharacteristic', 'museumcharacteristic.id', '=', 'museum.id')
                            ->join('characteristicentity', 'characteristicentity.id', '=', 'museumcharacteristic.id')
                            ->where('characteristicentity.name', '=', $types);
            } elseif (count($types) > 1) {
                return $query->join('museumcharacteristic', 'museumcharacteristic.id', '=', 'museum.id')
                            ->join('characteristicentity', 'characteristicentity.id', '=', 'museumcharacteristic.id')
                            ->whereIn('characteristicentity.name', $types);
            }
            
        }
    }

    //Formateo de las consultas de los museos
    public function scopeFormatMuseum($query)
    {
        return $query->select('museum.id', 'slug as slug', 'latitude as latitude', 'longitude as longitude', 'media as media', 'status as status', 'phonenumber as phonenumber', 'created as created', 'updated as updated', 'addedby as added_by', 'updateby as updated_by', 'outstanding as outstanding', 'museum.id')
                        ->with(['city' => function($query) {
                            $query->join('province', 'province.id', '=', 'city.id')
                            ->select('id', 'name as name_city', 'slug as slug_city', 'province.id as id_province', 'province.name as name_province');
                        }])
                        ->with(['characteristics' => function($query) {
                            $query
                                ->join('characteristicentity', 'characteristicentity.id', '=', 'museumcharacteristic.id')
                                ->select('id', 'characteristicentity.name as name');
                        }])
                        ->with(['translations' => function($query) {
                            $query
                                ->join('language', 'language.id', '=', 'museumtranslation.id')
                                ->join('languagefield', 'languagefield.id', '=', 'museumtranslation.id')
                                ->select('id', 'language.name as language', 'languagefield.name as language_field', 'museumtranslation.content as content');
                        }])
                        ->with(['valoration' => function($query) {
                            $query
                                ->select('museumvaloration.id', DB::raw('ROUND(AVG(museumvaloration.rating), 1) as average, COUNT(museumvaloration.id) as ratings'))
                                ->where('status', '=', 1)
                                ->groupBy('museumvaloration.id');
                        }]);
    }

    //Parseo de las traducciones
    public function parseMuseumToFront($museums)
    {
        $actual_translation = '';
        $actual_translation_abbr = '';
        for ($i = 0; $i < count($museums); $i++) {
            $new_museum = $museums[$i];
            
            $translations = [];
            $new_museum->media = unserialize($new_museum->media);
            foreach ($new_museum->translations as $key => $translation) {
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
                if ($new_museum->lang != null && $new_museum->lang == $actual_translation_abbr) {
                    $order_options = [
                        'name'
                    ];
                    foreach ($order_options as $option) {
                        if ($option == $translation->language_field) {
                            if ($option == 'name') {
                                $new_museum->name = $translation->content;
                            }
                        }
                    }
                }
                $translations[$actual_translation_abbr][$translation->language_field] = $translation->content;
                unset($new_museum['translations'][$key]);
            }
            unset($new_museum->translations);
            $new_museum->translations = $translations;

            $characteristics = [];
            $count_characteristics = 0;
            foreach ($new_museum->characteristics as $key => $characteristic) {
                $characteristics[$count_characteristics] = $characteristic->name;
                unset($new_museum['characteristics'][$key]);
                $count_characteristics++;
            }
            unset($new_museum->characteristics);
            $new_museum->characteristics = $characteristics;

            $city = [];
            $city['id'] = $new_museum->city->id;
            $city['name'] = $new_museum->city->name_city;
            $city['slug'] = $new_museum->city->slug_city;
            $province = [];
            $province['id'] = $new_museum->city->id_province;
            $province['name'] = $new_museum->city->name_province;
            unset($new_museum->city);

            $location = [
                "latitude" => $new_museum->latitude,
                "longitude" => $new_museum->longitude,
                "city" => $city,
                "province" => $province
            ];

            $new_museum->location = $location;
            unset($new_museum->latitude);
            unset($new_museum->longitude);

            $new_museum->id = $new_museum->id;

            $new_museum->valorations = (!empty($new_museum->valoration[0])) ? array( 'ratings' => $new_museum->valoration[0]->ratings, 'average' => $new_museum->valoration[0]->average) : NULL;

            unset($new_museum->id);
            unset($new_museum->id);
            unset($new_museum->valoration);

            $museums[$i] = $new_museum;
        }
        return $museums;
    }
}
