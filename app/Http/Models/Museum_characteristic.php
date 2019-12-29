<?php

namespace Travelestt\Models;

use Illuminate\Database\Eloquent\Model;

class Museum_characteristic extends Model
{
    /**
     * Modelo museum_characteristic, donde se almacenan las características de un museo
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'museumcharacteristic';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'id'
    ];

    //Relación características de los museos
    public function museum()
    {
        return $this->belongsTo('Travelestt\Models\Museum', 'id', 'id');
    }
}
