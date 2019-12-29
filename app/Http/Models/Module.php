<?php

namespace Travelestt\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Module extends Model
{
    use SoftDeletes;
    /**
     * Modelo module, donde se almacenan los modulos del sistema
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'module';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = [
        'name',
        'description',
        'id'
    ];

    //Relación submódulos
    public function submodules()
    {
        return $this->hasMany('Travelestt\Models\Sub_module');
    }

    //Relación entidad
    public function entity()
    {
        return $this->belongsTo('Travelestt\Models\Entity', 'id', 'id');
    }

    //Formateo de las consultas de los módulos
    public function scopeFormatModule($query)
    {
        return $query->select('id', 'name as name', 'description as description', 'id')
                        ->with(['entity' => function ($query) {
                            $query->select('id', 'name as name', 'description as description');
                        }]);
    }
}
