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
		                                <button class="btn btn-primary waves-effect" onclick="nuevoUsuario()"><i class='fa fa-plus-circle' style="color: white; font-size: 18px;"></i> | Nuevo</button>
		                            </ul>
		                        </div>
		                        <div class="body">
		                            <div class="table-responsive">
		                                <table class="table table-bordered table-striped table-hover" id="tabla" style="font-size: 13px; width: 100%;">
		                                    <thead>
		                                        <tr>
		                                        	<th style="text-align: center; padding: 0px 10px 0px 5px; width:4%;"><input type="checkbox" id="checkall" class="chk-col-blue"/><label for="checkall"></label></th>
		                                        	<th>Acciones</th>
		                                        	<th>Nombre(s)</th>
		                                        	<th>Apellido Paterno</th>
		                                        	<th>Apellido Materno</th>
		                                        	<th>CURP</th>
		                                            <th>Correo Electrónico</th>
		                                            <th>Rol</th>
		                                            <th>Fecha de Registro</th>
		                                            <th>Registrado Por</th>
		                                            <th>Fecha Últi. Conexión</th>
		                                            
		                                        </tr>
		                                    </thead>
		                                    <tbody></tbody>
		                                </table>
		                                <!-- Le quite la clase eliminar al btn -->
		                                <div class="col-md-2 ocultar  disabled">
		                                	<button class="btn btn-danger waves-effect" onclick="eliminarMultiple('Usuarios/eliminar_multiple_usuario')">Eliminar seleccionados</button>
		                                </div>
		                                <div class="col-md-2 ocultar actualizar">
		                                	<button class="btn btn-warning waves-effect" onclick="statusMultiple('Usuarios/status_multiple_usuario', 1, 'activar')">Activar seleccionados</button>
		                                </div>
		                                <div class="col-md-2 ocultar actualizar">
		                                	<button class="btn btn-warning waves-effect" onclick="statusMultiple('Usuarios/status_multiple_usuario', 2, 'desactivar')">Desactivar seleccionados</button>
		                                </div>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        <!-- Cierre del cuadro de la tabla -->

		        <!-- Comienzo del cuadro de registrar usuario -->
					<div class="row clearfix ocultar" id="cuadro2">
		                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                    <div class="card">
		                        <div class="header">
		                            <h2>Registro de <?php echo $breadcrumbs[0]["nombre_lista_vista"]; ?></h2>
		                        </div>
		                        <div class="body">
		                        	<div class="table-responsive">
		                        		<div class="col-md-12" style="margin-top: 20px;">
		                        			<h4>Datos Personales</h4>
		                        		</div>
			                            <form name="form_usuario_registrar" id="form_usuario_registrar" method="post" enctype="multipart/form-data">
			                            	<div class="col-sm-4">
			                            		<label for="nombre_datos_personales_registrar">Nombre(s)*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control mayusculas" name="nombre_datos_personales" id="nombre_datos_personales_registrar" onkeypress='return sololetras(event)' maxlength="200" placeholder="P. EJ. LUIS RAÚL" required>
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
				                                <label for="apellido_p_datos_personales_registrar">Apellido Paterno*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control mayusculas" maxlength="100" name="apellido_p_datos_personales" id="apellido_p_datos_personales_registrar" placeholder="P. EJ. BELLO" required onkeypress='return sololetras(event)'>
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
				                                <label for="apellido_m_datos_personales_registrar">Apellido Materno*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control mayusculas" maxlength="100" name="apellido_m_datos_personales" id="apellido_m_datos_personales_registrar" placeholder="P. EJ. MENA" required onkeypress='return sololetras(event)'>
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
				                                <label for="fecha_nac_datos_personales_registrar">Fecha de Nacimiento*</label>
				                                <div class="form-group form-float" id="xfec_nac">
				                                    <div class="form-line input-group fecha">
				                                        <input type="text" class="form-control validar_fecha_calendario" maxlength="10" div_error="#xfec_nac" name="fecha_nac_datos_personales" id="fecha_nac_datos_personales_registrar" placeholder="dd-mm-yyyy" required>
				                                        <span class="input-group-addon validar_fecha_calendario2">
									                        <span class="glyphicon glyphicon-calendar"></span>
									                    </span>
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
			                            		<label for="nacionalidad_datos_personales_registrar">Nacionalidad*</label>
		                                    	<select name="nacionalidad_datos_personales" id="nacionalidad_datos_personales_registrar" required class="form-control">
		                                    		<option value="" selected>Seleccione</option>
		                                    		<?php foreach ($nacionalidades as $nacionalidad): ?>
		                                    			<option value="<?=$nacionalidad->id_lista_valor;?>"><?=$nacionalidad->nombre_lista_valor;?></option>
		                                    		<?php endforeach ?>
		                                    	</select>
				                            </div>
				                            <div class="col-sm-4">
				                                <label for="curp_datos_personales_registrar">C.U.R.P.*</label>
				                                <div class="form-group form-float">
				                                    <div class="form-line" id="validCurp">
				                                        <input type="text" class="form-control mayusculas" maxlength="18" name="curp_datos_personales" id="curp_datos_personales_registrar" placeholder="P. EJ. BEML920313HCMLNS09" oninput="validarInputCurp(this)">
				                                    </div>
				                                    <span class="curpError text-danger"></span>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
				                                <label for="telefono_registrar">Teléfono*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control telefono" name="telefono_principal_contacto" id="telefono_registrar" placeholder="P. EJ.: +00 (000) 000-00-00">
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
			                            		<label for="edo_civil_datos_personales_registrar">Estado Civil*</label>
		                                    	<select name="edo_civil_datos_personales" id="edo_civil_datos_personales_registrar" required class="form-control">
		                                    		<option value="" selected>Seleccione</option>
		                                    		<?php foreach ($estadosCiviles as $estadoCivil): ?>
		                                    			<option value="<?=$estadoCivil->id_lista_valor;?>"><?=$estadoCivil->nombre_lista_valor;?></option>
		                                    		<?php endforeach ?>
		                                    	</select>
				                            </div>
				                            <div class="col-sm-4">
			                            		<label for="genero_datos_personales_registrar">Género*</label>
		                                    	<select name="genero_datos_personales" id="genero_datos_personales_registrar" required class="form-control">
		                                    		<option value="" selected>Seleccione</option>
		                                    		<?php foreach ($sexos as $sexo): ?>
		                                    			<option value="<?=$sexo->id_lista_valor;?>"><?=$sexo->nombre_lista_valor;?></option>
		                                    		<?php endforeach ?>
		                                    	</select>
				                            </div>
				                            <div class="col-md-12" style="margin-top: 20px;">
			                        			<h4>Datos de la Dirección</h4>
			                        		</div>
			                        		<!--
			                        		<div class="col-sm-4">
				                                <label for="direccion_contacto_registrar">Domicilio</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control mayusculas" maxlength="200" name="direccion_contacto" id="direccion_contacto_registrar" placeholder="P. EJ. INSURGENTE">
				                                    </div>
				                                </div>
				                            </div>
				                        	-->
				                            <div class="col-sm-4">
				                                <label for="calle_contacto_registrar">Calle</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control mayusculas" maxlength="100" name="calle_contacto" id="calle_contacto_registrar" placeholder="P. EJ. PRIMAVERA">
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
				                                <label for="exterior_contacto_registrar">Número Exterior</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control mayusculas" maxlength="30" name="exterior_contacto" id="exterior_contacto_registrar" placeholder="P. EJ. 33">
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
				                                <label for="interior_contacto_registrar">Número Interior</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control mayusculas" maxlength="30" name="interior_contacto" id="interior_contacto_registrar" placeholder="P. EJ. 2">
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
				                                <label for="codigo_postal_registrar">Código Postal*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control" id="codigo_postal_registrar" onkeypress='return solonumeros(event)' maxlength="6" onchange="buscarCodigosUs(this.value, 'create')">
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4" style="padding-bottom: 10px;">
				                            	<label for="colonia_registrar">Colonia*</label>
		                                        <select id="colonia_registrar" required class="form-control form-group" name="colonia">
		                                        	<!--<option value="">Seleccione</option>-->
		                                        </select>
				                            </div>
				                            <div class="col-sm-4">
				                                <label for="municipio_registrar">Municipio*</label>
		                                        <select id="municipio_registrar" required class="form-control form-group" name="municipio">
		                                        	<!--<option value="">Seleccione</option>-->
		                                        </select>
				                            </div>
				                            <div class="col-sm-12" style="padding: 0px">
					                            <div class="col-sm-4">
					                                <label for="ciudad_registrar">Ciudad*</label>
			                                        <select id="ciudad_registrar" required class="form-control form-group" name="ciudad">
			                                        	<!--<option value="">Seleccione</option>-->
			                                        </select>
					                            </div>
					                            <div class="col-sm-4">
					                                <label for="estado_registrar">Estado*</label>
			                                        <select id="estado_registrar" required class="form-control form-group" name="estado">
			                                        	<!--<option value="">Seleccione</option>-->
			                                        </select>
					                            </div>
					                        </div>    
				                            <div class="col-md-12" style="margin-top: 20px;">
			                        			<h4>Cuenta de Usuario</h4>
			                        		</div>
				                            <div class="col-sm-4">
				                                <label for="correo_usuario_registrar">Correo Electrónico*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="email" class="form-control" maxlength="100" name="correo_usuario" id="correo_usuario_registrar" placeholder="P. EJ. ejemplo@dominio.com">
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
				                                <label for="correo_confirmar_registrar">Confirmar Correo Electrónico*</label>
				                                <div class="form-group form-float">
				                                    <div class="form-line" id="correoConfirmarRegistrar">
				                                        <input type="email" class="form-control" maxlength="100" name="correo_confirmar" id="correo_confirmar_registrar" placeholder="P. EJ. ejemplo@dominio.com" oninput="validarCorreo('#correo_usuario_registrar', '#correo_confirmar_registrar','#correoConfirmarRegistrar')">
				                                    </div>
				                                    <span class="text-danger correoError"></span>
				                                </div>
				                            </div>
				                            <div class="col-sm-4" style="padding-bottom: 30px;">
			                            		<label for="id_rol_registrar">Tipo de Rol*</label>
		                                    	<select name="id_rol" id="id_rol_registrar" required class="form-control">
		                                    		<option value="" selected>Seleccione</option>
		                                    		<?php foreach ($roles as $rol): ?>
		                                    			<option value="<?=$rol['id_rol'];?>"><?=$rol['nombre_rol'];?></option>
		                                    		<?php endforeach ?>
		                                    	</select>
				                            </div>
				                            <div class="col-sm-4">
				                                <label for="clave_usuario_registrar">Contraseña*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="password" maxlength="50" class="form-control" name="clave_usuario" id="clave_usuario_registrar" placeholder="Escribir contraseña">
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
				                                <label for="repetir_registrar">Repetir Contraseña*</label>
				                                <div class="form-group form-float">
				                                    <div class="form-line" id="repetirContraseñaRegistrar">
				                                        <input type="password" maxlength="50" class="form-control" name="repetir_clave" id="repetir_registrar" placeholder="Repetir Contraseña" oninput="validarClave('#clave_usuario_registrar', '#repetir_registrar','#repetirContraseñaRegistrar')">
				                                    </div>
				                                    <span class="text-danger claveError"></span>
				                                </div>
				                            </div>
				                            <div class="col-sm-12">
				                                <label for="avatar_usuario_registrar">Subir Imagen</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="file" class="form-control" id="avatar_usuario_registrar" name="avatar_usuario" onchange="readURL(this, '#imagen_registrar', '#avatar_usuario_registrar')">
				                                    </div>
				                                    <img id="imagen_registrar" src="<?php echo base_url();?>assets/cpanel/Usuarios/images/default.png" alt="Tu avatar"/ class="img-responsive ima_error" style="max-width: 15%;">
				                                </div>
				                            </div>
                                			<br>
                                			<div class="col-sm-4 col-sm-offset-5">
		                                        <button type="button" onclick="regresar('#cuadro2')" class="btn btn-primary waves-effect">Regresar</button>
		                                        <input type="submit" value="Guardar" class="btn btn-success waves-effect">
			                                </div>
			                            </form>
			                        </div>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        <!-- Cierre del cuadro de registrar usuario -->

		        <!-- Comienzo del cuadro de consultar usuario -->
					<div class="row clearfix ocultar" id="cuadro3">
		                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                    <div class="card">
		                        <div class="header">
		                            <h2>Consultar <?php echo $breadcrumbs[0]["nombre_lista_vista"]; ?></h2>
		                        </div>
		                        <div class="body">
		                        	<div class="table-responsive">
		                        		<div class="col-md-12" style="margin-top: 20px;">
		                        			<h4>Datos Personales</h4>
		                        		</div>
		                            	<div class="col-sm-4">
		                            		<label>Nombre(s)*</label>
			                                <div class="form-group">
			                                    <div class="form-line">
			                                        <input type="text" class="form-control mayusculas" id="nombre_datos_personales_consultar" disabled>
			                                    </div>
			                                </div>
			                            </div>
			                            <div class="col-sm-4">
			                                <label>Apellido Paterno*</label>
			                                <div class="form-group">
			                                    <div class="form-line">
			                                        <input type="text" class="form-control mayusculas" id="apellido_p_datos_personales_consultar" disabled>
			                                    </div>
			                                </div>
			                            </div>
			                            <div class="col-sm-4">
			                                <label>Apellido Materno*</label>
			                                <div class="form-group">
			                                    <div class="form-line">
			                                        <input type="text" class="form-control mayusculas" id="apellido_m_datos_personales_consultar" disabled>
			                                    </div>
			                                </div>
			                            </div>
			                            <div class="col-sm-4">
			                                <label>Fecha de Nacimiento*</label>
			                                <div class="form-group">
			                                    <div class="form-line">
			                                        <input type="text" class="form-control" id="fecha_nac_datos_personales_consultar" disabled>
			                                    </div>
			                                </div>
			                            </div>
			                            <div class="col-sm-4">
		                            		<label>Nacionalidad*</label>
	                                    	<select id="nacionalidad_datos_personales_consultar" disabled class="form-control">
	                                    		<option value="" selected>Seleccione</option>
	                                    		<?php foreach ($nacionalidades as $nacionalidad): ?>
	                                    			<option value="<?=$nacionalidad->id_lista_valor;?>"><?=$nacionalidad->nombre_lista_valor;?></option>
	                                    		<?php endforeach ?>
	                                    	</select>
			                            </div>
			                            <div class="col-sm-4">
			                                <label>C.U.R.P.*</label>
			                                <div class="form-group form-float">
			                                    <div class="form-line">
			                                        <input type="text" class="form-control mayusculas" id="curp_datos_personales_consultar" disabled>
			                                    </div>
			                                </div>
			                            </div>
			                            <div class="col-sm-4">
			                                <label>Teléfono*</label>
			                                <div class="form-group">
			                                    <div class="form-line">
			                                        <input type="text" class="form-control" id="telefono_consultar" disabled>
			                                    </div>
			                                </div>
			                            </div>
			                            <div class="col-sm-4">
		                            		<label>Estado Civil*</label>
	                                    	<select  id="edo_civil_datos_personales_consultar" disabled class="form-control">
	                                    		<option value="" selected>Seleccione</option>
	                                    		<?php foreach ($estadosCiviles as $estadoCivil): ?>
	                                    			<option value="<?=$estadoCivil->id_lista_valor;?>"><?=$estadoCivil->nombre_lista_valor;?></option>
	                                    		<?php endforeach ?>
	                                    	</select>
			                            </div>
			                            <div class="col-sm-4">
		                            		<label>Género*</label>
	                                    	<select id="genero_datos_personales_consultar" disabled class="form-control">
	                                    		<option value="" selected>Seleccione</option>
	                                    		<?php foreach ($sexos as $sexo): ?>
	                                    			<option value="<?=$sexo->id_lista_valor;?>"><?=$sexo->nombre_lista_valor;?></option>
	                                    		<?php endforeach ?>
	                                    	</select>
			                            </div>
			                            <div class="col-md-12" style="margin-top: 20px;">
		                        			<h4>Datos de la Dirección</h4>
		                        		</div>
		                        		<!--
		                        		<div class="col-sm-4">
			                                <label>Domicilio</label>
			                                <div class="form-group">
			                                    <div class="form-line">
			                                        <input type="text" class="form-control mayusculas" id="direccion_contacto_consultar" disabled>
			                                    </div>
			                                </div>
			                            </div>
			                        -->
			                            <div class="col-sm-4">
			                                <label>Calle</label>
			                                <div class="form-group">
			                                    <div class="form-line">
			                                        <input type="text" class="form-control mayusculas" id="calle_contacto_consultar" disabled>
			                                    </div>
			                                </div>
			                            </div>
			                            <div class="col-sm-4">
			                                <label>Número Exterior</label>
			                                <div class="form-group">
			                                    <div class="form-line">
			                                        <input type="text" class="form-control" id="exterior_contacto_consultar" disabled>
			                                    </div>
			                                </div>
			                            </div>
			                            <div class="col-sm-4">
			                                <label>Número Interior</label>
			                                <div class="form-group">
			                                    <div class="form-line">
			                                        <input type="text" class="form-control" id="interior_contacto_consultar" disabled>
			                                    </div>
			                                </div>
			                            </div>
			                            <div class="col-sm-4">
			                                <label>Código Postal*</label>
			                                <div class="form-group">
			                                    <div class="form-line">
			                                        <input type="text" class="form-control" id="codigo_postal_consultar" disabled>
			                                    </div>
			                                </div>
			                            </div>
			                            <div class="col-sm-4">
			                                <label>Colonia*</label>
			                                <div class="form-group">
			                                    <div class="form-line">
			                                        <input type="text" class="form-control" id="colonia_consultar" disabled>
			                                    </div>
			                                </div>
			                            </div>
			                            <div class="col-sm-4">
			                                <label>Municipio*</label>
			                                <div class="form-group">
			                                    <div class="form-line">
			                                        <input type="text" class="form-control" id="municipio_consultar" disabled>
			                                    </div>
			                                </div>
			                            </div>
			                            <div class="col-sm-12" style="padding: 0px">
				                            <div class="col-sm-4">
				                                <label>Ciudad*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control" id="ciudad_consultar" disabled>
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
				                                <label>Estado*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control" id="estado_consultar" disabled>
				                                    </div>
				                                </div>
				                            </div>
				                        </div>    
			                            <div class="col-md-12" style="margin-top: 20px;">
		                        			<h4>Cuenta de Usuario</h4>
		                        		</div>
			                            <div class="col-sm-4">
			                                <label>Correo Electrónico*</label>
			                                <div class="form-group">
			                                    <div class="form-line">
			                                        <input type="email" class="form-control" id="correo_usuario_consultar" disabled>
			                                    </div>
			                                </div>
			                            </div>
			                            <div class="col-sm-4" style="padding-bottom: 30px;">
		                            		<label>Tipo de Rol*</label>
	                                    	<select id="id_rol_consultar" disabled class="form-control">
                                           		<option value="" selected>Seleccione</option>
	                                    		<?php foreach ($roles_consulta as $rol): ?>
	                                    			<option value="<?=$rol['id_rol'];?>"><?=$rol['nombre_rol'];?></option>
	                                    		<?php endforeach ?>
	                                    	</select>
			                            </div>
			                            <div class="col-sm-4">
			                                <label for="avatar_usuario_registrar">Imagen</label>
			                                <div class="form-group">
			                                    <img id="imagen_consultar" src="" alt="Tu avatar" class="img-responsive ima_error" style="max-width: 30%;">
			                                </div>
			                            </div>
                            			<br>
                            			<div class="col-sm-4 col-sm-offset-5">
	                                        <button type="button" onclick="regresar('#cuadro3')" class="btn btn-primary waves-effect">Regresar</button>
		                                </div>
			                        </div>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        <!-- Cierre del cuadro de consultar usuario -->

		        <!-- Comienzo del cuadro de editar usuario -->
					<div class="row clearfix ocultar" id="cuadro4">
		                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                    <div class="card">
		                        <div class="header">
		                            <h2>Editar <?php echo $breadcrumbs[0]["nombre_lista_vista"]; ?></h2>
		                        </div>
		                        <div class="body">
		                        	<div class="table-responsive">
		                        		<div class="col-md-12" style="margin-top: 20px;">
		                        			<h4>Datos Personales</h4>
		                        		</div>
			                            <form name="form_usuario_actualizar" id="form_usuario_actualizar" method="post" enctype="multipart/form-data">
			                            	<div class="col-sm-4">
			                            		<label for="nombre_datos_personales_actualizar">Nombre(s)*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control mayusculas" name="nombre_datos_personales" id="nombre_datos_personales_actualizar" onkeypress='return sololetras(event)' maxlength="200" placeholder="P. EJ. LUIS RAÚL" required>
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
				                                <label for="apellido_p_datos_personales_actualizar">Apellido Paterno*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control mayusculas" maxlength="100" name="apellido_p_datos_personales" id="apellido_p_datos_personales_actualizar" placeholder="P. EJ. BELLO" required onkeypress='return sololetras(event)'>
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
				                                <label for="apellido_m_datos_personales_actualizar">Apellido Materno*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control mayusculas" maxlength="100" name="apellido_m_datos_personales" id="apellido_m_datos_personales_actualizar" placeholder="P. EJ. MENA" required onkeypress='return sololetras(event)'>
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
				                                <label for="fecha_nac_datos_personales_actualizar">Fecha de Nacimiento*</label>
				                                <div class="form-group">
				                                    <div class="form-line input-group fecha">
				                                        <input type="text" class="form-control" maxlength="10" name="fecha_nac_datos_personales" id="fecha_nac_datos_personales_actualizar" placeholder="dd-mm-yyyy" required>
				                                        <span class="input-group-addon">
									                        <span class="glyphicon glyphicon-calendar"></span>
									                    </span>
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
			                            		<label for="nacionalidad_datos_personales_actualizar">Nacionalidad*</label>
		                                    	<select name="nacionalidad_datos_personales" id="nacionalidad_datos_personales_actualizar" required class="form-control">
		                                    		<option value="" selected>Seleccione</option>
		                                    		<?php foreach ($nacionalidades as $nacionalidad): ?>
		                                    			<option value="<?=$nacionalidad->id_lista_valor;?>"><?=$nacionalidad->nombre_lista_valor;?></option>
		                                    		<?php endforeach ?>
		                                    	</select>
				                            </div>
				                            <div class="col-sm-4">
				                                <label for="curp_datos_personales_actualizar">C.U.R.P.*</label>
				                                <div class="form-group form-float">
				                                    <div class="form-line" id="validCurp">
				                                        <input type="text" class="form-control mayusculas" maxlength="18" name="curp_datos_personales" id="curp_datos_personales_actualizar" placeholder="P. EJ. BEML920313HCMLNS09" oninput="validarInputCurp(this)">
				                                    </div>
				                                    <span class="curpError text-danger"></span>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
				                                <label for="telefono_actualizar">Teléfono*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control telefono" name="telefono_principal_contacto" id="telefono_actualizar" placeholder="P. EJ.: +00 (000) 000-00-00">
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
			                            		<label for="edo_civil_datos_personales_actualizar">Estado Civil*</label>
		                                    	<select name="edo_civil_datos_personales" id="edo_civil_datos_personales_actualizar" required class="form-control">
		                                    		<option value="" selected>Seleccione</option>
		                                    		<?php foreach ($estadosCiviles as $estadoCivil): ?>
		                                    			<option value="<?=$estadoCivil->id_lista_valor;?>"><?=$estadoCivil->nombre_lista_valor;?></option>
		                                    		<?php endforeach ?>
		                                    	</select>
				                            </div>
				                            <div class="col-sm-4">
			                            		<label for="genero_datos_personales_actualizar">Género*</label>
		                                    	<select name="genero_datos_personales" id="genero_datos_personales_actualizar" required class="form-control">
		                                    		<option value="" selected>Seleccione</option>
		                                    		<?php foreach ($sexos as $sexo): ?>
		                                    			<option value="<?=$sexo->id_lista_valor;?>"><?=$sexo->nombre_lista_valor;?></option>
		                                    		<?php endforeach ?>
		                                    	</select>
				                            </div>
				                            <div class="col-md-12" style="margin-top: 20px;">
			                        			<h4>Datos de la Dirección</h4>
			                        		</div>
			                        		<!--
			                        		<div class="col-sm-4">
				                                <label for="direccion_contacto_actualizar">Domicilio</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control mayusculas" maxlength="200" name="direccion_contacto" id="direccion_contacto_actualizar" placeholder="P. EJ. INSURGENTE">
				                                    </div>
				                                </div>
				                            </div>
				                        	-->
				                            <div class="col-sm-4">
				                                <label for="calle_contacto_actualizar">Calle</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control mayusculas" maxlength="100" name="calle_contacto" id="calle_contacto_actualizar" placeholder="P. EJ. PRIMAVERA">
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
				                                <label for="exterior_contacto_actualizar">Número Exterior</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control mayusculas" maxlength="30" name="exterior_contacto" id="exterior_contacto_actualizar" placeholder="P. EJ. 33">
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
				                                <label for="interior_contacto_actualizar">Número Interior</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control mayusculas" maxlength="30" name="interior_contacto" id="interior_contacto_actualizar" placeholder="P. EJ. 2">
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
				                                <label for="codigo_postal_actualizar">Código Postal*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control" id="codigo_postal_actualizar" onkeypress='return solonumeros(event)' maxlength="6" onchange="buscarCodigosUs(this.value, 'edit')">
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4" style="padding-bottom: 10px;">
				                                <label for="colonia_actualizar">Colonia*</label>
		                                        <select id="colonia_actualizar" required class="form-control form-group" name="colonia">
		                                        	<!--<option value="">Seleccione</option>-->
		                                        </select>
				                            </div>
				                            <div class="col-sm-4">
				                                <label for="municipio_actualizar">Municipio*</label>
		                                        <select id="municipio_actualizar" required class="form-control form-group" name="municipio">
		                                        	<!--<option value="">Seleccione</option>-->
		                                        </select>
				                            </div>
				                            <div class="col-sm-12" style="padding: 0px">
					                            <div class="col-sm-4">
					                                <label for="ciudad_actualizar">Ciudad*</label>
			                                        <select id="ciudad_actualizar" required class="form-control form-group" name="ciudad">
			                                        	<!--<option value="">Seleccione</option>-->
			                                        </select>
					                            </div>
					                            <div class="col-sm-4">
					                            	<label for="estado_actualizar">Estado*</label>
			                                        <select id="estado_actualizar" required class="form-control form-group" name="estado">
			                                        	<!--<option value="">Seleccione</option>-->
			                                        </select>
					                            </div>
					                        </div>    
				                            <div class="col-md-12" style="margin-top: 20px;">
			                        			<h4>Cuenta de Usuario</h4>
			                        		</div>
				                            <div class="col-sm-4">
				                                <label for="correo_usuario_actualizar">Correo Electrónico*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="email" class="form-control" maxlength="100" name="correo_usuario" id="correo_usuario_actualizar" placeholder="P. EJ. ejemplo@dominio.com">
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
				                                <label for="correo_confirmar_actualizar">Confirmar Correo Electrónico*</label>
				                                <div class="form-group form-float">
				                                    <div class="form-line" id="correoConfirmarActualizar">
				                                        <input type="email" class="form-control" maxlength="100" name="correo_confirmar" id="correo_confirmar_actualizar" placeholder="P. EJ. ejemplo@dominio.com" oninput="validarCorreo('#correo_usuario_actualizar', '#correo_confirmar_actualizar','#correoConfirmarActualizar')">
				                                    </div>
				                                    <span class="text-danger correoError"></span>
				                                </div>
				                            </div>
				                            <div class="col-sm-4" style="padding-bottom: 30px;">
			                            		<label for="id_rol_actualizar">Tipo de Rol*</label>
		                                    	<select name="id_rol" id="id_rol_actualizar" required class="form-control rol">
		                                    		<option value="" selected>Seleccione</option>
		                                    		<?php foreach ($roles_consulta as $rol): ?>
		                                    			<option value="<?=$rol['id_rol'];?>" status="<?=$rol['status'] ?>"><?=$rol['nombre_rol'];?></option>
		                                    		<?php endforeach ?>

		                                    	</select>
				                            </div>

				                            <div class="col-sm-4">
				                                <label for="clave_usuario_actualizar">Contraseña Nueva</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="password" maxlength="50" class="form-control" name="clave_usuario" id="clave_usuario_actualizar" placeholder="Escribir contraseña">
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
				                                <label for="repetir_actualizar">Repetir Contraseña</label>
				                                <div class="form-group form-float">
				                                    <div class="form-line" id="repetirContraseñaActualizar">
				                                        <input type="password" maxlength="50" class="form-control" name="repetir_clave" id="repetir_actualizar" placeholder="Repetir Contraseña" oninput="validarClave('#clave_usuario_actualizar', '#repetir_actualizar','#repetirContraseñaActualizar')">
				                                    </div>
				                                    <span class="text-danger claveError"></span>
				                                </div>
				                            </div>

				                            <div class="col-sm-12">
				                                <label for="avatar_usuario_actualizar">Subir Imagen</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="file" class="form-control" id="avatar_usuario_actualizar" name="avatar_usuario" onchange="readURL(this, '#imagen_actualizar', '#avatar_usuario_actualizar')">
				                                    </div>
				                                    <img id="imagen_actualizar" src="http://placehold.it/180" alt="Tu avatar" class="img-responsive ima_error" style="max-width: 15%;">
				                                </div>
				                            </div>
				                            <input type="hidden" name="id_usuario" id="id_usuario_actualizar">
				                            <input type="hidden" name="id_contacto" id="id_contacto_actualizar">
				                            <input type="hidden" name="id_datos_personales" id="id_datos_personales_actualizar">
                                			<br>
                                			<div class="col-sm-4 col-sm-offset-5">
		                                        <button type="button" onclick="regresar('#cuadro4')" class="btn btn-primary waves-effect">Regresar</button>
		                                        <input type="submit" value="Guardar" class="btn btn-success waves-effect">
			                                </div>
			                            </form>
			                        </div>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        <!-- Cierre del cuadro de editar usuario -->
			</div>
		</section>
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
    <script src="<?=base_url();?>assets/cpanel/Usuarios/js/usuarios.js"></script>
    <script src="<?=base_url();?>assets/template/plugins/momentjs/moment.js"></script>
    <script src="<?=base_url();?>assets/template/plugins/bootstrap-datetimepicker-master/js/bootstrap-datetimepicker.min.js"></script>
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
