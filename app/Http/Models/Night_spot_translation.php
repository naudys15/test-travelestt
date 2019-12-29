<?php

namespace Travelestt\Models;

use Illuminate\Database\Eloquent\Model;

class Night_spot_translation extends Model
{
    /**
     * Modelo night_spot_translation, donde se almacenan las traducciones de un sitio nocturno
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'nightspottranslation';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'id',
        'id',
        'content'
    ];

    //Relación traducciones de los sitios nocturnos
    public function nightspot()
    {
        return $this->belongsTo('Travelestt\Models\Night_spot', 'id', 'id');
    }
}
