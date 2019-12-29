<?php

namespace Travelestt\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Facades\Hash;
use Travelestt\Models\Range_sub_module;
use Travelestt\Models\Sub_module;
use Illuminate\Database\Eloquent\SoftDeletes;


//class User extends Model implements JWTSubject
class User extends Authenticatable implements JWTSubject
{
    use SoftDeletes;
    /**
     * Modelo user, donde se almacenan los usuarios del sistema
     *
     * @return \Illuminate\Http\Response
     */
    protected $table = 'user';
    protected $primaryKey = 'id';
    protected $appends = ['full_name'];
    public $timestamps = true;
    protected $hidden = ['pivot'];
    protected $fillable = [
        'firstname',
        'lastname',
        'id',
        'phonenumber',
        'email',
        'password',
        'key',
        'status',
        'image',
        'id'
    ];

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getUserFullNameAttribute()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    //Relación con ciudad
    public function city()
    {
        return $this->belongsTo('Travelestt\Models\City', 'id', 'id');
    }


    //Relación con roles
    public function role()
    {
        return $this->belongsTo('Travelestt\Models\Role', 'id', 'id');
    }

    //Relacion permisos de usuario
    public function permission()
    {
        return $this->belongsToMany('Travelestt\Models\User','permissionuser', 'id', 'id')
                    ->select('id', 'id');
    }

    // Relacion con valoracion de playas
    public function coastValoration()
    {
        return $this->hasMany('Travelestt\Models\Coast_valoration', 'id', 'id');
    }

    //Búsqueda por id
    public function scopeUserById($query, $id)
    {
        return $query->where('id', '=', $id);
    }

    //Búsqueda de usuarios activos
    public function scopeUsersActive($query)
    {
        return $query->where('status', '=', 1);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function verifyRoleAuthorization($role)
    {
        $role = $this->role()->where('id', '=', $role)->first();
        
        if (!$role) {
            return false;
        }
        return true;
    }

    public function verifyPermissionAuthorization($submodule, $range)
    {
        $submodule = Sub_module::where('name', '=', $submodule)->first();
        if (count($range) == 1) {
            $range = Range_sub_module::where('name', '=', $range)->first();
        } else {
            $range = Range_sub_module::whereIn('name', $range)->get();
        }
        if ($submodule != null){
            foreach ($range as $rang) {
                $permissions = $this->permission()
                                ->where('id', '=', $submodule->id)
                                ->where('id', '=', $rang->id)
                                ->first();
                if ($permissions != null) {
                    return $rang->name;
                }
            }
        }

        return false;
    }

    public function checkCredentials($credentials)
    {
        $users = $this->where('email', '=', $credentials['email'])->first();
        if (!empty($users)) {
            if (Hash::check($credentials['password'], $users->password)) {
                return true;
            } else {
                return 'wrong_password';
            }
        } else {
            return 'wrong_email';
        }
    }

    //Formateo de las consultas de los usuarios
    public function scopeFormatUser($query)
    {
        return $query->select('id', 'firstname as firstname', 'lastname as lastname', 'phonenumber as phonenumber', 'email as email', 'status as status', 'image as image', 'id', 'id')
                    ->with(['city' => function($query) {
                        $query->select('id', 'name as name', 'id');
                    }])
                    ->with(['role' => function($query) {
                        $query->select('id', 'name as name', 'description as description');
                    }])
                    ->with(['permission' => function($query) {
                        $query
                            ->join('submodule', 'permissionuser.id', '=', 'submodule.id')
                            ->join('rangesubmodule', 'permissionuser.id', '=', 'rangesubmodule.id')
                            ->select('submodule.id as id_submodule', 'submodule.name as name_submodule', 'rangesubmodule.id as id_range', 'rangesubmodule.name as name_range')
                            ->orderBy('id_submodule', 'asc');
                    }]);
    }
}
