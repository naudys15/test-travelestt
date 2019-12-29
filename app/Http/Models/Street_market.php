<?php

namespace Travelestt\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Street_market extends Model
{
    use SoftDeletes;

    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';
    const DELETED_AT = 'deleted';
    /**
     * Modelo street_market, donde se almacenan los mercadillos
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'streetmarket';
    protected $primaryKey = 'id';
    public $timestamps = false;
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

    //Relación características de los mercadillos
    public function characteristics()
    {
        return $this->hasMany('Travelestt\Models\Street_market_characteristic', 'id', 'id');
    }

    //Relación traducciones de los mercadillos
    public function translations()
    {
        return $this->hasMany('Travelestt\Models\Street_market_translation', 'id', 'id');
    }

    // Relacion con valoraciones de mercadillos
    public function valoration()
    {
        return $this->hasMany('Travelestt\Models\Street_market_valoration', 'id', 'id');
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
            return $query->join('city', 'city.id', '=', 'streetmarket.id')
                        ->where('city.id', '=', $city);
        } else {
            return $query->join('city', 'city.id', '=', 'streetmarket.id')
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
                return $query->join('streetmarketcharacteristic', 'streetmarketcharacteristic.id', '=', 'streetmarket.id')
                            ->join('characteristicentity', 'characteristicentity.id', '=', 'streetmarketcharacteristic.id')
                            ->where('characteristicentity.name', '=', $types);
            } elseif (count($types) > 1) {
                return $query->join('streetmarketcharacteristic', 'streetmarketcharacteristic.id', '=', 'streetmarket.id')
                            ->join('characteristicentity', 'characteristicentity.id', '=', 'streetmarketcharacteristic.id')
                            ->whereIn('characteristicentity.name', $types);
            }
            
        }
    }

    //Formateo de las consultas de los mercadillos
    public function scopeFormatStreetMarket($query)
    {
        return $query->select('streetmarket.id', 'slug as slug', 'latitude as latitude', 'longitude as longitude', 'media as media', 'status as status', 'created as created', 'updated as updated', 'addedby as added_by', 'updateby as updated_by', 'outstanding as outstanding', 'streetmarket.id')
                        ->with(['city' => function($query) {
                            $query->join('province', 'province.id', '=', 'city.id')
                                    ->select('id', 'name as name_city', 'slug as slug_city', 'province.id as id_province', 'province.name as name_province');
                        }])
                        ->with(['characteristics' => function($query) {
                            $query
                                ->join('characteristicentity', 'characteristicentity.id', '=', 'streetmarketcharacteristic.id')
                                ->select('id', 'characteristicentity.name as name');
                        }])
                        ->with(['translations' => function($query) {
                            $query
                                ->join('language', 'language.id', '=', 'streetmarkettranslation.id')
                                ->join('languagefield', 'languagefield.id', '=', 'streetmarkettranslation.id')
                                ->select('id', 'language.name as language', 'languagefield.name as language_field', 'streetmarkettranslation.content as content');
                        }])
                        ->with(['valoration' => function($query) {
                            $query
                                ->select('streetmarketvaloration.id', DB::raw('ROUND(AVG(streetmarketvaloration.rating), 1) as average, COUNT(streetmarketvaloration.id) as ratings'))
                                ->where('status', '=', 1)
                                ->groupBy('streetmarketvaloration.id');
                        }]);
    }

    //Parseo de las traducciones
    public function parseStreetMarketToFront($street_markets)
    {
        $actual_translation = '';
        $actual_translation_abbr = '';
        for ($i = 0; $i < count($street_markets); $i++) {
            $new_street_market = $street_markets[$i];
            
            $translations = [];
            $new_street_market->media = unserialize($new_street_market->media);
            foreach ($new_street_market->translations as $key => $translation) {
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
                if ($new_street_market->lang != null && $new_street_market->lang == $actual_translation_abbr) {
                    $order_options = [
                        'name'
                    ];
                    foreach ($order_options as $option) {
                        if ($option == $translation->language_field) {
                            if ($option == 'name') {
                                $new_street_market->name = $translation->content;
                            }
                        }
                    }
                }
                $translations[$actual_translation_abbr][$translation->language_field] = $translation->content;
                unset($new_street_market['translations'][$key]);
            }
            unset($new_street_market->translations);
            $new_street_market->translations = $translations;

            $characteristics = [];
            $count_characteristics = 0;
            foreach ($new_street_market->characteristics as $key => $characteristic) {
                $characteristics[$count_characteristics] = $characteristic->name;
                unset($new_street_market['characteristics'][$key]);
                $count_characteristics++;
            }
            unset($new_street_market->characteristics);
            $new_street_market->characteristics = $characteristics;

            $city = [];
            $city['id'] = $new_street_market->city->id;
            $city['name'] = $new_street_market->city->name_city;
            $city['slug'] = $new_street_market->city->slug_city;
            $province = [];
            $province['id'] = $new_street_market->city->id_province;
            $province['name'] = $new_street_market->city->name_province;
            unset($new_street_market->city);

            $location = [
                "latitude" => $new_street_market->latitude,
                "longitude" => $new_street_market->longitude,
                "city" => $city,
                "province" => $province
            ];

            $new_street_market->location = $location;
            unset($new_street_market->latitude);
            unset($new_street_market->longitude);

            $new_street_market->id = $new_street_market->id;

            $new_street_market->valorations = (!empty($new_street_market->valoration[0])) ? array( 'ratings' => $new_street_market->valoration[0]->ratings, 'average' => $new_street_market->valoration[0]->average) : NULL;

            unset($new_street_market->id);
            unset($new_street_market->id);
            unset($new_street_market->valoration);

            $street_markets[$i] = $new_street_market;
        }
        return $street_markets;
    }
}
