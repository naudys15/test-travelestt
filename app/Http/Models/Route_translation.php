<?php

namespace Travelestt\Models;

use Illuminate\Database\Eloquent\Model;

class Route_translation extends Model
{
    /**
     * Modelo route_translation, donde se almacenan las traducciones de una ruta
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'routetranslation';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'id',
        'id',
        'content'
    ];

    //Relación traducciones de las rutas
    public function route()
    {
        return $this->belongsTo('Travelestt\Models\Route', 'id', 'id');
    }
}
