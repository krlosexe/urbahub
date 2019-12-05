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
		                                            <th>Código</th>
		                                            <th>Título</th>
		                                            <th>Descripción</th>
		                                            <th>Posición</th>
		                                            <th>Vigencia</th>
		                                            <th>Plan empresarial</th>
		                                            <!--<th>Horas Jornada</th>-->
		                                            <th>Tiempo contrato</th>
		                                           	<!--<th>Precio</th>-->
		                                            <th>Fecha de Registro</th>
		                                            <th>Registrado Por</th>
		                                        </tr>
		                                    </thead>
		                                    <tbody></tbody>
		                                </table>
		                                <div class="col-md-2 eliminar ocultar">
		                                	<button class="btn btn-danger waves-effect" onclick="eliminarMultiple('Planes/eliminar_multiple_planes')">Eliminar seleccionados</button>
		                                </div>
		                                <div class="col-md-2 actualizar ocultar">
		                                	<button class="btn btn-warning waves-effect" onclick="statusMultiple('Planes/status_multiple_planes', 1, 'activar')">Activar seleccionados</button>
		                                </div>
		                                <div class="col-md-2 actualizar ocultar">
		                                	<button class="btn btn-warning waves-effect" onclick="statusMultiple('Planes/status_multiple_planes', 2, 'desactivar')">Desactivar seleccionados</button>
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
			                            <form name="form_planes_registrar" id="form_planes_registrar" method="post">
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
														<input type="hidden" name="indicador_jornadas_valor_actualizar" id="indicador_jornadas_valor_actualizar" value="N">
												</div>
											</div>


				                            <div class="col-sm-4">
			                            		<label for="cod_planes_registrar">Código*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control mayusculas" name="cod_planes" id="cod_planes_registrar" placeholder="P. EJ. N" required>
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
			                            		<label for="descripcion_registrar">Título*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control mayusculas" name="titulo" id="titulo_registrar" placeholder="P. EJ. XXXX (X)" required>
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
			                            		<label for="descripcion_registrar">Descripción*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control mayusculas" name="descripcion" id="descripcion_registrar" placeholder="P. EJ. XXXX (X)" required>
				                                    </div>
				                                </div>
				                            </div>
				                            <br>
			                            	

											<div id="div_hiddens">
												
												<div class="col-sm-4">
													<label for="vigencia_registrar">Vigencia*</label>
													<select name="vigencia" id="vigencia_registrar" class="form-control field_requires" required>
														<option value="" selected>Seleccione</option>
														<?php foreach ($vigencias as $vigencia): ?>
															<option value="<?= $vigencia["id_vigencia"]; ?>"><?= $vigencia["descripcion"]; ?></option>
														<?php endforeach ?>
													</select>
												</div>
												<div class="col-sm-4">
													<label for="tiempo_contrato_registrar">Tiempo Contrato*</label>
													<div class="form-group">
														<div class="form-line">
															<input type="text" class="form-control mayusculas field_requires" name="tiempo_contrato" id="tiempo_contrato_registrar" placeholder="P. EJ. X" required>
														</div>
													</div>
												</div>
												<div class="col-sm-4">
													<label for="posicion_planes_registrar">Posición*</label>
													<select id="posicion_planes_registrar" required class="form-control form-group field_requires" name="posicion_planes_registrar">
														<option value="">Seleccione</option>
													</select>
												</div>
												<!--<div class="col-sm-4">
													<label for="precio_registrar">Precio*</label>
													<div class="form-group">
														<div class="form-line">
															<input type="text" class="precio form-control" name="precio" id="precio_registrar" onkeypress='return valida(event)' placeholder="P. EJ. 2" required data-inputmask="'alias': 'numeric'">
														</div>
													</div>
												</div>
												<br>-->
												<div class="col-sm-12" style="padding: 0px;">
													
													<div class="col-sm-4">
														<div class="col-sm-12">
															<label for="cod_esquema_registrar">Plan empresarial*</label>
															<div class="switch">
																<label>
																No
																<input type="checkbox" id="indicador_plan_empresarial_registrar">
																<span class="lever"></span>
																Si
																</label>
															</div>
															<input type="hidden" name="indicador_plan_valor_registrar" id="indicador_plan_valor_registrar" value="N">
														</div>
													</div>
													<div class="col-sm-4">
														<div class="col-sm-12" style="padding: 0px;">
															<label for="muestra_web_registrar">¿Se muestra en la web?*</label>
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
												</div>	
											</div>
                                			<!--<div class="col-sm-4" id="jornadas_registrar">
			                            		<label for="descripcion_registrar">Horas de jornadas*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control mayusculas" name="horas_jornadas" id="horas_jornadas_registrar" placeholder="P. EJ. XXXX (X)" onkeypress='return valida(event)' data-inputmask="'alias': 'numeric'">
				                                    </div>
				                                </div>
				                            </div>-->
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
												<input type="hidden" name="indicador_jornadas_valor_actualizar" id="indicador_jornadas_valor_actualizar" value="N">
										</div>
									</div>



		                            	<div class="col-sm-4">
		                            		<label for="cod_esquema_consultar">Código*</label>
			                                <div class="form-group">
			                                    <div class="form-line">
			                                        <input type="text" class="form-control mayusculas" id="cod_planes_consultar" placeholder="P. EJ. N" disabled>
			                                    </div>
			                                </div>
			                            </div>
			                            <div class="col-sm-4">
			                            		<label for="descripcion_registrar">Título*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control mayusculas" id="titulo_consultar" placeholder="P. EJ. XXXX (X)" disabled="">
				                                    </div>
				                                </div>
				                            </div>
			                            <div class="col-sm-4">
		                            		<label for="descripcion_consultar">Descripción*</label>
			                                <div class="form-group">
			                                    <div class="form-line">
			                                        <input type="text" class="form-control mayusculas" id="descripcion_consultar" placeholder="P. EJ. XXXX (X)" disabled>
			                                    </div>
			                                </div>
			                            </div>
			                            <br>


										<div id="div_hiddens_view">
											<div class="col-sm-4">
												<label for="vigencia_consultar">Vigencia*</label>
												<select id="vigencia_consultar" class="form-control" disabled>
													<option value="" selected>Seleccione</option>
													<?php foreach ($vigencias as $vigencia): ?>
														<option value="<?= $vigencia["id_vigencia"]; ?>"><?= $vigencia["descripcion"]; ?></option>
													<?php endforeach ?>
												</select>
											</div>
											<div class="col-sm-4">
												<label for="tiempo_contrato_consultar">Tiempo Contrato*</label>
												<div class="form-group">
													<div class="form-line">
														<input type="text" class="form-control mayusculas" id="tiempo_contrato_consultar" placeholder="P. EJ. X" disabled>
													</div>
												</div>
											</div>
											<!--<div class="col-sm-4">
												<label for="precio_consultar">Precio*</label>
												<div class="form-group">
													<div class="form-line">
														<input type="text" class="form-control" name="precio_consultar" id="precio_consultar" onkeypress='return solonumeros(event)' placeholder="P. EJ. 2" disabled data-inputmask="'alias': 'numeric'">
													</div>
												</div>
											</div>
											<br>-->
											<div class="col-sm-4">
												<label for="posicion_paquetes_consultar">Posición*</label>
												<input type="text" id="posicion_planes_consultar" class="form-control" name="posicion_planes_consultar">
											</div>
											<div class="col-sm-12" style="padding: 0px;">
												<div class="col-sm-4">
													<div class="col-sm-12">
														<label for="cod_esquema_consultar">Plan empresarial*</label>
														<div class="switch">
															<label>
															No
															<input type="checkbox" id="indicador_plan_empresarial_consultar" disabled="">
															<span class="lever"></span>
															Si
															</label>
														</div>
														<input type="hidden" name="indicador_plan_valor_consultar" id="indicador_plan_valor_consultar" value="N">
													</div>
												</div>
												<div class="col-sm-4">
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
											</div>
										</div>
		                            		
	                            			<!--<div class="col-sm-4" id="jornadas_consultar">
			                            		<label for="descripcion_registrar">Horas de jornadas*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control mayusculas" name="horas_jornadas" id="horas_jornadas_consultar" placeholder="P. EJ. XXXX (X)" disabled="">
				                                    </div>
				                                </div>
				                            </div>-->
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
		                            <h2>Editar <?php echo $breadcrumbs[0]["nombre_lista_vista"]; ?></h2>
		                        </div>
		                       <div class="body">
		                        	<div class="table-responsive">
			                            <form name="form_planes_editar" id="form_planes_editar" method="post">

										<div class="form-group col-sm-12">
											<div class="col-sm-12" style="padding:0px;">
												<label for="cod_esquema_registrar">Membresia?</label>
												<div class="switch">
													<label>
														No
														<input type="checkbox" name="indicador_membresia" id="indicador_membresia_edit">
														<span class="lever"></span>
														Si
													</label>
												</div>
											</div>
										</div>



				                            <div class="col-sm-4">
			                            		<label for="cod_planes_editar">Código*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control mayusculas" name="cod_planes" id="cod_planes_editar" placeholder="P. EJ. N" required>
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
			                            		<label for="descripcion_registrar">Título*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control mayusculas" name="titulo" id="titulo_editar" placeholder="P. EJ. XXXX (X)" required>
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
			                            		<label for="descripcion_editar">Descripción*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control mayusculas" name="descripcion" id="descripcion_editar" placeholder="P. EJ. XXXX (X)" required>
				                                    </div>
				                                </div>
				                            </div>
				                             <input type="hidden" name="id_planes" id="id_planes_editar">
				                            <br>



											<div id="div_hiddens_edit">
													<div class="col-sm-4">
														<label for="vigencia_editar">Vigencia*</label>
														<select name="vigencia" id="vigencia_editar" class="form-control field_requires" >
															<option value="" selected>Seleccione</option>
															<?php foreach ($vigencias as $vigencia): ?>
																<option value="<?= $vigencia["id_vigencia"]; ?>"><?= $vigencia["descripcion"]; ?></option>
															<?php endforeach ?>
														</select>
													</div>
													<div class="col-sm-4">
														<label for="tiempo_contrato_editar">Tiempo Contrato*</label>
														<div class="form-group">
															<div class="form-line">
																<input type="text" class="form-control mayusculas field_requires" name="tiempo_contrato" id="tiempo_contrato_editar" placeholder="P. EJ. X" >
															</div>
														</div>
													</div>
													<!--<div class="col-sm-4">
														<label for="precio_editar">Precio*</label>
														<div class="form-group">
															<div class="form-line">
																<input type="text" class="form-control precio" name="precio" id="precio_editar" onkeypress='return solonumeros(event)' placeholder="P. EJ. 2" required data-inputmask="'alias': 'numeric'">
															</div>
														</div>
													</div>
													<br>-->
													<div class="col-sm-4">
														<label for="posicion_planes_editar">Posición*</label>
														<select id="posicion_planes_editar" required class="form-control form-group field_requires" name="posicion_planes_editar">
															<option value="">Seleccione</option>
														</select>
														<input type="hidden" name="inicial" id="inicial">
													</div>
													<div class="col-sm-12" style="padding: 0px;">
														<div class="col-sm-4">
															<div class="col-sm-12">
																<label for="cod_esquema_actualizar">Plan empresarial*</label>
																<div class="switch">
																	<label>
																	No
																	<input type="checkbox" id="indicador_plan_empresarial_actualizar">
																	<span class="lever"></span>
																	Si
																	</label>
																</div>
																<input type="hidden" name="indicador_plan_valor_actualizar" id="indicador_plan_valor_actualizar" value="N">
															</div>
														</div>
														<div class="col-sm-4">
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
													</div>	
											</div>
			                            	
	                            			<!--<div class="col-sm-4" id="jornadas_actualizar">
			                            		<label for="descripcion_registrar">Horas de jornadas*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control mayusculas" name="horas_jornadas" id="horas_jornadas_actualizar" placeholder="P. EJ. XXXX (X)" onkeypress='return valida(event)' data-inputmask="'alias': 'numeric'">
				                                    </div>
				                                </div>
				                            </div>-->
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
    <script src="<?=base_url();?>assets/cpanel/Planes/js/planes.js"></script>
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
