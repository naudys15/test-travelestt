<?php

namespace Travelestt\Models;

use Illuminate\Database\Eloquent\Model;

class Route_characteristic extends Model
{
    /**
     * Modelo route_characteristic, donde se almacenan las características de una ruta
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'routecharacteristic';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'id'
    ];

    //Relación características de las rutas
    public function route()
    {
        return $this->belongsTo('Travelestt\Models\Route', 'id', 'id');
    }
}
