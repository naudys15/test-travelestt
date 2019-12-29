<?php

namespace Travelestt\Models;

use Illuminate\Database\Eloquent\Model;

class Street_market_translation extends Model
{
    /**
     * Modelo street_market_translation, donde se almacenan las traducciones de un mercadillo
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'streetmarkettranslation';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'id',
        'id',
        'content'
    ];

    //Relación traducciones de las mercadillos
    public function streetmarket()
    {
        return $this->belongsTo('Travelestt\Models\Street_market', 'id', 'id');
    }
}
