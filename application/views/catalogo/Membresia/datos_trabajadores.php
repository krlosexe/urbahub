<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
<!DOCTYPE html>
<html>
	<link href="<?=base_url();?>assets/template/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
	<link href="<?=base_url();?>assets/template/plugins/bootstrap-datetimepicker-master/css/bootstrap-datetimepicker.css" rel="stylesheet" />
	<?php if(($permiso[0]->general==1 && $permiso[0]->detallada==1 && $permiso[0]->registrar==1 && $permiso[0]->actualizar==1 && $permiso[0]->eliminar==1) OR $permiso[0]->status==false): ?>
		<script src="<?=base_url();?>assets/cpanel/js/permiso.js"></script>
	<?php endif ?>
	<body class="theme-blue">
		<input type="hidden" id="ruta" value="<?=base_url();?>" name="ruta">
		<input type="hidden" id="len_num">

	        <div class="container-fluid">
	        	<div id="alertas"></div>
	        	
	        	<div class="block-header">
	  
	            </div>
	        	<!-- Comienzo del cuadro de la tabla -->
					<div class="row clearfix" id="cuadro1">
		                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                    <div class="card">
		                        <div class="header">
		                            <h2>
		                                Gestión de datos de trabajadores asociados a una membresía 
		                            </h2>
		                            <ul class="header-dropdown m-r--5">
		                                <button class="btn btn-primary waves-effect registrar ocultar" onclick="nuevoRegistro()"><i class='fa fa-plus-circle' style="color: white; font-size: 18px;"></i> | Nuevo</button>
		                            </ul>
		                        </div>
		                        <div class="body">
		                        <input type="hidden" class="form-control mayusculas" name="id_membresia" id="id_membresia_registrar" value="<?=$id_membresia;?>">
		                            <div class="table-responsive">
		                                <table class="table table-bordered table-striped table-hover" id="tabla">
		                                	   	<input type="hidden" class="form-control mayusculas" name="id_membresia" id="id_membresia" value="<?=$id_membresia;?>">

		                                    <thead>
		                                        <tr>
		                                        	<th style="text-align: center; padding: 0px 10px 0px 5px;"><input type="checkbox" id="checkall" class="chk-col-blue"/><label for="checkall"></label></th>
		                                        	<th style="width: 17%;">Acciones</th>
		                                            <th>Nombre Completo</th>
		                                            <th>Correo Electrónico</th>
		                                            <th>Teléfono</th>
		                                            <th>Fecha de Registro</th>
		                                            <th>Registrado Por</th>
		                                        </tr>
		                                    </thead>
		                                    <tbody></tbody>
		                                </table>
		                                <div class="col-md-2 eliminar ocultar">
		                                	<button class="btn btn-danger waves-effect" onclick="eliminarMultiple('Membresia/eliminar_multiple_datos_trabajador')">Eliminar seleccionados</button>
		                                </div>
		                                <div class="col-md-2 actualizar ocultar">
		                                	<button class="btn btn-warning waves-effect" onclick="statusMultiple('Membresia/status_multiple_datos_trabajadores', 1, 'activar')">Activar seleccionados</button>
		                                </div>
		                                <div class="col-md-2 actualizar ocultar">
		                                	<button class="btn btn-warning waves-effect" onclick="statusMultiple('Membresia/status_multiple_datos_trabajadores', 2, 'desactivar')">Desactivar seleccionados</button>
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
		                            <h2>Registro de trabajadores </h2>
		                        </div>
		                        <div class="body">
		                        	<div class="table-responsive">
			                            <form name="form_datos_trabajador_registrar" id="form_datos_trabajador_registrar" method="post"  enctype="multipart/form-data">
			                            	<input type="hidden" class="form-control mayusculas" name="id_membresia" id="id_membresia_registrar" value="<?=$id_membresia;?>">
 											<div>
	 											<!-- Cuerpo de datos del trabajador -->
												<div class="col-sm-6">
													<!--<div class="form-group">
					                                    <div class="form-line">
					                                        <input type="file" class="form-control" id="avatar_usuario_registrar" name="avatar_usuario" onchange="readURL(this, '#imagen_registrar', '#avatar_usuario_registrar')">
					                                    </div>
				                                    	<img id="imagen_registrar" src="<?php echo base_url();?>assets/cpanel/Usuarios/images/default.png" alt="Tu avatar"/ class="img-responsive ima_error " style="border-radius: 50%;max-width: 40%;">
				                                    </div>-->	
				                                    <label for="avatar_usuario_registrar">Subir Imagen</label>
					                                <div class="form-group">
					                                    <div class="form-line">
					                                        <input type="file" class="form-control" id="avatar_usuario_registrar" name="avatar_usuario" onchange="readURL(this, '#imagen_registrar', '#avatar_usuario_registrar')">
					                                    </div>
					                                    <img id="imagen_registrar" src="<?php echo base_url();?>assets/cpanel/Usuarios/images/default.png" alt="Tu avatar"/ class="img-responsive ima_error " style="border-radius: 50%;width:100px;height:100px">
					                                </div>
				                                </div>
													
												</div>
												<div class="col-sm-6">
													<div class="col-sm-12">
														<label for="serial_acceso_moral_dt">Serial de acceso*</label>
												        <div class="form-group valid-required">
												            <div class="form-line">
												                <input type="text" class="moralf form-control mayusculas" name="serial_acceso_moral_dt" autocomplete="off" onkeypress='return solosnumerosyletras(event)' id="serial_acceso_moral_dt" maxlength="30" placeholder="P. EJ.123456" required>
												        	</div>
												 		</div>
														</div>
														<!--<div class="col-sm-12 hide">
															<label for="grupo_empresarial_dt">Grupo empresarial*</label>
													    	<div class="form-group valid-required">
													        	<div class="form-line">
													            	<select name="grupo_empresarial_dt" id="grupo_empresarial_dt" class="fisicaf form-control" required>
																		<option value="" selected>Seleccione</option>
																		<?php foreach ($actividadesEconomicas as $actividadEconomica): ?>
							                            				<option value="<?=$actividadEconomica->id_lista_valor;?>"><?=$actividadEconomica->nombre_lista_valor;?></option>
							                            				<?php endforeach ?>
																	</select>
													        	</div>
													    	</div>
													 	</div>-->
													 	<div class="col-sm-12">
															<label for="nombre_dt">Nombre(s)*</label>
														    <div class="form-group valid-required">
														        <div class="form-line">
														            <input type="text" class="form-control mayusculas" name="nombre_dt" id="nombre_dt" placeholder="P. EJ. LUIS RAÚL" onkeypress='return sololetras(event)' required>
														        </div>
														    </div>
														</div>
												</div>
												
												<div class="col-sm-4">
													<label for="apellido_paterno_moral_dt">Apellido Paterno*</label>
												    <div class="form-group valid-required">
												        <div class="form-line">
												            <input type="text" class="form-control mayusculas" name="apellido_paterno_moral_dt" id="apellido_paterno_moral_dt" placeholder="P. EJ. BELLO" onkeypress='return sololetras(event)' required>
												        </div>
												    </div>
												</div>
												<div class="col-sm-4">
													<label for="apellido_materno_dt">Apellido Materno*</label>
												    <div class="form-group valid-required">
												        <div class="form-line">
												            <input type="text" class="form-control mayusculas" name="apellido_materno_dt" id="apellido_materno_dt" placeholder="P. EJ. MENA" onkeypress='return sololetras(event)' required>
												        </div>
												    </div>
													</div>
													
												<div class="col-sm-4">
													<label for="genero_registrar_dt">Género*</label>
													<select name="genero_registrar_dt" id="genero_registrar_dt" class="form-control" required>
														<option value="" selected>Seleccione</option>
														<?php foreach ($sexos as $sexo): ?>
															<option value="<?=$sexo->id_lista_valor;?>"><?=$sexo->nombre_lista_valor;?></option>
														<?php endforeach ?>
													</select>
												</div>
												<div class="col-sm-4">
													<label for="edo_civil_registrar_dt">Estado Civil*</label>
													<select name="edo_civil_registrar_dt" id="edo_civil_registrar_dt" class="form-control" required>
														<option value="" selected>Seleccione</option>
														<?php foreach ($estadosCiviles as $estadoCivil): ?>
															<option value="<?=$estadoCivil->id_lista_valor;?>"><?=$estadoCivil->nombre_lista_valor;?></option>
														<?php endforeach ?>
													</select>
												</div>
												<div class="col-sm-4">
													<label for="nacionalidad_registrar_dt">Nacionalidad*</label>
													<select name="nacionalidad_registrar_dt" id="nacionalidad_registrar_dt" class="form-control" required>
														<option value="" selected>Seleccione</option>
														<?php foreach ($nacionalidades as $nacionalidad): ?>
															<option value="<?=$nacionalidad->id_lista_valor;?>"><?=$nacionalidad->nombre_lista_valor;?></option>
														<?php endforeach ?>
													</select>
												</div>
												<div class="col-sm-4" style="margin-bottom: 10px;">
													<label for="fecha_nacimiento_dt">Fecha Nacimiento*</label>
													<div class="form-group valid-required">
															 <div class="form-line input-group fecha">
												    		<input type="text" class="moralf form-control" name="fecha_nacimiento_dt" id="fecha_nacimiento_dt" placeholder="dd-mm-yyyy" required>
												    		<span class="input-group-addon">
												       			 <span class="glyphicon glyphicon-calendar"></span>
												   		 	</span>
															 </div>
													</div>
												</div>
												<div class="col-sm-4">
												    <label for="curp_registrar_dt">C.U.R.P.</label>
												    <div class="form-group form-float">
												        <div class="form-line" id="validCurp">
												            <input type="text" class="form-control mayusculas" name="curp_registrar_dt" id="curp_registrar_dt" placeholder="P. EJ. BML920313HMLNNSOS" oninput="validarInputCurp(this)" >
												        </div>
												        <span class="curpError text-danger"></span>
												    </div>
												</div>
												<div class="col-sm-4">
												    <label for="pasaporte_registrar_dt">Pasaporte</label>
												    <div class="form-group form-float">
												        <div class="form-line" id="validCurp">
												            <input type="text" class="fisicaf form-control mayusculas" autocomplete="off" name="pasaporte_registrar_dt" id="pasaporte_registrar_dt" placeholder="P. EJ. 76543ER4325" maxlength="18" onkeypress='return solosnumerosyletras(event)'>
												        </div>
													   	
													</div>
												</div>
												<div class="col-sm-4">
													<label for="telefono_registrar_dt">Teléfono*</label>
												    <div class="form-group valid-required">
												        <div class="form-line">
												            <input type="text" class="moralf form-control telefono" name="telefono_registrar_dt" id="telefono_registrar_dt" placeholder="P. EJ.: +00 (000) 000-00-00" onkeyup="validPhone(this)" required>
												    		<span class="emailError text-danger"></span>
												        </div>
													 	</div>
												</div>
												<div class="col-sm-4">
													<label for="correo_registrar_dt">Correo Electrónico*</label>
												    <div class="form-group valid-required">
												    	<div class="form-line">
												        <input type="email" class="form-control moralf" name="correo_registrar_dt" id="correo_registrar_dt" placeholder="P. EJ. ejemplo@dominio.com" onchange="validEmail(this)" required>

												    	<span class="emailError text-danger"></span>
														</div>
													</div>
												</div>
												<div class="col-sm-4">
														<label for="actividad_economica_dt">Actividad Economica</label>
														<select name="actividad_economica_dt" id="actividad_economica_dt" class="fisicaf form-control">
														<option value="" selected>Seleccione</option>
															<?php foreach ($actividadesEconomicas as $actividadEconomica): ?>
														<option value="<?=$actividadEconomica->id_lista_valor;?>"><?=$actividadEconomica->nombre_lista_valor;?></option>
															<?php endforeach ?>
													</select>
												</div>
	 											<!-- -->
							        		</div>

								       	 		<div class="col-sm-4 col-sm-offset-5">
			                                        <button type="button" onclick="regresar('#cuadro2')" class="btn btn-primary waves-effect">Regresar</button>
			                                        <input type="submit" value="Guardar" class="btn btn-success waves-effect save-cliente">
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
		                            <h2>Consultar Membresia </h2>
		                        </div>
		                        <div class="body">
		                            <div class="table-responsive">
			                            <form name="form_datos_trabajador_mostrar" id="form_datos_trabajador_mostrar" method="post">
			                            	<input type="hidden" class="form-control mayusculas" name="id_membresia_mostrar" id="id_membresia_mostrar" value="<?=$id_membresia;?>">
 											<div>
	 											<!-- Cuerpo de datos del trabajador -->
												<div class="col-sm-6">
													<!--<img id="imagen_registrar" src="<?php echo base_url();?>assets/cpanel/Usuarios/images/default.png" alt="Tu avatar"/ class="img-responsive ima_error " style="border-radius: 50%;max-width: 40%;">-->

													<label for="avatar_usuario_registrar">Imagen</label>
					                                <div class="form-group">
					                                    <img id="imagen_consultar" src="" alt="Tu avatar" class="img-responsive ima_error" style="border-radius: 50%;width:100px;height: 100px;">
					                                </div>
												</div>
												<div class="col-sm-6">
													<div class="col-sm-12">
														<label for="serial_acceso_moral_dt_mostrar">Serial de acceso*</label>
												        <div class="form-group valid-required">
												            <div class="form-line">
												                <input type="text" class="form-control mayusculas" name="serial_acceso_moral_dt_mostrar" autocomplete="off" onkeypress='return solosnumerosyletras(event)' id="serial_acceso_moral_dt_mostrar" maxlength="30" placeholder="P. EJ.123456" disabled>
												        	</div>
												 		</div>
														</div>
														<!--<div class="col-sm-12 hide">
															<label for="grupo_empresarial_dt_mostrar">Grupo empresarial*</label>
													    	<div class="form-group valid-required">
													        	<div class="form-line">
													            	<input name="grupo_empresarial_dt_mostrar" id="grupo_empresarial_dt_mostrar" type="text" class="form-control mayusculas">
													        	</div>
													    	</div>
													 	</div>-->
													 	<div class="col-sm-12">
															<label for="nombre_dt_mostrar">Nombre(s)*</label>
														    <div class="form-group valid-required">
														        <div class="form-line">
														            <input type="text" class="form-control mayusculas" name="nombre_dt_mostrar" id="nombre_dt_mostrar" placeholder="P. EJ. LUIS RAÚL" onkeypress='return sololetras(event)' disabled>
														        </div>
														    </div>
														</div>
												</div>
												
												<div class="col-sm-4">
													<label for="apellido_paterno_moral_dt_mostrar">Apellido Paterno*</label>
												    <div class="form-group valid-required">
												        <div class="form-line">
												            <input type="text" class="form-control mayusculas" name="apellido_paterno_moral_dt_mostrar" id="apellido_paterno_moral_dt_mostrar" placeholder="P. EJ. BELLO" onkeypress='return sololetras(event)' disabled>
												        </div>
												    </div>
												</div>
												<div class="col-sm-4">
													<label for="apellido_materno_dt_mostrar">Apellido Materno*</label>
												    <div class="form-group valid-required">
												        <div class="form-line">
												            <input type="text" class="form-control mayusculas" name="apellido_materno_dt_mostrar" id="apellido_materno_dt_mostrar" placeholder="P. EJ. MENA" onkeypress='return sololetras(event)' disabled>
												        </div>
												    </div>
													</div>
													
												<div class="col-sm-4">
													<label for="genero_registrar_dt_mostrar">Género*</label>
													  	<div class="form-group">
					                                    	<div class="form-line">
																<input type="text" name="genero_registrar_dt_mostrar" id="genero_registrar_dt_mostrar" class="form-control mayusculas" disabled>
															</div>
														</div>	
												</div>
												<div class="col-sm-4">
													<label for="edo_civil_dt_mostrar">Estado Civil*</label>
													  	<div class="form-group">
					                                    	<div class="form-line">
																<input type="text" name="edo_civil_dt_mostrar" id="edo_civil_dt_mostrar" class="form-control mayusculas" disabled>
															</div>
														</div>	
												</div>
												<div class="col-sm-4">
													<label for="nacionalidad_dt_mostrar">Nacionalidad*</label>
													  	<div class="form-group">
					                                    	<div class="form-line">
																<input type="text" name="nacionalidad_dt_mostrar" id="nacionalidad_dt_mostrar" class="form-control mayusculas" disabled>
															</div>
														</div>	
												</div>
												<div class="col-sm-4" style="margin-bottom: 10px;">
													<label for="fecha_nacimiento_dt_mostrar">Fecha Nacimiento*</label>
													<div class="form-group valid-required">
															 <div class="form-line input-group fecha">
												    		<input type="text" class="moralf form-control" name="fecha_nacimiento_dt_mostrar" id="fecha_nacimiento_dt_mostrar" placeholder="dd-mm-yyyy" disabled>
												    		<span class="input-group-addon">
												       			 <span class="glyphicon glyphicon-calendar"></span>
												   		 	</span>
															 </div>
													</div>
												</div>
												<div class="col-sm-4">
												    <label for="curp_dt_mostrar">C.U.R.P.</label>
												    <div class="form-group form-float">
												        <div class="form-line" id="validCurp">
												            <input type="text" class="form-control mayusculas" name="curp_dt_mostrar" id="curp_dt_mostrar" placeholder="P. EJ. BML920313HMLNNSOS" disabled >
												        </div>
												        <span class="curpError text-danger"></span>
												    </div>
												</div>
												<div class="col-sm-4">
												    <label for="pasaporte_dt_mostrar">Pasaporte</label>
												    <div class="form-group form-float">
												        <div class="form-line" id="validCurp">
												            <input type="text" class="fisicaf form-control mayusculas" autocomplete="off" name="pasaporte_dt_mostrar" id="pasaporte_dt_mostrar" placeholder="P. EJ. 76543ER4325" maxlength="18" onkeypress='return solosnumerosyletras(event)'  disabled>
												        </div>
													   
														</div>
												</div>
												<div class="col-sm-4">
													<label for="telefono_dt_mostrar">Teléfono*</label>
												    <div class="form-group valid-required">
												        <div class="form-line">
												            <input type="text" class="moralf form-control telefono" name="telefono_dt_mostrar" id="telefono_dt_mostrar" placeholder="P. EJ.: +00 (000) 000-00-00" onkeyup="validPhone(this)" disabled>
												    		<span class="emailError text-danger"></span>
												        </div>
													 	</div>
												</div>
												<div class="col-sm-4">
													<label for="correo_dt_mostrar">Correo Electrónico*</label>
												    <div class="form-group valid-required">
												    	<div class="form-line">
												        <input type="email" class="form-control moralf" name="correo_dt_mostrar" id="correo_dt_mostrar" placeholder="P. EJ. ejemplo@dominio.com" onchange="validEmail(this)" disabled>

												    	<span class="emailError text-danger"></span>
														</div>
													</div>
												</div>
												<div class="col-sm-4">
													<label for="actividad_economica_dt_mostrar">Actividad Economica</label>
													<div class="form-group">
				                                    	<div class="form-line">
															<input type="text" name="actividad_economica_dt_mostrar" id="actividad_economica_dt_mostrar" class="form-control mayusculas" disabled>
														</div>
													</div>	
												</div>
	 											<!-- -->
							        		</div>

							       	 		<div class="col-sm-4 col-sm-offset-5">
		                                        <button type="button" onclick="regresar('#cuadro3')" class="btn btn-primary waves-effect">Regresar</button>
		                                	</div>
									   </form>
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
		                            <h2>Editar datos del trabajador</h2>
		                        </div>
		                        <div class="body">
		                           <div class="table-responsive">
			                            <form name="form_datos_trabajador_actualizar" id="form_datos_trabajador_actualizar" method="post">
			                            	<input type="hidden" class="form-control mayusculas" name="id_membresia_actualizar" id="id_membresia_actualizar" value="<?=$id_membresia;?>">
 											<div>
	 											<!-- Cuerpo de datos del trabajador -->
												<div class="col-sm-6">
													<label for="avatar_usuario_actualizar">Subir Imagen</label>
					                                <div class="form-group">
					                                    <div class="form-line">
					                                        <input type="file" class="form-control" id="avatar_usuario_actualizar" name="avatar_usuario" onchange="readURL(this, '#imagen_actualizar', '#avatar_usuario_actualizar')">
					                                    </div>
					                                    <img id="imagen_actualizar" src="http://placehold.it/180" alt="Tu avatar" class="img-responsive ima_error" style="border-radius: 50%;width: 100px;height: 100px;">
					                                </div>
												</div>
												<div class="col-sm-6">
													<div class="col-sm-12">
														<label for="serial_acceso_moral_dt_actualizar">Serial de acceso*</label>
												        <div class="form-group valid-required">
												            <div class="form-line">
												                <input type="text" class="form-control mayusculas" name="serial_acceso_moral_dt_actualizar" autocomplete="off" onkeypress='return solosnumerosyletras(event)' id="serial_acceso_moral_dt_actualizar" maxlength="30" placeholder="P. EJ.123456" readonly required>
												        	</div>
												 		</div>
													</div>
														<!--<div class="col-sm-12 hide">
															<label for="grupo_empresarial_dt_actualizar">Grupo empresarial*</label>
													    	<div class="form-group valid-required">
													        	<div class="form-line">
													            	<select name="grupo_empresarial_dt_actualizar" id="grupo_empresarial_dt_actualizar" class="fisicaf form-control" required>
																		<option value="" selected>Seleccione</option>
																		<?php foreach ($actividadesEconomicas as $actividadEconomica): ?>
							                            				<option value="<?=$actividadEconomica->id_lista_valor;?>"><?=$actividadEconomica->nombre_lista_valor;?></option>
							                            				<?php endforeach ?>
																	</select>
													        	</div>
													    	</div>
													 	</div>-->
													 	<div class="col-sm-12">
															<label for="nombre_dt_actualizar">Nombre(s)*</label>
														    <div class="form-group valid-required">
														        <div class="form-line">
														            <input type="text" class="form-control mayusculas" name="nombre_dt_actualizar" id="nombre_dt_actualizar" placeholder="P. EJ. LUIS RAÚL" onkeypress='return sololetras(event)' required>
														        </div>
														    </div>
														</div>
												</div>
												
												<div class="col-sm-4">
													<label for="apellido_paterno_moral_dt_actualizar">Apellido Paterno*</label>
												    <div class="form-group valid-required">
												        <div class="form-line">
												            <input type="text" class="form-control mayusculas" name="apellido_paterno_moral_dt_actualizar" id="apellido_paterno_moral_dt_actualizar" placeholder="P. EJ. BELLO" onkeypress='return sololetras(event)' required>
												        </div>
												    </div>
												</div>
												<div class="col-sm-4">
													<label for="apellido_materno_dt_actualizar">Apellido Materno*</label>
												    <div class="form-group valid-required">
												        <div class="form-line">
												            <input type="text" class="form-control mayusculas" name="apellido_materno_dt_actualizar" id="apellido_materno_dt_actualizar" placeholder="P. EJ. MENA" onkeypress='return sololetras(event)' required>
												        </div>
												    </div>
													</div>
													
												<div class="col-sm-4">
													<label for="genero_registrar_dt_actualizar">Género*</label>
													<select name="genero_dt_actualizar" id="genero_dt_actualizar" class="form-control" required>
														<option value="" selected>Seleccione</option>
														<?php foreach ($sexos as $sexo): ?>
															<option value="<?=$sexo->id_lista_valor;?>"><?=$sexo->nombre_lista_valor;?></option>
														<?php endforeach ?>
													</select>
												</div>
												<div class="col-sm-4">
													<label for="edo_civil_dt_actualizar">Estado Civil*</label>
													<select name="edo_civil_dt_actualizar" id="edo_civil_dt_actualizar" class="form-control" required>
														<option value="" selected>Seleccione</option>
														<?php foreach ($estadosCiviles as $estadoCivil): ?>
															<option value="<?=$estadoCivil->id_lista_valor;?>"><?=$estadoCivil->nombre_lista_valor;?></option>
														<?php endforeach ?>
													</select>
												</div>
												<div class="col-sm-4">
													<label for="nacionalidad_dt_actualizar">Nacionalidad*</label>
													<select name="nacionalidad_dt_actualizar" id="nacionalidad_dt_actualizar" class="form-control" required>
														<option value="" selected>Seleccione</option>
														<?php foreach ($nacionalidades as $nacionalidad): ?>
															<option value="<?=$nacionalidad->id_lista_valor;?>"><?=$nacionalidad->nombre_lista_valor;?></option>
														<?php endforeach ?>
													</select>
												</div>
												<div class="col-sm-4" style="margin-bottom: 10px;">
													<label for="fecha_nacimiento_dt_actualizar">Fecha Nacimiento*</label>
													<div class="form-group valid-required">
															 <div class="form-line input-group fecha">
												    		<input type="text" class="moralf form-control" name="fecha_nacimiento_dt_actualizar" id="fecha_nacimiento_dt_actualizar" placeholder="dd-mm-yyyy" required>
												    		<span class="input-group-addon">
												       			 <span class="glyphicon glyphicon-calendar"></span>
												   		 	</span>
															 </div>
													</div>
												</div>
												<div class="col-sm-4">
												    <label for="curp_dt_actualizar">C.U.R.P.</label>
												    <div class="form-group form-float">
												        <div class="form-line" id="validCurp">
												            <input type="text" class="form-control mayusculas" name="curp_dt_actualizar" id="curp_dt_actualizar" placeholder="P. EJ. BML920313HMLNNSOS" oninput="validarInputCurp(this)" >
												        </div>
												        <span class="curpError text-danger"></span>
												    </div>
												</div>
												<div class="col-sm-4">
												    <label for="pasaporte_dt_actualizar">Pasaporte</label>
												    <div class="form-group form-float">
												        <div class="form-line" id="validCurp">
												            <input type="text" class="fisicaf form-control mayusculas" autocomplete="off" name="pasaporte_dt_actualizar" id="pasaporte_dt_actualizar" placeholder="P. EJ. 76543ER4325" maxlength="18" onkeypress='return solosnumerosyletras(event)'>
												        </div>
													   	
													</div>
												</div>
												<div class="col-sm-4">
													<label for="telefono_dt_actualizar">Teléfono*</label>
												    <div class="form-group valid-required">
												        <div class="form-line">
												            <input type="text" class="moralf form-control telefono" name="telefono_dt_actualizar" id="telefono_dt_actualizar" placeholder="P. EJ.: +00 (000) 000-00-00" onkeyup="validPhone(this)" required>
												    		<span class="emailError text-danger"></span>
												        </div>
													 	</div>
												</div>
												<div class="col-sm-4">
													<label for="correo_dt_actualizar">Correo Electrónico*</label>
												    <div class="form-group valid-required">
												    	<div class="form-line">
												        <input type="email" class="form-control moralf" name="correo_dt_actualizar" id="correo_dt_actualizar" placeholder="P. EJ. ejemplo@dominio.com" onchange="validEmail(this)" required>

												    	<span class="emailError text-danger"></span>
														</div>
													</div>
												</div>
												<div class="col-sm-4">
														<label for="actividad_economica_dt_actualizar">Actividad Economica</label>
														<select name="actividad_economica_dt_actualizar" id="actividad_economica_dt_actualizar" class="fisicaf form-control">
														<option value="" selected>Seleccione</option>
															<?php foreach ($actividadesEconomicas as $actividadEconomica): ?>
														<option value="<?=$actividadEconomica->id_lista_valor;?>"><?=$actividadEconomica->nombre_lista_valor;?></option>
															<?php endforeach ?>
													</select>
												</div>
	 											<!-- -->
							        		</div>

							       	 		<div class="col-sm-4 col-sm-offset-5">
		                                        <button type="button" onclick="regresar('#cuadro4')" class="btn btn-primary waves-effect">Regresar</button>
		                                        <input type="submit" value="Guardar" class="btn btn-success waves-effect save-cliente">
		                                	</div>
									   </form>
			                       </div>
		                    	</div>
		                    </div>
		                </div>
		            </div>
		        <!-- Cierre del cuadro de editar  -->
			</div>
	
	</body>
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
    <script src="<?=base_url();?>assets/cpanel/Productos/js/numeral/min/numeral.min.js"></script>
    <script src="<?=base_url();?>assets/template/plugins/momentjs/moment.js"></script>
    <script src="<?=base_url();?>assets/template/plugins/bootstrap-datetimepicker-master/js/bootstrap-datetimepicker.min.js"></script>
    <script src="<?=base_url();?>assets/cpanel/Membresia/js/datos_trabajadores.js"></script>
    <script>
    	var editable = "<?php echo ($editable) ? 1 : 0;?>"
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
