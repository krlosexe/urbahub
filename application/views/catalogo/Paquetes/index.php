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
		                                            <th style="width:200px;">Título</th>
		                                            <th>Código</th>
		                                            <th style="text-align: right;">Precio</th>
		                                            <th>Planes</th>
		                                            <th>Servicios</th>
		                                            <th>Posición</th>
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
		                                	<button class="btn btn-warning waves-effect" onclick="statusMultiplePaquetes('Paquetes/status_multiple_paquetes', 1, 'activar')">Activar seleccionados</button>
		                                </div>
		                                <div class="col-md-2 actualizar ocultar">
		                                	<button class="btn btn-warning waves-effect" onclick="statusMultiplePaquetes('Paquetes/status_multiple_paquetes', 2, 'desactivar')">Desactivar seleccionados</button>
		                                </div>
		                                <input type="hidden" id="eliminados" name="eliminados" value="0">
		                                <input type="hidden" id="noEliminados" name="noEliminados" value="0">
		                            </div>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        <!-- Cierre del cuadro de la tabla -->

		        <!-- Comienzo del cuadro de registrar Paquetes -->
					<div class="row clearfix ocultar" id="cuadro2">
		                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                    <div class="card">
		                        <div class="header">
		                            <h2>Registro de <?php echo $breadcrumbs[0]["nombre_lista_vista"]; ?></h2>
		                        </div>
		                         <div class="body">
		                        	<div class="table-responsive">
		                        		<form name="form_paquetes_registrar" id="form_paquetes_registrar" method="post">
			                        		
			                            	<!--<div class="col-sm-4">
			                            		<label for="servicio_registrar">Servicios*</label>
		                                    	<select name="servicio" id="servicio_registrar" class="form-control" required>
		                                    		<option value="" selected>Seleccione</option>
		                                    		<?php foreach ($servicios as $servicio): ?>
		                                    			<?php if ($servicio['status']==true){ ?>
		                                    			<option value="<?= $servicio["id_servicios"]; ?>" status="<?=$servicio['status'] ?>"><?= $servicio["descripcion"]; ?></option>
		                                    			<?php } ?>
		                                    		<?php endforeach ?>
		                                    	</select>
				                            </div>-->
				                            <!--<div class="col-sm-6">
			                            		<label for="valor_registrar">Valor*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control mayusculas" name="valor" id="valor_registrar" placeholder="P. EJ. XXXX (X)" required>
				                                    </div>
				                                </div>
				                            </div>-->


											<div class="form-group col-sm-12">
												<div class="col-sm-12" style="padding:0px;">
													<label for="cod_esquema_registrar">Membresia?</label>
													<div class="switch">
														<label>
															No
															<input type="checkbox" id="indicador_membresia" checked = "checked">
															
															<span class="lever"></span>
															Si
														</label>
													</div>

													<input type="hidden" name="indicador_membresia" id="membresia">
												</div>
											</div>



				                            <div class="col-sm-6" id="codigo_div_registrar">
			                            		<label for="codigo_registrar">Código*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control mayusculas" name="codigo_registrar" id="codigo_registrar" placeholder="P. EJ. XXXX (X)" required>
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-6" id="descripcion_div_registrar">
			                            		<label for="descripcion_registrar">Descripción*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control mayusculas" name="descripcion_registrar" id="descripcion_registrar" placeholder="P. EJ. XXXX (X)" required>
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
			                            		<label for="plan_registrar">Plan*</label>
		                                    	<select name="plan" id="plan_registrar" class="form-control" >
		                                    		<option value="" selected>Seleccione</option>
		                                    		<?php foreach ($planes as $plan): ?>
		                                    			<?php if (($plan['status']==true)&&($plan['tiene_paquete']==false)){ ?> 
		                                    				<option value="<?= $plan["id_planes"]; ?>" status="<?=$plan['status'] ?>"><?= $plan["titulo"]." ".$plan["descripcion"]." ".$plan['tiene_paquete']; ?></option>
		                                    			<?php } ?> 
		                                    		<?php endforeach ?>
		                                    	</select>
				                            </div>
				                            <div class="col-sm-3 remove">
			                            		<label for="precio_registrar">Precio*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="precio form-control" name="precio" id="precio_registrar" onkeypress='return valida(event)' value="0.00" >
				                                    </div>
				                                </div>
				                            </div>

				                            <div class="col-sm-2 remove">
                                				<div class="col-sm-12" style="padding: 0px;">
				                            		<label for="cod_servicio_registrar">¿Se muestra en la web?*</label>
			                                    	<div class="switch">
													    <label>
													      No
													      <input type="checkbox" id="muestra_web_registrar">
													      <span class="lever"></span>
													      Si
													    </label>
													</div>
													 <input type="hidden" name="indicador_muestra_web_registrar" id="indicador_muestra_web_registrar" value="N">
					                            </div>
                                			</div>



				                            <div class="col-sm-3 remove">
				                                <label for="posicion_paquetes_registrar">Posición*</label>
		                                        <select id="posicion_paquetes_registrar"  class="form-control form-group" name="posicion_paquetes_registrar	">
		                                        	<option value="">Seleccione</option>
		                                        </select>
		                                        <!-- 
		                                 		<input type="text" name="posicionInicialRegistrar" id="posicionInicialRegistrar">
		                                        -->
				                            </div>
				                            <div class="col-sm-12" style="padding: 0px;">
					                            
                                			</div>
				                            <div class="col-sm-12" style="padding: 0px;">

				                            	 <div class="header">
						                            <h2>
						                                Agregar Servicios
						                            </h2>
						                           
						                        </div>


				                            <hr></hr>
				                            	
				                            	<div class="col-sm-4">
				                            		<label for="servicio_registrar">Servicios*</label>
			                                    	<select name="servicio" id="servicio_registrar" class="form-control">
			                                    		<option value="" selected>Seleccione</option>

			                                    		<?php foreach ($servicios as $servicio): ?>
			                                    			<?php if ($servicio['status']==true){ ?>

			                                    				<?php if ($servicio["tipo_servicio"] == "5dbb135ae31dd906e43b7d32" || $servicio["tipo_servicio"] == "5cacee2c2e7bddfe4c8b4568"): ?>
			                                    					<option value="<?= $servicio["id_servicios"]."|".$servicio['tipo']."|".$servicio['categoria'];?>" status="<?=$servicio['status'] ?>" tipo="<?=$servicio['tipo'] ?>"><?= $servicio["descripcion"]; ?></option>
			                                    				<?php endif ?>
			                                    			<?php } ?>
			                                    		<?php endforeach ?>
			                                    	</select>
					                            </div>


					                            <div class="col-sm-4 remove">
						                                <label for="posicion_servicios_registrar">Posición*</label>
				                                        <select id="posicion_servicios_registrar" class="form-control form-group" name="posicion_servicios_registrar">
				                                        	<option value="">Seleccione</option>
				                                        </select>
				                                        <!-- -->
				                                        <input type="hidden" name="posicionInicialRegistrarServicios" id="posicionInicialRegistrarServicios">
				                                        <!-- -->
						                            </div>


					                            
					                            					                            
					                            <div class="col-sm-12" style="padding: 0px">
					                            	


						                            <div class="col-sm-2 remove">
		                                				<div class="col-sm-12">
						                            		<label for="cod_esquema_registrar">Servicio Ilimitado*</label>
					                                    	<div class="switch">
															    <label>
															      No
															      <input type="checkbox" id="indicador_jornadas_registrar">
															      <span class="lever"></span>
															      Si
															    </label>
															</div>
															 <input type="hidden" name="indicador_jornadas_valor_registrar" id="indicador_jornadas_valor_registrar" value="N">
							                            </div>
		                                			</div>


						                            <div class="col-sm-2 remove">
		                                				<div class="col-sm-12">
						                            		<label for="cod_servicio_registrar">Es consumible*</label>
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
		                                			</div>



		                                			<div class="col-sm-4 remove">
					                            		<label for="valor_registrar">Valor*</label>
						                                <div class="form-group">
						                                    <div class="form-line">
						                                        <input type="text" class="form-control mayusculas" name="valor" id="valor_registrar" placeholder="P. EJ. XXXX (X)">
						                                    </div>
						                                </div>
						                            </div>

		                                			<div class="col-sm-2" style="padding-top: 25px;">
						                            	<button type="button" class="btn btn-primary waves-effect" onclick="agregarServicio('#servicio_registrar', '#tableRegistrar', '#valor_registrar','#plan_registrar','#indicador_servicio_consumible_registrar','#posicion_servicios_registrar', '#indicador_jornadas_valor_registrar')">Agregar</button>
						                            	<input type="hidden" name="proceso_registrar" id="proceso_registrar">
						                            </div>
						                        </div>    
					                            <div class="col-sm-12" >
					                            	<table class="table table-bordered table-striped table-hover" id="tableRegistrar">
					                            		<thead>
					                            			<tr>
					                            				<th>Código</th>
					                            				<!--<th>Plan</th>-->
					                            				<th>Servicio</th>
					                            				<th>Valor Servicio</th>
					                            				<th>Servicios Ilimitados</th>
					                            				<th>Consumible</th>
					                            				<th>Posición</th>
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
		                                        <input type="submit" value="Registrar" class="btn btn-success waves-effect">
			                                </div>
			                            </form>    
			                        </div>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        <!-- Cierre del cuadro de registrar Paquetes -->

		        <!-- Comienzo del cuadro de consultar Paquetes -->
					<div class="row clearfix ocultar" id="cuadro3">
		                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                    <div class="card">
		                        <div class="header">
		                            <h2>Consultar <?php echo $breadcrumbs[0]["nombre_lista_vista"]; ?></h2>
		                        </div>
		                        <div class="body">
		                        	<div class="table-responsive">
		                        		
		                            
		                            	<div class="col-sm-12" style="padding: 0px;">
											<div class="form-group col-sm-12">
												<div class="col-sm-12" style="padding:0px;">
													<label for="cod_esquema_registrar">Membresia?</label>
													<div class="switch">
														<label>
															No
															<input type="checkbox" name="indicador_membresia" id="indicador_membresia_view" checked = "checked">
															<span class="lever"></span>
															Si
														</label>
													</div>
												</div>
											</div>
			                            	<div class="col-sm-6" id="codigo_div_consultar">
			                            		<label for="codigo_consultar">Código*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control mayusculas" name="codigo_consultar" id="codigo_consultar" placeholder="P. EJ. XXXX (X)" disabled>
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-6" id="descripcion_div_consultar">
			                            		<label for="descripcion_consultar">Descripción*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control mayusculas" name="descripcion_consultar" id="descripcion_consultar" placeholder="P. EJ. XXXX (X)" disabled>
				                                    </div>
				                                </div>
				                            </div>
				                            
				                            <div class="col-sm-4">
			                            		<label for="plan_consultar">Plan*</label>
		                                    	<select id="plan_consultar" class="form-control" disabled>
		                                    		<option value="" selected>Seleccione</option>
		                                    		<?php foreach ($planes as $plan): ?>
		                                    			
		                                    			<option value="<?= $plan["id_planes"]; ?>"><?= $plan["descripcion_planes"]; ?></option>

		                                    		<?php endforeach ?>
		                                    	</select>
				                            </div>
				                            <div class="col-sm-3 remove">
			                            		<label for="precio_consultar">Precio*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="precio form-control" name="precio_consultar" id="precio_consultar" onkeypress='return valida(event)' value="0.00" disabled>
				                                    </div>
				                                </div>
				                            </div>

				                            <div class="col-sm-2 remove">
                                				<div class="col-sm-12" style="padding: 0px;">
				                            		<label for="muestra_web_consultar">¿Se muestra en la web?*</label>
			                                    	<div class="switch">
													    <label>
													      No
													      <input type="checkbox" id="muestra_web_consultar">
													      <span class="lever"></span>
													      Si
													    </label>
													</div>
													 <input type="hidden" name="indicador_muestra_web_consultar" id="indicador_muestra_web_consultar" value="N">
					                            </div>
                                			</div>


				                            <div class="col-sm-3 remove">
				                                <label for="posicion_paquetes_registrar">Posición*</label>
		                                        <input type="text" class="form-control" name="posicion_paquetes_consultar" id="posicion_paquetes_consultar" disabled>
				                            </div>
				                           
                                			<!-- -->
			                            	<div class="col-sm-12" style="padding: 0px;">
				                            <hr></hr>
					                            
				                            	<div class="col-sm-4">
				                            		<label for="servicio_consultar">Servicios*</label>
			                                    	<select name="servicio" id="servicio_consultar" class="form-control" disabled>
			                                    		<option value="" selected>Seleccione</option>
			                                    		<?php foreach ($servicios as $servicio): ?>
			                                    			<?php if ($servicio['status']==true){ ?>
			                                    			<option value="<?= $servicio["id_servicios"]."|".$servicio['categoria']; ?>" status="<?=$servicio['status'] ?>"><?= $servicio["descripcion"]; ?></option>
			                                    			<?php } ?>
			                                    		<?php endforeach ?>
			                                    	</select>
					                            </div>
					                            <div class="col-sm-4 remove">
				                            		<label for="valor_consultar">Valor*</label>
					                                <div class="form-group">
					                                    <div class="form-line">
					                                        <input type="text" class="form-control mayusculas" name="valor" id="valor_consultar" placeholder="P. EJ. XXXX (X)" required>
					                                    </div>
					                                </div>
					                            </div>
					                            <div class="col-sm-12 remove" style="padding: 0px">
					                            	<div class="col-sm-4">
						                                <label for="posicion_paquetes_actualizar">Posición*</label>
				                                        <select id="posicion_paquetes_actualizar" required class="form-control form-group" name="posicion_paquetes_actualizar">
				                                        	<option value="">Seleccione</option>
				                                        </select>
						                            </div>
						                            <div class="col-sm-2 remove">
			                            				<div class="col-sm-12">
						                            		<label for="cod_servicio_consultar">Es consumible*</label>
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
			                            		</div>	
			                            	</div>
				                            
				                            <!--<div class="col-sm-2" style="padding-top: 25px;">
				                            	<button type="button" class="btn btn-primary waves-effect" onclick="agregarServicio('#servicio_registrar', '#tableRegistrar', '#valor_registrar')">Agregar</button>
				                            </div>-->
				                            <div class="col-sm-12" >
				                            	<table class="table table-bordered table-striped table-hover" id="tableConsultar">
				                            		<thead>
				                            			<tr>
				                            				<th>Código</th>
				                            				<!--<th>Plan</th>-->
				                            				<th>Servicio</th>
				                            				<th>Valor</th>
				                            				<th>Servicio Ilimitado</th>
				                            				<th>Consumible</th>
				                            				<th>Posición</th>
				                            				<th>Acciones</th>
				                            			</tr>
				                            		</thead>
				                            		<tbody id="tbodyConsultar"></tbody>
				                            	</table>
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
			                        		<!-- -->

											<div class="form-group col-sm-12">
												<div class="col-sm-12" style="padding:0px;">
													<label for="cod_esquema_registrar">Membresia?</label>
													<div class="switch">
														<label>
															No
															<input type="checkbox" id="indicador_membresia_edit" checked = "checked">
															
															<span class="lever"></span>
															Si
														</label>
													</div>

													<input type="hidden" name="indicador_membresia" id="membresia_edit">
												</div>
											</div>



			                        		<div class="col-sm-6" id="codigo_div_editar">
			                            		<label for="codigo_editar">Código*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control mayusculas" name="codigo_editar" id="codigo_editar" placeholder="P. EJ. XXXX (X)" required>
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-6" id="descripcion_div_editar">
			                            		<label for="descripcion_editar">Descripción*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control mayusculas" name="descripcion_editar" id="descripcion_editar" placeholder="P. EJ. XXXX (X)" required>
				                                    </div>
				                                </div>
				                            </div>
				                            
			                        		<!-- -->
			                        		<div class="col-sm-4">
			                            		<label for="plan_editar">Plan*</label>
		                                    	<select name="plan" id="plan_editar" class="form-control"  required="">
		                                    		<option value="" selected>Seleccione</option>
		                                    	</select>
				                            </div>
				                            <div class="col-sm-3 remove">
			                            		<label for="precio_editar">Precio*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="precio form-control" name="precio_editar" id="precio_editar" onkeypress='return valida(event)' value="0.00" required>
				                                    </div>
				                                </div>
				                            </div>


				                            <div class="col-sm-2 remove">
                                				<div class="col-sm-12" style="padding: 0px;">
				                            		<label for="muestra_web_modificar">¿Se muestra en la web?*</label>
			                                    	<div class="switch">
													    <label>
													      No
													      <input type="checkbox" id="muestra_web_modificar">
													      <span class="lever"></span>
													      Si
													    </label>
													</div>
													 <input type="hidden" name="indicador_muestra_web_modificar" id="indicador_muestra_web_modificar" value="N">
					                            </div>
                                			</div>



				                            <div class="col-sm-3 remove">
				                                <label for="posicion_paquetes_editar">Posición*</label>
		                                        <select id="posicion_paquetes_editar" required class="form-control form-group" name="posicion_paquetes_editar">
		                                        	<option value="">Seleccione</option>
		                                        </select>
		                                        
		                                         <input type="hidden" name="inicial" id="inicial">
				                            </div>
			                            	<!--<div class="col-sm-4">
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
				                            </div>-->
				                            <!-- -->

				                            <!-- -->
				                            <div class="col-sm-12" style="padding: 0px;">

				                            	<div class="header">
						                            <h2>
						                                Agregar Servicios
						                            </h2>
						                           
						                        </div>


				                            	<hr></hr>
				                            	
				                            	<div class="col-sm-4">
				                            		<label for="servicio_editar">Servicios*</label>
			                                    	<select name="servicio" id="servicio_editar" class="form-control">
			                                    		<option value="" selected>Seleccione</option>

			                                    		<?php foreach ($servicios as $servicio): ?>
			                                    			<?php if ($servicio['status']==true){ ?>
			                                    				<?php if ($servicio["tipo_servicio"] == "5d8ce5022221b4b0006ed7b3" || $servicio["tipo_servicio"] == "5cacee2c2e7bddfe4c8b4568"): ?>
			                                    					<option value="<?= $servicio["id_servicios"]."|".$servicio['tipo']."|".$servicio['categoria'];?>" status="<?=$servicio['status'] ?>" tipo="<?=$servicio['tipo'] ?>"><?= $servicio["descripcion"]; ?></option>
			                                    				<?php endif ?>
			                                    			<?php } ?>
			                                    		<?php endforeach ?>
			                                    	</select>
					                            </div>


					                            <div class="col-sm-4 remove">
						                                <label for="posicion_servicios_editar">Posición*</label>
				                                        <select id="posicion_servicios_editar"  class="form-control form-group" name="posicion_servicios_editar">
				                                        	<option value="">Seleccione</option>
				                                        </select>
				                                        <!-- -->
				                                 		<input type="hidden" name="posicionInicialModificar" id="posicionInicialModificar">
				                                        <!-- -->
						                            </div>





					                            
					                            
					                            <div class="col-sm-12 remove" style="padding: 0px">
					                            	


						                            <div class="col-sm-2">
		                                				<div class="col-sm-12">
						                            		<label for="cod_esquema_registrar">Servicio Ilimitado*</label>
					                                    	<div class="switch">
															    <label>
															      No
															      <input type="checkbox" id="indicador_jornadas_editar">
															      <span class="lever"></span>
															      Si
															    </label>
															</div>
															 <input type="hidden" name="indicador_jornadas_valor_editar" id="indicador_jornadas_valor_editar" value="N">
							                            </div>
		                                			</div>


						                            <div class="col-sm-2 remove">
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



		                                			<div class="col-sm-4 remove">
					                            		<label for="valor_editar">Valor*</label>
						                                <div class="form-group">
						                                    <div class="form-line">
						                                        <input type="text" class="form-control mayusculas" name="valor" id="valor_editar" placeholder="P. EJ. XXXX (X)" >
						                                    </div>
						                                </div>
						                            </div>
		                                			<div class="col-sm-2" style="padding-top: 25px;">
						                            	<button type="button" class="btn btn-primary waves-effect" onclick="agregarServicio('#servicio_editar', '#tableEditar', '#valor_editar','#plan_editar','#indicador_servicio_consumible_modificar','#posicion_servicios_editar', '#indicador_jornadas_valor_editar')">Agregar</button>
						                            	<input type="hidden" name="proceso_editar" id="proceso_editar">
						                            </div>
					                            </div>
					                            <div class="col-sm-12" >
					                            	<table class="table table-bordered table-striped table-hover" id="tableEditar">
					                            		<thead>
					                            			<tr>
					                            				<th>Código</th>
					                            				<!--<th>Plan</th>-->
					                            				<th>Servicio</th>
					                            				<th>Valor</th>
					                            				<th>Servicio Ilimitado</th>
					                            				<th>Consumible</th>
					                            				<th>Posición</th>
					                            				<th>Acciones</th>
					                            			</tr>
					                            		</thead>
					                            		<tbody  id="tbodyEditar" ></tbody>
					                            	</table>
					                            </div>
				                            </div>
	                            			<br>
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
