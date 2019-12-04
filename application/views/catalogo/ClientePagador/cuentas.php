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
		                                Gestión de Cuentas del Cliente Pagador 
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
		                                            <th>CLABE</th>
		                                            <th>Tipo de Cuenta</th>
		                                            <th>Banco</th>
		                                            <th>Fecha de Registro</th>
		                                            <th>Registrado Por</th>
		                                        </tr>
		                                    </thead>
		                                    <tbody></tbody>
		                                </table>
		                                <div class="col-md-2 eliminar ocultar">
		                                	<button class="btn btn-danger waves-effect" onclick="eliminarMultiple('ClientePagador/eliminar_multiple_cta')">Eliminar seleccionados</button>
		                                </div>
		                                <div class="col-md-2 actualizar ocultar">
		                                	<button class="btn btn-warning waves-effect" onclick="statusMultiple('ClientePagador/status_multiple_cta', 1, 'activar')">Activar seleccionados</button>
		                                </div>
		                                <div class="col-md-2 actualizar ocultar">
		                                	<button class="btn btn-warning waves-effect" onclick="statusMultiple('ClientePagador/status_multiple_cta', 2, 'desactivar')">Desactivar seleccionados</button>
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
		                            <h2>Registro del las Cuentas del Cliente Pagador </h2>
		                        </div>
		                        <div class="body">
		                        	<div class="table-responsive">
			                            <form name="form_cuenta_clientePa_registrar" id="form_cuenta_clientePa_registrar" method="post">
			                            	<input type="hidden" class="form-control mayusculas" name="id_cliente" id="id_cliente_registrar" value="<?=$id_cliente;?>">
 											<div class="" id="cuenta">
									        	<div class="col-sm-4">
				                                	<label for="clabe_registrar">CLABE*</label>
					                                <div class="form-group">
					                                    <div class="form-line">
					                                        <input type="text" class="form-control mayusculas" name="clabe" id="clabe_registrar" onkeypress='return solonumeros(event)' id="clabe_registrar" placeholder="P. EJ. 00211501600326941" maxlength="14" minlength="14">

					                                    </div>
				                                	</div>
				                           		</div>
					                            <div class="col-sm-4">
					                                <label for="numero_cuenta_registrar">Número de Cuenta</label>
					                                <div class="form-group">
					                                    <div class="form-line">
					                                        <input type="text" class="form-control" id="numero_cuenta_registrar"  onkeypress='return solonumeros(event)' name="numero_cuenta" placeholder="P. EJ. 016001326941" maxlength="15">
					                                    </div>
					                                </div>
					                            </div>
					                            <div class="col-sm-4" style="padding-bottom: 50px;">
					                            	<label for="tipo_cuenta_reg">Tipo de Cuenta*</label>
			                                         <select name="tipo_cuenta" id="tipo_cuenta_registrar" class="form-control">
		                                    					<option value="" selected>Seleccione</option>
		                                    						<?php foreach ($tipoCuentas as $tipoCuenta): ?>
		                                    					<option value="<?=$tipoCuenta->id_lista_valor;?>"><?=$tipoCuenta->nombre_lista_valor;?></option>
		                                    						<?php endforeach ?>
		                                    				</select>
		                                    	</div>
					                            <div class="col-sm-4">
					                                <label for="banco_registrar">Banco*</label>
			                                         <select name="banco" id="banco_cliente_registrar" class="form-control">
		                                    					<option value="" selected>Seleccione</option>
		                                    						<?php foreach ($bancos as $banco): ?>
		                                    					<option value="<?=$banco->id_banco;?>"><?=$banco->nombre_banco;?></option>
		                                    						<?php endforeach ?>
		                                    				</select>
					                            </div>
					                            <div class="col-sm-4">
					                                <label for="swift_registrar">Swift</label>
					                                <div class="form-group">
					                                    <div class="form-line">
					                                        <input type="text" class="form-control" id="swift_registrar"  onkeypress='return solosnumerosyletras(event)' name="swift" placeholder="P. EJ. INGBMXMN" maxlength="15">
					                                    </div>
					                                </div>
					                            </div>
					                            <div class="col-sm-4" style="padding-bottom: 50px;">
					                                <label for="codigo_plaza_registrar">Código Plaza</label>
			                                        <select name="codigo_plaza" id="codigo_plaza_registrar" class="form-control">
		                                    					<option value="" selected>Seleccione</option>
		                                    						<?php foreach ($plazas as $plaza): ?>
		                                    					<option value="<?=$plaza->id_plaza;?>"><?=$plaza->nombre_plaza;?></option>
		                                    						<?php endforeach ?>
		                                    				</select>
					                            </div>
					                            </div>
					                            <div class="col-sm-4">
					                                <label for="sucursal_registrar">Sucursal</label>
					                                <div class="form-group">
					                                    <div class="form-line">
					                                        <input type="text" class="form-control  mayusculas" id="sucursal_registrar" name="sucursal" placeholder="P. EJ SICOMOROS CHIH" maxlength="30">
					                                    </div>
					                                </div>
					                            </div>

								       	 		<div class="col-sm-4 col-sm-offset-5">
			                                        <button type="button" onclick="regresar('#cuadro2')" class="btn btn-primary waves-effect">Regresar</button>
			                                        <input type="submit" value="Guardar" class="btn btn-success waves-effect">
			                                	</div>
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
		                            <h2>Consultar Cuenta del Cliente Pagador </h2>
		                        </div>
		                        <div class="body">
		                        	<div class="table-responsive">
		                                       
										     <div class="" id="cuentamostrar">
										        <fieldset disabled>
										        	<div class="col-sm-4">
				                                	<label for="clabe_mostrar">CLABE</label>
					                                <div class="form-group">
					                                    <div class="form-line">
					                                        <input type="text" class="form-control mayusculas" name="clabe" id="clabe_mostrar" placeholder="P. EJ. 00211501600326941" maxlength="14" minlength="14">
					                                    </div>
				                                	</div>
				                           		</div>
					                            <div class="col-sm-4">
					                                <label for="numero_cuenta_mostrar">Número de Cuenta</label>
					                                <div class="form-group">
					                                    <div class="form-line">
					                                        <input type="text" class="form-control" id="numero_cuenta_mostrar" name="numero_cuenta" placeholder="P. EJ. 016001326941">
					                                    </div>
					                                </div>
					                            </div>
					                            <div class="col-sm-4">
					                                <label for="tipo_cuenta_reg">Tipo de Cuenta</label>
					                                <div class="form-group">
					                                    <div class="form-line">
					                                        <input type="text" class="form-control" id="tipo_cuenta_mostrar" name="tipo_cuenta">
					                                    </div>
					                                </div>
					                            </div>
					                            <div class="col-sm-4">
					                                <label for="banco_mostrar">Banco</label>
			                                        <div class="form-group">
					                                    <div class="form-line">
					                                        <input type="text" class="form-control" id="banco_mostrar" name="banco_mostrar">
					                                    </div>
					                                </div>
					                            </div>
					                            <div class="col-sm-4">
					                                <label for="swift_mostrar">Swift</label>
					                                <div class="form-group">
					                                    <div class="form-line">
					                                        <input type="text" class="form-control" id="swift_mostrar" name="swift" placeholder="P. EJ. INGBMXMN">
					                                    </div>
					                                </div>
					                            </div>
					                            <div class="col-sm-4">
					                                <label for="codigo_plaza_mostrar">Código Plaza</label>
					                                <div class="form-group">
					                                    <div class="form-line">
					                                        <input type="text" class="form-control" id="codigo_plaza_mostrar" name="codigo_plaza_mostrar">
					                                    </div>
					                                </div>
			                                        
					                            </div>
					                            <div class="col-sm-4">
					                                <label for="sucursal_mostrar">Sucursal</label>
					                                <div class="form-group">
					                                    <div class="form-line">
					                                        <input type="text" class="form-control" id="sucursal_mostrar" name="sucursal">
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
		                            <h2>Editar Cuenta del Cliente Pagador</h2>
		                        </div>
		                        <div class="body">
		                        	<div class="table-responsive">
			                            <form name="form_cuenta_clientePa_editar" id="form_cuenta_clientePa_editar" method="post">
										    <div class="" id="cuentaEditar">
			                            	 <input type="hidden" name="id_cuenta" id="id_cuenta_actualizar">
			                            	 <input type="hidden" name="id_cliente" id="id_cliente_actualizar" value="<?=$id_cliente;?>">
										        
										        	<div class="col-sm-4">
				                                	<label for="clabe_editar">CLABE*</label>
					                                <div class="form-group">
					                                    <div class="form-line">
					                                        <input type="text" class="form-control mayusculas" name="clabe" id="clabe_editar" placeholder="P. EJ. 00211501600326941" readonly maxlength="14" minlength="14">
					                                    </div>
				                                	</div>
				                           		</div>
					                            <div class="col-sm-4">
					                                <label for="numero_cuenta_editar">Número de Cuenta</label>
					                                <div class="form-group">
					                                    <div class="form-line">
					                                        <input type="text" class="form-control" id="numero_cuenta_editar"  onkeypress='return solosnumeros(event)' name="numero_cuenta" placeholder="P. EJ. 016001326941">
					                                    </div>
					                                </div>
					                            </div>
					                            <div class="col-sm-4" style="padding-bottom: 10px;">
					                            	<label for="tipo_cuenta_editar">Tipo de Cuenta*</label>
			                                        <select id="tipo_cuenta_editar" required class="form-control form-group" name="tipo_cuenta">
			                                        	<option value="" selected>Seleccione</option>
		                                    						<?php foreach ($tipoCuentas as $tipoCuenta): ?>
		                                    			<option value="<?=$tipoCuenta->id_lista_valor;?>"><?=$tipoCuenta->nombre_lista_valor;?></option>
		                                    						<?php endforeach ?>
			                                        </select>
					                            </div>
					                            <div class="col-sm-4">
					                                <label for="banco_editar">Banco*</label>
			                                        <select id="banco_editar" required class="form-control form-group" name="banco">
			                                     	<option value="" selected>Seleccione</option>
		                                    						<?php foreach ($bancos as $banco): ?>
		                                    					<option value="<?=$banco->id_banco;?>"><?=$banco->nombre_banco;?></option>
		                                    						<?php endforeach ?>
			                                        	
			                                        </select>
					                            </div>
					                            <div class="col-sm-4">
					                                <label for="swift_editar">Swift</label>
					                                <div class="form-group">
					                                    <div class="form-line">
					                                        <input type="text" class="form-control" id="swift_editar"  onkeypress='return solosnumerosyletras(event)' name="swift" placeholder="P. EJ. INGBMXMN">
					                                    </div>
					                                </div>
					                            </div>
					                            <div class="col-sm-4" style="padding-bottom: 10px;">
					                                <label for="codigo_plaza_editar">Código Plaza</label>
			                                        <select id="codigo_plaza_editar" class="form-control form-group" name="codigo_plaza">
			                                        		<option value="" selected>Seleccione</option>
		                                    						<?php foreach ($plazas as $plaza): ?>
		                                    					<option value="<?=$plaza->id_plaza;?>"><?=$plaza->nombre_plaza;?></option>
		                                    						<?php endforeach ?>
			                                        	
			                                        </select>
					                            </div>
					                            <div class="col-sm-4">
					                                <label for="sucursal_editar">Sucursal</label>
					                                <div class="form-group">
					                                    <div class="form-line">
					                                        <input type="text" class="form-control" id="sucursal_editar" name="sucursal" placeholder="P. EJ SICOMOROS CHIH">
					                                    </div>
					                                </div>
					                            </div>
										        	
										        
										        </div>
                                			
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
    <script src="<?=base_url();?>assets/cpanel/ClientePagador/js/cuentas.js"></script>
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
