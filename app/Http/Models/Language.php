<?php

namespace Travelestt\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Language extends Model
{
    use SoftDeletes;
    /**
     * Modelo language, donde se almacenan los lenguajes o idiomas soportados por la empresa
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'language';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = [
        'name',
        'description'
    ];

    //Relación campos susceptibles a lenguaje
    public function fields()
    {
        return $this->hasMany('Travelestt\Models\Language_field');
    }
}
