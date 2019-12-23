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
		                                        <th>Cotización</th>
		                                        <th>Cliente</th>
		                                        <th>Vendedor</th>
		                                        <th>Estatus</th>
		                                        <th>Plan</th>
		                                        <th>Paquete</th>
		                                        <!--<th>Fecha de inicio</th>-->
		                                        <th>Vigencia</th>
		                                        <th>Fecha de Registro</th>
		                                        <th>Registrado Por</th>
		                                    </tr>
		                                </thead>
		                                <tbody></tbody>
		                            </table>
		                            <div class="col-md-2 eliminar ocultar">
		                            	<button class="btn btn-danger waves-effect" onclick="eliminarMultiple('Membresia/eliminar_multiple')">Eliminar seleccionados</button>
		                            </div>
		                            <div class="col-md-2 actualizar ocultar">
		                            	<button class="btn btn-warning waves-effect" onclick="statusMultiple('Membresia/status_multiple', 1, 'activar')">Activar seleccionados</button>
		                            </div>
		                            <div class="col-md-2 actualizar ocultar">
		                            	<button class="btn btn-warning waves-effect" onclick="statusMultiple('Membresia/status_multiple', 2, 'desactivar')">Desactivar seleccionados</button>
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
			                        <form name="form_cotizacion_registrar" id="form_cotizacion_registrar" method="post" enctype="multipart/form-data">
			                        	<div class="row">
											<div class="col-sm-3">
							        			<img id="imagen_registrar" src="<?php echo base_url();?>assets/cpanel/Usuarios/images/default.png" alt="Tu avatar"/ class="img-responsive ima_error"
												style=" border-radius: 50%;width: 100px;height: 100px;margin: 0 auto; margin-top: 30px;">
												<!-- style="    border-radius: 50%;
												    width: 200px;
												    margin-top: 20px;
												    height: 200px;" -->
							        		</div>
							        		<!--Persona fisica -->


											<div class="col-sm-9">
												<div class="row">
												<div class="col-sm-2" id="tipopersona" style="padding: 15px 0px 0px 15px;">
														<label for="tipopersona">Tipo Persona*</label> <br>
														<input type="radio" checked name="rad_tipoper" id="fisica"  value="fisica">
														<label for= "fisica">Física</label>
														<input type="radio" name="rad_tipoper" id="moral"  value="moral">
														<label for= "moral">Moral</label>
													</div>
													<div class="col-sm-4">
														<div class="col-sm-12" style="padding:0px;">
															<label for="cod_esquema_registrar">Tipo de cotización*</label>
															<div class="switch">
																<label>
																Otro
																<input type="checkbox" id="indicador_jornadas_registrar" checked="checked">
																<span class="lever"></span>
																Membresía
																</label>
															</div>
															<input type="hidden" name="membresia" id="membresia" value="S">
														</div>
													</div> 
												</div>
											</div>

											 
							        		<div id="personaFisica">
								        		<div class="col-sm-9">
								        			<!-- -->
													<div class="col-lg-12">
                                                  
                                                    <hr>
	                                                </div>
								        	    	<div class="col-sm-6">
					                            		<label for="cliente_membresia_registrar">Id (Prospecto/CLiente)*</label>
						                                <div class="form-group valid-required">
						                                    <div class="form-line">
						                                        <select name="rfc_cotizacion_registrar_fisica" id="rfc_cotizacion_registrar_fisica" required class="fisicaf form-control" style="width:100%;"  >
					                            					<option value="" selected>Seleccione</option>
																		<optgroup label="Clientes"></optgroup>
																			<?php foreach ($clientes_fisica as $cliente): ?>
																			<option value="<?=$cliente['id_clientes'];?>"><?=$cliente['nombre_datos_personales'];?></option>
																			<?php endforeach ?>

																		<optgroup label="Prospectos"></optgroup>
																			<?php foreach ($prospectos as $prospecto): ?>
																				<?php if($prospecto["status"]): ?>
																					<option value="<?=$prospecto['id_cliente'];?>"><?= $prospecto['nombre_datos_personales']." ".$prospecto['apellido_p_datos_personales']." ".$prospecto['apellido_m_datos_personales'];?></option>
																				<?php endif ?>
																				
																			<?php endforeach ?>
					                            				</select>
					                                    	</div>
					                             		</div>
					                           		</div>

													   <div class="col-sm-6">
						                            		<label for="id_vendedor">Vendedor*</label>
						                                	<select name="id_vendedor" id="id_vendedor" required class="form-control vendedor fisicaf">
						                                    	<option value="" selected>Seleccione</option>
						                                		<?php if (sizeof($vendedores) == 1): ?>
						                                			<?php foreach ($vendedores as $vendedor): ?>
						                                    			<option value="<?= $vendedor['id_vendedor']; ?>"><?= $vendedor['nombre_vendedor']." ".$vendedor['apellido_vendedor']; ?></option>
						                                    		<?php endforeach ?>
						                                    	<?php else: ?>
							                                    		<?php foreach ($vendedores as $vendedor): ?>
							                                    			<option value="<?= $vendedor['id_vendedor']; ?>"><?= $vendedor['nombre_vendedor']." ".$vendedor['apellido_vendedor']; ?></option>
							                                    		<?php endforeach ?>
						                                		<?php endif ?>
						                                	</select>
							                            </div>

														
										        	
			                            			<hr>								        		
			                            		</div>
			                            	
								        	</div>
								        	<!--Persona moral -->
								        	<div id="personaMoral" style="display: none;">
								        		<div class="col-sm-9">
								        			<!-- -->
													<div class="col-lg-12">
	                                                    
	                                                    <hr>
	                                                </div>

					                           		<div class="col-sm-6">
					                            		<label for="identificacion_prospecto">Identificación (Prospecto/CLiente)*</label>
						                                <div class="form-group valid-required">
						                                    <div class="form-line">
						                                        <select name="rfc_cotizacion_registrar_moral" id="rfc_cotizacion_registrar_moral" required class="moralf form-control" style="width:100%;"  >
					                            					<option value="" selected>Seleccione</option>
																	<optgroup label="Clientes"></optgroup>
					                            						<?php foreach ($clientes_moral as $cliente): ?>
					                            					<option value="<?=$cliente['id_clientes'];?>"><?=$cliente['nombre_datos_personales'];?></option>
					                            						<?php endforeach ?>

																		<optgroup label="Prospectos"></optgroup>
																			<?php foreach ($prospectos as $prospecto): ?>
																				<?php if($prospecto["status"]): ?>
																					<option value="<?=$prospecto['id_cliente'];?>"><?= $prospecto['nombre_datos_personales']." ".$prospecto['apellido_p_datos_personales']." ".$prospecto['apellido_m_datos_personales'];?></option>
																				<?php endif ?>
																			<?php endforeach ?>


					                            				</select>
					                                    	</div>
					                             		</div>
					                           		</div>


													   <div class="col-sm-6">
						                            		<label for="id_vendedor_moral">Vendedor*</label>
						                                	<select name="id_vendedor_moral" id="id_vendedor_moral" required class="form-control vendedor moralf">
						                                    	<option value="" selected>Seleccione</option>
						                                		<?php if (sizeof($vendedores) == 1): ?>
						                                			<?php foreach ($vendedores as $vendedor): ?>
						                                    			<option value="<?= $vendedor['id_vendedor']; ?>"><?= $vendedor['nombre_vendedor']." ".$vendedor['apellido_vendedor']; ?></option>
						                                    		<?php endforeach ?>
						                                    	<?php else: ?>
							                                    		<?php foreach ($vendedores as $vendedor): ?>
							                                    			<option value="<?= $vendedor['id_vendedor']; ?>"><?= $vendedor['nombre_vendedor']." ".$vendedor['apellido_vendedor']; ?></option>
							                                    		<?php endforeach ?>
						                                		<?php endif ?>
						                                	</select>
							                            </div>
										        	
								        			<!-- -->
								        		</div>
								        	
								        	
			                        		</div>
			                           	</div>
								        <!--Registrar persona física -->
								       
						                    <!--Fin de registrar persona moral  -->




						                    <div class="col-lg-12">
								        			<div class="col-lg-12">
	                                                    <h3>Planes/Paquetes</h3>
	                                                    <hr>
	                                                    <!--- ---------------------->
	                                                    <div id="plan-membresia">
															<div class="col-sm-3 planes-cotizacion">
																<label for="plan_cotizacion_registrar">Planes*</label>
																<select name="plan_cotizacion_registrar_fisica" id="plan_cotizacion_registrar_fisica"  class="form-control fisicaf" onchange="consultarPaquetes(this.value,'guardar');">
																	<option value="" selected>Seleccione</option>
																	<?php foreach ($planes as $plan): ?>
																		<?php if ($plan['status']==true){ ?> 
																			<option value="<?= $plan["id_planes"]; ?>" status="<?=$plan['status'] ?>"><?= $plan["titulo"]." ".$plan["descripcion"]; ?></option>
																		<?php } ?> 
																	<?php endforeach ?>
																</select>
															</div>
															<div class="col-sm-3 paquetes-cotizacion">
																<label for="paquetes_cotizacion_registrar_fisica">Paquetes*</label>
																<select name="paquetes_cotizacion_registrar_fisica" id="paquetes_cotizacion_registrar_fisica"  class="form-control fisicaf" onchange="consultarPlan(this.value,'','guardar');">
																	<option value="" selected>Seleccione</option>
																</select>
															</div>
															<div class="col-sm-3">
																<label for="razon_social">Vigencia*</label>
																<div class="form-group valid-required fisicaf">
																	<div class="form-line">
																		<input type="text" class=" form-control mayusculas" name="vigencia_registrar_fisica" id="vigencia_registrar_fisica" maxlength="30" placeholder="EJEM 6 MESES" readonly="readonly">
																	</div>
																</div>
															</div>

															<button type="button" onclick="addPlan()" class="btn btn-primary">Agregar</button>
														</div>



														<div id="servicios_cotizacion">
															<div class="col-sm-3 planes-cotizacion">
																<label for="plan_cotizacion_registrar">Servicios*</label>
																<select id="services"  class="form-control fisicaf">
																	<option value="" selected>Seleccione</option>
																</select>
															</div>


															<div class="col-sm-3">
																<label for="razon_social">Valor Servicio*</label>
																<div class="form-group valid-required fisicaf">
																	<div class="form-line">
																		<input type="text" class=" form-control mayusculas" id="value_service" maxlength="30" placeholder="EJEM 6 MESES" readonly="readonly">
																	</div>
																</div>
															</div>


															<div class="col-sm-3">
																<label for="razon_social">Cantidad*</label>
																<div class="form-group valid-required fisicaf">
																	<div class="form-line">
																		<input type="number" class="form-control mayusculas" id="cantidad_service">
																	</div>
																</div>
															</div>


															<div class="col-sm-3">
																<button type="button" onclick="addService()" class="btn btn-primary">Agregar</button>
															</div>


															
														</div>



	                                                    <!-- ----------------------->
	                                                </div>    
                                                </div>
												
				                           		<!-- -->
				                           		<div class="col-lg-12">
				                           			<div class="col-lg-12">
	                                                    <!-- -->
	                                                    <div class="col-lg-12">
		                                                	<table class="table table-bordered table-striped table-hover" id="tableRegistrarFisica">
							                            		<thead>
							                            			<tr>
							                            				<th></th>
							                            				<th>Planes</th>
							                            				<th>Paquetes</th>
							                            				<th>Vigencia</th>
							                            				<th>Cantidad de trabajadores</th>
							                            				<th>Precio</th>
							                            				<th>Descuento</th>
							                            				<th colspan="2">I.V.A</th>
							                            				<th>Total</th>
							                            			</tr>
							                            			<tr>
							                            				<th></th>
							                            				<th></th>
							                            				<th></th>
							                            				<th></th>
							                            				<th> </th>
							                            				<th></th>
							                            				<th></th>
							                            				<th>%</th>
							                            				<th>Monto</th>
							                            				<th></th>
							                            			</tr>
							                            		</thead>
							                            		<tbody id="tbodyRegistrarFisica"></tbody>
							                            	</table>




															<table class="table table-bordered table-striped table-hover" id="tableRegistrarServiceFisica">
							                            		<thead>
							                            			<tr>
							                            				<th></th>
							                            				<th>Servicio</th>
							                            				<th>Monto PU</th>
							                            				<th>Cantidad</th>
																		<th>Descuento</th>
							                            				<th colspan="2">I.V.A</th>
																		<th>Total</th>
																																				
							                            			</tr>
							                            			<th></th>
							                            				<th></th>
							                            				<th></th>
							                            				<th></th>
							                            				<th></th>
							                            				<th>%</th>
							                            				<th>Monto</th>
							                            				<th></th>
							                            			<tr>
							                            				
							                            			</tr>
							                            		</thead>
							                            		<tbody></tbody>
							                            	</table>







															


		                                                </div>
		                                                <div class="col-lg-6">
											        	</div>
											        	<div class="col-lg-6 remove">
											        		<!--Monto Inscripcion -->
						                            		<label for="valor_mostrar">Monto Inscripcion*</label>
							                                <div class="form-group">
							                                    <div class="form-line">
							                            			<input type="text" name="monto_inscripcion_registrar_fisica" id="monto_inscripcion_registrar_fisica" class="form-control fisicaf" style="float: right;text-align: right;" readonly value="<?= $inscripcion["monto_inscripcion"];?>">
							                            			<!--Oculto -->
																	<input type="hidden" name="monto_inscripcion_registrar_fisica_oculto" id="monto_inscripcion_registrar_fisica_oculto" class="form-control" style="float: right;" readonly value="<?= $inscripcion["monto_inscripcion_oculto"];?>">
									                            	<!-- -->
							                            		</div>
							                            	</div>
							                            	
						                            	</div>
						                            	<div class="col-lg-6">
											        	</div>
											        	<div class="col-lg-6 remove">
											        		<!--Monto Paquete -->
						                            		<label for="valor_mostrar">Monto Mensualidad*</label>
							                                <div class="form-group">
							                                    <div class="form-line">
							                            			<input type="text" name="monto_paquete_registrar_fisica" id="monto_paquete_registrar_fisica" class="form-control fisicaf" style="float: right;text-align: right;" readonly value="0.00">
							                            			<!--Oculto -->
																	<input type="hidden" name="monto_paquete_registrar_fisica_oculto" id="monto_paquete_registrar_fisica_oculto" class="form-control" style="float: right;" readonly value="0">
									                            	<!-- -->
							                            		</div>
							                            	</div>
						                            	</div>
						                            	<div class="col-lg-6">
											        	</div>
										        		<div class="col-lg-6">
						                            		<label for="valor_mostrar">Monto total a pagar*</label>
							                                <div class="form-group">
							                                    <div class="form-line">
							                            			<input type="text" name="monto_total_registrar_fisica" id="monto_total_registrar_fisica" class="form-control fisicaf" style="float: right;text-align: right;" readonly value="0.00">
							                            			<!--Oculto -->
																	<input type="hidden" name="monto_paquete_registrar_fisica_oculto" id="monto_paquete_registrar_fisica_oculto" class="form-control" style="float: right;" readonly value="0">
									                            	<!-- -->
							                            		</div>
							                            	</div>
						                            	</div>
	                                                    
	                                                </div>    
                                                </div>
						       	 		<div class="col-sm-4 col-sm-offset-5">
				                            <button type="button" onclick="regresar('#cuadro2')" class="btn btn-primary waves-effect">Regresar</button>
				                            <input type="submit" value="Guardar" id="send" class="btn btn-success waves-effect save-cliente">
				                    	</div>
				                    	<div style="clear: both"></div>
					                    <!--</div>	-->
			                    	</form>
								</div>
			                </div>
			            </div>
			        </div>



					 <!--Modal de recibo-->
					<div class="modal fade" id="modal-service" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
							<div class="modal-dialog" role="document" style="width: 70%;">
								<div class="modal-content">
									<div class="modal-header">
									<h5 class="modal-title" id="exampleModalLabel">Servicios</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
									</div>
									<div class="modal-body">
										<table class="table table-bordered table-striped table-hover" id="table-services">
		                            		<thead>
		                            			<tr>
		                            				<th>Codigo</th>
		                            				<th>Disponible</th>
		                            				<th>Servicio</th>
		                            			</tr>
		                            		</thead>
		                            		<tbody></tbody>
		                            	</table>
									</div>
									<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
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
		                    <div class="body">
			                	<div class="table-responsive">
			                        <form name="form_cotizacion_mostrar" id="form_cotizacion_mostrar" method="post" enctype="multipart/form-data">
			                        	<div class="row">
											<div class="col-sm-3">
							        			<img id="imagen_mostrar" src="<?php echo base_url();?>assets/cpanel/Usuarios/images/default.png" alt="Tu avatar"/ class="img-responsive ima_error"
												style=" border-radius: 50%;width: 100px;height: 100px;margin: 0 auto; margin-top: 30px;">
											
							        		</div>


											<div class="col-sm-9">


											<div class="row">
														<div class="col-sm-3" id="tipopersonaC" style="padding: 15px 0px 0px 15px;">
															<label for="tipopersonaC">Tipo Persona*</label> <br>
															<input type="radio" checked name="rad_tipoperC" id="fisica_mostrar"  value="fisica">
															<label for= "fisica">Física</label>
															<input type="radio" name="rad_tipoperC" id="moral_mostrar"  value="moral">
															<label for= "moral">Moral</label>
															<input type="hidden" name="tipo_persona_mostrar" id="tipo_persona_mostrar">
														</div>
														<div class="col-sm-3">
															<div class="col-sm-12" style="padding:0px;">
																<label for="cod_esquema_registrar">Tipo de cotización*</label>
																<div class="switch">
																	<label>
																	Otro
																	<input type="checkbox" id="indicador_jornadas_mostrar">
																	<span class="lever"></span>
																	Membresía
																	</label>
																</div>
																<input type="hidden" name="indicador_jornadas_valor_mostrar" id="indicador_jornadas_valor_mostrar" value="N">
															</div>
														</div> 


														<div class="col-sm-3">
															<label for="nombre_cliente_cotizacionC">Numero de Cotizacion*</label>
															<div class="form-group valid-required">
																<div class="form-line">
																	<input type="text" class="fisicaf form-control mayusculas"  id="n_cotizacion"  readonly="readonly">
																</div>
															</div>
														</div>
														<div class="col-sm-3">
															<label for="apellido_paterno_clienteC">Fecha de Aprobacion*</label>
															<div class="form-group valid-required">
																<div class="form-line">
																	<input type="text" class="fisicaf form-control mayusculas" id="fecha_aprobacion_view" readonly="readonly">
																</div>
															</div>
														</div>
													</div> 
											</div>


									
							        		<!--Persona fisica -->
							        		<div id="personaFisicaC">
								        		<div class="col-sm-9">
								        			<!-- -->
													

								        	    	<div class="col-sm-6">
					                            		<label for="cliente_membresia_mostrar">Id (Prospecto/CLiente)*</label>
						                                <div class="form-group valid-required">
						                                    <div class="form-line">
						                                        <select name="rfc_cotizacion_mostrar_fisica" id="rfc_cotizacion_mostrar_fisica" required class="fisicaf form-control" style="width:100%;"  disabled>
					                            					<option value="" selected>Seleccione</option>
					                            						<?php foreach ($clientes_fisica as $cliente): ?>
					                            					<option value="<?=$cliente['id_clientes'];?>"><?=$cliente['nombre_datos_personales'];?></option>
					                            						<?php endforeach ?>
					                            				</select>
					                                    	</div>
					                             		</div>
					                           		</div>



													   <div class="col-sm-6">
						                            		<label for="id_vendedor">Vendedor*</label>
						                                	<select name="id_vendedor_mostrar" id="id_vendedor_mostrar"  class="form-control vendedor fisicaf" readonly="readonly">
						                                    	<option value="" selected>Seleccione</option>
						                                		<?php if (sizeof($vendedores) == 1): ?>
						                                			<?php foreach ($vendedores as $vendedor): ?>
						                                    			<option value="<?= $vendedor['id_vendedor']; ?>"><?= $vendedor['nombre_vendedor']." ".$vendedor['apellido_vendedor']; ?></option>
						                                    		<?php endforeach ?>
						                                    	<?php else: ?>
							                                    		<?php foreach ($vendedores as $vendedor): ?>
							                                    			<option value="<?= $vendedor['id_vendedor']; ?>"><?= $vendedor['nombre_vendedor']." ".$vendedor['apellido_vendedor']; ?></option>
							                                    		<?php endforeach ?>
						                                		<?php endif ?>
						                                	</select>
							                            </div>



										        	
								        			<!-- -->
								        		</div>
								
								        		
								        	</div>
								        	<!--Persona moral -->
								        	<div id="personaMoralC" style="display: none;">
								        		<div class="col-sm-9">
								        			<!-- -->
					                           		<div class="col-sm-6">
					                            		<label for="identificacion_prospectoC">Identificación (Prospecto/CLiente)*</label>
						                                <div class="form-group valid-required">
						                                    <div class="form-line">
						                                        <select name="rfc_cotizacion_mostrar_moral" id="rfc_cotizacion_mostrar_moral" required class="moralf form-control" style="width:100%;" disabled>
					                            					<option value="" selected>Seleccione</option>
					                            						<?php foreach ($clientes_moral as $cliente): ?>
					                            					<option value="<?=$cliente['id_clientes'];?>"><?=$cliente['nombre_datos_personales'];?></option>
					                            						<?php endforeach ?>
					                            				</select>
					                                    	</div>
					                             		</div>
					                           		</div>


													   <div class="col-sm-6">
						                            		<label for="id_vendedor_moralC">Vendedor*</label>
						                                	<select name="id_vendedor_moral_mostrar" id="id_vendedor_moral_mostrar"  class="form-control vendedor moralf" readonly="readonly">
						                                    	<option value="" selected>Seleccione</option>
						                                		<?php if (sizeof($vendedores) == 1): ?>
						                                			<?php foreach ($vendedores as $vendedor): ?>
						                                    			<option value="<?= $vendedor['id_vendedor']; ?>"><?= $vendedor['nombre_vendedor']." ".$vendedor['apellido_vendedor']; ?></option>
						                                    		<?php endforeach ?>
						                                    	<?php else: ?>
							                                    		<?php foreach ($vendedores as $vendedor): ?>
							                                    			<option value="<?= $vendedor['id_vendedor']; ?>"><?= $vendedor['nombre_vendedor']." ".$vendedor['apellido_vendedor']; ?></option>
							                                    		<?php endforeach ?>
						                                		<?php endif ?>
						                                	</select>
							                            </div>
										        	
								        		</div>
								        



			                        		</div>


				                           		<!-- -->
				                           		<div class="col-lg-12">
				                           			<div class="col-lg-12">
	                                                    <h3>Detalle - Planes/Paquetes</h3>
	                                                    <hr>
	                                                    <!-- -->
	                                                    <div class="col-lg-12">
		                                                	<table class="table table-bordered table-striped table-hover" id="tableMostrarFisica">
							                            		<thead>
							                            			<tr>
																		<th></th>
																		<th>Planes</th>
							                            				<th>Paquetes</th>
							                            				<th>Vigencia</th>
							                            				<th>Cantidad de trabajadores</th>
							                            				<th>Precio</th>
							                            			</tr>
							                            		</thead>
							                            		<tbody id="tbodyMostrarFisica"></tbody>
							                            	</table>



															<table class="table table-bordered table-striped table-hover" id="tableServiceMostrar">
							                            		<thead>
							                            			<tr>
																		<th>Servicio</th>
							                            				<th>Monto PU</th>
							                            				<th>Cantidad</th>
																		<th>Total</th>
							                            			</tr>
							                            		</thead>
							                            		<tbody></tbody>
							                            	</table>


		                                                </div>
		                                                <div class="col-lg-6">
											        	</div>
											        	<div class="col-lg-6 remove">
											        		<!--Monto Inscripcion -->
						                            		<label for="valor_mostrar">Monto Inscripcion*</label>
							                                <div class="form-group">
							                                    <div class="form-line">
							                            			<input type="text" name="monto_inscripcion_mostrar_fisica" id="monto_inscripcion_mostrar_fisica" class="form-control fisicaf" style="float: right;text-align: right;" readonly value="<?= $inscripcion["monto_inscripcion"];?>">
							                            			<!--Oculto -->
																	<input type="hidden" name="monto_inscripcion_mostrar_fisica_oculto" id="monto_inscripcion_mostrar_fisica_oculto" class="form-control" style="float: right;" readonly value="<?= $inscripcion["monto_inscripcion_oculto"];?>">
									                            	<!-- -->
							                            		</div>
							                            	</div>
							                            	
						                            	</div>
						                            	<div class="col-lg-6">
											        	</div>
											        	<div class="col-lg-6 remove">
											        		<!--Monto Paquete -->
						                            		<label for="valor_mostrar">Monto Mensualidad*</label>
							                                <div class="form-group">
							                                    <div class="form-line">
							                            			<input type="text" name="monto_paquete_mostrar_fisica" id="monto_paquete_mostrar_fisica" class="form-control fisicaf" style="float: right;text-align: right;" readonly value="0.00">
							                            			<!--Oculto -->
																	<input type="hidden" name="monto_paquete_mostrar_fisica_oculto" id="monto_paquete_mostrar_fisica_oculto" class="form-control" style="float: right;" readonly value="0">
									                            	<!-- -->
							                            		</div>
							                            	</div>
						                            	</div>
						                            	<div class="col-lg-6">
											        	</div>
										        		<div class="col-lg-6">
						                            		<label for="valor_mostrar">Monto total a pagar*</label>
							                                <div class="form-group">
							                                    <div class="form-line">
							                            			<input type="text" name="monto_total_mostrar_fisica" id="monto_total_mostrar_fisica" class="form-control fisicaf" style="float: right;text-align: right;" readonly value="0.00">
							                            			<!--Oculto -->
																	<input type="hidden" name="monto_total_mostrar_fisica_oculto" id="monto_total_mostrar_fisica_oculto" class="form-control" style="float: right;" readonly value="0">
									                            	<!-- -->
							                            		</div>
							                            	</div>
						                            	</div>


														<!--Modal de recibo-->
														<div class="modal fade" id="modal-service-view" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
																<div class="modal-dialog" role="document" style="width: 70%;">
																	<div class="modal-content">
																		<div class="modal-header">
																		<h5 class="modal-title" id="exampleModalLabel">Servicios</h5>
																		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																			<span aria-hidden="true">&times;</span>
																		</button>
																		</div>
																		<div class="modal-body">
																			<table class="table table-bordered table-striped table-hover" id="table-services-view">
																				<thead>
																					<tr>
																						<th>Codigo</th>
																						<th>Disponible</th>
																						<th>Servicio</th>
																					</tr>
																				</thead>
																				<tbody></tbody>
																			</table>
																		</div>
																		<div class="modal-footer">
																		<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
																		</div>
																	</div>
																</div>
														</div>
																							
	                                                </div>    
                                                </div>
			                        		
			                        		<input type="hidden" id="id_cotizacion_mostrar" name="id_cotizacion_mostrar">
			                        		<input type="hidden" id="numero_cotizacionC" name="numero_cotizacionC">
			                           	</div>
								        
						       	 		<div class="col-sm-4 col-sm-offset-5">
				                            <button type="button" onclick="regresar('#cuadro3')" class="btn btn-primary waves-effect">Regresar</button>
				                             <a name="btn_imprimirC" id="btn_imprimirC" value="Imprimir" class="btn btn-warning waves-effect" target="_blank" >
				                            	PDF
				                            </a>
				                            <a name="btn_mailC" id="btn_mailC"  class="btn btn-danger waves-effect" target="_blank" onclick="enviar_emailC()">Enviar por Correo</a>
		                                   
				                    	</div>
				                    	<div style="clear: both"></div>
					                    <!--</div>	-->
			                    	</form>
								</div>
			        		</div>
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
					        <div class="body">
			                	<div class="table-responsive">
			                        <form name="form_cotizacion_actualizar" id="form_cotizacion_actualizar" method="post" enctype="multipart/form-data">
			                        	<div class="row">
											<div class="col-sm-3">
							        			<img id="imagen_actualizar" src="<?php echo base_url();?>assets/cpanel/Usuarios/images/default.png" alt="Tu avatar"/ class="img-responsive ima_error"
												style=" border-radius: 50%;width: 100px;height: 100px;margin: 0 auto; margin-top: 30px;">
												<!-- style="    border-radius: 50%;
												    width: 200px;
												    margin-top: 20px;
												    height: 200px;" -->
												<div class="form-group" id="tipopersonaE" style="padding: 15px 0px 0px 15px;">
					    							<label for="tipopersonaE">Tipo Persona*</label> <br>
					    							<input type="radio" checked name="rad_tipoperE" id="fisica_actualizar"  value="fisica">
					    							<label for= "fisica">Física</label>
					    							<input type="radio" name="rad_tipoperE" id="moral_actualizar"  value="moral">
					    							<label for= "moral">Moral</label>
					    							<input type="hidden" name="tipo_persona_actualizar" id="tipo_persona_actualizar">
					    						</div>
					    						<div class="form-group col-sm-4">
		                            				<div class="col-sm-12" style="padding:0px;">
					                            		<label for="cod_esquema_registrar">Tipo de cotización*</label>
				                                    	<div class="switch">
														    <label>
															Otro
														      <input type="checkbox" id="indicador_jornadas_actualizar">
														      <span class="lever"></span>
														      Membresía
														    </label>
														</div>
														 <input type="hidden" name="indicador_jornadas_valor_actualizar" id="indicador_jornadas_valor_actualizar" value="N">
						                            </div>
		                            			</div>   
							        		</div>
							        		<!--Persona fisica -->
							        		<div id="personaFisicaE">
								        		<div class="col-sm-9">
								        			<!-- -->
													<div class="col-lg-12">
                                                    <h3>Datos Cliente</h3>
                                                    <hr>
	                                                </div>
								        	    	<div class="col-sm-6">
					                            		<label for="cliente_membresia_actualizar">Id (Prospecto/CLiente)*</label>
						                                <div class="form-group valid-required">
						                                    <div class="form-line">
						                                        <select name="rfc_cotizacion_actualizar_fisica" id="rfc_cotizacion_actualizar_fisica" required class="fisicaf form-control" style="width:100%;"  disabled>
					                            					<option value="" selected>Seleccione</option>
					                            						<?php foreach ($clientes_fisica as $cliente): ?>
					                            					<option value="<?=$cliente['id_clientes'];?>"><?=$cliente['nombre_datos_personales'];?></option>
					                            						<?php endforeach ?>
					                            				</select>
					                                    	</div>
					                             		</div>
					                           		</div>
										        	<div class="col-sm-6">
					                            		<label for="nombre_cliente_cotizacionE">Nombre(s)*</label>
						                                <div class="form-group valid-required">
						                                    <div class="form-line">
						                                        <input type="text" class="fisicaf form-control mayusculas" name="nombre_fisica_actualizar" autocomplete="off" onkeypress='return sololetras(event)' id="nombre_fisica_actualizar" maxlength="30" placeholder="P. EJ.LUIS RAÚL" readonly="readonly">
					                                    	</div>
					                             		</div>
					                           		</div>
					                           		<div class="col-sm-6">
				                            			<label for="apellido_paterno_clienteE">Apellido Paterno*</label>
					                                	<div class="form-group valid-required">
					                                    	<div class="form-line">
					                                        	<input type="text" class="fisicaf form-control mayusculas" name="apellido_paterno_fisica_actualizar" autocomplete="off" onkeypress='return sololetras(event)' maxlength="15" id="apellido_paterno_fisica_actualizar" placeholder="P. EJ. BELLO" readonly="readonly">
					                                    	</div>
					                                	</div>
					                             	</div>
					                             	<div class="col-sm-6">
					                            		<label for="apellido_materno_clienteE">Apellido Materno*</label>
						                                <div class="form-group valid-required">
						                                    <div class="form-line">
						                                        <input type="text" class="fisicaf form-control mayusculas" name="apellido_materno_fisica_actualizar" autocomplete="off" maxlength="15" onkeypress='return sololetras(event)' id="apellido_materno_fisica_actualizar" placeholder="P. EJ. MENA" readonly="readonly">
						                                    </div>
					                                	</div>
				                          			</div>
								        			<!-- -->
								        		</div>
								        		<div class="col-sm-12">
								        			<div class="col-sm-6">
					                                	<label for="telefono_registrar">Teléfono*</label>
						                                <div class="form-group valid-required">
						                                    <div class="form-line">
						                                        <input type="text" class="form-control telefono fisicaf" name="telefono_fisica_actualizar" id="telefono_fisica_actualizar" placeholder="P. EJ.: +00 (000) 000-00-00" onkeyup="validPhone(this)"  readonly="readonly">
				                                        		<span class="emailError text-danger"></span>
						                                    </div>
				                               		 	</div>
			                            			</div>
			                            			<div class="col-sm-6" style="padding:0px">
						                                <label for="correo_usuario_registrar">Correo Electrónico*</label>
						                                <div class="form-group valid-required">
					                                    	<div class="form-line">
					                                        <input type="email" class="form-control fisicaf" autocomplete="off" name="correo_fisica_actualizar" id="correo_fisica_actualizar" placeholder="P. EJ. ejemplo@dominio.com" onchange="validEmail(this)" readonly="readonly">
				                                        	<span class="emailError text-danger"></span>
				                                    		</div>
			                                			</div>
		                                			</div>
			                            			<hr>								        		
			                            		</div>
			                            		<div class="col-lg-12">
			                            			<div class="col-lg-12">
	                                                    <h3>Datos Vendedor</h3>
	                                                    <hr>
	                                                    <!-- -->
	                                                    <div class="col-sm-6">
						                            		<label for="id_vendedor">Vendedor*</label>
						                                	<select name="id_vendedor_actualizar" id="id_vendedor_actualizar" required class="form-control vendedor fisicaf">
						                                    	<option value="" selected>Seleccione</option>
						                                		<?php if (sizeof($vendedores) == 1): ?>
						                                			<?php foreach ($vendedores as $vendedor): ?>
						                                    			<option value="<?= $vendedor['id_vendedor']; ?>"><?= $vendedor['nombre_vendedor']." ".$vendedor['apellido_vendedor']; ?></option>
						                                    		<?php endforeach ?>
						                                    	<?php else: ?>
							                                    		<?php foreach ($vendedores as $vendedor): ?>
							                                    			<option value="<?= $vendedor['id_vendedor']; ?>"><?= $vendedor['nombre_vendedor']." ".$vendedor['apellido_vendedor']; ?></option>
							                                    		<?php endforeach ?>
						                                		<?php endif ?>
						                                	</select>
							                            </div>
	                                                    <!-- -->
	                                                </div>
			                            		</div>

								        		
								        	</div>
								        	<!--Persona moral -->
								        	<div id="personaMoralE" style="display: none;">
								        		<div class="col-sm-9">
								        			<!-- -->
													<div class="col-lg-12">
	                                                    <h3>Datos Cliente</h3>
	                                                    <hr>
	                                                </div>
					                           		<div class="col-sm-6">
					                            		<label for="identificacion_prospectoE">Identificación (Prospecto/CLiente)*</label>
						                                <div class="form-group valid-required">
						                                    <div class="form-line">
						                                        <select name="rfc_cotizacion_actualizar_moral" id="rfc_cotizacion_actualizar_moral" required class="moralf form-control" style="width:100%;"  disabled>
					                            					<option value="" selected>Seleccione</option>
					                            						<?php foreach ($clientes_moral as $cliente): ?>
					                            					<option value="<?=$cliente['id_clientes'];?>"><?=$cliente['nombre_datos_personales'];?></option>
					                            						<?php endforeach ?>
					                            				</select>
					                                    	</div>
					                             		</div>
					                           		</div>
										        	<div class="col-sm-6">
						                            		<label for="razon_socialE">Denominación o Razón Social*</label>
							                                <div class="form-group valid-required">
							                                    <div class="form-line">
							                                        <input type="text" class="moralf form-control mayusculas" name="razon_social_actualizar" id="razon_social_actualizar" maxlength="30" placeholder="P. EJ.AG SITEMAS" readonly="readonly">
						                                    	</div>
						                             		</div>
						                           	</div>
						                            <div class="col-sm-6">
				                            			<label for="correo_moral_mE">Correo Electrónico*</label>
						                                <div class="form-group valid-required">
					                                    	<div class="form-line">
					                                        <input type="email" class="form-control moralf" name="correo_moral_actualizar" id="correo_moral_actualizar" placeholder="P. EJ. ejemplo@dominio.com" onchange="validEmail(this)" readonly="readonly">

				                                        	<span class="emailError text-danger"></span>
				                                    		</div>
			                                			</div>
				                            		</div>
				                            		<div class="col-sm-6">
					                            		<label for="telefono_moral_mE">Teléfono*</label>
						                                <div class="form-group valid-required">
						                                    <div class="form-line">
						                                        <input type="text" class="moralf form-control telefono" name="telefono_moral_actualizar" id="telefono_moral_actualizar" placeholder="P. EJ.: +00 (000) 000-00-00" onkeyup="validPhone(this)" readonly="readonly">
				                                        		<span class="emailError text-danger"></span>
						                                    </div>
				                               		 	</div>
			                            			</div>
								        			<!-- -->
								        		</div>
								        		<div class="col-lg-12">
			                            			<div class="col-lg-12">
	                                                    <h3>Datos Vendedor</h3>
	                                                    <hr>
	                                                    <!-- -->
	                                                    <div class="col-sm-6">
						                            		<label for="id_vendedor_moralE">Vendedor*</label>
						                                	<select name="id_vendedor_moral_actualizar" id="id_vendedor_moral_actualizar" required class="form-control vendedor moralf">
						                                    	<option value="" selected>Seleccione</option>
						                                		<?php if (sizeof($vendedores) == 1): ?>
						                                			<?php foreach ($vendedores as $vendedor): ?>
						                                    			<option value="<?= $vendedor['id_vendedor']; ?>"><?= $vendedor['nombre_vendedor']." ".$vendedor['apellido_vendedor']; ?></option>
						                                    		<?php endforeach ?>
						                                    	<?php else: ?>
							                                    		<?php foreach ($vendedores as $vendedor): ?>
							                                    			<option value="<?= $vendedor['id_vendedor']; ?>"><?= $vendedor['nombre_vendedor']." ".$vendedor['apellido_vendedor']; ?></option>
							                                    		<?php endforeach ?>
						                                		<?php endif ?>
						                                	</select>
							                            </div>
	                                                    <!-- -->
	                                                </div>
			                            		</div>
								        		
			                        		</div>



			                        		<div class="col-lg-12">
								        			<div class="col-lg-12">
	                                                    <h3>Planes/Paquetes</h3>
	                                                    <hr>
	                                                    <!--- ---------------------->
	                                                    <div class="col-sm-3 planes-cotizacion">
						                            		<label for="plan_cotizacion_actualizar">Planes*</label>
					                                    	<select name="plan_cotizacion_actualizar_fisica" id="plan_cotizacion_actualizar_fisica" required class="form-control fisicaf" onchange="consultarPaquetes(this.value,'actualizar','');">
					                                    		<option value="" selected>Seleccione</option>
					                                    		<?php foreach ($planes as $plan): ?>
					                                    			<?php if ($plan['status']==true){ ?> 
					                                    				<option value="<?= $plan["id_planes"]; ?>" status="<?=$plan['status'] ?>"><?= $plan["titulo"]." ".$plan["descripcion"]; ?></option>
					                                    			<?php } ?> 
					                                    		<?php endforeach ?>
					                                    	</select>
							                            </div>
							                            <div class="col-sm-3 paquetes-cotizacion-actualizar">
						                            		<label for="paquetes_cotizacion_actualizar_fisica">Paquetes*</label>
					                                    	<select name="paquetes_cotizacion_actualizar_fisica" id="paquetes_cotizacion_actualizar_fisica" required class="form-control fisicaf" onchange="consultarPlan(this.value,'','actualizar');">
					                                    		<option value="" selected>Seleccione</option>
					                                    	</select>
							                            </div>
							                            <div class="col-sm-3">
						                            		<label for="razon_social">Vigencia*</label>
							                                <div class="form-group valid-required fisicaf">
							                                    <div class="form-line">
							                                        <input type="text" class=" form-control mayusculas" name="vigencia_actualizar_fisica" id="vigencia_actualizar_fisica" maxlength="30" placeholder="EJEM 6 MESES" readonly="readonly">
						                                    	</div>
						                             		</div>
						                           		</div>


						                           		<div class="col-sm-3">
						                            		<button type="button" onclick="addPlanEdit()" class="btn btn-primary">Agregar</button>
						                           		</div>


	                                                    <!-- ----------------------->
	                                                </div>    
                                                </div>
												
				                           		<!-- -->
				                           		<div class="col-lg-12">
				                           			<div class="col-lg-12">
	                                                    <h3>Detalles - Planes/Paquetes</h3>
	                                                    <hr>
	                                                    <!-- -->
	                                                    <div class="col-lg-12">
		                                                	<table class="table table-bordered table-striped table-hover" id="tableActualizarFisica">
							                            		<thead>
							                            			<tr>
							                            				<th></th>
							                            				<th>Planes</th>
							                            				<th>Paquetes</th>
							                            				<th>Vigencia</th>
							                            				<th>Cantidad de trabajadores</th>
							                            				<th>Precio</th>
							                            			</tr>
							                            		</thead>
							                            		<tbody id="tbodyActualizarFisica"></tbody>
							                            	</table>
		                                                </div>
		                                                <div class="col-lg-6">
											        	</div>
											        	<div class="col-lg-6">
											        		<!--Monto Inscripcion -->
						                            		<label for="valor_mostrar">Monto Inscripcion*</label>
							                                <div class="form-group">
							                                    <div class="form-line">
							                            			<input type="text" name="monto_inscripcion_actualizar_fisica" id="monto_inscripcion_actualizar_fisica" class="form-control fisicaf" style="float: right;text-align: right;" readonly value="<?= $inscripcion["monto_inscripcion"];?>">
							                            			<!--Oculto -->
																	<input type="hidden" name="monto_inscripcion_actualizar_fisica_oculto" id="monto_inscripcion_actualizar_fisica_oculto" class="form-control" style="float: right;" readonly value="<?= $inscripcion["monto_inscripcion_oculto"];?>">
									                            	<!-- -->
							                            		</div>
							                            	</div>
							                            	
						                            	</div>
						                            	<div class="col-lg-6">
											        	</div>
											        	<div class="col-lg-6">
											        		<!--Monto Paquete -->
						                            		<label for="valor_mostrar">Monto Mensualidad*</label>
							                                <div class="form-group">
							                                    <div class="form-line">
							                            			<input type="text" name="monto_paquete_actualizar_fisica" id="monto_paquete_actualizar_fisica" class="form-control fisicaf" style="float: right;text-align: right;" readonly value="0.00">
							                            			<!--Oculto -->
																	<input type="hidden" name="monto_paquete_actualizar_fisica_oculto" id="monto_paquete_actualizar_fisica_oculto" class="form-control" style="float: right;" readonly value="0">
									                            	<!-- -->
							                            		</div>
							                            	</div>
						                            	</div>
						                            	<div class="col-lg-6">
											        	</div>
										        		<div class="col-lg-6">
						                            		<label for="valor_mostrar">Monto total a pagar*</label>
							                                <div class="form-group">
							                                    <div class="form-line">
							                            			<input type="text" name="monto_total_actualizar_fisica" id="monto_total_actualizar_fisica" class="form-control fisicaf" style="float: right;text-align: right;" readonly value="0.00">
							                            			<!--Oculto -->
																	<input type="hidden" name="monto_total_actualizar_fisica_oculto" id="monto_total_actualizar_fisica_oculto" class="form-control" style="float: right;" readonly value="0">
									                            	<!-- -->
							                            		</div>
							                            	</div>
						                            	</div>
	                                                    
	                                                </div>    
                                                </div>
			                        		
			                        		<input type="hidden" id="id_cotizacion_actualizar" name="id_cotizacion_actualizar">
			                        		<input type="hidden" id="numero_cotizacionE" name="numero_cotizacionE">
			                        		<input type="hidden" name="accion" id="accion">
			                           	</div>
								        
						       	 		<div class="col-sm-4 col-sm-offset-5">
				                            <button type="button" onclick="regresar('#cuadro4')" class="btn btn-primary waves-effect">Regresar</button>
				                            <a name="btn_imprimir" id="btn_imprimir" value="Imprimir" class="btn btn-warning waves-effect" target="_blank" >
				                            	Imprimir
				                            </a>
		                                    <a name="btn_mail" id="btn_mail"  class="btn btn-danger waves-effect" target="_blank" onclick="enviar_email()">Enviar por Mail</a>
		                                    <input type="submit" value="Actualizar" class="btn btn-success waves-effect">
				                    	</div>
				                    	<div style="clear: both"></div>
					                    <!--</div>	-->
			                    	</form>
								</div>
			        		</div>
						</div>
					</div>
				</div>













				<!-- Comienzo del cuadro de editar -->
				<div class="row clearfix ocultar" id="cuadro5">
		            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                <div class="card">
					        <div class="header">
					            <h2>Aprobacion de <?php echo $breadcrumbs[0]["nombre_lista_vista"]; ?></h2>
					        </div>
					        <div class="body">
			                	<div class="table-responsive">
			                        <form name="form_cotizacion_actualizar" id="form_aprobar" method="post" enctype="multipart/form-data">
										
										
										<div class="row">
											<div class="col-md-3">
												<label for="valor_mostrar">Fecha de Aprobacion*</label>
													<input type="date" name="fecha_aprobacion" id="fecha_aprobacion" class="form-control" required>
											</div>
										</div>


										<div class="row">
											<div class="col-md-12">
												<table class="table table-bordered table-striped table-hover" id="tableAprobarListPlanes">
													<thead>
														<tr>
															<th>Planes</th>
															<th>Paquetes</th>
															<th>Usuarios</th>
															<th>Acciones</th>
														</tr>
													</thead>
													<tbody id="tbodyActualizarFisica"></tbody>
												</table>
											</div>
			                           	</div>


										   <div class="row">
												<div class="col-md-12">
													<h4>Cliente / Facturar</h4>
													<hr>
												</div>
										   </div>


										   <div class="row">
												<div class="col-md-12">
													<table class="table table-bordered table-striped table-hover" id="tableAprobarFacturar">
														<thead>
															<tr>
																<th>Plan / Paquete</th>
																<th>Cliente</th>
																<th>Facturar</th>
															</tr>
														</thead>
														<tbody></tbody>
													</table>
												</div>
											</div>


											<input type="hidden" id="id_cotizacion" name="id_cotizacion">


								        
						       	 		<div class="col-sm-4 col-sm-offset-5">
				                            <button type="button" onclick="regresar('#cuadro5')" class="btn btn-primary waves-effect">Regresar</button>
				                           
		                                    <input type="submit" value="Aprobar" class="btn btn-success waves-effect">
				                    	</div>
				                    	<div style="clear: both"></div>
					                    <!--</div>	-->
			                    	</form>
								</div>
			        		</div>
						</div>
					</div>
				</div>













		        <!-- Cierre del cuadro de editar  -->
		        <input type="hidden" name="id_membresia" id="id_membresia">
		        <input type="hidden" name="numero_renovacion" id="numero_renovacion">
		        <!--Modal aceptar documentos -->
                <div class="modal fade" id="modal_aceptar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
					  <div class="modal-dialog" role="document" style="width: 70%;">
					    <div class="modal-content">
					      <div class="modal-header">
					        <h5 class="modal-title" id="exampleModalLabel">Aceptar Cotización</h5>
					        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
					          <span aria-hidden="true">&times;</span>
					        </button>
					      </div>
					      <div class="modal-body">
					         <form id="form_aceptar_cotizacion" method="post"  onKeypress="if(event.keyCode == 13) event.returnValue = false;" enctype="multipart/form-data">

								<div class="row">
			                            <div class="col-xs-12" style="height: 279px;margin-bottom: 15%;">
	                              			<label>Carta simple describiendo actividad comercial</label>
			                                <div class="form-group valid-required">
			                                    <div class="form-line">
			                                        <input type="file" id="carta_aceptar_cotizacion"  name="asasd" data-show-upload="false"  class="form-control mayusculas file_venta"  placeholder="Arrastre y suelte aquí los archivos" >
			                                    </div>
			                                </div>
	                            		</div>
									</div>
									<input type="hidden" name="numero_cotizacionA" id="numero_cotizacionA">
									<input type="hidden" name="id_cotizacionA" id="id_cotizacionA">
								<div style="margin-top: 60px;">	
								<center>
									<button type="submit" class="btn btn-success" >Aplicar</button>
									
								</center>
									
								</div>
					         </form>
					      </div>
					      <div class="modal-footer">
					        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>

					      </div>
					    </div>
					  </div>
			    </div>
                <!-- -->
                  <!--Modal ver documentos -->
                <div class="modal fade" id="modal_ver_documentos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
					  <div class="modal-dialog" role="document" style="width: 70%;">
					    <div class="modal-content">
					      <div class="modal-header">
					        <h5 class="modal-title" id="exampleModalLabel">Ver documentos</h5>
					        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
					          <span aria-hidden="true">&times;</span>
					        </button>
					      </div>
					      <div class="modal-body">
					         <form id="form_aceptar_cotizacion" method="post"  onKeypress="if(event.keyCode == 13) event.returnValue = false;" enctype="multipart/form-data">

								<div class="row">
			                            <div class="col-xs-12" style="height: 279px;margin-bottom: 15%;">
	                              			<label>Carta simple describiendo actividad comercial</label>
			                                <div class="form-group valid-required">
			                                    <div class="form-line">
			                                        <input type="file" id="carta_aceptar_cotizacion_view"  name="asasd" data-show-upload="false"  class="form-control mayusculas file_venta"  placeholder="Arrastre y suelte aquí los archivos" readonly>
			                                    </div>
			                                </div>
	                            		</div>
									</div>
									<input type="hidden" name="numero_cotizacionV" id="numero_cotizacionA">
									<input type="hidden" name="id_cotizacionV" id="id_cotizacionA">
					         </form>
					      </div>
					      <div class="modal-footer">
					        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>

					      </div>
					    </div>
					  </div>
			    </div>
                <!-- -->
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

     

    <script src="<?=base_url();?>assets/cpanel/Cotizacion/js/cotizacion.js"></script>
    <!-- Select 2 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <!-- -->
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
