<?php

namespace Travelestt\Models;

use Illuminate\Database\Eloquent\Model;

class Point_of_interest_translation extends Model
{
    /**
     * Modelo point_of_interest_translation, donde se almacenan las traducciones de un punto de interés
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'pointofinteresttranslation';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'id',
        'id',
        'content'
    ];

    //Relación traducciones de los puntos de interés
    public function pointofinterest()
    {
        return $this->belongsTo('Travelestt\Models\Point_of_interest', 'id', 'id');
    }
}
