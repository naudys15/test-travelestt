<?php

namespace Travelestt\Models;

use Illuminate\Database\Eloquent\Model;

class Log_activity extends Model
{
    /**
     * Modelo log_activity, donde se almacenan las actividades realizadas por los usuarios
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'logactivity';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'description',
        'date',
        'id'
    ];

    //Búsqueda por usuario
    public function scopeByUser($query, $user)
    {
        return $query->where('id', '=', $user);
    }

    //Búsqueda por fecha específica
    public function scopeBySpecificDate($query, $date)
    {
        return $query->whereDate('date', '=', $date);
    }

    //Búsqueda por rango de fechas
    public function scopeByRangeOfDate($query, $from, $to)
    {
        return $query->whereBetween('date', [$from, $to]);
    }

    //Formateo de las consultas del log
    public function scopeFormatLog($query)
    {
        return $query->select('id as id', 'description as description',  'date as date', 'id');
    }
}
