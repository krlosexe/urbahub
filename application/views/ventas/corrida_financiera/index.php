<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
	<link href="<?=base_url();?>assets/template/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
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
		                                <button class="btn btn-primary waves-effect registrar ocultar" onclick="nuevoRegistro()"><i class='fa fa-plus-circle' style="color: white; font-size: 18px;"></i> | Nuevo</button>
		                            </ul>
		                        </div>
		                        <div class="body">
		                            <div class="table-responsive">
		                                <table class="table table-bordered table-striped table-hover" id="tabla">
		                                    <thead>
		                                        <tr>
		                                        	<th style="text-align: center; padding: 0px 10px 0px 5px;"><input type="checkbox" id="checkall" class="chk-col-blue"/><label for="checkall"></label></th>
		                                            <th>Código</th>
		                                            <th>Nombre o Razón Social</th>
		                                            <th>Fecha de Registro</th>
		                                            <th>Registrado Por</th>
		                                            <th style="width: 17%;">Acciones</th>
		                                        </tr>
		                                    </thead>
		                                    <tbody></tbody>
		                                </table>
		                                <div class="col-md-2 eliminar ocultar">
		                                	<button class="btn btn-danger waves-effect" onclick="eliminarMultiple('Bancos/eliminar_multiple_banco')">Eliminar seleccionados</button>
		                                </div>
		                                <div class="col-md-2 actualizar ocultar">
		                                	<button class="btn btn-warning waves-effect" onclick="statusMultiple('Bancos/status_multiple_banco', 1, 'activar')">Activar seleccionados</button>
		                                </div>
		                                <div class="col-md-2 actualizar ocultar">
		                                	<button class="btn btn-warning waves-effect" onclick="statusMultiple('Bancos/status_multiple_banco', 2, 'desactivar')">Desactivar seleccionados</button>
		                                </div>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        <!-- Cierre del cuadro de la tabla -->

		        <!-- Comienzo del cuadro de registrar banco -->
					<div class="row clearfix ocultar" id="cuadro2">
		                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                    <div class="card">
		                        <div class="header">
		                            <h2>Registro de <?php echo $breadcrumbs->nombre_lista_vista; ?></h2>
		                        </div>
		                        <div class="body">
		                        	<div class="table-responsive">
			                            <form name="form_corrida_registrar" id="form_corrida_registrar" method="post">

			                            	<div class="col-sm-5">
			                            		<label for="proyecto">Proyectos*</label>
		                                    	<select name="proyecto" id="proyecto" required class="form-control">
		                                    		<option value="" selected>Seleccione</option>
		                                    		<?php foreach ($proyectos as $proyecto): ?>
		                                    			<option value="<?= $proyecto->id_proyecto; ?>"><?= $proyecto->nombre."-- CODIGO: ".$proyecto->codigo; ?></option>
		                                    		<?php endforeach ?>
		                                    	</select>
				                            </div>


				                            <div class="col-sm-3 col-sm-offset-4">
			                            		<label for="fecha_venta">Fecha*</label>
		                                    	<input type="date" class="form-control" id="fecha_venta" value="<?= $fecha_actual ?>" readonly>
				                            </div>


				                            <div class="col-sm-5">
			                            		<label for="cliente">Cliente*</label>
		                                    	<select name="cliente" id="cliente" required disabled class="form-control">
		                                    		<option value="" selected>Seleccione</option>
		                                    	</select>
				                            </div>


				                            <div class="col-sm-3 col-sm-offset-1">
			                            		<label for="vendedor">Vendedor*</label>
		                                    	<select name="vendedor" id="vendedor" required disabled class="form-control">
		                                    		
		                                    	</select>
				                            </div>

				                            <input type="hidden" id="tipo_vendedor">




				                            <div class="col-sm-3">
			                            		<label for="inmobiliaria">Inmobiliaria*</label>
		                                    	<select name="inmobiliaria" id="inmobiliaria" required disabled class="form-control">
		                                    		
		                                    	</select>
				                            </div>








                                			<br>


                                			<div style="border-bottom: 1px solid #ccc;">
					                            <h4>Datos del Producto</h4>
					                        </div>
					                        <br>



					                        <div class="col-sm-3">
			                            		<label for="etapas_proyecto">Etapas*</label>
		                                    	<select name="etapas_proyecto" id="etapas_proyecto" required disabled class="form-control etapa_pp">
		                                    		<option value="" selected>Seleccione</option>
		                                    	</select>
				                            </div>

	
				                            <div class="col-sm-4">
			                            		<label for="zonas_proyecto">Zonas*</label>
		                                    	<select name="zonas_proyecto" id="zonas_proyecto" required disabled class="form-control">
		                                    		<option value="" selected>Seleccione</option>
		                                    	</select>
				                            </div>



					                        <div class="col-sm-5">
			                            		<label for="productos">Productos*</label>
		                                    	<select name="productos" id="productos" required disabled class="form-control">
		                                    		<option value="" selected>Seleccione</option>
		                                    	</select>
				                            </div>





				                            <div class="col-sm-3">
			                            		<label for="zona">Lote Anterior</label>
		                                    	<input type="text" class="form-control" id="lote_anterior" readonly>
				                            </div>

				                            <div class="col-sm-3">
			                            		<label for="lote">Lote</label>
		                                    	<input type="text" class="form-control" id="lote" readonly>
				                            </div>

				                            <div class="col-sm-3">
			                            		<label for="superficie_producto">Superficie m2</label>
		                                    	<input type="text" class="form-control" id="superficie_producto" readonly>
				                            </div>

				                            <div class="col-sm-3">
			                            		<label for="precio_m">Precio m2</label>
		                                    	<input type="text" class="form-control" id="precio_m" readonly style="text-align: right;">
				                            </div>


				                            <div class="row">
				                            	
				                            	<div class="col-sm-3">
				                            		<label for="fecha_producto">Fecha</label>
			                                    	<input type="date" class="form-control" id="fecha_producto" readonly>
					                            </div>


					                            <div class="col-sm-3">
				                            		<label for="total_producto">Monto total</label>
			                                    	<input type="text" class="form-control" id="total_producto" readonly style="text-align: right;">
					                            </div>


					                            <div class="col-sm-3 col-sm-offset-3">
			                                        <button type="button" id="btn-agregar" style="margin-top: 12%; margin-right: 7%;" onclick="agregarProductos()" class="btn btn-primary waves-effect pull-right">Agregar</button>
				                                </div>
				                            </div>










			                                <br> <br>


                                			<div style="border-bottom: 1px solid #ccc;">
					                            <h4>Productos Registrados</h4>
					                        </div>
					                        <br>

					                        <div class="col-sm-12">
				                            	<table class="table table-bordered table-striped table-hover" id="tableProductoRegistrar">
				                            		<thead>
				                            			<tr>
				                            				<th>Etapa</th>
				                            				<th>Zona</th>
				                            				<th>Productos</th>
				                            				<th>Fecha</th>
				                            				<th>Lote Anterior</th>
				                            				<th>Lote</th>
				                            				<th>M2</th>
				                            				<th>Precio por M2</th>
				                            				<th>Monto Total</th>
				                            				<th>Acciones</th>
				                            			</tr>
				                            		</thead>
				                            		<tbody></tbody>
				                            	</table>
				                            </div>




			                            	<br> <br>

                                			<div style="border-bottom: 1px solid #ccc;">
					                            <h4>Anticipo / Plazo</h4>
					                        </div>
					                        <br>

				                        	<div class="pull-right col-md-3">
			                            		<label for="monto_totals">Monto Total</label>
	                                    		<input type="text" class="form-control monto_totals" id="monto_totals" style="text-align: right; font-weight: bold;" readonly>
			                           		</div>

			                           		<br><br><br><br><br>

			                           		<div class="col-md-3">
			                            		<label for="anticipo_m">Anticipo (Monto)</label>
	                                    		<input type="text" class="form-control monto_formato_decimales" id="anticipo_m" style="text-align: right; font-weight: bold" onkeypress="return valida(event)">
			                           		</div>

			                           		<div class="col-md-3">
			                            		<label for="anticipo_p">Anticipo (Porcentaje)</label>
	                                    		<input type="text" class="form-control" id="anticipo_p" style="text-align: center;font-weight: bold" onkeypress="return valida(event)">
			                           		</div>


			                           		<div class="col-sm-3">
			                            		<label for="plazo_saldo">Plazo del Saldo*</label>
		                                    	<select name="plazo_saldo" id="plazo_saldo" required class="form-control" disabled>
		                                    		<option value="" selected>Seleccione</option>
		                                    		<?php foreach ($plazo_saldos as $plazo_saldo): ?>
		                                    			<option value="<?= $plazo_saldo->id_lista_valor; ?>"><?= $plazo_saldo->nombre_lista_valor; ?></option>
		                                    		<?php endforeach ?>
		                                    	</select>
				                            </div>


				                            <div class="col-sm-3">
			                            		<label for="plazo_anticipo">Plazo Anticipo*</label>
		                                    	<select name="plazo_anticipo" id="plazo_anticipo" required class="form-control" style="font-weight:  bold;">
		                                    		<option value="" selected>Seleccione</option>
		                                    		<?php foreach ($plazo_anticipos as $plazo_anticipo): ?>
		                                    			<option value="<?= $plazo_anticipo->id_lista_valor; ?>"><?= $plazo_anticipo->nombre_lista_valor; ?></option>
		                                    		<?php endforeach ?>
		                                    	</select>
				                            </div>

				                            <div class="col-sm-3">
			                            		<label for="descuento">Descuento Disponible*</label>
		                                    	<select name="descuento" id="descuento" required class="form-control" style="font-weight:  bold;" disabled>
		                                    		<option value="" selected>Seleccione</option>
		                                    	</select>
				                            </div>


				                            <div class="col-sm-3">
			                            		<label for="descuento_select">Descuento*</label>
		                                    	<input type="text" class="form-control" id="descuento_select" style="text-align: right;font-weight: bold" readonly>
				                            </div>


				                            <div class="col-sm-3">
			                            		<label for="recargo">Recargo*</label>
		                                    	<input type="text" class="form-control" id="recargo" style="text-align: right;font-weight: bold" readonly>
				                            </div>


				                            <div class="col-sm-3">
			                            		<label for="saldo">Saldo*</label>
		                                    	<input type="text" class="form-control" id="saldo" style="text-align: right;font-weight: bold" readonly>
				                            </div>




				                            <div class="col-sm-3">
			                            		<label for="mensualidad">Mensualidad*</label>
		                                    	<input type="text" class="form-control monto_formato_decimales" id="mensualidad" style="text-align: right;font-weight: bold" readonly>
				                            </div>


				                            <div class="col-sm-3">
			                            		<label for="forma_pago">Forma de pago*</label>
		                                    	<select name="forma_pago" id="forma_pago" required class="form-control" style="font-weight: bold" disabled>
		                                    		<option value="" selected>Seleccione</option>
		                                    		<?php foreach ($forma_pagos as $forma_pago): ?>
		                                    			<option value="<?= $forma_pago->id_lista_valor; ?>"><?= $forma_pago->nombre_lista_valor; ?></option>
		                                    		<?php endforeach ?>
		                                    	</select>
				                            </div>


				                            <div class="col-sm-1">
			                            		<label for="cuotas">Cuotas*</label>
		                                    	<input type="text" class="form-control monto_formato_decimales" id="cuotas" style="text-align: center;" readonly>
				                            </div>

				                            <div class="col-sm-2">
			                            		<label for="monto_cuotas">Monto cuotas*</label>
		                                    	<input type="text" class="form-control monto_formato_decimales" id="monto_cuotas" style="text-align: right;" readonly>
				                            </div>
		

											<br>
				                           
			                            	<br>
                                			<div class="col-md-12" style="border-bottom: 1px solid #ccc;">
					                            <h4>Cuotas Extraordinarias</h4>
					                        </div>
					                        <br>
											

											<input type="hidden" name="total_cuota_extraordinaria" id="total_cuota_extraordinaria">
					                        <div class="col-sm-3">
			                            		<label for="tipo_cuota">Tipo de cuota*</label>
		                                    	<select name="tipo_cuota" id="tipo_cuota" required disabled class="form-control" style="font-weight: bold">
		                                    		<option value="" selected>Seleccione</option>
		                                    		<?php foreach ($tipo_cuotas as $tipo_cuota): ?>
	                                    			<option value="<?= $tipo_cuota->id_lista_valor; ?>"><?= $tipo_cuota->nombre_lista_valor; ?></option>
	                                    		<?php endforeach ?>
		                                    	</select>
				                            </div>


				                             <div class="col-sm-3">
			                            		<label for="mes_cuota">Mes de Pago</label>
		                                    	<select name="mes_cuota" id="mes_cuota" disabled required class="form-control" style="font-weight: bold; opacity: 0.5">
		                                    		<option value="" selected>Seleccione</option>
		                                    		<?php foreach ($meses as $key => $mes): ?>
		                                    			<option value="<?= $key?>"><?= $mes?></option>
		                                    		<?php endforeach ?>
		                                    	</select>
				                            </div>


				                            <div class="col-sm-3">
			                            		<label for="monto_cuotas_extra">Monto cuota*</label>
		                                    	<input type="text" class="form-control monto_formato_decimales" id="monto_cuotas_extra" style="text-align: right; font-weight: bold" onkeypress="return valida(event)">
				                            </div>


				                            <div class="col-sm-3">
			                            		<label></label>
		                                    	 <button type="button" style="margin-top: 11%;" onclick="agregar_cuotas()" class="btn btn-primary waves-effect">Agregar</button>
				                            </div>

				                            <br>
				                           
			                            	<br>

			                            	<input type="hidden" name="super_total" id="super_total">
                                			<div class="col-md-12" style="border-bottom: 1px solid #ccc;">
					                            <h4>Cuotas Extraordinarias Registradas</h4>
					                        </div>
					                        <br>

					                        <div class="col-sm-12">
				                            	<table class="table table-bordered table-striped table-hover" id="tableCoutasRegistrar">
				                            		<thead>
				                            			<tr>
				                            				<th>Tipo de cuota</th>
				                            				<th>Mes</th>
				                            				<th>Monto</th>
				                            				<th>Acciones</th>
				                            			</tr>
				                            		</thead>
				                            		<tbody></tbody>
				                            	</table>
				                            </div>



				                            <br>
				                           
			                            	<br>
                                			<div class="col-md-12" style="border-bottom: 1px solid #ccc;">
					                            <h4>Total</h4>
					                        </div>
					                        <br>


					                         <div class="col-sm-12">
				                            	<table class="table table-bordered table-striped table-hover" id="tableTotalRegistrar">
				                            		<thead>
				                            			<tr>
				                            				<th>Prod</th>
				                            				<th>Fecha Venta</th>
				                            				<th>Zona / Lote</th>
				                            				<th>M2</th>
				                            				<th>Precio por M2</th>
				                            				<th>Monto total</th>
				                            				<th>Anticipo</th>
				                            				<th>Saldo</th>
				                            				<th>Mensualidad Plazos</th>
				                            				<th>Forma Pago</th>
				                            				<th>Monto Cuotas</th>
				                            			</tr>
				                            		</thead>
				                            		<tbody>
				                            			<tr style="text-align: center;">
				                            				<td id="cantidad">0</td>
				                            				<td><?= date("d-m-Y")?></td>
				                            				<td id="zona_total">Zona / Lote</td>
				                            				<td id="m2_total">0</td>
				                            				<td id="precio_m2_total">0</td>
				                            				<td id="monto_total_total">0</td>
				                            				<td id="anticipo_total">0</td>
				                            				<td id="saldo_total">0</td>
				                            				<td id="mensualidad_total">0</td>
				                            				<td id="fp"></td>
				                            				<td id="monto_cuotas_total">0</td>
				                            			</tr>
				                            		</tbody>
				                            	</table>
				                            </div>

					                            




                                			<div class="col-sm-4 col-sm-offset-8" style="text-align: right;">
		                                        <input type="submit" value="Generar Corrida" class="btn btn-success waves-effect" style="padding: 3% 12%;font-size: 1.2em;">
		                                        <button type="button" onclick="regresar('#cuadro2')" class="btn btn-primary waves-effect" style="padding: 3% 12%;font-size: 1.2em;">Regresar</button>
			                                </div>
			                            </form>
			                        </div>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        <!-- Cierre del cuadro de registrar banco -->

		        <!-- Comienzo del cuadro de consultar banco -->
					<div class="row clearfix ocultar" id="cuadro3">
		                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                    <div class="card">
		                        <div class="header">
		                            <h2>Consultar <?php echo $breadcrumbs->nombre_lista_vista; ?></h2>
		                        </div>
		                        <div class="body">
		                        	<div class="table-responsive">
		                            	<div class="col-sm-6">
		                            		<label>Código</label>
			                                <div class="form-group">
			                                    <div class="form-line">
			                                        <input type="text" class="form-control" id="cod_banco_consultar" disabled>
			                                    </div>
			                                </div>
			                            </div>
			                            <div class="col-sm-6">
			                                <label>Nombre o Razón Social</label>
			                                <div class="form-group">
			                                    <div class="form-line">
			                                        <input type="text" class="form-control mayusculas" id="nombre_banco_consultar" disabled>
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
		        <!-- Cierre del cuadro de consultar banco -->

		        <!-- Comienzo del cuadro de editar banco -->
					<div class="row clearfix ocultar" id="cuadro4">
		                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                    <div class="card">
		                        <div class="header">
		                            <h2>Editar de <?php echo $breadcrumbs->nombre_lista_vista; ?></h2>
		                        </div>
		                        <div class="body">
		                        	<div class="table-responsive">
			                            <form name="form_banco_actualizar" id="form_banco_actualizar" method="post">
			                            	<div class="col-sm-6">
			                            		<label for="cod_banco_editar">Código*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control" id="cod_banco_editar" disabled>
				                                    </div>
				                                </div>
				                            </div>
				                            <input type="hidden" class="form-control" name="id_banco" id="id_banco_editar">
				                            <div class="col-sm-6">
				                                <label for="nombre_banco_editar">Nombre o Razón Social*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control mayusculas" name="nombre_banco" id="nombre_banco_editar" placeholder="P. EJ. BANCO NACIONAL DE MÉXICO, S.A." required>
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
		        <!-- Cierre del cuadro de editar banco -->
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

    <script src="<?=base_url();?>assets/cpanel/Productos/js/numeral/min/numeral.min.js"></script>
    <script src="<?=base_url();?>assets/cpanel/Corrida_financiera/js/Corrida_financiera.js"></script>
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
