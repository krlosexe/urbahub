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
		                                            <th>Tipo valor</th>
		                                            <th>Tipo servicio</th>
		                                            <th>Código</th>
		                                            <th>Descripción</th>
		                                            <!--<th>Horas</th>
		                                            <th>Indicador de servicios</th>-->
		                                            <th>Monto</th>
		                                            <th>Fecha de Registro</th>
		                                            <th>Registrado Por</th>
		                                        </tr>
		                                    </thead>
		                                    <tbody></tbody>
		                                </table>
		                                <div class="col-md-2 eliminar ocultar">
		                                	<button class="btn btn-danger waves-effect" onclick="eliminarMultiple('Servicios/eliminar_multiple_servicios')">Eliminar seleccionados</button>
		                                </div>
		                                <div class="col-md-2 actualizar ocultar">
		                                	<button class="btn btn-warning waves-effect" onclick="statusMultiple('Servicios/status_multiple_servicio', 1, 'activar')">Activar seleccionados</button>
		                                </div>
		                                <div class="col-md-2 actualizar ocultar">
		                                	<button class="btn btn-warning waves-effect" onclick="statusMultiple('Servicios/status_multiple_servicio', 2, 'desactivar')">Desactivar seleccionados</button>
		                                </div>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        <!-- Cierre del cuadro de la tabla -->

		        <!-- Comienzo del cuadro de registrar Servicios -->
					<div class="row clearfix ocultar" id="cuadro2">
		                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                    <div class="card">
		                        <div class="header">
		                            <h2>Registro de <?php echo $breadcrumbs[0]["nombre_lista_vista"]; ?></h2>
		                        </div>
		                         <div class="body">
		                        	<div class="table-responsive">
		                        		<form name="form_servicios_registrar" id="form_servicios_registrar" method="post">


										<div class="form-group col-sm-12">
											<div class="col-sm-12" style="padding:0px;">
												<label for="cod_esquema_registrar">Membresia?</label>
												<div class="switch">
													<label>
														No
														<input type="checkbox" name="indicador_membresia" id="indicador_membresia" checked="checked">
														<span class="lever"></span>
														Si
													</label>
												</div>
												<input type="hidden" name="membresia" id="membresia" value="N">
											</div>
										</div>


			                            	<div class="col-sm-4">
			                            		<label for="tipo_registrar">Tipo valor*</label>
		                                    	<select name="tipo" id="tipo_registrar" class="form-control" required>
	                                    			<option value="N">Número</option>
	                                    			<option value="C">Caracter</option>
		                                    	</select>
				                            </div>

				                            <div class="col-sm-4">
			                            		<label for="tipo_serv_registrar">Categorias*</label>
		                                    	<select name="categorias" id="categorias_registrar" class="form-control" required style="text-transform: capitalize;">
		                                    		<option value="Horas">HORAS</option>
		                                    		<option value="Cafe Gourmet">CAFE GOURMET</option>
		                                    		<option value="Sala de juntas">SALA DE JUNTAS</option>
		                                    		<option value="Servicios generales">SERVICIOS GENERALES</option>
		                                    		<option value="Servicios adicionales">SERVICIOS ADICIONALES</option>
		                                    		
		                                    	</select>
				                            </div>

				                            <div class="col-sm-4">
			                            		<label for="tipo_serv_registrar">Tipo*</label>
		                                    	<select name="tipo_serv_registrar" id="tipo_serv_registrar" class="form-control" required style="text-transform: capitalize;">
		                                    		
		                                    		<?php foreach ($tipoServ as $tipo_serv): ?>
		                                    			<option value="<?= $tipo_serv["id_tipo_serv"]; ?>"><?= $tipo_serv["titulo"]; ?></option>
		                                    		<?php endforeach ?>
		                                    	</select>
				                            </div>






			                            	<div class="col-sm-4">
			                            		<label for="cod_servicio_registrar">Código*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" name="cod_servicio" class="form-control mayusculas" id="cod_servicio_registrar" placeholder="P. EJ. XXXX00001" required>
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
				                            <!--<div class="col-sm-4">
                                				<div class="col-sm-12">
				                            		<label for="cod_servicio_registrar">Es servicio consumible*</label>
			                                    	<div class="switch">
													    <label>
													      No
													      <input type="checkbox" id="indicador_servicios_registrar">
													      <span class="lever"></span>
													      Si
													    </label>
													</div>
													 <input type="hidden" name="indicador_servicio_consumible_registrar" id="indicador_servicio_consumible_registrar" value="N">
					                            </div>
                                			</div>-->
                                			<!--<div class="col-sm-4" id="_registrar">
			                            		<label for="horas_registrar">Horas</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control mayusculas" name="horas_registrar" id="horas_registrar" placeholder="P. EJ. XXXX (X)" onkeypress='return valida(event)' data-inputmask="'alias': 'numeric'">
				                                    </div>
				                                </div>
				                            </div>-->
				                            <div class="col-sm-4">
			                            		<label for="monto_registrar">Monto*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="precio form-control" name="monto" id="monto_registrar" onkeypress='return solonumeros(event)' placeholder="P. EJ. 2" required data-inputmask="'alias': 'numeric'" style="text-align: right;">
				                                    </div>
				                                </div>
				                            </div>
				                             <input type="hidden" name="id_servicio" id="id_servicio_registrar">
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
		        <!-- Cierre del cuadro de registrar Servicios -->

		        <!-- Comienzo del cuadro de consultar Servicios -->
					<div class="row clearfix ocultar" id="cuadro3">
		                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                    <div class="card">
		                        <div class="header">
		                            <h2>Consultar <?php echo $breadcrumbs[0]["nombre_lista_vista"]; ?></h2>
		                        </div>
		                        <div class="body">
		                        	<div class="table-responsive">

										<div class="form-group col-sm-12">
											<div class="col-sm-12" style="padding:0px;">
												<label for="cod_esquema_registrar">Membresia?</label>
												<div class="switch">
													<label>
														No
														<input type="checkbox" name="indicador_membresia" id="indicador_membresia_view" checked="checked">
														<span class="lever"></span>
														Si
													</label>
												</div>
												<input type="hidden" name="membresia" id="membresia_view" value="N">
											</div>
										</div>

		                            	<div class="col-sm-4">
		                            		<label for="tipo_consultar">Tipo*</label>
	                                    	<select id="tipo_consultar" class="form-control" disabled>
	                                    		<option value="" selected>Seleccione</option>
                                    			<option value="N">Número</option>
                                    			<option value="C">Caracter</option>
	                                    	</select>
			                            </div>
				                        <div class="col-sm-4">
		                            		<label for="tipo_serv_registrar">Categorias*</label>
	                                    	<select name="categorias" id="categorias_view" class="form-control" disabled style="text-transform: uppercase;">
	                                    		<option value="" selected>Seleccione</option>
	                                    		<option value="Cafe Gourmet">Cafe Gourmet</option>
	                                    		<option value="Sala de juntas">Sala de juntas</option>
	                                    		<option value="Servicios generales">Servicios generales</option>
	                                    		<option value="Servicios adicionales">Servicios adicionales</option>
	                                    		<option value="Horas">Horas</option>
	                                    	</select>
			                            </div>

			                            <div class="col-sm-4">
		                            		<label for="tipo_serv_consultar">Tipo*</label>
	                                    	<select name="tipo_serv_consultar" id="tipo_serv_consultar" class="form-control" disabled>
	                                    		<option value="" selected>Seleccione</option>
	                                    		<?php foreach ($tipoServ as $tipo_serv): ?>
	                                    			<option value="<?= $tipo_serv["id_tipo_serv"]; ?>"><?= $tipo_serv["titulo"]; ?></option>
	                                    		<?php endforeach ?>
	                                    	</select>
				                        </div>





		                            	<div class="col-sm-4">
		                            		<label for="cod_servicio_consultar">Código*</label>
			                                <div class="form-group">
			                                    <div class="form-line">
			                                        <input type="text" class="form-control mayusculas" id="cod_servicio_consultar" placeholder="P. EJ. XXXX00001" disabled>
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
			                            <!--<div class="col-sm-4">
                            				<div class="col-sm-12">
			                            		<label for="cod_servicio_consultar">Es servicio consumible*</label>
		                                    	<div class="switch" >
												    <label>
												      No
												      <input type="checkbox" id="indicador_servicios_consultar" disabled>
												      <span class="lever"></span>
												      Si
												    </label>
												</div>
				                            </div>
                            			</div>
                            			<div class="col-sm-4" id="horas_div_consultar">
		                            		<label for="horas_consultar">Horas</label>
			                                <div class="form-group">
			                                    <div class="form-line">
			                                        <input type="text" class="form-control mayusculas" name="horas_consultar" id="horas_consultar" placeholder="P. EJ. XXXX (X)" onkeypress='return valida(event)' data-inputmask="'alias': 'numeric'">
			                                    </div>
			                                </div>
			                            </div>-->
			                            <div class="col-sm-4">
			                            		<label for="monto_registrar">Monto*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="precio form-control"  id="monto_consultar"  placeholder="P. EJ. 2"  data-inputmask="'alias': 'numeric'" disabled style="text-align: right;">
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
		        <!-- Cierre del cuadro de consultar Servicios -->

		        <!-- Comienzo del cuadro de editar Servicios -->
					<div class="row clearfix ocultar" id="cuadro4">
		                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                    <div class="card">
		                        <div class="header">
		                            <h2>Editar de <?php echo $breadcrumbs[0]["nombre_lista_vista"]; ?></h2>
		                        </div>
		                        <div class="body">
		                        	<div class="table-responsive">
		                        		 <form name="form_servicios_editar" id="form_servicios_editar" method="post">

										 <div class="form-group col-sm-12">
											<div class="col-sm-12" style="padding:0px;">
												<label for="cod_esquema_registrar">Membresia?</label>
												<div class="switch">
													<label>
														No
														<input type="checkbox" name="indicador_membresia" id="indicador_membresia_edit" checked="checked">
														<span class="lever"></span>
														Si
													</label>
												</div>
												<input type="hidden" name="membresia" id="membresia_edit" value="N">
											</div>
										</div>


			                            	<div class="col-sm-4">
			                            		<label for="tipo_editar">Tipo*</label>
		                                    	<select name="tipo" id="tipo_editar" class="form-control" required>
		                                    		<option value="" selected>Seleccione</option>
	                                    			<option value="N">Número</option>
	                                    			<option value="C">Caracter</option>
		                                    	</select>
				                            </div>
					                        <div class="col-sm-4">
			                            		<label for="tipo_serv_registrar">Categorias*</label>
		                                    	<select name="categorias" id="categorias_edit" class="form-control" required style="text-transform: uppercase;">
		                                    		<option value="" selected>Seleccione</option>
		                                    		<option value="Cafe Gourmet">Cafe Gourmet</option>
		                                    		<option value="Sala de juntas">Sala de juntas</option>
		                                    		<option value="Servicios generales">Servicios generales</option>
		                                    		<option value="Servicios adicionales">Servicios adicionales</option>
		                                    		<option value="Horas">Horas</option>
		                                    	</select>
				                            </div>


				                            <div class="col-sm-4">
			                            		<label for="tipo_serv_editar">Tipo*</label>
		                                    	<select name="tipo_serv_editar" id="tipo_serv_editar" class="form-control" required>
		                                    		<option value="" selected>Seleccione</option>
		                                    		<?php foreach ($tipoServ as $tipo_serv): ?>
		                                    			<option value="<?= $tipo_serv["id_tipo_serv"]; ?>"><?= $tipo_serv["titulo"]; ?></option>
		                                    		<?php endforeach ?>
		                                    	</select>
					                        </div>





			                            	<div class="col-sm-4">
			                            		<label for="cod_servicio_editar">Código*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" name="cod_servicio" class="form-control mayusculas" id="cod_servicio_editar" placeholder="P. EJ. XXXX00001" required>
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
			                            		<label for="descripcion_editar">Descripción*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control mayusculas" name="descripcion" id="descripcion_editar" placeholder="P. EJ. XXXX FASE X" required>
				                                    </div>
				                                </div>
				                            </div>
				                            <!--<div class="col-sm-4">
                                				<div class="col-sm-12">
				                            		<label for="cod_servicio_modificar">Es servicio consumible*</label>
			                                    	<div class="switch">
													    <label>
													      No
													      <input type="checkbox" id="indicador_servicios_modificar">
													      <span class="lever"></span>
													      Si
													    </label>
													</div>
													 <input type="hidden" name="indicador_servicio_consumible_modificar" id="indicador_servicio_consumible_modificar" value="N">
					                            </div>
                                			</div>
                                			<div class="col-sm-4" id="_registrar">
			                            		<label for="horas_modificar">Horas</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control mayusculas" name="horas_modificar" id="horas_modificar" placeholder="P. EJ. XXXX (X)" onkeypress='return valida(event)' data-inputmask="'alias': 'numeric'">
				                                    </div>
				                                </div>
				                            </div>-->
				                            <div class="col-sm-4">
			                            		<label for="monto_registrar">Monto*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="precio form-control" name="monto" id="monto_editar" onkeypress='return solonumeros(event)' placeholder="P. EJ. 2" required data-inputmask="'alias': 'numeric'" style="text-align: right;">
				                                    </div>
				                                </div>
				                            </div>
				                             <input type="hidden" name="id_servicio" id="id_servicio_editar">
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
    <script src="<?=base_url();?>assets/cpanel/Servicios/js/servicios.js"></script>
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
