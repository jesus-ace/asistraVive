@extends('menu')
@section('contenido')
<?php if (! $errors->isEmpty()): ?>
	<div class="col-lg-4" style="margin-left: 750px;" id="Alertaerror">
		<div class="alert alert-danger">
			<p><strong>Lo sentimos </strong> Por favor corrige los siguientes errores</p>
			<?php foreach ($errors->all() as $error): ?>
				<li>{{ $error}}</li>
			<?php endforeach ?>	
		</div>
	</div>
<?php endif ?> 
<div class="row">
@if(Session::has('flash_message'))
	<div class="col-lg-4" style="margin-left: 750px;" id="Alerta">
		<div class="alert alert-success">
		{{Session::get('flash_message')}}
		</div>	
	</div>
@endif
@if(Session::has('session'))
	<div class="col-lg-4" style="margin-left: 750px;" id="Alerta">
		<div class="alert alert-danger">
		{{Session::get('session')}}
		</div>	
	</div>
@endif
</div>
<div class="col-md-12">
	<input type="hidden" name="ventana" id="ventana" value="departamentos">

		<div class="panel panel-default" style="margin-left: 20px; margin-right: 20px;">
		  	<!-- Default panel contents -->
		  	<div class="panel-heading"  style="background-color: #e5e8e8;">
		  		DEPARTAMENTOS
		  		<a href="#" data-toggle="modal" data-target="#agregardpto" title="Registrar un nuevo horario" style="display: none;" id="bph_agregar"> 
					<img src="assets/img/iconos/registroh.svg" style="height: 30px;"  align="right">
				</a>
				<a title="Asignación en masa" href="#" data-toggle="modal" data-target="#asigmasa"  align="right" style="display: none;" id="bph_asig_masa"> 
					<img src="assets/img/iconos/addexc.svg" style="height: 30px;"  align="right">
				</a>
		  	</div>
 			<div class="table-responsive">
			  	<!-- Table -->
			  	<table class="table table-hover">
					<thead style='background: #f2f3f4  repeat-x center top; font-size:14px;'>
						<tr>
							<th>
								Código
							</th>
							<th>
								Nombre
							</th>
							<th>
								Teléfono principar
							</th>
							<th>
								Teléfono secundario
							</th>
							<th>
								Estatus
							</th>
							<th class="text-right">
								<div class="modal fade" id="agregardpto" tabindex="-1" role="dialog" aria-labelledby="AgregarHorario" aria-hidden="true">
									<div class="modal-dialog">	
										<div class="modal-content text-center">
											<div class="panel panel-default">
											  	<div class="panel-heading"  style="background-color: #e5e8e8;">
											    	<h3 class="panel-title">
											    		<b>
											    			REGISTRAR UN NUEVO DEPARTAMENTO
											    		</b>
											    		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
											    	</h3>
											  	</div>
											  	<div class="panel-body">
											  		
											  	</div>
											</div>
										</div>
									</div>
								</div>
							</th>
							<th class="text-right">
								<div class="modal fade" id="asigmasa" tabindex="-1" role="dialog" aria-labelledby="Asigmasa" aria-hidden="true">
									<div class="modal-dialog">
										<div class="modal-content text-center">
											<div class="panel panel-default">
											  	<div class="panel-heading"  style="background-color: #e5e8e8;">
											    	<h3 class="panel-title">
											    		<b>
												    		ASIGNACIÓN DE DEPARTAMENTOS
												    	</b>
												    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
											   		</h3>
											  	</div>
											  	<div class="panel-body">

										  			
										  			<ul class="list-group text-left">
											  			<li class="list-group-item">
											  				<label for="inputHorario">Cédula</label>
															<input type="number" name="cedula" class="form-control" placeholder="Ingrese la cédula del empleado" min="5000000" id="cedulaDp">
											  			</li>
											  		</ul>
											  		<form method="POST" action="guarda_dp" >
											  			{{ csrf_field() }}
												  		<ul class="list-group text-left" id="depatos" style="display: none;">
												  			<li class="list-group-item" style="display: none;" id="InfEmp">
												  				
												  			</li>
												  			<li class="list-group-item list-group-item presii text-left">
										                        Presidencia
										                        <div class="material-switch pull-right">
										                            <input id="pres" name="presidencia" type="checkbox" value="2">
										                            <label for="pres" class="label-success"></label>
										                        </div>
										                    </li>
										                    <li class="list-group-item text-left" >
										                    	
										                    	<label style="margin-top: 10px;" class="v">
										                    		Despacho de la presidencia
											                        <div class="material-switch pull-right"style="margin-left: 47px;">
											                            <input id="desp_presidencia" name="desp_presidencia" type="checkbox" class="ve_presi" value="3" />
											                            <label for="desp_presidencia" class="label-success"></label>
											                        </div>
										                    	</label>
										                    	<label >
										                    		Atención al ciudadano
											                        <div class="material-switch pull-right"style="margin-left: 47;">
											                            <input id="aten_ciudadano1" name="aten_ciudadano1" type="checkbox" class="ve_presi" value="4" />
											                            <label for="aten_ciudadano1" class="label-success"></label>
											                        </div>
										                    	</label>
										                    </li>

										                    <li class="list-group-item text-left" >
										                    	
										                    	<label style="margin-top: 10px;" class="v">
										                    		Auditoría interna
											                        <div class="material-switch pull-right" style="margin-left: 123px;">
											                            <input id="aud_interna" name="aud_interna" type="checkbox" class="ve_presi" value="8" />
											                            <label for="aud_interna" class="label-success"></label>
											                        </div>
										                    	</label>
										                    	<label >
										                    		Consultoría jurídica
											                        <div class="material-switch pull-right" style="margin-left: 66px;">
											                            <input id="cons_jurd" name="cons_jurd" type="checkbox" class="ve_presi" value="9" />
											                            <label for="cons_jurd" class="label-success"></label>
											                        </div>
										                    	</label>
										                    </li>

										                    <li class="list-group-item text-left" >
										                    	
										                    	<label style="margin-top: 10px;" class="v">
										                    		Seguridad integral
											                        <div class="material-switch pull-right" style="margin-left: 113px;">
											                            <input id="seg_int" name="seg_int" type="checkbox" class="ve_presi" value="10" />
											                            <label for="seg_int" class="label-success"></label>
											                        </div>
										                    	</label>
										                    	<label style="margin-top: 10px;" >
										                    		Contenido Editorial
											                        <div class="material-switch pull-right" style="margin-left: 66px;">
											                            <input id="cont_edit" name="cont_edit" type="checkbox" class="ve_presi" value="41" />
											                            <label for="cont_edit" class="label-success"></label>
											                        </div>
										                    	</label>

										                    </li>
										                    <!-- Departamentos para la vp de gestion interna -->
												  			<li class="list-group-item list-group-item dp_pres text-left">
										                        Vicepresidencia de gestión interna
										                        <div class="material-switch pull-right">
										                            <input id="vp_gest_inter" name="vp_gest_inter" type="checkbox" class="ve_presi" value="13" />
										                            <label for="vp_gest_inter" class="label-success"></label>
										                        </div>
										                    </li>
										                    <li class="list-group-item list-group-item dp_pres vp_interna text-left" style="display: none;">
										                        
										                    	<label style="margin-left: 40px;">
										                    		Vicepresidencia
										                    	</label>
										                        <div class="material-switch pull-right">
										                            <input id="vp_gest_inter_co" name="vp_gest_inter_co" type="checkbox" class="ve_presi ve_vp_interna" value="13" />
										                            <label for="vp_gest_inter_co" class="label-success"></label>
										                        </div>
										                    </li>
										                    <li class="list-group-item list-group-item dp_pres vp_interna text-left" style="display: none;">
										                        
										                    	<label style="margin-left: 40px;">
										                    		Gerencia de planificación, presupuesto y organización
										                    	</label>
										                        <div class="material-switch pull-right">
										                            <input id="gerencia_plan_pres_org" name="gerencia_plan_pres_org" type="checkbox" class="ve_presi ve_vp_interna" value="14" />
										                            <label for="gerencia_plan_pres_org" class="label-success"></label>
										                        </div>
										                    </li>
										                    <li class="list-group-item list-group-item dp_pres vp_interna text-left" style="display: none;">
										                        
										                    	<label style="margin-left: 40px;">
										                    		Gerencia de administración y contabilidad
										                    	</label>
										                        <div class="material-switch pull-right">
										                            <input id="adm_cont_co" name="adm_cont_co" type="checkbox" class="ve_presi ve_vp_interna" value="15" />
										                            <label for="adm_cont_co" class="label-success"></label>
										                        </div>
										                    </li>
										                    <li class="list-group-item list-group-item dp_pres vp_interna text-left" style="display: none;">
										                        <label style="margin-left: 40px;">
										                    		Gerencia de finanzas
										                    	</label>
										                        <div class="material-switch pull-right">
										                            <input id="finanzas_co" name="finanzas_co" type="checkbox" class="ve_presi ve_vp_interna" value="18" />
										                            <label for="finanzas_co" class="label-success"></label>
										                        </div>
										                    </li>
										                    <li class="list-group-item list-group-item dp_pres vp_interna text-left" style="display: none;">
										                        <label style="margin-left: 40px;">
										                    		Gerencia de servicios generales
										                    	</label>
										                        <div class="material-switch pull-right">
										                            <input id="serv_gen_co" name="serv_gen_co" type="checkbox" class="ve_presi ve_vp_interna" value="16" />
										                            <label for="serv_gen_co" class="label-success"></label>
										                        </div>
										                    </li>
										                    <li class="list-group-item list-group-item dp_pres vp_interna text-left" style="display: none;">
										                        <label style="margin-left: 40px;">
										                    		Gerencia de mercadeo y asuntos públicos
										                    	</label>
										                        <div class="material-switch pull-right">
										                            <input id="merc_asu_pub_co" name="merc_asu_pub_co" type="checkbox" class="ve_presi ve_vp_interna" value="17" />
										                            <label for="merc_asu_pub_co" class="label-success"></label>
										                        </div>
										                    </li>
										                    <li class="list-group-item list-group-item dp_pres vp_interna text-left" style="display: none;">
										                        <label style="margin-left: 40px;">
											                    	Gerencia de talento humano
											                    </label>
										                        <div class="material-switch pull-right">
										                            <input id="tal_hum_co" name="tal_hum_co" type="checkbox" class="ve_presi ve_vp_interna" value="6" />
										                            <label for="tal_hum_co" class="label-success"></label>
										                        </div>
										                    </li>
										                    <!-- Departamentos para la vp de gestion  productiva -->
												  			<li class="list-group-item list-group-item dp_pres  text-left">
										                        Vicepresidencia de gestión productiva
										                        <div class="material-switch pull-right">
										                            <input id="vp_gest_prod" name="vp_gest_prod" type="checkbox" class="ve_presi" value="19" />
										                            <label for="vp_gest_prod" class="label-success"></label>
										                        </div>
										                    </li>
										                    <li class="list-group-item list-group-item dp_pres vp_productiva text-left" style="display: none;">
												  				<label style="margin-left: 40px;">
											                        Vicepresidencia
											                    </label>
										                        <div class="material-switch pull-right">
										                            <input id="vp_gest_prod_co" name="vp_gest_prod_co" type="checkbox" class="ve_presi ve_vp_produc" value="19" />
										                            <label for="vp_gest_prod_co" class="label-success"></label>
										                        </div>
										                    </li>
										                    <!-- Departamentos para la vp de gestion  productiva -->
												  			<li class="list-group-item list-group-item dp_pres vp_productiva text-left" style="display: none;">
												  				<label style="margin-left: 40px;">
											                        Gerencia de 123 T.V.
											                    </label>
										                        <div class="material-switch pull-right">
										                            <input id="123_tv" name="unodos_tv" type="checkbox" class="ve_presi ve_vp_produc" value="40" />
										                            <label for="123_tv" class="label-success"></label>
										                        </div>
										                    </li>
										                    <!-- Departamentos para la vp de gestion  productiva -->
												  			<li class="list-group-item list-group-item dp_pres vp_productiva text-left" style="display: none;">
												  				<label style="margin-left: 40px;">
											                        Gerencia de programación
											                    </label>
										                        <div class="material-switch pull-right">
										                            <input id="programacion" name="programacion" type="checkbox" class="ve_presi ve_vp_produc" value="28" />
										                            <label for="programacion" class="label-success"></label>
										                        </div>
										                    </li>
										                    <!-- Departamentos para la vp de gestion  productiva -->
												  			<li class="list-group-item list-group-item dp_pres vp_productiva text-left" style="display: none;">
												  				<label style="margin-left: 40px;">
											                        Gerencia de Producción Integral
											                    </label>
										                        <div class="material-switch pull-right">
										                            <input id="prod_int" name="prod_int" type="checkbox" class="ve_presi ve_vp_produc" value="26" />
										                            <label for="prod_int" class="label-success"></label>
										                        </div>
										                    </li>
										                    <!-- Departamentos para la vp de gestion  productiva -->
												  			<li class="list-group-item list-group-item dp_pres vp_productiva text-left" style="display: none;">
												  				<label style="margin-left: 40px;">
											                        Gerencia de imagen y promoción
											                    </label>
										                        <div class="material-switch pull-right">
										                            <input id="img_promo" name="img_promo" type="checkbox" class="ve_presi ve_vp_produc" value="32" />
										                            <label for="img_promo" class="label-success"></label>
										                        </div>
										                    </li>
										                    <!-- Departamentos para la vp de gestion  productiva -->
												  			<li class="list-group-item list-group-item dp_pres vp_productiva text-left" style="display: none;">
												  				<label style="margin-left: 40px;">
											                        Gerencia de comunicación popular
											                    </label>
										                        <div class="material-switch pull-right">
										                            <input id="com_pop" name="com_pop" type="checkbox" class="ve_presi ve_vp_produc" value="31" />
										                            <label for="com_pop" class="label-success"></label>
										                        </div>
										                    </li>
										                    <!-- Departamentos para la vp de gestion  productiva -->
												  			<li class="list-group-item list-group-item dp_pres vp_productiva text-left" style="display: none;">
												  				<label style="margin-left: 40px;">
											                        Gerencia de PostProduccion
											                    </label>
										                        <div class="material-switch pull-right">
										                            <input id="postPd" name="postPd" type="checkbox" class="ve_presi ve_vp_produc" value="49" />
										                            <label for="postPd" class="label-success"></label>
										                        </div>
										                    </li>
										                    <!-- Departamentos para la vp de gestion  productiva / SEDES -->
												  			<li class="list-group-item list-group-item dp_pres vp_productiva text-left" style="display: none;">
												  				<label style="margin-left: 40px;">
											                        Sedes
											                    </label>
										                        <div class="material-switch pull-right">
										                            <input id="sedes" name="sedes" type="checkbox" class="ve_presi ve_vp_produc" />
										                            <label for="sedes" class="label-success"></label>
										                        </div>
										                    </li>
										                    <!-- Departamentos para la vp de gestion  productiva / SEDES -->
												  			<li class="list-group-item list-group-item dp_pres sedes_list text-left" style="display: none;">
												  				<label style="margin-left: 80px;">
											                        Sede oriente (Anzoátegui)
											                    </label>
										                        <div class="material-switch pull-right">
										                            <input id="sede_oriente" name="sede_oriente" type="checkbox" class="ve_presi ve_vp_produc ve_sedes" value="20" />
										                            <label for="sede_oriente" class="label-success"></label>
										                        </div>
										                    </li>
										                    <!-- Departamentos para la vp de gestion  productiva / SEDES -->
												  			<li class="list-group-item list-group-item dp_pres sedes_list text-left" style="display: none;">
												  				<label style="margin-left: 80px;">
											                        Sede los llanos (Apure)
											                    </label>
										                        <div class="material-switch pull-right">
										                            <input id="sede_llanos" name="sede_llanos" type="checkbox" class="ve_presi ve_vp_produc ve_sedes" value="21" />
										                            <label for="sede_llanos" class="label-success"></label>
										                        </div>
										                    </li>
										                    <!-- Departamentos para la vp de gestion  productiva / SEDES -->
												  			<li class="list-group-item list-group-item dp_pres sedes_list text-left" style="display: none;">
												  				<label style="margin-left: 80px;">
											                        Sede orinoco (Bolívar)
											                    </label>
										                        <div class="material-switch pull-right">
										                            <input id="sede_orinoco" name="sede_orinoco" type="checkbox" class="ve_presi ve_vp_produc ve_sedes" value="22" />
										                            <label for="sede_orinoco" class="label-success"></label>
										                        </div>
										                    </li>
										                    <!-- Departamentos para la vp de gestion  productiva / SEDES -->
												  			<li class="list-group-item list-group-item dp_pres sedes_list text-left" style="display: none;">
												  				<label style="margin-left: 80px;">
											                        Sede centro-occidente (Lara)
											                    </label>
										                        <div class="material-switch pull-right">
										                            <input id="sede_c_occ" name="sede_c_occ" type="checkbox" class="ve_presi ve_vp_produc ve_sedes" value="23" />
										                            <label for="sede_c_occ" class="label-success"></label>
										                        </div>
										                    </li>
										                    <!-- Departamentos para la vp de gestion  productiva / SEDES -->
												  			<li class="list-group-item list-group-item dp_pres sedes_list text-left" style="display: none;">
												  				<label style="margin-left: 80px;">
											                        Sede los andes (Táchira)
											                    </label>
										                        <div class="material-switch pull-right">
										                            <input id="sede_andes" name="sede_andes" type="checkbox" class="ve_presi ve_vp_produc ve_sedes" value="24" />
										                            <label for="sede_andes" class="label-success"></label>
										                        </div>
										                    </li>
										                    <!-- Departamentos para la vp de gestion  productiva / SEDES -->
												  			<li class="list-group-item list-group-item dp_pres sedes_list text-left" style="display: none;">
												  				<label style="margin-left: 80px;">
											                        Sede occidente (Zulia)
											                    </label>
										                        <div class="material-switch pull-right">
										                            <input id="sede_occ" name="sede_occ" type="checkbox" class="ve_presi ve_vp_produc ve_sedes" value="25" />
										                            <label for="sede_occ" class="label-success"></label>
										                        </div>
										                    </li>



										                    <!-- Departamentos para la vp de gestión para el desarrollo tecnológico -->
												  			<li class="list-group-item list-group-item dp_pres  text-left">
										                        Vicepresidencia de gestión para el desarrollo tecnológico
										                        <div class="material-switch pull-right">
										                            <input id="vp_gest_des_tec" name="vp_gest_des_tec" type="checkbox" class="ve_presi" value="33" />
										                            <label for="vp_gest_des_tec" class="label-success"></label>
										                        </div>
										                    </li>
												  			<li class="list-group-item list-group-item dp_pres vp_des_tec text-left" style="display: none;">
												  				<label style="margin-left: 40px;">
											                        Vicepresidencia
											                    </label>
										                        <div class="material-switch pull-right">
										                            <input id="vp_gest_des_tec_co" name="vp_gest_des_tec_co" type="checkbox" class="ve_presi ve_vp_destec" value="33" />
										                            <label for="vp_gest_des_tec_co" class="label-success"></label>
										                        </div>
										                    </li>
												  			<li class="list-group-item list-group-item dp_pres vp_des_tec text-left" style="display: none;">
												  				<label style="margin-left: 40px;">
											                        Gerencia de ingenieria
											                    </label>
										                        <div class="material-switch pull-right">
										                            <input id="ingenieria" name="ingenieria" type="checkbox" class="ve_presi ve_vp_destec" value="35" />
										                            <label for="ingenieria" class="label-success"></label>
										                        </div>
										                    </li>
												  			<li class="list-group-item list-group-item dp_pres vp_des_tec text-left" style="display: none;">
												  				<label style="margin-left: 40px;">
											                        Gerencia de tecnología de la información
											                    </label>
										                        <div class="material-switch pull-right">
										                            <input id="tec_info" name="tec_info" type="checkbox" class="ve_presi ve_vp_destec" value="34" />
										                            <label for="tec_info" class="label-success"></label>
										                        </div>
										                    </li>
										                    <!-- Departamentos para la vp de gestion  productiva -->
												  			<li class="list-group-item list-group-item dp_pres vp_des_tec text-left" style="display: none;">
												  				<label style="margin-left: 40px;">
											                        Gerencia de servicios a la producción
											                    </label>
										                        <div class="material-switch pull-right">
										                            <input id="serv_prod" name="serv_prod" type="checkbox" class="ve_presi ve_vp_produc" value="37" />
										                            <label for="serv_prod" class="label-success"></label>
										                        </div>
										                    </li>



										                    <!--Departamentos para la vp de gestión de operaciones-->
												  			<li class="list-group-item list-group-item dp_pres  text-left">
										                        Vicepresidencia de gestión de operaciones
										                        <div class="material-switch pull-right">
										                            <input id="vp_gest_ope" name="vp_gest_ope" type="checkbox" class="ve_presi oper" value="42" />
										                            <label for="vp_gest_ope" class="label-success"></label>
										                        </div>
										                    </li>
												  			<li class="list-group-item list-group-item dp_pres vp_ope text-left" style="display: none;">
												  				<label style="margin-left: 40px;">
											                        Vicepresidencia
											                    </label>
										                        <div class="material-switch pull-right">
										                            <input id="vp_gest_ope_co" name="vp_gest_ope_co" type="checkbox" class="ve_presi ve_vp_ope" value="42" />
										                            <label for="vp_gest_ope_co" class="label-success"></label>
										                        </div>
										                    </li>
												  			<li class="list-group-item list-group-item dp_pres vp_ope text-left" style="display: none;">
												  				<label style="margin-left: 40px;">
											                        Gerencia de comunicaciones
											                    </label>
										                        <div class="material-switch pull-right">
										                            <input id="comunicaciones" name="comunicaciones" type="checkbox" class="ve_presi ve_vp_ope" value="43" />
										                            <label for="comunicaciones" class="label-success"></label>
										                        </div>
										                    </li>
												  			<li class="list-group-item list-group-item dp_pres vp_ope text-left" style="display: none;">
												  				<label style="margin-left: 40px;">
											                        Gerencia de operaciones técnicas
											                    </label>
										                        <div class="material-switch pull-right">
										                            <input id="op_tecn" name="op_tecn" type="checkbox" class="ve_presi ve_vp_ope" value="39" />
										                            <label for="op_tecn" class="label-success"></label>
										                        </div>
										                    </li>
												  			<li class="list-group-item list-group-item dp_pres vp_ope text-left" style="display: none;">
												  				<label style="margin-left: 40px;">
											                        Gerencia de transporte
											                    </label>
										                        <div class="material-switch pull-right">
										                            <input id="transporte" name="transporte" type="checkbox" class="ve_presi ve_vp_ope" value="36" />
										                            <label for="transporte" class="label-success"></label>
										                        </div>
										                    </li>
												  		</ul>	
												  		<div class="col-md-12" id="pie_modal">
												  			
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
					<tbody id="horarioCuerpo">
						<?php foreach ($departamento as $dp): 
							if ($dp->dp_status == 1) {
								$st = 'Activo';
							}
							else{
								$st = 'Inactivo';
							}
						?>
							<tr>
								<td>
									{{$dp->dp_codigo}}
								</td>
								<td>
									{{$dp->dp_nombre}}
								</td>
								<td>
									{{$dp->dp_tlf_ppl}}
								</td>
								<td>
									{{$dp->dp_tlf_sec}}
								</td>
								<td>
									{{$st}}
								</td>
								<td class="text-right">
									<a class="editdpto bpdp_modificar"  href="#" data-toggle="modal" data-target="#editarDpto" title="Editar departamento"  onclick="editar_dp({{$dp->dp_id}})"> 
										<img src="assets/img/iconos/editar.svg" class="imgmenuho" >
									</a>
									<div class="modal fade" id="editarDpto" tabindex="-1" role="dialog" aria-labelledby="editarDepto" aria-hidden="true">
										<div class="modal-dialog">
											<div class="modal-content text-center">
												<div class="panel panel-default">
												  	<div class="panel-heading" style="background-color: #e5e8e8;">
												    	<h3 class="panel-title">
												    		<b>
												    			EDITAR DEPARTAMENTO
												    			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												    		</b>
												    	</h3>
												  	</div>
												  	<div class="panel-body">
												    	<form method="POST" action="editar_dp" >
												  			{{ csrf_field() }}
												  			<div class="col-md-12">
												  				<div id="dpto">
														  			
														  		</div>
												  			</div>
													  		

												  			<div class="col-md-12">
												  				<div class="col-lg-6 text-left">
																	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
																</div>
																<div class="col-lg-6 text-right">
																	<button style="background-color: #48c9b0; color:white;" class="btn" type="submit" name="modificar">
																		<b>MODIFICAR</b>
																	</button>
																</div>
												  			</div>

													  	</form>
												  	</div>
												</div>
											</div>
										</div>
									</div>
								</td>
								<td>
									<a href="#" class="bpdp_eliminar eliminarDepto"  title="Eliminar Departamento" onclick="eliminarDp({{$dp->dp_id}})"> 
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
		<?php echo $departamento->render(); ?>
	</center>
</div> 
<script src="assets/js/jquery.js"></script>
<script src="assets/js/Config/departamentos.js"></script>
@endsection