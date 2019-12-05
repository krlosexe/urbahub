<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
	<link href="<?=base_url();?>assets/template/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
	<link href="<?=base_url();?>assets/template/plugins/bootstrap-datetimepicker-master/css/bootstrap-datetimepicker.css" rel="stylesheet" />
	<body class="theme-blue">
		<input type="hidden" id="ruta" value="<?=base_url();?>" name="ruta">
		<section class="content">
	        <div class="container-fluid">
	        	<div id="alertas" style="display: <?=$mensaje_result['mostrar']?>;"><div class="alert alert-<?=$mensaje_result['tipo']?>" role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><?=$mensaje_result['texto']?></div></div>

		        <!-- Comienzo del cuadro de editar usuario -->
					<div class="row clearfix" id="cuadro4">
		                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                    <div class="card">
		                        <div class="header">
		                            <h2>Editar Perfil</h2>
		                        </div>
		                        <div class="body">
		                        	<div class="table-responsive">
		                        		<div class="col-md-12" style="margin-top: 20px;">
		                        			<h4>Datos Personales</h4>
		                        		</div>
			                            <form name="form_usuario_actualizar" id="form_usuario_actualizar" method="post" enctype="multipart/form-data" action="<?=base_url()."perfil"?>">
			                            	<div class="col-sm-4">
			                            		<label for="nombre_datos_personales_actualizar">Nombre(s)*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input disabled value="<?=$arreglo_datos["nombre_datos_personales"] ?>" type="text" class="form-control mayusculas" maxlength="200" name="nombre_datos_personales" id="nombre_datos_personales_actualizar" onkeypress='return sololetras(event)' placeholder="P. EJ. LUIS RAÚL" required>
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
				                                <label for="apellido_p_datos_personales_actualizar">Apellido Paterno*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input disabled value="<?=$arreglo_datos["apellido_p_datos_personales"]?>" type="text" class="form-control mayusculas" maxlength="100" name="apellido_p_datos_personales" id="apellido_p_datos_personales_actualizar" placeholder="P. EJ. BELLO" required onkeypress='return sololetras(event)'>
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
				                                <label for="apellido_m_datos_personales_actualizar">Apellido Materno*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input disabled value="<?=$arreglo_datos["apellido_m_datos_personales"] ?>" type="text" class="form-control mayusculas" maxlength="100" name="apellido_m_datos_personales" id="apellido_m_datos_personales_actualizar" placeholder="P. EJ. MENA" required onkeypress='return sololetras(event)'>
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
				                                <label for="fecha_nac_datos_personales_actualizar">Fecha de Nacimiento*</label>
				                                <div class="form-group">
				                                    <div class="form-line input-group fecha">
				                                        <input disabled value="<?=date("d-m-Y", strtotime($arreglo_datos["fecha_nac_datos_personales"]))?>" type="text" class="form-control" maxlength="10" name="fecha_nac_datos_personales" id="fecha_nac_datos_personales_actualizar" placeholder="dd-mm-yyyy" required>
				                                        <span class="input-group-addon">
									                        <span class="glyphicon glyphicon-calendar"></span>
									                    </span>
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
			                            		<label for="nacionalidad_datos_personales_actualizar">Nacionalidad*</label>
		                                    	<select disabled name="nacionalidad_datos_personales" id="nacionalidad_datos_personales_actualizar" required class="form-control">
		                                    		<option value="">Seleccione</option>
		                                    		<?php foreach ($nacionalidades as $nacionalidad): ?>
		                                    			<option value="<?=$nacionalidad->id_lista_valor;?>" <?=$nacionalidad->id_lista_valor==$arreglo_datos["nacionalidad_datos_personales"]?"selected":""?> ><?=$nacionalidad->nombre_lista_valor;?></option>
		                                    		<?php endforeach ?>
		                                    	</select>
				                            </div>
				                            <div class="col-sm-4">
				                                <label for="curp_datos_personales_actualizar">C.U.R.P.*</label>
				                                <div class="form-group form-float">
				                                    <div class="form-line" id="validCurp">
				                                        <input disabled value='<?=$arreglo_datos["curp_datos_personales"]?>' type="text" class="form-control mayusculas" maxlength="18" name="curp_datos_personales" id="curp_datos_personales_actualizar" placeholder="P. EJ. BEML920313HCMLNS09" oninput="validarInputCurp(this)" required>
				                                    </div>
				                                    <span class="curpError text-danger"></span>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
				                                <label for="telefono_actualizar">Teléfono*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input value='<?=$arreglo_datos["telefono_principal_contacto"]?>' type="text" class="form-control telefono" name="telefono_principal_contacto" id="telefono_actualizar" placeholder="P. EJ.: +00 (000) 000-00-00" required>
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
			                            		<label for="edo_civil_datos_personales_actualizar">Estado Civil*</label>
		                                    	<select disabled name="edo_civil_datos_personales" id="edo_civil_datos_personales_actualizar" required class="form-control">
		                                    		<option value="" selected>Seleccione</option>
		                                    		<?php foreach ($estadosCiviles as $estadoCivil): ?>
		                                    			<option value="<?=$estadoCivil->id_lista_valor;?>" <?=$estadoCivil->id_lista_valor==$arreglo_datos["edo_civil_datos_personales"]?"selected":""?>><?=$estadoCivil->nombre_lista_valor;?></option>
		                                    		<?php endforeach ?>
		                                    	</select>
				                            </div>
				                            <div class="col-sm-4">
			                            		<label for="genero_datos_personales_actualizar">Género*</label>
		                                    	<select disabled name="genero_datos_personales" id="genero_datos_personales_actualizar" required class="form-control">
		                                    		<option value="" selected>Seleccione</option>
		                                    		<?php foreach ($sexos as $sexo): ?>
		                                    			<option value="<?=$sexo->id_lista_valor;?>" <?=$sexo->id_lista_valor==$arreglo_datos["genero_datos_personales"]?"selected":""?>><?=$sexo->nombre_lista_valor;?></option>
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
				                                        <input value='<?=$arreglo_datos["direccion_contacto"]?>' type="text" class="form-control mayusculas" maxlength="200" name="direccion_contacto" id="direccion_contacto_actualizar" placeholder="P. EJ. INSURGENTE">
				                                    </div>
				                                </div>
				                            </div>
				                        -->
				                            <div class="col-sm-4">
				                                <label for="calle_contacto_actualizar">Calle</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input value='<?=$arreglo_datos["calle_contacto"]?>' type="text" class="form-control mayusculas" maxlength="100" name="calle_contacto" id="calle_contacto_actualizar" placeholder="P. EJ. PRIMAVERA">
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
				                                <label for="exterior_contacto_actualizar">Número Exterior</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input value='<?=$arreglo_datos["exterior_contacto"]?>' type="text" class="form-control mayusculas" maxlength="30" name="exterior_contacto" id="exterior_contacto_actualizar" placeholder="P. EJ. 33">
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
				                                <label for="interior_contacto_actualizar">Número Interior</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input value='<?=$arreglo_datos["interior_contacto"]?>' type="text" class="form-control mayusculas" maxlength="30" name="interior_contacto" id="interior_contacto_actualizar" placeholder="P. EJ. 2">
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
				                                <label for="codigo_postal_actualizar">Código Postal*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input value="<?=$arreglo_sepomex['d_codigo']?>" type="text" class="form-control" id="codigo_postal_actualizar" onkeypress='return solonumeros(event)' maxlength="6" onchange="buscarCodigos(this.value, 'edit')" required>
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4" style="padding-bottom: 10px;">
				                                <label for="colonia_actualizar">Colonia*</label>
		                                        <select id="colonia_actualizar" required class="form-control form-group" name="colonia">
		                                        	<option value="<?=$arreglo_datos["id_codigo_postal"]?>" selected><?=$super_sepomex["colonias"][0]->d_asenta?></option>
		                                        </select>
				                            </div>
				                            <div class="col-sm-4">
				                                <label for="municipio_actualizar">Municipio*</label>
		                                        <select id="municipio_actualizar" required class="form-control form-group" name="municipio">
		                                        	<option value="<?php  if($super_sepomex['municipios'][0]->d_mnpio!=''){echo $super_sepomex['municipios'][0]->d_mnpio;}else{echo 'N/A';}?>" selected><?php  if($super_sepomex["municipios"][0]->d_mnpio!=""){echo $super_sepomex["municipios"][0]->d_mnpio;}else{echo "NO APLICA";}?></option>
		                                        </select>
				                            </div>
				                            <div class="col-sm-12" style="padding: 0px;">
					                            <div class="col-sm-4">
					                                <label for="ciudad_actualizar">Ciudad*</label>
			                                        <select id="ciudad_actualizar" required class="form-control form-group" name="ciudad">
			                                        	<option value="<?php if($super_sepomex['ciudades'][0]->d_ciudad!=''){echo $super_sepomex['ciudades'][0]->d_ciudad;}else{ echo 'N/A';}?>" selected><?php if($super_sepomex["ciudades"][0]->d_ciudad!=""){ echo $super_sepomex["ciudades"][0]->d_ciudad;}else{ echo "NO APLICA";}?></option>
			                                        </select>
					                            </div>
					                            <div class="col-sm-4">
					                            	<label for="estado_actualizar">Estado*</label>
			                                        <select id="estado_actualizar" required class="form-control form-group" name="estado">
			                                        	<option value="<?php if($super_sepomex['estados'][0]->d_estado!=''){echo $super_sepomex['estados'][0]->d_estado;}else{echo 'N/A';}?>" selected><?php if($super_sepomex["estados"][0]->d_estado!=""){echo $super_sepomex["estados"][0]->d_estado;}else{echo "NO APLICA";}?></option>
			                                        </select>
					                            </div>
					                        </div>    
				                            <div class="col-sm-4"></div>
				                            <div class="col-md-12" style="margin-top: 20px;">
			                        			<h4>Cuenta de Usuario</h4>
			                        		</div>
				                            <div class="col-sm-4">
				                                <label for="correo_usuario_actualizar">Correo Electrónico*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input value='<?=$arreglo_datos["correo_usuario"]?>' type="email" class="form-control" maxlength="100" name="correo_usuario" id="correo_usuario_actualizar" placeholder="P. EJ. ejemplo@dominio.com" required>
				                                    </div>
				                                </div>
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
				                                    <img id="imagen_actualizar" src="<?=base_url()."assets/cpanel/Usuarios/images/".$arreglo_datos["avatar_usuario"]?>" alt="Tu avatar" class="img-responsive ima_error" style="max-width: 15%;">
				                                </div>
				                            </div>
				                            <input type="hidden" name="id_contacto" id="id_contacto_actualizar" alue="<?=$arreglo_datos["id_contacto"]?>">
				                            <input type="hidden" name="id_datos_personales" id="id_datos_personales_actualizar" value="<?=$arreglo_datos["id_datos_personales"]?>">
                                			<br>
                                			<div class="col-sm-4 col-sm-offset-5">
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
    <script src="<?=base_url();?>assets/cpanel/Usuarios/js/perfil.js"></script>
    <script src="<?=base_url();?>assets/template/plugins/momentjs/moment.js"></script>
    <script src="<?=base_url();?>assets/template/plugins/bootstrap-datetimepicker-master/js/bootstrap-datetimepicker.min.js"></script>
</html>
