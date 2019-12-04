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
													<th style="width:30%">Acciones</th>
		                                            <th>N° Identificacion</th>
		                                            <th>Tipo Persona </th>
		                                            <th>Nombre Prospecto</th>

		                                            <th>Vendedor</th>

		                                            <!--<th style="display: none">Proyecto</th>-->

		                                            <th>Fecha de Registro</th>

		                                            <th>Registrado Por</th>

		                                            

		                                        </tr>

		                                    </thead>

		                                    <tbody></tbody>

		                                </table>

		                                <div class="col-md-2 eliminar ocultar">

		                                	<button class="btn btn-danger waves-effect" onclick="eliminarMultiple('Prospecto/eliminar_multiple')">Eliminar seleccionados</button>

		                                </div>

		                                <div class="col-md-2 actualizar ocultar">

		                                	<button class="btn btn-warning waves-effect" onclick="statusMultiple('Prospecto/status_multiples', 1, 'activar')">Activar seleccionados</button>

		                                </div>

		                                <div class="col-md-2 actualizar ocultar">

		                                	<button class="btn btn-warning waves-effect" onclick="statusMultiple('Prospecto/status_multiples', 2, 'desactivar')">Desactivar seleccionados</button>

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

                        <form name="form_prospecto_registrar" id="form_prospecto_registrar" method="post" enctype="multipart/form-data">

    						<div>

                        		<div class="col-sm-6">

                            		<label for="id_vendedor">Vendedor*</label>

                                	<select name="id_vendedor" id="id_vendedor" required class="form-control vendedor">

                                    	<option value="" selected>Seleccione</option>

                                		<?php if (sizeof($vendedores) == 1): ?>
                                			<?php foreach ($vendedores as $vendedor): ?>

                                    			<option value="<?= $vendedor['id_vendedor']; ?>"><?= $vendedor['nombre_vendedor']." ".$vendedor['apellido_vendedor']; ?></option>

                                    		<?php endforeach ?>



                                    	<?php else: ?>

                                    			<option value="" selected>Seleccione</option>
	                                    		<?php foreach ($vendedores as $vendedor): ?>

	                                    			<option value="<?= $vendedor['id_vendedor']; ?>"><?= $vendedor['nombre_vendedor']." ".$vendedor['apellido_vendedor']; ?></option>

	                                    		<?php endforeach ?>

                                		<?php endif ?>

                                		

                                	</select>

	                            </div>
	                           
	                            <div class="col-sm-6" >

                            		<label for="proyecto"><!--Proyecto*--></label>

                                	<!--<select name="id_proyecto" id="proyecto" required disabled class="form-control proyecto">

                                		<option value="" selected>Seleccione</option>

                                	</select>-->

	                            </div>

							</div>

                           	<div class="form-group" id="tipopersona">

    							<label for="tipopersona">Tipo Persona*</label> <br>

    							<input type="radio" checked name="rad_tipoper" id="fisica"  value="fisica">

    							<label for= "fisica">Física</label>

    							<input type="radio" name="rad_tipoper" id="moral"  value="moral">

    							<label for= "moral">Moral</label>

    						</div>

				                            <div style="border-bottom: 1px solid #ccc;"> </div>

    						 

				                            <div class="col-sm-12"><h3>Datos Generales</h3></div>

				                             



				      <div id="personaFisica">

					        	      

					        	      	<div class="col-sm-4">

	                            		<label for="rfc">N° Identificacion</label>

		                                <div class="form-group valid-required">

		                                    <div class="form-line">

		                                        <input type="text" class="fisicaf form-control mayusculas rfc" name="rfc_fisico" id="rfc_fisico" onkeypress='return solosnumerosyletras(event)' maxlength="13" placeholder="P. EJ. BML920313XXX" autocomplete="off" onblur="//clienteExiste(this)">

		                                        <span id="resultado" class="curpError text-danger resultado"></span>

		                                    </div>

		                                </div>

	                            		</div>



							        	<div class="col-sm-4">

		                            		<label for="nombre_prospecto">Nombre(s)*</label>

			                                <div class="form-group valid-required">

			                                    <div class="form-line">

			                                        <input type="text" class="fisicaf form-control mayusculas nombre_prospecto" name="nombre_prospecto" autocomplete="off" onkeypress='return sololetras(event)' id="nombre_prospecto" maxlength="30" placeholder="P. EJ.LUIS RAÚL" required>

			                                        <input type="hidden" class="form-control mayusculas" name="id_cliente" id="id_cliente"  maxlength="30">

			                                        <input type="hidden" class="form-control mayusculas" name="id_datos_personales" id="id_datos_personales"  maxlength="30">

			                                         <input type="hidden" class="form-control mayusculas" name="id_contacto" id="id_contacto"  maxlength="30">

		                                    	</div>

		                             		</div>

		                           		</div>

		                           		<div class="col-sm-4">

	                            			<label for="apellido_paterno_prospecto">Apellido Paterno*</label>

		                                	<div class="form-group valid-required">

		                                    	<div class="form-line">

		                                        	<input type="text" class="fisicaf form-control mayusculas" name="apellido_paterno_prospecto" autocomplete="off" onkeypress='return sololetras(event)' maxlength="15" id="apellido_paterno_prospecto" placeholder="P. EJ. BELLO" required>

		                                    	</div>

		                                	</div>

		                             	</div>

		                             	<div class="col-sm-4">

		                            		<label for="apellido_materno_prospecto">Apellido Materno*</label>

			                                <div class="form-group valid-required">

			                                    <div class="form-line">

			                                        <input type="text" class="fisicaf form-control mayusculas" name="apellido_materno_prospecto" autocomplete="off" maxlength="15" onkeypress='return sololetras(event)' id="apellido_materno_prospecto" placeholder="P. EJ. MENA" required>

			                                    </div>

		                                	</div>

	                          			</div>

	                          			

	                            		<div class="col-sm-4">

                                <label for="tef_ppal_prospecto">Teléfono Principal*</label>

	                                <div class="form-group valid-required">

	                                    <div class="form-line">

	                                        <input type="text" class="form-control telefono guardado fisicaf" name="telefono_principal_prospecto" id="tef_ppal_prospecto" placeholder="P. EJ.: +00 (000) 000-00-00" required onkeyup="validPhone(this)">

	                                        		<span class="emailError text-danger"></span>

                                    	</div>

                           		 	</div>

                    		</div>

                            		<div class="col-sm-4">

		                                <label for="tfl_movil_r">Teléfono Celular</label>

		                                <div class="form-group valid-required">

	                                    <div class="form-line">

	                                        <input type="text" class="form-control telefono" name="telefono_movil" id="tfl_movil_r" placeholder="P. EJ.: +00 (000) 000-00-00" onkeyup="validPhone(this)">

	                                        		<span class="emailError text-danger"></span>

	                                    </div>

                               		 </div>

                            		</div>

                            		<div class="col-sm-4">

		                                <label for="tlf_casa_r">Teléfono Casa</label>

		                                <div class="form-group valid-required">

	                                    <div class="form-line">

	                                        <input type="text" class="form-control telefono" name="telefono_casa" id="tlf_casa_r" placeholder="P. EJ.: +00 (000) 000-00-00" onkeyup="validPhone(this)">

	                                        		<span class="emailError text-danger"></span>

	                                    </div>

                               		 </div>

                            		</div>

                            		<div class="col-sm-4">

		                                <label for="tlf_trabajo_r">Teléfono Trabajo</label>

		                                <div class="form-group valid-required">

	                                    <div class="form-line">

	                                        <input type="text" class="form-control telefono" name="telefono_trabajo" id="tlf_trabajo_r" placeholder="P. EJ.: +00 (000) 000-00-00" onkeyup="validPhone(this)">

	                                        		<span class="emailError text-danger"></span>

	                                    </div>

                               		 </div>

                            		</div>

                            		<div class="col-sm-4">

		                                <label for="tlf_fax_r">Teléfono Fax</label>

		                                <div class="form-group valid-required">

	                                    <div class="form-line">

	                                        <input type="text" class="form-control telefono" name="telefono_fax_r" id="tlf_fax_r" placeholder="P. EJ.: +00 (000) 000-00-00" onkeyup="validPhone(this)">

	                                        		<span class="emailError text-danger"></span>

	                                    </div>

                               		 </div>

                            		</div>

                            		<div class="col-sm-4">

		                                <label for="correo_r">Correo Electrónico*</label>

		                                <div class="form-group valid-required">

	                                    	<div class="form-line">

	                                        	<input type="email" class="form-control fisicaf" name="correo_fisico" id="correo_r" placeholder="P. EJ. ejemplo@dominio.com" required maxlength="60" onchange="validEmail(this)"  style="text-transform: lowercase;">

	                                        	<span class="emailError text-danger"></span>

	                                    	</div>

                                		</div>

                            		</div>

                            		<div class="col-sm-4">

		                                <label for="coreo_opc_r">Correo Electrónico Opcional</label>

		                                <div class="form-group valid-required">

	                                    	<div class="form-line">

	                                        	<input type="email" class="form-control" name="coreo_opc_r" id="coreo_opc_r" placeholder="P. EJ. ejemplo@dominio.com" maxlength="60" onchange="validEmail(this)" style="text-transform: lowercase;"> 

	                                        	<span class="emailError text-danger"></span>

	                                    	</div>

                                		</div>

                            		</div>


                            		<div class="col-sm-12">

		                                <label for="">Obervaciones</label>

		                                <div class="form-group valid-required">

	                                    	<div class="form-line">

												<textarea name="observaciones_fisica" id="observaciones_fisica" class="form-control"></textarea>

	                                    	</div>

                                		</div>

                            		</div>



		

						        </div>

						        <div id="personaMoral" dstyle="display: none;">

						          		<div class="col-sm-4">

	                            		<label for="rfc">N° Identificacion</label>

		                                <div class="form-group valid-required">

		                                    <div class="form-line">

		                                        <input type="text" class="form-control mayusculas moralfp" name="rfc_moral" id="rfc_moral" onkeypress='return solosnumerosyletras(event)' maxlength="13" placeholder="P. EJ. BML920313XXX" autocomplete="off" onchange="////clienteExiste(this)">

		                                         <input type="hidden" class="form-control mayusculas" name="id_cliente" id="id_cliente_moral"  maxlength="30">

			                                        <input type="hidden" class="form-control mayusculas" name="id_datos_personales" id="id_datos_personales_moral"  maxlength="30">

			                                         <input type="hidden" class="form-control mayusculas" name="id_contacto" id="id_contacto_moral"  maxlength="30">

		                                      

		                                    </div>

		                                </div>

	                            		</div>

							        	<div class="col-sm-4">

		                            		<label for="razon_social_r">Razon Social*</label>

			                                <div class="form-group valid-required">

			                                    <div class="form-line">

			                                        <input type="text" class="form-control mayusculas moralfp" name="razon_social_r" autocomplete="off" id="razon_social_r" maxlength="30" placeholder="P. EJ.LUIS RAÚL">

		                                    	</div>

		                             		</div>

		                           		</div>

                         		

	                            		<div class="col-sm-4">

                                <label for="tlf_ppal_r">Teléfono Principal*</label>

	                                <div class="form-group valid-required">

	                                    <div class="form-line">

	                                        <input type="text" class="form-control telefono guardado moralfp" name="telefono_principal" id="tlf_ppal_moral_r" placeholder="P. EJ.: +00 (000) 000-00-00" onkeyup="validPhone(this)">

	                                        		<span class="emailError text-danger"></span>

                                    	</div>

                           		 	</div>

                    				</div>

                            		

                            		<div class="col-sm-4">

		                                <label for="tlf_fax_r">Teléfono Fax</label>

		                                <div class="form-group valid-required">

	                                    <div class="form-line">

	                                        <input type="text" class="form-control telefono" name="telefono_fax_r" id="tlf_fax_moral_r" placeholder="P. EJ.: +00 (000) 000-00-00" onkeyup="validPhone(this)">

	                                        		<span class="emailError text-danger"></span>

	                                    </div>

                               		 </div>

                            		</div>

                            		<div class="col-sm-4">

		                                <label for="correo_r">Correo Electrónico*</label>

		                                <div class="form-group valid-required">

	                                    	<div class="form-line">

	                                        	<input type="email" class="form-control guardado moralfp" name="correo" id="correo_moral_r" placeholder="P. EJ. ejemplo@dominio.com" maxlength="60" onchange="validEmail(this)" style="text-transform: lowercase;">

	                                        	<span class="emailError text-danger"></span>

	                                    	</div>

                                		</div>

                            		</div>


                            		<div class="col-sm-12">

		                                <label for="">Obervaciones</label>

		                                <div class="form-group valid-required">

	                                    	<div class="form-line">

												<textarea name="observaciones_moral" id="observaciones_moral" class="form-control"></textarea>

	                                    	</div>

                                		</div>

                            		</div>



                            		

		

						        </div>

						             	 		<div class="col-sm-4 col-sm-offset-5">

                                    <button type="button" onclick="regresar('#cuadro2')" class="btn btn-primary waves-effect">Regresar</button>

                                    <input type="submit" value="Guardar"  class="btn btn-success waves-effect save-cliente">

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
	                            <h2>Consultar <?php echo $breadcrumbs[0]["nombre_lista_vista"]; ?></h2>
	                        </div>
	                        <div class="body">
	                        	<div class="table-responsive">

		                        	<div class="col-sm-6">

	                            		<label for="vendedor_m">Vendedor*</label>

		                                <div class="form-group valid-required">

		                                    <div class="form-line">

		                                        <input type="text" class="form-control mayusculas" name="vendedor_m" autocomplete="off" onkeypress='return sololetras(event)' id="vendedor_m" maxlength="30" placeholder="P. EJ.LUIS RAÚL" disabled>

	                                    	</div>

	                             		</div>

	                           		</div>
	                           		<!--- Vacio-->
	                           		<div class="col-sm-6" style="display: none">
	                           		</div>	
	                           		<!-- --> 

		                       		<div class="col-sm-6" style="display: none">

		                        		<label for="proyecto_m">Proyecto*</label>

		                                <div class="form-group valid-required">

		                                    <div class="form-line">

		                                        <input type="text" class="form-control mayusculas" name="proyecto_m" autocomplete="off" onkeypress='return sololetras(event)' id="proyecto_m" maxlength="30" placeholder="P. EJ.LUIS RAÚL" disabled>

		                                	</div>

		                         		</div>

		                       		</div>

				                 	<div class="form-group" id="tipopersona_mostrar">

											<label for="tipopersona_mostrar">Tipo Persona*</label> <br>

											<input type="radio" disabled name="rad_tipoper_mostrar" id="fisica_mostrar"  value="fisica_m">

											<label for= "fisica_mostrar">Física</label>

											<input type="radio" disabled name="rad_tipoper_mostrar" id="moral_mostrar"  value="moral_m">

											<label for= "moral_mostrar">Moral</label>

									</div>





					                <div style="border-bottom: 1px solid #ccc;"> </div>

					                <div class="col-sm-12"><h3>Datos Generales</h3></div>

							        <div class="tab-content">
										<div class="tab-pane fade in active" id="datogeneralMostrar">

								        	<div id="datosGeneralFisica">

								        		

								        		<div class="col-sm-4">

					                        		<label for="rfc_m">N° Identificacion</label>

					                                <div class="form-group valid-required">

					                                    <div class="form-line">

					                                        <input type="text" class="fisicaf form-control mayusculas" name="rfc_m" id="rfc_m" onkeypress='return solosnumerosyletras(event)' maxlength="13" placeholder="P. EJ. BML920313XXX" autocomplete="off" disabled oninput="validarInputRfc(this)">

					                                        <span id="resultado" class="curpError text-danger resultado"></span>

					                                    </div>

					                                </div>

					                    		</div>

									        	<div class="col-sm-4">

					                        		<label for="nombre_prospecto_m">Nombre(s)*</label>

					                                <div class="form-group valid-required">

					                                    <div class="form-line">

					                                        <input type="text" class="form-control mayusculas" name="nombre_prospecto_m" autocomplete="off" onkeypress='return sololetras(event)' id="nombre_prospecto_m" maxlength="30" placeholder="P. EJ.LUIS RAÚL" disabled>

					                                	</div>

					                         		</div>

					                       		</div>

					                       		<div class="col-sm-4">

					                    			<label for="apellido_paterno_prospecto_m">Apellido Paterno*</label>

					                            	<div class="form-group valid-required">

					                                	<div class="form-line">

					                                    	<input type="text" class="form-control mayusculas" name="apellido_paterno_prospecto_m" autocomplete="off" onkeypress='return sololetras(event)' maxlength="15" id="apellido_paterno_prospecto_m" placeholder="P. EJ. BELLO" disabled>

					                                	</div>

					                            	</div>

					                         	</div>

					                         	<div class="col-sm-4">

					                        		<label for="apellido_materno_prospecto_m">Apellido Materno*</label>

					                                <div class="form-group valid-required">

					                                    <div class="form-line">

					                                        <input type="text" class="fisicaf form-control mayusculas" name="apellido_materno_prospecto_m" autocomplete="off" maxlength="15" onkeypress='return sololetras(event)' id="apellido_materno_prospecto_m" placeholder="P. EJ. MENA" disabled>

					                                    </div>

					                            	</div>

					                  			</div>

					                  			

					                    		<div class="col-sm-4">

					                                <label for="tef_ppal_prospecto_m">Teléfono Principal*</label>

						                                <div class="form-group valid-required">

						                                    <div class="form-line">

						                                        <input type="text" class="form-control telefono guardado" name="telefono_principal_prospecto_m" id="tef_ppal_prospecto_m" placeholder="P. EJ.: +00 (000) 000-00-00" disabled>

					                                    	</div>

					                           		 	</div>

					        					</div>

					                    		<div class="col-sm-4">

					                                <label for="tfl_movilContacto_m">Teléfono Celular</label>

					                                <div class="form-group valid-required">

					                                <div class="form-line">

					                                    <input type="text" class="form-control telefono" name="telefono_movil_contacto" id="tfl_movilContacto_m" placeholder="P. EJ.: +00 (000) 000-00-00" disabled>

					                                </div>

					                       		 </div>

					                    		</div>

					                    		<div class="col-sm-4">

					                                <label for="tlf_casa_m">Teléfono Casa</label>

					                                <div class="form-group valid-required">

					                                <div class="form-line">

					                                    <input type="text" class="form-control telefono" name="telefono_casa_contacto" id="tlf_casa_m" placeholder="P. EJ.: +00 (000) 000-00-00" disabled>

					                                </div>

					                       		 </div>

					                    		</div>

					                    		<div class="col-sm-4">

					                                <label for="tlf_trabajo_m">Teléfono Trabajo</label>

					                                <div class="form-group valid-required">

					                                <div class="form-line">

					                                    <input type="text" class="form-control telefono" name="telefono_trabajo_contacto" id="tlf_trabajo_m" placeholder="P. EJ.: +00 (000) 000-00-00" disabled>

					                                </div>

					                       		 </div>

					                    		</div>

					                    		<div class="col-sm-4">

					                                <label for="tlf_fax_m">Teléfono Fax</label>

					                                <div class="form-group valid-required">

					                                <div class="form-line">

					                                    <input type="text" class="form-control telefono" name="telefono_fax" id="tlf_fax_m" placeholder="P. EJ.: +00 (000) 000-00-00" disabled>

					                                </div>

					                       		 </div>

					                    		</div>

					                    		<div class="col-sm-4">

					                                <label for="correo_contacto_m">Correo Electrónico*</label>

					                                <div class="form-group valid-required">

					                                	<div class="form-line">

					                                    	<input type="email" class="form-control guardado" name="correo_contacto" id="correo_contacto_m" placeholder="P. EJ. ejemplo@dominio.com" disabled maxlength="60" onchange="validEmail(this)" style="text-transform: lowercase;">
					                                    	<span class="emailError text-danger"></span>

					                                	</div>

					                        		</div>

					                    		</div>

					                    		<div class="col-sm-4">

					                                <label for="coreo_contactp_opc_m">Correo Electrónico Opcional</label>

					                                <div class="form-group valid-required">

					                                	<div class="form-line">

					                                    	<input type="email" class="form-control" name="coreo_contactp_opc_m" id="coreo_contactp_opc_m" placeholder="P. EJ. ejemplo@dominio.com" maxlength="60" onchange="validEmail(this)"  style="text-transform: lowercase;">
					                                    	<span class="emailError text-danger"></span>

					                                	</div>

					                        		</div>

					                    		</div>	


					                    		<div class="col-sm-12">

					                                <label for="">Obervaciones</label>

					                                <div class="form-group valid-required">

					                                	<div class="form-line">

															<textarea name="observaciones_fisica_view" id="observaciones_fisica_view" class="form-control" disabled></textarea>

					                                	</div>

					                        		</div>

					                    		</div>
											</div>



								        	<div id="datosGeneralMoral">

								        		<!--<fieldset disabled>-->
												<div class="col-sm-4">

					                            		<label for="rfc_m">N° Identificacion</label>

						                                <div class="form-group valid-required">

						                                    <div class="form-line">

						                                        <input type="text" class="form-control mayusculas" name="rfc_m" id="rfc_moral_m" onkeypress='return solosnumerosyletras(event)' maxlength="13" placeholder="P. EJ. BML920313XXX" autocomplete="off"  oninput="validarInputRfc(this)" disabled>

						                                        <span id="resultado" class="curpError text-danger resultado"></span>

						                                    </div>

						                                </div>

					                        	</div>

							        			<div class="col-sm-4">

				                            		<label for="razon_social_m">Razon Social*</label>

					                                <div class="form-group valid-required">

					                                    <div class="form-line">

					                                        <input type="text" class="form-control mayusculas" name="razon_social_m" autocomplete="off" onkeypress='return sololetras(event)' id="razon_social_m" maxlength="30" placeholder="P. EJ.AG SITEMAS" disabled>

				                                    	</div>

				                             		</div>
				                           		</div>
				                           		<div class="col-sm-4">

					                                <label for="tlf_ppalContacto_m">Teléfono Principal*</label>

						                                <div class="form-group valid-required">

						                                    <div class="form-line">

						                                        <input type="text" class="form-control telefono guardado" name="telefono_principal_contacto" id="tlf_ppalContacto_moral_m" placeholder="P. EJ.: +00 (000) 000-00-00" disabled>

					                                    	</div>

					                           		 	</div>
					                    		</div>
					                    		<div class="col-sm-4">

					                                <label for="tlf_fax_m">Teléfono Fax</label>

					                                <div class="form-group valid-required">

				                                    <div class="form-line">

				                                        <input type="text" class="form-control telefono" name="telefono_fax" id="tlf_fax_moral_m" placeholder="P. EJ.: +00 (000) 000-00-00" disabled>

				                                    </div>

				                           		 </div>

				                        		</div>

				                        		<div class="col-sm-4">

					                                <label for="correo_contacto_m">Correo Electrónico*</label>

					                                <div class="form-group valid-required">

				                                    	<div class="form-line">

				                                        	<input type="email" class="form-control guardado" name="correo_contacto" id="correo_contacto_moral_m" placeholder="P. EJ. ejemplo@dominio.com" disabled maxlength="60" onchange="validEmail(this)" >
				                                        	<span class="emailError text-danger"></span>

				                                    	</div>

				                            		</div>

				                        		</div>


				                        		<div class="col-sm-12">

					                                <label for="">Obervaciones</label>

					                                <div class="form-group valid-required">

				                                    	<div class="form-line">

															<textarea name="observaciones_moral_view" id="observaciones_moral_view" class="form-control" disabled></textarea>

				                                    	</div>

				                            		</div>

				                        		</div>
				                        		<!--</fieldset>-->
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

                <form name="form_prospecto_actualizar" id="form_prospecto_actualizar" method="post" enctype="multipart/form-data">

                				<div class="col-sm-6">

	                            		<label for="vendedor_m">Vendedor*</label>

		                                <div class="form-group valid-required">

		                                    <div class="">

		                                        <input type="text" class="form-control mayusculas" name="vendedor_e" autocomplete="off" onkeypress='return sololetras(event)' id="vendedor_e" maxlength="30" placeholder="P. EJ.LUIS RAÚL" disabled>

	                                    	</div>

	                             		</div>

	                           		</div>
	                           		
	                           		<div class="col-sm-6" style="display: none">

	                            		<label for="proyecto_m">Proyecto*</label>

		                                <div class="form-group valid-required">

		                                    <div class="form-line">

		                                        <input type="text" class="form-control mayusculas" name="proyecto_e" autocomplete="off" onkeypress='return sololetras(event)' id="proyecto_e" maxlength="30" placeholder="P. EJ.LUIS RAÚL" disabled>

	                                    	</div>

	                             		</div>

	                           		</div>

                	<div class="form-group" id="tipopersona_editar">

						<label for="tipopersona_editar">Tipo Persona*</label> <br>

						<input type="radio" disabled name="rad_tipoper_editar" id="fisica_editar"  value="fisica">

						<label for= "fisica_editar">Física</label>

						<input type="radio" disabled name="rad_tipoper_editar" id="moral_editar"  value="moral">

						<label for= "moral_editar">Moral</label>

						  <input type="hidden" class="form-control mayusculas" name="id_cliente" id="id_cliente_e"  maxlength="30">

						   <input type="hidden" class="form-control mayusculas" name="tipo_persona_e" id="tipo_persona_e"  maxlength="30">

                        <input type="hidden" class="form-control mayusculas" name="id_datos_personales" id="id_datos_personales_e"  maxlength="30">

                         <input type="hidden" class="form-control mayusculas" name="id_contacto" id="id_contacto_e"  maxlength="30">

                           <input type="hidden" class="form-control mayusculas" name="id_prospecto" id="id_prospecto"  maxlength="30">

                          <div style="border-bottom: 1px solid #ccc;"> </div>

    						 

				                            <div class="col-sm-12"><h3>Datos Generales</h3></div>



			</div>

	      	<div class="tab-content">

		        <div class="tab-pane fade in active" id="datogeneraleditar">

		        	<div id="personaFisica_e">

		        		

		        		<div class="col-sm-4">

                		<label for="rfc_editar">N° Identificacion</label>

                        <div class="form-group valid-required">

                            <div class="form-line">

                                <input type="text" class="form-control mayusculas fisicae" disabled name="rfc_editar" onkeypress='return solosnumerosyletras(event)' id="rfc_editar" placeholder="P. EJ. BML920313XXX">

                            </div>

                        </div>

                	</div>

			       		<div class="col-sm-4">

                		<label for="nombre_prospecto_e">Nombre(s)*</label>

                        <div class="form-group valid-required">

                            <div class="form-line">

                                <input type="text" disabled class="form-control mayusculas fisicae"disablname="nombre_prospecto_e" id="nombre_prospecto_e" placeholder="P. EJ.LUIS RAÚL" onkeypress='return sololetras(event)' required>

                            </div>

                        </div>

                	</div>

                    <div class="col-sm-4">

                		<label for="apellido_paterno_editar">Apellido Paterno*</label>

                        <div class="form-group valid-required">

                            <div class="form-line">

                                <input type="text" class="form-control mayusculas fisicae" disabled name="apellido_paterno" onkeypress='return sololetras(event)' id="apellido_paterno_editar" placeholder="P. EJ. BELLO" required>

                            </div>

                        </div>

                    </div>

                    <div class="col-sm-4">

                		<label for="apellido_materno_editar">Apellido Materno*</label>

                        <div class="form-group valid-required">

                            <div class="form-line">

                                <input type="text" class="form-control mayusculas fisicae" disabled name="apellido_materno" onkeypress='return sololetras(event)' id="apellido_materno_editar" placeholder="P. EJ. MENA" required>

                            </div>

                        </div>

              		</div>

              		

                	<div class="col-sm-4">

                                <label for="tef_ppal_prospecto_e">Teléfono Principal*</label>

	                                <div class="form-group valid-required">

	                                    <div class="form-line">

	                                        <input type="text" class="form-control telefono guardado fisicae" name="telefono_principal_prospecto_e" id="tef_ppal_prospecto_e" placeholder="P. EJ.: +00 (000) 000-00-00" required onkeyup="validPhone(this)">

	                                        		<span class="emailError text-danger"></span>

                                    	</div>

                           		 	</div>

                    		</div>

                            		<div class="col-sm-4">

		                                <label for="tfl_movilContactoer">Teléfono Celular</label>

		                                <div class="form-group valid-required">

	                                    <div class="form-line">

	                                        <input type="text" class="form-control telefono" name="telefono_movil_contacto_e" id="tfl_movilContacto_e" placeholder="P. EJ.: +00 (000) 000-00-00" onkeyup="validPhone(this)">

	                                        		<span class="emailError text-danger"></span>

	                                    </div>

                               		 </div>

                            		</div>

                            		<div class="col-sm-4">

		                                <label for="tlf_casa_e">Teléfono Casa</label>

		                                <div class="form-group valid-required">

	                                    <div class="form-line">

	                                        <input type="text" class="form-control telefono" name="telefono_casa_contacto_e" id="tlf_casa_e" placeholder="P. EJ.: +00 (000) 000-00-00" onkeyup="validPhone(this)">

	                                        		<span class="emailError text-danger"></span>

	                                    </div>

                               		 </div>

                            		</div>

                            		<div class="col-sm-4">

		                                <label for="tlf_trabajo_e">Teléfono Trabajo</label>

		                                <div class="form-group valid-required">

	                                    <div class="form-line">

	                                        <input type="text" class="form-control telefono" name="telefono_trabajo_contacto" id="tlf_trabajo_e" placeholder="P. EJ.: +00 (000) 000-00-00" onkeyup="validPhone(this)">

	                                        		<span class="emailError text-danger"></span>

	                                    </div>

                               		 </div>

                            		</div>

                            		<div class="col-sm-4">

		                                <label for="tlf_fax_e">Teléfono Fax</label>

		                                <div class="form-group valid-required">

	                                    <div class="form-line">

	                                        <input type="text" class="form-control telefono" name="telefono_fax_e" id="tlf_fax_e" placeholder="P. EJ.: +00 (000) 000-00-00" onkeyup="validPhone(this)">

	                                        		<span class="emailError text-danger"></span>

	                                    </div>

                               		 </div>

                            		</div>

                            		<div class="col-sm-4">

		                                <label for="correo_contacto_e"> Correo Electrónico*</label>

		                                <div class="form-group valid-required">

	                                    	<div class="form-line">

	                                        	<input type="email" class="form-control guardado fisicae" name="correo_contacto" id="correo_contacto_e" placeholder="P. EJ. ejemplo@dominio.com" required maxlength="60" onchange="validEmail(this)"  style="text-transform: lowercase;">
	                                        	<span class="emailError text-danger"></span>

	                                    	</div>

                                		</div>

                            		</div>

                            		<div class="col-sm-4">

		                                <label for="coreo_contactp_opc_e">Correo Electrónico Opcional</label>

		                                <div class="form-group valid-required">

	                                    	<div class="form-line">

	                                        	<input type="email" class="form-control" name="coreo_contactp_opc_e" id="coreo_contactp_opc_e" placeholder="P. EJ. ejemplo@dominio.com" maxlength="60" onchange="validEmail(this)"  style="text-transform: lowercase;">
												<span class="emailError text-danger"></span>
	                                    	</div>

                                		</div>

                            		</div>


                            		<div class="col-sm-12">

		                                <label for="">Obervaciones</label>

		                                <div class="form-group valid-required">

	                                    	<div class="form-line">

												<textarea name="observaciones_fisica_editar" id="observaciones_fisica_editar" class="form-control"></textarea>

	                                    	</div>

                                		</div>

                            		</div>





		        	

		        </div>

		        	<div id="personaMoral_e" style="display: none;">

		        		

	                   

                   		<div class="col-sm-4">

                		<label for="rfc_moral_e">N° Identificacion</label>

                        <div class="form-group valid-required">

                            <div class="form-line">

                                <input disabled type="text" class="moralfp form-control mayusculas morale" onkeypress='return solosnumerosyletras(event)' name="rfc_moral_e" id="rfc_moral_e" maxlength="13" placeholder="P. EJ. BML920313XXX">

                            </div>

                        </div>

                		</div>

                		<div class="col-sm-4">

                    		<label for="razon_social_e">Denominación o Razón Social*</label>

                            <div class="form-group valid-required">

                                <div class="form-line">

                                    <input type="text" disabled class="moralfp form-control mayusculas morale" name="razon_social_e" id="razon_social_e" maxlength="30" placeholder="P. EJ.AG SITEMAS" required>

                            	</div>

                     		</div>

                   		</div>

                        

            			<div class="col-sm-4">

            				<label for="correo_moral_e">Correo Electrónico*</label>

                            <div class="form-group valid-required">

                            	<div class="form-line">

                                <input type="email" class="form-control morale" name="correo_moral_e" id="correo_moral_e" placeholder="P. EJ. ejemplo@dominio.com" onchange="validEmail(this)" style="text-transform: lowercase;">
                                <span class="emailError text-danger"></span>

                        		</div>

                			</div>

                   		</div>

                        <div class="col-sm-4">

                    	<label for="telefono_moral_m">Teléfono*</label>

                            <div class="form-group valid-required">

                                <div class="form-line">

                                    <input type="text" class="form-control telefono morale" name="telefono_moral_e" id="telefono_moral_e" placeholder="P. EJ.: +00 (000) 000-00-00" onkeyup="validPhone(this)">

	                                        		<span class="emailError text-danger"></span>

                                </div>

                   		 	</div>	

                        </div>

                        <div class="col-sm-4">

                            <label for="tlf_fax_e">Teléfono Fax</label>

                            <div class="form-group valid-required">

	                            <div class="form-line">

	                                <input type="text" class="form-control telefono" name="telefono_fax" id="tlf_fax_moral_e" placeholder="P. EJ.: +00 (000) 000-00-00" onkeyup="validPhone(this)">

	                                        		<span class="emailError text-danger"></span>

	                            </div>

                   		 	</div>

                        </div>


                        <div class="col-sm-12">

                            <label for="">Obervaciones</label>

                            <div class="form-group valid-required">

                            	<div class="form-line">

									<textarea name="observaciones_moral_editar" id="observaciones_moral_editar" class="form-control"></textarea>

                            	</div>

                    		</div>

                		</div>



		        	</div>

		        </div>

		       

					        </div>																							



		        			<br>

		        			<div class="col-sm-4 col-sm-offset-5">

		                        <button type="button" onclick="regresar('#cuadro4')" id="regreso_editar" class="btn btn-primary waves-effect">Regresar</button>

		                        <input type="submit" value="Guardar" class="btn btn-success waves-effect save-cliente">

		                    </div>

		                </form>

		            </div>

		        </div>

		    </div>

		</div>

	</div>

        <!-- Cierre del cuadro de editar  -->

     

		        <!-- Comienzo del cuadro de registrar  -->

		        

	<div class="row clearfix ocultar" id="cuadro5">

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

            <div class="card">

                <div class="header">

                    <h2>Registro de <?php echo $breadcrumbs[0]["nombre_lista_vista"]; ?> a Cliente</h2>

                </div>

                <div class="body">

                	<div class="table-responsive">

                        <form name="form_clientePagador_registrar" id="form_clientePagador_registrar" method="post" enctype="multipart/form-data">

							<div class="form-group" id="tipo_cliente">

    							<label for="tipo_cliente">Tipo Persona*</label> <br>

    							<input type="radio" checked name="rad_tipoper_cliente" id="fisica_cliente" value="fisica_cliente">

    							<label for= "fisica_cliente">Física</label>

    							<input type="radio" name="rad_tipoper_cliente" id="moral_cliente"  value="moral_cliente">

    							<label for= "moral_cliente">Moral</label>

    						</div>  

    						<input type="hidden" class="form-control mayusculas" name="id_cliente" id="id_cliente_cliente"  maxlength="30">  

    						<input type="hidden" class="form-control mayusculas" name="id_datos_personales" id="id_datos_personales_cliente"  maxlength="30">

							<input type="hidden" class="form-control mayusculas" name="id_contacto" id="id_contacto_cliente"  maxlength="30">

							<input type="hidden" class="form-control mayusculas" name="id_proyecto" id="id_proyecto_cliente"  maxlength="30">        

							<input type="hidden" class="form-control mayusculas" name="id_vendedor" id="id_vendedor_cliente"  maxlength="30">  

							<input type="hidden" class="form-control mayusculas" name="id_prospecto" id="id_prospecto_cliente"  maxlength="30">                    





					        <ul class="nav nav-tabs">

					        	<li id="tab0" class="active"><a href="#datogeneralCliente" data-toggle="tab" >Datos Generales*</a></li>

					        	<li id="tab1"><a href="#domicilio" data-toggle="tab" class="pestanaDomicilio" >Domicilio*</a></li>

					        	<li id="tab2"><a href="#replegal" data-toggle="tab" style="display:none" class="pestana_replegal">Rep Legal</a></li>

					        	<li id="tab3"><a href="#cuenta" data-toggle="tab" class="pestanaCuenta" >Cuenta</a></li>

					        	<li id="tab4"><a href="#contacto" data-toggle="tab" class="pestanaContacto">Contacto</a></li>

					        </ul> 





					        <div class="tab-content">

					        	<div class="tab-pane fade in active tab_content0" id="datogeneralCliente">

					        	    <div id="personaFisicaCliente">
					        	    	<!-- -->
					        	    	<div class="col-sm12">
						        	    	<div class="col-sm-8 img_profile">
				                                <label for="">Imagen Cliente*</label>
				                                <input 
				                                	type="file" autocomplete="off" 
				                                	class="file-img" 
				                                	data-msg-placeholder="Selecciona un {files} ..." 
				                                	id="cliente_img" 
				                                	name=""
				                                	>        
				                            </div>
								        	<div class="col-sm-4">

			                            		<label for="nombre_cliente">Nombre(s)*</label>

				                                <div class="form-group valid-required">

				                                    <div class="form-line">

				                                        <input type="text" class="fisicac form-control mayusculas" name="nombre_cliente" autocomplete="off" onkeypress='return sololetras(event)' id="nombre_cliente" maxlength="30" placeholder="P. EJ.LUIS RAÚL" required>

			                                    	</div>

			                             		</div>

			                           		</div>

			                           		<div class="col-sm-4">

		                            			<label for="apellido_paterno_cliente">Apellido Paterno*</label>

			                                	<div class="form-group valid-required">

			                                    	<div class="form-line">

			                                        	<input type="text" class="fisicac form-control mayusculas" name="apellido_paterno_cliente" autocomplete="off" onkeypress='return sololetras(event)' maxlength="15" id="apellido_paterno_cliente" placeholder="P. EJ. BELLO" required>

			                                    	</div>

			                                	</div>

			                             	</div>

			                             	<div class="col-sm-4">

			                            		<label for="apellido_materno_cliente">Apellido Materno*</label>

				                                <div class="form-group valid-required">

				                                    <div class="form-line">

				                                        <input type="text" class="fisicac form-control mayusculas" name="apellido_materno_cliente" autocomplete="off" maxlength="15" onkeypress='return sololetras(event)' id="apellido_materno_cliente" placeholder="P. EJ. MENA" required>

				                                    </div>

			                                	</div>

		                          			</div>

		                          			<div class="col-sm-4">

			                            		<label for="rfc">N° Identificacion*</label>

				                                <div class="form-group valid-required">

				                                    <div class="form-line">

				                                        <input type="text" class="fisicac form-control mayusculas" name="rfc" id="rfc" onkeypress='return solosnumerosyletras(event)' maxlength="13" placeholder="P. EJ. BML920313XXX" autocomplete="off" required>

				                                       

				                                    </div>

				                                </div>

		                            		</div>

				                           	<div class="col-sm-4">

		                            			<label for="actividad_economica">Actividad Economica*</label>

			                               		<select name="actividad_economica" id="actividad_economica_registrar" required class="fisicac form-control">

	                            					<option value="" selected>Seleccione</option>

	                            						<?php foreach ($actividadesEconomicas as $actividadEconomica): ?>

	                            					<option value="<?=$actividadEconomica->id_lista_valor;?>"><?=$actividadEconomica->nombre_lista_valor;?></option>

	                            						<?php endforeach ?>

	                            				</select>

		                            		</div>

				                            <div class="col-sm-4">

				                                <label for="curp_datos_personales_registrar">C.U.R.P.*</label>

				                                <div class="form-group form-float">

				                                    <div class="form-line" id="validCurp">

				                                        <input type="text" class="fisicac form-control mayusculas" autocomplete="off" name="curp_datos_personales" id="curp_datos_personales_registrar" placeholder="P. EJ. BML920313HMLNNSOS" maxlength="18" onkeypress='return solosnumerosyletras(event)' required oninput="validarInputCurp(this)" >

				                                    </div>

			                                	   	<span class="curpError text-danger"></span>

			                               		</div>

			                            	</div>
			                            </div>		
		                            	<!-- -->
	                            		<div class="col-sm-4">

	                                		<label for="fecha_nac_datos_personales_registrar">Fecha de Nacimiento*</label>

	                                		  <div class="form-group valid-required">

				                                    <div class="form-line input-group fecha">

				                                        <input type="text" class="form-control fisicac" name="fecha_nac_datos_personales" autocomplete="off" id="fecha_nac_datos_personales_registrar" placeholder="dd-mm-yyyy" required max="<?= date("Y-m-d")?>">

				                                        <span class="input-group-addon">

									                        <span class="glyphicon glyphicon-calendar"></span>

									                    </span>

				                                    </div>

				                                </div>

				                            </div>

	                                      <div class="col-sm-4">

			                                <label for="correo_usuario_registrar">Correo Electrónico*</label>

			                                <div class="form-group valid-required">

		                                    	<div class="form-line">

		                                        <input type="email" class="form-control fisicac" autocomplete="off" name="correo_clente" id="correo_cliente_registrar" placeholder="P. EJ. ejemplo@dominio.com" required onchange="validEmail(this)" style="text-transform: lowercase;">
		                                        
	                                        	<span class="emailError text-danger"></span>

	                                    		</div>

                                			</div>

                            			</div>

	                            		<div class="col-sm-4">

			                                <label for="telefono_registrar">Teléfono*</label>

				                                <div class="form-group valid-required">

				                                    <div class="form-line">

				                                        <input type="text" class="form-control telefono fisicac" name="telefono_cliente" id="telefono_registrar" placeholder="P. EJ.: +00 (000) 000-00-00" required onkeyup="validPhone(this)">

		                                        		<span class="emailError text-danger"></span>

				                                    </div>

		                               		 	</div>

	                            			</div>

		                            		<div class="col-sm-4">

	                            				<label for="pais_nacionalidad_registrar">País de Nacionalidad*</label>

	                            				  <div class="valid-required">

	                                				<select name="pais_nacionalidad" id="pais_nacionalidad_registrar" required class="fisicac form-control">
														<
	                                					<option value="" selected>Seleccione</option>

	                                					<?php foreach ($nacionalidades as $nacionalidad): ?>

	                                						<option value="<?=$nacionalidad->id_lista_valor;?>"><?=$nacionalidad->nombre_lista_valor;?></option>

	                                					<?php endforeach ?>
	                                				</select>

	                                			</div>

			                           	</div>

	                           			<div class="col-sm-4">

                            				<label for="pais_origen_registrar">País Origen*</label>

                            				<div class="valid-required">

                                				<select name="pais_origen" id="pais_origen_registrar" required class="fisicac form-control">

                                					<option value="" selected>Seleccione</option>

                                						<?php foreach ($nacionalidades as $nacionalidad): ?>

                                					<option value="<?=$nacionalidad->id_lista_valor;?>"><?=$nacionalidad->nombre_lista_valor;?></option>

                                						<?php endforeach ?>

                                				</select>

                                			</div>

	                           			</div>
										<div class="col-sm-8 col-md-3 col-lg-3 col-xs-3 form-group img-n-identificacion">

			                                <label for="">Copia escaneada del N° Identificacion*</label>

			                                <input 

			                                	type="file" autocomplete="off" 

			                                	class="file-rfc" 

			                                	data-msg-placeholder="Selecciona un {files} ..." 

			                                	id="rfc_img" name="" 

			                                	

			                                	> 



			                                	<!--<input type="hidden" name="rfc_img" id="rfc_img">-->       

			                            </div>

						        	</div>
									<div id="personaMoralCliente">
											<div class="col-sm-8 img_profile">
				                                <label for="">Imagen Cliente*</label>
				                                <input 
				                                	type="file" autocomplete="off" 
				                                	class="file-img" 
				                                	data-msg-placeholder="Selecciona un {files} ..." 
				                                	id="cliente_img_moral" 
				                                	name=""
				                                	>        
				                            </div>
							        		<div class="col-sm-4">

			                            		<label for="razon_social">Denominación o Razón Social*</label>

				                                <div class="form-group valid-required">

				                                    <div class="form-line">

				                                        <input type="text" class="moralf form-control mayusculas" name="razon_social" id="razon_social" maxlength="30" placeholder="P. EJ.AG SITEMAS">

			                                    	</div>

			                             		</div>

			                           		</div>

			                           		<div class="col-sm-4">

			                            		<label for="rfc_moral">N° Identificacion</label>

				                                <div class="form-group valid-required">

				                                    <div class="form-line">

				                                        <input type="text" class="moralf form-control mayusculas" name="rfc_moral" id="rfc_moral_cliente" onkeypress='return solosnumerosyletras(event)' maxlength="13" placeholder="P. EJ. BML920313XXX">

				                                    </div>

				                                </div>

		                            		</div>

				                            <div class="col-sm-4" >

			                            		<label for="fecha_cons_r">Fecha Constitución*</label>

		                                		<div class="form-group valid-required">

		                                   			 <div class="form-line input-group fecha">

		                                        		<input type="text" class="moralf form-control" name="fecha_cons_r" id="fecha_cons_r" placeholder="dd-mm-yyyy" max="<?= date("Y-m-d")?>">

		                                        		<span class="input-group-addon">

							                       			 <span class="glyphicon glyphicon-calendar"></span>

							                   		 	</span>

		                                   			 </div>

		                                		</div>

				                            </div>

				                            <div class="col-sm-4">

		                                			<label for="actividad_economica">Giro Mercantil*</label>

					                               <select name="giro_mercantil_r" id="giro_mercantil_r" class="moralf form-control">

			                            					<option value="" selected>Seleccione</option>

			                            						<?php foreach ($giros as $giro): ?>

			                            					<option value="<?=$giro->id_lista_valor;?>"><?=$giro->nombre_lista_valor;?></option>

			                            						<?php endforeach ?>

			                            				</select>

	                            			</div>

	                            			<div class="col-sm-4">

		                            			<label for="correo_moral_m">Correo Electrónico*</label>

				                                <div class="form-group valid-required">

			                                    	<div class="form-line">

			                                        <input type="email" class="form-control moralf" name="correo_moral_m" id="correo_moral_m" placeholder="P. EJ. ejemplo@dominio.com" onchange="validEmail(this)" style="text-transform: lowercase;">
			                                        <span class="emailError text-danger"></span>

		                                    		</div>

	                                			</div>

		                            		</div>

				                            <div class="col-sm-4">

			                            		<label for="telefono_moral_m">Teléfono*</label>

				                                <div class="form-group valid-required">

				                                    <div class="form-line">

				                                        <input type="text" class="moralf form-control telefono" name="telefono_moral_m" id="telefono_moral_m" placeholder="P. EJ.: +00 (000) 000-00-00" onkeyup="validPhone(this)">

		                                        		<span class="emailError text-danger"></span>

				                                    </div>

		                               		 	</div>

	                            			</div>

	                            			<div class="col-sm-4">

		                            		 	<label for="rfc">Acta Constitutiva y en su caso los cambios realizados*</label>

				                                <div class="form-group valid-required">

				                                    <div class="form-line">

				                                        <input type="text" class="moralf form-control mayusculas" name="acta_constutiva_r" id="acta_constutiva_r" maxlength="15" placeholder="P. EJ. ALB520">

				                                    </div>

				                                </div>
											</div>

		                            		<div class="col-sm-12 img-n-identificacion">
				                               <label for="rfc_imag_mo">Copia escaneada del N° Identificacion*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="file" class="form-control" data-show-upload="false" placeholder="Copia escaneada del RFC" name="" id="rfc_imag_mo">
				                                    </div>
				                                    <!--<input type="hidden" name="rfc_imag_mo" id="rfc_imag_mo">-->
				                                </div>
				                            </div>

	                            			<div class="col-sm-12" id="file-acta">

			                                	<label for="acta_img_r">Copia escaneada del Acta Constitutiva*</label>

				                                <div class="form-group">

				                                    <div class="form-line">

				                                        <input type="file" class="form-control file-acta" data-show-upload="false"  placeholder="Copia escaneada del Acta constitutiva" name="" id="acta_img_r" >

				                                    </div>

				                                    <!-- <input type="hidden" name="acta_img_r" > -->





				                                </div> 

		                          			</div>

				                    </div>
								</div>
								<div class="tab-pane fade tab_content1" id="domicilio">

						        	<div class="col-sm-4">

		                                <label for="calle_contacto_registrar">Calle*</label>

		                                <div class="form-group valid-required">

		                                    <div class="form-line">

		                                        <input type="text" class="form-control mayusculas" name="calle_cliente" id="calle_contacto_registrar" placeholder="P. EJ. PRIMAVERA" required>

		                                    </div>

		                                </div>

		                            </div>

		                            <div class="col-sm-4">

		                                <label for="exterior_contacto_registrar">Número Exterior*</label>

		                                <div class="form-group valid-required">

		                                    <div class="form-line">

		                                        <input type="text" class="form-control mayusculas" name="exterior_cliente" maxlength="30" onkeypress='return solosnumerosyletras(event)' id="exterior_contacto_registrar" placeholder="P. EJ. 33" maxlength="10" required>

		                                    </div>

		                                </div>

		                            </div>

		                            <div class="col-sm-4">

		                                <label for="interior_contacto_registrar">Número Interior</label>

		                                <div class="form-group">

		                                    <div class="form-line">

		                                        <input type="text" class="form-control mayusculas interior_contacto_registrar" onkeypress='return solosnumerosyletras(event)' name="interior_cliente" id="interior_contacto_registrar" placeholder="P. EJ. 2" maxlength="10">

		                                    </div>

		                                </div>

		                            </div>

		                            <div class="col-sm-4">

		                                <label for="codigo_postal_registrar">Código Postal*</label>

		                                <div class="form-group valid-required">

		                                    <div class="form-line">

		                                        <input type="text" class="form-control" id="codigo_postal_registrar" onkeypress='return codigoPostal(event)' name="codigo_postal_domicilio" maxlength="5" onchange="buscarCodigosUs(this.value, 'create')" required>

		                                    </div>

		                                </div>

		                            </div>

		                            <div class="col-sm-4">

		                            	<label for="colonia_registrar">Colonia*</label>

		                            	<div class="valid-required">

		                                <select id="colonia_registrar" class="form-control form-group" name="colonia" >

			                                	<option value="">Seleccione</option>

			                                </select>

			                            </div>

		                            </div>

		                            <div class="col-sm-4"  style="padding-bottom: 10px;">

		                                <label for="municipio_registrar">Municipio*</label>

		                                <div class="valid-required">

			                                <select id="municipio_registrar" class="form-control form-group" name="municipio" >

			                                	<option value="">Seleccione</option>

			                                </select>

			                            </div>

		                            </div>

		                            <div class="col-sm-4">

		                                <label for="ciudad_registrar">Ciudad*</label>

		                                <div class="valid-required">

			                                <select id="ciudad_registrar" class="form-control form-group" name="ciudad" >

			                                	<option value="">Seleccione</option>

			                                </select>

			                            </div>

		                            </div>

		                            <div class="col-sm-4">

		                                <label for="estado_registrar">Estado*</label>

		                                <div class="valid-required">

			                                <select id="estado_registrar" class="form-control form-group" name="estado" >

			                                	<option value="">Seleccione</option>

			                                </select>

			                            </div>

		                            </div>

		                            <div class="col-sm-12 col-md-3 col-lg-3 col-xs-3 form-group" id="domicilio_regis">

			                            		<label for="domicilio_fiscal_img">Copia escaneada del Domicilio Fiscal*</label>

				                                <div class="form-group valid-required">

				                                    <div class="form-line">

				                                        <input type="file" class="form-control file-domicilio"  placeholder="Copia escaneada del Domicilio Fiscal" data-show-upload="false"  name="" id="domicilio_fiscal_img">

				                                    </div>

				                                </div>





				                                <!--<input type="hidden" name="domicilio_fiscal_img" id="domicilio_fiscal_img">-->

				                    </div>

					        	</div>





					        	<div class="tab-pane fade tab_content2" id="replegal">

						        	<div class="col-sm-4">

		                            		<label for="nombre_representante">Nombre(s)*</label>

			                                <div class="form-group valid-required">

			                                    <div class="form-line">

			                                        <input type="text" class="form-control mayusculas" name="nombre_representante" id="nombre_representante" placeholder="P. EJ. LUIS RAÚL" onkeypress='return sololetras(event)'>

			                                    </div>

			                                </div>

		                            	</div>

			                            <div class="col-sm-4">

		                            		<label for="apellido_paterno_rep">Apellido Paterno*</label>

			                                <div class="form-group valid-required">

			                                    <div class="form-line">

			                                        <input type="text" class="form-control mayusculas" name="apellido_paterno_rep" id="apellido_paterno_rep" placeholder="P. EJ. BELLO" onkeypress='return sololetras(event)'>

			                                    </div>

			                                </div>

			                            </div>

			                            <div class="col-sm-4">

		                            		<label for="apellido_materno_rep">Apellido Materno*</label>

			                                <div class="form-group valid-required">

			                                    <div class="form-line">

			                                        <input type="text" class="form-control mayusculas" name="apellido_materno_rep" id="apellido_materno_rep" placeholder="P. EJ. MENA" onkeypress='return sololetras(event)'>

			                                    </div>

			                                </div>

		                          		</div>

		                          		<div class="col-sm-4">

		                            		<label for="rfc_representante">N° Identificacion</label>

			                                <div class="form-group valid-required">

			                                    <div class="form-line">

			                                        <input type="text" class="form-control mayusculas file-legal" name="rfc_representante" onkeypress='return solosnumerosyletras(event)' id="rfc_representante" maxlength="13" placeholder="P. EJ. BML920313XXX">



			                                    </div>

			                                </div>

		                            	</div>

			                            <div class="col-sm-4">

		                            		  <div class="form-group valid-required">

	                                    <div class="form-line">

	                                <label for="telf_rep_legal">Teléfono*</label>

	                                        <input type="text" class="form-control telefono" name="telf_rep_legal" id="telf_rep_legal" placeholder="P. EJ.: +00 (000) 000-00-00" onkeyup="validPhone(this)">

	                                        		<span class="emailError text-danger"></span>

	                                    </div>

	                                </div>

			                            </div>

			                            <div class="col-sm-4">

			                                <label for="curp_rep_legal_registrar">C.U.R.P.*</label>

			                                <div class="form-group form-float">

			                                    <div class="form-line" id="validCurp">

			                                        <input type="text" class="moralf form-control mayusculas" name="curp_rep_legal" id="curp_rep_legal_registrar" placeholder="P. EJ. BML920313HMLNNSOS" maxlength="18" onkeypress='return solosnumerosyletras(event)'  oninput="validarInputCurp(this)"  >

			                                    </div>

			                                    <span class="curpError text-danger"></span>

			                                </div>

			                            </div>

	                            		<!--<div class="col-sm-4">

	                              		<label for="rfc_img_rep">Copia escaneada del N° Identificacion</label>

			                                <div class="form-group valid-required">

			                                    <div class="form-line">

			                                        <input type="file" class="form-control mayusculas" data-show-upload="false"  placeholder="Copia escaneada del RFC"  id="rfc_img_rep_file" name="rfc_img_rep_file">

			                                    </div>



			                                    <input type="text" name="rfc_img_rep" id="rfc_img_rep">





			                                </div>

	                            		</div>-->
	                            		<div class="col-sm-4">

	                              		<label for="rfc_img_rep">Copia escaneada del N° Identificacion*</label>

			                                <div class="form-group valid-required">

			                                    <div class="form-line">
			                                        <input type="file" class="form-control mayusculas" data-show-upload="false"  placeholder="Copia escaneada del RFC"  id="rfc_img_rep" name="">
			                                    </div>
			                                    
			                                    <!--<input type="hidden" name="rfc_img_rep" id="rfc_img_rep">-->
											</div>

	                            		</div>


			                            <div class="col-sm-4">

			                                <label for="correo_rep_legal">Correo Electrónico*</label>

			                                <div class="form-group valid-required">

	                                    	<div class="form-line">

	                                        <input type="email" class="form-control" name="correo_rep_legal" id="correo_rep_legal" placeholder="P. EJ. ejemplo@dominio.com" onchange="validEmail(this)" style="text-transform: lowercase;">

	                                        	<span class="emailError text-danger"></span>

	                                    	</div>

	                                		</div>

	                            		</div>





					        	</div>

					        <div class="tab-pane fade tab_content3" id="cuenta">

					        	<div class="col-sm-4">

                                	<label for="clabe_registrar">CLABE*</label>

	                                <div class="form-group valid-required">

	                                    <div class="form-line">

	                                        <input type="text" class="form-control mayusculas guardado" name="clabe" id="clabe_registrar" onkeypress="return valida(event)" placeholder="P. EJ. 00211501600326941" maxlength="14" minlength="14">

	                                    </div>

                                	</div>

                           		</div>

	                            <div class="col-sm-4">

	                                <label for="numero_cuenta_registrar">Número de Cuenta</label>

	                                <div class="form-group valid-required">

	                                    <div class="form-line">

	                                        <input type="text" class="form-control" id="numero_cuenta_registrar" name="numero_cuenta"  onkeypress='return solonumeros(event)' placeholder="P. EJ. 016001326941" maxlength="15">

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

	                                <div class="form-group valid-required">

	                                    <div class="form-line">

	                                        <input type="text" class="form-control mayusculas" id="swift_registrar" name="swift"  onkeypress='return solosnumerosyletras(event)' placeholder="P. EJ. INGBMXMN" maxlength="15">

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

	                            

	                            <div class="col-sm-4">

	                                <label for="sucursal_registrar">Sucursal</label>

	                                <div class="form-group valid-required">

	                                    <div class="form-line">

	                                        <input type="text" class="form-control mayusculas" id="sucursal_registrar" name="sucursal" placeholder="P. EJ SICOMOROS CHIH" maxlength="30">

	                                    </div>

	                                </div>

					   		 	</div>		

				       	</div>

				       	<div class="tab-pane fade tab_content4" id="contacto">

					       	<div class="col-sm-4">

                           		<label for="nombre_contacto">Nombre Contacto*</label>

	                            <div class="form-group valid-required">

	                	            <div class="form-line">

	                                   <input type="text" class="form-control mayusculas guardado" name="nombre_contacto" onkeypress='return sololetras(event)' id="nombre_contacto" placeholder="P. EJ.LUIS RAÚL"  maxlength="100">

                                    </div>

                                </div>

                             </div>

                             <div class="col-sm-4">

                                <label for="tlf_ppalContacto_r">Teléfono Principal*</label>

	                                <div class="form-group valid-required">

	                                    <div class="form-line">

	                                        <input type="text" class="form-control telefono guardado" name="telefono_principal_contacto" id="tlf_ppalContacto_r" placeholder="P. EJ.: +00 (000) 000-00-00" onkeyup="validPhone(this)">

	                                        		<span class="emailError text-danger"></span>

                                    	</div>

                           		 	</div>

                    		</div>

                            		<div class="col-sm-4">

		                                <label for="tfl_movilContacto_r">Teléfono Celular</label>

		                                <div class="form-group valid-required">

	                                    <div class="form-line">

	                                        <input type="text" class="form-control telefono" name="telefono_movil_contacto" id="tfl_movilContacto_r" placeholder="P. EJ.: +00 (000) 000-00-00" onkeyup="validPhone(this)">

	                                        		<span class="emailError text-danger"></span>

	                                    </div>

                               		 </div>

                            		</div>

                            		<div class="col-sm-4">

		                                <label for="tlf_casa_r">Teléfono Casa</label>

		                                <div class="form-group valid-required">

	                                    <div class="form-line">

	                                        <input type="text" class="form-control telefono" name="telefono_casa_contacto" id="tlf_casa_r" placeholder="P. EJ.: +00 (000) 000-00-00" onkeyup="validPhone(this)">

	                                        		<span class="emailError text-danger"></span> 

	                                    </div>

                               		 </div>

                            		</div>

                            		<div class="col-sm-4">

		                                <label for="tlf_trabajo_r">Teléfono Trabajo</label>

		                                <div class="form-group valid-required">

	                                    <div class="form-line">

	                                        <input type="text" class="form-control telefono" name="telefono_trabajo_contacto" id="tlf_trabajo_r" placeholder="P. EJ.: +00 (000) 000-00-00" onkeyup="validPhone(this)">

	                                        		<span class="emailError text-danger"></span>

	                                    </div>

                               		 </div>

                            		</div>

                            		<div class="col-sm-4">

		                                <label for="tlf_fax_r">Teléfono Fax</label>

		                                <div class="form-group valid-required">

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

	                                        	<input type="email" class="form-control guardado" name="correo_contacto" id="correo_contacto_r" placeholder="P. EJ. ejemplo@dominio.com"  maxlength="60" onchange="validEmail(this)" style="text-transform: lowercase;">

	                                        	<span class="emailError text-danger"></span>

	                                    	</div>

                                		</div>

                            		</div>

                            		<div class="col-sm-4">

		                                <label for="coreo_contactp_opc_r">Correo Electrónico Opcional</label>

		                                <div class="form-group valid-required">

	                                    	<div class="form-line">

	                                        	<input type="email" class="form-control" name="coreo_contactp_opc_r" id="coreo_contactp_opc_r" placeholder="P. EJ. ejemplo@dominio.com" maxlength="60" onchange="validEmail(this)" style="text-transform: lowercase;">
	                                        	<span class="emailError text-danger"></span>

	                                    	</div>

                                		</div>

                            		</div>

				        </div>

				       

				       	 		<div class="col-sm-4 col-sm-offset-5">

                                    <button type="button" onclick="regresar('#cuadro5')" class="btn btn-primary waves-effect">Regresar</button>

                                    <input type="submit" value="Guardar" id="send" class="btn btn-success waves-effect save-cliente">

                            	</div>

					</div>

					   </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

		        <!-- Cierre del cuadro de registrar -->

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

