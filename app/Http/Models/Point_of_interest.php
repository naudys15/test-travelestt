<?php

namespace Travelestt\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Point_of_interest extends Model
{
    use SoftDeletes;

    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';
    const DELETED_AT = 'deleted';
    /**
     * Modelo point_of_interest, donde se almacenan los sitios nocturnos
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'pointofinterest';
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

    //Relación características de los puntos de interés
    public function characteristics()
    {
        return $this->hasMany('Travelestt\Models\Point_of_interest_characteristic', 'id', 'id');
    }

    //Relación traducciones de los puntos de interés
    public function translations()
    {
        return $this->hasMany('Travelestt\Models\Point_of_interest_translation', 'id', 'id');
    }

    // Relacion con valoraciones de puntos de interes
    public function valoration()
    {
        return $this->hasMany('Travelestt\Models\Point_of_interest_valoration', 'id', 'id');
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
            return $query->join('city', 'city.id', '=', 'pointofinterest.id')
                        ->where('city.id', '=', $city);
        } else {
            return $query->join('city', 'city.id', '=', 'pointofinterest.id')
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
                return $query->join('pointofinterestcharacteristic', 'pointofinterestcharacteristic.id', '=', 'pointofinterest.id')
                            ->join('characteristicentity', 'characteristicentity.id', '=', 'pointofinterestcharacteristic.id')
                            ->where('characteristicentity.name', '=', $extras);
            } elseif (count($extras) > 1) {
                return $query->join('pointofinterestcharacteristic', 'pointofinterestcharacteristic.id', '=', 'pointofinterest.id')
                            ->join('characteristicentity', 'characteristicentity.id', '=', 'pointofinterestcharacteristic.id')
                            ->whereIn('characteristicentity.name', $extras);
            }
            
        }
    }

    //Formateo de las consultas de los puntos de interés
    public function scopeFormatPointOfInterest($query)
    {
        return $query->select('pointofinterest.id', 'slug as slug', 'latitude as latitude', 'longitude as longitude', 'media as media', 'status as status', 'created as created', 'updated as updated', 'addedby as added_by', 'updateby as updated_by', 'outstanding as outstanding', 'pointofinterest.id')
                        ->with(['city' => function($query) {
                            $query->join('province', 'province.id', '=', 'city.id')
                                    ->select('id', 'name as name_city', 'slug as slug_city', 'province.id as id_province', 'province.name as name_province');
                        }])
                        ->with(['characteristics' => function($query) {
                            $query
                                ->join('characteristicentity', 'characteristicentity.id', '=', 'pointofinterestcharacteristic.id')
                                ->select('id', 'characteristicentity.name as name');
                        }])
                        ->with(['translations' => function($query) {
                            $query
                                ->join('language', 'language.id', '=', 'pointofinteresttranslation.id')
                                ->join('languagefield', 'languagefield.id', '=', 'pointofinteresttranslation.id')
                                ->select('id', 'language.name as language', 'languagefield.name as language_field', 'pointofinteresttranslation.content as content');
                        }])
                        ->with(['valoration' => function($query) {
                            $query
                                ->select('pointofinterestvaloration.id', DB::raw('ROUND(AVG(pointofinterestvaloration.rating), 1) as average, COUNT(pointofinterestvaloration.id) as ratings'))
                                ->where('status', '=', 1)
                                ->groupBy('pointofinterestvaloration.id');
                        }]);
    }

    //Parseo de las traducciones
    public function parsePointOfInterestToFront($points_of_interest)
    {
        $actual_translation = '';
        $actual_translation_abbr = '';
        for ($i = 0; $i < count($points_of_interest); $i++) {
            $new_point_of_interest = $points_of_interest[$i];
            
            $translations = [];
            $new_point_of_interest->media = unserialize($new_point_of_interest->media);
            foreach ($new_point_of_interest->translations as $key => $translation) {
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
                if ($new_point_of_interest->lang != null && $new_point_of_interest->lang == $actual_translation_abbr) {
                    $order_options = [
                        'name'
                    ];
                    foreach ($order_options as $option) {
                        if ($option == $translation->language_field) {
                            if ($option == 'name') {
                                $new_point_of_interest->name = $translation->content;
                            }
                        }
                    }
                }
                $translations[$actual_translation_abbr][$translation->language_field] = $translation->content;
                unset($new_point_of_interest['translations'][$key]);
            }
            unset($new_point_of_interest->translations);
            $new_point_of_interest->translations = $translations;

            $characteristics = [];
            $count_characteristics = 0;
            foreach ($new_point_of_interest->characteristics as $key => $characteristic) {
                $characteristics[$count_characteristics] = $characteristic->name;
                unset($new_point_of_interest['characteristics'][$key]);
                $count_characteristics++;
            }
            unset($new_point_of_interest->characteristics);
            $new_point_of_interest->characteristics = $characteristics;

            $city = [];
            $city['id'] = $new_point_of_interest->city->id;
            $city['name'] = $new_point_of_interest->city->name_city;
            $city['slug'] = $new_point_of_interest->city->slug_city;
            $province = [];
            $province['id'] = $new_point_of_interest->city->id_province;
            $province['name'] = $new_point_of_interest->city->name_province;
            unset($new_point_of_interest->city);

            $location = [
                "latitude" => $new_point_of_interest->latitude,
                "longitude" => $new_point_of_interest->longitude,
                "city" => $city,
                "province" => $province
            ];

            $new_point_of_interest->location = $location;
            unset($new_point_of_interest->latitude);
            unset($new_point_of_interest->longitude);

            $new_point_of_interest->id = $new_point_of_interest->id;

            $new_point_of_interest->valorations = (!empty($new_point_of_interest->valoration[0])) ? array( 'ratings' => $new_point_of_interest->valoration[0]->ratings, 'average' => $new_point_of_interest->valoration[0]->average) : NULL;

            unset($new_point_of_interest->id);
            unset($new_point_of_interest->id);
            unset($new_point_of_interest->valoration);

            $points_of_interest[$i] = $new_point_of_interest;
        }
        return $points_of_interest;
    }
}
