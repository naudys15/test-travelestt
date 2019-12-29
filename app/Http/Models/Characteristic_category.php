<?php

namespace Travelestt\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Characteristic_category extends Model
{
    use SoftDeletes;
    /**
     * Modelo characteristic_category, donde se almacenan las categorías de una característica
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'characteristiccategory';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = [
        'name',
        'description',
        'render',
        'id'
    ];

    //Relación entidad
    public function entity()
    {
        return $this->belongsTo('Travelestt\Models\Entity', 'id', 'id');
    }

    // Relación características
    public function characteristic()
    {
        return $this->hasMany('Travelestt\Models\Characteristic_entity');
    }

    //Formateo de las consultas de las entidades
    public function scopeFormatCategory($query)
    {
        return $query->select('id as id', 'name as name', 'description as description', 'id')
                    ->with(['entity' => function($query) {
                        $query->select('id', 'name as name', 'description as description');
                    }]);
    }
}
