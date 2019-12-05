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
		                                <button class="btn btn-primary waves-effect registrar ocultar" onclick="nuevoPlanes()"><i class='fa fa-plus-circle' style="color: white; font-size: 18px;"></i> | Nuevo</button>
		                            </ul>
		                        </div>
		                        <div class="body">
		                            <div class="table-responsive">
		                                <table class="table table-bordered table-striped table-hover" id="tabla">
		                                    <thead>
		                                        <tr>
		                                        	<th style="text-align: center; padding: 0px 10px 0px 5px;"><input type="checkbox" id="checkall" class="chk-col-blue"/><label for="checkall"></label></th>
		                                        	<th>Acciones</th>
		                                            <th>Plan</th>
		                                            <th>Servicio</th>
		                                            <th>Valor</th>
		                                            <th>Fecha de Registro</th>
		                                            <th>Registrado Por</th>
		                                        </tr>
		                                    </thead>
		                                    <tbody></tbody>
		                                </table>
		                                <div class="col-md-2 eliminar ocultar">
		                                	<button class="btn btn-danger waves-effect" onclick="eliminarMultiple('Paquetes/eliminar_multiple_paquetes')">Eliminar seleccionados</button>
		                                </div>
		                                <div class="col-md-2 actualizar ocultar">
		                                	<button class="btn btn-warning waves-effect" onclick="statusMultiple('Paquetes/status_multiple_paquetes', 1, 'activar')">Activar seleccionados</button>
		                                </div>
		                                <div class="col-md-2 actualizar ocultar">
		                                	<button class="btn btn-warning waves-effect" onclick="statusMultiple('Paquetes/status_multiple_paquetes', 2, 'desactivar')">Desactivar seleccionados</button>
		                                </div>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        <!-- Cierre del cuadro de la tabla -->

		        <!-- Comienzo del cuadro de registrar Esquemas -->
					<div class="row clearfix ocultar" id="cuadro2">
		                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                    <div class="card">
		                        <div class="header">
		                            <h2>Registro de <?php echo $breadcrumbs[0]["nombre_lista_vista"]; ?></h2>
		                        </div>
		                         <div class="body">
		                        	<div class="table-responsive">
		                        		<form name="form_paquetes_registrar" id="form_paquetes_registrar" method="post">
			                        		<div class="col-sm-4">
			                            		<label for="plan_registrar">Plan*</label>
		                                    	<select name="plan" id="plan_registrar" class="form-control" required>
		                                    		<option value="" selected>Seleccione</option>
		                                    		<?php foreach ($planes as $plan): ?>
		                                    			<?php if ($plan['status']==true){ ?>
		                                    				<option value="<?= $plan["id_planes"]; ?>" status="<?=$plan['status'] ?>"><?= $plan["descripcion"]; ?></option>
		                                    			<?php } ?> 
		                                    		<?php endforeach ?>
		                                    	</select>
				                            </div>
			                            	<div class="col-sm-4">
			                            		<label for="servicio_registrar">Servicios*</label>
		                                    	<select name="servicio" id="servicio_registrar" class="form-control" required>
		                                    		<option value="" selected>Seleccione</option>
		                                    		<?php foreach ($servicios as $servicio): ?>
		                                    			<?php if ($servicio['status']==true){ ?>
		                                    			<option value="<?= $servicio["id_servicios"]; ?>" status="<?=$servicio['status'] ?>"><?= $servicio["descripcion"]; ?></option>
		                                    			<?php } ?>
		                                    		<?php endforeach ?>
		                                    	</select>
				                            </div>
				                            <div class="col-sm-4">
			                            		<label for="valor_registrar">Valor*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control mayusculas" name="valor" id="valor_registrar" placeholder="P. EJ. XXXX (X)" required>
				                                    </div>
				                                </div>
				                            </div>
	                            			<br>
	                            			<div class="col-sm-4 col-sm-offset-5">
		                                        <button type="button" onclick="regresar('#cuadro2')" class="btn btn-primary waves-effect">Regresar</button>
		                                        <input type="submit" value="Registrar" class="btn btn-success waves-effect">
			                                </div>
			                            </form>    
			                        </div>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        <!-- Cierre del cuadro de registrar Esquemas -->

		        <!-- Comienzo del cuadro de consultar Esquemas -->
					<div class="row clearfix ocultar" id="cuadro3">
		                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                    <div class="card">
		                        <div class="header">
		                            <h2>Consultar <?php echo $breadcrumbs[0]["nombre_lista_vista"]; ?></h2>
		                        </div>
		                        <div class="body">
		                        	<div class="table-responsive">
		                        		<div class="col-sm-4">
		                            		<label for="plan_consultar">Plan*</label>
	                                    	<select id="plan_consultar" class="form-control" disabled>
	                                    		<option value="" selected>Seleccione</option>
	                                    		<?php foreach ($planes as $plan): ?>
	                                    			<option value="<?= $plan["id_planes"]; ?>"><?= $plan["descripcion"]; ?></option>
	                                    		<?php endforeach ?>
	                                    	</select>
			                            </div>
		                            	<div class="col-sm-4">
		                            		<label for="servicio_consultar">Servicios*</label>
	                                    	<select id="servicio_consultar" class="form-control" disabled>
	                                    		<option value="" selected>Seleccione</option>
	                                    		<?php foreach ($servicios as $servicio): ?>
	                                    			<option value="<?= $servicio["id_servicios"]; ?>"><?= $servicio["descripcion"]; ?></option>
	                                    		<?php endforeach ?>
	                                    	</select>
			                            </div>
			                            <div class="col-sm-4">
		                            		<label for="valor_consultar">Valor*</label>
			                                <div class="form-group">
			                                    <div class="form-line">
			                                        <input type="text" class="form-control mayusculas" id="valor_consultar" placeholder="P. EJ. XXXX (X)" disabled>
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
		        <!-- Cierre del cuadro de consultar Esquemas -->

		        <!-- Comienzo del cuadro de editar Esquemas -->
					<div class="row clearfix ocultar" id="cuadro4">
		                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                    <div class="card">
		                        <div class="header">
		                            <h2>Editar de <?php echo $breadcrumbs[0]["nombre_lista_vista"]; ?></h2>
		                        </div>
		                        <div class="body">
		                        	<div class="table-responsive">
		                        		<form name="form_paquetes_editar" id="form_paquetes_editar" method="post">
			                        		<div class="col-sm-4">
			                            		<label for="plan_editar">Plan*</label>
		                                    	<select name="plan" id="plan_editar" class="form-control" required>
		                                    		<option value="" selected>Seleccione</option>
		                                    		<?php foreach ($planes as $plan): ?>
		                                    			<option value="<?= $plan["id_planes"]; ?>" status="<?=$plan['status'] ?>"><?= $plan["descripcion"]; ?></option>
		                                    		<?php endforeach ?>
		                                    	</select>
				                            </div>
			                            	<div class="col-sm-4">
			                            		<label for="servicio_editar">Servicios*</label>
		                                    	<select name="servicio" id="servicio_editar" class="form-control" required>
		                                    		<option value="" selected>Seleccione</option>
		                                    		<?php foreach ($servicios as $servicio): ?>
		                                    			<option value="<?= $servicio["id_servicios"]; ?>" status="<?=$servicio['status'] ?>"><?= $servicio["descripcion"]; ?></option>
		                                    		<?php endforeach ?>
		                                    	</select>
				                            </div>
				                            <div class="col-sm-4">
			                            		<label for="valor_editar">Valor*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control mayusculas" name="valor" id="valor_editar" placeholder="P. EJ. XXXX (X)" required>
				                                    </div>
				                                </div>
				                            </div>
				                            <input type="hidden" name="id_paquetes" id="id_paquetes_editar">
	                            			<br>
	                            			<div class="col-sm-4 col-sm-offset-5">
		                                        <button type="button" onclick="regresar('#cuadro4')" class="btn btn-primary waves-effect">Regresar</button>
		                                        <input type="submit" value="Actualizar" class="btn btn-success waves-effect">
			                                </div>
			                            </form>    
			                        </div>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        <!-- Cierre del cuadro de editar Esquemas -->
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
    <script src="<?=base_url();?>assets/cpanel/Paquetes/js/paquetes.js"></script>
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
