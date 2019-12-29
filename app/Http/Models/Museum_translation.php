<?php

namespace Travelestt\Models;

use Illuminate\Database\Eloquent\Model;

class Museum_translation extends Model
{
    /**
     * Modelo museum_translation, donde se almacenan las traducciones de un museo
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'museumtranslation';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'id',
        'id',
        'content'
    ];

    //Relación traducciones de los museos
    public function museum()
    {
        return $this->belongsTo('Travelestt\Models\Museum', 'id', 'id');
    }
}
