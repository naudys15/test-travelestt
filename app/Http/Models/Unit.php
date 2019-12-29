<?php

namespace Travelestt\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
    use SoftDeletes;
    /**
     * Modelo unit, donde se almacenan los tipos de duración de una entidad
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'unitduration';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = [
        'name',
        'description'
    ];
}
