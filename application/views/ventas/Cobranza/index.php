<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>

	<link href="<?=base_url();?>assets/template/plugins/bootstrap-fileinput/css/fileinput.css" media="all" rel="stylesheet" type="text/css" />


	<link href="<?=base_url();?>assets/template/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">

	<link href="<?=base_url();?>assets/template/plugins/bootstrap-datetimepicker-master/css/bootstrap-datetimepicker.css" rel="stylesheet" />


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
						<!--
						<div class="col-sm-2" style="padding-top: 5px;">
                			<span class="form-group" >Filtrar: <b>&nbsp;&nbsp;Proyecto-Director</b></span>
                		</div>
                        <div class="col-sm-4" style="margin-bottom: 10px;">
                            <select class="form-control" onchange="filtrar(this.value)" id="filter">
                            	<option value="" selected>Seleccione</option>
                        		<?php foreach ($proyectos as $data): ?>
                        			<option value="<?=$data->id_proyecto;?>"><?=$data->nombre." - ".$data->nombres." - ".$data->paterno;?></option>
                        		<?php endforeach ?>
                        	</select>
                        </div>
                    	-->
		                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                    <div class="card">
		                        <div class="header">
		                            <h2>
		                                Gestión de <?php echo $breadcrumbs[0]["nombre_lista_vista"]; ?>
		                            </h2>
		                             <!--<ul class="header-dropdown m-r--5">
			                            <button class="btn btn-primary waves-effect registrar ocultar" onclick="registrarCobranza()"><i class='fa fa-plus-circle' style="color: white; font-size: 18px;"></i> | Nuevo</button>
			                        </ul>-->
		                        </div>
		                        <div class="body">
		                            <div class="table-responsive">
		                            	<ul class="header-dropdown m-r--5" style="display: none;">
			                                <button class="btn btn-primary waves-effect" id="btn-detalle" style="margin-left: -40px">Consultar Detalle</button>
			                            </ul>
		                                <table class="table table-bordered table-striped table-hover" id="tabla">
		                                    <thead>
		                                        <tr>
		                                        	<th style="text-align: center; padding: 0px 10px 0px 5px; width: 5%;"><input type="checkbox" id="checkall" class="chk-col-blue"/><label for="checkall"></label></th>
		                                            <th style="min-width: 17%;">Acciones</th>
		                                        	<th>#Cotización</th>
		                                        	<th>Cliente</th>
													<th>Facturar</th>
		                                            <th>Vendedor</th>
		                                            <th>Productos</th>
		                                            <th>Saldo</th>
		                                            <th>Estatus</th>
		                                            <th>Fecha Registro</th>
		                                            <th>Registrado Por</th>
		                                        </tr>
		                                    </thead>
		                                    <tbody></tbody>
		                                </table>
		                                <!--<div class="col-md-2 eliminar ocultar">
		                                	<button class="btn btn-danger waves-effect" onclick="eliminarMultiple__tasa('tasaCambio/eliminar_multiple')">Eliminar seleccionados</button>
		                                </div>-->
		                            </div>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        <!-- Cierre del cuadro de la tabla -->

		        <!-- Comienzo del cuadro de registrar lista de valores -->
					<div class="row clearfix ocultar" id="cuadro2">
		                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                    <div class="card">
		                        <div class="header">
		                            <h2>Administración de <?php echo $breadcrumbs[0]["nombre_lista_vista"]; ?></h2>
		                        </div>
		                        <div class="body">
		                        	<div class="table-responsive">
				                        <form name="form_cobranzas_registrar" id="form_cobranzas_registrar" method="post" enctype="multipart/form-data">
				                        	<div class="row">
												<div class="col-sm-3">
													<input type="hidden" id="id_cotizacion" name="id_cotizacion">
													<input type="hidden" id="id_cobranza" name="id_cobranza">
								        			<img id="imagen_registrar" src="<?php echo base_url();?>assets/cpanel/Usuarios/images/default.png" alt="Tu avatar"/ class="img-responsive ima_error"
													style=" border-radius: 50%;width: 100px;height: 100px;margin: 0 auto; margin-top: 30px;"> 
								        		</div>
								        		<div class="col-sm-9">
								        			<div class="col-md-6">
						                                <label for="tipolval">Cliente</label> <input type="text" class="form-control " name="rfc_cliente_cobranza_registrar" id="rfc_cliente_cobranza_registrar" readonly  required>
						                               
						                            </div>
						                            <div class="col-md-6">
						                               <label for="tipolval">Vendedor</label>
						                               <input type="text" class="form-control " name="vendedor_cobranza_registrar" id="vendedor_cobranza_registrar" readonly  required>
						                            </div>
								        			<div class="col-md-2">
						                                <label for="tipolval">#Cotización</label>
						                                <input type="text" class="form-control " name="cotizacion_cobranza_registrar" id="cotizacion_cobranza_registrar" readonly  required>
						                            </div>
						                            <div class="col-md-5">
						                                <label for="tipolval">Estatus</label>
						                                <input type="text" class="form-control " name="status_cobranza_registrar" id="status_cobranza_registrar" placeholder="" readonly  required>
						                            </div>
						                            <div class="col-md-5">
						                                <label for="tipolval">Fecha</label>
						                                <input type="text" class="form-control " name="fecha_cobranza_registrar" id="fecha_cobranza_registrar" readonly  required>
						                            </div>
								        		</div>
								        		<!-- -->
								        		<div class="col-sm-12">
						                            
						                            <div class="col-md-6">
						                                <label for="tipolval">Monto Total</label>
						                                <input type="text" class="form-control " name="monto_cobranza_registrar" style="text-align: right;" id="monto_cobranza_registrar" placeholder="" readonly  required>
						                            </div>
						                            <div class="col-md-6">
						                                <label for="tipolval">Saldo</label>
						                                <input type="text" class="form-control " name="saldo_cobranza_registrar" id="saldo_cobranza_registrar" style="text-align: right;" readonly  required>
						                            </div>
								        		</div>
								        		<!-- -->
								        		<div class="col-sm-12">
								        			<h3>Detalle</h3>
						                            <hr>
						                            <div class="col-md-4">
						                                <label for="tipolval">Plan</label>
						                                <input type="text" class="form-control " name="plan_cobranza_registrar" id="plan_cobranza_registrar" readonly  required>
						                            </div>
						                            <div class="col-md-4">
						                                <label for="tipolval">Paquete</label>
						                                <input type="text" class="form-control " name="paquete_cobranza_registrar" id="paquete_cobranza_registrar" readonly  required>
						                            </div>
						                            <div class="col-md-4">
						                                <label for="tipolval">Vigencia</label>
						                                <input type="text" class="form-control " name="vigencia_cobranza_registrar" id="vigencia_cobranza_registrar" readonly  required>
						                            </div>
								        		</div>
								        		<div class="col-lg-12">
                                                	<table class="table table-bordered table-striped table-hover" id="tableRegistrarFisica">
					                            		<thead>
					                            			<tr>
					                            				<th>#</th>
					                            				<th>Concepto</th>
					                            				<th>Montos</th>
					                            			</tr>
					                            		</thead>
					                            		<tbody id="tbodyRegistrarCobranza">
					                            			<tr>
					                            				<th>1</th>
						                            			<th>Inscripción</th>
						                            			<th id="monto_inscripcion_cobranza_registrar" style="text-align: right;"></th>
						                            		</tr>
						                            		<tr>
						                            			<th>2</th>
						                            			<th>Mensualidad</th>
						                            			<th id="monto_mensualidad_cobranza_registrar" style="text-align: right;"></th>
						                            		</tr>	
					                            		</tbody>
					                            	</table>
                                                </div>
                                                <!-- -->
                                                <div class="col-sm-12">
								        			<h3>Cobranzas</h3>
						                            <hr>
						                            <div class="row">
							                        	<div class="col-md-4">
															<div class="row">
																<div class="col-md-3">
									                                <label for="tipolval">Recibo</label>
									                                <input type="text" class="form-control" name="recibo" id="recibo" readonly  style="text-align: center;" required>
																</div>
																

																<input type="hidden" id="numero_secuencia" name="numero_secuencia">

									                            <div class="col-md-3">
									                                <label for="tipolval">Mes</label>
									                                <input type="text" class="form-control" name="mes" id="mes"  readonly style="text-align: center;" required>
									                            </div>

									                            <div class="col-md-6">
									                                <label for="tipolval">Monto</label>
									                                <input type="text" class="form-control monto_formato_decimales" readonly name="monto" id="monto"  required style="text-align: right; font-weight: bold;" onkeypress="return valida(event)" required>
									                            </div>
															</div>

															<div class="row">
															    <label class="col-sm-3" >Forma de pago</label>
															    <div class="col-sm-9">
															      <select name="fp" id="fp" class="form-control" >
															      	<option value="">Seleccione</option>
																      	<?php foreach ($forma_pagos as  $forma_pago): ?>
																      		<option value="<?= $forma_pago->id_lista_valor;?>"><?= $forma_pago->nombre_lista_valor;?></option>
																      	<?php endforeach ?>
																      </select>
															      </select>
															    </div>
															</div>


															<div class="row" style="display: none" id="tarjeta">
															    <label class="col-sm-3" >Numero de Tarjeta</label>
															    <div class="col-sm-9">
															      <input type="text" name="numero_tarjeta" id="numero_tarjeta" class="form-control" maxlength="16" minlength="16" >
															    </div>
															</div>


															
															<div class="row">
															    <label class="col-sm-3" style="margin-top: 2%">Banco</label>
															    <div class="col-sm-9">
															      <select name="banco" id="banco" class="form-control"  onchange="consultarCuentas()">
															      	<option value="0">--Seleccione --</option>
															      	<!--<?php foreach($bancos as $banco){ ?>
															      		<option value="<?=$banco->id_banco ?>">
															      			<?=$banco->nombre_banco ?></option>
															      	<?php }?>-->
															      </select>
															    </div>
															</div>


															<div class="row">
															    <label class="col-sm-3" style="margin-top: 2%">Clabe / Cuenta</label>
															    <div class="col-sm-9">
															      <!--<input type="text" name="cuenta" id="cuenta" class="form-control" onkeypress='return solonumeros(event)' placeholder="P. EJ. 00211501600326941" maxlength="14" minlength="14" required>-->
															      <select name="cuenta" id="cuenta" class="form-control">
															      	<option value="0">--Seleccione --</option>
															      	<!--<?php foreach($bancos as $banco){ ?>
															      		<option value="<?=$banco->id_banco ?>">
															      			<?=$banco->nombre_banco ?></option>
															      	<?php }?>-->
															      </select>
															    </div>
															</div>


															<div class="row">
									                            <div class="col-md-6">
									                                <label for="tipolval">Fecha del pago*</label>
									                                <input type="date" class="form-control" name="fecha_pago" id="fecha_pago"  required style="text-align: center;" max="9999-12-31" required>
									                            </div>

									                            <input type="hidden" id="plazo">

									                            <div class="col-md-6">
									                                <label for="tipolval">Monto del pago</label>
									                                <input type="text" class="form-control monto_formato_decimales monto_pago" name="monto_pago" id="monto_pago"  required style="text-align: right; font-weight: bold;" onkeypress="return valida(event)" required>
									                            </div>
															</div>

															<div class="row">
															    <label class="col-sm-6">Saldo <br>Pendiente Total</label>
															    <div class="col-sm-6">
															      <input type="text" class="form-control monto_formato_decimales " name="saldo_pendiente_total" id="saldo_pendiente_total"  disabled style="text-align: right; font-weight: bold;" required>

															      <input type="hidden" class="form-control monto_formato_decimales"  id="saldo_pendiente_total_hidden"  disabled style="text-align: right; font-weight: bold;" >
															     
															    </div>
															</div>
							                        	</div>
							                        	<div class="col-md-8" style="">
							                        		<!-- max-height: 1200px;overflow: hidden;max-width: 600px;overflow: scroll; -->
							                        		<div class="row">
							                        			<div class="col-sm-12">
									                            	<table class="table table-bordered table-striped table-hover tableTotalRegistrar" id="tableCobranza">
									                            		<thead>
									                            			<tr style="font-size: 13px; background: #FFC000">
									                            				<th style="text-align: center; padding: 5px !important;">Acc.</th>
									                            				<th style="text-align: center; padding: 5px !important;"># oper.</th>
									                            				<th style="text-align: center; padding: 5px !important;">Recibo</th>
									                            				<th style="text-align: center; padding: 5px !important;">Mes</th>
									                            				<th style="text-align: center; padding: 5px !important;">Tipo</th>
									                            				<th style="text-align: center; padding: 5px !important;">Concepto</th>
									                            				<th style="text-align: center; padding: 5px !important;">Fecha de Movimiento</th>
									                            				<th style="text-align: center; padding: 5px !important;">Cargo</th>
									                            				<th style="text-align: center; padding: 5px !important;">Abono</th>
									                            				<th style="text-align: center; padding: 5px !important;">Saldo</th>
									                            			</tr>
									                            		</thead>

									                            		<tbody style="text-align: center;" id="tbody_cobranzas_detalle"></tbody>
									                            	</table>
									                            </div>
							                        		</div>
							                        	</div>
							                        	<div class="col-md-12">
							                        		<!-- -->
							                        		<div class="row">
									                            <div class="col-xs-12" style="height: 279px;margin-bottom: 15%;">
							                              			<label>Copia escaneada del Comprobante de pago</label>
									                                <div class="form-group valid-required">
									                                    <div class="form-line">
									                                        <!-- -->
									                                        <input 
										                                	type="file" autocomplete="off" 
										                                	class="file-rfc" 
										                                	data-msg-placeholder="Selecciona un {files} ..." 
										                                	id="comprobante_pago">    
									                                        <!-- -->
									                                    </div>
									                                </div>
							                            		</div>
															</div>
							                        		<!-- -->
							                        	</div>
						                        	</div>
						                        </div>    
								        	</div>
								        	
								        	<div class="col-sm-4 col-sm-offset-5">
					                            <button type="button" onclick="regresar('#cuadro2')" class="btn btn-primary waves-effect">Regresar</button>
					                            <input type="submit" value="Aplicar" id="send" class="btn btn-success waves-effect save-cliente">
					                            <button type="button" id="estado_cuenta" class="btn btn-warning waves-effect" data-toggle="modal" data-target="#exampleModal">
												  Estado de cuenta
												</button>
					                    	</div>
					                    	<div style="clear: both"></div>		
								        </form>	
								        <!--Modal de recibo-->
										<div class="modal fade" id="modal_recibo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
												  <div class="modal-dialog" role="document" style="width: 70%;">
												    <div class="modal-content">
												      <div class="modal-header">
												        <h5 class="modal-title" id="exampleModalLabel">Consultar Recibo</h5>
												        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
												          <span aria-hidden="true">&times;</span>
												        </button>
												      </div>
												      <div class="modal-body">
												         <form id="form_view_recibo" method="post"  onKeypress="if(event.keyCode == 13) event.returnValue = false;" enctype="multipart/form-data">

												         	<div class="row">
												         		<div class="col-md-4">
									                                <label for="tipolval">#Operacion</label>
									                                <input type="text" class="form-control monto_formato_decimales" name="operacion_view" id="operacion_view"  required style="font-weight: bold;" onkeypress="return valida(event)" disabled>
									                            </div>

									                            <div class="col-md-4">
									                                <label for="tipolval">Recibo</label>
									                                <input type="text" class="form-control monto_formato_decimales" name="recibo_view" id="recibo_view"  required style="font-weight: bold;" onkeypress="return valida(event)" disabled>
									                            </div>

									                             <div class="col-md-4">
									                                <label for="tipolval">Mes</label>
									                                <input type="text" class="form-control monto_formato_decimales" name="mes_view" id="mes_view"  required style="font-weight: bold;" onkeypress="return valida(event)" disabled>
									                            </div>

									                            <div class="col-md-4">
									                                <label for="tipolval">Monto del pago</label>
									                                <input type="text" class="form-control monto_formato_decimales" name="monto_pago_view" id="monto_pago_view"  required style="text-align: right; font-weight: bold;" onkeypress="return valida(event)" disabled>
									                            </div>

									                            <div class="col-md-4">
									                                <label for="tipolval">Forma de Pago</label>
									                                <input type="text" class="form-control monto_formato_decimales" name="fp_pago_view" id="fp_pago_view"  required style="font-weight: bold;" onkeypress="return valida(event)" disabled>
									                            </div>

									                             <div class="col-md-4">
									                                <label for="tipolval">Banco Pago</label>
									                                <input type="text" class="form-control monto_formato_decimales" name="banco_pago_view" id="banco_pago_view"  required style="font-weight: bold;" onkeypress="return valida(event)" disabled>
									                            </div>

									                             <div class="col-md-4">
									                                <label for="tipolval">Clabe / Cuenta</label>
									                                <input type="text" class="form-control monto_formato_decimales" name="cuenta_view" id="cuenta_view"  required style="text-align: right; font-weight: bold;" onkeypress="return valida(event)" disabled>
									                            </div>


									                            <div class="col-md-4">
									                                <label for="tipolval">Fecha del pago</label>
									                                <input type="text" class="form-control" name="fecha_pago_view" id="fecha_pago_view"  required style="text-align: center;" max="9999-12-31" disabled>
									                            </div>

									                             <div class="col-md-4">
									                                <label for="tipolval">Fecha de Realización*</label>
									                                <input type="text" class="form-control" name="fecha_contable_view" id="fecha_contable_view"  required style="text-align: center;" max="9999-12-31" disabled>
									                            </div>

															</div>

															<div class="row">
									                            <div class="col-xs-12" style="height: 279px;margin-bottom: 15%;">
							                              			<label>Copia escaneada del Comprobante de pago</label>
									                                <div class="form-group valid-required">
									                                    <div class="form-line">
									                                        <input type="file" id="comprobante_pago_view"  name="comprobante_pago_view" data-show-upload="false"  class="form-control mayusculas file_venta"  placeholder="Copia escaneada del Comprobante de pago" disabled>
									                                    </div>
									                                </div>
							                            		</div>
															</div>



												         </form>
												      </div>
												      <div class="modal-footer">
												        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
												      </div>
												    </div>
												  </div>
										</div>
					                    <!--Modal de recibo para editar -->
					                    <div class="modal fade" id="modal_recibo_edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
											  <div class="modal-dialog" role="document" style="width: 70%;">
											    <div class="modal-content">
											      <div class="modal-header">
											        <h5 class="modal-title" id="exampleModalLabel">Editar Recibo</h5>
											        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
											          <span aria-hidden="true">&times;</span>
											        </button>
											      </div>
											      <div class="modal-body">
											         <form id="form_edit_recibo" method="post"  onKeypress="if(event.keyCode == 13) event.returnValue = false;" enctype="multipart/form-data">

											         	<input type="hidden" id="id_recibo_edit" name="id_recibo">
											         	<input type="hidden" id="id_cotizacion_edit" name="id_venta">
											         	<input type="hidden" id="id_cobranza_edit" name="id_cobranza">
											         	<div class="row">

											         		<div class="col-md-4">
								                                <label for="tipolval">#Operacion</label>
								                                <input type="text" class="form-control monto_formato_decimales" name="operacion_edit" id="operacion_edit"  required style="font-weight: bold;" onkeypress="return valida(event)" disabled>
								                            </div>

								                            <div class="col-md-4">
								                                <label for="tipolval">Recibo</label>
								                                <input type="text" class="form-control monto_formato_decimales" name="recibo_edit" id="recibo_edit"  required style="font-weight: bold;" onkeypress="return valida(event)" disabled>
								                            </div>

								                             <div class="col-md-4">
								                                <label for="tipolval">Mes</label>
								                                <input type="text" class="form-control monto_formato_decimales" name="mes_edit" id="mes_edit"  required style="font-weight: bold;" onkeypress="return valida(event)" disabled>
								                            </div>

								                            <div class="col-md-4">
								                                <label for="tipolval">Monto del pago</label>
								                                <input type="text" class="form-control monto_formato_decimales" name="monto_pago_view" id="monto_pago_edit"  required style="text-align: right; font-weight: bold;" onkeypress="return valida(event)" disabled>
								                            </div>

								                            <div class="col-md-4">
								                                <label for="tipolval">Forma de Pago</label>
								                                <input type="text" class="form-control monto_formato_decimales" name="fp_pago_view" id="fp_pago_edit"  required style="font-weight: bold;" onkeypress="return valida(event)" disabled>
								                            </div>

								                             <div class="col-md-4">
								                                <label for="tipolval">Banco Pago</label>
								                                <input type="text" class="form-control monto_formato_decimales" name="banco_pago_view" id="banco_pago_edit"  required style="font-weight: bold;" onkeypress="return valida(event)" disabled>
								                            </div>

								                             <div class="col-md-4">
								                                <label for="tipolval">Clabe / Cuenta</label>
								                                <input type="text" class="form-control monto_formato_decimales" name="cuenta_view" id="cuenta_edit"  required style="text-align: right; font-weight: bold;" onkeypress="return valida(event)" disabled>
								                            </div>


								                            <div class="col-md-4">
								                                <label for="tipolval">Fecha del pago</label>
								                                <input type="text" class="form-control" name="fecha_pago_view" id="fecha_pago_edit"  required style="text-align: center;" max="9999-12-31" disabled>
								                            </div>

								                             <div class="col-md-4">
								                                <label for="tipolval">Fecha de Realización*</label>
								                                <input type="text" class="form-control fecha" name="fecha_contable" id="fecha_contable_edit"  required style="text-align: center;" max="9999-12-31">
								                            </div>

														</div>

														<div class="row">
									                            <div class="col-xs-12" style="height: 279px;margin-bottom: 15%;">
							                              			<label>Copia escaneada del Comprobante de pago</label>
									                                <div class="form-group valid-required">
									                                    <div class="form-line">
									                                        <input type="file" id="comprobante_pago_edit"  name="" data-show-upload="false"  class="form-control mayusculas file_venta"  placeholder="Copia escaneada del Comprobante de pago">
									                                    </div>
									                                </div>
							                            		</div>
															</div>
														<div style="margin-top: 20px;">	
															<center><button type="button" class="btn btn-primary" data-dismiss="modal">Regregar</button> <button type="submit" id="btn-edit-recibo" class="btn btn-success">Aplicar</button></center>
															
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
			                        </div>
		                    	</div>
		                	</div>
		            	</div>
		            </div>	
		        <!-- Cierre del cuadro de registrar lista de valores -->

		        <!-- Comienzo del cuadro de consultar lista de valores -->
					<div class="row clearfix ocultar" id="cuadro3">
		                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                    <div class="card">
		                        <div class="header">
		                            <h2>Administracion de la  <?php echo $breadcrumbs[0]["nombre_lista_vista"]; ?></h2>
		                        </div>
		                        <div class="body">
		                        	<div class="table-responsive">
										<div class="row">
											<div class="col-sm-4">
			                            		<label for="descriplval">Proyecto</label>
		                                    	<select name="proyecto" id="proyecto" disabled required class="form-control">
		                                    		<?php foreach ($proyectos as $data): ?>
					                        			<option value="<?=$data->id_proyecto;?>"><?=$data->nombre." - ".$data->nombres." - ".$data->paterno;?></option>
					                        		<?php endforeach ?>
		                                    	</select>
				                            </div>

				                            <div class="col-md-4">
				                                <label for="tipolval">Estatus</label>
				                                <input type="text" class="form-control " name="status" id="status" disabled  required>
				                            </div>


				                            <div class="col-md-4">
				                                <label for="tipolval">Fecha</label>
				                                <input type="date" class="form-control " name="fecha" id="fecha" disabled  required>
				                            </div>
										</div>


										<div class="row">
				                            <div class="col-md-4">
				                                <label for="tipolval">Cliente o Prospecto</label>
				                                <input type="text" class="form-control " name="cliente" id="cliente" disabled  required>
				                            </div>


				                            <div class="col-md-3">
				                                <label for="tipolval">Vendedor</label>
				                                <input type="text" class="form-control" name="vendedor" id="vendedor" disabled  required>
				                            </div>

				                            <div class="col-md-3">
				                                <label for="tipolval">Inmobiliaria</label>
				                                <input type="text" class="form-control" name="inmobiliaria" id="inmobiliaria" disabled  required>
				                            </div>

				                            <div class="col-md-2">
				                                <label for="tipolval">Corrida</label>
				                                <input type="text" class="form-control" name="corrida" id="corrida" disabled  required>
				                            </div>
										</div>




										    <br>
                                			<div class="col-md-12" style="border-bottom: 5px solid #22B14C;">
					                            <h4>Total</h4>
					                        </div>
					                        <br>

					                         <input type="hidden" id="date_actual" value="<?= date("d-m-Y")?>">
					                         <div class="col-sm-12">
				                            	<table class="table table-bordered table-striped table-hover tableTotalRegistrar" id="tableTotalEditar">
				                            		<thead>
				                            			<tr style="text-align: center;">
				                            				<th>Prod</th>
				                            				<th class="date-total_editar">Fecha Venta</th>
				                            				<th>Lote</th>
				                            				<th class="optional_editar">M2</th>
				                            				<th class="optional_editar">Precio por M2</th>
				                            				<th>Monto total</th>
				                            				<th>Anticipo</th>
				                            				<th>Monto Pagado</th>
				                            				<th>Saldo</th>
				                            				<th>Plazo del saldo</th>
				                            				<th>Mensualidad Plazos</th>
				                            				<th>Forma Pago</th>
				                            				<th>Monto Cuotas</th>
				                            			</tr>
				                            		</thead>
				                            		<tbody>
				                            			<tr style="text-align: center;">
				                            				<td id="cantidad_editar">0</td>
				                            				<td class="date-total_editar" id="date_editar"></td>
				                            				<td  id="zona_total_editar"></td>
				                            				<td class="optional_editar" id="m2_total_editar">0</td>
				                            				<td class="optional_editar" id="precio_m2_total_editar">0</td>
				                            				<td id="monto_total_editar">0</td>
				                            				<td id="anticipo_total_editar">0</td>
				                            				<td id="monto_pagado">0</td>
				                            				<td id="saldo_total_editar">0</td>
				                            				<td id="saldo_plazo_editar">0</td>
				                            				<td id="mensualidad_total_editar">0</td>
				                            				<td id="fp_editar"></td>
				                            				<td id="monto_cuotas_total_editar">0</td>
				                            			</tr>
				                            		</tbody>
				                            	</table>


				                            	
											    <!-- -->

											    <!-- -->  	
											</div>

                            				<br>



                            			 	<br>
	                            			<div class="col-md-12" style="border-bottom: 5px solid #7092BE;">
					                            <h4>Cobranza</h4>
					                        </div>
					                        <br>

											<form id="form_save_pago" method="post"  onKeypress="if(event.keyCode == 13) event.returnValue = false;" enctype="multipart/form-data">
												<input type="hidden" name="id_venta" id="id_venta">
												<input type="hidden" name="id_recibo" id="id_recibo">
												<input type="hidden" name="fecha_cuota" id="fecha_cuota">
												<input type="hidden" name="id_cliente" id="id_cliente">

												<input type="hidden" id="status_venta">
	

						                        <div class="row">
						                        	<div class="col-md-4">
														<div class="row">
															<div class="col-md-3">
								                                <label for="tipolval">Recibo</label>
								                                <input type="text" class="form-control" name="recibo" id="recibo" readonly  style="text-align: center;">
								                            </div>

								                            <div class="col-md-3">
								                                <label for="tipolval">Mes</label>
								                                <input type="text" class="form-control" name="mes" id="mes"  readonly style="text-align: center;">
								                            </div>

								                            <div class="col-md-6">
								                                <label for="tipolval">Monto</label>
								                                <input type="text" class="form-control monto_formato_decimales" readonly name="monto" id="monto"  required style="text-align: right; font-weight: bold;" onkeypress="return valida(event)">
								                            </div>
														</div>

														<div class="row">
														    <label class="col-sm-3" >Forma de pago</label>
														    <div class="col-sm-9">
														      <select name="fp" id="fp" class="form-control" required>
														      	<option value="">Seleccione</option>
															      	<?php foreach ($forma_pagos as  $forma_pago): ?>
															      		<option value="<?= $forma_pago->codlval?>"><?= $forma_pago->descriplval?></option>
															      	<?php endforeach ?>
															      </select>
														      </select>
														    </div>
														</div>


														<div class="row" style="display: none" id="tarjeta">
														    <label class="col-sm-3" >Numero de Tarjeta</label>
														    <div class="col-sm-9">
														      <input type="text" name="numero_tarjeta" id="numero_tarjeta" class="form-control" maxlength="16" minlength="16">
														    </div>
														</div>


														
														<div class="row">
														    <label class="col-sm-3" style="margin-top: 2%">Banco</label>
														    <div class="col-sm-9">
														      <select name="banco" id="banco" class="form-control" required>
														      	
														      </select>
														    </div>
														</div>


														<div class="row">
														    <label class="col-sm-3" style="margin-top: 2%">Clabe / Cuenta</label>
														    <div class="col-sm-9">
														      <select name="cuenta" id="cuenta" class="form-control" disabled required>
														      	<option value="">Seleccione</option>
														      </select>
														    </div>
														</div>




														<div class="row">
								                            <div class="col-md-6">
								                                <label for="tipolval">Fecha del pago*</label>
								                                <input type="date" class="form-control" name="fecha_pago" id="fecha_pago"  required style="text-align: center;" max="9999-12-31">
								                            </div>

								                            <input type="hidden" id="plazo">

								                            <div class="col-md-6">
								                                <label for="tipolval">Monto del pago</label>
								                                <input type="text" class="form-control monto_formato_decimales" name="monto_pago" id="monto_pago"  required style="text-align: right; font-weight: bold;" onkeypress="return valida(event)">
								                            </div>
														</div>

														<div class="row">
								                            <div class="col-md-6">
								                                <label for="tipolval">Fecha de Realización*</label>
								                                <input type="date" class="form-control" name="fecha_contable" id="fecha_contable"  required style="text-align: center;" max="9999-12-31">
								                            </div>
								                            <!-- -->
								                            <div class="row">
									                            <div class="col-xs-12" style="height: 279px;margin-bottom: 15%;">
							                              			<label>Copia escaneada del Comprobante de pago</label>
									                                <div class="form-group valid-required">
									                                    <div class="form-line">
									                                        <input type="file" id="comprobante_pago_view"  name="comprobante_pago_view" data-show-upload="false"  class="form-control mayusculas file_venta"  placeholder="Copia escaneada del Comprobante de pago" disabled>
									                                    </div>
									                                </div>
							                            		</div>
															</div>
								                            <!-- -->
														</div>




														<div class="row">
								                            <div class="col-md-3">
								                                <label for="tipolval">Dias Morosidad</label>
								                                <input type="number" class="form-control" name="dias_mora" id="dias_mora"  style="text-align: center;" readonly>
								                            </div>

								                            <div class="col-md-3">
								                                <label for="tipolval">% Morosidad</label>
								                                <input type="text" class="form-control" name="porcentaje" id="porcentaje"  style="text-align: center;" readonly>
								                            </div>

								                            <div class="col-md-6" style="text-align: center;">
								                                <label for="tipolval">Monto por <br>Morosidad</label>
								                                <input type="text" class="form-control monto_formato_decimales" name="monto_mora" id="monto_mora"  style="text-align: right; font-weight: bold;" onkeypress="return valida(event)" readonly>
								                            </div>
														</div>


														<div class="row">
														    <label class="col-sm-6">Saldo <br>Pendiente de la cuota</label>
														    <div class="col-sm-6">
														      <input type="text" class="form-control monto_formato_decimales" name="saldo_pendiente" id="saldo_pendiente"  disabled style="text-align: right; font-weight: bold;" >
														    </div>
														</div>


														<div class="row">
														    <label class="col-sm-6">Saldo <br>Pendiente Total</label>
														    <div class="col-sm-6">
														      <input type="text" class="form-control monto_formato_decimales" name="saldo_pendiente_total" id="saldo_pendiente_total"  disabled style="text-align: right; font-weight: bold;" >

														      <input type="hidden" class="form-control monto_formato_decimales"  id="saldo_pendiente_total_hidden"  disabled style="text-align: right; font-weight: bold;" >
														      
														    </div>
														</div>


						                        	</div>


						                        	<div class="col-md-8">
						                        		<div class="row">
						                        			<div class="col-sm-12">
								                            	<table class="table table-bordered table-striped table-hover tableTotalRegistrar" id="tableCobranza">
								                            		<thead>
								                            			<tr style="font-size: 13px; background: #FFC000">
								                            				<th style="text-align: center; padding: 5px !important;">Acc.</th>
								                            				<th style="text-align: center; padding: 5px !important;"># oper.</th>
								                            				<th style="text-align: center; padding: 5px !important;">Recibo</th>
								                            				<th style="text-align: center; padding: 5px !important;">Mes</th>
								                            				<th style="text-align: center; padding: 5px !important;">Tipo</th>
								                            				<th style="text-align: center; padding: 5px !important;">Concepto</th>
								                            				<th style="text-align: center; padding: 5px !important;">Fecha de Movimiento</th>
								                            				<th style="text-align: center; padding: 5px !important;">Cargo</th>
								                            				<th style="text-align: center; padding: 5px !important;">Abono</th>
								                            				<th style="text-align: center; padding: 5px !important;">Saldo</th>
								                            			</tr>
								                            		</thead>

								                            		<tbody style="text-align: center;"></tbody>
								                            	</table>
								                            </div>
						                        		</div>
						                        	</div>
						                        </div>
						                       
						                        <br><br>
						                        <!--<div class="row">
						                            <div class="col-xs-12" style="height: 279px;margin-bottom: 15%;">
				                              			<label>Copia escaneada del Comprobante de pago</label>
						                                <div class="form-group valid-required">
						                                    <div class="form-line">
						                                        <input type="file" id="comprobante_pago"  name="comprobante_pago" data-show-upload="false"  class="form-control mayusculas file_venta"  placeholder="Copia escaneada del Comprobante de pago">
						                                    </div>
						                                </div>
				                            		</div>
												</div>-->

	                            			<br>
	                            			<div class="col-sm-4 col-sm-offset-4">
		                                        <button type="button" onclick="regresar_lval('#cuadro3')" class="btn btn-primary waves-effect">Regresar</button>
	                            				<button type="submit" id="btn-save" class="btn btn-success waves-effect">Aplicar</button>
	                            				<button type="button" id="estado_cuenta" class="btn btn-warning waves-effect" data-toggle="modal" data-target="#exampleModal">
												  Estado de cuenta
												</button>


			                                </div>

		                                </form>
			                        </div>
		                        </div>
		                    </div>
		                </div>
		            </div>



		            <!-- Modal -->
					<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
					  <div class="modal-dialog" role="document" style="width: 70%;">
					    <div class="modal-content">
					      <div class="modal-header">
					        <h5 class="modal-title" id="exampleModalLabel">Estado de cuenta</h5>
					        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
					          <span aria-hidden="true">&times;</span>
					        </button>
					      </div>
					      <div>
					      	<input type="hidden" id="id_cotizacionModalEc" name="id_cotizacionModalEc">
					      	<input type="hidden" id="id_clienteModalEc" name="id_clienteModalEc">
					      </div>
					      <div class="modal-body">
					        <table class="table table-bordered table-striped table-hover tableTotalRegistrar" style="width: 100%;" id="tableCobranzaModal">
                        		<thead>
                        			<tr style="font-size: 13px; background: #FFC000">
                        				<th style="text-align: center; padding: 5px !important;"></th>
                        				<th style="text-align: center; padding: 5px !important;"># oper.</th>
                        				<th style="text-align: center; padding: 5px !important;">Recibo</th>
                        				<th style="text-align: center; padding: 5px !important;">Mes</th>
                        				<th style="text-align: center; padding: 5px !important;">Tipo</th>
                        				<th style="text-align: center; padding: 5px !important;">Concepto</th>
                        				<th style="text-align: center; padding: 5px !important;">Fecha de Movimiento</th>
                        				<th style="text-align: center; padding: 5px !important;">Cargo</th>
                        				<th style="text-align: center; padding: 5px !important;">Abono</th>
                        				<th style="text-align: center; padding: 5px !important;">Saldo</th>
                        			</tr>
                        		</thead>

                        		<tbody style="text-align: center;"></tbody>
                        	</table>
					      </div>
					      <div class="modal-footer">
					        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
					        <button id="send-mail"  class="btn btn-primary waves-effect">Enviar al correo</button>
					        <a id="pdf" target="_blank" class="btn btn-danger">PDF</a>
					      </div>
					    </div>
					  </div>
					</div>
			</div>
		</section>
	</body>
	<script src="<?=base_url();?>assets/template/plugins/jquery-datatable/jquery.dataTables.js"></script>
    <script src="<?=base_url();?>assets/template/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>
    <script src="<?=base_url();?>assets/template/plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js"></script>
    <script src="<?=base_url();?>assets/template/plugins/momentjs/moment.js"></script>
    <script src="<?=base_url();?>assets/template/plugins/bootstrap-datetimepicker-master/js/bootstrap-datetimepicker.min.js"></script>
    <script src="<?=base_url();?>assets/template/plugins/jquery-datatable/extensions/export/buttons.flash.min.js"></script>
    <script src="<?=base_url();?>assets/template/plugins/jquery-datatable/extensions/export/jszip.min.js"></script>
    <script src="<?=base_url();?>assets/template/plugins/jquery-datatable/extensions/export/pdfmake.min.js"></script>
    <script src="<?=base_url();?>assets/template/plugins/jquery-datatable/extensions/export/vfs_fonts.js"></script>
    <script src="<?=base_url();?>assets/template/plugins/jquery-datatable/extensions/export/buttons.html5.min.js"></script>
    <script src="<?=base_url();?>assets/template/plugins/jquery-datatable/extensions/export/buttons.print.min.js"></script>

    <script src="<?=base_url();?>assets/template/plugins/bootstrap-fileinput/js/fileinput.js" type="text/javascript"></script>



    <script src="<?=base_url();?>assets/cpanel/Productos/js/numeral/min/numeral.min.js"></script>

    <script src="<?=base_url();?>assets/cpanel/Cobranza/js/Cobranza.js"></script>
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
