<?php

namespace Travelestt\Models;

use Illuminate\Database\Eloquent\Model;

class Point_of_interest_characteristic extends Model
{
    /**
     * Modelo point_of_interest_characteristic, donde se almacenan las características de un punto de interés
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'pointofinterestcharacteristic';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'id'
    ];

    //Relación características de los puntos de interés
    public function pointofinterest()
    {
        return $this->belongsTo('Travelestt\Models\Point_of_interest', 'id', 'id');
    }
}
