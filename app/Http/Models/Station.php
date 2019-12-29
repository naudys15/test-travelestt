<?php

namespace Travelestt\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Station extends Model
{
    use SoftDeletes;
    
    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';
    const DELETED_AT = 'deleted';
    /**
     * Modelo station, donde se almacenan las estaciones de una ruta
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'station';
    protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $fillable = [
        'latitude',
        'longitude',
        'id',
        // 'created',
        // 'updated',
        'id'
    ];

    //Relación con ciudad
    public function city()
    {
        return $this->belongsTo('Travelestt\Models\City', 'id', 'id');
    }

    //Relación estaciones de la ruta
    public function route()
    {
        return $this->belongsTo('Travelestt\Models\Route', 'id', 'id');
    }

    //Relación traducciones de las estaciones
    public function translations()
    {
        return $this->hasMany('Travelestt\Models\Station_translation', 'id', 'id');
    }
}
