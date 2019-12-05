<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
<!DOCTYPE html>
<html>
	<link href="<?=base_url();?>assets/template/plugins/bootstrap-fileinput/css/fileinput.css" media="all" rel="stylesheet" type="text/css" />
	
	<link href="<?=base_url();?>assets/template/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
	<link href="<?=base_url();?>assets/template/plugins/bootstrap-datetimepicker-master/css/bootstrap-datetimepicker.css" rel="stylesheet" />
	<style type="text/css">
		.file-drop-zone{
			height: auto;
		}
	</style>
	<!-- select2 -->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
	<!-- -->
		<?php

		 if(($permiso[0]->general==1 && $permiso[0]->detallada==1 && $permiso[0]->registrar==1 && $permiso[0]->actualizar==1 && $permiso[0]->eliminar==1) OR $permiso[0]->status==false): ?>
			<script src="<?=base_url();?>assets/cpanel/js/permiso.js"></script>
		<?php endif ?>
	<body class="theme-blue">
		<input type="hidden" id="ruta" value="<?=base_url();?>" name="ruta">

		<input type="hidden" id="len_num">

		<section class="content">
	        <div class="container-fluid">
	        	<div id="alertas"></div>
	        	<div class="block-header">
	                <ol class="breadcrumb breadcrumb-col-cyan">
                        <li><a href="javascript:void(0);"><?php echo $breadcrumbs[0]["nombre_modulo_vista"]; ?></a></li>
                        <li><?php echo $breadcrumbs[0]["nombre_lista_vista"]; ?></li>
                    </ol>
	            </div>
	            <input type="hidden" name="id_membresia_from_to" id="id_membresia_from_to" value="<?php echo $id_membresia; ?>">
		    	<!-- Comienzo del cuadro de la tabla -->
				<div class="row clearfix" id="cuadro1">
		            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                <div class="card">
		                    <div class="header">
		                        <h2>
		                            Gestión de <?php echo $breadcrumbs[0]["nombre_lista_vista"]; ?>
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
		                                     	<th style="width: 17%;">Acciones</th>
		                                        <th style="width: 10%;">Id Reservaciones</th>
		                                        <th>Id membresia</th>
		                                        <th>Identificador cliente</th>
		                                        <th>Nombres y Apellidos</th>
		                                        <th>Sala</th>
		                                        <th>Fecha Resevación</th>
		                                        <th>Hora de ingreso</th>
		                                        <th>Hora de salida</th>
		                                        <th>Hora liberada</th>
		                                        <th>Condición</th>
		                                        <th>Fecha de Registro</th>
		                                        <th>Registrado Por</th>
		                                    </tr>
		                                </thead>
		                                <tbody></tbody>
		                            </table>
		                            <div class="col-md-2 eliminar ocultar">
		                            	<button class="btn btn-danger waves-effect disabled" onclick="eliminarMultiple('Membresia/eliminar_multiple')">Eliminar seleccionados</button>
		                            </div>
		                            <div class="col-md-2 actualizar ocultar">
		                            	<button class="btn btn-warning waves-effect" onclick="statusMultiple('Reservaciones/status_multiple', 1, 'activar')">Activar seleccionados</button>
		                            </div>
		                            <div class="col-md-2 actualizar ocultar">
		                            	<button class="btn btn-warning waves-effect" onclick="statusMultiple('Reservaciones/status_multiple', 2, 'desactivar')">Desactivar seleccionados</button>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        </div>
		        <!-- Cierre del cuadro de la tabla -->

				<!-- Comienzo del cuadro de registrar  -->
				        
				<div class="row clearfix ocultar" id="cuadro2">
			        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			            <div class="card">
			                <div class="header">
			                    <h2>Registro de <?php echo $breadcrumbs[0]["nombre_lista_vista"]; ?></h2>
			                </div>
			                <div class="body">
			                	<div class="table-responsive">
			                        <form name="form_reservaciones_registrar" id="form_reservaciones_registrar" method="post">
			                        	<div class="row">
											<div class="col-sm-3">
							        			<img id="imagen_registrar" src="<?php echo base_url();?>assets/cpanel/Usuarios/images/default.png" alt="Tu avatar"/ class="img-responsive ima_error " style="border-radius: 50%;width: 100px;height: 100px;margin: 0 auto; margin-top: 30px;">
							        		</div>
							        		<div class="col-sm-9">
							        			<div class="col-sm-12">
					                            	<table class="table table-bordered table-striped table-hover" id="tableInmobiliariaRegistrar">
					                            		<thead>
					                            			<tr>
					                            				<th>Reservaciones</th>
					                            				<th>Sala</th>
					                            				<th>Precio</th>
					                            				<th>Condición</th>
					                            				<th>Fecha</th>
					                            			</tr>
					                            		</thead>
					                            		<tbody>
					                            			<tr>
					                            				<th id="tbl_numero_reservaciones">N</th>
					                            				<th id="tbl_sala_registrar"></th>
					                            				<th id="tbl_precio"></th>
					                            				<th id="tbl_condicion"></th>
					                            				<th id="tbl_fecha">dd-mm-yyyy</th>
					                            			</tr>
					                            		</tbody>
					                            	</table>
					                            </div>
							        		</div>
			                        	</div>
			                             <ul class="nav nav-tabs">
								        	<li id="tab0" class="active"><a href="#datosgenerales" data-toggle="tab" >Datos Clientes</a></li>
								        	
								        </ul> 
								        <div class="tab-content">
								        	<div class="tab-pane fade in active tab_content0" id="datosgenerales">
								        	    <!--Registrar Jornada -->
								        	    <div id="">
								        	    	<div class="col-sm-4">
					                            		<label for="cliente_jornada_registrar">Id (Prospecto/CLiente)*</label>
						                                <div class="form-group valid-required">
						                                    <div class="form-line">
						                                        <select name="cliente_jornada_registrar" id="cliente_jornada_registrar" required class="form-control" onchange="consultarMembresia()" style="width:100%;">
					                            					<option value="" selected>Seleccione</option>
					                            						<?php foreach ($membresia as $afiliado): 
					                            							if($afiliado['cancelado']==false){ ?>
					                            						<option value="<?=$afiliado['id_membresia'];?>" <?php if ($id_membresia==$afiliado['id_membresia']){ ?> selected <?php }?>><?=$afiliado['nombre_datos_personales'];?></option>
					                            						<?php }
					                            						 endforeach ?>
					                            				</select>
					                            				
					                                    	</div>
					                             		</div>
					                           		</div>
					                           		<div class="col-sm-4">
				                        				<label for="sala_registrar">Sala*</label>
				                        				<div class="valid-required">
				                            				<select name="sala_registrar" id="sala_registrar" required class="fisicaf form-control" onchange="infoSalas()">
				                            					<option value="" selected>Seleccione</option>
				                            						<?php foreach ($salas as $sala): ?>
				                            					<option value="<?=$sala['id_salas'];?>"><?=$sala['descripcion'];?></option>
				                            						<?php endforeach ?>
				                            				</select>
				                            				<input type="hidden" name="precio_registrar" id="precio_registrar">
				                            			</div>
					                           		</div>
					                           		<div class="col-sm-4" >
					                            		<label for="fecha_reservacion_r">Fecha*</label>
				                                		<div class="form-group valid-required">
				                                   			 <div class="form-line input-group fecha">
				                                        		<input type="text" class="form-control" name="fecha_resevacion_registrar" id="fecha_resevacion_registrar" placeholder="dd-mm-yyyy" required>
				                                        		<span class="input-group-addon">
									                       			 <span class="glyphicon glyphicon-calendar"></span>
									                   		 	</span>
				                                   			 </div>
				                                		</div>
						                            </div>
					                           		<div class="col-sm-6" >
					                            		<label for="hora_inicio_reservacion_r">Hora inicio*</label>
				                                		<div class="form-group valid-required">
				                                   			 <div class="form-line input-group fecha">
				                                        		<input type="text" class="form-control" name="hora_inicio_reservacion_registrar" id="hora_inicio_reservacion_registrar" placeholder="hh:mm:ss" required>
				                                        		<span class="input-group-addon">
									                       			 <span class="glyphicon glyphicon-time"></span>
									                   		 	</span>
				                                   			 </div>
				                                		</div>
						                            </div>
						                            <div class="col-sm-6" >
					                            		<label for="hora_fin_reservacion_r">Hora Fin*</label>
				                                		<div class="form-group valid-required">
				                                   			 <div class="form-line input-group fecha">
				                                        		<input type="text" class="form-control" name="hora_fin_reservacion_registrar" id="hora_fin_reservacion_registrar" placeholder="hh:mm:ss" required>
				                                        		<span class="input-group-addon">
									                       			 <span class="glyphicon glyphicon-time"></span>
									                   		 	</span>
				                                   			 </div>
				                                		</div>
						                            </div>
					                           		
					                           		<div class="col-sm-3" style="display: none;">
					                            		<label for="hora_contratadas_reservacion_r">Horas contratadas*</label>
				                                		<div class="form-group valid-required">
				                                   			 <div class="form-line input-group fecha">
				                                        		<input type="text" class="form-control" name="hora_contratadas_reservacion_registrar" id="hora_contratadas_reservacion_registrar" placeholder="00:00:00" disabled>
				                                        		<span class="input-group-addon">
									                       			 <span class="glyphicon glyphicon-time"></span>
									                   		 	</span>
				                                   			 </div>
				                                		</div>
						                            </div>
						                            <div class="col-sm-3" style="display: none;">
					                            		<label for="hora_contratadas_reservacion_r">Horas consumidas*</label>
				                                		<div class="form-group valid-required">
				                                   			 <div class="form-line input-group fecha">
				                                        		<input type="text" class="form-control" name="hora_contratadas_reservacion_registrar" id="hora_contratadas_reservacion_registrar" placeholder="00:00:00" disabled>
				                                        		<span class="input-group-addon">
									                       			 <span class="glyphicon glyphicon-time"></span>
									                   		 	</span>
				                                   			 </div>
				                                		</div>
						                            </div>
						                            <div class="col-sm-3" style="display: none;">
					                            		<label for="hora_contratadas_reservacion_r">Horas por consumir*</label>
				                                		<div class="form-group valid-required">
				                                   			 <div class="form-line input-group fecha">
				                                        		<input type="text" class="form-control" name="hora_contratadas_reservacion_registrar" id="hora_contratadas_reservacion_registrar" placeholder="00:00:00"
				                                        		disabled>
				                                        		<span class="input-group-addon">
									                       			 <span class="glyphicon glyphicon-time"></span>
									                   		 	</span>
				                                   			 </div>
				                                		</div>
						                            </div>
					                           		<!--<div class="col-sm-3">
				                            			<label for="grupo_empresarial_jornada_registrar">Grupo empresarial*</label>
					                                	<div class="form-group">
					                                    	<div class="form-line">
					                                        	<select name="grupo_empresarial_jornada_registrar" id="grupo_empresarial_jornada_registrar"  class=" form-control">
					                            					<option value="" selected>Seleccione</option>
					                            						<?php foreach ($actividadesEconomicas as $actividadEconomica): ?>
					                            					<option value="<?=$actividadEconomica->id_lista_valor;?>"><?=$actividadEconomica->nombre_lista_valor;?></option>
					                            						<?php endforeach ?>
					                            				</select>
					                                    	</div>
					                                	</div>
					                             	</div>-->
				                           			<!-- ===============================-->

										        </div>
									        	<!--Fin de Registrar jornada -->
									        	
									        </div>
								        
								        	<?php if ($id_membresia!=""){ ?>
 											<!-- -->
					                    	<div class="col-sm-4 col-sm-offset-5">
					                            <a type="button"  class="btn btn-primary waves-effect" href="<?php echo base_url();?>membresia">Regresar</a>
					                            <input type="submit" value="Guardar" class="btn btn-success waves-effect">
					                            <!-- onclick="registrar_jornadas()" -->
					                    	</div>
					                    	<!-- -->
								        	<?php }else{ ?>
									        <!-- -->
					                    	<div class="col-sm-4 col-sm-offset-5">
					                            <button type="button" onclick="regresar('#cuadro2')" class="btn btn-primary waves-effect">Regresar</button>
					                            <input type="submit" value="Guardar" class="btn btn-success waves-effect">
					                            <button type="button" onclick="verModalReservaciones()" class="btn btn-warning waves-effect">Buscar</button>
					                            <!-- onclick="registrar_jornadas()" -->
					                    	</div>
					                    	<!-- -->
					                    	<?php } ?>
					                    </div>	
			                    	</form>
								</div>
			                </div>
			                
			            </div>
			        </div>
			    </div>
				<!-- Cierre del cuadro de registrar -->

		        <!-- Comienzo del cuadro de consultar -->
				<div class="row clearfix ocultar" id="cuadro3">
		            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                <div class="card">
		                    <div class="header">
		                        <h2>Consultar <?php echo $breadcrumbs[0]["nombre_lista_vista"]; ?></h2>
		                    </div>
		                    <!-- Form de jornadas -->
					        <div class="body">
			                	<div class="table-responsive">
			                        <form name="form_reservaciones_consultar" id="form_reservaciones_consultar" method="post">
			                        	<div class="row">
											<div class="col-sm-3">
							        			<img id="imagen_consultar" src="<?php echo base_url();?>assets/cpanel/Usuarios/images/default.png" alt="Tu avatar"/ class="img-responsive ima_error " style="border-radius: 50%;width: 100px;height: 100px;margin: 0 auto; margin-top: 30px;">
							        		</div>
							        		<div class="col-sm-9">
							        			<div class="col-sm-12">
					                            	<table class="table table-bordered table-striped table-hover" id="tableReservacionesConsultar">
					                            		<thead>
					                            			<tr>
					                            				<th>Reservaciones</th>
					                            				<th>Sala</th>
					                            				<th>Precio</th>
					                            				<th>Condición</th>
					                            				<th>Fecha</th>
					                            			</tr>
					                            		</thead>
					                            		<tbody>
					                            			<tr>
					                            				<th id="tbl_numero_reservaciones_consultar">N</th>
					                            				<th id="tbl_sala_consultar"></th>
					                            				<th id="tbl_precio_consultar"></th>
					                            				<th id="tbl_condicion_consultar"></th>
					                            				<th id="tbl_fecha_consultar">dd-mm-yyyy</th>
					                            			</tr>
					                            		</tbody>
					                            	</table>
					                            </div>
							        		</div>
			                        	</div>
			                             <ul class="nav nav-tabs">
								        	<li id="tab0" class="active"><a href="#datosgenerales" data-toggle="tab" >Datos Clientes</a></li>
								        	
								        </ul> 
								        <div class="tab-content">
								        	<div class="tab-pane fade in active tab_content0" id="datosgenerales">
								        	    <!--Registrar Jornada -->
								        	    <div id="">
								        	    	<div class="col-sm-4">
					                            		<label for="cliente_jornada_consultar">Id (Prospecto/CLiente)*</label>
						                                <div class="form-group valid-required">
						                                    <div class="form-line">
						                                        <select name="cliente_jornada_consultar" id="cliente_jornada_consultar" required class="form-control" style="width:100%;" disabled>
					                            					<option value="" selected>Seleccione</option>
					                            						<?php foreach ($membresia as $afiliado): ?>
					                            						<option value="<?=$afiliado['id_membresia'];?>" <?php if ($id_membresia==$afiliado['id_membresia']){ ?> selected <?php }?>><?=$afiliado['nombre_datos_personales'];?></option>
					                            						<?php endforeach ?>
					                            				</select>
					                            				
					                                    	</div>
					                             		</div>
					                           		</div>
					                           		<div class="col-sm-4">
				                        				<label for="sala_consultar">Sala*</label>
				                        				<div class="valid-required">
				                            				<!--<select name="sala_consultar" id="sala_consultar" required class="form-control" onchange="infoSalas()" disabled>
				                            					<option value="" selected>Seleccione</option>
				                            						<?php foreach ($salas as $sala): ?>
				                            					<option value="<?=$sala['id_salas'];?>"><?=$sala['descripcion'];?></option>
				                            						<?php endforeach ?>
				                            				</select>-->
				                            				<input type="text" class="form-control" name="sala_consultar" id="sala_consultar" readonly>
				                            			</div>
					                           		</div>
					                           		<div class="col-sm-4" >
					                            		<label for="fecha_reservacion_consultar">Fecha*</label>
				                                		<div class="form-group valid-required">
				                                   			 <div class="form-line input-group fecha">
				                                        		<input type="text" class="form-control" name="fecha_reservacion_consultar" id="fecha_reservacion_consultar" placeholder="dd-mm-yyyy" disabled>
				                                        		<span class="input-group-addon">
									                       			 <span class="glyphicon glyphicon-calendar"></span>
									                   		 	</span>
				                                   			 </div>
				                                		</div>
						                            </div>
					                           		<div class="col-sm-6" >
					                            		<label for="hora_inicio_reservacion_consultar">Hora inicio*</label>
				                                		<div class="form-group valid-required">
				                                   			 <div class="form-line input-group fecha">
				                                        		<input type="text" class="form-control" name="hora_inicio_reservacion_consultar" id="hora_inicio_reservacion_consultar" placeholder="hh:mm:ss" disabled>
				                                        		<span class="input-group-addon">
									                       			 <span class="glyphicon glyphicon-time"></span>
									                   		 	</span>
				                                   			 </div>
				                                		</div>
						                            </div>
						                            <div class="col-sm-6" >
					                            		<label for="hora_fin_reservacion_consultar">Hora Fin*</label>
				                                		<div class="form-group valid-required">
				                                   			 <div class="form-line input-group fecha">
				                                        		<input type="text" class="form-control" name="hora_fin_reservacion_consultar" id="hora_fin_reservacion_consultar" placeholder="hh:mm:ss" disabled>
				                                        		<span class="input-group-addon">
									                       			 <span class="glyphicon glyphicon-time"></span>
									                   		 	</span>
				                                   			 </div>
				                                		</div>
						                            </div>
					                           		
					                           		<div class="col-sm-4" >
					                            		<label for="hora_contratadas_reservacion_consultar">Horas contratadas*</label>
				                                		<div class="form-group valid-required">
				                                   			 <div class="form-line input-group fecha">
				                                        		<input type="text" class="form-control" name="hora_contratadas_reservacion_consultar" id="hora_contratadas_reservacion_consultar" placeholder="00:00:00" disabled>
				                                        		<span class="input-group-addon">
									                       			 <span class="glyphicon glyphicon-time"></span>
									                   		 	</span>
				                                   			 </div>
				                                		</div>
						                            </div>
						                            <div class="col-sm-4">
					                            		<label for="hora_consumidas_reservacion_consultar">Horas consumidas*</label>
				                                		<div class="form-group valid-required">
				                                   			 <div class="form-line input-group fecha">
				                                        		<input type="text" class="form-control" name="hora_consumidas_reservacion_consultar" id="hora_consumidas_reservacion_consultar" placeholder="00:00:00" disabled>
				                                        		<span class="input-group-addon">
									                       			 <span class="glyphicon glyphicon-time"></span>
									                   		 	</span>
				                                   			 </div>
				                                		</div>
						                            </div>
						                            <div class="col-sm-4">
					                            		<label for="hora_contratadas_reservacion_actualizar">Horas por consumir*</label>
				                                		<div class="form-group valid-required">
				                                   			 <div class="form-line input-group fecha">
				                                        		<input type="text" class="form-control" name="hora_por_consumir_reservacion_consultar" id="hora_por_consumir_reservacion_consultar" placeholder="00:00:00"
				                                        		disabled>
				                                        		<span class="input-group-addon">
									                       			 <span class="glyphicon glyphicon-time"></span>
									                   		 	</span>
				                                   			 </div>
				                                		</div>
						                            </div>
					                           		

										        </div>
									        	<!--Fin de Actualizar jornada -->
									        	
									        </div>
									        <!-- -->
					                    	<div class="col-sm-4 col-sm-offset-5">
					                            <button type="button" onclick="regresar('#cuadro3')" class="btn btn-primary waves-effect">Regresar</button>
					                            <!-- onclick="registrar_jornadas()" -->
					                    	</div>
					                    	<!-- -->
					                    </div>	
			                    	</form>
								</div>
			                </div>
			                <!-- Fin de form editar jornada -->
		                </div>
		            </div>
		        </div>
				<!-- Cierre del cuadro de consultar -->

		    	<!-- Comienzo del cuadro de editar -->
				<div class="row clearfix ocultar" id="cuadro4">
		            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                <div class="card">
					        <div class="header">
					            <h2>Editar de <?php echo $breadcrumbs[0]["nombre_lista_vista"]; ?></h2>
					        </div>
					        <!-- Form de jornadas -->
					        <div class="body">
			                	<div class="table-responsive">
			                        <form name="form_reservaciones_actualizar" id="form_reservaciones_actualizar" method="post">
			                        	<div class="row">
											<div class="col-sm-3">
							        			<img id="imagen_actualizar" src="<?php echo base_url();?>assets/cpanel/Usuarios/images/default.png" alt="Tu avatar"/ class="img-responsive ima_error " style="border-radius: 50%;width: 100px;height: 100px;margin: 0 auto; margin-top: 30px;">
							        		</div>
							        		<div class="col-sm-9">
							        			<div class="col-sm-12">
					                            	<table class="table table-bordered table-striped table-hover" id="tableReservacionesActualizar">
					                            		<thead>
					                            			<tr>
					                            				<th>Reservaciones</th>
					                            				<th>Sala</th>
					                            				<th>Precio</th>
					                            				<th>Condición</th>
					                            				<th>Fecha</th>
					                            			</tr>
					                            		</thead>
					                            		<tbody>
					                            			<tr>
					                            				<th id="tbl_numero_reservaciones_actualizar">N</th>
					                            				<th id="tbl_sala_actualizar"></th>
					                            				<th id="tbl_precio_actualizar"></th>
					                            				<th id="tbl_condicion_actualizar"></th>
					                            				<th id="tbl_fecha_actualizar">dd-mm-yyyy</th>
					                            			</tr>
					                            		</tbody>
					                            	</table>
					                            </div>
							        		</div>
			                        	</div>
			                             <ul class="nav nav-tabs">
								        	<li id="tab0" class="active"><a href="#datosgenerales" data-toggle="tab" >Datos Clientes</a></li>
								        	
								        </ul> 
								        <div class="tab-content">
								        	<div class="tab-pane fade in active tab_content0" id="datosgenerales">
								        	    <!--Actualizar Reservaciones -->
								        	    <div id="">
								        	    	<div class="col-sm-4">
					                            		<label for="cliente_jornada_actualizar">Id (Prospecto/CLiente)*</label>
						                                <div class="form-group valid-required">
						                                    <div class="form-line">
						                                        <select name="cliente_jornada_actualizar" id="cliente_jornada_actualizar" required class="form-control"  style="width:100%;" disabled>
					                            					<option value="" selected>Seleccione</option>
					                            						<?php foreach ($membresia as $afiliado): ?>
					                            						<option value="<?=$afiliado['id_membresia'];?>" <?php if ($id_membresia==$afiliado['id_membresia']){ ?> selected <?php }?>><?=$afiliado['nombre_datos_personales'];?></option>
					                            						<?php endforeach ?>
					                            				</select>
					                            				
					                                    	</div>
					                             		</div>
					                           		</div>
					                           		<div class="col-sm-4">
				                        				<label for="sala_actualizar">Sala*</label>
				                        				<div class="valid-required">
				                            				<!--<select name="sala_actualizar" id="sala_actualizar" required class="form-control" disabled>
				                            					<option value="" selected>Seleccione</option>
				                            						<?php foreach ($salas as $sala): ?>
				                            					<option value="<?=$sala['id_salas'];?>"><?=$sala['descripcion'];?></option>
				                            						<?php endforeach ?>
				                            				</select>-->
				                            				<input type="text" class="form-control" name="sala_actualizar" id="sala_actualizar" readonly>
				                            			</div>
					                           		</div>
					                           		<div class="col-sm-4" >
					                            		<label for="fecha_reservacion_actualizar">Fecha*</label>
				                                		<div class="form-group valid-required">
				                                   			 <div class="form-line input-group fecha">
				                                        		<input type="text" class="form-control" name="fecha_resevacion_actualizar" id="fecha_resevacion_actualizar" placeholder="dd-mm-yyyy" disabled>
				                                        		<span class="input-group-addon">
									                       			 <span class="glyphicon glyphicon-calendar"></span>
									                   		 	</span>
				                                   			 </div>
				                                		</div>
						                            </div>
					                           		<div class="col-sm-6" >
					                            		<label for="hora_inicio_reservacion_actualizar">Hora inicio*</label>
				                                		<div class="form-group valid-required">
				                                   			 <div class="form-line input-group fecha">
				                                        		<input type="text" class="form-control" name="hora_inicio_reservacion_actualizar" id="hora_inicio_reservacion_actualizar" placeholder="hh:mm:ss" disabled>
				                                        		<span class="input-group-addon">
									                       			 <span class="glyphicon glyphicon-time"></span>
									                   		 	</span>
				                                   			 </div>
				                                		</div>
						                            </div>
						                            <div class="col-sm-6" >
					                            		<label for="hora_fin_reservacion_actualizar">Hora Fin*</label>
				                                		<div class="form-group valid-required">
				                                   			 <div class="form-line input-group fecha">
				                                        		<input type="text" class="form-control" name="hora_fin_reservacion_actualizar" id="hora_fin_reservacion_actualizar" placeholder="hh:mm:ss" disabled>
				                                        		<span class="input-group-addon">
									                       			 <span class="glyphicon glyphicon-time"></span>
									                   		 	</span>
				                                   			 </div>
				                                		</div>
						                            </div>
					                           		
					                           		<div class="col-sm-4" style="">
					                            		<label for="hora_contratadas_reservacion_actualizar">Horas contratadas*</label>
				                                		<div class="form-group valid-required">
				                                   			 <div class="form-line input-group fecha">
				                                        		<input type="text" class="form-control" name="hora_contratadas_reservacion_actualizar" id="hora_contratadas_reservacion_actualizar" placeholder="00:00:00" disabled>
				                                        		<span class="input-group-addon">
									                       			 <span class="glyphicon glyphicon-time"></span>
									                   		 	</span>
				                                   			 </div>
				                                		</div>
						                            </div>
						                            <div class="col-sm-4" style="">
					                            		<label for="hora_consumidas_reservacion_actualizar">Horas consumidas*</label>
				                                		<div class="form-group valid-required">
				                                   			 <div class="form-line input-group fecha">
				                                        		<input type="text" class="form-control" name="hora_consumidas_reservacion_actualizar" id="hora_consumidas_reservacion_actualizar" placeholder="00:00:00" disabled>
				                                        		<span class="input-group-addon">
									                       			 <span class="glyphicon glyphicon-time"></span>
									                   		 	</span>
				                                   			 </div>
				                                		</div>
						                            </div>
						                            <div class="col-sm-4" style="">
					                            		<label for="hora_contratadas_reservacion_actualizar">Horas por consumir*</label>
				                                		<div class="form-group valid-required">
				                                   			 <div class="form-line input-group fecha">
				                                        		<input type="text" class="form-control" name="hora_por_consumir_reservacion_actualizar" id="hora_por_consumir_reservacion_actualizar" placeholder="00:00:00"
				                                        		disabled>
				                                        		<span class="input-group-addon">
									                       			 <span class="glyphicon glyphicon-time"></span>
									                   		 	</span>
				                                   			 </div>
				                                		</div>
						                            </div>
					                           		<input type="hidden" id="id_reservaciones_actualizar" name="id_reservaciones_actualizar">
					                           		<input type="hidden" id="minutos_tolerancia" name="minutos_tolerancia" value="<?php echo $configuracion["min_tolerancia"];?>">
										        </div>
									        	<!--Fin de Actualizar Reservaciones -->
									        	
									        </div>
									        <!-- -->
					                    	<div class="col-sm-12 col-sm-offset-5">
					                            <button type="button" onclick="regresar('#cuadro4')" class="btn btn-primary waves-effect">Regresar</button>
					                           <!-- onclick="verModalReservaciones()"-->
					                            <button type="button" onclick="verModalReservaciones()" class="btn btn-warning waves-effect">Buscar</button>
					                             <button id="btn-ingresar" name="btn-ingresar" type="button" onclick="ingresarReservacion()" class="btn btn-success waves-effect" style="display: none">Ingresar</button>
					                            <button id="btn-salir" name="btn-salir" type="button" onclick="salirReservacion()" class="btn btn-danger waves-effect" style="display: none">Salir</button>
					                            <div style="clear: both"></div>
					                            <!-- onclick="registrar_jornadas()" -->
					                    	</div>
					                    	<!-- -->
					                    </div>	
			                    	</form>
								</div>
			                </div>
			                <!-- Fin de form editar reservaciones -->
						</div>
					</div>
				</div>		
		        <!-- Cierre del cuadro de editar  -->
			</div>
			<!-- Modal -->
			<div class="modal fade" id="modal_tabla" name="modal_tabla" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
			    <div class="modal-dialog modal-lg .modal-sm">
					<div class="modal-content">
						<div class="modal-header header_conf">
						    <button type="button" id="cerrar_mensaje2" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						    <p id="cabecera_mensaje" name="cabecera_mensaje"><h2>Reservaciones</h2></p>
						</div>
						<div class="modal-body" id="cuerpo_mensaje" name="cuerpo_mensaje">
						<!--Modal body -->
							<div class=row>
								<div class="col-lg-6">
									<label>Hora de inicio operaciones:</label>
									<span><?php echo $configuracion["hora_inicio_operaciones"] ?></span>
								</div>
								<div class="col-lg-6">
									<label>Hora de fin operaciones:</label>
									<span><?php echo $configuracion["hora_fin_operaciones"] ?></span>
								</div>
								<div class="col-lg-12" style="display:none">
									<div class="row">
										<div class="col-sm-3" >
		                            		<label for="fecha_d-reservacion_actualizar">Fecha Desde*</label>
	                                		<div class="form-group valid-required">
	                                   			 <div class="form-line input-group fecha">
	                                        		<input type="text" class="form-control" name="fecha_desde" id="fecha_desde" placeholder="dd-mm-yyyy" disabled>
	                                        		<span class="input-group-addon">
						                       			 <span class="glyphicon glyphicon-calendar"></span>
						                   		 	</span>
	                                   			 </div>
	                                		</div>
			                            </div>
										<div class="col-sm-3" >
		                            		<label for="fecha_d-reservacion_actualizar">Fecha Hasta*</label>
	                                		<div class="form-group valid-required">
	                                   			 <div class="form-line input-group fecha">
	                                        		<input type="text" class="form-control" name="fecha_hasta" id="fecha_hasta" placeholder="dd-mm-yyyy" >
	                                        		<span class="input-group-addon">
						                       			 <span class="glyphicon glyphicon-calendar"></span>
						                   		 	</span>
	                                   			 </div>
	                                		</div>
			                            </div>
										<div class="col-lg-3">
											<button type="button" class="btn btn-primary" onclick="filtrarReservaciones()">Filtrar</button>
										</div>
									</div>
								</div>
								<div id="DivtablaModal" name="DivtablaModal" class="col-lg-12 col-md-12 col-xs-12 col-sm-12" style="    overflow-y: hidden;max-width: 100%;">
									
									<!-- -->
									 <table class="table table-bordered table-striped table-hover" id="tablaModal">
		                                <thead>
		                                    <tr>
		                                        <th style="width: 10%;">Id Reservaciones</th>
		                                        <th>Id membresia</th>
		                                        <th>Identificador cliente</th>
		                                        <th>Nombres y Apellidos</th>
		                                        <th>Sala</th>
		                                        <th>Fecha Resevación</th>
		                                        <th>Hora de ingreso</th>
		                                        <th>Hora de salida</th>
		                                        <th>Hora liberada</th>
		                                        <th>Condición</th>
		                                        <th>Fecha de Registro</th>
		                                        <th>Registrado Por</th>
		                                    </tr>
		                                </thead>
		                                <tbody></tbody>
		                            </table>
									<!-- -->
								</div>

							</div>
						<!-- modal body-->
						</div>  
						<div class="modal-footer footer_conf">
						    <!-- Footter del modal -->
						      <button type="button" name="modal_reporte_salir" id="modal_reporte_salir" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
						    <!-- Fin footter del modal -->
						</div>
					</div>
				</div>
			</div>
			<!-- -->
			<div class="modal fade" id="modal_mensaje_eliminar" name="modal_mensaje_eliminar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
			    <div class="modal-dialog modal-lg .modal-sm">
					<div class="modal-content">
						<div class="modal-header header_conf">
						    <button type="button" id="cerrar_mensaje2" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						    <p id="cabecera_mensaje" name="cabecera_mensaje"><h2>Cancelar reservaciones</h2></p>
						</div>
						<div class="modal-body" id="cuerpo_mensaje" name="cuerpo_mensaje">
						<!--Modal body -->
							<div class=row>
								<div class="col-lg-12">
									<textarea class="form-control" placeholder="Ingrese motivo" id="motivo_cancelacion_reservacion" name="motivo_cancelacion_reservacion"></textarea>
									<div id="id_reservaciones_modal" name="id_reservaciones_modal"></div>
								</div>
							</div>
						<!-- modal body-->
						</div>  
						<div class="modal-footer footer_conf">
						    <!-- Footter del modal -->
						      <button type="button" name="modal_reporte_salir" id="modal_reporte_salir" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
						      <button type="button" name="modal_reporte_salir" id="modal_reporte_salir" class="btn btn-primary" data-dismiss="modal" onclick="cancelar_reservacion_motivo()">Aceptar</button>
						    <!-- Fin footter del modal -->
						</div>
					</div>
				</div>
			</div>
			<!-- -->
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
    <script src="<?=base_url();?>assets/template/plugins/momentjs/moment.js"></script>
    <script src="<?=base_url();?>assets/template/plugins/jquery-validation/jquery.validate.js"></script>
    <script src="<?=base_url();?>assets/template/plugins/jquery-validation/additional-methods.js"></script>
    <script src="<?=base_url();?>assets/template/plugins/bootstrap-datetimepicker-master/js/bootstrap-datetimepicker.min.js"></script>
    <script src="<?=base_url();?>assets/cpanel/Productos/js/numeral/min/numeral.min.js"></script>
    <script src="<?=base_url();?>assets/template/plugins/bootstrap-fileinput/js/plugins/piexif.min.js" type="text/javascript"></script>
    <script src="<?=base_url();?>assets/template/plugins/bootstrap-fileinput/js/plugins/purify.min.js" type="text/javascript"></script>
    <script src="<?=base_url();?>assets/template/plugins/bootstrap-fileinput/js/plugins/sortable.min.js" type="text/javascript"></script>
    
    
    <script src="<?=base_url();?>assets/template/plugins/bootstrap-fileinput/js/fileinput.js" type="text/javascript"></script>
    <script type="text/javascript" src="<?=base_url();?>assets/template/plugins/bootstrap-fileinput/themes/fa/theme.min.js"></script>
    <script src="<?=base_url();?>assets/template/plugins/bootstrap-fileinput/js/locales/es.js" type="text/javascript"></script>
    <!-- Select 2 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <!-- -->
    <script src="<?=base_url();?>assets/cpanel/Reservaciones/js/reservaciones.js"></script>
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
