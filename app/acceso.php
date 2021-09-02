<?php

namespace Asistencias;

use Illuminate\Database\Eloquent\Model;

class acceso extends Model
{
    protected $table = 'accesos';
    protected $primaryKey = 'aco_id';
    protected $fillable = ['aco_ro_id','aco_mod_id','aco_pnt_id','aco_acc_id','aco_time_reg', 'aco_user_reg'];
}
