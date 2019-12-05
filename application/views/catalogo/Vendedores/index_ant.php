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
		                                <button class="btn btn-primary waves-effect registrar ocultar" onclick="nuevoVendedor()"><i class='fa fa-plus-circle' style="color: white; font-size: 18px;"></i> | Nuevo</button>
		                            </ul>
		                        </div>
		                        <div class="body">
		                            <div class="table-responsive" style="padding-top: 2%;">
		                                <table class="table table-bordered table-striped table-hover" id="tabla">
		                                    <thead>
		                                        <tr>
		                                        	<th style="text-align: center; padding: 0px 10px 0px 5px;"><input type="checkbox" id="checkall" class="chk-col-blue"/><label for="checkall"></label></th>
		                                            <th>Código</th>
		                                            <th>Nombre</th>
		                                            <th>Apellido Paterno</th>
		                                            <th>Apellido Materno</th>
		                                            <th>Tipo Vendedor</th>
		                                            <th>RFC Vendedor</th>
		                                            <th>Fecha de Registro</th>
		                                            <th>Registrado por</th>
		                                            <th style="width: 17%;">Acciones</th>
		                                        </tr>
		                                    </thead>
		                                    <tbody></tbody>
		                                </table>
		                                <div class="col-md-2 eliminar ocultar">
		                                	<button class="btn btn-danger waves-effect" onclick="eliminarMultiple('vendedores/eliminar_multiple_vendedores')">Eliminar seleccionados</button>
		                                </div>
		                                <div class="col-md-2 actualizar ocultar">
		                                	<button class="btn btn-warning waves-effect" onclick="statusMultiple('vendedores/status_multiple_vendedores', 1, 'activar')">Activar seleccionados</button>
		                                </div>
		                                <div class="col-md-2 actualizar ocultar">
		                                	<button class="btn btn-warning waves-effect" onclick="statusMultiple('vendedores/status_multiple_vendedores', 2, 'desactivar')">Desactivar seleccionados</button>
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
		                            <h2>Registro de <?php echo $breadcrumbs[0]["nombre_lista_vista"]; ?></h2>
		                        </div>
		                        <div class="body">
		                        	<div class="table-responsive">
			                            <form name="form_vendedores_registrar" id="form_vendedores_registrar" method="post">
			                            	<div class="col-sm-4">
			                            		<label for="id_usuario">Usuario*</label>
		                                    	<select name="id_usuario" id="id_usuario" required class="form-control">
		                                    		<option value="" selected>Seleccione</option>
		                                    		<?php foreach ($usuarios_vendedores as $usuario_vendedor): ?>
		                                    			<option value="<?= $usuario_vendedor->id_usuario; ?>"><?= $usuario_vendedor->nombre_usuario." ".$usuario_vendedor->apellido_user; ?></option>
		                                    		<?php endforeach ?>
		                                    	</select>
				                            </div>

				                            <div class="col-sm-4">
			                            		<label for="tipo_vendedor">Tipo de Vendedor*</label>
		                                    	<select name="tipo_vendedor" id="tipo_vendedor" required class="form-control">
		                                    		<option value="" selected>Seleccione</option>
		                                    		<?php foreach ($tipos_vendedores as $tipo_vendedor): ?>
		                                    			<option value="<?= $tipo_vendedor->id_lista_valor; ?>"><?= $tipo_vendedor->nombre_lista_valor; ?></option>
		                                    		<?php endforeach ?>
		                                    	</select>
				                            </div>


				                            <div class="col-sm-4" id="validCurp">
			                            		<label for="rfc">RFC Vendedor*</label>
		                                    	<input type="text" name="rfc"  class="form-control rcf" id="rfc" style="text-transform: uppercase;" required maxlength="13" minlength="13"  onkeypress="return valida_r(event)" oninput="validarInputRfc(this)">
		                                    	
		                                    	<span id="resultado" class="curpError text-danger resultado"></span>

				                            </div>


				                            <div style="border-bottom: 1px solid #ccc;">
					                            <h3>Proyectos</h3>
					                        </div>
					                        <div class="col-sm-12">
				                            	<div class="col-sm-6" style="padding-top: 10px;">
			                                        <label for="proyecto_registrar">Proyectos*</label>
			                                        <select id="proyecto_registrar" class="form-control form-group">
			                                        	<option value="">Seleccione un Proyecto</option>
			                                        	<?php foreach($proyectos as $proyecto): ?>
			                                        		<option value="<?php echo $proyecto->id_proyecto ?>"><?php echo $proyecto->codigo . " - " . $proyecto->nombre?></option>
			                                        	<?php endforeach ?>
			                                        </select>
					                            </div>

					                            <div class="col-sm-6" style="padding-top: 10px;">
					                            	<label for="inmobiliaria_registrar">Inmobiliarias*</label>
			                                        <select id="inmobiliaria_registrar" class="form-control form-group" disabled>
			                                        	<option value="">Seleccione</option>
			                                        	<?php foreach($inmobiliarias as $inmobiliaria): ?>
			                                        		<option value="<?php echo $inmobiliaria->id_inmobiliaria ?>"><?php echo $inmobiliaria->codigo . " - " . $inmobiliaria->nombre?></option>
			                                        	<?php endforeach ?>
			                                        </select>
					                            </div>
					                            <div class="col-sm-2" style="padding-top: 10px;">
					                            	<button type="button" class="btn btn-primary waves-effect" onclick="agregarInmobiliaria('#proyecto_registrar', '#inmobiliaria_registrar', '#tableInmobiliariaRegistrar', '#tableClienteRegistrar')">Agregar</button>
					                            </div>
					                            <div class="col-sm-12">
					                            	<table class="table table-bordered table-striped table-hover" id="tableInmobiliariaRegistrar">
					                            		<thead>
					                            			<tr>
					                            				<th>Proyecto</th>
					                            				<th>Inmobiliaria</th>
					                            				<th>Acciones</th>
					                            			</tr>
					                            		</thead>
					                            		<tbody></tbody>
					                            	</table>
					                            </div>
				                            </div>
				                          	

				                          	<br>

				                          	<div style="border-bottom: 1px solid #ccc;">
					                            <h3>Cartera de Clientes</h3>
					                        </div>
					                        <div class="col-sm-12">
				                            	<div class="col-sm-6" style="padding-top: 10px;">
			                                        <label for="proyecto_clientes_registrar">Proyectos*</label>
			                                        <select id="proyecto_clientes_registrar" disabled class="form-control form-group">
			                                        	<option value="">Seleccione un Proyecto</option>
			                                        </select>
					                            </div>

					                            <div class="col-sm-6" style="padding-top: 10px;">
					                            	<label for="cliente_registrar">Clientes*</label>
			                                        <select id="cliente_registrar" class="form-control form-group" disabled>
			                                        	<option value="">Seleccione</option>
			                                        	<?php foreach($clientes as $cliente): ?>
			                                        		<option value="<?php echo $cliente->id_cliente ?>"><?php echo $cliente->nombre_datos_personales." - ".$cliente->apellido_p_datos_personales." - ".$cliente->apellido_m_datos_personales?></option>
			                                        	<?php endforeach ?>
			                                        </select>
					                            </div>
					                            <div class="col-sm-2" style="padding-top: 10px;">
					                            	<button type="button" class="btn btn-primary waves-effect" onclick="agregarCliente('#proyecto_clientes_registrar', '#cliente_registrar', '#tableClienteRegistrar')">Agregar</button>
					                            </div>
					                            <div class="col-sm-12">
					                            	<table class="table table-bordered table-striped table-hover" id="tableClienteRegistrar">
					                            		<thead>
					                            			<tr>
					                            				<th>Proyecto</th>
					                            				<th>Nombre del Cliente</th>
					                            				<th>Apellido Paterno</th>
					                            				<th>Apellido Materno</th>
					                            				<th>Acciones</th>
					                            			</tr>
					                            		</thead>
					                            		<tbody></tbody>
					                            	</table>
					                            </div>
				                            </div>
                                			<br>
                                			<div class="col-sm-4 col-sm-offset-5">
		                                        <button type="button" onclick="regresar('#cuadro2')" class="btn btn-primary waves-effect">Regresar</button>
		                                        <input type="submit" value="Guardar" class="btn btn-success waves-effect save">
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
		                            <h2>Consultar <?php echo $breadcrumbs[0]["nombre_lista_vista"]; ?></h2>
		                        </div>
		                        <div class="body">
		                        	<div class="table-responsive">
		                        		<form id="form_vendedores_consultar" name= "input" action="#" method="get">
		                        			<div class="col-sm-3">
			                            		<label for="codigo">Codigo</label>
		                                    	<input type="text" name="codigo" id="codigo" class="form-control" style="text-transform: uppercase;" disabled>
				                            </div>

				                            <div class="col-sm-3">
			                            		<label for="nombres">Nombres</label>
		                                    	<input type="text" name="nombres" id="nombres" class="form-control" style="text-transform: uppercase;" disabled>
				                            </div>

				                            <div class="col-sm-3">
			                            		<label for="apellido_p">Apellido Paterno</label>
		                                    	<input type="text" name="apellido_p" id="apellido_p" class="form-control" style="text-transform: uppercase;" disabled>
				                            </div>


				                            <div class="col-sm-3">
			                            		<label for="apellido_m">Apellido Materno</label>
		                                    	<input type="text" name="apellido_m" id="apellido_m" class="form-control" style="text-transform: uppercase;" disabled>
				                            </div>
				                            <br><br><br><br> <br>
				                            <div class="col-sm-4">
			                            		<label for="email">Correo Electronico</label>
		                                    	<input type="text" name="email" id="email" class="form-control" style="text-transform: uppercase;" disabled>
				                            </div>



				                            <div class="col-sm-4">
			                            		<label for="tipo_vendedor_view">Tipo de Vendedor*</label>
		                                    	<select name="tipo_vendedor_view" id="tipo_vendedor_view" required class="form-control" disabled>
		                                    		<option value="">Seleccione</option>
		                                    		<?php foreach ($tipos_vendedores as $tipo_vendedor): ?>
		                                    			<option value="<?= $tipo_vendedor->id_lista_valor; ?>"><?= $tipo_vendedor->nombre_lista_valor; ?></option>
		                                    		<?php endforeach ?>
		                                    	</select>
				                            </div>


				                            <div class="col-sm-4">
			                            		<label for="rfc_view">RFC Vendedor*</label>
		                                    	<input type="text" name="rfc_view" id="rfc_view" class="form-control" style="text-transform: uppercase;" disabled>
				                            </div>

				                            <div style="border-bottom: 1px solid #ccc;">
					                            <h3>Proyectos</h3>
					                        </div>
				                            <div class="col-sm-12" style="margin-top: 5%;">
				                            	<table class="table table-bordered table-striped table-hover" id="tableInmobiliariaConsultar">
				                            		<thead>
				                            			<tr>
				                            				<th>Proyecto</th>
				                            				<th>Inmobiliaria</th>
				                            			</tr>
				                            		</thead>
				                            		<tbody></tbody>
				                            	</table>
				                            </div>
	                            			<br>


	                            			<div style="border-bottom: 1px solid #ccc;">
					                            <h3>Cartera de clientes</h3>
					                        </div>
				                            <div class="col-sm-12" style="margin-top: 5%;">
				                            	<table class="table table-bordered table-striped table-hover" id="tableClientesConsultar">
				                            		<thead>
				                            			<tr>
				                            				<th>Proyecto</th>
				                            				<th>Nombre del Cliente</th>
				                            				<th>Apellido Paterno</th>
				                            				<th>Apellido Materno</th>
				                            			</tr>
				                            		</thead>
				                            		<tbody></tbody>
				                            	</table>
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
		        <!-- Cierre del cuadro de consult///ar inmobiliarias -->

		        <!-- Comienzo del cuadro de editar inmobiliarias -->
					<div class="row clearfix ocultar" id="cuadro4">
		                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                    <div class="card">
		                        <div class="header">
		                            <h2>Editar de <?php echo $breadcrumbs[0]["nombre_lista_vista"]; ?></h2>
		                        </div>
		                        <div class="body">
		                        	<div class="table-responsive">
			                            <form name="form_vendedor_editar" id="form_vendedor_editar" method="post">
			                            	<input type="hidden" name="id_vendedor_editar" id="id_vendedor_editar">
			                            	<div class="col-sm-3">
			                            		<label for="codigo_edit">Codigo</label>
		                                    	<input type="text" name="codigo_edit" id="codigo_edit" class="form-control" style="text-transform: uppercase;" disabled>
				                            </div>

				                            <div class="col-sm-3">
			                            		<label for="nombres_edit">Nombres</label>
		                                    	<input type="text" name="nombres_edit" id="nombres_edit" class="form-control" style="text-transform: uppercase;" disabled>
				                            </div>

				                            <div class="col-sm-3">
			                            		<label for="apellido_p_edit">Apellido Paterno</label>
		                                    	<input type="text" name="apellido_p_edit" id="apellido_p_edit" class="form-control" style="text-transform: uppercase;" disabled>
				                            </div>


				                            <div class="col-sm-3">
			                            		<label for="apellido_m_edit">Apellido Materno</label>
		                                    	<input type="text" name="apellido_m_edit" id="apellido_m_edit" class="form-control" style="text-transform: uppercase;" disabled>
				                            </div>
				                            <br><br><br><br> <br>

				                            <div class="col-sm-6">
			                            		<label for="tipo_vendedor_editar">Tipo de Vendedor*</label>
		                                    	<select name="tipo_vendedor" id="tipo_vendedor_editar" required class="form-control">
		                                    		<option value="" >Seleccione</option>
		                                    		<?php foreach ($tipos_vendedores as $tipo_vendedor): ?>
		                                    			<option value="<?= $tipo_vendedor->id_lista_valor; ?>"><?= $tipo_vendedor->nombre_lista_valor; ?></option>
		                                    		<?php endforeach ?>
		                                    	</select>
				                            </div>


				                            <div class="col-sm-6" id="validCurp_2">
			                            		<label for="rfc_editar">RFC Vendedor*</label>
		                                    	<input type="text" name="rfc_editar" id="rfc_editar" class="form-control" style="text-transform: uppercase;" required maxlength="13" minlength="13" onkeypress="return valida_r(event)" oninput="validarInputRfc_2(this)">
		                                    	
		                                    	<span id="resultado_2" class="curpError text-danger resultado"></span>
				                            </div>

				                            <br><br><br><br> <br>

				                            <div style="border-bottom: 1px solid #ccc;">
					                            <h3>Proyectos:</h3>
					                        </div>
					                         <br><br>
					                        <div class="col-sm-12">

					                        	<div class="col-sm-6" style="padding-top: 10px;">
			                                        <label for="proyecto_editar">Proyectos*</label>
			                                        <select id="proyecto_editar" class="form-control form-group">
			                                        	<option value="">Seleccione un Proyecto</option>
			                                        	<?php foreach($proyectos as $proyecto): ?>
			                                        		<option value="<?php echo $proyecto->id_proyecto ?>"><?php echo $proyecto->codigo . " - " . $proyecto->nombre?></option>
			                                        	<?php endforeach ?>
			                                        </select>
					                            </div>


				                            	<div class="col-sm-6" style="padding-top: 10px;">
				                            		<label for="inmobiliaria_editar">Inmobiliarias*</label>
			                                        <select id="inmobiliaria_editar" class="form-control form-group">
			                                        	<option value="">Seleccione</option>
			                                        	<?php foreach($inmobiliarias as $inmobiliaria): ?>
			                                        		<option value="<?php echo $inmobiliaria->id_inmobiliaria ?>"><?php echo $inmobiliaria->codigo . " - " . $inmobiliaria->nombre?></option>
			                                        	<?php endforeach ?>
			                                        </select>
					                            </div>
					                            <div class="col-sm-2" style="padding-top: 10px;">
					                            	<button type="button" class="btn btn-primary waves-effect" onclick="agregarInmobiliaria('#proyecto_editar', '#inmobiliaria_editar', '#tableInmobiliariaEditar', '#tableClientesEditar')">Agregar</button>
					                            </div>
					                            <div class="col-sm-12">
					                            	<table class="table table-bordered table-striped table-hover" id="tableInmobiliariaEditar">
					                            		<thead>
					                            			<tr>
					                            				<th>Proyectos</th>
					                            				<th>Inmobiliaria</th>
					                            				<th>Acciones</th>
					                            			</tr>
					                            		</thead>
					                            		<tbody></tbody>
					                            	</table>
					                            </div>
				                            </div>
                                			<br>

                                			<div style="border-bottom: 1px solid #ccc;">
					                            <h3>Cartera de clientes</h3>
					                        </div>
					                         <br><br>
					                        <div class="col-sm-6" style="padding-top: 10px;">
			                                        <label for="proyecto_clientes_editar">Proyectos*</label>
			                                        <select id="proyecto_clientes_editar" class="form-control form-group">
			                                        	<option value="">Seleccione un Proyecto</option>
			                                        </select>
					                            </div>

					                            <div class="col-sm-6" style="padding-top: 10px;">
					                            	<label for="cliente_editar">Clientes*</label>
			                                        <select id="cliente_editar" class="form-control form-group">
			                                        	<option value="">Seleccione</option>
			                                        	<?php foreach($clientes as $cliente): ?>
			                                        		<option value="<?php echo $cliente->id_cliente ?>"><?php echo $cliente->nombre_datos_personales." - ".$cliente->apellido_p_datos_personales." - ".$cliente->apellido_m_datos_personales?></option>
			                                        	<?php endforeach ?>
			                                        </select>
					                            </div>
					                            <div class="col-sm-2" style="padding-top: 10px;">
					                            	<button type="button" class="btn btn-primary waves-effect" onclick="agregarCliente('#proyecto_clientes_editar', '#cliente_editar', '#tableClientesEditar')">Agregar</button>
					                            </div>

				                            <div class="col-sm-12" style="margin-top: 3%;">
				                            	<table class="table table-bordered table-striped table-hover" id="tableClientesEditar">
				                            		<thead>
				                            			<tr>
				                            				<th>Proyecto</th>
				                            				<th>Nombre del Cliente</th>
				                            				<th>Apellido Paterno</th>
				                            				<th>Apellido Materno</th>
				                            				<th>Acciones</th>
				                            			</tr>
				                            		</thead>
				                            		<tbody></tbody>
				                            	</table>
				                            </div>
	                            			<br>


                                			<div class="col-sm-4 col-sm-offset-5">
		                                        <button type="button" onclick="regresar('#cuadro4')" class="btn btn-primary waves-effect">Regresar</button>
		                                        <input type="submit" value="Guardar" class="btn btn-success waves-effect save2">
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
    <script src="<?=base_url();?>assets/cpanel/Vendedores/js/vendedores.js"></script>
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
