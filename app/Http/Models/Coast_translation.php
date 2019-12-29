<?php

namespace Travelestt\Models;

use Illuminate\Database\Eloquent\Model;

class Coast_translation extends Model
{
    /**
     * Modelo coast_translation, donde se almacenan las traducciones de una playa o cala
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'coasttranslation';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'id',
        'id',
        'content'
    ];

    //Relación traducciones de las playas
    public function coast()
    {
        return $this->belongsTo('Travelestt\Models\Coast', 'id', 'id');
    }
}
