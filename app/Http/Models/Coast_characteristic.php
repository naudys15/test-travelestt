<?php

namespace Travelestt\Models;

use Illuminate\Database\Eloquent\Model;

class Coast_characteristic extends Model
{
    /**
     * Modelo coast_characteristic, donde se almacenan las características de una playa o cala
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'coastcharacteristic';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'id'
    ];

    //Relación características de las playas
    public function coast()
    {
        return $this->belongsTo('Travelestt\Models\Coast', 'id', 'id');
    }
}
