<?php

namespace Travelestt\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Route extends Model
{
    use SoftDeletes;
    
    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';
    const DELETED_AT = 'deleted';
    /**
     * Modelo route, donde se almacenan las rutas
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'route';
    protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $fillable = [
        'slug',
        // 'type',
        // 'level',
        'slopes',
        'latitude_start',
        'longitude_start',
        'latitude_end',
        'longitude_end',
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

    //Relación características de las rutas
    public function characteristics()
    {
        return $this->hasMany('Travelestt\Models\Route_characteristic', 'id', 'id');
    }

    //Relación traducciones de las rutas
    public function translations()
    {
        return $this->hasMany('Travelestt\Models\Route_translation', 'id', 'id');
    }

    //Relación estaciones de las rutas
    public function stations()
    {
        return $this->hasMany('Travelestt\Models\Station', 'id', 'id');
    }

    // Relacion con valoraciones de rutas
    public function valoration()
    {
        return $this->hasMany('Travelestt\Models\Route_valoration', 'id', 'id');
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
            return $query->join('city', 'city.id', '=', 'route.id')
                        ->where('city.id', '=', $city);
        } else {
            return $query->join('city', 'city.id', '=', 'route.id')
                        ->where('slug', '=', $city);
        }
    }

    //Búsqueda por filtros de caracteristicas de categorias
    public function scopeByCharacteristics($query, $filters)
    {
        if ($filters == null) {
            return $query;
        } else {
            if (count($filters) == 1) {
                return $query->join('routecharacteristic', 'routecharacteristic.id', '=', 'route.id')
                            ->join('characteristicentity', 'characteristicentity.id', '=', 'routecharacteristic.id')
                            ->where('characteristicentity.name', '=', $filters);
            } elseif (count($filters) > 1) {
                return $query->join('routecharacteristic', 'routecharacteristic.id', '=', 'route.id')
                            ->join('characteristicentity', 'characteristicentity.id', '=', 'routecharacteristic.id')
                            ->whereIn('characteristicentity.name', $filters);
            }
            
        }
    }
    //Formateo de las consultas de las rutas
    public function scopeFormatRoute($query)
    {
        return $query->select('route.id', 'slug as slug', 'latitude_start as latitude_start', 'longitude_start as longitude_start', 'latitude_end as latitude_end', 'longitude_end as longitude_end', 'slopes as slopes', 'media as media', 'status as status', 'created as created', 'updated as updated', 'addedby as added_by', 'updateby as updated_by', 'outstanding as outstanding', 'route.id')
                        ->with(['city' => function($query) {
                            $query->join('province', 'province.id', '=', 'city.id')
                                    ->select('id', 'name as name_city', 'slug as slug_city', 'province.id as id_province', 'province.name as name_province');
                        }])
                        ->with(['characteristics' => function($query) {
                            $query
                                ->join('characteristicentity', 'characteristicentity.id', '=', 'routecharacteristic.id')
                                ->select('routecharacteristic.id', 'characteristicentity.name as name');
                        }])
                        ->with(['translations' => function($query) {
                            $query
                                ->join('language', 'language.id', '=', 'routetranslation.id')
                                ->join('languagefield', 'languagefield.id', '=', 'routetranslation.id')
                                ->select('routetranslation.id', 'language.name as language', 'languagefield.name as language_field', 'routetranslation.content as content');
                        }])
                        ->with(['stations' => function($query) {
                            $query
                                ->join('stationtranslation', 'station.id', '=', 'stationtranslation.id')
                                ->join('language', 'language.id', '=', 'stationtranslation.id')
                                ->join('languagefield', 'languagefield.id', '=', 'stationtranslation.id')
                                ->select('station.id', 'station.id', 'station.latitude as latitude', 'station.longitude as longitude','language.name as language', 'stationtranslation.content as content');
                        }])
                        ->with(['valoration' => function($query) {
                            $query
                                ->select('routevaloration.id', DB::raw('ROUND(AVG(routevaloration.rating), 1) as average, COUNT(routevaloration.id) as ratings'))
                                ->where('status', '=', 1)
                                ->groupBy('routevaloration.id');
                        }]);
    }

    //Parseo de las traducciones
    public function parseRouteToFront($routes)
    {
        $actual_translation = '';
        $actual_translation_abbr = '';
        for ($i = 0; $i < count($routes); $i++) {
            $new_route = $routes[$i];
            $new_route->media = unserialize($new_route->media);

            $translations = [];
            foreach ($new_route->translations as $key => $translation) {
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
                if ($new_route->lang != null && $new_route->lang == $actual_translation_abbr) {
                    $order_options = [
                        'name'
                    ];
                    foreach ($order_options as $option) {
                        if ($option == $translation->language_field) {
                            if ($option == 'name') {
                                $new_route->name = $translation->content;
                            }
                        }
                    }
                }
                $translations[$actual_translation_abbr][$translation->language_field] = $translation->content;
                unset($new_route['translations'][$key]);
            }
            unset($new_route->translations);
            $new_route->translations = $translations;

            $characteristics = [];
            $count_characteristics = 0;
            foreach ($new_route->characteristics as $key => $characteristic) {
                $characteristics[$count_characteristics] = $characteristic->name;
                unset($new_route['characteristics'][$key]);
                $count_characteristics++;
            }
            unset($new_route->characteristics);
            $new_route->characteristics = $characteristics;

            $actual_translation = '';
            $actual_translation_abbr = '';
            $actual_key = 0;
            $translations_stations = [];
            $count_stations = 0;
            foreach ($new_route->stations as $key => $station) {
                if ($actual_key != $station->id) {
                    $actual_key = $station->id;
                    if (!isset($translations_stations[$actual_key])) {
                        $translations_stations[$actual_key] = [];
                    }
                    $translations_stations[$actual_key]['latitude'] = $station->latitude;
                    $translations_stations[$actual_key]['longitude'] = $station->longitude;
                }
                if ($actual_translation != $station->language) {
                    $actual_translation = $station->language;
                    if ($actual_translation == "spanish") {
                        $actual_translation_abbr = "es";
                    } elseif ($actual_translation == "english") {
                        $actual_translation_abbr = "en";
                    } elseif ($actual_translation == "french") {
                        $actual_translation_abbr = "fr";
                    }
                    $translations_stations[$actual_key][$actual_translation_abbr] = [];
                }
                $translations_stations[$actual_key][$actual_translation_abbr] = $station->content;
                $count_stations++;
            }
            unset($new_route->stations);
            $new_route->stations = $translations_stations;

            $city = [];
            $city['id'] = $new_route->city->id;
            $city['name'] = $new_route->city->name_city;
            $city['slug'] = $new_route->city->slug_city;
            $province = [];
            $province['id'] = $new_route->city->id_province;
            $province['name'] = $new_route->city->name_province;
            unset($new_route->city);

            $location = [
                "latitude" => $new_route->latitude,
                "longitude" => $new_route->longitude,
                "city" => $city,
                "province" => $province
            ];

            $new_route->slopes = unserialize($new_route->slopes);
            $new_route->location = $location;
            unset($new_route->latitude);
            unset($new_route->longitude);

            $new_route->id = $new_route->id;

            $new_route->valorations = (!empty($new_route->valoration[0])) ? array( 'ratings' => $new_route->valoration[0]->ratings, 'average' => $new_route->valoration[0]->average) : NULL;

            unset($new_route->id);
            unset($new_route->id);
            unset($new_route->valoration);

            $routes[$i] = $new_route;
        }
        return $routes;
    }
}
