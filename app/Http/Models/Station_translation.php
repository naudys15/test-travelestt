<?php

namespace Travelestt\Models;

use Illuminate\Database\Eloquent\Model;

class Station_translation extends Model
{
    /**
     * Modelo station_translation, donde se almacenan las traducciones de una estación
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'stationtranslation';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'id',
        'id',
        'content'
    ];

    //Relación traducciones de las estaciones
    public function station()
    {
        return $this->belongsTo('Travelestt\Models\Station', 'id', 'id');
    }
}