<script src="<?=base_url();?>assets/template/plugins/momentjs/moment.js"></script>

   

    <script src="<?=base_url();?>assets/template/plugins/bootstrap-datetimepicker-master/js/bootstrap-datetimepicker.min.js"></script>

    <script src="<?=base_url();?>assets/template/plugins/jquery-inputmask/jquery.inputmask.bundle.js"></script>

    <script src="<?=base_url();?>assets/template/plugins/momentjs/moment.js"></script>

    <script src="<?=base_url();?>assets/template/plugins/jquery-validation/jquery.validate.js"></script>

    <script src="<?=base_url();?>assets/template/plugins/jquery-validation/additional-methods.js"></script>
   	
   	<script src="<?=base_url();?>assets/cpanel/Productos/js/numeral/min/numeral.min.js"></script>

    <script src="<?=base_url();?>assets/template/plugins/bootstrap-fileinput/js/plugins/piexif.min.js" type="text/javascript"></script>

    <script src="<?=base_url();?>assets/template/plugins/bootstrap-fileinput/js/plugins/purify.min.js" type="text/javascript"></script>

    <script src="<?=base_url();?>assets/template/plugins/bootstrap-fileinput/js/plugins/sortable.min.js" type="text/javascript"></script>



    <script src="<?=base_url();?>assets/cpanel/Productos/js/numeral/min/numeral.min.js"></script>

    

    

    <script src="<?=base_url();?>assets/template/plugins/bootstrap-fileinput/js/fileinput.js" type="text/javascript"></script>

    <script type="text/javascript" src="<?=base_url();?>assets/template/plugins/bootstrap-fileinput/themes/fa/theme.min.js"></script>

    <script src="<?=base_url();?>assets/template/plugins/bootstrap-fileinput/js/locales/es.js" type="text/javascript"></script>

 



     



    <script src="<?=base_url();?>assets/cpanel/Prospecto/js/prospecto.js"></script>

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

