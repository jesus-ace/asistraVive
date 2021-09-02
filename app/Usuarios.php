<?php

namespace Asistencias;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class Usuarios extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
	use Authenticatable, Authorizable, CanResetPassword; 
	
	protected $table = 'usuarios';

    protected $fillable = ['us_nom', 'us_ape', 'us_ced', 'us_login', 'us_pass', 'us_preg', 'us_resp','us_ro_id', 'us_dp_id', 'us_tdu_id','us_status'];
    protected $primaryKey = 'us_ced';
    protected $hidden = ['us_pass', 'us_resp'];
}
