@extends('menu')
@section('contenido')

<?php if (! $errors->isEmpty()): ?>
	<div class="row">
		<div class="col-lg-4" style="margin-left: 750px;" id="Alertaerror">
			<div class="alert alert-danger">
				<p><strong>Lo sentimos </strong> Por favor corrige los siguientes errores</p>
				<?php foreach ($errors->all() as $error): ?>
					<li>{{ $error}}</li>
				<?php endforeach ?>	
			</div>
		</div>
	</div>
<?php endif ?> 


@if(Session::has('flash_message'))
	<div class="row">
		<div class="col-lg-4" style="margin-left: 750px;" id="Alerta">
			<div class="alert alert-success">
			{{Session::get('flash_message')}}
			</div>	
		</div>
	</div>
@endif
@if(Session::has('session'))
	<div class="row">
		<div class="col-lg-4" style="margin-left: 750px;" id="Alerta">
			<div class="alert alert-danger">
			{{Session::get('session')}}
			</div>	
		</div>
	</div>
@endif
@if($aco_rol = 'p_roles')
	<input type="hidden" name="ventana" id="ventana" value="roles">
	<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">

	<div class="panel panel-default">
	  	<!-- Default panel contents -->
	  	<div class="panel-heading" style="background-color: #e5e8e8;">
	  		ROLES
	  		<!-- Boton de la modal -->
			<a href="#" data-toggle="modal" data-target="#modalRol" style="display: block;" id="bproles_agregar"> 
				<img src="assets/img/iconos/agregaru.png" style="height: 35px; margin-top: -25px" align="right">
			</a>
	  	</div>
	  	<div class="table-responsive">
			<table class="table table-hover">
				<thead style='background: #f2f3f4  repeat-x center top; font-size:14px;'>
					<tr>
						<th class="text-left">
							
						</th>
						<th class="text-left">
							Rol
						</th>
						<th class="text-left">
							Descripción
						</th>
						<th class="text-left">
							Estatus
						</th>
						<th class="text-right">
							
							<!-- Modal -->
							<div class="modal fade" id="modalRol" role="dialog">
							    <div class="modal-dialog" id="mdialTamanio">
							        <div class="modal-content text-center">
							            <div class="panel panel-default">
	  										<div class="panel-heading" style="background-color: #e5e8e8;">
												<h3 class="panel-title">
													<b>REGISTRAR UN NUEVO ROL</b>
													<button type="button" class="close" data-dismiss="modal">
									                    <span aria-hidden="true">×</span>
									                </button>
												</h3>
	  										</div>
	  										<div class="panel-body">
	    										<form method="POST" action="registroRol" role="form" accept-charset="UTF-8" enctype="multipart/form-data">
	    											{{ csrf_field()}}
								                	<ul class="list-group" >

		    											<li  class="list-group-item  text-left">
								                			<label>Nombre del Rol</label>
								                			<input type="text" name="rol_name" id="rolName" class="form-control" placeholder="Ingrese el nombre del rol">
									                	</li>

									                	<li  class="list-group-item text-left">
								                			<label>Descripción del rol</label>
								                			<input type="text" name="rol_desc" id="rolName" class="form-control" placeholder="Ingrese la descripción del rol">
									                	</li>

									                    <li class="list-group-item list-group-item  text-left">
									                        Usuarios
									                        <div class="material-switch pull-right">
									                            <input id="m_usuarios" name="m_usuarios" type="checkbox" class="active" />
									                            <label for="m_usuarios" class="label-success"></label>
									                        </div>
									                    </li>
									                    <!--Pantalla de empleados-->
									                    <li class="pm_usuarios list-group-item text-left" style="display:none;">
									                    	<label class="v" id="imagen_candado">
									                    		<img src="assets/img/iconos/candado_c.svg" style="height: 55px;">
									                    	</label>
									                    	<label style="margin-top: 10px;" class="v">
									                    		Empleados
									                    		<div class="material-switch pull-right m_derecha_c" style=" margin-right: 3px;">
										                            <input 
										                            	id="p_empleados" 
										                            	name="p_empleados" 
										                            	type="checkbox" 
										                            	class="cm_usuarios"
										                            />
										                            <label for="p_empleados" class="label-success"></label>
										                        </div>
									                    	</label>
									                    	<label class="v">
									                    		Agregar
									                    		<div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="be_agregar" 
										                            	name="be_agregar" 
										                            	type="checkbox" 
										                            	class="cm_usuarios cp_empleados" 
										                            />
										                            <label for="be_agregar" class="label-success"></label>
										                        </div>
									                    	</label>
									                    	<label class="v">
									                    		Modificar
									                    		<div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="be_modificar" 
										                            	name="bpe_modificar" 
										                            	type="checkbox"
										                            	class="cm_usuarios cp_empleados" 
										                            />
										                            <label for="be_modificar" class="label-success"></label>
										                        </div>
									                    	</label>
									                    </li>

									                    <li class="pm_usuarios list-group-item text-left" style="display:none;">
									                    	<label class="v" id="imagen_candado_h">
									                    		<img src="assets/img/iconos/candado_c.svg" style="height: 55px;">
									                    	</label>
									                    	<label class="v">
									                    		Horarios
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="p_horarios" 
										                            	name="p_horarios" 
										                            	type="checkbox"
										                            	class="cm_usuarios" 
										                            />
										                            <label for="p_horarios" class="label-success"></label>
										                        </div>
										                    </label>
									                    	<label class="v">
									                    		Agregar
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="bh_agregar" 
										                            	name="bph_agregar" 
										                            	type="checkbox" 
										                            	class="cm_usuarios cp_horarios" 
										                            />
										                            <label for="bh_agregar" class="label-success"></label>
										                        </div>
															</label>
															<label class="v" >
																Modificar
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="bph_modificar" 
										                            	name="bph_modificar"
										                            	type="checkbox" 
										                            	class="cm_usuarios cp_horarios" 
										                            />
										                            <label for="bph_modificar" class="label-success"></label>
										                        </div>
															</label>
															<label class="v" >
																Eliminar
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="bph_eliminar" 
										                            	name="bph_eliminar" 
										                            	type="checkbox"
										                            	class="cm_usuarios cp_horarios" 
										                            />
										                            <label for="bph_eliminar" class="label-success"></label>
										                        </div>
															</label>
									                    </li>

									                    <li class="pm_usuarios list-group-item text-left" style="display:none;">
									                        Carnet
									                        <div class="material-switch pull-right">
									                            <input 
									                            	id="sm_carnet" 
									                            	name="sm_carnet" 
									                            	type="checkbox" 
									                            	class="cm_usuarios" 
									                            />
									                            <label for="sm_carnet" class="label-success"></label>
									                        </div>
									                    </li>


									                    <li class="psm_carnet list-group-item text-left" style="display: none;">
									                    	
										                        Carnet de Empleados
										                        <div class="material-switch pull-right">
										                            <input 
										                            	id="b_carnet_nuevo" 
										                            	name="b_carnet_nuevo" 
										                            	type="checkbox" 
										                            	class="cm_usuarios p_carnet" 
										                            />
										                            <label for="b_carnet_nuevo" class="label-success"></label>
										                        </div>
									                    </li> 
									                    <li class="p_new_carnet list-group-item text-left" style="display: none;">
									                    	<label class="v" id="imagen_candado_h">
									                    		<img src="assets/img/iconos/candado_c.svg" style="height: 55px;">
									                    	</label>
									                    	<label class="v">
										                        Nuevos
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="p_carnet_new" 
										                            	name="p_carnet_new" 
										                            	type="checkbox" 
										                            	class="cm_usuarios p_carnet pp_carnet" 
										                            />
										                            <label for="p_carnet_new" class="label-success"></label>
										                        </div>
									                        </label>
									                        <label>
																Modificar
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="bpcn_modificar" 
										                            	name="bpcn_modificar"
										                            	type="checkbox" 
										                            	class="cm_usuarios pp_carnet p_carnet" 
										                            />
										                            <label for="bpcn_modificar" class="label-success"></label>
										                        </div>
															</label>
									                    </li>
									                    <li class="p_new_carnet list-group-item text-left" style="display: none;">
									                    	<label class="v" id="imagen_candado_h">
									                    		<img src="assets/img/iconos/candado_c.svg" style="height: 55px;">
									                    	</label>
									                    	<label class="v">
										                        Reportes
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="p_reportes" 
										                            	name="p_reportes" 
										                            	type="checkbox" 
										                            	class="cm_usuarios p_carnet pp_carnet" 
										                            />
										                            <label for="p_reportes" class="label-success"></label>
										                        </div>
									                        </label>
									                        <label>
																Modificar
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="bpcr_modificar" 
										                            	name="bpcr_modificar"
										                            	type="checkbox" 
										                            	class="cm_usuarios p_carnet pp_carnet" 
										                            />
										                            <label for="bpcr_modificar" class="label-success"></label>
										                        </div>
															</label>
									                    </li>


									                    <li class="psm_carnet list-group-item text-left" style="display: none;">
									                    	
										                        Carnet Provisional
										                        <div class="material-switch pull-right">
										                            <input 
										                            	id="b_carnet_prov" 
										                            	name="b_carnet_prov" 
										                            	type="checkbox" 
										                            	class="cm_usuarios p_carnet" 
										                            />
										                            <label for="b_carnet_prov" class="label-success"></label>
										                        </div>
									                    </li> 

									                    <li class="p_pro_carnet list-group-item text-left" style="display: none;">
									                    	<label class="v" id="imagen_candado_h">
									                    		<img src="assets/img/iconos/candado_c.svg" style="height: 55px;">
									                    	</label>
									                    	<label class="v">
										                        Nuevos
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="p_prox_new" 
										                            	name="p_prox_new" 
										                            	type="checkbox" 
										                            	class="cm_usuarios p_carnet cp_pro" 
										                            />
										                            <label for="p_prox_new" class="label-success"></label>
										                        </div>
									                        </label>
									                        <label>
																Modificar
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="bpcpn_modificar" 
										                            	name="bpcpn_modificar"
										                            	type="checkbox" 
										                            	class="cm_usuarios p_carnet cp_pro" 
										                            />
										                            <label for="bpcpn_modificar" class="label-success"></label>
										                        </div>
															</label>
									                    </li>

									                    <li class="p_pro_carnet list-group-item text-left" style="display: none;">
									                    	<label class="v" id="imagen_candado_h">
									                    		<img src="assets/img/iconos/candado_c.svg" style="height: 55px;">
									                    	</label>
									                    	<label class="v">
										                        Reportes
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="p_prov_rep" 
										                            	name="p_prov_rep" 
										                            	type="checkbox" 
										                            	class="cm_usuarios p_carnet pp_carnet cp_pro" 
										                            />
										                            <label for="p_prov_rep" class="label-success"></label>
										                        </div>
									                        </label>
									                        <label>
																Modificar
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="bpcpr_modificar" 
										                            	name="bpcpr_modificar"
										                            	type="checkbox" 
										                            	class="cm_usuarios p_carnet cp_pro" 
										                            />
										                            <label for="bpcpr_modificar" class="label-success"></label>
										                        </div>
															</label>
									                    </li>

									                    <li class="p_pro_carnet list-group-item text-left" style="display: none;">
									                    	<label class="v" id="imagen_candado_h">
									                    		<img src="assets/img/iconos/candado_c.svg" style="height: 55px;">
									                    	</label>
									                    	<label class="v">
										                        Seriales
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="p_seriales" 
										                            	name="p_seriales" 
										                            	type="checkbox" 
										                            	class="cm_usuarios p_carnet pp_carnet cp_pro" 
										                            />
										                            <label for="p_seriales" class="label-success"></label>
										                        </div>
									                        </label>
									                        <label class="v">
																Agregar
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="bpcps_agregar" 
										                            	name="bpcps_agregar"
										                            	type="checkbox" 
										                            	class="cm_usuarios p_carnet pp_carnet cp_pro" 
										                            />
										                            <label for="bpcps_agregar" class="label-success"></label>
										                        </div>
															</label>
									                        <label class="v">
																Modificar
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="bpcps_modificar" 
										                            	name="bpcps_modificar"
										                            	type="checkbox" 
										                            	class="cm_usuarios p_carnet pp_carnet cp_pro" 
										                            />
										                            <label for="bpcps_modificar" class="label-success"></label>
										                        </div>
															</label>
									                    </li>

									                    <li class="psm_carnet list-group-item text-left" style="display: none;">
									                        Configuracion de diseño
									                        <div class="material-switch pull-right">
									                            <input 
									                            	id="p_conf_dis" 
									                            	name="p_conf_dis" 
									                            	type="checkbox" 
									                            	class="cm_usuarios p_carnet pp_carnet" 
									                            />
									                            <label for="p_conf_dis" class="label-success"></label>
									                        </div>
									                    </li> 

									                    <li class="psm_carnet list-group-item text-left" style="display: none;">
									                        Histórico de carnet
									                        <div class="material-switch pull-right">
									                            <input 
									                            	id="b_hist_c" 
									                            	name="b_hist_c" 
									                            	type="checkbox" 
									                            	class="cm_usuarios p_carnet pp_carnet" 
									                            />
									                            <label for="b_hist_c" class="label-success"></label>
									                        </div>
									                    </li> 

									                    <li class="psm_histo list-group-item text-left" style="display: none;">
									                    	<label class="v" id="imagen_candado_h">
									                    		<img src="assets/img/iconos/candado_c.svg" style="height: 55px;">
									                    	</label>
									                    	<label class="v">
										                        Histórico de carnet de empleado
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="p_histoe" 
										                            	name="p_histoe" 
										                            	type="checkbox" 
										                            	class="cm_usuarios p_carnet cp_histo" 
										                            />
										                            <label for="p_histoe" class="label-success"></label>
										                        </div>
									                    	</label>
									                    	<label class="v">
																Imprimir
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="bphce_imprimir" 
										                            	name="bphce_imprimir"
										                            	type="checkbox" 
										                            	class="cm_usuarios p_carnet pp_carnet cp_pro" 
										                            />
										                            <label for="bphce_imprimir" class="label-success"></label>
										                        </div>
															</label>
									                    </li> 
									                    <li class="psm_histo list-group-item text-left" style="display: none;">
									                        <label class="v" id="imagen_candado_h">
									                    		<img src="assets/img/iconos/candado_c.svg" style="height: 55px;">
									                    	</label>
									                    	<label class="v">
										                        Histórico de carnet provisionales
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="p_histop" 
										                            	name="p_histop" 
										                            	type="checkbox" 
										                            	class="cm_usuarios p_carnet cp_histo" 
										                            />
										                            <label for="p_histop" class="label-success"></label>
										                        </div>
									                    	</label>
									                    	<label class="v">
																Imprimir
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="bphcp_imprimir" 
										                            	name="bphcp_imprimir"
										                            	type="checkbox" 
										                            	class="cm_usuarios p_carnet pp_carnet cp_pro" 
										                            />
										                            <label for="bphcp_imprimir" class="label-success"></label>
										                        </div>
															</label>
									                    </li> 
									                    
									                    <li class="psm_carnet list-group-item text-left" style="display: none;">
									                    	<label class="v" id="imagen_candado_h">
									                    		<img src="assets/img/iconos/candado_c.svg" style="height: 55px;">
									                    	</label>
									                    	<label class="v">
										                        Carnet por vencer
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="p_cpvencer" 
										                            	name="p_cpvencer" 
										                            	type="checkbox" 
										                            	class="cm_usuarios p_carnet pp_carnet" 
										                            />
										                            <label for="p_cpvencer" class="label-success"></label>
										                        </div>
									                        </label>
									                        <label class="v">
																Imprimir
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="bpcpv_imprimir" 
										                            	name="bpcpv_imprimir"
										                            	type="checkbox" 
										                            	class="cm_usuarios p_carnet pp_carnet cp_pro" 
										                            />
										                            <label for="bpcpv_imprimir" class="label-success"></label>
										                        </div>
															</label>
									                        <label class="v">
																Modificar
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="bpcpv_modificar" 
										                            	name="bpcpv_modificar"
										                            	type="checkbox" 
										                            	class="cm_usuarios p_carnet pp_carnet cp_pro" 
										                            />
										                            <label for="bpcpv_modificar" class="label-success"></label>
										                        </div>
															</label>
									                    </li>

									                    <li class="psm_carnet list-group-item text-left" style="display: none;">
									                    	<label class="v" id="imagen_candado_h">
									                    		<img src="assets/img/iconos/candado_c.svg" style="height: 55px;">
									                    	</label>
									                    	<label class="v">
										                        Carnet robados
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="p_crobados" 
										                            	name="p_crobados" 
										                            	type="checkbox" 
										                            	class="cm_usuarios p_carnet pp_carnet" 
										                            />
										                            <label for="p_crobados" class="label-success"></label>
										                        </div>
									                        </label>
									                        <label class="v">
																Imprimir
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="bpcr_imprimir" 
										                            	name="bpcr_imprimir"
										                            	type="checkbox" 
										                            	class="cm_usuarios p_carnet pp_carnet cp_pro" 
										                            />
										                            <label for="bpcr_imprimir" class="label-success"></label>
										                        </div>
															</label>
									                    </li>

									                    <li class="psm_carnet list-group-item text-left" style="display: none;">
									                    	<label class="v" id="imagen_candado_h">
									                    		<img src="assets/img/iconos/candado_c.svg" style="height: 55px;">
									                    	</label>
									                    	<label class="v">
										                        Carnet hurtados
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="p_churtados" 
										                            	name="p_churtados" 
										                            	type="checkbox" 
										                            	class="cm_usuarios p_carnet pp_carnet" 
										                            />
										                            <label for="p_churtados" class="label-success"></label>
										                        </div>
									                        </label>
									                        <label class="v">
																Imprimir
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="bpch_imprimir" 
										                            	name="bpch_imprimir"
										                            	type="checkbox" 
										                            	class="cm_usuarios p_carnet pp_carnet cp_pro" 
										                            />
										                            <label for="bpch_imprimir" class="label-success"></label>
										                        </div>
															</label>
									                    </li>

									                    <li class="psm_carnet list-group-item text-left" style="display: none;">
									                    	<label class="v" id="imagen_candado_h">
									                    		<img src="assets/img/iconos/candado_c.svg" style="height: 55px;">
									                    	</label>
									                    	<label class="v">
										                        Carnet extraviados
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="p_cextra" 
										                            	name="p_cextra" 
										                            	type="checkbox" 
										                            	class="cm_usuarios p_carnet pp_carnet" 
										                            />
										                            <label for="p_cextra" class="label-success"></label>
										                        </div>
									                        </label>
									                        <label class="v">
																Imprimir
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="bpce_imprimir" 
										                            	name="bpce_imprimir"
										                            	type="checkbox" 
										                            	class="cm_usuarios p_carnet pp_carnet cp_pro" 
										                            />
										                            <label for="bpce_imprimir" class="label-success"></label>
										                        </div>
															</label>
									                    </li>
									                    
									                    <li class="psm_carnet list-group-item text-left" style="display: none;">
									                    	<label class="v" id="imagen_candado_h">
									                    		<img src="assets/img/iconos/candado_c.svg" style="height: 55px;">
									                    	</label>
									                    	<label class="v">
										                        Carnet vencidos
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="p_cvencidos" 
										                            	name="p_cvencidos" 
										                            	type="checkbox" 
										                            	class="cm_usuarios p_carnet pp_carnet" 
										                            />
										                            <label for="p_cvencidos" class="label-success"></label>
										                        </div>
									                        </label>
									                        <label class="v">
																Imprimir
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="bpcv_imprimir" 
										                            	name="bpcv_imprimir"
										                            	type="checkbox" 
										                            	class="cm_usuarios p_carnet pp_carnet cp_pro" 
										                            />
										                            <label for="bpcv_imprimir" class="label-success"></label>
										                        </div>
															</label>
									                        <label class="v">
																Modificar
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="bpcv_modificar" 
										                            	name="bpcv_modificar"
										                            	type="checkbox" 
										                            	class="cm_usuarios p_carnet pp_carnet cp_pro" 
										                            />
										                            <label for="bpcv_modificar" class="label-success"></label>
										                        </div>
															</label>
									                    </li>


									                    <li class="psm_carnet list-group-item text-left" style="display: none;">
									                    	<label class="v" id="imagen_candado_h">
									                    		<img src="assets/img/iconos/candado_c.svg" style="height: 55px;">
									                    	</label>
									                    	<label class="v">
										                        Especiales
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="p_cespe" 
										                            	name="p_cespe" 
										                            	type="checkbox" 
										                            	class="cm_usuarios p_carnet pp_carnet" 
										                            />
										                            <label for="p_cespe" class="label-success"></label>
										                        </div>
									                        </label>
									                        <label class="v">
																Agregar
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="bpce_agregar" 
										                            	name="bpce_agregar"
										                            	type="checkbox" 
										                            	class="cm_usuarios p_carnet pp_carnet cp_pro" 
										                            />
										                            <label for="bpce_agregar" class="label-success"></label>
										                        </div>
															</label>
									                        <label class="v">
																Modificar
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="bpce_modificar" 
										                            	name="bpce_modificar"
										                            	type="checkbox" 
										                            	class="cm_usuarios p_carnet pp_carnet cp_pro" 
										                            />
										                            <label for="bpce_modificar" class="label-success"></label>
										                        </div>
															</label>
									                        <label class="v">
																Eliminar
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="bpce_eliminar" 
										                            	name="bpce_eliminar"
										                            	type="checkbox" 
										                            	class="cm_usuarios p_carnet pp_carnet cp_pro" 
										                            />
										                            <label for="bpce_eliminar" class="label-success"></label>
										                        </div>
															</label>
									                    </li> 



									                    <li class="list-group-item text-left">
									                        Asistencia
									                        <div class="material-switch pull-right">
									                            <input id="m_asistencia" name="m_asistencia" type="checkbox"/>
									                            <label for="m_asistencia" class="label-success"></label>
									                        </div>
									                    </li>
									                    <li class="pm_asistencias list-group-item text-left" style="display:none;">
									                    	<label class="v" id="imagen_candado_con">
									                    		<img src="assets/img/iconos/candado_c.svg" style="height: 55px;">
									                    	</label>
									                    	<label class="v">
										                        Control
										                        <div class="material-switch pull-right m_derecha_c" style="margin-right: 10px;">
										                            <input 
										                            	id="p_control" 
										                            	name="p_control" 
										                            	type="checkbox" 
										                            	class="cm_asistencia" 
										                            />
										                            <label for="p_control" class="label-success"></label>
										                        </div>
									                    	</label>
									                    	<label class="v">
									                    		Modificar
									                    		<div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="bc_modificar" 
										                            	name="bpc_modificar" 
										                            	type="checkbox"
										                            	class="cm_asistencia cp_control" 
										                            />
										                            <label for="bc_modificar" class="label-success"></label>
										                        </div>
									                    	</label>
									                    	<label>
									                    		Imprimir
										                        <div class="material-switch pull-right m_derecha_c" style="margin-right: 10px;">
										                            <input 
										                            	id="bc_imprimir" 
										                            	name="bc_imprimir" 
										                            	type="checkbox"
										                            	class="cm_asistencia cp_control" 
										                            />
										                            <label for="bc_imprimir" class="label-success"></label>
										                        </div>
									                    	</label>
									                    </li>
									                    <li class="pm_asistencias list-group-item text-left" style="display:none;">
									                    	<label class="v" id="imagen_candado_mar">
									                    		<img src="assets/img/iconos/candado_c.svg" style="height: 55px;">
									                    	</label>
										                        Marcaje
										                        <div class="material-switch pull-right m_derecha_c" style="margin-right: 10px; margin-top: 20px;">
										                            <input 
										                            	id="p_marcaje" 
										                            	name="p_marcaje" 
										                            	type="checkbox" 
										                            	class="cm_asistencia" 
										                            />
										                            <label for="p_marcaje" class="label-success"></label>
										                        </div>
									                    </li>
									                    <li class="pm_asistencias list-group-item text-left" style="display:none;">
									                    	<label class="v" id="imagen_candado_mar">
									                    		<img src="assets/img/iconos/candado_c.svg" style="height: 55px;">
									                    	</label>
										                        Notificaciones
										                        <div class="material-switch pull-right m_derecha_c" style="margin-right: 10px; margin-top: 20px;">
										                            <input 
										                            	id="p_notificaciones" 
										                            	name="p_notificaciones" 
										                            	type="checkbox" 
										                            	class="cm_asistencia" 
										                            />
										                            <label for="p_notificaciones" class="label-success"></label>
										                        </div>
									                    </li>
									                    <li class="pm_asistencias list-group-item text-left" style="display:none;">
									                    	<label class="v" id="imagen_candado_ae">
									                    		<img src="assets/img/iconos/candado_c.svg" style="height: 55px;">
									                    	</label>	
										                        Asistencia Extraordinaria
										                        <div class="material-switch pull-right m_derecha_c" style=" margin-right: 10px; margin-top: 20px;">
										                            <input 
										                            	id="p_asistenciae" 
										                            	name="p_asistenciae" 
										                            	type="checkbox" 
										                            	class="cm_asistencia" 
										                            />
										                            <label for="p_asistenciae" class="label-success"></label>
										                        </div>
									                    </li>
									                    <li class="pm_asistencias list-group-item text-left" style="display:none;">
									                        Excepciones
									                        <div class="material-switch pull-right">
									                            <input 
									                            	id="sm_excepciones" 
									                            	name="sm_excepciones" 
									                            	type="checkbox" 
									                            	class="cm_asistencia" 
									                            />
									                            <label for="sm_excepciones" class="label-success"></label>
									                        </div>
									                    </li>
									                    <li class="psm_excepciones list-group-item text-left" style="display:none;">
									                    	<label class="v" id="imagen_candado_rep">
									                    		<img src="assets/img/iconos/candado_c.svg" style="height: 55px;">
									                    	</label>
									                    	<label class="v">
										                        Reposos
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="p_reposos" 
										                            	name="p_reposos" 
										                            	type="checkbox" 
										                            	class="cm_asistencia csm_exc" 
										                            />
										                            <label for="p_reposos" class="label-success"></label>
										                        </div>
									                    	</label>
									                    	<label>
									                    		Agregar
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="br_agregar" 
										                            	name="bpr_agregar" 
										                            	type="checkbox" 
										                            	class="cm_asistencia csm_exc cp_reposos" 
										                            />
										                            <label for="br_agregar" class="label-success"></label>
										                        </div>
									                    	</label>
									                    	<label>
									                    		Modificar
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="bpr_modificar" 
										                            	name="bpr_modificar" 
										                            	type="checkbox" 
										                            	class="cm_asistencia csm_exc cp_reposos" 
										                            />
										                            <label for="bpr_modificar" class="label-success"></label>
										                        </div>
									                    	</label>
									                    	<label>
									                    		Eliminar
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="bpr_eliminar" 
										                            	name="bpr_eliminar" 
										                            	type="checkbox" 
										                            	class="cm_asistencia csm_exc cp_reposos" 
										                            />
										                            <label for="bpr_eliminar" class="label-success"></label>
										                        </div>
															</label>
									                    </li>
									                    <li class="psm_excepciones list-group-item text-left" style="display:none;">
									                    	<label class="v" id="imagen_candado_per">
									                    		<img src="assets/img/iconos/candado_c.svg" style="height: 55px;">
									                    	</label>
									                    	<label class="v">
										                        Permisos
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="p_permisos" 
										                            	name="p_permisos" 
										                            	type="checkbox" 
										                            	class="cm_asistencia csm_exc" 
										                            />
										                            <label for="p_permisos" class="label-success"></label>
										                        </div>
										                    </label>
										                    <label>
										                    	Agregar
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="bp_agregar" 
										                            	name="bpp_agregar" 
										                            	type="checkbox" 
										                            	class="cm_asistencia csm_exc cp_permisos" 
										                            />
										                            <label for="bp_agregar" class="label-success"></label>
										                        </div>
										                    </label>
										                    <label>
										                    	Modificar
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="bpp_modificar" 
										                            	name="bpp_modificar" 
										                            	type="checkbox" 
										                            	class="cm_asistencia csm_exc cp_permisos" 
										                            />
										                            <label for="bpp_modificar" class="label-success"></label>
										                        </div>
										                    </label>
										                    <label>
										                    	Eliminar
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="bpp_eliminar" 
										                            	name="bpp_eliminar" 
										                            	type="checkbox" 
										                            	class="cm_asistencia csm_exc cp_permisos" 
										                            />
										                            <label for="bpp_eliminar" class="label-success"></label>
										                        </div>
										                    </label>
									                    </li>
									                    <li class="psm_excepciones list-group-item text-left" style="display:none;">
									                    	<label class="v" id="imagen_candado_vac">
									                    		<img src="assets/img/iconos/candado_c.svg" style="height: 55px;">
									                    	</label>
									                    	<label class="v">
										                        Vacaciones
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="p_vacaciones" 
										                            	name="p_vacaciones" 
										                            	type="checkbox" 
										                            	class="cm_asistencia csm_exc" 
										                            />
										                            <label for="p_vacaciones" class="label-success"></label>
										                        </div>
									                        </label>
									                    	<label>
									                    		Agregar
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="bv_agregar" 
										                            	name="bpv_agregar" 
										                            	type="checkbox" 
										                            	class="cm_asistencia csm_exc cp_vacaciones" 
										                            />
										                            <label for="bv_agregar" class="label-success"></label>
										                        </div>
															</label>
															<label>
																Modificar
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="bpv_modificar" 
										                            	name="bpv_modificar" 
										                            	type="checkbox" 
										                            	class="cm_asistencia csm_exc cp_vacaciones" 
										                            />
										                            <label for="bpv_modificar" class="label-success"></label>
										                        </div>
															</label>
															<label>
																Eliminar
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="bpv_eliminar" 
										                            	name="bpv_eliminar" 
										                            	type="checkbox" 
										                            	class="cm_asistencia csm_exc cp_vacaciones" 
										                            />
										                            <label for="bpv_eliminar" class="label-success"></label>
										                        </div>
															</label>
									                    </li>
									                    <li class="psm_excepciones list-group-item text-left" style="display:none; ">
									                    	<label class="v" id="imagen_candado_au">
									                    		<img src="assets/img/iconos/candado_c.svg" style="height: 55px;">
									                    	</label>
									                    	<label class="v">
										                        Autorización
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="p_autorizacion" 
										                            	name="p_autorizacion" 
										                            	type="checkbox" 
										                            	class="cm_asistencia csm_exc" 
										                            />
										                            <label for="p_autorizacion" class="label-success"></label>
										                        </div>
									                    	</label>
															<label>
																Agregar
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="ba_agregar" 
										                            	name="bpa_agregar" 
										                            	type="checkbox" 
										                            	class="cm_asistencia csm_exc cp_autorizacion" 
										                            />
										                            <label for="ba_agregar" class="label-success"></label>
										                        </div>
															</label>
															<label>
																Modificar
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="bpa_modificar" 
										                            	name="bpa_modificar" 
										                            	type="checkbox" 
										                            	class="cm_asistencia csm_exc cp_autorizacion" 
										                            />
										                            <label for="bpa_modificar" class="label-success"></label>
										                        </div>
															</label>
															<label>
																Eliminar
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="bpa_eliminar" 
										                            	name="bpa_eliminar" 
										                            	type="checkbox" 
										                            	class="cm_asistencia csm_exc cp_autorizacion" 
										                            />
										                            <label for="bpa_eliminar" class="label-success"></label>
										                        </div>
															</label>
									                    </li>

									                    <li class="list-group-item text-left">
									                        Configuración
									                        <div class="material-switch pull-right">
									                            <input id="m_config" name="m_config" type="checkbox" />
									                            <label for="m_config" class="label-success"></label>
									                        </div>
									                    </li>
									                    <li class="pm_config list-group-item text-left" style="display:none;">
									                    	<label class="v" id="imagen_candador">
									                    		<img src="assets/img/iconos/candado_c.svg" style="height: 55px;">
									                    	</label>
									                    	<label class="v">
										                        Roles
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="p_roles" 
										                            	name="p_roles" 
										                            	type="checkbox" 
										                            	class="cm_config" 
										                            />
										                            <label for="p_roles" class="label-success"></label>
										                        </div>
									                    	</label>
									                        <label>
									                        	Agregar
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="brl_agregar" 
										                            	name="bproles_agregar" 
										                            	type="checkbox" 
										                            	class="cm_config cp_roles" 
										                            />
										                            <label for="brl_agregar" class="label-success"></label>
										                        </div>
															</label>
															<label>
																Modificar
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="bproles_modificar" 
										                            	name="bproles_modificar" 
										                            	type="checkbox"
										                            	class="cm_config cp_roles" 
										                            />
										                            <label for="bproles_modificar" class="label-success"></label>
										                        </div>
															</label>
															<label>
																Eliminar
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="bproles_eliminar" 
										                            	name="bproles_eliminar" 
										                            	type="checkbox"
										                            	class="cm_config cp_roles" 
										                            />
										                            <label for="bproles_eliminar" class="label-success"></label>
										                        </div>
															</label>
									                    </li>
									                    <li class="pm_config list-group-item text-left" style="display:none;">
									                    	<label class="v" id="imagen_candadodf">
									                    		<img src="assets/img/iconos/candado_c.svg" style="height: 55px;">
									                    	</label>
									                    	<label class="v">
										                        Días Feriados
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="p_diasf" 
										                            	name="p_diasf" 
										                            	type="checkbox"
										                            	class="cm_config" 
										                            />
										                            <label for="p_diasf" class="label-success"></label>
										                        </div>
									                    	</label>
									                    	<label>
									                    		Agregar
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="bdf_agregar" 
										                            	name="bpdf_agregar" 
										                            	type="checkbox" 
										                            	class="cm_config cp_diasf" 
										                            />
										                            <label for="bdf_agregar" class="label-success"></label>
										                        </div>
															</label>
									                    	<label>
									                    		Modificar
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="bpdf_modificar" 
										                            	name="bpdf_modificar"
										                            	type="checkbox" 
										                            	class="cm_config cp_diasf" 
										                            />
										                            <label for="bpdf_modificar" class="label-success"></label>
										                        </div>
															</label>
									                    	<label>
									                    		Eliminar
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="bpdf_eliminar" 
										                            	name="bpdf_eliminar" 
										                            	type="checkbox"
										                            	class="cm_config cp_diasf" 
										                            />
										                            <label for="bpdf_eliminar" class="label-success"></label>
										                        </div>
															</label>
									                    </li>
									                    <li class="pm_config list-group-item text-left" style="display:none;">
									                    	<label class="v" id="imagen_candadodf">
									                    		<img src="assets/img/iconos/candado_c.svg" style="height: 55px;">
									                    	</label>
									                    	<label class="v">
										                        Días Feriados
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="p_diasf" 
										                            	name="p_diasf" 
										                            	type="checkbox"
										                            	class="cm_config" 
										                            />
										                            <label for="p_diasf" class="label-success"></label>
										                        </div>
									                    	</label>
									                    	<label>
									                    		Agregar
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="bdf_agregar" 
										                            	name="bpdf_agregar" 
										                            	type="checkbox" 
										                            	class="cm_config cp_diasf" 
										                            />
										                            <label for="bdf_agregar" class="label-success"></label>
										                        </div>
															</label>
									                    	<label>
									                    		Modificar
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="bpdf_modificar" 
										                            	name="bpdf_modificar"
										                            	type="checkbox" 
										                            	class="cm_config cp_diasf" 
										                            />
										                            <label for="bpdf_modificar" class="label-success"></label>
										                        </div>
															</label>
									                    	<label>
									                    		Eliminar
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="bpdf_eliminar" 
										                            	name="bpdf_eliminar" 
										                            	type="checkbox"
										                            	class="cm_config cp_diasf" 
										                            />
										                            <label for="bpdf_eliminar" class="label-success"></label>
										                        </div>
															</label>
									                    </li>
									                    <li class="pm_config list-group-item text-left" style="display:none;">
									                        <label class="v" id="imagen_candado">
									                    		<img src="assets/img/iconos/candado_c.svg" style="height: 55px;">
									                    	</label>
									                    	<label class="v">
									                    		Auditoría
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="p_audit" 
										                            	name="p_audit" 
										                            	type="checkbox"
										                            	class="cm_config" 
										                            />
										                            <label for="p_audit" class="label-success"></label>
										                        </div>
										                    </label>
										                    <label>
									                    		Imprimir
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="da_imprimir" 
										                            	name="da_imprimir" 
										                            	type="checkbox"
										                            	class="cm_config cp_aud" 
										                            />
										                            <label for="da_imprimir" class="label-success"></label>
										                        </div>
															</label>
									                    </li>
									                    <li class="pm_config list-group-item text-left" style="display:none;">
									                    	<label class="v" id="imagen_candado">
									                    		<img src="assets/img/iconos/candado_c.svg" style="height: 55px;">
									                    	</label>
									                    	<label class="v">
										                        Actualización<br> de tablas
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="p_actualizacion" 
										                            	name="p_actualizacion" 
										                            	type="checkbox"
										                            	class="cm_config" 
										                            />
										                            <label for="p_actualizacion" class="label-success"></label>
										                        </div>
										                    </label>
									                    	<label style="font-size: 12px;">
									                    		Agregar
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="bact_agregar" 
										                            	name="bact_agregar" 
										                            	type="checkbox" 
										                            	class="cm_config cp_act" 
										                            />
										                            <label for="bact_agregar" class="label-success"></label>
										                        </div>
															</label>
									                    	<label>
									                    		Modificar
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="bact_modificar" 
										                            	name="bact_modificar"
										                            	type="checkbox" 
										                            	class="cm_config cp_act" 
										                            />
										                            <label for="bact_modificar" class="label-success"></label>
										                        </div>
															</label>
									                    </li>
									                    <li class="pm_config list-group-item text-left" style="display:none;">
									                    	<label class="v" id="imagen_candado">
									                    		<img src="assets/img/iconos/candado_c.svg" style="height: 55px;">
									                    	</label>
									                    	<label class="v">
										                        Nuevo Cliente
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="p_newcliente" 
										                            	name="p_newcliente" 
										                            	type="checkbox"
										                            	class="cm_config" 
										                            />
										                            <label for="p_newcliente" class="label-success"></label>
										                        </div>
										                    </label>
									                    	<label style="font-size: 12px;">
									                    		Agregar
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="bnc_agregar" 
										                            	name="bnc_agregar" 
										                            	type="checkbox" 
										                            	class="cm_config cp_ncliente" 
										                            />
										                            <label for="bnc_agregar" class="label-success"></label>
										                        </div>
															</label>
									                    	<label>
									                    		Modificar
										                        <div class="material-switch pull-right m_derecha_c">
										                            <input 
										                            	id="bnc_modificar" 
										                            	name="bnc_modificar"
										                            	type="checkbox" 
										                            	class="cm_config cp_ncliente" 
										                            />
										                            <label for="bnc_modificar" class="label-success"></label>
										                        </div>
															</label>
									                    </li>
									                    <li class="pm_config list-group-item text-left" style="display:none;">
									                    	<label class="v" id="imagen_candado">
									                    		<img src="assets/img/iconos/candado_c.svg" style="height: 55px;">
									                    	</label>
									                        Conexión
									                        <div class="material-switch pull-right" style="margin-top: 20px;">
									                            <input 
									                            	id="p_conexion" 
									                            	name="p_conexion" 
									                            	type="checkbox"
									                            	class="cm_config" 
									                            />
									                            <label for="p_conexion" class="label-success"></label>
									                        </div>
									                    </li>

											        </ul>
											        <div class="col-lg-12" style="border-top: 1px solid #ccc; font-size: 14px;">
														<div class="col-lg-6 text-left" style="margin-top:10px;">
															<button type="button" class="btn btn-secondary" data-dismiss="modal">
												        		Cancelar
												        	</button>
														</div>
												    	<div class="col-lg-6 text-right" style="margin-top:10px;">
												        	<button type="submit" name="registrar" class="btn" style="background-color: #48c9b0; color:white;">
												        		Guardar
												        	</button>
												    	</div>
												    </div>
	    										</form>
											</div>
										</div>
							        </div>
							    </div>
							</div>
						</th>
					</tr>
				</thead>
				<tbody id="RolesBody">
					<?php 
					foreach ($roles as $rol): 
						if ($rol->ro_status == 1) {
							$status = 'Activo';
						} 
						else{ 
							$status = 'Inactivo';
						}
					?>
						<tr value="<?php echo $rol->ro_id?>">		
							<td>
								<img src="assets/img/iconos/team.svg" style="height: 50px;">
							</td>
							<td>
								{{$rol->ro_nom}}
							</td>				
							<td>
								{{$rol->ro_desc}}
							</td>				
							<td>
								{{$status}}
							</td>
							<td class="text-right">
								<!--<a  onclick="editRol({{$rol->ro_id}})"  class="editRol" style="display:none;" onclick="editRol({{$rol->ro_id}})" href="#" data-toggle="modal" data-target="#editRol"> 
									<img src="assets/img/iconos/editar.svg" class="imgmenuho" >
								</a>-->
								<div class="modal fade" id="editRol" tabindex="-1" role="dialog" aria-labelledby="AgregarHorario" aria-hidden="true">
									<div class="modal-dialog" id="mdialTamanio">
										<div class="modal-content text-center">
							            <div class="panel panel-default">
	  										<div class="panel-heading" style="background-color: #e5e8e8;">
												<h3 class="panel-title">
													<b>REGISTRAR UN NUEVO ROL</b>
													<button type="button" class="close" data-dismiss="modal">
									                    <span aria-hidden="true">×</span>
									                </button>
												</h3>
	  										</div>
	  										<div class="panel-body" >
	    										<form method="POST" action="registroRol" role="form" accept-charset="UTF-8" enctype="multipart/form-data">
	    											{{ csrf_field()}}
								                	<ul class="list-group" id="body_roles_edit">
		    											
		    											
											        </ul>
											        <div class="col-lg-12"style="border-top: 1px solid #ccc; font-size: 14px;">
														<div class="col-lg-6 text-left" style="margin-top:10px;">
															<button type="button" class="btn btn-secondary" data-dismiss="modal">
												        		Cancelar
												        	</button>
														</div>
												    	<div class="col-lg-6 text-right" style="margin-top:10px;">
												        	<button type="submit" name="registrar" class="btn" style="background-color: #48c9b0; color:white;">
												        		Guardar
												        	</button>
												    	</div>
												    </div>
	    										</form>
											</div>
										</div>
							        	</div>
									</div>
								</div>
								<a href="#" class="eliminarRol" onclick="eliminarRol({{$rol->ro_id}})" style="display:none;" rolid="{{$rol->ro_id}}"> 
									<img src="assets/img/iconos/eliminar.svg" class="imgmenuho">
								</a>
							</td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
	  	</div>
	</div>


	<center class="paginacion_f">
		<?php echo $roles->render(); ?>
	</center>
@else
<center>
	<img src="imagenes/denegado.png" style="height: 500px">
	<br>
	<b style="font-size: 50px;text-shadow: 1px 2px 5px red">ACCESO DENEGADO</b><br>
	<b style="margin-bottom: 15px;">Usted no tiene permiso para ingresar a esta pantalla</b>
</center>
@endif
	<script src="assets/js/jquery.js"></script>
	<script src="assets/js/Config/roles.js"></script>
@endsection
