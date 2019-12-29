<?php

namespace Travelestt\Models;

use Illuminate\Database\Eloquent\Model;

class Street_market_characteristic extends Model
{
    /**
     * Modelo street_market_characteristic, donde se almacenan las características de un mercadillo
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'streetmarketcharacteristic';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'id'
    ];

    //Relación características de los mercadillos
    public function streetmarket()
    {
        return $this->belongsTo('Travelestt\Models\Street_market', 'id', 'id');
    }
}
