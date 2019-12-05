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
		                                <button class="btn btn-primary waves-effect registrar ocultar" onclick="nuevoDescuento()"><i class='fa fa-plus-circle' style="color: white; font-size: 18px;"></i> | Nuevo</button>
		                            </ul>
		                        </div>
		                        <div class="body">
		                            <div class="table-responsive">
		                                <table class="table table-bordered table-striped table-hover" id="tabla">
		                                    <thead>
		                                        <tr>
		                                        	<th style="text-align: center; padding: 0px 10px 0px 5px;"><input type="checkbox" id="checkall" class="chk-col-blue"/><label for="checkall"></label></th>
		                                        	<th>Acciones</th>
		                                            <th>Tipo de Plazo</th>
		                                            <th>Tipo de Vendedor</th>
		                                            <th>% Descuento</th>
		                                            <th>Fecha de Registro</th>
		                                            <th>Registrado Por</th>
		                                        </tr>
		                                    </thead>
		                                    <tbody></tbody>
		                                </table>
		                                <div class="col-md-2 eliminar ocultar">
		                                	<button class="btn btn-danger waves-effect" onclick="eliminarMultiple('Descuento/eliminar_multiple_descuento')">Eliminar seleccionados</button>
		                                </div>
		                                <div class="col-md-2 actualizar ocultar">
		                                	<button class="btn btn-warning waves-effect" onclick="statusMultiple('Descuento/status_multiple_descuento', 1, 'activar')">Activar seleccionados</button>
		                                </div>
		                                <div class="col-md-2 actualizar ocultar">
		                                	<button class="btn btn-warning waves-effect" onclick="statusMultiple('Descuento/status_multiple_descuento', 2, 'desactivar')">Desactivar seleccionados</button>
		                                </div>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        <!-- Cierre del cuadro de la tabla -->

		        <!-- Comienzo del cuadro de registrar Descuento -->
					<div class="row clearfix ocultar" id="cuadro2">
		                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                    <div class="card">
		                        <div class="header">
		                            <h2>Registro de <?php echo $breadcrumbs[0]["nombre_lista_vista"]; ?></h2>
		                        </div>
		                        <div class="body">
		                        	<div class="table-responsive">
			                            <form name="form_descuento_registrar" id="form_descuento_registrar" method="post">
				                            <div class="col-sm-6">
			                            		<label for="tipo_plazo_registrar">Tipo de Plazo*</label>
		                                    	<select name="tipo_plazo" id="tipo_plazo_registrar" required class="form-control">
		                                    		<option value="" selected>Seleccione</option>
		                                    		<?php foreach ($tipos_plazos as $tipo_plazo): ?>
		                                    			<option value="<?= $tipo_plazo->id_lista_valor; ?>"><?= $tipo_plazo->nombre_lista_valor; ?></option>
		                                    		<?php endforeach ?>
		                                    	</select>
				                            </div>
				                            <div class="col-sm-6">
			                            		<label for="tipo_vendedor_registrar">Tipo de Vendedor*</label>
		                                    	<select name="tipo_vendedor" id="tipo_vendedor_registrar" required class="form-control">
		                                    		<option value="" selected>Seleccione</option>
		                                    		<?php foreach ($tipos_vendedores as $tipo_vendedor): ?>
		                                    			<option value="<?= $tipo_vendedor->id_lista_valor; ?>"><?= $tipo_vendedor->nombre_lista_valor; ?></option>
		                                    		<?php endforeach ?>
		                                    	</select>
				                            </div>
				                            <div class="col-sm-6">
			                            		<label for="cod_esquema_registrar">Esquema de Descuento*</label>
		                                    	<select name="cod_esquema" id="cod_esquema_registrar" required class="form-control">
		                                    		<option value="" selected>Seleccione</option>
		                                    		<?php var_dump($esquemas); foreach ($esquemas as $esquema): ?>
			                                    		<?php if ($esquema['status']==true){ ?>
		                                    			<option value="<?= $esquema["_id"]->{'$id'}; ?>" status="<?=$esquema['status'] ?>"><?= $esquema['cod_esquema'] . ' - ' .$esquema['descripcion']; ?></option>
		                                    			<?php } ?>
		                                    		<?php endforeach ?>
		                                    	</select>
				                            </div>
				                            <div class="col-sm-6">
				                                <label for="descuento_registrar">% Descuento*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control" name="descuento" id="descuento_registrar" placeholder="P. EJ. 00.00" required onkeypress="return valida(event)" style="text-align: right;">
				                                    </div>
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
		        <!-- Cierre del cuadro de registrar Descuento -->

		        <!-- Comienzo del cuadro de consultar Descuento -->
					<div class="row clearfix ocultar" id="cuadro3">
		                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                    <div class="card">
		                        <div class="header">
		                            <h2>Consultar <?php echo $breadcrumbs[0]['nombre_lista_vista']; ?></h2>
		                        </div>
		                        <div class="body">
		                        	<div class="table-responsive">
		                            	<div class="col-sm-6">
		                            		<label for="tipo_plazo_consultar">Tipo de Plazo*</label>
	                                    	<select id="tipo_plazo_consultar" class="form-control" disabled>
	                                    		<option value="" selected>Seleccione</option>
	                                    		<?php foreach ($tipos_plazos as $tipo_plazo): ?>
	                                    			<option value="<?= $tipo_plazo->id_lista_valor; ?>"><?= $tipo_plazo->nombre_lista_valor; ?></option>
	                                    		<?php endforeach ?>
	                                    	</select>
			                            </div>
			                            <div class="col-sm-6">
		                            		<label for="tipo_vendedor_consultar">Tipo de Vendedor*</label>
	                                    	<select id="tipo_vendedor_consultar" disabled class="form-control">
	                                    		<option value="" selected>Seleccione</option>
	                                    		<?php foreach ($tipos_vendedores as $tipo_vendedor): ?>
	                                    			<option value="<?= $tipo_vendedor->id_lista_valor; ?>"><?= $tipo_vendedor->nombre_lista_valor; ?></option>
	                                    		<?php endforeach ?>
	                                    	</select>
			                            </div>
			                            <div class="col-sm-6">
		                            		<label for="cod_esquema_consultar">Esquema de Descuento*</label>
	                                    	<select id="cod_esquema_consultar" class="form-control" disabled>
	                                    		<option value="" selected>Seleccione</option>
	                                    		
	                                    		<?php foreach ($esquemas as $esquema): ?>
		                                    			<option value="<?= $esquema["_id"]->{'$id'}; ?>"><?= $esquema['cod_esquema'] . ' - ' .$esquema['descripcion']; ?></option>
		                                    	<?php endforeach ?>
	                                    	</select>
			                            </div>
			                            <div class="col-sm-6">
			                                <label for="descuento_registrar">% Descuento*</label>
			                                <div class="form-group">
			                                    <div class="form-line">
			                                        <input type="text" class="form-control" id="descuento_consultar" disabled style="text-align: right;">
			                                    </div>
			                                </div>
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
		        <!-- Cierre del cuadro de consultar Descuento -->

		        <!-- Comienzo del cuadro de editar Descuento -->
					<div class="row clearfix ocultar" id="cuadro4">
		                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                    <div class="card">
		                        <div class="header">
		                            <h2>Editar de <?php echo $breadcrumbs[0]['nombre_lista_vista']; ?></h2>
		                        </div>
		                        <div class="body">
		                        	<div class="table-responsive">
			                            <form name="form_descuento_actualizar" id="form_descuento_actualizar" method="post">
			                            	<div class="col-sm-6">
			                            		<label for="tipo_plazo_actualizar">Tipo de Plazo*</label>
		                                    	<select name="tipo_plazo" id="tipo_plazo_actualizar" required class="form-control">
		                                    		<option value="" selected>Seleccione</option>
		                                    		<?php foreach ($tipos_plazos as $tipo_plazo): ?>
		                                    			<option value="<?= $tipo_plazo->id_lista_valor; ?>"><?= $tipo_plazo->nombre_lista_valor; ?></option>
		                                    		<?php endforeach ?>
		                                    	</select>
				                            </div>
				                            <div class="col-sm-6">
			                            		<label for="tipo_vendedor_actualizar">Tipo de Vendedor*</label>
		                                    	<select name="tipo_vendedor" id="tipo_vendedor_actualizar" required class="form-control">
		                                    		<option value="" selected>Seleccione</option>
		                                    		<?php foreach ($tipos_vendedores as $tipo_vendedor): ?>
		                                    			<option value="<?= $tipo_vendedor->id_lista_valor; ?>"><?= $tipo_vendedor->nombre_lista_valor; ?></option>
		                                    		<?php endforeach ?>
		                                    	</select>
				                            </div>
				                            <div class="col-sm-6">
			                            		<label for="cod_esquema_actualizar">Esquema de Descuento*</label>
		                                    	<select name="cod_esquema" id="cod_esquema_actualizar" required class="form-control">
		                                    		<option value="" selected>Seleccione</option>
		                                    		<?php foreach ($esquemas as $esquema): ?>
		                                    			<option value="<?= $esquema["_id"]->{'$id'}; ?>" status="<?=$esquema['status'] ?>"><?= $esquema['cod_esquema'] . ' - ' .$esquema['descripcion']; ?></option>
		                                    		<?php endforeach ?>
		                                    	</select>
				                            </div>
				                            <div class="col-sm-6">
				                                <label for="descuento_registrar">% Descuento*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control" name="descuento" id="descuento_actualizar" placeholder="P. EJ. 00,00" required onkeypress="return valida(event)" style="text-align: right;">
				                                    </div>
				                                </div>
				                            </div>
				                            <input type="hidden" name="id_descuento" id="id_descuento_actualizar">
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
		        <!-- Cierre del cuadro de editar Descuento -->
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
    <script src="<?=base_url();?>assets/cpanel/Descuento/js/descuento.js"></script>
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
