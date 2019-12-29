<?php

namespace Travelestt\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Entity extends Model
{
    use SoftDeletes;
    /**
     * Modelo entity, donde se almacenan las entidades de la empresa
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'entity';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = [
        'name',
        'description'
    ];

    //Relación categorías
    public function categories()
    {
        return $this->hasMany('Travelestt\Models\Characteristic_category');
    }

    //Relación módulos
    public function modules()
    {
        return $this->hasMany('Travelestt\Models\Module');
    }

    //Formateo de las consultas de las entidades
    public function scopeFormatEntity($query)
    {
        return $query->select('id as id', 'name as name', 'description as description');
    }
}
