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
	<?php if(($permiso[0]->general==1 && $permiso[0]->detallada==1 && $permiso[0]->registrar==1 && $permiso[0]->actualizar==1 && $permiso[0]->eliminar==1) OR $permiso[0]->status==false): ?>
		<script src="<?=base_url();?>assets/cpanel/js/permiso.js"></script>
	<?php endif ?>
	<body class="theme-blue">
		<input type="hidden" id="ruta" value="<?=base_url();?>" name="ruta">
			<input type="hidden" id="len_num">
	        <div class="container-fluid">
	        	<div id="alertas"></div>
	        	<div class="block-header">
	  
	            </div>
	        	<!-- Comienzo del cuadro de la tabla -->
					<div class="row clearfix" id="cuadro1">
		                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                    <div class="card">
		                        <div class="header">
		                            <h2>
		                                Gestión de Representante Legal del Cliente Pagador 
		                            </h2>
		                            <ul class="header-dropdown m-r--5">
		                                <button class="btn btn-primary waves-effect registrar ocultar" onclick="nuevoRegistro()"><i class='fa fa-plus-circle' style="color: white; font-size: 18px;"></i> | Nuevo</button>
		                            </ul>
		                        </div>
		                        <div class="body">
		                            <div class="table-responsive">

		                                <table class="table table-bordered table-striped table-hover" id="tabla">
		                                <input type="hidden" class="form-control mayusculas" name="id_cliente" id="id_cliente" value="<?=$id_cliente;?>">  
		                                  <thead>
		                                        <tr>
		                                        	<th style="text-align: center; padding: 0px 10px 0px 5px;"><input type="checkbox" id="checkall" class="chk-col-blue"/><label for="checkall"></label></th>
		                                        	<th style="width: 17%;">Acciones</th>
		                                            <th>Nombre</th>
		                                            <th>Apellido Paterno</th>
		                                            <th>Apellido Materno</th>
		                                            <th>Fecha de Registro</th>
		                                            <th>Registrado Por</th>
		                                            
		                                        </tr>
		                                    </thead>
		                                    <tbody></tbody>
		                                </table>
		                                <div class="col-md-2 eliminar ocultar">
		                                	<button class="btn btn-danger waves-effect" onclick="eliminarMultiple('ClientePagador/eliminar_multiple_repLegal')">Eliminar seleccionados</button>
		                                </div>
		                                <div class="col-md-2 actualizar ocultar">
		                                	<button class="btn btn-warning waves-effect" onclick="statusMultiple('ClientePagador/status_multiple_repLegal', 1, 'activar')">Activar seleccionados</button>
		                                </div>
		                                <div class="col-md-2 actualizar ocultar">
		                                	<button class="btn btn-warning waves-effect" onclick="statusMultiple('ClientePagador/status_multiple_repLegal', 2, 'desactivar')">Desactivar seleccionados</button>
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
		                            <h2>Registro del Representante Legal del Cliente Pagador </h2>
		                        </div>
		                        <div class="body">
		                        	<div class="table-responsive">
			                            <form name="form_repLegal_registrar" id="form_repLegal_registrar" method="post">
			                            	<input type="hidden" class="form-control mayusculas" name="id_cliente" id="id_cliente_registrar" value="<?=$id_cliente;?>">
 											<div class="" id="rep_legal">
									        	<div class="col-sm-4">
		                            		<label for="nombre_representante">Nombre(s)*</label>
			                                <div class="form-group valid-required">
			                                    <div class="form-line">
			                                        <input type="text" class="form-control mayusculas" name="nombre_representante" id="nombre_representante" placeholder="P. EJ. LUIS RAÚL" onkeypress='return sololetras(event)' required>
			                                    </div>
			                                </div>
		                            	</div>
			                            <div class="col-sm-4">
		                            		<label for="apellido_paterno_rep">Apellido Paterno*</label>
			                                <div class="form-group valid-required">
			                                    <div class="form-line">
			                                        <input type="text" class="form-control mayusculas" name="apellido_paterno_rep" id="apellido_paterno_rep" placeholder="P. EJ. BELLO" onkeypress='return sololetras(event)' required>
			                                    </div>
			                                </div>
			                            </div>
			                            <div class="col-sm-4">
		                            		<label for="apellido_materno_rep">Apellido Materno*</label>
			                                <div class="form-group valid-required">
			                                    <div class="form-line">
			                                        <input type="text" class="form-control mayusculas" name="apellido_materno_rep" id="apellido_materno_rep" placeholder="P. EJ. MENA" onkeypress='return sololetras(event)' required>
			                                    </div>
			                                </div>
		                          		</div>
		                          		<div class="col-sm-4">
		                            		<label for="rfc_representante">RFC*</label>
			                                <div class="form-group valid-required">
			                                    <div class="form-line">
			                                        <input type="text" class="form-control mayusculas file-legal" name="rfc_representante" onkeypress='return solosnumerosyletras(event)' id="rfc_representante" placeholder="P. EJ. BML920313XXX" required>
			                                    </div>
			                                </div>
		                            	</div>
			                            <div class="col-sm-4">
		                            		  <div class="form-group valid-required">
	                                    <div class="form-line">
	                                <label for="telf_rep_legal">Teléfono*</label>
	                                        <input type="text" class="form-control telefono" name="telf_rep_legal" id="telf_rep_legal" placeholder="P. EJ.: +00 (000) 000-00-00" onkeyup="validPhone(this)" required>
	                                        <span class="emailError text-danger"></span>
	                                    </div>
	                                </div>
			                            </div>
			                            <div class="col-sm-4">
			                                <label for="curp_rep_legal_registrar">C.U.R.P.*</label>
			                                <div class="form-group form-float">
			                                    <div class="form-line" id="validCurp">
			                                        <input type="text" class="form-control mayusculas" name="curp_rep_legal" id="curp_rep_legal_registrar" placeholder="P. EJ. BML920313HMLNNSOS" oninput="validarInputCurp(this)"  required>
			                                    </div>
			                                    <span class="curpError text-danger"></span>
			                                </div>
			                            </div>
	                            		<div class="col-sm-4">
	                              		<label for="rfc_img_rep">Copia escaneada del RFC*</label>
			                                <div class="form-group valid-required">
			                                    <div class="form-line">
			                                        <input type="file" class="form-control mayusculas" data-show-upload="false"  placeholder="Copia escaneada del RFC"  id="rfc_img_rep">
			                                    </div>
			                                </div>
	                            		</div>
			                            <div class="col-sm-4">
			                                <label for="correo_rep_legal">Correo Electrónico*</label>
			                                <div class="form-group valid-required">
	                                    	<div class="form-line">
	                                        <input type="email" class="form-control" name="correo_rep_legal" id="correo_rep_legal" placeholder="P. EJ. ejemplo@dominio.com" onchange="validEmail(this)">
												<span class="emailError text-danger"></span>
	                                    	</div>
	                                		</div>
	                            		</div>


									        	</div>
								       	
								       	 		<div class="col-sm-4 col-sm-offset-5">
			                                        <button type="button" onclick="regresar('#cuadro2')" class="btn btn-primary waves-effect">Regresar</button>
			                                        <input type="submit" value="Guardar" class="btn btn-success waves-effect save-cliente">
			                                	</div>
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
		                            <h2>Consultar Representante Legal del Cliente Pagador </h2>
		                        </div>
		                        <div class="body">
		                        	<div class="table-responsive">
		                                       
										     <div class="" id="rep_legal_mostrar">
										     	
										        <fieldset disabled>
										        	<div class="col-sm-4">
					                            		<label for="nombre_respresentante">Nombre(s)*</label>
						                                <div class="form-group">
						                                    <div class="form-line">
						                                        <input type="text" class="form-control mayusculas" name="nombre_respresentante" id="nombre_respresentante_m" placeholder="P. EJ. LUIS RAÚL" >
						                                    </div>
						                                </div>
					                            	</div>
						                            <div class="col-sm-4">
					                            		<label for="apellido_paterno_rep">Apellido Paterno*</label>
						                                <div class="form-group">
						                                    <div class="form-line">
						                                        <input type="text" class="form-control mayusculas" name="apellido_paterno_rep" id="apellido_paterno_rep_m" placeholder="P. EJ. BELLO" >
						                                    </div>
						                                </div>
						                            </div>
						                            <div class="col-sm-4">
					                            		<label for="apellido_materno_rep">Apellido Materno*</label>
						                                <div class="form-group">
						                                    <div class="form-line">
						                                        <input type="text" class="form-control mayusculas" name="apellido_materno_rep" id="apellido_materno_rep_m" placeholder="P. EJ. MENA" >
						                                    </div>
						                                </div>
					                          		</div>
					                          		<div class="col-sm-4">
					                            		<label for="rfc_representante">RFC*</label>
						                                <div class="form-group">
						                                    <div class="form-line">
						                                        <input type="text" class="form-control mayusculas" name="rfc_representante" id="rfc_representante_m" placeholder="P. EJ. BML920313XXX" >
						                                    </div>
						                                </div>
					                            	</div>
						                             <div class="col-sm-4">
						                                <label for="curp_rep_legal_registrar">C.U.R.P.*</label>
						                                <div class="form-group form-float">
						                                    <div class="form-line" id="validCurp">
						                                        <input type="text" class="form-control mayusculas" name="curp_rep_legal" id="curp_rep_legal_mostrar" placeholder="P. EJ. BML920313HMLNNSOS" oninput="validarInputCurp(this)" >
						                                    </div>
						                                    <span class="curpError text-danger"></span>
						                                </div>
						                            </div>
						                            <div class="col-sm-4">
						                                <label for="correo_rep_legal">Correo Electrónico*</label>
						                                <div class="form-group">
				                                    	<div class="form-line">
				                                        <input type="email" class="form-control" name="correo_rep_legal" id="correo_rep_legal_m" placeholder="P. EJ. ejemplo@dominio.com" onchange="validEmail(this)">
	                                        				<span class="emailError text-danger"></span>
				                                    	</div>
				                                		</div>
				                            		</div>
				                            		<div class="col-sm-4">
				                                <label for="telf_rep_legal">Teléfono*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control telefono" name="telf_rep_legal" id="telf_rep_legal_m" placeholder="P. EJ.: +00 (000) 000-00-00" onkeyup="validPhone(this)">
	                                        			<span class="emailError text-danger"></span>
				                                    </div>
				                                </div>
				                            </div>
										        </fieldset>
				                            <div class="col-sm-8">
				                               <label for="rfc_img_rep">Copia escaneada del RFC*</label>
					                               <div class="form-group valid-required">
			                                   			 <div class="form-line input-group">
					                                        <input type="file" class="form-control mayusculas"  name="rfc_img_rep_c" id="rfc_img_rep_c" readonly="true">
			                                   			 </div>
					                                </div> 
			                            	</div>



									        	</div>
										        	
                            			<div class="col-sm-2 col-sm-offset-5">
	                                        <button type="button" onclick="regresar('#cuadro3')" class="btn btn-primary waves-effect">Regresar</button>
		                                </div>
			                        </div>
										        </div>	                            	
                            			<br>
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
		                            <h2>Editar Representante Legal del Cliente Pagador</h2>
		                        </div>
		                        <div class="body">
		                        	<div class="table-responsive">
			                            <form name="form_repLegal_editar" id="form_repLegal_editar" method="post">
			                            	 <input type="hidden" name="id_repLegal" id="id_repLegal_clientePagador_actualizar">
			                            	 <input type="hidden" name="id_datos_personales" id="id_datos_personales">
			                            	 <input type="hidden" class="form-control mayusculas" name="id_cliente" id="id_cliente_actualizar" value="<?=$id_cliente;?>">
			                            	  <input type="hidden" name="imagen_editar" id="imagen_editar">
										    <div class="" id="rep_legalEditar">
										        	<div class="col-sm-4">
					                            		<label for="nombre_respresentante">Nombre(s)*</label>
						                                <div class="form-group">
						                                    <div class="form-line">
						                                        <input type="text" class="form-control mayusculas" name="nombre_representante" id="nombre_respresentante_e" placeholder="P. EJ. LUIS RAÚL" required>
						                                    </div>
						                                </div>
					                            	</div>
						                            <div class="col-sm-4">
					                            		<label for="apellido_paterno_rep">Apellido Paterno*</label>
						                                <div class="form-group">
						                                    <div class="form-line">
						                                        <input type="text" class="form-control mayusculas" name="apellido_paterno_rep" id="apellido_paterno_rep_e" placeholder="P. EJ. BELLO" required>
						                                    </div>
						                                </div>
						                            </div>
						                            <div class="col-sm-4">
					                            		<label for="apellido_materno_rep">Apellido Materno*</label>
						                                <div class="form-group">
						                                    <div class="form-line">
						                                        <input type="text" class="form-control mayusculas" name="apellido_materno_rep" id="apellido_materno_rep_e" placeholder="P. EJ. MENA" required>
						                                    </div>
						                                </div>
					                          		</div>
					                          		<div class="col-sm-4">
					                            		<label for="rfc_representante">RFC*</label>
						                                <div class="form-group">
						                                    <div class="form-line">
						                                        <input type="text" class="form-control mayusculas" name="rfc_representante" onkeypress='return solosnumerosyletras(event)' id="rfc_representante_e" placeholder="P. EJ. BML920313XXX" required oninput="validarInputRfc(this)">
						                                    </div>
						                                </div>
					                            	</div>
						                          
						                            <div class="col-sm-4">
						                                <label for="curp_rep_legal_editar">C.U.R.P.*</label>
						                                <div class="form-group form-float">
						                                    <div class="form-line" id="validCurp">
						                                        <input type="text" class="form-control mayusculas" name="curp_rep_legal" id="curp_rep_legal_e" placeholder="P. EJ. BML920313HMLNNSOS" onkeypress='return solosnumerosyletras(event)' oninput="validarInputCurp(this)" required>
						                                    </div>
						                                    <span class="curpError text-danger"></span>
						                                </div>
						                            </div>
						                            <div class="col-sm-4">
						                                <label for="correo_rep_legal">Correo Electrónico*</label>
						                                <div class="form-group">
				                                    	<div class="form-line">
				                                        <input type="email" class="form-control" name="correo_rep_legal" id="correo_rep_legal_e" placeholder="P. EJ. ejemplo@dominio.com" required onchange="validEmail(this)">
	                                        				<span class="emailError text-danger"></span>
				                                    	</div>
				                                		</div>
				                            		</div>
				                            		  <div class="col-sm-4">
					                            		<label for="rfc_img_rep_e">Copia escaneada del RFC*</label>
						                                <div class="form-group">
						                                    <div class="form-line">
						                                        <input type="file" class="form-control mayusculas" placeholder="Copia escaneada del RFC"  id="rfc_img_rep_e">
						                                    </div>
						                                </div>
						                            </div>
				                            		<div class="col-sm-4">
				                                <label for="telf_rep_legal">Teléfono*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control telefono" name="telf_rep_legal" id="telf_rep_legal_e" placeholder="P. EJ.: +00 (000) 000-00-00" required onkeyup="validPhone(this)">
	                                        			<span class="emailError text-danger"></span>
				                                    </div>
				                                </div>
				                            </div>


									        	</div>
                                			<br>
                                			<div class="col-sm-4 col-sm-offset-5">
		                                        <button type="button" onclick="regresar('#cuadro4')" class="btn btn-primary waves-effect">Regresar</button>
		                                        <input type="submit" value="Guardar" class="btn btn-success waves-effect save-cliente">
			                                </div>
			                            </form>
			                        </div>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        <!-- Cierre del cuadro de editar  -->
			</div>
		
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
    <script src="<?=base_url();?>assets/template/plugins/momentjs/moment.js"></script>
    <script src="<?=base_url();?>assets/template/plugins/bootstrap-datetimepicker-master/js/bootstrap-datetimepicker.min.js"></script>
    <script src="<?=base_url();?>assets/template/plugins/bootstrap-fileinput/js/plugins/piexif.min.js" type="text/javascript"></script>
    <script src="<?=base_url();?>assets/template/plugins/bootstrap-fileinput/js/plugins/purify.min.js" type="text/javascript"></script>
    <script src="<?=base_url();?>assets/template/plugins/bootstrap-fileinput/js/plugins/sortable.min.js" type="text/javascript"></script>
    <script src="<?=base_url();?>assets/cpanel/Productos/js/numeral/min/numeral.min.js"></script>
	<script src="<?=base_url();?>assets/template/plugins/bootstrap-fileinput/js/fileinput.js" type="text/javascript"></script>
    <script type="text/javascript" src="<?=base_url();?>assets/template/plugins/bootstrap-fileinput/themes/fa/theme.min.js"></script>
    <script src="<?=base_url();?>assets/template/plugins/bootstrap-fileinput/js/locales/es.js" type="text/javascript"></script>
    <script src="<?=base_url();?>assets/cpanel/ClientePagador/js/rep_legal.js"></script>
    <script>
    	var editable = "<?php echo ($editable) ? 1 : 0;?>"
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
