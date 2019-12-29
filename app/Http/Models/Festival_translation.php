<?php

namespace Travelestt\Models;

use Illuminate\Database\Eloquent\Model;

class Festival_translation extends Model
{
    /**
     * Modelo festival_translation, donde se almacenan las traducciones de un festival
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'festivaltranslation';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'id',
        'id',
        'content'
    ];

    //Relación traducciones de los festivales
    public function festival()
    {
        return $this->belongsTo('Travelestt\Models\Festival', 'id', 'id');
    }
}
