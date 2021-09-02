<?php

namespace Asistencias;

use Illuminate\Database\Eloquent\Model;

class carnet_provisionales extends Model
{
   protected $table = 'carnet_provisionales';
    protected $primaryKey = 'car_prov_id';
    protected $fillable =['car_prov_cod', 'car_prov_status', 'car_prov_ced'];
}
