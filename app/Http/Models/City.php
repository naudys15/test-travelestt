<?php

namespace Travelestt\Models;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    //use SoftDeletes;
    /**
     * Modelo city, donde se almacenan las ciudades del sistema
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'city';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'name',
        'id',
        'id',
        'latitude',
        'longitude',
        'altitude',
        'slug',
        'image'
    ];
    
    //Relación país
    public function country()
    {
        return $this->belongsTo('Travelestt\Models\Country', 'id', 'id');
    }

    //Relación estado
    public function province()
    {
        return $this->belongsTo('Travelestt\Models\Province', 'id', 'id');
    }

    //Relación usuarios
    public function users()
    {
        return $this->hasMany('Travelestt\Models\User', 'id', 'id');
    }

    //Relación playas
    public function coasts()
    {
        return $this->hasMany('Travelestt\Models\Coast', 'id', 'id');
    }

    //Relación festivales
    public function festivals()
    {
        return $this->hasMany('Travelestt\Models\Festival', 'id', 'id');
    }

    //Relación museos
    public function museums()
    {
        return $this->hasMany('Travelestt\Models\Museum', 'id', 'id');
    }

    //Relación sitios nocturnos
    public function nightspots()
    {
        return $this->hasMany('Travelestt\Models\Night_spot', 'id', 'id');
    }

    //Relación puntos de interés
    public function pointsofinterest()
    {
        return $this->hasMany('Travelestt\Models\Point_of_interest', 'id', 'id');
    }

    //Relación mercadillos
    public function streetmarkets()
    {
        return $this->hasMany('Travelestt\Models\Street_market', 'id', 'id');
    }

    //Relación rutas
    public function routes()
    {
        return $this->hasMany('Travelestt\Models\Route', 'id', 'id');
    }

    public function scopeByCountry($query, $id)
    {
        return $query->where('id', '=', $id);
    }

    public function scopeByProvince($query, $id)
    {
        return $query->where('id', '=', $id);
    }

    public function scopeBySlug($query, $slug)
    {
        return $query->where('slug', '=', $slug);
    }

    public function scopeCity($query, $city)
    {
        return $query->where('name', '=', $city);
    }

    public function scopeByFeatured($query)
    {
        return $query->where('outstanding', '=', 1);
    }

    public function scopeByTopDestinations($query)
    {
        return $query->where('top_destination', '=', 1);
    }

    //Formateo de las consultas de las ciudades
    public function scopeFormatCity($query)
    {
        return $query->select('id as id', 'name as name', 'latitude as latitude', 'longitude as longitude', 'altitude as altitude', 'slug as slug', 'outstanding as outstanding', 'top_destination as top_destination', 'image as image', 'id', 'id')
                    ->with(['country' => function($query) {
                        $query->select('id', 'iso as iso', 'name as name');
                    }])->with(['province' => function($query) {
                        $query->select('id', 'name as name');
                    }]);
    }

    //Formateo de las consultas de las ciudades por país
    public function scopeFormatCityByCountry($query)
    {
        return $query->select('id as id', 'name as name', 'latitude as latitude', 'longitude as longitude', 'altitude as altitude', 'slug as slug', 'outstanding as outstanding', 'top_destination as top_destination', 'id', 'id')
                    ->with(['country' => function($query) {
                        $query->select('id', 'iso as iso', 'name as name');
                    }])->with(['province' => function($query) {
                        $query->select('id', 'name as name');
                    }]);
    }

    //Formateo de las consultas de las ciudades por país y provincia
    public function scopeFormatCityByProvince($query)
    {
        return $query->select('id as id', 'name as name', 'latitude as latitude', 'longitude as longitude', 'altitude as altitude', 'slug as slug', 'outstanding as outstanding', 'top_destination as top_destination', 'id', 'id')
                        ->with(['country' => function($query) {
                            $query->select('id', 'iso as iso', 'name as name');
                        }])->with(['province' => function($query) {
                            $query->select('id', 'name as name');
                        }]);
    }
}
