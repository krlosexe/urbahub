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
		                                <button class="btn btn-primary waves-effect registrar ocultar" onclick="nuevoProyecto()"><i class='fa fa-plus-circle' style="color: white; font-size: 18px;"></i> | Nuevo</button>
		                            </ul>
		                        </div>
		                        <div class="body">
		                            <div class="table-responsive">
		                                <table class="table table-bordered table-striped table-hover" id="tabla">
		                                    <thead>
		                                        <tr>
		                                        	<th style="text-align: center; padding: 0px 10px 0px 5px;"><input type="checkbox" id="checkall" class="chk-col-blue"/><label for="checkall"></label></th>
		                                            <th>Código</th>
		                                            <th>Nombre</th>
		                                            <th>Descripción</th>
		                                            <th>Director</th>
		                                            <th>Fecha de Registro</th>
		                                            <th>Registrado Por</th>
		                                            <th style="width: 17%;">Acciones</th>
		                                        </tr>
		                                    </thead>
		                                    <tbody></tbody>
		                                </table>
		                                <div class="col-md-2 eliminar ocultar">
		                                	<button class="btn btn-danger waves-effect" onclick="eliminarMultiple('Proyectos/eliminar_multiple_proyecto')">Eliminar seleccionados</button>
		                                </div>
		                                <div class="col-md-2 actualizar ocultar">
		                                	<button class="btn btn-warning waves-effect" onclick="statusMultiple('Proyectos/status_multiple_proyecto', 1, 'activar')">Activar seleccionados</button>
		                                </div>
		                                <div class="col-md-2 actualizar ocultar">
		                                	<button class="btn btn-warning waves-effect" onclick="statusMultiple('Proyectos/status_multiple_proyecto', 2, 'desactivar')">Desactivar seleccionados</button>
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
			                            <form name="form_proyecto_registrar" id="form_proyecto_registrar" method="post">
			                            	<div class="col-sm-4">
			                            		<label for="codigo_registrar">Código del Proyecto*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control mayusculas" name="codigo" id="codigo_registrar" maxlength="50" placeholder="P. EJ. PXXXXXXXXX" required>
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
				                                <label for="nombre_registrar">Nombre del Proyecto*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control mayusculas" name="nombre" id="nombre_registrar" required>
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
				                                <label for="descripcion_registrar">Descripción del Proyecto*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control mayusculas" name="descripcion" id="descripcion_registrar" required>
				                                    </div>
				                                </div>
				                            </div>
				                        <div class="col-sm-6">
			                            		<label for="director_registrar">Director*</label>
		                                    	<select name="director" id="director_registrar" required class="form-control">
		                                    		<option value="" selected>Seleccione</option>
		                                    		<?php foreach ($directores as $director): ?>
		                                    			<option value="<?= $director->id_usuario; ?>"><?= $director->nombres . " " . $director->paterno . " " . $director->materno ?></option>
		                                    		<?php endforeach ?>
		                                    	</select>
				                            </div>
				                            <div class="col-sm-2">
			                            		<label for="cod_esquema_registrar">Indicador de Mora*</label>
		                                    	<div class="switch">
												    <label>
												      No
												      <input type="checkbox" id="indicador_registrar">
												      <span class="lever"></span>
												      Si
												    </label>
												  </div>
				                            </div>

				                            <input type="hidden" name="indicador_mora_registrar" id="indicador_mora_registrar" value="N">

			                            <div class="col-sm-2">
			                            		<label for="num_ventas_max_mes_registrar">Num. dias Vencidos*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control" name="dias_vencidos" id="dias_vencidos_registrar" onkeypress='return solonumeros(event)' placeholder="P. EJ. 2" disabled>
				                                    </div>
				                                </div>
				                            </div>

			                            	<div class="col-sm-2">
			                            		<label for="num_ventas_min_mes_registrar">% de Mora*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control" name="porcentaje_nora" id="porcentaje_mora_registrar" onkeypress='return solonumeros(event)' placeholder="P. EJ. 1.2" disabled style="text-align: right;">
				                                    </div>
				                                </div>
				                            </div>
				                              <div class="col-sm-12">
				                                <label for="plano_registrar">Planos del Proyecto</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="file" class="form-control filen" id="plano_registrar" name="">
				                                    </div>
				                                </div>
				                            </div>
				                            <div style="border-bottom: 1px solid #ccc;">
					                            <h3>Inmobiliarias</h3>
					                        </div>
					                        <div class="col-sm-12">
				                            	<div class="col-sm-4" style="padding-top: 10px;">
			                                        <select id="inmobiliaria_registrar" class="form-control form-group">
			                                        	<option value="">Seleccione</option>
			                                        	<?php foreach($inmobiliarias as $inmobiliaria): ?>
			                                        		<option value="<?php echo $inmobiliaria->id_inmobiliaria ?>"><?php echo $inmobiliaria->codigo . " - " . $inmobiliaria->nombre?></option>
			                                        	<?php endforeach ?>
			                                        </select>
					                            </div>
					                            <div class="col-sm-2" style="padding-top: 10px;">
					                            	<button type="button" class="btn btn-primary waves-effect" onclick="agregarInmobiliaria('#inmobiliaria_registrar', '#tableInmobiliariaRegistrar')">Agregar</button>
					                            </div>
					                            <div class="col-sm-12">
					                            	<table class="table table-bordered table-striped table-hover" id="tableInmobiliariaRegistrar">
					                            		<thead>
					                            			<tr>
					                            				<th>Inmobiliaria</th>
					                            				<th>Acciones</th>
					                            			</tr>
					                            		</thead>
					                            		<tbody></tbody>
					                            	</table>
					                            </div>
				                            </div>
				                            <div style="border-bottom: 1px solid #ccc;">
					                            <h3>Etapas / Zonas</h3>
					                        </div>
					                        <div class="col-sm-12">
				                            	<div class="col-sm-3" style="padding-top: 10px;">
			                                        <select id="etapa_registrar" class="form-control form-group">
			                                        	<option value="">Seleccione</option>
			                                        	<?php foreach($etapas as $etapa): ?>
			                                        		<option value="<?php echo $etapa->id_lista_valor ?>"><?php echo $etapa->nombre_lista_valor ?></option>
			                                        	<?php endforeach ?>
			                                        </select>
					                            </div>
					                            <div class="col-sm-3" style="padding-top: 10px;">
			                                        <select id="clasificacion_registrar" class="form-control form-group">
			                                        	<option value="">Seleccione</option>
			                                        	<?php foreach($clasificaciones as $clasificacion): ?>
			                                        		<option value="<?php echo $clasificacion->id_lista_valor ?>"><?php echo $clasificacion->nombre_lista_valor ?></option>
			                                        	<?php endforeach ?>
			                                        </select>
					                            </div>
					                            <div class="col-sm-3">
					                                <div class="form-group">
					                                    <div class="form-line">
					                                        <input type="text" class="form-control precio" id="precio_registrar" placeholder="Precio m2" maxlength="9">
					                                    </div>
					                                </div>
					                            </div>
					                            <div class="col-sm-2" style="padding-top: 10px;">
					                            	<button type="button" class="btn btn-primary waves-effect" onclick="agregarClasificacion('#etapa_registrar','#clasificacion_registrar', '#precio_registrar', '#tableClasificacionRegistrar')">Agregar</button>
					                            </div>
					                            <div class="col-sm-12">
					                            	<table class=" table table-bordered table-striped table-hover" id="tableClasificacionRegistrar">
					                            		<thead>
					                            			<tr>
					                            				<th class="text-center">Etapas</th>
					                            				<th class="text-center">Zona</th>
					                            				<th class="text-center">Precio m2</th>
					                            				<th class="text-center">Acciones</th>
					                            			</tr>
					                            		</thead>
					                            		<tbody></tbody>
					                            	</table>
					                            </div>

				                            </div>
				                             <div style="border-bottom: 1px solid #ccc;">
					                            <h3>Esquemas</h3>
					                        </div>
					                        <div class="col-sm-12">
				                            	<div class="col-sm-4" style="padding-top: 10px;">
			                                        <select id="esquemas_registrar" class="form-control form-group">
			                                        	<option value="">Seleccione</option>
			                                        	<?php foreach($esquemas as $esquema): ?>
			                                        		<option value="<?php echo $esquema->id_esquema ?>"><?php echo $esquema->descripcion?></option>
			                                        	<?php endforeach ?>
			                                        </select>
					                            </div>
					                            <div class="col-sm-2" style="padding-top: 10px;">
					                            	<button type="button" class="btn btn-primary waves-effect" onclick="agregarEsquemas('#esquemas_registrar', '#tableEsquemaRegistrar')">Agregar</button>
					                            </div>
					                            <div class="col-sm-12">
					                            	<table class="table table-bordered table-striped table-hover" id="tableEsquemaRegistrar">
					                            		<thead>
					                            			<tr>
					                            				<th>Esquema</th>
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
		                            	<div class="col-sm-4">
		                            		<label for="codigo_consultar">Código del Proyecto*</label>
			                                <div class="form-group">
			                                    <div class="form-line">
			                                        <input type="text" class="form-control mayusculas" id="codigo_consultar" disabled>
			                                    </div>
			                                </div>
			                            </div>
			                            <div class="col-sm-4">
			                                <label for="nombre_consultar">Nombre del Proyecto*</label>
			                                <div class="form-group">
			                                    <div class="form-line">
			                                        <input type="text" class="form-control mayusculas" id="nombre_consultar" disabled>
			                                    </div>
			                                </div>
			                            </div>
			                            <div class="col-sm-4">
			                                <label for="descripcion_consultar">Descripción del Proyecto*</label>
			                                <div class="form-group">
			                                    <div class="form-line">
			                                        <input type="text" class="form-control mayusculas" id="descripcion_consultar" disabled>
			                                    </div>
			                                </div>
			                            </div>
			                            <div class="col-sm-6">
		                            		<label for="director_consultar">Director*</label>
	                                    	<select id="director_consultar" disabled class="form-control">
	                                    		<option value="" selected>Seleccione</option>
	                                    		<?php foreach ($directores as $director): ?>
	                                    			<option value="<?= $director->id_usuario; ?>"><?= $director->nombres . " " . $director->paterno . " " . $director->materno ?></option>
	                                    		<?php endforeach ?>
	                                    	</select>
			                            </div>
			                            
			                            <div class="col-sm-3">
		                            		<label for="num_ventas_max_mes_registrar">Num. dias Vencidos*</label>
			                                <div class="form-group">
			                                    <div class="form-line">
			                                        <input type="text" class="form-control" id="dias_vencidos_view" name="dias_vencidos" onkeypress='return solonumeros(event)' placeholder="P. EJ. 2" disabled>
			                                    </div>
			                                </div>
			                            </div>

		                            	<div class="col-sm-3">
		                            		<label for="num_ventas_min_mes_registrar">% de Mora*</label>
			                                <div class="form-group">
			                                    <div class="form-line">
			                                        <input type="text" class="form-control" id="porcentaje_mora_view" name="porcentaje_nora"  onkeypress='return solonumeros(event)' placeholder="P. EJ. 1.2" disabled style="text-align: right;">
			                                    </div>
			                                </div>
			                            </div>
			                            <div class="col-sm-12">
			                                <label for="plano_consultar">Planos del Proyecto</label>
			                                <div class="form-group valid-required">
				                               <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="file" class="form-control" readonly="true" id="plano_consultar" name="">
				                                    </div>
				                                </div>
			                                </div>
			                            </div>


			                            <div class="col-sm-12">
			                            	<table class="table table-bordered table-striped table-hover" id="tableInmobiliariaConsultar">
			                            		<thead>
			                            			<tr>
			                            				<th>Inmobiliaria</th>
			                            				<th>&nbsp;</th>
			                            			</tr>
			                            		</thead>
			                            		<tbody></tbody>
			                            	</table>
			                            </div>
			                            <div class="col-sm-12">
					                            	<table class="table table-bordered table-striped table-hover" id="tableClasificacionConsultar">
					                            		<thead>
					                            			<tr>
					                            				<th>Etapa</th>
					                            				<th>Zona</th>
					                            				<th>Precio m2</th>
					                            				<th>&nbsp;</th>
					                            			</tr>
					                            		</thead>
					                            		<tbody></tbody>
					                            	</table>
					                            </div>
					                              <div class="col-sm-12">
			                            	<table class="table table-bordered table-striped table-hover" id="tableEsquemaConsultar">
			                            		<thead>
			                            			<tr>
			                            				<th>Esquema</th>
			                            				<th>&nbsp;</th>
			                            			</tr>
			                            		</thead>
			                            		<tbody></tbody>
			                            	</table>
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
			                            <form name="form_proyecto_actualizar" id="form_proyecto_actualizar" method="post">
			                            	<div class="col-sm-4">
			                            		<label for="codigo_editar">Código del Proyecto*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control mayusculas" name="codigo" id="codigo_editar" maxlength="50" placeholder="P. EJ. PXXXXXXXXX" required>
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
				                                <label for="nombre_editar">Nombre del Proyecto*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control mayusculas" name="nombre" id="nombre_editar" required>
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
				                                <label for="descripcion_editar">Descripción del Proyecto*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control mayusculas" name="descripcion" id="descripcion_editar" required>
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-6">
			                            		<label for="director_editar">Director*</label>
		                                    	<select name="director" id="director_editar" required class="form-control">
		                                    		<option value="" selected>Seleccione</option>
		                                    		<?php foreach ($directores as $director): ?>
		                                    			<option value="<?= $director->id_usuario; ?>"><?= $director->nombres . " " . $director->paterno . " " . $director->materno ?></option>
		                                    		<?php endforeach ?>
		                                    	</select>
				                            </div>
				                            <div class="col-sm-2">
			                            		<label for="cod_esquema_registrar">Indicador de Mora*</label>
		                                    	<div class="switch">
												    <label>
												      No
												      <input type="checkbox" id="indicador_actualizar">
												      <span class="lever"></span>
												      Si
												    </label>
												  </div>
				                            </div>

				                            <input type="hidden" name="indicador_mora_actualizar" id="indicador_mora_actualizar">


				                            

				                            <div class="col-sm-2">
			                            		<label for="num_ventas_max_mes_registrar">Num. dias Vencidos*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control" name="dias_vencidos" id="dias_vencidos_editar" onkeypress='return solonumeros(event)' placeholder="P. EJ. 2" disabled>
				                                    </div>
				                                </div>
				                            </div>

			                            	<div class="col-sm-2">
			                            		<label for="num_ventas_min_mes_editar">% de Mora*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control" name="porcentaje_nora" id="porcentaje_mora_editar" onkeypress='return solonumeros(event)' placeholder="P. EJ. 1.2" disabled style="text-align: right;">
				                                    </div>
				                                </div>
				                            </div>
										 <div class="col-sm-12">
				                                <label for="plano_editar">Planos del Proyecto</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="file" class="form-control" id="plano_editar" name="">
				                                    </div>
				                                </div>
				                            </div>


				                            <input type="hidden" name="id_proyecto" id="id_proyecto_editar">
				                            <div style="border-bottom: 1px solid #ccc;">
					                            <h3>Inmobiliarias</h3>
					                        </div>
					                        <div class="col-sm-12">
				                            	<div class="col-sm-4" style="padding-top: 10px;">
			                                        <select id="inmobiliaria_editar" class="form-control form-group">
			                                        	<option value="">Seleccione</option>
			                                        	<?php foreach($inmobiliarias as $inmobiliaria): ?>
			                                        		<option value="<?php echo $inmobiliaria->id_inmobiliaria ?>"><?php echo $inmobiliaria->codigo . " - " . $inmobiliaria->nombre?></option>
			                                        	<?php endforeach ?>
			                                        </select>
					                            </div>
					                            <div class="col-sm-2" style="padding-top: 10px;">
					                            	<button type="button" class="btn btn-primary waves-effect" onclick="agregarInmobiliaria('#inmobiliaria_editar', '#tableInmobiliariaEditar')">Agregar</button>
					                            </div>
					                            <div class="col-sm-12">
					                            	<table class="table table-bordered table-striped table-hover" id="tableInmobiliariaEditar">
					                            		<thead>
					                            			<tr>
					                            				<th>Inmobiliaria</th>
					                            				<th>Acciones</th>
					                            			</tr>
					                            		</thead>
					                            		<tbody></tbody>
					                            	</table>
					                            </div>
				                            </div>
				                            <div style="border-bottom: 1px solid #ccc;">
					                            <h3>Etapas / Zonas</h3>
					                        </div>
					                        <div class="col-sm-12">
				                            	<div class="col-sm-3" style="padding-top: 10px;">
			                                        <select id="etapa_editar" class="form-control form-group">
			                                        	<option value="">Seleccione</option>
			                                        	<?php foreach($etapas as $etapa): ?>
			                                        		<option value="<?php echo $etapa->id_lista_valor ?>"><?php echo $etapa->nombre_lista_valor ?></option>
			                                        	<?php endforeach ?>
			                                        </select>
					                            </div>
				                            	<div class="col-sm-3" style="padding-top: 10px;">
			                                        <select id="clasificacion_editar" class="form-control form-group">
			                                        	<option value="">Seleccione</option>
			                                        	<?php foreach($clasificaciones as $clasificacion): ?>
			                                        		<option value="<?php echo $clasificacion->id_lista_valor ?>"><?php echo $clasificacion->nombre_lista_valor ?></option>
			                                        	<?php endforeach ?>
			                                        </select>
					                            </div>
					                            <div class="col-sm-3">
					                                <div class="form-group">
					                                    <div class="form-line">
					                                        <input type="text" class="form-control precio" id="precio_editar" placeholder="Precio m2" maxlength="9">
					                                    </div>
					                                </div>
					                            </div>
					                            <div class="col-sm-2" style="padding-top: 10px;">
					                            	<button type="button" class="btn btn-primary waves-effect" onclick="agregarClasificacion('#etapa_editar','#clasificacion_editar', '#precio_editar', '#tableClasificacionEditar')">Agregar</button>
					                            </div>
					                            <div class="col-sm-12">
					                            	<table class="table table-bordered table-striped table-hover" id="tableClasificacionEditar">
					                            		<thead>
					                            			<tr>
					                            				<th>Etapa</th>
					                            				<th>Zona</th>
					                            				<th>Precio m2</th>
					                            				<th>Acciones</th>
					                            			</tr>
					                            		</thead>
					                            		<tbody></tbody>
					                            	</table>
					                            </div>
					                          </div>
					                           <div style="border-bottom: 1px solid #ccc;">
					                            <h3>Esquemas</h3>
					                        </div>
					                          <div class="col-sm-12">
				                            	<div class="col-sm-4" style="padding-top: 10px;">
			                                        <select id="esquemas_editar" class="form-control form-group">
			                                        	<option value="">Seleccione</option>
			                                        	<?php foreach($esquemas as $esquema): ?>
			                                        		<option value="<?php echo $esquema->id_esquema ?>"><?php echo $esquema->descripcion?></option>
			                                        	<?php endforeach ?>
			                                        </select>
					                            </div>
					                            <div class="col-sm-2" style="padding-top: 10px;">
					                            	<button type="button" class="btn btn-primary waves-effect" onclick="agregarEsquemas('#esquemas_editar', '#tableEsquemaEditar')">Agregar</button>
					                            </div>
					                            <div class="col-sm-12">
					                            	<table class="table table-bordered table-striped table-hover" id="tableEsquemaEditar">
					                            		<thead>
					                            			<tr>
					                            				<th>Esquema</th>
					                            				<th>Acciones</th>
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
     <script src="<?=base_url();?>assets/template/plugins/bootstrap-fileinput/js/plugins/piexif.min.js" type="text/javascript"></script>
    <script src="<?=base_url();?>assets/template/plugins/bootstrap-fileinput/js/plugins/purify.min.js" type="text/javascript"></script>
    <script src="<?=base_url();?>assets/template/plugins/bootstrap-fileinput/js/plugins/sortable.min.js" type="text/javascript"></script>
	<script src="<?=base_url();?>assets/template/plugins/bootstrap-fileinput/js/fileinput.js" type="text/javascript"></script>
    <script type="text/javascript" src="<?=base_url();?>assets/template/plugins/bootstrap-fileinput/themes/fa/theme.min.js"></script>
    <script src="<?=base_url();?>assets/template/plugins/bootstrap-fileinput/js/locales/es.js" type="text/javascript"></script>

    <script src="<?=base_url();?>assets/cpanel/Proyectos/js/proyectos.js"></script>
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
