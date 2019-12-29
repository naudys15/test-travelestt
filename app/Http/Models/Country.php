<?php

namespace Travelestt\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use SoftDeletes;
    /**
     * Modelo country, donde se almacenan los paises del sistema
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'country';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = [
        'iso',
        'name'
    ];

    //Relación estados
    public function provinces()
    {
        return $this->hasMany('Travelestt\Models\Province');
    }
    
    //Formateo de las consultas de los paises
    public function scopeFormatCountry($query)
    {
        return $query->select('id as id', 'iso as iso', 'name as name');
    }
}
