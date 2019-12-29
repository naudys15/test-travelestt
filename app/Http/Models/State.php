<?php

namespace Travelestt\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class State extends Model
{
    use SoftDeletes;
    /**
     * Modelo country, donde se almacenan los estados o provincias del sistema
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'state';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = [
        'name',
        'id'
    ];
}
