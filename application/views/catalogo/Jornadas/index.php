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
		                                        <th style="width: 10%;">Id Jornada</th>
		                                        <th>Identificacion</th>
		                                        <th>Nombres y Apellidos</th>
		                                        <th>Plan</th>
		                                        <th>Hora de ingreso</th>
		                                        <th>Hora de salida</th>
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
		                            	<button class="btn btn-warning waves-effect" onclick="statusMultiple('Jornadas/status_multiple', 1, 'activar')">Activar seleccionados</button>
		                            </div>
		                            <div class="col-md-2 actualizar ocultar">
		                            	<button class="btn btn-warning waves-effect" onclick="statusMultiple('Jornadas/status_multiple', 2, 'desactivar')">Desactivar seleccionados</button>
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
			                        <form name="form_jornadas_registrar" id="form_jornadas_registrar" method="post">
			                        	<div class="row">
											<div class="col-sm-3">
							        			<img id="imagen_registrar" src="<?php echo base_url();?>assets/cpanel/Usuarios/images/default.png" alt="Tu avatar"/ class="img-responsive ima_error " style="border-radius: 50%;width: 100px;height: 100px;margin: 0 auto; margin-top: 30px;">
							        		</div>
							        		<div class="col-sm-9">
							        			<div class="col-sm-12">
					                            	<table class="table table-bordered table-striped table-hover" id="tableInmobiliariaRegistrar">
					                            		<thead>
					                            			<tr>
					                            				<th>Membresía</th>
					                            				<th>Plan</th>
					                            				<th>Horas contratadas</th>
					                            				<th>Horas consumidas</th>
					                            				<th>Horas disponibles</th>
					                            				<th>Valor</th>
					                            				<th>Inicio</th>
					                            				<th>Vigencia</th>
					                            			</tr>
					                            		</thead>
					                            		<tbody>
					                            			<tr>
					                            				<th id="numero_membresia"></th>
					                            				<th id="plan"></th>
					                            				<th id="horas_jornadas"></th>
					                            				<th id="horas_consumidas"></th>
					                            				<th id="horas_disponibles"></th>
					                            				<th id="plan_valor"></th>
					                            				<th id="fecha_inicio"></th>
					                            				<th id="fecha_fin"></th>
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
								        	    	<div class="col-sm-3">
					                            		<label for="cliente_jornada_registrar">Id (Prospecto/CLiente)*</label>
						                                <div class="form-group valid-required">
						                                    <div class="form-line">
						                                        <select name="cliente_jornada_registrar" id="cliente_jornada_registrar" required class="form-control" onchange="consultarMembresia()" style="width:100%;">
					                            					<option value="" selected>Seleccione</option>
					                            						<?php foreach ($membresia as $afiliado): 
					                            							if($afiliado['cancelado']==false){?>
					                            						<option value="<?=$afiliado['id_membresia'];?>" <?php if ($id_membresia==$afiliado['id_membresia']){ ?> selected <?php }?>><?=$afiliado['nombre_datos_personales'];?></option>
					                            						<?php } 
					                            								endforeach ?>
					                            				</select>
					                            				
					                                    	</div>
					                             		</div>
					                           		</div>
					                           		<div class="col-sm-3">
					                           			<label for="cliente_nombre_jornada_registrar">Nombre:</label>
						                                <div class="form-group valid-required">
						                                    <div class="form-line">
						                                        <input type="text" name="nombre_datos_personales_registrar" id="nombre_datos_personales_registrar" class="form-control" disabled="">
					                                    	</div>
					                             		</div>
				                            			
					                             	</div>
					                             	<div class="col-sm-3">
					                           			<label for="cliente_nombre_jornada_registrar">Apellido paterno:</label>
						                                <div class="form-group valid-required">
						                                    <div class="form-line">
						                                        <input type="text" name="apellido_p_datos_personales_registrar" id="apellido_p_datos_personales_registrar" class="form-control" disabled="">
					                                    	</div>
					                             		</div>
				                            			
					                             	</div>
					                             	<div class="col-sm-3">
					                           			<label for="cliente_nombre_jornada_registrar">Apellido Materno:</label>
						                                <div class="form-group valid-required">
						                                    <div class="form-line">
						                                        <input type="text" name="apellido_m_datos_personales_registrar" id="apellido_m_datos_personales_registrar" class="form-control" disabled="">
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
			                        <form name="form_jornadas_mostrar" id="form_jornadas_mostrar" method="post">
			                        	<div class="row">
											<div class="col-sm-3">
							        			<img id="imagen_mostrar" src="<?php echo base_url();?>assets/cpanel/Usuarios/images/default.png" alt="Tu avatar"/ class="img-responsive ima_error " style="border-radius: 50%;width: 100px;height: 100px;margin: 0 auto; margin-top: 30px;">
							        		</div>
							        		<div class="col-sm-9">
							        			<div class="col-sm-12">
					                            	<table class="table table-bordered table-striped table-hover" id="tableInmobiliariaActualizar">
					                            		<thead>
					                            			<tr>
					                            				<th>Membresía</th>
					                            				<th>Plan</th>
					                            				<th>Horas contratadas</th>
					                            				<th>Horas consumidas</th>
					                            				<th>Horas disponibles</th>
					                            				<th>Valor</th>
					                            				<th>Inicio</th>
					                            				<th>Vigencia</th>
					                            			</tr>
					                            		</thead>
					                            		<tbody>
					                            			<tr>
					                            				<th id="numero_membresia_mostrar"></th>
					                            				<th id="plan_mostrar"></th>
					                            				<th id="horas_jornadas_mostrar"></th>
					                            				<th id="horas_consumidas_mostrar"></th>
					                            				<th id="horas_disponibles_mostrar"></th>
					                            				<th id="plan_valor_mostrar"></th>
					                            				<th id="fecha_inicio_mostrar"></th>
					                            				<th id="fecha_fin_mostrar"></th>
					                            			</tr>
					                            		</tbody>
					                            	</table>
					                            </div>
							        		</div>
			                        	</div>
			                             <ul class="nav nav-tabs">
								        	<li id="tab0" class="active"><a href="#datosgenerales_mostrar" data-toggle="tab" >Datos Clientes</a></li>
								        	<li id="tab1"><a href="#datosRecargasMostrar" data-toggle="tab" class="">Recargas</a></li>
								        </ul> 
								        <div class="tab-content">
								        	<div class="tab-pane fade in active tab_content0" id="datosgenerales_mostrar">
								        	    <!--Registrar Jornada -->
								        	    <div id="">
								        	    	<div class="col-sm-3">
					                            		<label for="cliente_jornada_mostrar">Id(Prospecto/CLiente)*</label>
						                                <div class="form-group valid-required">
						                                    <div class="form-line">
						                                        <select name="cliente_jornada_mostrar" id="cliente_jornada_mostrar" required class="form-control" onchange="consultarMembresia()" disabled>
					                            					<option value="" selected>Seleccione</option>
					                            						<?php foreach ($membresia as $afiliado): ?>
					                            					<option value="<?=$afiliado['id_membresia'];?>"><?=$afiliado['nombre_datos_personales'];?></option>
					                            						<?php endforeach ?>
					                            				</select>
					                                    	</div>
					                             		</div>
					                           		</div>
					                           		<div class="col-sm-3">
					                           			<label for="cliente_nombre_jornada_mostrar">Nombre:</label>
						                                <div class="form-group valid-required">
						                                    <div class="form-line">
						                                        <input type="text" name="nombre_datos_personales_mostrar" id="nombre_datos_personales_mostrar" class="form-control" disabled="">
					                                    	</div>
					                             		</div>
				                            			
					                             	</div>
					                             	<div class="col-sm-3">
					                           			<label for="cliente_nombre_jornada_mostrar">Apellido paterno:</label>
						                                <div class="form-group valid-required">
						                                    <div class="form-line">
						                                        <input type="text" name="apellido_p_datos_personales_mostrar" id="apellido_p_datos_personales_mostrar" class="form-control" disabled="">
					                                    	</div>
					                             		</div>
				                            			
					                             	</div>
					                             	<div class="col-sm-3">
					                           			<label for="cliente_nombre_jornada_mostrar">Apellido Materno:</label>
						                                <div class="form-group valid-required">
						                                    <div class="form-line">
						                                        <input type="text" name="apellido_m_datos_personales_mostrar" id="apellido_m_datos_personales_mostrar" class="form-control" disabled="">
					                                    	</div>
					                             		</div>
				                            			
					                             	</div>
					                             	<input type="hidden" name="id_jornada_mostrar" id="id_jornada_mostrar" class="form-control">
				                           			<!-- ===============================-->

										        </div>
									        	<!--Fin de Registrar jornada -->
									        	<div class="col-sm-4 col-sm-offset-5">
						                            <button type="button" onclick="regresar('#cuadro3')" class="btn btn-primary waves-effect">Regresar</button>
						                            
						                            <!-- onclick="registrar_jornadas()" -->
						                    	</div>
									        </div>
								        	<div class="tab-pane fade tab_content1" id="datosRecargasMostrar">
									        	<div class="embed-responsive embed-responsive-16by9">
										        	<!--<iframe class="embed-responsive-item " id="iframeRecargos" allowfullscreen>
													</iframe>-->
													<!--                              -->
													<div class="row clearfix">
													    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="max-height: 600px;overflow-x: hidden;">
													        <div class="card">
													             <div class="body">
													            	<div class="table-responsive">
													            		<form name="form_recargos_mostrar" id="form_recargos_mostrar" method="post">
													            			<div class="col-sm-12">
													            				<h4>Servicios Contratados</h4>
													            				<hr>
													            			</div>
													                		<div class="col-sm-12" style="max-height: 200px;overflow-x: hidden;" >
													                        	<table class="table table-bordered table-striped table-hover" id="tableRegistrarServiciosPlanMostrar">
													                        		<thead>
													                        			<tr>
													                        				<th>Código</th>
													                        				<th>Título</th>
													                        				<th>Cantidad</th>
													                        				<th>Costo individual</th>
													                        				<th>Consumido</th>
													                        				<th>Disponible</th>
													                        			</tr>
													                        		</thead>
													                        		<tbody id="tbody_servicios_mostrar"></tbody>
													                        	</table>
													                        	
													                        </div>
													                        <div class="col-sm-12">
													            				<h4>Servicios Opcionales</h4>
													            				<hr>
													            			</div>
													                        <div class="col-sm-12" style="padding: 0px;">
													                            <div class="col-sm-12" id="contenedorTablaMostrar" name="contenedorTablaMostrar" >
													                            	<table class="table table-bordered table-striped table-hover" id="tableMostrar">
													                            		<thead>
													                            			<tr>
													                            				<th>Código</th>
													                            				<th>Título</th>
													                            				<th>Cantidad</th>
													                            				<th>Costo</th>
													                            				<th>Total por servicio</th>
													                            			</tr>
													                            		</thead>
													                            		<tbody></tbody>
													                            	</table>
													                            </div>
													                            
													                            <div class="col-sm-12" >
													                            	<div class="col-lg-6"></div>
													                            	<div class="col-lg-6">
													                            		<label for="valor_mostrar">Monto total recargo*</label>
														                                <div class="form-group">
														                                    <div class="form-line">
														                            			<input type="text" name="monto_total_recargo_mostrar" id="monto_total_recargo_mostrar" class="form-control" style="float: right;text-align: right;" readonly value="0.00">
														                            			
														                            		</div>
														                            	</div>
													                            	</div>
													                            </div>
													                            <div class="col-sm-12" >
													                            	<div class="col-lg-6"></div>
													                            	<div class="col-lg-6">
														                            	<label for="valor_actualizar">Monto a pagar*</label>
														                                <div class="form-group">
														                                    <div class="form-line">
														                            			<input type="text" name="monto_pagar_mostrar" id="monto_pagar_mostrar" class="form-control" style="float: right;text-align: right;" readonly value="0.00">
														                            		</div>
														                            	</div>
														                            </div>		
													                            </div>	
													                        </div>
													                       
													            			<br>
													            			<div class="col-sm-4 col-sm-offset-5">
													                            <button type="button" onclick="regresar('#cuadro3')" class="btn btn-primary waves-effect">Regresar</button>
													                        </div>
													                    </form>    
													                </div>
													            </div>
													        </div>
													    </div>
													</div>
													<!--                              -->
												</div>
								        	</div>
								        	
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
			                        <form name="form_jornadas_actualizar" id="form_jornadas_actualizar" method="post">
			                        	<div class="row">
											<div class="col-sm-3">
							        			<img id="imagen_actualizar" src="<?php echo base_url();?>assets/cpanel/Usuarios/images/default.png" alt="Tu avatar"/ class="img-responsive ima_error " style="border-radius: 50%;width: 100px;height: 100px;margin: 0 auto; margin-top: 30px;">
							        		</div>
							        		<div class="col-sm-9">
							        			<div class="col-sm-12">
					                            	<table class="table table-bordered table-striped table-hover" id="tableInmobiliariaActualizar">
					                            		<thead>
					                            			<tr>
					                            				<th>Membresía</th>
					                            				<th>Plan</th>
					                            				<th>Horas contratadas</th>
					                            				<th>Horas consumidas</th>
					                            				<th>Horas disponibles</th>
					                            				<th>Valor</th>
					                            				<th>Inicio</th>
					                            				<th>Vigencia</th>
					                            			</tr>
					                            		</thead>
					                            		<tbody>
					                            			<tr>
					                            				<th id="numero_membresia_actualizar"></th>
					                            				<th id="plan_actualizar"></th>
					                            				<th id="horas_jornadas_actualizar"></th>
					                            				<th id="horas_consumidas_actualizar"></th>
					                            				<th id="horas_disponibles_actualizar"></th>
					                            				<th id="plan_valor_actualizar"></th>
					                            				<th id="fecha_inicio_actualizar"></th>
					                            				<th id="fecha_fin_actualizar"></th>
					                            			</tr>
					                            		</tbody>
					                            	</table>
					                            </div>
							        		</div>
			                        	</div>
			                             <ul class="nav nav-tabs">
								        	<li id="tab0" class="active"><a href="#datosgenerales_actualizar" data-toggle="tab" >Datos Clientes</a></li>
								        	<li id="tab1"><a href="#datosRecargas" data-toggle="tab" class="">Recargas</a></li>
								        </ul> 
								        <div class="tab-content">
								        	<div class="tab-pane fade in active tab_content0" id="datosgenerales_actualizar">
								        	    <!--Registrar Jornada -->
								        	    <div id="">
								        	    	<div class="col-sm-3">
					                            		<label for="cliente_jornada_actualizar">Id (Prospecto/CLiente)*</label>
						                                <div class="form-group valid-required">
						                                    <div class="form-line">
						                                        <select name="cliente_jornada_actualizar" id="cliente_jornada_actualizar" required class="form-control" onchange="consultarMembresia()" disabled>
					                            					<option value="" selected>Seleccione</option>
					                            						<?php foreach ($membresia as $afiliado): ?>
					                            					<option value="<?=$afiliado['id_membresia'];?>"><?=$afiliado['nombre_datos_personales'];?></option>
					                            						<?php endforeach ?>
					                            				</select>
					                                    	</div>
					                             		</div>
					                           		</div>
					                           		<div class="col-sm-3">
					                           			<label for="cliente_nombre_jornada_actualizar">Nombre:</label>
						                                <div class="form-group valid-required">
						                                    <div class="form-line">
						                                        <input type="text" name="nombre_datos_personales_actualizar" id="nombre_datos_personales_actualizar" class="form-control" disabled="">
					                                    	</div>
					                             		</div>
				                            			
					                             	</div>
					                             	<div class="col-sm-3">
					                           			<label for="cliente_nombre_jornada_actualizar">Apellido paterno:</label>
						                                <div class="form-group valid-required">
						                                    <div class="form-line">
						                                        <input type="text" name="apellido_p_datos_personales_actualizar" id="apellido_p_datos_personales_actualizar" class="form-control" disabled="">
					                                    	</div>
					                             		</div>
				                            			
					                             	</div>
					                             	<div class="col-sm-3">
					                           			<label for="cliente_nombre_jornada_actualizar">Apellido Materno:</label>
						                                <div class="form-group valid-required">
						                                    <div class="form-line">
						                                        <input type="text" name="apellido_m_datos_personales_actualizar" id="apellido_m_datos_personales_actualizar" class="form-control" disabled="">
					                                    	</div>
					                             		</div>
				                            			
					                             	</div>
					                             	<!--<label for="grupo_empresarial_jornada_actualizar">Grupo empresarial*</label>
					                                	<div class="form-group">
					                                    	<div class="form-line">
					                                        	<select name="grupo_empresarial_jornada_actualizar" id="grupo_empresarial_jornada_actualizar"  class=" form-control" disabled>
					                            					<option value="" selected>Seleccione</option>
					                            						<?php foreach ($actividadesEconomicas as $actividadEconomica): ?>
					                            					<option value="<?=$actividadEconomica->id_lista_valor;?>"><?=$actividadEconomica->nombre_lista_valor;?></option>
					                            						<?php endforeach ?>
					                            				</select>
					                                    	</div>
					                                	</div>-->
					                             	<input type="hidden" name="id_jornada" id="id_jornada" class="form-control">
				                           			<!-- ===============================-->

										        </div>
									        	<!--Fin de Registrar jornada -->
									        	<div class="col-sm-4 col-sm-offset-5">
						                            <button type="button" onclick="regresar('#cuadro4')" class="btn btn-primary waves-effect">Regresar</button>
						                            <input type="button" value="Marcar salida" class="btn btn-success waves-effect" onclick="marcarSalida()">
						                            <!-- onclick="registrar_jornadas()" -->
						                    	</div>
									        </div>
								        	<div class="tab-pane fade tab_content1" id="datosRecargas">
									        	<div class="embed-responsive embed-responsive-16by9">
										        	<!--<iframe class="embed-responsive-item " id="iframeRecargos" allowfullscreen>
													</iframe>-->
													<!--                              -->
													<div class="row clearfix">
													    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="max-height: 600px;overflow-x: hidden;">
													        <div class="card" >
													             <div class="body" style="max-height: 500px;overflow-x: hidden;">
													            	<div class="table-responsive">
													            		<form name="form_recargos_actualizar" id="form_recargos_actualizar" method="post">
													            			<div class="col-sm-12">
													            				<h4>Servicios Contratados</h4>
													            				<hr>
													            			</div>
													                		<div class="col-sm-12" style="max-height: 200px;overflow-x: hidden;" >
													                        	<table class="table table-bordered table-striped table-hover" id="tableRegistrarServiciosPlan">
													                        		<thead>
													                        			<tr>
													                        				<th>Código</th>
													                        				<th>Título</th>
													                        				<th>Cantidad</th>
													                        				<th>Costo individual</th>
													                        				<th>Consumido</th>
													                        				<th>Disponible</th>
													                        			</tr>
													                        		</thead>
													                        		<tbody id="tbody_servicios"></tbody>
													                        	</table>
													                        	<div id="arreglo_servicios_contratados" name="arreglo_servicios_contratados" data="0" style="display: block"></div>
													                        </div>
													                        <div class="col-sm-12">
													            				<h4>Servicios Opcionales</h4>
													            				<hr>
													            			</div>
													                        <div class="col-sm-12" style="padding: 0px;">
													                        	<div class="col-sm-4">
													                        		<label for="servicio_actualizar">Servicios*</label>
													                            	<select name="servicio" id="servicio_actualizar" class="form-control" required>
													                            		<option value="" selected>Seleccione</option>
													                            		<?php foreach ($servicios_recargos as $servicio): ?>
													                            			<?php if ($servicio['status']==true){ ?>
													                            			<option value="<?= $servicio["id_servicios"]."|".$servicio['tipo']."|".$servicio['cod_servicios']."|".$servicio['costo']."|".$servicio['categoria'];?>" status="<?=$servicio['status'] ?>" tipo="<?=$servicio['tipo'] ?>"><?= $servicio["descripcion"]; ?></option>
													                            			<?php } ?>
													                            		<?php endforeach ?>
													                            	</select>
													                            </div>
													                            <div class="col-sm-6">
													                        		<label for="valor_actualizar">Cantidad*</label>
													                                <div class="form-group">
													                                    <div class="form-line">
													                                        <input type="text" class="form-control mayusculas" name="valor" id="valor_actualizar" placeholder="P. EJ. XXXX (X)" onkeypress='return solonumeros(event)' required>
													                                    </div>
													                                </div>
													                            </div>
													                            
													                            <div class="col-sm-2" style="padding-top: 25px;">
													                            	<button type="button" class="btn btn-primary waves-effect" onclick="agregarServicio('#servicio_actualizar', '#tableRegistrar', '#valor_actualizar')">Agregar</button>
													                            </div>
													                            <div class="col-sm-12" id="contenedorTablaRegistrar" name="contenedorTablaRegistrar" >
													                            	<table class="table table-bordered table-striped table-hover" id="tableRegistrar">
													                            		<thead>
													                            			<tr>
													                            				<th>Código</th>
													                            				<th>Título</th>
													                            				<th>Cantidad</th>
													                            				<th>Costo</th>
													                            				<th>Total por servicio</th>
													                            				<th class="campos_acciones">Acciones</th>
													                            			</tr>
													                            		</thead>
													                            		<tbody></tbody>
													                            	</table>
													                            </div>
													                            <div id="arreglo_servicios_opcionales" name="arreglo_servicios_opcionales" data2="0" style="display: block"></div>
													                            <div class="col-sm-12" >
													                            	<div class="col-lg-6"></div>
													                            	<div class="col-lg-6">
													                            		<label for="valor_actualizar">Monto total recargo*</label>
														                                <div class="form-group">
														                                    <div class="form-line">
														                            			<input type="text" name="monto_total_recargo" id="monto_total_recargo" class="form-control" style="float: right;text-align: right;" readonly value="0.00">
														                            			<!--Campo oculto -->
														                            			<input type="hidden" name="monto_total_recargo_oculto" id="monto_total_recargo_oculto" class="form-control" style="float: right;" readonly value="0">
														                            			<!--Campo oculto monto total guardado-->
														                            			<input type="hidden" name="ultimo_monto_total_guardado" id="ultimo_monto_total_guardado" class="form-control" style="float: right;" readonly value="0">
														                            		</div>
														                            	</div>
													                            	</div>
													                            </div>
													                            <div class="col-sm-12" >
													                            	<div class="col-lg-6"></div>
													                            	<div class="col-lg-6">
														                            	<label for="valor_actualizar">Monto a pagar*</label>
														                                <div class="form-group">
														                                    <div class="form-line">
														                            			<input type="text" name="monto_pagar" id="monto_pagar" class="form-control" style="float: right;text-align: right;" readonly value="0.00">
														                            			<!--Campo oculto -->
														                            			<input type="hidden" name="monto_pagar_oculto" id="monto_pagar_oculto" class="form-control" style="float: right;" value="0" readonly>
														                            			<!--Campo oculto monto a pagar-->
																								<input type="hidden" name="servicios_contratados_oculto" id="servicios_contratados_oculto" class="form-control" style="float: right;" value="0" readonly>
														                            			<!--Campo oculto para llevar conteo de los cargos a servicios contratados --->
														                            			<input type="hidden" name="ultimo_monto_pagar_guardado" id="ultimo_monto_pagar_guardado" class="form-control" style="float: right;" value="0" readonly>
														                            		</div>
														                            	</div>
														                            </div>		
													                            </div>	
													                        </div>
													                        <input type="hidden" name="tipo_registro" id="tipo_registro">
													            			<br>
													            			<div class="col-sm-4 col-sm-offset-5">
													                            <button type="button" onclick="regresar('#cuadro4')" class="btn btn-primary waves-effect">Regresar</button>
													                            <input type="button" value="Registrar" class="btn btn-success waves-effect" onclick="registrarRecargos()">
													                        </div>
													                    </form>    
													                </div>
													            </div>
													        </div>
													    </div>
													</div>
													<!--                              -->
												</div>
								        	</div>
								        	
					                    </div>	
			                    	</form>
								</div>
			                </div>
			                <!-- Fin de form editar jornada -->
						</div>
					</div>
				</div>		
		        <!-- Cierre del cuadro de editar  -->
			</div>
			<!-- Modal -->
			<div class="modal fade" id="modal_mensaje" name="modal_mensaje" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
			    <div class="modal-dialog modal-lg .modal-sm">
					<div class="modal-content">
						<div class="modal-header header_conf">
						    <button type="button" id="cerrar_mensaje2" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						    <p id="cabecera_mensaje" name="cabecera_mensaje"><h2>Servicios consumidos</h2></p>
						</div>
						<div class="modal-body" id="cuerpo_mensaje" name="cuerpo_mensaje">
						<!--Modal body -->
							<div class=row>
								<div id="tabla_modal" name="tabla_modal" class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
									
								</div>
								<div class="col-lg-12">
									<div class="col-lg-6"></div>
									<div class="col-lg-6">
		                            	<label for="valor_actualizar">Monto a pagar*</label>
		                                <div class="form-group">
		                                    <div class="form-line">
		                            			<input type="text" name="monto_pagar_modal" id="monto_pagar_modal" class="form-control" style="float: right;text-align: right;" readonly>
		                            		</div>
		                            	</div>
		                            </div>
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
    <script src="<?=base_url();?>assets/cpanel/Jornadas/js/jornadas.js"></script>
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
