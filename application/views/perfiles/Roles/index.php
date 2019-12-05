<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
	<link href="<?=base_url();?>assets/template/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
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
		                                <button class="btn btn-primary ocultar registrar waves-effect" onclick="nuevoRol()"><i class='fa fa-plus-circle' style="color: white; font-size: 18px;"></i> | Nuevo</button>
		                            </ul>
		                        </div>
		                        <div class="body">
		                            <div class="table-responsive">
		                                <table class="table table-bordered table-striped table-hover" id="tabla">
		                                    <thead>
		                                        <tr>
		                                        	<th style="text-align: center; padding: 0px 10px 0px 5px;"><input type="checkbox" id="checkall" class="chk-col-blue"/><label for="checkall"></label></th>
		                                        	<th>Acciones</th>
		                                            <th>Nombre de Rol</th>
		                                            <th>Descripción</th>
		                                            <th>Funciones</th>
		                                            <th>Fecha de Registro</th>
		                                            <th>Registrado Por</th>
		                                        </tr>
		                                    </thead>
		                                    <tbody></tbody>
		                                </table>
		                                <div class="col-md-2 ocultar eliminar">
		                                	<button class="btn btn-danger waves-effect" onclick="eliminarMultipleRoles('Roles/eliminar_multiple_roles')">Eliminar seleccionados</button>
		                                </div>
		                                <div class="col-md-2 ocultar actualizar">
		                                	<button class="btn btn-warning waves-effect" onclick="statusMultiple('Roles/status_multiple_roles', 1, 'activar')">Activar seleccionados</button>
		                                </div>
		                                <div class="col-md-2 ocultar actualizar">
		                                	<button class="btn btn-warning waves-effect" onclick="statusMultiple('Roles/status_multiple_roles', 2, 'desactivar')">Desactivar seleccionados</button>
		                                </div>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        <!-- Cierre del cuadro de la tabla -->

		        <!-- Comienzo del cuadro de registrar Modulo -->
					<div class="row clearfix ocultar" id="cuadro2">
		                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                    <div class="card">
		                        <div class="header">
		                            <h2>Registro de <?php echo $breadcrumbs[0]["nombre_lista_vista"]; ?></h2>
		                        </div>
		                        <div class="body">
		                        	<div class="table-responsive">
			                            <form name="form_rol_registrar" id="form_rol_registrar" method="post">
			                            	<div class="col-sm-6">
			                            		<label for="nombre_rol_registrar">Nombre de Rol*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control mayusculas" name="nombre_rol" id="nombre_rol_registrar" placeholder="P. EJ. ROL" required>
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-6">
				                                <label for="descripcion_rol_registrar">Descripción</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control mayusculas" name="descripcion_rol" id="descripcion_rol_registrar" placeholder="P. EJ. Describir funciones">
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-12">
				                            	<div class="col-sm-4">
					                                <label for="modulo_vista_registrar">Módulos</label>
			                                        <select id="modulo_vista_registrar" class="form-control form-group" onchange="buscarFunciones(this.value, 'lista_vista_registrar');">
			                                        	<option value="">Seleccione</option>
			                                        	<?php /*foreach($modulos as $modulo): ?>
			                                        		<option value="<?php echo $modulo->id_modulo_vista ?>"><?php echo $modulo->nombre_modulo_vista?></option>
			                                        	<?php endforeach */?>
			                                        	<?php foreach($modulos as $modulo): ?>
			                                        		<option value="<?php echo($modulo["_id"]->{'$id'}); ?>"><?php echo $modulo["nombre_modulo_vista"]; ?></option>
			                                        	<?php endforeach ?>
			                                        </select>
					                            </div>
					                            <div class="col-sm-4">
					                                <label for="lista_vista_registrar">Funciones</label>
			                                        <select id="lista_vista_registrar" class="form-control form-group">
			                                        	<option value="">Seleccione</option>
			                                        </select>
					                            </div>
					                            <div class="col-sm-2" style="padding-top: 25px;">
					                            	<button type="button" class="btn btn-primary waves-effect" onclick="agregarListaVista('#lista_vista_registrar', '#tableRegistrar', '#modulo_vista_registrar')">Agregar</button>
					                            </div>
					                            <div class="col-sm-12">
					                            	<table class="table table-bordered table-striped table-hover" id="tableRegistrar">
					                            		<thead>
					                            			<tr>
					                            				<th>Función</th>
					                            				<th>Consulta General</th>
					                            				<th>Consulta Detallada</th>
					                            				<th>Registrar</th>
					                            				<th>Actualizar</th>
					                            				<th>Eliminar</th>
					                            				<th>&nbsp;</th>
					                            			</tr>
					                            		</thead>
					                            		<tbody></tbody>
					                            	</table>
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
		        <!-- Cierre del cuadro de registrar Modulo -->

		        <!-- Comienzo del cuadro de consultar Modulo -->
					<div class="row clearfix ocultar" id="cuadro3">
		                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                    <div class="card">
		                        <div class="header">
		                            <h2>Consultar <?php echo $breadcrumbs[0]["nombre_lista_vista"]; ?></h2>
		                        </div>
		                        <div class="body">
		                        	<div class="table-responsive">
		                            	<div class="col-sm-6">
		                            		<label for="nombre_rol_consultar">Nombre de Rol*</label>
			                                <div class="form-group">
			                                    <div class="form-line">
			                                        <input type="text" class="form-control mayusculas" id="nombre_rol_consultar" disabled>
			                                    </div>
			                                </div>
			                            </div>
			                            <div class="col-sm-6">
			                                <label for="descripcion_rol_consultar">Descripción</label>
			                                <div class="form-group">
			                                    <div class="form-line">
			                                        <input type="text" class="form-control mayusculas" id="descripcion_rol_consultar" disabled>
			                                    </div>
			                                </div>
			                            </div>
			                            <div class="col-sm-12" id="operaciones_consultar"></div>
                            			<br>
                            			<div class="col-sm-2 col-sm-offset-5">
	                                        <button type="button" onclick="regresar('#cuadro3')" class="btn btn-primary waves-effect">Regresar</button>
		                                </div>
			                        </div>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        <!-- Cierre del cuadro de consultar Modulo -->

		        <!-- Comienzo del cuadro de editar Modulo -->
					<div class="row clearfix ocultar" id="cuadro4">
		                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                    <div class="card">
		                        <div class="header">
		                            <h2>Editar de <?php echo $breadcrumbs[0]["nombre_lista_vista"]; ?></h2>
		                        </div>
		                        <div class="body">
		                        	<div class="table-responsive">
			                            <form name="form_rol_actualizar" id="form_rol_actualizar" method="post">
			                            	<div class="col-sm-6">
			                            		<label for="nombre_rol_actualizar">Nombre de Rol*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control mayusculas" name="nombre_rol" id="nombre_rol_actualizar" placeholder="P. EJ. Rol" required>
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-6">
				                                <label for="descripcion_rol_actualizar">Descripción</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control mayusculas" name="descripcion_rol_actualizar" id="descripcion_rol_actualizar" placeholder="P. EJ. Describir funciones">
				                                    </div>
				                                </div>
				                            </div>
				                            <input type="hidden" name="id_rol" id="id_rol_actualizar">
				                            <div id="esperarLoading" class="col-sm-12"></div>
				                            <div class="col-sm-12 ocultar" id="listarRoles">
				                            	<div class="col-sm-4">
					                                <label for="modulo_vista_actualizar">Módulos</label>
			                                        <select id="modulo_vista_actualizar" class="form-control form-group" onchange="buscarFunciones(this.value, 'lista_vista_actualizar');">
			                                        	<option value="">Seleccione</option>
			                                        	<!--<?php foreach($modulos as $modulo): ?>
			                                        		<option value="<?php echo $modulo->id_modulo_vista ?>"><?php echo $modulo->nombre_modulo_vista?></option>
			                                        	<?php endforeach ?>-->
			                                        	<?php foreach($modulos as $modulo): ?>
			                                        		<option value="<?php echo($modulo["_id"]->{'$id'}); ?>"><?php echo $modulo["nombre_modulo_vista"]; ?></option>
			                                        	<?php endforeach ?>
			                                        </select>
					                            </div>
					                            <div class="col-sm-4">
					                                <label for="lista_vista_actualizar">Funciones</label>
			                                        <select id="lista_vista_actualizar" class="form-control form-group">
			                                        	<option value="">Seleccione</option>
			                                        </select>
					                            </div>
					                            <div class="col-sm-2" style="padding-top: 25px;">
					                            	<button type="button" class="btn btn-primary waves-effect" onclick="agregarListaVista('#lista_vista_actualizar', '#tableActualizar', '#modulo_vista_actualizar')">Agregar</button>
					                            </div>
					                            <div class="col-sm-12">
					                            	<table class="table table-bordered table-striped table-hover" id="tableActualizar">
					                            		<thead>
					                            			<tr>
					                            				<th>Función</th>
					                            				<th>Consulta General</th>
					                            				<th>Consulta Detallada</th>
					                            				<th>Registrar</th>
					                            				<th>Actualizar</th>
					                            				<th>Eliminar</th>
					                            				<th>&nbsp;</th>
					                            			</tr>
					                            		</thead>
					                            		<tbody></tbody>
					                            	</table>
					                            </div>
				                            </div>
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
		        <!-- Cierre del cuadro de editar Modulo -->

		        <!-- Comienzo deñ Modal de Operaciones -->
					<div class="modal fade" id="modalOperaciones" tabindex="-1" role="dialog">
		                <div class="modal-dialog modal-lg" role="document">
		                    <div class="modal-content">
		                        <div class="modal-header">
		                            <h4 class="modal-title" id="largeModalLabel">Funciones</h4>
		                        </div>
		                        <div class="modal-body" id="resultados"></div>
		                        <div class="modal-footer">
		                            <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">Cerrar</button>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        <!-- Cierre del Modal de Operaciones -->
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
	<script src="<?=base_url();?>assets/cpanel/Roles/js/roles.js"></script>
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
