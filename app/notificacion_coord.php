<?php

namespace Asistencias;

use Illuminate\Database\Eloquent\Model;

class notificacion_coord extends Model
{
    protected $table = 'notificacion_coord';

    protected $primaryKey = 'not_id';
    protected $fillable = ['not_coord','not_ced_emp','not_motivo','not_asi_id'];

    protected function insert_not($dep,$ced,$motivo,$id)
    {
    	$notificacion = new notificacion_coord;
    	$notificacion->not_coord = $dep;
    	$notificacion->not_ced_emp = $ced;
    	$notificacion->not_motivo = $motivo;
    	$notificacion->not_asi_id = $id;
    	$notificacion->save();
    	$noti = $notificacion->save();
    	return $noti;
    }
}
