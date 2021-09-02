<?php

namespace Asistencias;

use Illuminate\Database\Eloquent\Model;

class carnet_seriales_inutilizados extends Model
{
    protected $table = 'carnet_seriales_inutilizados';
    protected $primaryKey = 'csi_id';
    protected $fillable = ['csi_fecha','csi_hora','csi_cedula','csi_nombre','csi_apellido', 'csi_cod_barra', 'csi_tipo_reporte_id', 'csi_carnet_user_id','csi_tipo_carnet'];
}


