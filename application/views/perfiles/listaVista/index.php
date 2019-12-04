<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
	<link href="<?=base_url();?>assets/template/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
	<?php 
	if(($permiso[0]->general==1 && $permiso[0]->detallada==1 && $permiso[0]->registrar==1 && $permiso[0]->actualizar==1 && $permiso[0]->eliminar==1) OR $permiso[0]->status==false): ?>
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
		                                <button class="btn btn-primary ocultar registrar waves-effect" onclick="nuevoListaVista()"><i class='fa fa-plus-circle' style="color: white; font-size: 18px;"></i> | Nuevo</button>
		                            </ul>
		                        </div>
		                        <div class="body">
		                            <div class="table-responsive">
		                                <table class="table table-bordered table-striped table-hover" id="tabla">
		                                    <thead>
		                                        <tr>
		                                        	<th style="text-align: center; padding: 0px 10px 0px 5px;"><input type="checkbox" id="checkall" class="chk-col-blue"/><label for="checkall"></label></th>
		                                        	<th style="">Acciones</th>
		                                            <th>Nombre Función</th>
		                                            <th>Descripción</th>
		                                            <th>Nombre Modulo</th>
		                                            <th>Posición</th>
		                                            <th>Fecha de Registro</th>
		                                            <th>Registrado Por</th>
		                                            
		                                        </tr>
		                                    </thead>
		                                    <tbody></tbody>
		                                </table>
		                                <div class="col-md-2 ocultar eliminar">
		                                	<button class="btn btn-danger waves-effect" onclick="eliminarMultiple('ListaVista/eliminar_multiple_lista_vista')">Eliminar seleccionados</button>
		                                </div>
		                                <div class="col-md-2 ocultar actualizar">
		                                	<button class="btn btn-warning waves-effect" onclick="statusMultiple('ListaVista/status_multiple_lista_vista', 1, 'activar')">Activar seleccionados</button>
		                                </div>
		                                <div class="col-md-2 ocultar actualizar">
		                                	<button class="btn btn-warning waves-effect" onclick="statusMultiple('ListaVista/status_multiple_lista_vista', 2, 'desactivar')">Desactivar seleccionados</button>
		                                </div>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        <!-- Cierre del cuadro de la tabla -->

		        <!-- Comienzo del cuadro de registrar Lista Vista -->
					<div class="row clearfix ocultar" id="cuadro2">
		                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                    <div class="card">
		                        <div class="header">
		                            <h2>Registro de <?php echo $breadcrumbs[0]["nombre_lista_vista"]; ?></h2>
		                        </div>
		                        <div class="body">
		                        	<div class="table-responsive">
			                            <form name="form_lista_vista_registrar" id="form_lista_vista_registrar" method="post">
			                            	<div class="col-sm-4">
			                            		<label for="nombre_lista_vista_registrar">Nombre*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control" name="nombre_lista_vista" id="nombre_lista_vista_registrar" placeholder="P. EJ. NOMBRE" required>
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
				                                <label for="descripcion_lista_vista_registrar">Descripción</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control mayusculas" name="descripcion_lista_vista" id="descripcion_lista_vista_registrar" placeholder="P. EJ. Describir funciones">
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
				                                <label for="url_lista_vista_registrar">URL*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control" name="url_lista_vista" id="url_lista_vista_registrar" required placeholder="P. EJ. URL">
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
				                                <label for="id_modulo_vista_registrar">Modulo*</label>
		                                        <select id="id_modulo_vista_registrar" required class="form-control form-group" name="id_modulo_vista" onchange="contador_listaVista(this.value, 'registrar', 0)">
		                                        	<option value="">Seleccione</option>
		                                        	<?php foreach($modulos as $modulo): ?>
		                                        		<?php if ($modulo['status']==true){ ?>
		                                        		<option value="<?php echo($modulo["_id"]->{'$id'}); ?>"  status="<?=$modulo['status'] ?>"><?php echo $modulo["nombre_modulo_vista"]; ?></option>
		                                        		<?php } ?>
		                                        	<?php endforeach ?>
		                                        </select>
				                            </div>
				                            <div class="col-sm-4">
				                                <label for="posicion_lista_vista_registrar">Posición*</label>
		                                        <select id="posicion_lista_vista_registrar" required class="form-control form-group" name="posicion_lista_vista">
		                                        	<option value="">Seleccione</option>
		                                        </select>
		                                        <span class="text-info">Debe seleccionar un modulo</span>
				                            </div>
				                            <div class="col-sm-4">
				                                <label for="visibilidad_lista_vista_registrar">Visible</label>
		                                        <select id="visibilidad_lista_vista_registrar" class="form-control form-group" name="visibilidad_lista_vista">
		                                        	<option value="">Seleccione</option>
		                                        	<option value="0">SI</option>
		                                        	<option value="1">NO</option>
		                                        </select>
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
		        <!-- Cierre del cuadro de registrar Lista Vista -->

		        <!-- Comienzo del cuadro de consultar Lista Vista -->
					<div class="row clearfix ocultar" id="cuadro3">
		                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                    <div class="card">
		                        <div class="header">
		                            <h2>Consultar <?php echo $breadcrumbs[0]["nombre_lista_vista"]; ?></h2>
		                        </div>
		                        <div class="body">
		                        	<div class="table-responsive">
		                            	<div class="col-sm-4">
		                            		<label for="nombre_lista_vista_consultar">Nombre*</label>
			                                <div class="form-group">
			                                    <div class="form-line">
			                                        <input type="text" class="form-control mayusculas" id="nombre_lista_vista_consultar" disabled>
			                                    </div>
			                                </div>
			                            </div>
			                            <div class="col-sm-4">
			                                <label for="descripcion_lista_vista_consultar">Descripción</label>
			                                <div class="form-group">
			                                    <div class="form-line">
			                                        <input type="text" class="form-control mayusculas" id="descripcion_lista_vista_consultar" disabled>
			                                    </div>
			                                </div>
			                            </div>
			                            <div class="col-sm-4">
			                                <label for="url_lista_vista_consultar">URL*</label>
			                                <div class="form-group">
			                                    <div class="form-line">
			                                        <input type="text" class="form-control mayusculas" id="url_lista_vista_consultar" disabled>
			                                    </div>
			                                </div>
			                            </div>
			                            <div class="col-sm-4">
			                                <label for="id_modulo_vista_consultar">Modulo*</label>
	                                        <select id="id_modulo_vista_consultar" required class="form-control form-group" disabled>	                                      
	                                        	<option value="">Seleccione</option>
	                                        	<?php foreach($modulos as $modulo): ?>
	                                        		<option value="<?php echo($modulo["_id"]->{'$id'}); ?>" status="<?=$modulo['status'] ?>"><?php echo $modulo["nombre_modulo_vista"]; ?></option>
	                                        	<?php endforeach ?>
	                                        </select>
			                            </div>
			                            <div class="col-sm-4">
			                                <label for="posicion_lista_vista_consultar">Posición*</label>
	                                        <select id="posicion_lista_vista_consultar" required class="form-control form-group" disabled>
	                                        	<option value="">Seleccione</option>
	                                        </select>
			                            </div>
			                            <div class="col-sm-4">
			                                <label for="visibilidad_lista_vista_consultar">Visible</label>
	                                        <select id="visibilidad_lista_vista_consultar" required class="form-control form-group" disabled>
	                                        	<option value="">Seleccione</option>
	                                        	<option value="0">SI</option>
	                                        	<option value="1">NO</option>
	                                        </select>
			                            </div>
                            			<br>
                            			<div class="col-sm-2 col-sm-offset-5">
	                                        <button type="button" onclick="regresar('#cuadro3')" class="btn btn-primary waves-effect">Regresar</button>
		                                </div>
			                        </div>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        <!-- Cierre del cuadro de consultar Lista Vista -->

		        <!-- Comienzo del cuadro de editar Lista Vista -->
					<div class="row clearfix ocultar" id="cuadro4">
		                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                    <div class="card">
		                        <div class="header">
		                            <h2>Editar de <?php echo $breadcrumbs[0]["nombre_lista_vista"]; ?></h2>
		                        </div>
		                        <div class="body">
		                        	<div class="table-responsive">
			                            <form name="form_lista_vista_actualizar" id="form_lista_vista_actualizar" method="post">
			                            	<div class="col-sm-4">
			                            		<label for="nombre_lista_vista_actualizar">Nombre*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control" name="nombre_lista_vista" id="nombre_lista_vista_actualizar" placeholder="P. EJ. NOMBRE" required>
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
				                                <label for="descripcion_lista_vista_actualizar">Descripción</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control mayusculas" name="descripcion_lista_vista" id="descripcion_lista_vista_actualizar" placeholder="P. EJ. Describir funciones">
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
				                                <label for="url_lista_vista_actualizar">URL*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control" name="url_lista_vista" id="url_lista_vista_actualizar" required placeholder="P. EJ. URL">
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
				                                <label for="id_modulo_vista_actualizar">Modulo*</label>
		                                        <select id="id_modulo_vista_actualizar" required class="form-control form-group" name="id_modulo_vista" onchange="contador_listaVista(this.value, 'actualizar', 0)">
		                                        	<option value="">Seleccione</option>
		                                        	<?php foreach($modulos as $modulo): ?>
		                                        		<option value="<?php echo($modulo["_id"]->{'$id'}); ?>"  status="<?=$modulo['status'] ?>"><?php echo $modulo["nombre_modulo_vista"]; ?></option>
		                                        	<?php endforeach ?>
		                                        </select>
				                            </div>
				                            <div class="col-sm-4">
				                                <label for="posicion_lista_vista_actualizar">Posición*</label>
		                                        <select id="posicion_lista_vista_actualizar" required class="form-control form-group" name="posicion_lista_vista">
		                                        	<option value="">Seleccione</option>
		                                        </select>
		                                        <span class="text-info">Debe seleccionar un modulo</span>
				                            </div>
				                            <div class="col-sm-4">
				                                <label for="visibilidad_lista_vista_actualizar">Visible</label>
		                                        <select id="visibilidad_lista_vista_actualizar" class="form-control form-group" name="visibilidad_lista_vista">
		                                        	<option value="">Seleccione</option>
		                                        	<option value="0">SI</option>
		                                        	<option value="1">NO</option>
		                                        </select>
				                            </div>
				                            <input type="hidden" name="id_lista_vista" id="id_lista_vista_actualizar">
				                            <input type="hidden" name="id_modulo_vista_hidden" id="id_modulo_vista_hidden">
				                            <input type="hidden" name="posicion_lista_vista_hidden" id="posicion_lista_vista_hidden">
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
		        <!-- Cierre del cuadro de editar Lista Vista -->
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
	<script src="<?=base_url();?>assets/cpanel/ListaVista/js/listaVista.js"></script>
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
