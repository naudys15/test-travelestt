<?php

namespace Travelestt\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Language_field extends Model
{
    use SoftDeletes;
    /**
     * Modelo language_field, donde se almacenan los campos susceptibles a lenguajes o idiomas
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'languagefield';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = [
        'name',
        'description'
    ];

    //Relación lenguaje
    public function language()
    {
        return $this->belongsTo('Travelestt\Models\Language');
    }
}
