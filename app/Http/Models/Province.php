<?php

namespace Travelestt\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Province extends Model
{
    use SoftDeletes;
    /**
     * Modelo province, donde se almacenan los estados o provincias del sistema
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'province';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = [
        'name',
        'id'
    ];

    //Relación país
    public function country()
    {
        return $this->belongsTo('Travelestt\Models\Country', 'id', 'id');
    }

    //Relación ciudades
    public function cities()
    {
        return $this->hasMany('Travelestt\Models\City');
    }

    public function scopeByCountry($query, $id)
    {
        return $query->where('id', '=', $id);
    }

    //Formateo de las consultas de las provincias
    public function scopeFormatProvince($query)
    {
        return $query->select('id as id', 'name as name', 'id')
                        ->with(['country' => function($query) {
                            $query->select('id', 'iso as iso', 'name as name');
                        }]);
    }
}
