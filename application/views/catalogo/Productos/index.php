<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
	<link href="<?=base_url();?>assets/template/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
	<link href="<?=base_url();?>assets/template/plugins/bootstrap-fileinput/css/fileinput.css" media="all" rel="stylesheet" type="text/css" />
	<style type="text/css">
		.file-drop-zone{
			height: auto;
		}
	</style>

	<?php if(($permiso[0]->general==1 && $permiso[0]->detallada==1 && $permiso[0]->registrar==1 && $permiso[0]->actualizar==1 && $permiso[0]->eliminar==1) OR $permiso[0]->status==2): ?>
		<script src="<?=base_url();?>assets/cpanel/js/permiso.js"></script>
	<?php endif ?>
	<body class="theme-blue">
		<input type="hidden" id="ruta" value="<?=base_url();?>" name="ruta">
		<section class="content">
	        <div class="container-fluid">
	        	<div id="alertas"></div>
				<div class="block-header">
	                <ol class="breadcrumb breadcrumb-col-cyan">
                        <li><a href="javascript:void(0);"><?php echo $breadcrumbs->nombre_modulo_vista; ?></a></li>
                        <li><?php echo $breadcrumbs->nombre_lista_vista; ?></li>
                    </ol>
	            </div>
	        	<!-- Comienzo del cuadro de la tabla -->
					<div class="row clearfix" id="cuadro1">
		                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                    <div class="card">
		                        <div class="header">
		                            <h2>
		                                Gestión de <?php echo $breadcrumbs->nombre_lista_vista; ?>
		                            </h2>
		                            <ul class="header-dropdown m-r--5">
		                                <button class="btn btn-primary waves-effect registrar ocultar" onclick="nuevoVendedor()"><i class='fa fa-plus-circle' style="color: white; font-size: 18px;"></i> | Nuevo</button>
		                            </ul>
		                        </div>
		                        <div class="body">
		                            <div class="table-responsive">
		                                <table class="table table-bordered table-striped table-hover" id="tabla">
		                                    <thead>
		                                        <tr>
		                                        	<th style="text-align: center; padding: 0px 10px 0px 5px;"><input type="checkbox" id="checkall" class="chk-col-blue"/><label for="checkall"></label></th>
		                                            <th>Código</th>
		                                            <th>Descripción</th>
		                                            <th>Proyecto</th>
		                                            <th>Zona</th>
		                                           	<th>Lote anterior</th>
		                                            <th>Lote nuevo</th>
		                                            <th>Superficie</th>
		                                            <th>Precio Venta</th>
		                                            <th>Estatus</th>
		                                            <th>Registrado por</th>
		                                            <th>Fecha de Registro</th>
		                                            <th style="width: 17%;">Acciones</th>
		                                        </tr>
		                                    </thead>
		                                    <tbody></tbody>
		                                </table>
		                                <div class="col-md-2 eliminar ocultar">
		                                	<button class="btn btn-danger waves-effect" onclick="eliminarMultiple('productos/eliminar_multiple_productos')">Eliminar seleccionados</button>
		                                </div>
		                                <div class="col-md-2 actualizar ocultar">
		                                	<button class="btn btn-warning waves-effect" onclick="statusMultiple('productos/status_multiple_productos', 1, 'activar')">Activar seleccionados</button>
		                                </div>
		                                <div class="col-md-2 actualizar ocultar">
		                                	<button class="btn btn-warning waves-effect" onclick="statusMultiple('productos/status_multiple_productos', 2, 'desactivar')">Desactivar seleccionados</button>
		                                </div>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        <!-- Cierre del cuadro de la tabla -->

		        <!-- Comienzo del cuadro de registrar inmobiliarias -->
					<div class="row clearfix ocultar" id="cuadro2">
		                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                    <div class="card">
		                        <div class="header">
		                            <h2>Registro de <?php echo $breadcrumbs->nombre_lista_vista; ?></h2>
		                        </div>
		                        <div class="body">
		                        	<div class="table-responsive">
			                            <form name="form_productos_registrar" id="form_productos_registrar" method="post">
			                            	<div class="col-sm-12">
			                            		<label for="descripcion">Descripcion del producto*</label>
		                                    	<input type="text" class="form-control" name="descripcion" id="descripcion" required style="text-transform: uppercase;">
				                            </div>

				                            <div class="col-sm-4">
			                            		<label for="proyecto">Proyectos*</label>
		                                    	<select name="proyecto" id="proyecto" required class="form-control">
		                                    		<option value="" selected>Seleccione</option>
		                                    		<?php foreach ($proyectos as $proyecto): ?>
		                                    			<option value="<?= $proyecto->id_proyecto; ?>"><?= $proyecto->nombre."-- CODIGO: ".$proyecto->codigo; ?></option>
		                                    		<?php endforeach ?>
		                                    	</select>
				                            </div>
				                            
											<div class="col-sm-4">
			                            		<label for="etapas_proyecto">Etapas*</label>
		                                    	<select name="etapas_proyecto" id="etapas_proyecto" required disabled class="form-control etapa_pp">
		                                    		<option value="" selected>Seleccione</option>
		                                    	</select>
				                            </div>
	
				                            <div class="col-sm-4">
			                            		<label for="clasificacion_proyecto">Zonas*</label>
		                                    	<select name="clasificacion_proyecto" id="clasificacion_proyecto" required disabled class="form-control">
		                                    		<option value="" selected>Seleccione</option>
		                                    	</select>
				                            </div>

				                            <div class="col-sm-4">
			                            		<label for="precio_m2">Precio por m2*</label>
		                                    	<input type="text" class="form-control monto_formato_decimales" name="precio_m2" id="precio_m2" required disabled onkeypress="return valida(event)" style="text-align: right;">
				                            </div>

				                            <div class="col-sm-4">
			                            		<label for="superficie_m2">Superficie por m2*</label>
		                                    	<input type="text" class="form-control monto_formato_decimales_normal" name="superficie_m2" id="superficie_m2" required disabled onkeypress="return valida(event)">
				                            </div>

				                             <div class="col-sm-4">
			                            		<label for="precio_venta">Precio de venta</label>
		                                    	<input type="text" class="form-control" name="precio_venta" id="precio_venta" required disabled style="text-align: right;">
				                            </div>

				                             <div class="col-sm-6">
			                            		<label for="lote_anterior">Lote anterior</label>
		                                    	<input type="text" class="form-control" name="lote_anterior" id="lote_anterior" style="text-transform: uppercase;">
				                            </div>

				                             <div class="col-sm-6">
			                            		<label for="lote_nuevo">Lote nuevo</label>
		                                    	<input type="text" class="form-control" name="lote_nuevo" id="lote_nuevo" style="text-transform: uppercase;">
				                            </div>

				                            <div class="col-sm-12">
			                            		<label for="lote_nuevo">Observacion</label>
		                                    	<textarea class="form-control" name="observaciones_registrar" id="observaciones_registrar" style="text-transform: uppercase;"></textarea>
				                            </div>


				                            <div class="col-sm-12">
				                                <label for="plano_registrar">Planos del Producto</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="file" class="form-control" id="plano_registrar" name="" multiple="false">
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
		        <!-- Cierre del cuadro de registrar inmobiliarias -->

		        <!-- Comienzo del cuadro de consultar inmobiliarias -->
					<div class="row clearfix ocultar" id="cuadro3">
		                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                    <div class="card">
		                        <div class="header">
		                            <h2>Consultar <?php echo $breadcrumbs->nombre_lista_vista; ?></h2>
		                        </div>
		                        <div class="body">
		                        	<div class="table-responsive">
		                        		<form id="form_vendedores_consultar" name= "input" action="#" method="get">
			                            	<div class="col-sm-12">
			                            		<label for="descripcion_view">Descripcion del producto*</label>
		                                    	<input type="text" class="form-control" name="descripcion_view" id="descripcion_view" required disabled>
				                            </div>

				                            <div class="col-sm-4">
			                            		<label for="proyecto">Proyectos*</label>
		                                    	<select name="proyecto_view" id="proyecto_view" required class="form-control" disabled>
		                                    		<option value="" selected>Seleccione</option>
		                                    		<?php foreach ($proyectos as $proyecto): ?>
		                                    			<option value="<?= $proyecto->id_proyecto; ?>"><?= $proyecto->nombre."-- CODIGO: ".$proyecto->codigo; ?></option>
		                                    		<?php endforeach ?>
		                                    	</select>
				                            </div>
				                            
											<div class="col-sm-4">
			                            		<label for="etapas_proyecto">Etapas*</label>
		                                    	<select name="etapas_proyecto_c" disabled id="etapas_proyecto_c" required disabled class="form-control">
		                                    		<option value="" selected>Seleccione</option>
		                                    	</select>
				                            </div>

				                            <div class="col-sm-4">
			                            		<label for="clasificacion_proyecto_view">Zonas*</label>
		                                    	<select name="clasificacion_proyecto_view" id="clasificacion_proyecto_view" required disabled  class="form-control">
		                                    		<option value="" selected>Seleccione</option>
		                                    	</select>
				                            </div>

				                            <div class="col-sm-4">
			                            		<label for="precio_m2_view">Precio por m2*</label>
		                                    	<input type="text" class="form-control monto_formato_decimales" name="precio_m2_view" id="precio_m2_view" required disabled   disabled style="text-align: right;">
				                            </div>

				                            <div class="col-sm-4">
			                            		<label for="superficie_m2_view">Superficie por m2*</label>
		                                    	<input type="text" class="form-control" name="superficie_m2_view" id="superficie_m2_view" required disabled  disabled>
				                            </div>

				                             <div class="col-sm-4">
			                            		<label for="precio_venta_view">Precio de venta</label>
		                                    	<input type="text" class="form-control" name="precio_venta_view" id="precio_venta_view" required disabled style="text-align: right;">
				                            </div>


				                             <div class="col-sm-6">
			                            		<label for="lote_anterior_view">Lote anterior</label>
		                                    	<input type="text" class="form-control" name="lote_anterior_view" id="lote_anterior_view" disabled>
				                            </div>

				                             <div class="col-sm-6">
			                            		<label for="lote_nuevo_view">Lote nuevo</label>
		                                    	<input type="text" class="form-control" name="lote_nuevo_view" id="lote_nuevo_view" required disabled>
				                            </div>

				                            <div class="col-sm-12">
			                            		<label for="lote_nuevo">Observacion</label>
		                                    	<textarea class="form-control" disabled name="observaciones_view" id="observaciones_view"></textarea>
				                            </div>


				                            <div class="col-sm-6">
			                            		<label for="status">Estatus</label>
		                                    	<input type="text" class="form-control" name="status" id="status" required disabled>
				                            </div>
				                            <div class="col-sm-12">
				                               <label for="plano_consultar">Planos del Producto</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="file" class="form-control" readonly="true" id="plano_consultar" name="">
				                                    </div>
				                                </div>
				                            </div>



	                            			<br>
	                            			<div class="col-sm-2 col-sm-offset-5">
		                                        <button type="button" onclick="regresar('#cuadro3')" class="btn btn-primary waves-effect">Regresar</button>
			                                </div>
			                            </form>
			                        </div>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        <!-- Cierre del cuadro de consultar inmobiliarias -->

		        <!-- Comienzo del cuadro de editar inmobiliarias -->
					<div class="row clearfix ocultar" id="cuadro4">
		                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                    <div class="card">
		                        <div class="header">
		                            <h2>Editar de <?php echo $breadcrumbs->nombre_lista_vista; ?></h2>
		                        </div>
		                        <div class="body">
		                        	<div class="table-responsive">
			                            <form name="form_producto_editar" id="form_producto_editar" method="post">
			                            	<input type="hidden" name="id_producto_editar" id="id_producto_editar">

				                            <div class="col-sm-12">
			                            		<label for="descripcion_edit">Descripcion del producto*</label>
		                                    	<input type="text" class="form-control" name="descripcion_edit" id="descripcion_edit" required style="text-transform: uppercase;">
				                            </div>

				                            <div class="col-sm-4">
			                            		<label for="proyecto">Proyectos*</label>
		                                    	<select name="proyecto_edit" id="proyecto_edit" required class="form-control">
		                                    		<option value="" selected>Seleccione</option>
		                                    		<?php foreach ($proyectos as $proyecto): ?>
		                                    			<option value="<?= $proyecto->id_proyecto; ?>"><?= $proyecto->nombre."-- CODIGO: ".$proyecto->codigo; ?></option>
		                                    		<?php endforeach ?>
		                                    	</select>
				                            </div>
				                            				                            
				                            <div class="col-sm-4">
			                            		<label for="etapas_proyecto_e">Etapas*</label>
		                                    	<select name="etapas_proyecto_e" id="etapas_proyecto_e" required class="form-control etapa_pp2">
		                                    		<option value="" selected>Seleccione</option>
		                                    	</select>
				                            </div>

				                            <div class="col-sm-4">
			                            		<label for="clasificacion_proyecto_edit">Zonas*</label>
		                                    	<select name="clasificacion_proyecto_edit" id="clasificacion_proyecto_edit" required  class="form-control">
		                                    		<option value="" selected>Seleccione</option>
		                                    	</select>
				                            </div>

				                            <div class="col-sm-4">
			                            		<label for="precio_m2_edit">Precio por m2*</label>
		                                    	<input type="text" class="form-control monto_formato_decimales" name="precio_m2_edit" id="precio_m2_edit" required  onkeypress="return valida(event)" style="text-align: right;">
				                            </div>

				                            <div class="col-sm-4">
			                            		<label for="superficie_m2_edit">Superficie por m2*</label>
		                                    	<input type="text" class="form-control monto_formato_decimales_normal" name="superficie_m2_edit" id="superficie_m2_edit" required onkeypress="return valida(event)">
				                            </div>

				                             <div class="col-sm-4">
			                            		<label for="precio_venta_edit">Precio de venta</label>
		                                    	<input type="text" class="form-control" name="precio_venta_edit" id="precio_venta_edit" required disabled style="text-align: right;">
				                            </div>

				                             <div class="col-sm-6">
			                            		<label for="lote_anterior_edit">Lote anterior</label>
		                                    	<input type="text" class="form-control" name="lote_anterior_edit" id="lote_anterior_edit" style="text-transform: uppercase;">
				                            </div>

				                             <div class="col-sm-6">
			                            		<label for="lote_nuevo_edit">Lote nuevo</label>
		                                    	<input type="text" class="form-control" name="lote_nuevo_edit" id="lote_nuevo_edit" style="text-transform: uppercase;">
				                            </div>

				                            <div class="col-sm-12">
			                            		<label for="lote_nuevo">Observacion</label>
		                                    	<textarea class="form-control" name="observaciones_editar" id="observaciones_editar" style="text-transform: uppercase;"></textarea>
				                            </div>


				                            <div class="col-sm-12">
				                                <label for="plano_editar">Planos del Producto</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="file" class="form-control" id="plano_editar" name=""  multiple="false">
				                                    </div>
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
		        <!-- Cierre del cuadro de editar inmobiliarias -->
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
   	 <script src="<?=base_url();?>assets/cpanel/Productos/js/numeral/min/numeral.min.js"></script>
   	 <script src="<?=base_url();?>assets/template/plugins/bootstrap-fileinput/js/plugins/piexif.min.js" type="text/javascript"></script>
    <script src="<?=base_url();?>assets/template/plugins/bootstrap-fileinput/js/plugins/purify.min.js" type="text/javascript"></script>
    <script src="<?=base_url();?>assets/template/plugins/bootstrap-fileinput/js/plugins/sortable.min.js" type="text/javascript"></script>
    
    
    <script src="<?=base_url();?>assets/template/plugins/bootstrap-fileinput/js/fileinput.js" type="text/javascript"></script>
    <script type="text/javascript" src="<?=base_url();?>assets/template/plugins/bootstrap-fileinput/themes/fa/theme.min.js"></script>
    <script src="<?=base_url();?>assets/template/plugins/bootstrap-fileinput/js/locales/es.js" type="text/javascript"></script>
    <script src="<?=base_url();?>assets/cpanel/Productos/js/productos.js"></script>
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
