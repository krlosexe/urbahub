<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
<!DOCTYPE html>
<html>
	<link href="<?=base_url();?>assets/template/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
	<link href="<?=base_url();?>assets/template/plugins/bootstrap-datetimepicker-master/css/bootstrap-datetimepicker.css" rel="stylesheet" />
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
		                                Gestión de Contactos del Cliente Pagador 
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
		                                            <th>Nombre Completo</th>
		                                            <th>Correo Electrónico</th>
		                                            <th>Teléfono</th>
		                                            <th>Fecha de Registro</th>
		                                            <th>Registrado Por</th>
		                                        </tr>
		                                    </thead>
		                                    <tbody></tbody>
		                                </table>
		                                <div class="col-md-2 eliminar ocultar">
		                                	<button class="btn btn-danger waves-effect" onclick="eliminarMultiple('ClientePagador/eliminar_multiple_contacto')">Eliminar seleccionados</button>
		                                </div>
		                                <div class="col-md-2 actualizar ocultar">
		                                	<button class="btn btn-warning waves-effect" onclick="statusMultiple('ClientePagador/status_multiple_contacto', 1, 'activar')">Activar seleccionados</button>
		                                </div>
		                                <div class="col-md-2 actualizar ocultar">
		                                	<button class="btn btn-warning waves-effect" onclick="statusMultiple('ClientePagador/status_multiple_contacto', 2, 'desactivar')">Desactivar seleccionados</button>
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
		                            <h2>Registro de los Contactos del Cliente </h2>
		                        </div>
		                        <div class="body">
		                        	<div class="table-responsive">
			                            <form name="form_contacto_registrar" id="form_contacto_registrar" method="post">
			                            	<input type="hidden" class="form-control mayusculas" name="id_cliente" id="id_cliente_registrar" value="<?=$id_cliente;?>">
 											<div  id="contacto">
										       	<div class="col-sm-4">
					                           		<label for="nombre_contacto">Nombre Contacto*</label>
						                            <div class="form-group">
						                	            <div class="form-line">
						                                   <input type="text" class="form-control mayusculas" name="nombre_contacto" onkeypress='return sololetras(event)' id="nombre_contacto" placeholder="P. EJ.LUIS RAÚL" required>
					                                    </div>
					                                </div>
					                             </div>
					                             <div class="col-sm-4">
					                                <label for="tlf_ppalContacto_r">Teléfono Principal*</label>
						                                <div class="form-group">
						                                    <div class="form-line">
						                                        <input type="text" class="form-control telefono" name="telefono_principal_contacto" id="tlf_ppalContacto_r" placeholder="P. EJ.: +00 (000) 000-00-00" required onkeyup="validPhone(this)">
	                                        					<span class="emailError text-danger"></span>
					                                    	</div>
				                               		 	</div>
			                            		</div>
			                            		<div class="col-sm-4">
					                               <label for="tfl_movilContacto_r">Teléfono Celular</label>
					                                <div class="form-group">
					                                    <div class="form-line">
					                                        <input type="text" class="form-control telefono" name="telefono_movil_contacto" id="tfl_movilContacto_r" placeholder="P. EJ.: +00 (000) 000-00-00" onkeyup="validPhone(this)">
	                                        				<span class="emailError text-danger"></span>
					                                    </div>
			                               			 </div>
			                            		</div>
			                            		<div class="col-sm-4">
						                                <label for="tlf_casa_r">Teléfono Casa</label>
						                                <div class="form-group">
						                                    <div class="form-line">
						                                        <input type="text" class="form-control telefono" name="telefono_casa_contacto" id="tlf_casa_r" placeholder="P. EJ.: +00 (000) 000-00-00">
						                                    </div>
				                               		 	</div>
				                            	</div>
			                            		<div class="col-sm-4">
					                                <label for="tlf_trabajo_r">Teléfono Trabajo</label>
						                                <div class="form-group">
						                                    <div class="form-line">
						                                        <input type="text" class="form-control telefono" name="telefono_trabajo_contacto" id="tlf_trabajo_r" placeholder="P. EJ.: +00 (000) 000-00-00" onkeyup="validPhone(this)">
	                                        					<span class="emailError text-danger"></span>
						                                    </div>
				                               		 </div>
			                            		</div>
			                            		<div class="col-sm-4">
					                                <label for="tlf_fax_r">Teléfono Fax</label>
					                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control telefono" name="telefono_fax_contacto" id="tlf_fax_r" placeholder="P. EJ.: +00 (000) 000-00-00" onkeyup="validPhone(this)">
	                                        			<span class="emailError text-danger"></span>
				                                    </div>
			                               		 </div>
			                            		</div>
			                            		<div class="col-sm-4">
					                                <label for="correo_contacto_r">Correo Electrónico*</label>
					                                <div class="form-group valid-required">
				                                    	<div class="form-line">
				                                        	<input type="email" class="form-control" name="correo_contacto" id="correo_contacto_r" placeholder="P. EJ. ejemplo@dominio.com" required onchange="validEmail(this)">

	                                        				<span class="emailError text-danger"></span>
				                                    	</div>
			                                		</div>
			                            		</div>
			                            		<div class="col-sm-4">
					                                <label for="coreo_contactp_opc_r">Correo Electrónico Opcional</label>
					                                <div class="form-group">
				                                    	<div class="form-line">
				                                        	<input type="email" class="form-control" name="coreo_contactp_opc_r" id="coreo_contactp_opc_r" placeholder="P. EJ. ejemplo@dominio.com" onchange="validEmail(this)">

	                                        				<span class="emailError text-danger"></span>
				                                    	</div>
			                                		</div>
			                            		</div>
							        		</div>

								       	 		<div class="col-sm-4 col-sm-offset-5">
			                                        <button type="button" onclick="regresar('#cuadro2')" class="btn btn-primary waves-effect">Regresar</button>
			                                        <input type="submit" value="Guardar" class="btn btn-success waves-effect save-cliente">
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
		                            <h2>Consultar Contactos del Cliente Pagador </h2>
		                        </div>
		                        <div class="body">
		                        	<div class="table-responsive">
		                                       
										     <div class="" id="contactoMostrar">
										        <fieldset disabled>
										        		<div class="col-sm-4">
				                           		<label for="nombre_contacto_m">Nombre Contacto*</label>
					                            <div class="form-group">
					                	            <div class="form-line">
					                                   <input type="text" class="form-control mayusculas" name="nombre_contacto_m" id="nombre_contacto_m" placeholder="P. EJ.LUIS RAÚL" required>
				                                    </div>
				                                </div>
				                             </div>
				                             <div class="col-sm-4">
				                                <label for="tlf_ppalContacto_m">Teléfono Principal*</label>
					                                <div class="form-group">
					                                    <div class="form-line">
					                                        <input type="text" class="form-control telefono" name="tlf_ppalContacto_m" id="tlf_ppalContacto_m" placeholder="P. EJ.: +00 (000) 000-00-00" required onkeyup="validPhone(this)">
	                                        				<span class="emailError text-danger"></span>
				                                    	</div>
			                               		 	</div>
		                            		</div>
				                            		<div class="col-sm-4">
						                                <label for="tfl_movilContacto_m">Teléfono Celular</label>
						                                <div class="form-group">
					                                    <div class="form-line">
					                                        <input type="text" class="form-control telefono" name="telefono_movil_contacto" id="tfl_movilContacto_m" placeholder="P. EJ.: +00 (000) 000-00-00" onkeyup="validPhone(this)">
	                                        				<span class="emailError text-danger"></span>
					                                    </div>
				                               		 </div>
				                            		</div>
				                            		<div class="col-sm-4">
						                                <label for="tlf_casa_m">Teléfono Casa</label>
						                                <div class="form-group">
					                                    <div class="form-line">
					                                        <input type="text" class="form-control telefono" name="telefono_casa_contacto" id="tlf_casa_m" placeholder="P. EJ.: +00 (000) 000-00-00" onkeyup="validPhone(this)">
	                                        				<span class="emailError text-danger"></span>
					                                    </div>
				                               		 </div>
				                            		</div>
				                            		<div class="col-sm-4">
						                                <label for="tlf_trabajo_m">Teléfono Trabajo</label>
						                                <div class="form-group">
					                                    <div class="form-line">
					                                        <input type="text" class="form-control telefono" name="telefono_trabajo_contacto" id="tlf_trabajo_m" placeholder="P. EJ.: +00 (000) 000-00-00" onkeyup="validPhone(this)">
	                                        				<span class="emailError text-danger"></span>
					                                    </div>
				                               		 </div>
				                            		</div>
				                            		<div class="col-sm-4">
						                                <label for="tlf_fax_m">Teléfono Fax</label>
						                                <div class="form-group">
					                                    <div class="form-line">
					                                        <input type="text" class="form-control telefono" name="telefono_fax_contacto" id="tlf_fax_m" placeholder="P. EJ.: +00 (000) 000-00-00" onkeyup="validPhone(this)">
	                                        				<span class="emailError text-danger"></span>
					                                    </div>
				                               		 </div>
				                            		</div>
				                            		<div class="col-sm-4">
						                                <label for="correo_contacto_m">Correo Electrónico*</label>
						                                <div class="form-group">
					                                    	<div class="form-line">
					                                        	<input type="email" class="form-control" name="correo_contacto" id="correo_contacto_m" placeholder="P. EJ. ejemplo@dominio.com" required  onchange="validEmail(this)">

	                                        					<span class="emailError text-danger"></span>
					                                    	</div>
				                                		</div>
				                            		</div>
				                            		<div class="col-sm-4">
						                                <label for="coreo_contactp_opc_m">Correo Electrónico Opcional</label>
						                                <div class="form-group">
					                                    	<div class="form-line">
					                                        	<input type="email" class="form-control" name="coreo_contactp_opc_m" id="coreo_contactp_opc_m" placeholder="P. EJ. ejemplo@dominio.com" onchange="validEmail(this)">

	                                        					<span class="emailError text-danger"></span>
					                                    	</div>
				                                		</div>
				                            		</div>
										        	
										        </fieldset>
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
		        <!-- Cierre del cuadro de consultar -->

		        <!-- Comienzo del cuadro de editar -->
					<div class="row clearfix ocultar" id="cuadro4">
		                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                    <div class="card">
		                        <div class="header">
		                            <h2>Editar Contacto del Cliente Pagador</h2>
		                        </div>
		                        <div class="body">
		                        	<div class="table-responsive">
			                            <form name="form_contacto_editar" id="form_contacto_editar" method="post">
										    <div class="" id="contactoEditar">
			                            		<input type="hidden" name="id_contacto" id="id_contacto_actualizar">
			                            		<input type="hidden" name="id_contacto_cliente" id="id_contacto_cliente_e">
			                            		<input type="hidden" name="id_datos_personales" id="id_datos_personales_e">
										        <div class="col-sm-4">
				                           		<label for="nombre_contacto_e">Nombre Contacto*</label>
					                            <div class="form-group">
					                	            <div class="form-line">
					                                   <input type="text" class="form-control mayusculas" name="nombre_contacto" onkeypress='return sololetras(event)' id="nombre_contacto_e" placeholder="P. EJ.LUIS RAÚL" required>
				                                    </div>
				                                </div>
				                             </div>
				                             <div class="col-sm-4">
				                                <label for="tlf_ppalContacto_e">Teléfono Principal*</label>
					                                <div class="form-group">
					                                    <div class="form-line">
					                                        <input type="text" class="form-control telefono" name="telefono_principal_contacto" id="tlf_ppalContacto_e" placeholder="P. EJ.: +00 (000) 000-00-00" required onkeyup="validPhone(this)">
	                                        				<span class="emailError text-danger"></span>
				                                    	</div>
			                               		 	</div>
		                            		</div>
				                            		<div class="col-sm-4">
						                                <label for="tfl_movilContacto_e">Teléfono Celular</label>
						                                <div class="form-group">
					                                    <div class="form-line">
					                                        <input type="text" class="form-control telefono" name="telefono_movil_contacto" id="tfl_movilContacto_e" placeholder="P. EJ.: +00 (000) 000-00-00" onkeyup="validPhone(this)">
	                                        				<span class="emailError text-danger"></span>
					                                    </div>
				                               		 </div>
				                            		</div>
				                            		<div class="col-sm-4">
						                                <label for="tlf_casa_e">Teléfono Casa</label>
						                                <div class="form-group">
					                                    <div class="form-line">
					                                        <input type="text" class="form-control telefono" name="telefono_casa_contacto" id="tlf_casa_e" placeholder="P. EJ.: +00 (000) 000-00-00" onkeyup="validPhone(this)">
	                                        				<span class="emailError text-danger"></span>
					                                    </div>
				                               		 </div>
				                            		</div>
				                            		<div class="col-sm-4">
						                                <label for="tlf_trabajo_e">Teléfono Trabajo</label>
						                                <div class="form-group">
					                                    <div class="form-line">
					                                        <input type="text" class="form-control telefono" name="telefono_trabajo_contacto" id="tlf_trabajo_e" placeholder="P. EJ.: +00 (000) 000-00-00" onkeyup="validPhone(this)">
	                                        				<span class="emailError text-danger"></span>
					                                    </div>
				                               		 </div>
				                            		</div>
				                            		<div class="col-sm-4">
						                                <label for="tlf_fax_e">Teléfono Fax</label>
						                                <div class="form-group">
					                                    <div class="form-line">
					                                        <input type="text" class="form-control telefono" name="telefono_fax_contacto" id="tlf_fax_e" placeholder="P. EJ.: +00 (000) 000-00-00" onkeyup="validPhone(this)">
	                                        				<span class="emailError text-danger"></span>
					                                    </div>
				                               		 </div>
				                            		</div>
				                            		<!--<div class="col-sm-4">
						                                <label for="correo_contacto_e">Correo Electrónico*</label>
						                                <div class="form-group valid-required">
					                                    	<div class="form-line">
					                                        	<input type="email" class="form-control" name="correo_contacto" id="correo_contacto_e" placeholder="P. EJ. ejemplo@dominio.com" required>
					                                    	</div>
				                                		</div>
				                            		</div>-->
				                            		<div class="col-sm-4">
						                                <label for="correo_contacto_e">Correo Electrónico*</label>
						                                <div class="form-group valid-required">
					                                    	<div class="form-line">
					                                        	<input type="email" class="form-control guardado" name="correo_contacto_e" id="correo_contacto_e" placeholder="P. EJ. ejemplo@dominio.com" required maxlength="30" onchange="validEmail(this)">

	                                        					<span class="emailError text-danger"></span>
					                                    	</div>
				                                		</div>
				                            		</div>
				                            		<div class="col-sm-4">
						                                <label for="coreo_contactp_opc_e">Correo Electrónico Opcional</label>
						                                <div class="form-group">
					                                    	<div class="form-line">
					                                        	<input type="email" class="form-control" name="coreo_contactp_opc_e" id="coreo_contactp_opc_e" placeholder="P. EJ. ejemplo@dominio.com" onchange="validEmail(this)">

	                                        					<span class="emailError text-danger"></span>
					                                    	</div>
				                                		</div>
				                            		</div>
										        	
										        	
										        
										    </div>
                                			
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
    <script src="<?=base_url();?>assets/cpanel/Productos/js/numeral/min/numeral.min.js"></script>
    <script src="<?=base_url();?>assets/template/plugins/momentjs/moment.js"></script>
    <script src="<?=base_url();?>assets/template/plugins/bootstrap-datetimepicker-master/js/bootstrap-datetimepicker.min.js"></script>
    <script src="<?=base_url();?>assets/cpanel/ClientePagador/js/contacto.js"></script>
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
