<?php

namespace Asistencias;

use Illuminate\Database\Eloquent\Model;
use Asistencias\dp_x_us;
use DB;

class departamentos extends Model
{
    protected $table = 'departamentos';
    protected $primaryKey = 'dp_id';
    protected $fillable = ['dp_nombre','dp_tlf_ppl','dp_tlf_sec','dp_codigo','dp_co_id','dp_status'];

    protected static function departamento_acco($us_dep)
    {
        $dp_acco = dp_x_us::where('dxu_us_ced',$_SESSION['id'])->pluck('dxu_dp_id');
        
        $departamento = DB::table('departamentos')           
        ->whereIn('dp_id',$dp_acco)
        ->get();
        
        return $departamento;
    }

    protected function get_id_dp($codigo)
    {
        $dep = DB::table('departamentos')->where('dp_codigo',$codigo)->get()->pluck('dp_id')->last();
        return $dep;
    }

}
