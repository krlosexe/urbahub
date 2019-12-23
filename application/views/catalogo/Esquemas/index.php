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
		                                <button class="btn btn-primary waves-effect registrar ocultar" onclick="nuevoEsquema()"><i class='fa fa-plus-circle' style="color: white; font-size: 18px;"></i> | Nuevo</button>
		                            </ul>
		                        </div>
		                        <div class="body">
		                            <div class="table-responsive">
		                                <table class="table table-bordered table-striped table-hover" id="tabla">
		                                    <thead>
		                                        <tr>
		                                        	<th style="text-align: center; padding: 0px 10px 0px 5px;"><input type="checkbox" id="checkall" class="chk-col-blue"/><label for="checkall"></label></th>
		                                        	<th>Acciones</th>
		                                            <th>Tipo</th>
		                                            <th>Código</th>
		                                            <th>Descripción</th>
		                                            <th>Fecha de Registro</th>
		                                            <th>Registrado Por</th>
		                                        </tr>
		                                    </thead>
		                                    <tbody></tbody>
		                                </table>
		                                <div class="col-md-2 eliminar ocultar">
		                                	<button class="btn btn-danger waves-effect" onclick="eliminarMultiple('Esquemas/eliminar_multiple_esquema')">Eliminar seleccionados</button>
		                                </div>
		                                <div class="col-md-2 actualizar ocultar">
		                                	<button class="btn btn-warning waves-effect" onclick="statusMultiple('Esquemas/status_multiple_esquema', 1, 'activar')">Activar seleccionados</button>
		                                </div>
		                                <div class="col-md-2 actualizar ocultar">
		                                	<button class="btn btn-warning waves-effect" onclick="statusMultiple('Esquemas/status_multiple_esquema', 2, 'desactivar')">Desactivar seleccionados</button>
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
			                            <form name="form_esquema_registrar" id="form_esquema_registrar" method="post">
			                            	<div class="col-sm-4">
			                            		<label for="tipo_registrar">Tipo*</label>
		                                    	<select name="tipo" id="tipo_registrar" required class="form-control">
		                                    		<option value="" selected>Seleccione</option>
		                                    		<?php foreach ($tipos_esquemas as $tipo_esquema): ?>
		                                    			<option value="<?= $tipo_esquema->id_lista_valor; ?>"><?= $tipo_esquema->nombre_lista_valor; ?></option>
		                                    		<?php endforeach ?>
		                                    	</select>
				                            </div>
			                            	<div class="col-sm-4">
			                            		<label for="cod_esquema_registrar">Código*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control mayusculas" name="cod_esquema" id="cod_esquema_registrar" placeholder="P. EJ. XXXX00001" required>
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
			                            		<label for="descripcion_registrar">Descripción*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control mayusculas" name="descripcion" id="descripcion_registrar" placeholder="P. EJ. XXXX FASE X" required>
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-2">
			                            		<label for="cod_esquema_registrar">Descuento por Servicio</label>
		                                    	<div class="switch">
												    <label>
												      No
												      <input type="checkbox" id="indicador_registrar">
												      <span class="lever"></span>
												      Si
												    </label>
												  </div>
				                            </div>
				                            <input type="hidden" name="indicador_servicio" id="indicador_servicio">
                            			<br>
                            			  <div class="col-sm-4">
			                            		<label for="tipo_registrar">Plan/Paquete</label>
		                                    	<select name="plan_paquete" id="plan_paquete" required class="form-control select-order" disabled>
		                                    		<option value="" selected>Seleccione</option>
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
		                            		<label for="tipo_consultar">Tipo*</label>
	                                    	<select id="tipo_consultar" class="form-control" disabled>
	                                    		<option value="" selected>Seleccione</option>
	                                    		<?php foreach ($tipos_esquemas as $tipo_esquema): ?>
	                                    			<option value="<?= $tipo_esquema->id_lista_valor; ?>"><?= $tipo_esquema->nombre_lista_valor; ?></option>
	                                    		<?php endforeach ?>
	                                    	</select>
			                            </div>
		                            	<div class="col-sm-4">
		                            		<label for="cod_esquema_consultar">Código*</label>
			                                <div class="form-group">
			                                    <div class="form-line">
			                                        <input type="text" class="form-control mayusculas" id="cod_esquema_consultar" placeholder="P. EJ. XXXX00001" disabled>
			                                    </div>
			                                </div>
			                            </div>
			                            <div class="col-sm-4">
		                            		<label for="descripcion_consultar">Descripción*</label>
			                                <div class="form-group">
			                                    <div class="form-line">
			                                        <input type="text" class="form-control mayusculas" id="descripcion_consultar" placeholder="P. EJ. XXXX FASE X" disabled>
			                                    </div>
			                                </div>
			                            </div>
			                             <div class="col-sm-2">
		                            		<label for="cod_esquema_registrar">Descuento por Servicio</label>
	                                    	<div class="switch">
											    <label>
											      No
											      <input type="checkbox" id="indicador_view" disabled>
											      <span class="lever"></span>
											      Si
											    </label>
											  </div>
			                            </div>
			                            <input type="hidden" name="indicador_servicio" id="indicador_servicio_view" disabled>


			                            <div class="col-sm-4">
		                            		<label for="tipo_registrar">Plan/Paquete</label>
	                                    	<select name="plan_paquete" id="plan_paquete_view" required class="form-control select-order" disabled>
	                                    		<option value="" selected>Seleccione</option>
	                                    	</select>
			                            </div>

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
			                            <form name="form_esquema_actualizar" id="form_esquema_actualizar" method="post">
			                            	<div class="col-sm-4">
			                            		<label for="tipo_actualizar">Tipo*</label>
		                                    	<select name="tipo" id="tipo_actualizar" required class="form-control">
		                                    		<option value="" selected>Seleccione</option>
		                                    		<?php foreach ($tipos_esquemas as $tipo_esquema): ?>
		                                    			<option value="<?= $tipo_esquema->id_lista_valor; ?>"><?= $tipo_esquema->nombre_lista_valor; ?></option>
		                                    		<?php endforeach ?>
		                                    	</select>
				                            </div>
			                            	<div class="col-sm-4">
			                            		<label for="cod_esquema_actualizar">Código*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control mayusculas" name="cod_esquema" id="cod_esquema_actualizar" placeholder="P. EJ. XXXX00001" required>
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
			                            		<label for="descripcion_actualizar">Descripción*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control mayusculas" name="descripcion" id="descripcion_actualizar" placeholder="P. EJ. XXXX FASE X" required>
				                                    </div>
				                                </div>
				                            </div>
				                              <div class="col-sm-2">
			                            		<label for="cod_esquema_registrar">Descuento por Servicio</label>
		                                    	<div class="switch">
												    <label>
												      No
												      <input type="checkbox" id="indicador_edit">
												      <span class="lever"></span>
												      Si
												    </label>
												  </div>
				                            </div>
				                            <input type="hidden" name="indicador_servicio" id="indicador_servicio_edit">


				                            <div class="col-sm-4">
			                            		<label for="tipo_registrar">Plan/Paquete</label>
		                                    	<select name="plan_paquete" id="plan_paquete_edit" required class="form-control select-order" disabled>
		                                    		<option value="" selected>Seleccione</option>
		                                    	</select>
				                            </div>

				                            <input type="hidden" name="id_esquema" id="id_esquema_actualizar">
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
    <script src="<?=base_url();?>assets/cpanel/Esquemas/js/esquemas.js"></script>
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
