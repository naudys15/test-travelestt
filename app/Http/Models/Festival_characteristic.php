<?php

namespace Travelestt\Models;

use Illuminate\Database\Eloquent\Model;

class Festival_characteristic extends Model
{
    /**
     * Modelo festival_characteristic, donde se almacenan las características de un festival
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'festivalcharacteristic';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'id'
    ];

    //Relación características de los festivales
    public function festival()
    {
        return $this->belongsTo('Travelestt\Models\Festival', 'id', 'id');
    }
}
