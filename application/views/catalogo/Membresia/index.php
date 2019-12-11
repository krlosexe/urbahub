<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
<!DOCTYPE html>
<html>
	<link href="<?=base_url();?>assets/template/plugins/bootstrap-fileinput/css/fileinput.css" media="all" rel="stylesheet" type="text/css" />
	
	<link href="<?=base_url();?>assets/template/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
	<link href="<?=base_url();?>assets/template/plugins/bootstrap-datetimepicker-master/css/bootstrap-datetimepicker.css" rel="stylesheet" />
	<style type="text/css">
		.file-drop-zone{
			height: auto;
		}
	</style>
	<!-- select2 -->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
	<!-- -->
		<?php

		 if(($permiso[0]->general==1 && $permiso[0]->detallada==1 && $permiso[0]->registrar==1 && $permiso[0]->actualizar==1 && $permiso[0]->eliminar==1) OR $permiso[0]->status==false): ?>
			<script src="<?=base_url();?>assets/cpanel/js/permiso.js"></script>
		<?php endif ?>
	<body class="theme-blue">
		<input type="hidden" id="ruta" value="<?=base_url();?>" name="ruta">

		<input type="hidden" id="len_num">

		<section class="content">
	        <div class="container-fluid">
	        	<div id="alertas"></div>
	        	<div class="block-header">
	                <ol class="breadcrumb breadcrumb-col-cyan">
                        <li><a href="javascript:void(0);"><?php echo $breadcrumbs[0]["nombre_modulo_vista"]; ?></a></li>
                        <li><?php echo $breadcrumbs[0]["nombre_lista_vista"]; ?></li>
                    </ol>
	            </div>
		    	<!-- Comienzo del cuadro de la tabla -->
				<div class="row clearfix" id="cuadro1">
		            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                <div class="card">
		                    <div class="header">
		                        <h2>
		                            Gestión de <?php echo $breadcrumbs[0]["nombre_lista_vista"]; ?>
		                        </h2>
		                        <ul class="header-dropdown m-r--5">
		                           <!-- <button class="btn btn-primary waves-effect registrar ocultar" onclick="nuevoRegistro()"><i class='fa fa-plus-circle' style="color: white; font-size: 18px;"></i> | Nuevo</button> -->
		                        </ul>
		                    </div>
		                    <div class="body">
		                        <div class="table-responsive">
		                            <table class="table table-bordered table-striped table-hover" id="tabla">
		                                <thead>
		                                    <tr>
		                                    	<th style="text-align: center; padding: 0px 10px 0px 5px;"><input type="checkbox" id="checkall" class="chk-col-blue"/><label for="checkall"></label></th>
		                                     	<th style="width: 17%;">Acciones</th>
		                                        <th style="width: 10%;">N Membresia</th>
		                                        <th>Serial de acceso</th>
		                                        <th>Identificacion</th>
		                                        <th>Tipo persona</th>
		                                        <th>Nombres</th>
		                                        <th>Plan</th>
		                                        <th>Fecha de inicio</th>
		                                        <th>Fecha de Vigencia</th>
		                                        <th>Fecha de Registro</th>
		                                        <th>Registrado Por</th>
		                                    </tr>
		                                </thead>
		                                <tbody></tbody>
		                            </table>
		                            <div class="col-md-2 eliminar ocultar">
		                            	<button class="btn btn-danger waves-effect" onclick="eliminarMultiple('Membresia/eliminar_multiple')">Eliminar seleccionados</button>
		                            </div>
		                            <div class="col-md-2 actualizar ocultar">
		                            	<button class="btn btn-warning waves-effect" onclick="statusMultiple('Membresia/status_multiple', 1, 'activar')">Activar seleccionados</button>
		                            </div>
		                            <div class="col-md-2 actualizar ocultar">
		                            	<button class="btn btn-warning waves-effect" onclick="statusMultiple('Membresia/status_multiple', 2, 'desactivar')">Desactivar seleccionados</button>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        </div>
		        <!-- Cierre del cuadro de la tabla -->

				<!-- Comienzo del cuadro de registrar  -->
				        
				<div class="row clearfix ocultar" id="cuadro2">
			        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			            <div class="card">
			                <div class="header">
			                    <h2>Registro de <?php echo $breadcrumbs[0]["nombre_lista_vista"]; ?></h2>
			                </div>
			                <div class="body">
			                	<div class="table-responsive">
			                        <form name="form_membresia_registrar" id="form_membresia_registrar" method="post" enctype="multipart/form-data">
			                        	<div class="row">
											<div class="col-sm-3">
							        			<img id="imagen_registrar" src="<?php echo base_url();?>assets/cpanel/Usuarios/images/default.png" alt="Tu avatar"/ class="img-responsive ima_error"
												style=" border-radius: 50%;width: 100px;height: 100px;margin: 0 auto; margin-top: 30px;">
												<!-- style="    border-radius: 50%;
												    width: 200px;
												    margin-top: 20px;
												    height: 200px;" -->
							        		</div>
							        		<div class="col-sm-9">
							        			<div class="col-sm-12">
					                            	<table class="table table-bordered table-striped table-hover" id="tableInmobiliariaRegistrar">
					                            		<thead>
					                            			<tr>
					                            				<th>Membresía</th>
					                            				<th>Id renovación</th>
					                            				<th>Horas contratadas</th>
					                            				<th>Valor</th>
					                            				<th>Inicio</th>
					                            				<th>Vigencia</th>
					                            				<th>Condición</th>
					                            			</tr>
					                            		</thead>
					                            		<tbody>
					                            			<tr>
					                            				<th id="numero_membresia">N</th>
					                            				<th id="id_renovacion">N</th>
					                            				<th id="horas_jornadas"></th>
					                            				<th id="precio_plan"></th>
					                            				<th id="fecha_inicio"></th>
					                            				<th id="fecha_fin"></th>
					                            				<th style="">
							                                    	<div class="switch">
																	    <label>
																	      Inac
																	      <input type="checkbox" id="plan_activo" disabled>
																	      <span class="lever"></span>
																	      Act
																	    </label>
																	</div>
														 			<input type="hidden" name="plan_activo_registrar" id="plan_activo_registrar" value="N">
						                            			</th>
					                            			</tr>
					                            		</tbody>
					                            	</table>
					                            	<!-- -->
						                            <div class="col-sm-6 paquetes-membresia" style="    float: right;">
					                            		<label for="paquetes_membresia_registrar">Paquetes*</label>
				                                    	<select name="paquetes_membresia_registrar" id="paquetes_membresia_registrar" required class="form-control" onchange="consultarPlan(this.value,'','guardar');">
				                                    		<option value="" selected>Seleccione</option>
				                                    	</select>
						                            </div>
						                            <!-- -->
					                            	<div class="col-sm-6 planes-membresia" style="    float: right;padding: 0px;">
					                            		<label for="plan_membresia_registrar">Planes*</label>
				                                    	<select name="plan_membresia_registrar" id="plan_membresia_registrar" required class="form-control" onchange="consultarPaquetes(this.value,'guardar');">
				                                    		<option value="" selected>Seleccione</option>
				                                    		<?php foreach ($planes as $plan): ?>
				                                    			<?php if ($plan['status']==true){ ?> 
				                                    				<option value="<?= $plan["id_planes"]; ?>" status="<?=$plan['status'] ?>"><?= $plan["titulo"]." ".$plan["descripcion"]; ?></option>
				                                    			<?php } ?> 
				                                    		<?php endforeach ?>
				                                    	</select>
				                                    	<input type="hidden" name="plan_horas" id="plan_horas">
				                                    	<input type="hidden" name="plan_fecha_inicio" id="plan_fecha_inicio">
				                                    	<input type="hidden" name="plan_fecha_fin" id="plan_fecha_fin">
				                                    	<input type="hidden" name="plan_valor" id="plan_valor">
						                            </div>
						                            
					                            </div>
							        		</div>
			                        	</div>
			                           	<div class="form-group" id="tipopersona">
			    							<label for="tipopersona">Tipo Persona*</label> <br>
			    							<input type="radio" checked name="rad_tipoper" id="fisica"  value="fisica">
			    							<label for= "fisica">Física</label>
			    							<input type="radio" name="rad_tipoper" id="moral"  value="moral">
			    							<label for= "moral">Moral</label>
			    						</div>

								        <ul class="nav nav-tabs">
								        	<li id="tab0" class="active"><a href="#datogeneral" data-toggle="tab" >Datos Generales*</a></li>
								        	<li id="tab2"><a href="#datosTrabajadores" data-toggle="tab" style="display:none" class="hide pestana_datosTrabajadores">Datos de trabajadores</a></li>
								        	<li id="tab1" ><a href="#domicilio" data-toggle="tab" class="pestanaDomicilio " >Saldos*</a></li>
								        	<li id="tab3"><a href="#cuenta" data-toggle="tab" class="pestanaCuenta hide" >Histórico*</a></li>
								        </ul> 
								        <div class="tab-content">
								        	<div class="tab-pane fade in active tab_content0" id="datogeneral">
								        	    <!--Registrar persona física -->
								        	    <div id="personaFisica">
								        	    	<div class="col-sm-4">
					                            		<label for="nombre_cliente">Serial de acceso*</label>
						                                <div class="form-group valid-required">
						                                    <div class="form-line">
						                                        <input type="text" class="fisicaf form-control mayusculas" name="serial_acceso_registrar_fisica" id="serial_acceso_registrar_fisica" autocomplete="off" onkeypress='return solosnumerosyletras(event)'  maxlength="30" placeholder="P. EJ.123456" required>
					                                    	</div>
					                             		</div>
					                           		</div>
					                           		
					                             	<!--<div class="col-sm-4">
					                            		<label for="nombre_cliente">Identificación (Prospecto/CLiente)*</label>
						                                <div class="form-group valid-required">
						                                    <div class="form-line">
						                                        <input type="text" class="fisicaf form-control mayusculas" name="rfc_cliente_registrar_fisica" autocomplete="off" onkeypress='return solosnumerosyletras(event);'  id="rfc_cliente_registrar_fisica" maxlength="30" placeholder="P. EJ.CONRA19901234" required onchange="consultarCliente()" >
					                                    	</div>
					                             		</div>
					                           		</div>-->
					                           		<div class="col-sm-4">
					                            		<label for="cliente_membresia_registrar">Id (Prospecto/CLiente)*</label>
						                                <div class="form-group valid-required">
						                                    <div class="form-line">
						                                        <select name="rfc_cliente_registrar_fisica" id="rfc_cliente_registrar_fisica" required class="fisicaf form-control" style="width:100%;" onchange="consultarCliente()" >
					                            					<option value="" selected>Seleccione</option>
					                            						<?php foreach ($clientes_fisica as $cliente): ?>
					                            					<option value="<?=$cliente['rfc'];?>"><?=$cliente['nombre_datos_personales'];?></option>
					                            						<?php endforeach ?>
					                            				</select>
					                                    	</div>
					                             		</div>
					                           		</div>
										        	<div class="col-sm-4">
					                            		<label for="nombre_cliente">Nombre(s)*</label>
						                                <div class="form-group valid-required">
						                                    <div class="form-line">
						                                        <input type="text" class="fisicaf form-control mayusculas" name="nombre_fisica_registrar" autocomplete="off" onkeypress='return sololetras(event)' id="nombre_fisica_registrar" maxlength="30" placeholder="P. EJ.LUIS RAÚL" readonly="readonly">
					                                    	</div>
					                             		</div>
					                           		</div>
					                           		<div class="col-sm-4">
				                            			<label for="apellido_paterno_cliente">Apellido Paterno*</label>
					                                	<div class="form-group valid-required">
					                                    	<div class="form-line">
					                                        	<input type="text" class="fisicaf form-control mayusculas" name="apellido_paterno_fisica_registrar" autocomplete="off" onkeypress='return sololetras(event)' maxlength="15" id="apellido_paterno_fisica_registrar" placeholder="P. EJ. BELLO" readonly="readonly">
					                                    	</div>
					                                	</div>
					                             	</div>
					                             	<div class="col-sm-4">
					                            		<label for="apellido_materno_cliente">Apellido Materno*</label>
						                                <div class="form-group valid-required">
						                                    <div class="form-line">
						                                        <input type="text" class="fisicaf form-control mayusculas" name="apellido_materno_fisica_registrar" autocomplete="off" maxlength="15" onkeypress='return sololetras(event)' id="apellido_materno_fisica_registrar" placeholder="P. EJ. MENA" readonly="readonly">
						                                    </div>
					                                	</div>
				                          			</div>
				                          			<div class="col-sm-4">
								                		<label for="fecha_nac_datos_registrar">Fecha de Nacimiento*</label>
								                		<div class="form-group valid-required">
								                   			 <div class="form-line input-group fecha">
								                        		<input type="text" class="form-control" name="fecha_nac_fisica_registrar" id="fecha_nac_fisica_registrar" placeholder="dd-mm-yyyy"  readonly="readonly">
								                        		<span class="input-group-addon">
									                       			 <span class="glyphicon glyphicon-calendar"></span>
									                   		 	</span>
								                   			 </div>
								                		</div>
								                    </div>
						                           
						                            <div class="col-sm-4 hide" style="margin-bottom: 60px;">
					                            		<label for="genero_membresia_registrar_fisica">Género*</label>
				                                    	<select name="genero_membresia_fisica_registrar" id="genero_membresia_fisica_registrar"  class="form-control" disabled>
				                                    		<option value="" selected>Seleccione</option>
				                                    		<?php foreach ($sexos as $sexo): ?>
				                                    			<option value="<?=$sexo->id_lista_valor;?>"><?=$sexo->nombre_lista_valor;?></option>
				                                    		<?php endforeach ?>
				                                    	</select>
						                            </div>
						                            <div class="col-sm-4 hide">
					                            		<label for="edo_civil_membresia_registrar">Estado Civil*</label>
				                                    	<select name="edo_civil_fisica_registrar" id="edo_civil_fisica_registrar" class="form-control" disabled>
				                                    		<option value="" selected>Seleccione</option>
				                                    		<?php foreach ($estadosCiviles as $estadoCivil): ?>
				                                    			<option value="<?=$estadoCivil->id_lista_valor;?>"><?=$estadoCivil->nombre_lista_valor;?></option>
				                                    		<?php endforeach ?>
				                                    	</select>
						                            </div>
						                            <div class="col-sm-4 hide">
					                            		<label for="nacionalidad_membresia_registrar">Nacionalidad*</label>
				                                    	<select name="nacionalidad_fisica_registrar" id="nacionalidad_fisica_registrar" class="form-control" disabled>
				                                    		<option value="" selected>Seleccione</option>
				                                    		<?php foreach ($nacionalidades as $nacionalidad): ?>
				                                    			<option value="<?=$nacionalidad->id_lista_valor;?>"><?=$nacionalidad->nombre_lista_valor;?></option>
				                                    		<?php endforeach ?>
				                                    	</select>
						                            </div>
						                            <div class="col-sm-4">
						                                <label for="curp_membresia_registrar">C.U.R.P.*</label>
						                                <div class="form-group form-float">
						                                    <div class="form-line" id="validCurp">
						                                        <input type="text" class="fisicaf form-control mayusculas" autocomplete="off" name="curp_fisica_registrar" id="curp_fisica_registrar" placeholder="P. EJ. BML920313HMLNNSOS" maxlength="18" onkeypress='return solosnumerosyletras(event)' oninput="validarInputCurp(this)" >
						                                    </div>
					                                	   	<span class="curpError text-danger"></span>
					                               		</div>
					                            	</div>
						                            <div class="col-sm-4 hide">
						                                <label for="curp_membresia_registrar">Pasaporte</label>
						                                <div class="form-group form-float">
						                                    <div class="form-line" id="validCurp">
						                                        <input type="text" class="fisicaf form-control mayusculas" autocomplete="off" name="pasaporte_fisica_registrar" id="pasaporte_fisica_registrar" placeholder="P. EJ. 76543ER4325" maxlength="18" onkeypress='return solosnumerosyletras(event)'  oninput="validarInputCurp(this)"  readonly="readonly">
						                                    </div>
					                                	   	<span class="curpError text-danger"></span>
					                               		</div>
					                            	</div>
					                            	<div class="col-sm-4">
					                                	<label for="telefono_registrar">Teléfono*</label>
						                                <div class="form-group valid-required">
						                                    <div class="form-line">
						                                        <input type="text" class="form-control telefono fisicaf" name="telefono_fisica_registrar" id="telefono_fisica_registrar" placeholder="P. EJ.: +00 (000) 000-00-00" onkeyup="validPhone(this)"  readonly="readonly">
				                                        		<span class="emailError text-danger"></span>
						                                    </div>
				                               		 	</div>
			                            			</div>
				                            		<div class="col-sm-4">
						                                <label for="correo_usuario_registrar">Correo Electrónico*</label>
						                                <div class="form-group valid-required">
					                                    	<div class="form-line">
					                                        <input type="email" class="form-control fisicaf" autocomplete="off" name="correo_fisica_registrar" id="correo_fisica_registrar" placeholder="P. EJ. ejemplo@dominio.com" onchange="validEmail(this)" readonly="readonly">
				                                        	<span class="emailError text-danger"></span>
				                                    		</div>
			                                			</div>
			                            			</div>
			                            			<div class="col-sm-4">
				                            	   		<label for="actividad_economica">Actividad Economica*</label>
					                               		<select name="actividad_economica_fisica_registrar" id="actividad_economica_fisica_registrar" class="fisicaf form-control" disabled>
			                            					<option value="" selected>Seleccione</option>
			                            						<?php foreach ($actividadesEconomicas as $actividadEconomica): ?>
			                            					<option value="<?=$actividadEconomica->id_lista_valor;?>"><?=$actividadEconomica->nombre_lista_valor;?></option>
			                            						<?php endforeach ?>
			                            				</select>
				                            		</div>
				                            		<!-- ==============Inicio de direccion=================-->

				                            		<div class="col-lg-12">
				                            			<h3>Dirección</h3>
				                            			<hr>
				                            		</div>
				                            		<div class="col-sm-4">
						                                <label for="calle_contacto_registrar">Calle*</label>
						                                <div class="form-group valid-required">
						                                    <div class="form-line">
						                                        <input type="text" class="form-control mayusculas" name="calle_fisica_registrar" id="calle_fisica_registrar" placeholder="P. EJ. PRIMAVERA" readonly="readonly">
						                                    </div>
						                                </div>
						                            </div>
						                            <div class="col-sm-4">
						                                <label for="exterior_contacto_registrar">Número Exterior*</label>
						                                <div class="form-group valid-required">
						                                    <div class="form-line">
						                                        <input type="text" class="form-control mayusculas" name="exterior_fisica_registrar" maxlength="30" onkeypress='return solosnumerosyletras(event)' id="exterior_fisica_registrar" placeholder="P. EJ. 33" maxlength="10" readonly="readonly">
						                                    </div>
						                                </div>
						                            </div>
						                            <div class="col-sm-4">
						                                <label for="interior_contacto_registrar">Número Interior</label>
						                                <div class="form-group">
						                                    <div class="form-line">
						                                        <input type="text" class="form-control mayusculas interior_contacto_registrar_fisica" onkeypress='return solosnumerosyletras(event)' name="numero_interior_fisica_registrar" id="numero_interior_fisica_registrar" placeholder="P. EJ. 2" maxlength="10" readonly="readonly">
						                                    </div>
						                                </div>
						                            </div>
						                            <div class="col-sm-4">
						                                <label for="codigo_postal_registrar">Código Postal*</label>
						                                <div class="form-group valid-required">
						                                    <div class="form-line">
						                                        <input type="text" class="form-control" id="codigo_postal_fisica_registrar" onkeypress='return codigoPostal(event)' name="codigo_postal_fisica_registrar" maxlength="5" onchange="buscarCodigosUs(this.value, 'create')" readonly="readonly">
						                                    </div>
						                                </div>
						                            </div>
						                            <div class="col-sm-4">
						                            	<label for="colonia_registrar">Colonia*</label>
						                            	<div class="valid-required">
						                                <select id="colonia_fisica_registrar" class="form-control form-group" name="colonia_fisica_registrar" disabled>
							                                	<option value="">Seleccione</option>
							                                </select>
							                            </div>
						                            </div>
						                            <div class="col-sm-4"  style="padding-bottom: 10px;">
						                                <label for="municipio_registrar">Municipio*</label>
						                                <div class="valid-required">
							                                <select id="municipio_fisica_registrar" class="form-control form-group" name="municipio_fisica_registrar" disabled>
							                                	<option value="">Seleccione</option>
							                                </select>
							                            </div>
						                            </div>
						                            <div class="col-sm-4">
						                                <label for="ciudad_registrar">Ciudad*</label>
						                                <div class="valid-required">
							                                <select id="ciudad_fisica_registrar" class="form-control form-group" name="ciudad_fisica_registrar" disabled>
							                                	<option value="">Seleccione</option>
							                                </select>
							                            </div>
						                            </div>
						                            <div class="col-sm-4">
						                                <label for="estado_registrar">Estado*</label>
						                                <div class="valid-required">
							                                <select id="estado_fisica_registrar" class="form-control form-group" name="estado_fisica_registrar" disabled>
							                                	<option value="">Seleccione</option>
							                                </select>
							                            </div>
						                            </div>
				                           			<!-- ===============================-->

										        </div>
									        	<!--Fin de registrar persona física -->
									        	<div id="personaMoral" style="display: none;">
									        		<div class="col-sm-4">
					                            		<label for="serial_acceso_registrar_moral">Serial de acceso*</label>
						                                <div class="form-group valid-required">
						                                    <div class="form-line">
						                                        <input type="text" class="moralf form-control mayusculas" name="serial_acceso_registrar_moral" autocomplete="off" onkeypress='return solosnumerosyletras(event)' id="serial_acceso_moral" maxlength="30" placeholder="P. EJ.123456" required>
					                                    	</div>
					                             		</div>
					                           		</div>
					                           		<div class="col-sm-4">
					                            		<label for="identificacion_prospecto">Identificación (Prospecto/CLiente)*</label>
						                                <div class="form-group valid-required">
						                                    <div class="form-line">
						                                        <!--<input type="text" class="moralf form-control mayusculas" name="rfc_cliente_registrar_moral" autocomplete="off" onkeypress='return solosnumerosyletras(event)'  id="rfc_cliente_registrar_moral" maxlength="30" placeholder="P. EJ.CONRA19901234" required onchange="consultarCliente()">-->
						                                        <select name="rfc_cliente_registrar_moral" id="rfc_cliente_registrar_moral" required class="moralf form-control" style="width:100%;" onchange="consultarCliente()" >
					                            					<option value="" selected>Seleccione</option>
					                            						<?php foreach ($clientes_moral as $cliente): ?>
					                            					<option value="<?=$cliente['rfc'];?>"><?=$cliente['nombre_datos_personales'];?></option>
					                            						<?php endforeach ?>
					                            				</select>
					                                    	</div>
					                             		</div>
					                           		</div>
										        		<div class="col-sm-4">
						                            		<label for="razon_social">Denominación o Razón Social*</label>
							                                <div class="form-group valid-required">
							                                    <div class="form-line">
							                                        <input type="text" class="moralf form-control mayusculas" name="razon_social_registrar" id="razon_social_moral_registrar" maxlength="30" placeholder="P. EJ.AG SITEMAS" readonly="readonly">
						                                    	</div>
						                             		</div>
						                           		</div>
						                           	
							                            <div class="col-sm-4 hide" style="margin-bottom: 50px;">
						                            		<label for="genero_registrar hide">Género*</label>
					                                    	<select name="genero_registrar" id="genero_membresia_registrar_moral" class="form-control" disabled>
					                                    		<option value="" selected>Seleccione</option>
					                                    		<?php foreach ($sexos as $sexo): ?>
					                                    			<option value="<?=$sexo->id_lista_valor;?>"><?=$sexo->nombre_lista_valor;?></option>
					                                    		<?php endforeach ?>
					                                    	</select>
							                            </div>
							                            <div class="col-sm-4 hide" style="margin-bottom: 50px;">
						                            		<label for="edo_civil_membresia_registrar">Estado Civil*</label>
					                                    	<select name="edo_civil_membresia_registrar" id="edo_civil_membresia_registrar_moral" class="form-control" disabled>
					                                    		<option value="" selected>Seleccione</option>
					                                    		<?php foreach ($estadosCiviles as $estadoCivil): ?>
					                                    			<option value="<?=$estadoCivil->id_lista_valor;?>"><?=$estadoCivil->nombre_lista_valor;?></option>
					                                    		<?php endforeach ?>
					                                    	</select>
							                            </div>
							                            <div class="col-sm-4 hide">
						                            		<label for="nacionalidad_membresia_registrar">Nacionalidad*</label>
					                                    	<select name="nacionalidad_membresia_registrar" id="nacionalidad_membresia_moral_registrar" class="form-control"  style="margin-bottom: 50px;" disabled>
					                                    		<option value="" selected>Seleccione</option>
					                                    		<?php foreach ($nacionalidades as $nacionalidad): ?>
					                                    			<option value="<?=$nacionalidad->id_lista_valor;?>"><?=$nacionalidad->nombre_lista_valor;?></option>
					                                    		<?php endforeach ?>
					                                    	</select>
							                            </div>
							                            <div class="col-sm-4 " style="margin-bottom: 10px;">
						                            		<label for="fecha_cons_r">Fecha Nacimiento*</label>
					                                		<div class="form-group valid-required">
					                                   			 <div class="form-line input-group fecha">
					                                        		<input type="text" class="moralf form-control" name="fecha_nac_moral_registrar" id="fecha_nac_moral_registrar" placeholder="dd-mm-yyyy" readonly="readonly">
					                                        		<span class="input-group-addon">
										                       			 <span class="glyphicon glyphicon-calendar"></span>
										                   		 	</span>
					                                   			 </div>
					                                		</div>
							                            </div>
							                           <!-- <div class="col-sm-4 hide">
							                                <label for="curp_membresia_registrar">Pasaporte</label>
							                                <div class="form-group form-float">
							                                    <div class="form-line" id="validCurp">
							                                        <input type="text" class="form-control mayusculas" autocomplete="off" name="pasaporte_registrar" id="pasaporte_moral_registrar" placeholder="P. EJ. 76543ER4325" maxlength="18" onkeypress='return solosnumerosyletras(event)' oninput="validarInputCurp(this)" >
							                                    </div>
						                                	   	<span class="curpError text-danger"></span>
						                               		</div>
						                            	</div>-->
							                           <div class="col-sm-4">
					                            			<label for="correo_moral_m">Correo Electrónico*</label>
							                                <div class="form-group valid-required">
						                                    	<div class="form-line">
						                                        <input type="email" class="form-control moralf" name="correo_cliente" id="correo_moral_registrar" placeholder="P. EJ. ejemplo@dominio.com" onchange="validEmail(this)" readonly="readonly">

					                                        	<span class="emailError text-danger"></span>
					                                    		</div>
				                                			</div>
					                            		</div>
							                            <div class="col-sm-4">
						                            		<label for="telefono_moral_m">Teléfono*</label>
							                                <div class="form-group valid-required">
							                                    <div class="form-line">
							                                        <input type="text" class="moralf form-control telefono" name="telefono_cliente" id="telefono_moral_registrar" placeholder="P. EJ.: +00 (000) 000-00-00" onkeyup="validPhone(this)" readonly="readonly">
					                                        		<span class="emailError text-danger"></span>
							                                    </div>
					                               		 	</div>
				                            			</div>
							                            <div class="col-sm-4">
					                                		<label for="actividad_economica">Giro Mercantil*</label>
						                               		<select name="giro_mercantil_registrar" id="giro_mercantil_moral_registrar" class="moralf form-control" disabled>
				                            					<option value="" selected>Seleccione</option>
				                            						<?php foreach ($giros as $giro): ?>
				                            					<option value="<?=$giro->id_lista_valor;?>"><?=$giro->nombre_lista_valor;?></option>
				                            						<?php endforeach ?>
				                            				</select>
				                            			</div>
				                            			
					                            		<!-- ==============Inicio de direccion=================-->

					                            		<div class="col-lg-12">
					                            			<h3>Dirección</h3>
					                            			<hr>
					                            		</div>
					                            		<div class="col-sm-4">
							                                <label for="calle_contacto_registrar">Calle*</label>
							                                <div class="form-group valid-required">
							                                    <div class="form-line">
							                                        <input type="text" class="form-control mayusculas" name="calle_cliente" id="calle_contacto_moral_registrar" placeholder="P. EJ. PRIMAVERA" readonly="readonly">
							                                    </div>
							                                </div>
							                            </div>
							                            <div class="col-sm-4">
							                                <label for="exterior_contacto_registrar">Número Exterior*</label>
							                                <div class="form-group valid-required">
							                                    <div class="form-line">
							                                        <input type="text" class="form-control mayusculas" name="numero_exterior" maxlength="30" onkeypress='return solosnumerosyletras(event)' id="exterior_moral_registrar" placeholder="P. EJ. 33" maxlength="10" readonly="readonly">
							                                    </div>
							                                </div>
							                            </div>
							                            <div class="col-sm-4">
							                                <label for="interior_contacto_registrar">Número Interior</label>
							                                <div class="form-group">
							                                    <div class="form-line">
							                                        <input type="text" class="form-control mayusculas numero_exterior" onkeypress='return solosnumerosyletras(event)' name="numero_interior" id="interior_moral_registrar" placeholder="P. EJ. 2" maxlength="10" readonly="readonly">
							                                    </div>
							                                </div>
							                            </div>
										                <div class="col-sm-4">
							                                <label for="codigo_postal_registrar">Código Postal*</label>
							                                <div class="form-group valid-required">
							                                    <div class="form-line">
							                                        <input type="text" class="form-control" id="codigo_postal_moral_registrar" onkeypress='return codigoPostal(event)' name="codigo_postal_registrar" maxlength="5" onchange="buscarCodigosUs(this.value, 'create')" readonly="readonly" >
							                                    </div>
							                            </div>
							                            </div>
							                            <div class="col-sm-4">
							                            	<label for="colonia_registrar">Colonia*</label>
							                            	<div class="valid-required">
							                                <select id="colonia_registrar_moral" class="form-control form-group" name="colonia_registrar" disabled>
								                                	<option value="">Seleccione</option>
								                                </select>
								                            </div>
							                            </div>
							                            <div class="col-sm-4"  style="padding-bottom: 10px;">
							                                <label for="municipio_registrar">Municipio*</label>
							                                <div class="valid-required">
								                                <select id="municipio_moral_registrar" class="form-control form-group" name="municipio_registrar" disabled>
								                                	<option value="">Seleccione</option>
								                                </select>
								                            </div>
							                            </div>
							                            <div class="col-sm-4">
							                                <label for="ciudad_registrar">Ciudad*</label>
							                                <div class="valid-required">
								                                <select id="ciudad_moral_registrar" class="form-control form-group" name="ciudad_registrar" disabled>
								                                	<option value="">Seleccione</option>
								                                </select>
								                            </div>
							                            </div>
							                            <div class="col-sm-4">
							                                <label for="estado_registrar">Estado*</label>
							                                <div class="valid-required">
								                                <select id="estado_moral_registrar" class="form-control form-group" name="estado_registrar" disabled>
								                                	<option value="">Seleccione</option>
								                                </select>
								                            </div>
							                            </div>
					                           			<!-- ===============================-->
							                    </div>
									        </div>
								        
								        	<div class="tab-pane fade tab_content1" id="domicilio">
									        	
								        	</div>


								        	<div class="tab-pane fade tab_content2" id="datosTrabajadores">
								        		<div class="embed-responsive embed-responsive-16by9">
												  <iframe class="embed-responsive-item " id="iframedatosTrabajadores" allowfullscreen>
												  </iframe>
												</div>
							                </div>
									        
							       
							       	 		<div class="col-sm-4 col-sm-offset-5">
					                            <button type="button" onclick="regresar('#cuadro2')" class="btn btn-primary waves-effect">Regresar</button>
					                            <input type="submit" value="Guardar" id="send" class="btn btn-success waves-effect save-cliente">
					                    	</div>
					                    </div>	
			                    	</form>
								</div>
			                </div>
			            </div>
			        </div>
			    </div>
				<!-- Cierre del cuadro de registrar -->

		        <!-- Comienzo del cuadro de consultar -->
				<div class="row clearfix ocultar" id="cuadro3">
		            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                <div class="card">
		                    <div class="header">
		                        <h2>Consultar <?php echo $breadcrumbs[0]["nombre_lista_vista"]; ?></h2>
		                    </div>
		                    <div class="body">
			                	<div class="table-responsive">
			                        	<input type="hidden" name="id_membresia_mostrar" id="id_membresia_mostrar">
			                        	<div class="row">
											<div class="col-sm-3">
							        			<img id="imagen_motrar" src="<?php echo base_url();?>assets/cpanel/Usuarios/images/default.png" alt="Tu avatar"/ class="img-responsive ima_error " style="border-radius: 50%;width: 100px;height: 100px;margin: 0 auto; margin-top: 30px;"">
							        		</div>
							        		<div class="col-sm-9">
							        			<div class="col-sm-12">
					                            	<table class="table table-bordered table-striped table-hover" id="tableInmobiliariaRegistrar">
					                            		<thead>
					                            			<tr>
					                            				<th>Membresía</th>
					                            				<th>Id renovación</th>
					                            				<th>Horas contratadas</th>
					                            				<th>Valor</th>
					                            				<th>Inicio</th>
					                            				<th>Vigencia</th>
					                            				<th>Condición</th>
					                            			</tr>
					                            		</thead>
					                            		<tbody>
					                            			<tr>
					                            				<th id="numero_membresiaC">N</th>
					                            				<th id="id_renovacionC">N</th>
					                            				<th id="horas_jornadasC"></th>
					                            				<th id="precio_planC"></th>
					                            				<th id="fecha_inicioC"></th>
					                            				<th id="fecha_finC"></th>
					                            				<th style="">
							                                    	<div class="switch">
																	    <label>
																	      Inac
																	      <input type="checkbox" id="plan_activoC" disabled>
																	      <span class="lever"></span>
																	      Act
																	    </label>
																	</div>
														 			<input type="hidden" name="plan_activo_mostrar" id="plan_activo_mostrar" value="N">
						                            			</th>
					                            			</tr>
					                            		</tbody>
					                            	</table>
					                            	<!-- -->
						                            <div class="col-sm-6 paquetes-membresia" style="    float: right;">
					                            		<label for="paquetes_membresia_mostrar">Paquetes*</label>
				                                    	<select name="paquetes_membresia_mostrar" id="paquetes_membresia_mostrar" required class="form-control" disabled>
				                                    		<option value="">Seleccione</option>
				                                    	</select>
						                            </div>
						                            <!-- -->
					                            	<div class="col-sm-6 planes-membresia" style="    float: right;padding: 0px;">
					                            		<label for="plan_membresia_mostrar">Planes*</label>
				                                    	<select name="plan_membresia_mostrar" id="plan_membresia_mostrar" disabled class="form-control " onchange="consultarPaquetes(this.value,'mostrar');">
				                                    		<option value="">Seleccione</option>
				                                    		<?php foreach ($planes as $plan): ?>
				                                    			<?php //if ($plan['status']==true){ ?> 
				                                    				<option value="<?= $plan["id_planes"]; ?>"><?= $plan["titulo"]." ".$plan["descripcion"];?></option>
				                                    			<?php //} ?> 
				                                    		<?php endforeach ?>
				                                    	</select>
				                                    	<input type="hidden" name="plan_horasC" id="plan_horasC_fecha_inicioC">
				                                    	<input type="hidden" name="plan_fecha_finC" id="plan_fecha_finC">
				                                    	<input type="hidden" name="plan_valorC" id="plan_valorC">
						                            </div>
					                            </div>
							        		</div>
			                        	</div>
			                           
			    						<div class="form-group" id="tipopersona_mostrar">
			    							<label for="tipopersona_mostrar">Tipo Persona*</label> <br>
			    							<input type="radio" checked name="rad_tipoper_mostrar" id="fisica_mostrar"  value="fisica_mostrar" disabled>
			    							<label for= "fisica_mostrar">Física</label>
			    							<input type="radio" name="rad_tipoper_mostrar" id="moral_mostrar"  value="moral_mostrar" disabled>
			    							<label for= "moral_mostrar">Moral</label>
			    							<input type="hidden" name="tipo_persona_mostrar" id="tipo_persona_mostrar">
			    						</div>
								        <ul class="nav nav-tabs">
								        	<li id="tab0" class="active"><a href="#datogeneralC" data-toggle="tab" >Datos Generales*</a></li>
								        	<li id="tab2"><a href="#datosTrabajadoresC" data-toggle="tab" class=" pestana_datosTrabajadoresC">Datos de trabajadores</a></li>
								        	<li id="tabSaldosC"><a href="#saldosC" data-toggle="tab" class="pestanaSaldosC " >Saldos*</a></li>
								        	<li id="tab3"><a href="#cuenta" data-toggle="tab" class="pestanaCuenta hide" >Histórico*</a></li>
								        </ul> 
								        <div class="tab-content">
								        	<div class="tab-pane fade in active tab_content0" id="datogeneralC">
								        	    <!--Actualizar persona física -->
								        	    <div id="personaFisicaC">
								        	    	<div class="col-sm-4">
					                            		<label for="nombre_cliente">Serial de acceso*</label>
						                                <div class="form-group">
						                                    <div class="form-line">
						                                        <input type="text" class="form-control mayusculas" name="serial_acceso_mostrar_fisica" id="serial_acceso_mostrar_fisica" autocomplete="off" onkeypress='return solosnumerosyletras(event)'  maxlength="30" placeholder="P. EJ.123456" disabled>
					                                    	</div>
					                             		</div>
					                           		</div>
					                           		
					                             	<div class="col-sm-4">
					                            		<label for="rfc_cliente_mostrar_fisica">Identificación (Prospecto/CLiente)*</label>
						                                <div class="form-group">
						                                    <div class="form-line">
						                                        <input type="text" class="form-control mayusculas" name="rfc_cliente_mostrar_fisica" autocomplete="off" onkeypress='return solosnumerosyletras(event)' id="rfc_cliente_mostrar_fisica" maxlength="30" placeholder="P. EJ.CONRA19901234" disabled>
					                                    	</div>
					                             		</div>
					                           		</div>
										        	<div class="col-sm-4">
					                            		<label for="nombre_fisica_mostrar">Nombre(s)*</label>
						                                <div class="form-group valid-required">
						                                    <div class="form-line">
						                                        <input type="text" class="form-control mayusculas" name="nombre_fisica_mostrar" autocomplete="off" onkeypress='return sololetras(event)' id="nombre_fisica_mostrar" maxlength="30" placeholder="P. EJ.LUIS RAÚL" disabled>
					                                    	</div>
					                             		</div>
					                           		</div>
					                           		<div class="col-sm-4">
				                            			<label for="apellido_paterno_fisica_mostrar">Apellido Paterno*</label>
					                                	<div class="form-group">
					                                    	<div class="form-line">
					                                        	<input type="text" class="form-control mayusculas" name="apellido_paterno_fisica_mostrar" autocomplete="off" onkeypress='return sololetras(event)' maxlength="15" id="apellido_paterno_fisica_mostrar" placeholder="P. EJ. BELLO" disabled>
					                                    	</div>
					                                	</div>
					                             	</div>
					                             	<div class="col-sm-4">
					                            		<label for="apellido_materno_fisica_mostrar">Apellido Materno*</label>
						                                <div class="form-group">
						                                    <div class="form-line">
						                                        <input type="text" class="form-control mayusculas" name="apellido_materno_fisica_mostrar" autocomplete="off" maxlength="15" onkeypress='return sololetras(event)' id="apellido_materno_fisica_mostrar" placeholder="P. EJ. MENA" disabled>
						                                    </div>
					                                	</div>
				                          			</div>
				                          			<div class="col-sm-4">
								                		<label for="fecha_nac_fisica_mostrar">Fecha de Nacimiento*</label>
								                		<div class="form-group valid-required">
								                   			 <div class="form-line input-group fecha">
								                        		<input type="text" class="form-control " name="fecha_nac_fisica_mostrar" id="fecha_nac_fisica_mostrar" placeholder="dd-mm-yyyy"  disabled>
								                        		<span class="input-group-addon">
									                       			 <span class="glyphicon glyphicon-calendar"></span>
									                   		 	</span>
								                   			 </div>
								                		</div>
								                    </div>
						                           
						                            <div class="col-sm-4 hide" style="margin-bottom: 60px;">
					                            		<label for="genero_membresia_fisica_mostrar">Género*</label>
					                            		<div class="form-group valid-required">
				                                    		<div class="form-line">
				                                    			<input type="text" name="genero_membresia_fisica_mostrar" id="genero_membresia_fisica_mostrar"  class="form-control mayusculas" disabled>
				                                    		</div>
				                                    	</div>		
						                            </div>
						                            <div class="col-sm-4 hide">
					                            		<label for="edo_civil_fisica_mostrar">Estado Civil*</label>
					                            		<div class="form-group valid-required">
				                                    		<div class="form-line">
				                                    			<input type="text" name="edo_civil_fisica_mostrar" id="edo_civil_fisica_mostrar" class="form-control mayusculas" disabled>
				                                    		</div>
				                                    	</div>		
						                            </div>
						                            <div class="col-sm-4 hide">
					                            		<label for="nacionalidad_fisica_mostrar">Nacionalidad*</label>
					                            		<div class="form-group valid-required">
				                                    		<div class="form-line">
				                                    			<input type="text" name="acionalidad_fisica_mostrar" id="nacionalidad_fisica_mostrar" class="form-control mayusculas" disabled>
				                                    		</div>
				                                    	</div>
						                            </div>
						                            <div class="col-sm-4">
						                                <label for="curp_fisica_mostrar">C.U.R.P.*</label>
						                                <div class="form-group form-float">
						                                    <div class="form-line" id="validCurp">
						                                        <input type="text" class="form-control mayusculas" autocomplete="off" name="curp_fisica_mostrar" id="curp_fisica_mostrar" placeholder="P. EJ. BML920313HMLNNSOS" maxlength="18" disabled>
						                                    </div>
					                                	   	<span class="curpError text-danger"></span>
					                               		</div>
					                            	</div>
						                            <!--<div class="col-sm-4 hide">
						                                <label for="pasaporte_fisica_mostrar">Pasaporte</label>
						                                <div class="form-group form-float">
						                                    <div class="form-line" id="validCurp">
						                                        <input type="text" class="form-control mayusculas" autocomplete="off" name="pasaporte_fisica_mostrar" id="pasaporte_fisica_mostrar" placeholder="P. EJ. 76543ER4325" maxlength="18" disabled>
						                                    </div>
					                                	   	<span class="curpError text-danger"></span>
					                               		</div>
					                            	</div>-->
					                            	<div class="col-sm-4">
					                                	<label for="telefono_fisica_mostrar">Teléfono*</label>
						                                <div class="form-group">
						                                    <div class="form-line">
						                                        <input type="text" class="form-control telefono" name="telefono_fisica_mostrar" id="telefono_fisica_mostrar" placeholder="P. EJ.: +00 (000) 000-00-00" disabled>
				                                        		<span class="emailError text-danger"></span>
						                                    </div>
				                               		 	</div>
			                            			</div>
				                            		<div class="col-sm-4">
						                                <label for="correo_fisica_mostrar">Correo Electrónico*</label>
						                                <div class="form-group valid-required">
					                                    	<div class="form-line">
					                                        <input type="email" class="form-control" autocomplete="off" name="correo_fisica_mostrar" id="correo_fisica_mostrar" placeholder="P. EJ. ejemplo@dominio.com" disabled>
				                                        	<span class="emailError text-danger"></span>
				                                    		</div>
			                                			</div>
			                            			</div>
			                            			<div class="col-sm-4">
				                            	   		<label for="actividad_economica_fisica_mostrar">Actividad Economica*</label>
				                            	   		<div class="form-group valid-required">
				                                    		<div class="form-line">
					                               				<input type="text" name="actividad_economica_fisica_mostrar" id="actividad_economica_fisica_mostrar" class="form-control mayusculas" disabled>
					                               			</div>
					                               		</div>		
				                            		</div>
				                            		<!-- ==============Inicio de direccion=================-->

				                            		<div class="col-lg-12">
				                            			<h3>Dirección</h3>
				                            			<hr>
				                            		</div>
				                            		<div class="col-sm-4">
						                                <label for="calle_fisica_mostrar">Calle*</label>
						                                <div class="form-group valid-required">
						                                    <div class="form-line">
						                                        <input type="text" class="form-control mayusculas" name="calle_fisica_mostrar" id="calle_fisica_mostrar" placeholder="P. EJ. PRIMAVERA" disabled>
						                                    </div>
						                                </div>
						                            </div>
						                            <div class="col-sm-4">
						                                <label for="exterior_fisica_mostrar">Número Exterior*</label>
						                                <div class="form-group valid-required">
						                                    <div class="form-line">
						                                        <input type="text" class="form-control mayusculas" name="exterior_fisica_mostrar" maxlength="30" onkeypress='return solosnumerosyletras(event)' id="exterior_fisica_mostrar" placeholder="P. EJ. 33" maxlength="10" disabled>
						                                    </div>
						                                </div>
						                            </div>
						                            <div class="col-sm-4">
						                                <label for="numero_interior_fisica_mostrar">Número Interior</label>
						                                <div class="form-group">
						                                    <div class="form-line">
						                                        <input type="text" class="form-control mayusculas interior_contacto_registrar_fisica"  name="numero_interior_fisica_mostrar" id="numero_interior_fisica_mostrar" placeholder="P. EJ. 2" maxlength="10" disabled>
						                                    </div>
						                                </div>
						                            </div>
						                            <div class="col-sm-4">
						                                <label for="codigo_postal_fisica_mostrar">Código Postal*</label>
						                                <div class="form-group valid-required">
						                                    <div class="form-line">
						                                        <input type="text" class="form-control" id="codigo_postal_fisica_mostrar" onkeypress='return codigoPostal(event)' name="codigo_postal_fisica_mostrar" maxlength="5" disabled>
						                                    </div>
						                                </div>
						                            </div>
						                            <div class="col-sm-4">
						                            	<label for="colonia_fisica_mostrar">Colonia*</label>
						                            	<div class="form-group valid-required">
				                                    		<div class="form-line">
						                                		<input type="text" id="colonia_fisica_mostrar" class="form-control mayusculas" name="colonia_fisica_mostrar" disabled>
						                                	</div>	
							                            </div>
						                            </div>
						                            <div class="col-sm-4"  style="padding-bottom: 10px;">
						                                <label for="municipio_fisica_mostrar">Municipio*</label>
						                                <div class="form-group valid-required">
				                                    		<div class="form-line">
							                                	<input type="text" id="municipio_fisica_mostrar" class="form-control mayusculas" name="municipio_fisica_mostrar" disabled>
							                                </div>	
							                            </div>
						                            </div>
						                            <div class="col-sm-4">
						                                <label for="ciudad_fisica_mostrar">Ciudad*</label>
						                                <div class="form-group valid-required">
				                                    		<div class="form-line">
							                                	<input type="text" id="ciudad_fisica_mostrar" class="form-control mayusculas" name="ciudad_fisica_mostrar" disabled>
							                                </div>	
							                            </div>
						                            </div>
						                            <div class="col-sm-4">
						                                <label for="estado_fisica_mostrar">Estado*</label>
						                                <div class="form-group valid-required">
				                                    		<div class="form-line">
							                                	<input type="text" id="estado_fisica_mostrar" class="form-control mayusculas" name="estado_fisica_mostrar" disabled>
							                                </div>	
							                            </div>
						                            </div>
				                           			<!-- ===============================-->

										        </div>
									        	<!--Fin de Actualizar persona física -->
									        	<div id="personaMoralC" style="display: none;">
									        		<div class="col-sm-4">
					                            		<label for="serial_acceso_mostrar_moral">Serial de acceso*</label>
						                                <div class="form-group">
						                                    <div class="form-line">
						                                        <input type="text" class="form-control mayusculas" name="serial_acceso_mostrar_moral" autocomplete="off" onkeypress='return solosnumerosyletras(event)' id="serial_acceso_mostrar_moral" maxlength="30" placeholder="P. EJ.123456" disabled>
					                                    	</div>
					                             		</div>
					                           		</div>
					                           		<div class="col-sm-4">
					                            		<label for="rfc_cliente_mostrar_moral">Identificación (Prospecto/CLiente)*</label>
						                                <div class="form-group">
						                                    <div class="form-line">
						                                        <input type="text" class="form-control mayusculas" name="rfc_cliente_mostrar_moral" autocomplete="off" onkeypress='return solosnumerosyletras(event)' id="rfc_cliente_mostrar_moral" maxlength="30" placeholder="P. EJ.CONRA19901234" disabled>
					                                    	</div>
					                             		</div>
					                           		</div>
										        		<div class="col-sm-4">
						                            		<label for="razon_social_mostrar">Denominación o Razón Social*</label>
							                                <div class="form-group">
							                                    <div class="form-line">
							                                        <input type="text" class="form-control mayusculas" name="razon_social_mostrar" id="razon_social_mostrar" maxlength="30" placeholder="P. EJ.AG SITEMAS" disabled="">
						                                    	</div>
						                             		</div>
						                           		</div>
						                           	
							                            <div class="col-sm-4 hide" style="margin-bottom: 50px;">
						                            		<label for="genero_membresia_mostrar_moral">Género*</label>
						                            		<div class="form-group valid-required">
				                                    			<div class="form-line">
					                                    			<input type="text" name="genero_membresia_mostrar_moral" id="genero_membresia_mostrar_moral" class="form-control mayusculas" disabled>
					                                    		</div>
					                                    	</div>		
							                            </div>
							                            <div class="col-sm-4 hide" style="margin-bottom: 50px;">
						                            		<label for="edo_civil_membresia_mostrar_moral">Estado Civil*</label>
						                            		<div class="form-group valid-required">
				                                    			<div class="form-line">
					                                    			<input type="text" name="edo_civil_membresia_mostrar_moral" id="edo_civil_membresia_mostrar_moral" class="form-control mayusculas" disabled>
					                                    		</div>
					                                    	</div>		
							                            </div>
							                            <div class="col-sm-4 hide">
						                            		<label for="nacionalidad_membresia_moral_mostrar">Nacionalidad*</label>
						                            		<div class="form-group">
				                                    			<div class="form-line">
					                                    			<input name="nacionalidad_membresia_moral_mostrar" id="nacionalidad_membresia_moral_mostrar" class="form-control mayusculas" disabled>
					                                    		</div>
					                                    	</div>		
							                            </div>
							                            <div class="col-sm-4" style="margin-bottom: 10px;">
						                            		<label for="fecha_nac_moral_mostrar">Fecha Nacimiento*</label>
					                                		<div class="form-group">
					                                   			 <div class="form-line input-group fecha">
					                                        		<input type="text" class="form-control" name="fecha_nac_moral_mostrar" id="fecha_nac_moral_mostrar" placeholder="dd-mm-yyyy" disabled>
					                                        		<span class="input-group-addon">
										                       			 <span class="glyphicon glyphicon-calendar"></span>
										                   		 	</span>
					                                   			 </div>
					                                		</div>
							                            </div>
							                            <!--<div class="col-sm-4 hide">
							                                <label for="pasaporte_moral_mostrar">Pasaporte</label>
							                                <div class="form-group form-float">
							                                    <div class="form-line" id="validCurp">
							                                        <input type="text" class="form-control mayusculas" autocomplete="off" name="pasaporte_moral_mostrar" id="pasaporte_moral_mostrar" placeholder="P. EJ. 76543ER4325" maxlength="18" onkeypress='return solosnumerosyletras(event)' disabled>
							                                    </div>
						                                	   	<span class="curpError text-danger"></span>
						                               		</div>
						                            	</div>-->
							                           <div class="col-sm-4">
					                            			<label for="correo_moral_mostrar">Correo Electrónico*</label>
							                                <div class="form-group">
						                                    	<div class="form-line">
						                                        <input type="email" class="form-control mayusculas" name="correo_moral_mostrar" id="correo_moral_mostrar" placeholder="P. EJ. ejemplo@dominio.com" onchange="validEmail(this)" readonly="readonly">

					                                        	<span class="emailError text-danger"></span>
					                                    		</div>
				                                			</div>
					                            		</div>
							                            <div class="col-sm-4">
						                            		<label for="telefono_moral_mostrar">Teléfono*</label>
							                                <div class="form-group">
							                                    <div class="form-line">
							                                        <input type="text" class="form-control telefono" name="telefono_moral_mostrar" id="telefono_moral_mostrar" placeholder="P. EJ.: +00 (000) 000-00-00" onkeyup="validPhone(this)" disabled>
					                                        		<span class="emailError text-danger"></span>
							                                    </div>
					                               		 	</div>
				                            			</div>
							                            <div class="col-sm-4">
					                                		<label for="giro_mercantil_moral_mostrar">Giro Mercantil*</label>
					                                		<div class="form-group valid-required">
				                                    			<div class="form-line">
						                               				<input type="text" name="giro_mercantil_moral_mostrar" id="giro_mercantil_moral_mostrar" class="form-control" disabled >
						                               			</div>
						                               		</div>		
				                            			</div>
				                            			
					                            		<!-- ==============Inicio de direccion=================-->

					                            		<div class="col-lg-12">
					                            			<h3>Dirección</h3>
					                            			<hr>
					                            		</div>
					                            		<div class="col-sm-4">
							                                <label for="calle_contacto_moral_mostrar">Calle*</label>
							                                <div class="form-group valid-required">
							                                    <div class="form-line">
							                                        <input type="text" class="form-control mayusculas" name="calle_contacto_moral_mostrar" id="calle_contacto_moral_mostrar" placeholder="P. EJ. PRIMAVERA" disabled>
							                                    </div>
							                                </div>
							                            </div>
							                            <div class="col-sm-4">
							                                <label for="exterior_moral_mostrar">Número Exterior*</label>
							                                <div class="form-group valid-required">
							                                    <div class="form-line">
							                                        <input type="text" class="form-control mayusculas" name="exterior_moral_mostrar" maxlength="30" onkeypress='return solosnumerosyletras(event)' id="exterior_moral_mostrar" placeholder="P. EJ. 33" maxlength="10" disabled>
							                                    </div>
							                                </div>
							                            </div>
							                            <div class="col-sm-4">
							                                <label for="interior_moral_mostrar">Número Interior</label>
							                                <div class="form-group">
							                                    <div class="form-line">
							                                        <input type="text" class="form-control mayusculas numero_exterior" onkeypress='return solosnumerosyletras(event)' name="interior_moral_mostrar" id="interior_moral_mostrar" placeholder="P. EJ. 2" maxlength="10" disabled>
							                                    </div>
							                                </div>
							                            </div>
										                <div class="col-sm-4">
							                                <label for="codigo_postal_moral_mostrar">Código Postal*</label>
							                                <div class="form-group">
							                                    <div class="form-line">
							                                        <input type="text" class="form-control mayusculas" id="codigo_postal_moral_mostrar" onkeypress='return codigoPostal(event)' name="codigo_postal_moral_mostrar" maxlength="5" onchange="buscarCodigosUs(this.value, 'create')" disabled>
							                                    </div>
							                            	</div>
							                            </div>
							                            <div class="col-sm-4">
							                            	<label for="colonia_mostrar_moral">Colonia*</label>
							                            	<div class="form-group valid-required">
				                                    			<div class="form-line">
							                                		<input type="text" id="colonia_mostrar_moral" class="form-control form-group mayusculas" name="colonia_mostrar_moral" disabled>
							                                	</div>	
								                            </div>
							                            </div>
							                            <div class="col-sm-4"  style="padding-bottom: 10px;">
							                                <label for="municipio_moral_mostrar">Municipio*</label>
							                                <div class="form-group valid-required">
				                                    			<div class="form-line">
								                               		<input type="text" id="municipio_moral_mostrar" class="form-control form-group mayusculas" name="municipio_moral_mostrar" disabled>
								                               	</div>	
								                            </div>
							                            </div>
							                            <div class="col-sm-4">
							                                <label for="ciudad_moral_mostrar">Ciudad*</label>
							                                <div class="form-group valid-required">
				                                    			<div class="form-line">
								                                	<input type="text" id="ciudad_moral_mostrar" class="form-control form-group mayusculas" name="ciudad_moral_mostrar" disabled>
								                                </div>	
								                            </div>
							                            </div>
							                            <div class="col-sm-4">
							                                <label for="estado_moral_mostrar">Estado*</label>
							                                <div class="form-group valid-required">
				                                    			<div class="form-line">
								                                	<input type="text" id="estado_moral_mostrar" class="form-control form-group" name="estado_moral_mostrar" disabled>
								                                </div>	
								                            </div>
							                            </div>
					                           			<!-- ===============================-->
							                    </div>
									        </div>
								        
								        	<div class="tab-pane fade tab_content2 " id="datosTrabajadoresC">
								        		<div class="embed-responsive embed-responsive-16by9">
												  <iframe class="embed-responsive-item " id="iframedatosTrabajadoresC" allowfullscreen>
												  </iframe>
												</div>
							                </div>
									        <div class="tab-pane fade tab_content3 " id="saldosC">
								        		<div class="embed-responsive embed-responsive-16by9">
								        			<div id="mensajesSaldosC"></div>      			
								        		 	<iframe class="embed-responsive-item " id="iframedatosSaldosC" allowfullscreen>
												  	</iframe>
												</div>
							                </div>
							       
							       	 		<div class="col-sm-4 col-sm-offset-5">
					                            <button type="button" onclick="regresar('#cuadro3')" class="btn btn-primary waves-effect">Regresar</button>

					                    	</div>
					                    </div>	
			                    	
								</div>
			        		</div>
		                </div>
		            </div>
		        </div>
				<!-- Cierre del cuadro de consultar -->

		    	<!-- Comienzo del cuadro de editar -->
				<div class="row clearfix ocultar" id="cuadro4">
		            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                <div class="card">
					        <div class="header">
					            <h2>Editar de <?php echo $breadcrumbs[0]["nombre_lista_vista"]; ?></h2>
					        </div>
					        <div class="body">
			                	<div class="table-responsive">
			                        <form name="form_membresia_actualizar" id="form_membresia_actualizar" method="post" enctype="multipart/form-data">
			                        	<input type="hidden" name="id_membresia_actualizar" id="id_membresia_actualizar">
			                        	<div class="row">
											<div class="col-sm-3">
							        			<img id="imagen_editar" src="<?php echo base_url();?>assets/cpanel/Usuarios/images/default.png" alt="Tu avatar"/ class="img-responsive ima_error " style="border-radius: 50%;width: 100px;height: 100px;margin: 0 auto; margin-top: 30px;">
							        		</div>
							        		<div class="col-sm-9">
							        			<div class="col-sm-12">
					                            	<table class="table table-bordered table-striped table-hover" id="tableInmobiliariaRegistrar">
					                            		<thead>
					                            			<tr>
					                            				<th>Membresía</th>
					                            				<th>Id renovación</th>
					                            				<th>Horas contratadas</th>
					                            				<th>Valor</th>
					                            				<th>Inicio</th>
					                            				<th>Vigencia</th>
					                            				<th>Condición</th>
					                            			</tr>
					                            		</thead>
					                            		<tbody>
					                            			<tr>
					                            				<th id="numero_membresiaE">N</th>
					                            				<th id="id_renovacionE">N</th>
					                            				<th id="horas_jornadasE"></th>
					                            				<th id="precio_planE"></th>
					                            				<th id="fecha_inicioE"></th>
					                            				<th id="fecha_finE"></th>
					                            				<th style="">
							                                    	<div class="switch">
																	    <label>
																	      Inac
																	      <input type="checkbox" id="plan_activoE" disabled="">
																	      <span class="lever"></span>
																	      Act
																	    </label>
																	</div>
														 			<input type="hidden" name="plan_activo_modificar" id="plan_activo_modificar" value="N">
						                            			</th>
					                            			</tr>
					                            		</tbody>
					                            	</table>
					                            	<!-- -->
						                            <div class="col-sm-6 paquetes-membresia" style="    float: right;">
					                            		<label for="paquetes_membresia_actualizar">Paquetes*</label>
				                                    	<select name="paquetes_membresia_actualizar" id="paquetes_membresia_actualizar" disabled class="form-control">
				                                    		<option value="">Seleccione</option>
				                                    	</select>
						                            </div>
						                            <!-- -->
					                            	<div class="col-sm-6 planes-membresia" style="    float: right;padding: 0px;">
					                            		<label for="plan_membresia_actualizar">Planes*</label>
				                                    	<select name="plan_membresia_actualizar" id="plan_membresia_actualizar" required class="form-control "  onchange="consultarPaquetes(this.value,'guardar');" disabled>
				                                    		<option value="">Seleccione</option>
				                                    		<?php foreach ($planes as $plan): ?>
				                                    			<?php //if ($plan['status']==true){ ?> 
				                                    				<option value="<?= $plan["id_planes"]; ?>" status="<?=$plan['status'] ?>"><?= $plan["titulo"]." ".$plan["descripcion"]; ?></option>
				                                    			<?php //} ?> 
				                                    		<?php endforeach ?>
				                                    	</select>
				                                    	<input type="hidden" name="plan_horasE" id="plan_horasE">
				                                    	<input type="hidden" name="plan_fecha_inicioE" id="plan_fecha_inicioE">
				                                    	<input type="hidden" name="plan_fecha_finE" id="plan_fecha_finE">
				                                    	<input type="hidden" name="plan_valorE" id="plan_valorE">
						                            </div>
					                            </div>
							        		</div>
			                        	</div>
			                           	<div class="form-group" id="tipopersona_editar">
			    							<label for="tipopersona_editar">Tipo Persona*</label> <br>
			    							<input type="radio" checked name="rad_tipoper_editar" id="fisica_actualizar"  value="fisica_actualizar" disabled>
			    							<label for= "fisica_actualizar">Física</label>
			    							<input type="radio" name="rad_tipoper_editar" id="moral_actualizar"  value="moral_actualizar" disabled>
			    							<label for= "moral_actualizar">Moral</label>
			    							<input type="hidden" name="tipo_persona_actualizar" id="tipo_persona_actualizar">
			    						</div>

								        <ul class="nav nav-tabs">
								        	<li id="tab0" class="active"><a href="#datogeneralE" data-toggle="tab" >Datos Generales*</a></li>
								        	<li id="tab2"><a href="#datosTrabajadoresE" data-toggle="tab" class=" pestana_datosTrabajadoresE">Datos de trabajadores</a></li>
								        	<li id="tabSaldosE"><a href="#saldosE" data-toggle="tab" class="pestanaDomicilio " >Saldos*</a></li>
								        	<li id="tabSRenovacionE"><a href="#renovacionE" data-toggle="tab" class="pestanaRenovaciones " >Renovación*</a></li>
								        	<li id="tab3"><a href="#cuenta" data-toggle="tab" class="pestanaCuenta hide" >Histórico*</a></li>
								        </ul> 
								        <div class="tab-content">
								        	<div class="tab-pane fade in active tab_content0" id="datogeneralE">
								        	    <!--Actualizar persona física -->
								        	    <div id="personaFisicaE">
								        	    	<div class="col-sm-4">
					                            		<label for="nombre_cliente">Serial de acceso*</label>
						                                <div class="form-group valid-required">
						                                    <div class="form-line">
						                                        <input type="text" class="fisicaf form-control mayusculas" name="serial_acceso_actualizar_fisica" id="serial_acceso_actualizar_fisica" autocomplete="off" onkeypress='return solosnumerosyletras(event)'  maxlength="30" placeholder="P. EJ.123456" >
					                                    	</div>
					                             		</div>
					                           		</div>
					                           		
					                             	<div class="col-sm-4">
					                            		<label for="rfc_cliente_actualizar_fisica">Identificación (Prospecto/CLiente)*</label>
						                                <div class="form-group valid-required">
						                                    <div class="form-line">
						                                        <input type="text" class="fisicaf form-control mayusculas" readonly name="rfc_cliente_actualizar_fisica" autocomplete="off" onkeypress='return solosnumerosyletras(event)' id="rfc_cliente_actualizar_fisica" maxlength="30" placeholder="P. EJ.CONRA19901234" readonly="readonly" onchange="consultarCliente()">
					                                    	</div>
					                             		</div>
					                           		</div>
										        	<div class="col-sm-4">
					                            		<label for="nombre_fisica_actualizar">Nombre(s)*</label>
						                                <div class="form-group valid-required">
						                                    <div class="form-line">
						                                        <input type="text" class="fisicaf form-control mayusculas" readonly name="nombre_fisica_actualizar" autocomplete="off" onkeypress='return sololetras(event)' id="nombre_fisica_actualizar" maxlength="30" placeholder="P. EJ.LUIS RAÚL" readonly="readonly" >
					                                    	</div>
					                             		</div>
					                           		</div>
					                           		<div class="col-sm-4">
				                            			<label for="apellido_paterno_fisica_actualizar">Apellido Paterno*</label>
					                                	<div class="form-group valid-required">
					                                    	<div class="form-line">
					                                        	<input type="text" class="fisicaf form-control mayusculas" readonly name="apellido_paterno_fisica_actualizar" autocomplete="off" onkeypress='return sololetras(event)' maxlength="15" id="apellido_paterno_fisica_actualizar" placeholder="P. EJ. BELLO" readonly="readonly">
					                                    	</div>
					                                	</div>
					                             	</div>
					                             	<div class="col-sm-4">
					                            		<label for="apellido_materno_fisica_actualizar">Apellido Materno*</label>
						                                <div class="form-group valid-required">
						                                    <div class="form-line">
						                                        <input type="text" class="fisicaf form-control mayusculas" readonly name="apellido_materno_fisica_actualizar" autocomplete="off" maxlength="15" onkeypress='return sololetras(event)' id="apellido_materno_fisica_actualizar" placeholder="P. EJ. MENA" readonly="readonly">
						                                    </div>
					                                	</div>
				                          			</div>
				                          			<div class="col-sm-4">
								                		<label for="fecha_nac_fisica_actualizar">Fecha de Nacimiento*</label>
								                		<div class="form-group valid-required">
								                   			 <div class="form-line input-group fecha">
								                        		<input type="text" class="form-control " readonly name="fecha_nac_fisica_actualizar" id="fecha_nac_fisica_actualizar" placeholder="dd-mm-yyyy"  readonly="readonly">
								                        		<span class="input-group-addon">
									                       			 <span class="glyphicon glyphicon-calendar"></span>
									                   		 	</span>
								                   			 </div>
								                		</div>
								                    </div>
						                           
						                            <div class="col-sm-4 hide" style="margin-bottom: 60px;">
					                            		<label for="genero_membresia_fisica_actualizar">Género*</label>
				                                    	<select name="genero_membresia_fisica_actualizar" readonly id="genero_membresia_fisica_actualizar"  class="form-control" disabled>
				                                    		<option value="" selected>Seleccione</option>
				                                    		<?php foreach ($sexos as $sexo): ?>
				                                    			<option value="<?=$sexo->id_lista_valor;?>"><?=$sexo->nombre_lista_valor;?></option>
				                                    		<?php endforeach ?>
				                                    	</select>
						                            </div>
						                            <div class="col-sm-4 hide">
					                            		<label for="edo_civil_fisica_actualizar">Estado Civil*</label>
				                                    	<select name="edo_civil_fisica_actualizar" readonly id="edo_civil_fisica_actualizar" class="form-control" disabled>
				                                    		<option value="" selected>Seleccione</option>
				                                    		<?php foreach ($estadosCiviles as $estadoCivil): ?>
				                                    			<option value="<?=$estadoCivil->id_lista_valor;?>"><?=$estadoCivil->nombre_lista_valor;?></option>
				                                    		<?php endforeach ?>
				                                    	</select>
						                            </div>
						                            <div class="col-sm-4 hide">
					                            		<label for="nacionalidad_fisica_actualizar">Nacionalidad*</label>
				                                    	<select name="nacionalidad_fisica_actualizar" readonly id="nacionalidad_fisica_actualizar" class="form-control" disabled>
				                                    		<option value="" selected>Seleccione</option>
				                                    		<?php foreach ($nacionalidades as $nacionalidad): ?>
				                                    			<option value="<?=$nacionalidad->id_lista_valor;?>"><?=$nacionalidad->nombre_lista_valor;?></option>
				                                    		<?php endforeach ?>
				                                    	</select>
						                            </div>
						                            <div class="col-sm-4">
						                                <label for="curp_fisica_actualizar">C.U.R.P.*</label>
						                                <div class="form-group form-float">
						                                    <div class="form-line" id="validCurp">
						                                        <input type="text" class="fisicaf form-control mayusculas" readonly autocomplete="off" name="curp_fisica_actualizar" id="curp_fisica_actualizar" placeholder="P. EJ. BML920313HMLNNSOS" maxlength="18" onkeypress='return solosnumerosyletras(event)' oninput="validarInputCurp(this)" >
						                                    </div>
					                                	   	<span class="curpError text-danger"></span>
					                               		</div>
					                            	</div>
						                            <!--<div class="col-sm-4 hide">
						                                <label for="pasaporte_fisica_actualizar">Pasaporte</label>
						                                <div class="form-group form-float">
						                                    <div class="form-line" id="validCurp">
						                                        <input type="text" class="fisicaf form-control mayusculas" autocomplete="off" name="pasaporte_fisica_actualizar" id="pasaporte_fisica_actualizar" placeholder="P. EJ. 76543ER4325" maxlength="18" onkeypress='return solosnumerosyletras(event)'  oninput="validarInputCurp(this)"  readonly="readonly">
						                                    </div>
					                                	   	<span class="curpError text-danger"></span>
					                               		</div>
					                            	</div>-->
					                            	<div class="col-sm-4">
					                                	<label for="telefono_fisica_actualizar">Teléfono*</label>
						                                <div class="form-group valid-required">
						                                    <div class="form-line">
						                                        <input type="text" class="form-control telefono fisicaf" readonly name="telefono_fisica_actualizar" id="telefono_fisica_actualizar" placeholder="P. EJ.: +00 (000) 000-00-00" onkeyup="validPhone(this)"  readonly="readonly">
				                                        		<span class="emailError text-danger"></span>
						                                    </div>
				                               		 	</div>
			                            			</div>
				                            		<div class="col-sm-4">
						                                <label for="correo_fisica_actualizar">Correo Electrónico*</label>
						                                <div class="form-group valid-required">
					                                    	<div class="form-line">
					                                        <input type="email" class="form-control fisicaf" autocomplete="off"readonly name="correo_fisica_actualizar" id="correo_fisica_actualizar" placeholder="P. EJ. ejemplo@dominio.com" onchange="validEmail(this)" readonly="readonly">
				                                        	<span class="emailError text-danger"></span>
				                                    		</div>
			                                			</div>
			                            			</div>
			                            			<div class="col-sm-4">
				                            	   		<label for="actividad_economica">Actividad Economica*</label>
					                               		<select name="actividad_economica_fisica_actualizar"readonly  id="actividad_economica_fisica_actualizar" class="fisicaf form-control" disabled>
			                            					<option value="" selected>Seleccione</option>
			                            						<?php foreach ($actividadesEconomicas as $actividadEconomica): ?>
			                            					<option value="<?=$actividadEconomica->id_lista_valor;?>"><?=$actividadEconomica->nombre_lista_valor;?></option>
			                            						<?php endforeach ?>
			                            				</select>
				                            		</div>
				                            		<!-- ==============Inicio de direccion=================-->

				                            		<div class="col-lg-12">
				                            			<h3>Dirección</h3>
				                            			<hr>
				                            		</div>
				                            		<div class="col-sm-4">
						                                <label for="calle_contacto_actualizar">Calle*</label>
						                                <div class="form-group valid-required">
						                                    <div class="form-line">
						                                        <input type="text" class="form-control mayusculas" readonly name="calle_fisica_actualizar" id="calle_fisica_actualizar" placeholder="P. EJ. PRIMAVERA" readonly="readonly">
						                                    </div>
						                                </div>
						                            </div>
						                            <div class="col-sm-4">
						                                <label for="exterior_contacto_registrar">Número Exterior*</label>
						                                <div class="form-group valid-required">
						                                    <div class="form-line">
						                                        <input type="text" class="form-control mayusculas" readonly name="exterior_fisica_actualizar" maxlength="30" onkeypress='return solosnumerosyletras(event)' id="exterior_fisica_actualizar" placeholder="P. EJ. 33" maxlength="10" readonly="readonly">
						                                    </div>
						                                </div>
						                            </div>
						                            <div class="col-sm-4">
						                                <label for="interior_contacto_registrar">Número Interior</label>
						                                <div class="form-group">
						                                    <div class="form-line">
						                                        <input type="text" class="form-control mayusculas interior_contacto_registrar_fisica" readonly onkeypress='return solosnumerosyletras(event)' name="numero_interior_fisica_actualizar" id="numero_interior_fisica_actualizar" placeholder="P. EJ. 2" maxlength="10" readonly="readonly">
						                                    </div>
						                                </div>
						                            </div>
						                            <div class="col-sm-4">
						                                <label for="codigo_postal_registrar">Código Postal*</label>
						                                <div class="form-group valid-required">
						                                    <div class="form-line">
						                                        <input type="text" class="form-control" id="codigo_postal_fisica_actualizar" readonly onkeypress='return codigoPostal(event)' name="codigo_postal_fisica_actualizar" maxlength="5" onchange="buscarCodigosUs(this.value, 'create')" readonly="readonly">
						                                    </div>
						                                </div>
						                            </div>
						                            <div class="col-sm-4">
						                            	<label for="colonia_actualizar">Colonia*</label>
						                            	<div class="valid-required">
						                                <select id="colonia_fisica_actualizar" class="form-control form-group" readonly name="colonia_fisica_actualizar" disabled>
							                                	<option value="">Seleccione</option>
							                                </select>
							                            </div>
						                            </div>
						                            <div class="col-sm-4"  style="padding-bottom: 10px;">
						                                <label for="municipio_registrar">Municipio*</label>
						                                <div class="valid-required">
							                                <select id="municipio_fisica_actualizar" readonly class="form-control form-group" name="municipio_fisica_actualizar" disabled>
							                                	<option value="">Seleccione</option>
							                                </select>
							                            </div>
						                            </div>
						                            <div class="col-sm-4">
						                                <label for="ciudad_registrar">Ciudad*</label>
						                                <div class="valid-required">
							                                <select id="ciudad_fisica_actualizar" readonly class="form-control form-group" name="ciudad_fisica_actualizar" disabled>
							                                	<option value="">Seleccione</option>
							                                </select>
							                            </div>
						                            </div>
						                            <div class="col-sm-4">
						                                <label for="estado_registrar">Estado*</label>
						                                <div class="valid-required">
							                                <select id="estado_fisica_actualizar" readonly class="form-control form-group" name="estado_fisica_actualizar" disabled>
							                                	<option value="">Seleccione</option>
							                                </select>
							                            </div>
						                            </div>
				                           			<!-- ===============================-->

										        </div>
									        	<!--Fin de Actualizar persona física -->
									        	<div id="personaMoralE" style="display: none;">
									        		<div class="col-sm-4">
					                            		<label for="serial_acceso_actualizar_moral">Serial de acceso*</label>
						                                <div class="form-group valid-required">
						                                    <div class="form-line">
						                                        <input type="text" class="morale form-control mayusculas" name="serial_acceso_actualizar_moral" autocomplete="off" onkeypress='return solosnumerosyletras(event)' id="serial_acceso_actualizar_moral" maxlength="30" placeholder="P. EJ.123456">
					                                    	</div>
					                             		</div>
					                           		</div>
					                           		<div class="col-sm-4">
					                            		<label for="identificacion_prospecto">Identificación (Prospecto/CLiente)*</label>
						                                <div class="form-group valid-required">
						                                    <div class="form-line">
						                                        <input type="text" class="morale form-control mayusculas" readonly name="rfc_cliente_actualizar_moral" autocomplete="off" onkeypress='return solosnumerosyletras(event)' id="rfc_cliente_actualizar_moral" maxlength="30" placeholder="P. EJ.CONRA19901234" readonly="readonly" onchange="consultarCliente()">
					                                    	</div>
					                             		</div>
					                           		</div>
										        		<div class="col-sm-4">
						                            		<label for="razon_social">Denominación o Razón Social*</label>
							                                <div class="form-group valid-required">
							                                    <div class="form-line">
							                                        <input type="text" class="morale form-control mayusculas" readonly name="razon_social_actualizar" id="razon_social_moral_actualizar" maxlength="30" placeholder="P. EJ.AG SITEMAS" readonly>
						                                    	</div>
						                             		</div>
						                           		</div>
						                           	
							                            <div class="col-sm-4 hide" style="margin-bottom: 50px;">
						                            		<label for="genero_actualizar">Género*</label>
					                                    	<select name="genero_membresia_actualizar_moral" readonly id="genero_membresia_actualizar_moral" class="form-control  morale" disabled>
					                                    		<option value="" selected>Seleccione</option>
					                                    		<?php foreach ($sexos as $sexo): ?>
					                                    			<option value="<?=$sexo->id_lista_valor;?>"><?=$sexo->nombre_lista_valor;?></option>
					                                    		<?php endforeach ?>
					                                    	</select>
							                            </div>
							                            <div class="col-sm-4 hide" style="margin-bottom: 50px;">
						                            		<label for="edo_civil_membresia_actualizar">Estado Civil*</label>
					                                    	<select name="edo_civil_membresia_actualizar_moral" readonly id="edo_civil_membresia_actualizar_moral" class="form-control morale" disabled>
					                                    		<option value="" selected>Seleccione</option>
					                                    		<?php foreach ($estadosCiviles as $estadoCivil): ?>
					                                    			<option value="<?=$estadoCivil->id_lista_valor;?>"><?=$estadoCivil->nombre_lista_valor;?></option>
					                                    		<?php endforeach ?>
					                                    	</select>
							                            </div>
							                            <div class="col-sm-4 hide">
						                            		<label for="nacionalidad_membresia_actualizar">Nacionalidad*</label>
					                                    	<select name="nacionalidad_membresia_moral_actualizar" readonly id="nacionalidad_membresia_moral_actualizar" class="form-control morale"  style="margin-bottom: 50px;" disabled>
					                                    		<option value="" selected>Seleccione</option>
					                                    		<?php foreach ($nacionalidades as $nacionalidad): ?>
					                                    			<option value="<?=$nacionalidad->id_lista_valor;?>"><?=$nacionalidad->nombre_lista_valor;?></option>
					                                    		<?php endforeach ?>
					                                    	</select>
							                            </div>
							                            <div class="col-sm-4" style="margin-bottom: 10px;">
						                            		<label for="fecha_nac_moral_actualizar">Fecha Nacimiento*</label>
					                                		<div class="form-group valid-required">
					                                   			 <div class="form-line input-group fecha">
					                                        		<input type="text" class="morale form-control" readonly name="fecha_nac_moral_actualizar" id="fecha_nac_moral_actualizar" placeholder="dd-mm-yyyy" readonly="readonly">
					                                        		<span class="input-group-addon">
										                       			 <span class="glyphicon glyphicon-calendar"></span>
										                   		 	</span>
					                                   			 </div>
					                                		</div>
							                            </div>
							                            <!--<div class="col-sm-4 hide">
							                                <label for="curp_membresia_actualizar">Pasaporte</label>
							                                <div class="form-group form-float">
							                                    <div class="form-line" id="validCurp">
							                                        <input type="text" class="morale form-control mayusculas" autocomplete="off" name="pasaporte_moral_actualizar" id="pasaporte_moral_actualizar" placeholder="P. EJ. 76543ER4325" maxlength="18" onkeypress='return solosnumerosyletras(event)' readonly="readonly">
							                                    </div>
						                                	   	<span class="curpError text-danger"></span>
						                               		</div>
						                            	</div>-->
							                           <div class="col-sm-4">
					                            			<label for="correo_moral_m">Correo Electrónico*</label>
							                                <div class="form-group valid-required">
						                                    	<div class="form-line">
						                                        <input type="email" class="form-control morale" readonly name="correo_moral_actualizar" id="correo_moral_actualizar" placeholder="P. EJ. ejemplo@dominio.com" onchange="validEmail(this)" readonly="readonly">

					                                        	<span class="emailError text-danger"></span>
					                                    		</div>
				                                			</div>
					                            		</div>
							                            <div class="col-sm-4">
						                            		<label for="telefono_moral_m">Teléfono*</label>
							                                <div class="form-group valid-required">
							                                    <div class="form-line">
							                                        <input type="text" class="morale form-control telefono" readonly ame="telefono_moral_actualizar" id="telefono_moral_actualizar" placeholder="P. EJ.: +00 (000) 000-00-00" onkeyup="validPhone(this)" readonly="readonly">
					                                        		<span class="emailError text-danger"></span>
							                                    </div>
					                               		 	</div>
				                            			</div>
							                            <div class="col-sm-4">
					                                		<label for="actividad_economica">Giro Mercantil*</label>
						                               		<select name="giro_mercantil_moral_actualizar" readonly id="giro_mercantil_moral_actualizar" class="morale form-control" disabled>
				                            					<option value="" selected>Seleccione</option>
				                            						<?php foreach ($giros as $giro): ?>
				                            					<option value="<?=$giro->id_lista_valor;?>"><?=$giro->nombre_lista_valor;?></option>
				                            						<?php endforeach ?>
				                            				</select>
				                            			</div>
				                            			
					                            		<!-- ==============Inicio de direccion=================-->

					                            		<div class="col-lg-12">
					                            			<h3>Dirección</h3>
					                            			<hr>
					                            		</div>
					                            		<div class="col-sm-4">
							                                <label for="calle_contacto_actualizar">Calle*</label>
							                                <div class="form-group valid-required">
							                                    <div class="form-line">
							                                        <input type="text" class="form-control mayusculas morale" readonly name="calle_contacto_moral_actualizar" id="calle_contacto_moral_actualizar" placeholder="P. EJ. PRIMAVERA" readonly="readonly">
							                                    </div>
							                                </div>
							                            </div>
							                            <div class="col-sm-4">
							                                <label for="exterior_contacto_actualizar">Número Exterior*</label>
							                                <div class="form-group valid-required">
							                                    <div class="form-line">
							                                        <input type="text" class="form-control mayusculas morale" readonly name="exterior_moral_actualizar" maxlength="30" onkeypress='return solosnumerosyletras(event)' id="exterior_moral_actualizar" placeholder="P. EJ. 33" maxlength="10" readonly="readonly">
							                                    </div>
							                                </div>
							                            </div>
							                            <div class="col-sm-4">
							                                <label for="interior_contacto_registrar">Número Interior</label>
							                                <div class="form-group">
							                                    <div class="form-line">
							                                        <input type="text" class="form-control mayusculas numero_exterior morale" readonly onkeypress='return solosnumerosyletras(event)' name="interior_moral_actualizar" id="interior_moral_actualizar" placeholder="P. EJ. 2" maxlength="10" readonly="readonly">
							                                    </div>
							                                </div>
							                            </div>
										                <div class="col-sm-4">
							                                <label for="codigo_postal_registrar">Código Postal*</label>
							                                <div class="form-group valid-required">
							                                    <div class="form-line">
							                                        <input type="text" class="form-control morale" readonly id="codigo_postal_moral_actualizar" onkeypress='return codigoPostal(event)' name="codigo_postal_moral_actualizar" maxlength="5" onchange="buscarCodigosUs(this.value, 'create')" readonly="readonly">
							                                    </div>
							                            </div>
							                            </div>
							                            <div class="col-sm-4">
							                            	<label for="colonia_registrar">Colonia*</label>
							                            	<div class="valid-required">
							                                <select id="colonia_actualizar_moral" class="form-control form-group morale" readonly name="colonia_actualizar_moral" disabled>
								                                	<option value="">Seleccione</option>
								                                </select>
								                            </div>
							                            </div>
							                            <div class="col-sm-4"  style="padding-bottom: 10px;">
							                                <label for="municipio_registrar">Municipio*</label>
							                                <div class="valid-required">
								                                <select id="municipio_moral_actualizar" class="form-control form-group morale" readonly name="municipio_moral_actualizar" disabled>
								                                	<option value="">Seleccione</option>
								                                </select>
								                            </div>
							                            </div>
							                            <div class="col-sm-4">
							                                <label for="ciudad_actualizar">Ciudad*</label>
							                                <div class="valid-required">
								                                <select id="ciudad_moral_actualizar" class="form-control form-group morale" readonly name="ciudad_moral_actualizar" disabled>
								                                	<option value="">Seleccione</option>
								                                </select>
								                            </div>
							                            </div>
							                            <div class="col-sm-4">
							                                <label for="estado_actualizar">Estado*</label>
							                                <div class="valid-required">
								                                <select id="estado_moral_actualizar" class="form-control form-group morale" readonly name="estado_moral_actualizar" disabled>
								                                	<option value="">Seleccione</option>
								                                </select>
								                            </div>
							                            </div>
					                           			<!-- ===============================-->
							                    </div>
									        </div>
								        
								        	<div class="tab-pane fade tab_content2 " id="datosTrabajadoresE">
								        		<div class="embed-responsive embed-responsive-16by9">
												  <iframe class="embed-responsive-item " id="iframedatosTrabajadoresE" allowfullscreen>
												  </iframe>
												</div>
							                </div>
									        
									       <div class="tab-pane fade tab_content3 " id="saldosE">
								        		<div class="embed-responsive embed-responsive-16by9">
								        			<div id="mensajesSaldos"></div>
								        		 	<iframe class="embed-responsive-item " id="iframedatosSaldosE" allowfullscreen>
												  	</iframe>
												</div>
							                </div>
							       			<div class="tab-pane fade tab_content3 " id="renovacionE">
								        		<div class="embed-responsive embed-responsive-16by9">
								        			<div id="mensajesRenovaciones" ></div>
								        		 	<iframe class="embed-responsive-item " id="iframedatosRenovacionE" allowfullscreen>
												  	</iframe>
												</div>
							                </div>
							       	 		<div class="col-sm-4 col-sm-offset-5">
					                            <button type="button" onclick="regresar('#cuadro4')" class="btn btn-primary waves-effect">Regresar</button>
					                            <input type="submit" value="Actualizar" id="send" class="btn btn-success waves-effect save-cliente">
					                    	</div>
					                    </div>	
			                    	</form>
								</div>
			        		</div>
						</div>
					</div>
				</div>		
		        <!-- Cierre del cuadro de editar  -->
		        <input type="hidden" name="id_membresia" id="id_membresia">
		        <input type="hidden" name="numero_renovacion" id="numero_renovacion">
			</div>
		</section>
	</body>
	<script type="text/javascript">
		var base_url = '<?=base_url()?>';
	</script>
	<script src="<?=base_url();?>assets/template/plugins/jquery-datatable/jquery.dataTables.js"></script>
    <script src="<?=base_url();?>assets/template/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>
    <script src="<?=base_url();?>assets/template/plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js"></script>
    <script src="<?=base_url();?>assets/template/plugins/jquery-datatable/extensions/export/buttons.flash.min.js"></script>
    <script src="<?=base_url();?>assets/template/plugins/jquery-datatable/extensions/export/jszip.min.js"></script>
    <script src="<?=base_url();?>assets/template/plugins/jquery-datatable/extensions/export/pdfmake.min.js"></script>
    <script src="<?=base_url();?>assets/template/plugins/jquery-datatable/extensions/export/vfs_fonts.js"></script>
    <script src="<?=base_url();?>assets/template/plugins/jquery-datatable/extensions/export/buttons.html5.min.js"></script>
    <script src="<?=base_url();?>assets/template/plugins/jquery-datatable/extensions/export/buttons.print.min.js"></script>
    <script src="<?=base_url();?>assets/template/plugins/jquery-inputmask/jquery.inputmask.bundle.js"></script>
    <script src="<?=base_url();?>assets/template/plugins/momentjs/moment.js"></script>
    <script src="<?=base_url();?>assets/template/plugins/jquery-validation/jquery.validate.js"></script>
    <script src="<?=base_url();?>assets/template/plugins/jquery-validation/additional-methods.js"></script>
    <script src="<?=base_url();?>assets/template/plugins/bootstrap-datetimepicker-master/js/bootstrap-datetimepicker.min.js"></script>
    <script src="<?=base_url();?>assets/cpanel/Productos/js/numeral/min/numeral.min.js"></script>
    <script src="<?=base_url();?>assets/template/plugins/bootstrap-fileinput/js/plugins/piexif.min.js" type="text/javascript"></script>
    <script src="<?=base_url();?>assets/template/plugins/bootstrap-fileinput/js/plugins/purify.min.js" type="text/javascript"></script>
    <script src="<?=base_url();?>assets/template/plugins/bootstrap-fileinput/js/plugins/sortable.min.js" type="text/javascript"></script>
    
    
    <script src="<?=base_url();?>assets/template/plugins/bootstrap-fileinput/js/fileinput.js" type="text/javascript"></script>
    <script type="text/javascript" src="<?=base_url();?>assets/template/plugins/bootstrap-fileinput/themes/fa/theme.min.js"></script>
    <script src="<?=base_url();?>assets/template/plugins/bootstrap-fileinput/js/locales/es.js" type="text/javascript"></script>

     

    <script src="<?=base_url();?>assets/cpanel/Membresia/js/membresia.js"></script>
    <!-- Select 2 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <!-- -->
    <script>
		$("#mv<?php echo $permiso[0]->id_modulo_vista ?>").attr('class', 'active');
		$("#lv<?php echo $permiso[0]->id_lista_vista ?>").attr('class', 'active');
		var consultar = <?php echo $permiso[0]->detallada ?>,
			registrar = <?php echo $permiso[0]->registrar ?>,
			actualizar = <?php echo $permiso[0]->actualizar ?>,
			borrar = <?php echo $permiso[0]->eliminar ?>;
		if(registrar==0)
			$(".registrar").removeClass('ocultar');
		if(actualizar==0)
			$(".actualizar").removeClass('ocultar');
		if(borrar==0)
			$(".eliminar").removeClass('ocultar');
    	
	</script>
</html>
