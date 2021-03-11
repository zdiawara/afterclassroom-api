<?php

namespace App;


use App\Admin;
//use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Student;
use App\Teacher;
use App\Referentiel;
use App\Constants\TypeReferentiel;
use App\Exceptions\BadRequestException;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User  extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        //'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function gender(){
        return $this->belongsTo(Referentiel::class);
    }

    /**
     * Relation
     */
    public function userable(){
        return $this->morphTo();
    }

    public function isTeacher (){
        return $this->userable instanceof Teacher;
    }

    public function isStudent (){
        return $this->userable instanceof Student;
    }

    public function isAdmin (){
        return $this->userable instanceof Admin;
    }

    public function isOwner($username){
        return $this->username == $username;
    }

    public function setGenderIdAttribute($id){
        
        if(!is_null($id)){
            $etat = Referentiel::findOrFail($id);
            if($etat->type != TypeReferentiel::GENDER){
                throw new BadRequestException("Referentiel incorrect", 1);
            }
            $this->attributes['gender_id'] = $etat->id;
        }
    }

    // Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
