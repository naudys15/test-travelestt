<?php

namespace Travelestt\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Night_spot_valoration extends Model
{
    use SoftDeletes;

    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';
    const DELETED_AT = 'deleted';

    /**
     * Modelo night_spot_valoration, donde se almacenan las valoraciones de los sitios nocturnos
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'nightspotvaloration';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'id',
        'title',
        'content',
        'rating',
        'status'
    ];

    //Relación con sitios nocturnos
    public function nightSpot()
    {
        return $this->belongsTo('Travelestt\Models\Night_spot', 'id', 'id');
    }

    //Relación con usuarios
    public function user()
    {
        return $this->belongsTo('Travelestt\Models\User', 'id', 'id');
    }

    public function scopeComments($query, $id)
    {
        return $query
            ->select(
                '*', 
                DB::raw('TIMESTAMPDIFF(MINUTE, nightspotvaloration.created, NOW()) as minutes'),
                DB::raw('TIMESTAMPDIFF(HOUR, nightspotvaloration.created, NOW()) as hours'),
                DB::raw('TIMESTAMPDIFF(DAY, nightspotvaloration.created, NOW()) as days'),
                DB::raw('TIMESTAMPDIFF(MONTH, nightspotvaloration.created, NOW()) as months'),
                DB::raw('TIMESTAMPDIFF(YEAR, nightspotvaloration.created, NOW()) as years')
            )
            ->where([
                ['id', '=', $id]
            ])
            ->join('user', 'user.id', '=', 'nightspotvaloration.id')
            ->orderBy('created', 'DESC')
            ->get()
            ->map(function($comments) {
                if ($comments->status == 0) {
                    $comments = array(
                        'user'          =>    array(
                            'id'        =>    $comments->id,
                            'name'      =>    $comments->firstname.' '.$comments->lastname,
                            'email'     =>    $comments->email,
                            'image'     =>    $comments->image,
                        )
                    );
                } else {
                    $years = $comments->years;
                    $months = $comments->months;
                    $days = $comments->days;
                    $hours = $comments->hours;
                    $minutes = $comments->minutes;
                    if ($minutes > 0) {
                        if ($hours > 0) {
                            if ($days > 0) {
                                if ($months > 0) {
                                    if ($years > 0) {
                                        $months = $months - ($years * 12);
                                    }
                                    $days = $days - ($years * 365) - ($months * 30);
                                }
                                $hours = $hours - ($years * 365 * 24) - ($months * 30 * 24) - ($days * 24);
                            }
                            $minutes = $minutes - ($years * 365 * 24 * 60) - ($months * 30 * 24 * 60) - ($days * 24 * 60) - ($hours * 60);
                        }
                    }
                    $comments = array(
                        'user'          =>    array(
                            'id'        =>    $comments->id,
                            'name'      =>    $comments->firstname.' '.$comments->lastname,
                            'email'     =>    $comments->email,
                            'image'     =>    $comments->image,
                        ),
                        'title'         =>    $comments->title,
                        'content'       =>    $comments->content,
                        'rating'        =>    $comments->rating,
                        'minutes'       =>    $minutes,
                        'hours'         =>    $hours,
                        'days'          =>    $days,
                        'months'        =>    $months,
                        'years'         =>    $years,
                    );
                }
                return $comments;
            });
    }
}
