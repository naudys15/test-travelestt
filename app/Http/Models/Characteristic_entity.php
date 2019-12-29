<?php

namespace Travelestt\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Characteristic_entity extends Model
{
    use SoftDeletes;
    /**
     * Modelo characteristic_entity, donde se almacenan las características de una entidad
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'characteristicentity';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = [
        'id',
        'name'
    ];

    //Relación categorías
    public function category()
    {
        return $this->belongsTo('Travelestt\Models\Characteristic_category', 'id', 'id');
    }

    //Formateo de las consultas de las características de las entidades
    public function scopeFormatCharacteristicEntity($query)
    {
        return $query->select('id as id', 'name as name', 'id')
                        ->with(['category' => function($query) {
                            $query->select('id', 'name as name', 'description as description');
                        }]);
    }

    public function scopeByCategory($query, $id)
    {
        return $query->select('id as id', 'name as name', 'id')
                        ->with(['category' => function($query) {
                            $query->select('id', 'name as name', 'description as description');
                        }])->where('id', '=', $id);
    }

    public function scopeByEntity($query, $name)
    {
        return $query->select('id as id', 'name as name', 'characteristiccategory.id')
                        ->with(['category' => function($query) {
                            $query->select('characteristiccategory.id', 'name as name', 'description as description', 'render as render');
                        }])
                        ->join('characteristiccategory', 'characteristiccategory.id', '=', 'characteristicentity.id')
                        ->join('entity', 'entity.id', '=', 'characteristiccategory.id')
                        ->where('entity.name', '=', $name);
    }
}
