<?php

namespace Travelestt\Models;

use Illuminate\Database\Eloquent\Model;

class Night_spot_characteristic extends Model
{
    /**
     * Modelo night_spot_characteristic, donde se almacenan las características de un sitio nocturno
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'nightspotcharacteristic';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'id'
    ];

    //Relación características de los sitios nocturnos
    public function nightspot()
    {
        return $this->belongsTo('Travelestt\Models\Night_spot', 'id', 'id');
    }
}
