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
		                                         	<th style="width: 17%;">Acciones</th>
		                                            <th>Nombre</th>
		                                            <th>N° Identificacion</th>
		                                            <th>Tipo Persona</th>
													<th>Empresa</th>
		                                            <th>Fecha de Registro</th>
		                                            <th>Registrado Por</th>
		                                        </tr>
		                                    </thead>
		                                    <tbody></tbody>
		                                </table>
		                                <div class="col-md-2 eliminar ocultar">
		                                	<button class="btn btn-danger waves-effect" onclick="eliminarMultiple('ClientePagador/eliminar_multiple')">Eliminar seleccionados</button>
		                                </div>
		                                <div class="col-md-2 actualizar ocultar">
		                                	<button class="btn btn-warning waves-effect" onclick="statusMultiple('ClientePagador/status_multiple', 1, 'activar')">Activar seleccionados</button>
		                                </div>
		                                <div class="col-md-2 actualizar ocultar">
		                                	<button class="btn btn-warning waves-effect" onclick="statusMultiple('ClientePagador/status_multiple', 2, 'desactivar')">Desactivar seleccionados</button>
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
                        <form name="form_clientePagador_registrar" id="form_clientePagador_registrar" method="post" enctype="multipart/form-data">
                           	<div class="form-group" id="tipopersona">
    							<label for="tipopersona">Tipo Persona*</label> <br>
    							<input type="radio" checked name="rad_tipoper" id="fisica"  value="fisica">
    							<label for= "fisica">Física</label>
    							<input type="radio" name="rad_tipoper" id="moral"  value="moral">
    							<label for= "moral">Moral</label>
    						</div>

					        <ul class="nav nav-tabs">
					        	<li id="tab0" class="active"><a href="#datogeneral" data-toggle="tab" >Datos Generales*</a></li>
					        	<li id="tab1"><a href="#domicilio" data-toggle="tab" class="pestanaDomicilio" >Domicilio*</a></li>
					        	<li id="tab2"><a href="#replegal" data-toggle="tab" style="display:none" class="pestana_replegal">Rep Legal</a></li>
					        	<li id="tab3"><a href="#cuenta" data-toggle="tab" class="pestanaCuenta" >Cuenta</a></li>
					        	<li id="tab4"><a href="#contacto" data-toggle="tab" class="pestanaContacto">Contacto</a></li>
					        </ul> 

					        <div class="tab-content">
					        	<div class="tab-pane fade in active tab_content0" id="datogeneral">
						        	
					        	    <div id="personaFisica">
					        	    	<div class="col-sm-8 img_profile">
			                                <label for="">Imagen Cliente*</label>
			                                <input 
			                                	type="file" autocomplete="off" 
			                                	class="file-img fileeditar" 
			                                	data-msg-placeholder="Selecciona un {files} ..." 
			                                	id="cliente_img" 
			                                	name=""
			                                	>        
			                            </div>
							        	<div class="col-sm-4">
		                            		<label for="nombre_cliente">Nombre(s)*</label>
			                                <div class="form-group valid-required">
			                                    <div class="form-line">
			                                        <input type="text" class="fisicaf form-control mayusculas" name="nombre_cliente" autocomplete="off" onkeypress='return sololetras(event)' id="nombre_cliente" maxlength="30" placeholder="P. EJ.LUIS RAÚL" required>
		                                    	</div>
		                             		</div>
		                           		</div>
		                           		<div class="col-sm-4">
	                            			<label for="apellido_paterno_cliente">Apellido Paterno*</label>
		                                	<div class="form-group valid-required">
		                                    	<div class="form-line">
		                                        	<input type="text" class="fisicaf form-control mayusculas" name="apellido_paterno_cliente" autocomplete="off" onkeypress='return sololetras(event)' maxlength="15" id="apellido_paterno_cliente" placeholder="P. EJ. BELLO" required>
		                                    	</div>
		                                	</div>
		                             	</div>
		                             	<div class="col-sm-4">
		                            		<label for="apellido_materno_cliente">Apellido Materno*</label>
			                                <div class="form-group valid-required">
			                                    <div class="form-line">
			                                        <input type="text" class="fisicaf form-control mayusculas" name="apellido_materno_cliente" autocomplete="off" maxlength="15" onkeypress='return sololetras(event)' id="apellido_materno_cliente" placeholder="P. EJ. MENA" required>
			                                    </div>
		                                	</div>
	                          			</div>
	                          			<div class="col-sm-4">
	                            		<label for="rfc">N° Identificacion*</label>
		                                <div class="form-group valid-required">
		                                    <div class="form-line">
		                                        <input type="text" class="fisicaf form-control mayusculas" name="rfc" id="rfc" onkeypress='return solosnumerosyletras(event)' maxlength="13" placeholder="P. EJ. BML920313XXX" autocomplete="off" required oninput="validarInputRfc(this)">
		                                        <span id="resultado" class="curpError text-danger resultado"></span>
		                                    </div>
		                                </div>
	                            		</div>
			                            <div class="col-sm-4">
	                            	   		<label for="actividad_economica">Actividad Economica*</label>
		                               		<select name="actividad_economica" id="actividad_economica_registrar" required class="fisicaf form-control">
                            					<option value="" selected>Seleccione</option>
                            						<?php foreach ($actividadesEconomicas as $actividadEconomica): ?>
                            					<option value="<?=$actividadEconomica->id_lista_valor;?>"><?=$actividadEconomica->nombre_lista_valor;?></option>
                            						<?php endforeach ?>
                            				</select>
	                            		</div>


										<div class="col-sm-4">
	                            	   		<label for="actividad_economica">Empresa al cual pertenece*</label>
		                               		<select name="empresa_pertenece" id="empresa_pertenece" required class="fisicaf form-control">
                            					<option value="" selected>Seleccione</option>
                            				</select>
	                            		</div>



			                            <div class="col-sm-4">
			                                <label for="curp_datos_personales_registrar">C.U.R.P.*</label>
			                                <div class="form-group form-float">
			                                    <div class="form-line" id="validCurp">
			                                        <input type="text" class="fisicaf form-control mayusculas" autocomplete="off" name="curp_datos_personales" id="curp_datos_personales_registrar" placeholder="P. EJ. BML920313HMLNNSOS" maxlength="18" onkeypress='return solosnumerosyletras(event)' required oninput="validarInputCurp(this)" >
			                                    </div>
		                                	   	<span class="curpError text-danger"></span>
		                               		</div>
		                            	</div>
		                            	
	                            		<div class="col-sm-4">
	                                		<label for="fecha_nac_datos_personales_registrar">Fecha de Nacimiento*</label>
	                                		<div class="form-group valid-required">
			                                    <div class="form-line input-group fecha">
			                                        <input type="text" class="form-control fisicaf" name="fecha_nac_datos_personales" autocomplete="off" id="fecha_nac_datos_personales_registrar" placeholder="dd-mm-yyyy" required>
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
		                                        <input type="email" class="form-control fisicaf" autocomplete="off" name="correo_clente" id="correo_cliente_registrar" placeholder="P. EJ. ejemplo@dominio.com" required onchange="validEmail(this)">

	                                        	<span class="emailError text-danger"></span>
	                                    		</div>
                                			</div>
                            			</div>
                            			<div class="col-sm-4">
		                                	<label for="telefono_registrar">Teléfono*</label>
			                                <div class="form-group valid-required">
			                                    <div class="form-line">
			                                        <input type="text" class="form-control telefono fisicaf" name="telefono_cliente" id="telefono_registrar" placeholder="P. EJ.: +00 (000) 000-00-00" required onkeyup="validPhone(this)">
	                                        		<span class="emailError text-danger"></span>
			                                    </div>
	                               		 	</div>
                            			</div>
	                            		<div class="col-sm-4">
                            				<label for="pais_nacionalidad_registrar">País de Nacionalidad*</label>
                            				<div class="valid-required">
                                				<select name="pais_nacionalidad" id="pais_nacionalidad_registrar" required class="fisicaf form-control">
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
                                				<select name="pais_origen" id="pais_origen_registrar" required class="fisicaf form-control">
                                					<option value="" selected>Seleccione</option>
                                						<?php foreach ($nacionalidades as $nacionalidad): ?>
                                					<option value="<?=$nacionalidad->id_lista_valor;?>"><?=$nacionalidad->nombre_lista_valor;?></option>
                                						<?php endforeach ?>
                                				</select>
                                			</div>
	                           			</div>
	                           			 
		                           		<div class="col-sm-8 col-md-3 col-lg-3 col-xs-3 form-group img_n_identificacion">
			                                <label for="">Copia escaneada del N° Identificación*</label>
			                                <input 
			                                	type="file" autocomplete="off" 
			                                	class="file-rfc" 
			                                	data-msg-placeholder="Selecciona un {files} ..." 
			                                	id="rfc_img" 
			                                	name=""
			                                	>        
			                            </div>
							        </div>
						        
						        	<div id="personaMoral" style="display: none;">
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
			                            		<label for="rfc_moral">N° Identificacion*</label>
				                                <div class="form-group valid-required">
				                                    <div class="form-line">
				                                        <input type="text" class="moralf form-control mayusculas" name="rfc_moral" id="rfc_moral" onkeypress='return solosnumerosyletras(event)' maxlength="13" placeholder="P. EJ. BML920313XXX">
				                                    </div>
				                                </div>
		                            		</div>
				                            <div class="col-sm-4" >
			                            		<label for="fecha_cons_r">Fecha Constitución*</label>
		                                		<div class="form-group valid-required">
		                                   			 <div class="form-line input-group fecha">
		                                        		<input type="text" class="moralf form-control" name="fecha_cons_r" id="fecha_cons_r" placeholder="dd-mm-yyyy">
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
			                                        <input type="email" class="form-control moralf" name="correo_moral_m" id="correo_moral_m" placeholder="P. EJ. ejemplo@dominio.com" onchange="validEmail(this)">

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
			                            	<div class="col-sm-12 img_n_identificacion">
				                               <label for="rfc_imag_mo">Copia escaneada del N° Identificacion*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="file" class="form-control" data-show-upload="false" data-show-preview="false"  placeholder="Copia escaneada del N° Identificacion" name="" id="rfc_imag_mo">
				                                    </div>
				                                </div>
		                        			</div>
		                            		<div class="col-sm-12" id="img_acta_constitutiva">
				                                <label for="acta_img_r">Copia escaneada del Acta Constitutiva*</label>
					                                <div class="form-group">
					                                    <div class="form-line">
					                                        <input type="file" class="form-control file-acta" data-show-upload="false" data-show-preview="false" placeholder="Copia escaneada del Acta constitutiva" name="" id="acta_img_r">
					                                    </div>
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
	                                <select id="colonia_registrar" class="form-control form-group" name="colonia">
		                                	<option value="">Seleccione</option>
		                                </select>
		                            </div>
	                            </div>
	                            <div class="col-sm-4"  style="padding-bottom: 10px;">
	                                <label for="municipio_registrar">Municipio*</label>
	                                <div class="valid-required">
		                                <select id="municipio_registrar" class="form-control form-group" name="municipio">
		                                	<option value="">Seleccione</option>
		                                </select>
		                            </div>
	                            </div>
	                            <div class="col-sm-4">
	                                <label for="ciudad_registrar">Ciudad*</label>
	                                <div class="valid-required">
		                                <select id="ciudad_registrar" class="form-control form-group" name="ciudad">
		                                	<option value="">Seleccione</option>
		                                </select>
		                            </div>
	                            </div>
	                            <div class="col-sm-4">
	                                <label for="estado_registrar">Estado*</label>
	                                <div class="valid-required">
		                                <select id="estado_registrar" class="form-control form-group" name="estado">
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
			                     </div>
					        	</div>


					        	<div class="tab-pane fade tab_content2" id="replegal">
						        	<div class="col-sm-4">
		                            		<label for="nombre_representante">Nombre(s)</label>
			                                <div class="form-group valid-required">
			                                    <div class="form-line">
			                                        <input type="text" class="form-control mayusculas" name="nombre_representante" id="nombre_representante" placeholder="P. EJ. LUIS RAÚL" onkeypress='return sololetras(event)'>
			                                    </div>
			                                </div>
		                            	</div>
			                            <div class="col-sm-4">
		                            		<label for="apellido_paterno_rep">Apellido Paterno</label>
			                                <div class="form-group valid-required">
			                                    <div class="form-line">
			                                        <input type="text" class="form-control mayusculas" name="apellido_paterno_rep" id="apellido_paterno_rep" placeholder="P. EJ. BELLO" onkeypress='return sololetras(event)'>
			                                    </div>
			                                </div>
			                            </div>
			                            <div class="col-sm-4">
		                            		<label for="apellido_materno_rep">Apellido Materno</label>
			                                <div class="form-group valid-required">
			                                    <div class="form-line">
			                                        <input type="text" class="form-control mayusculas" name="apellido_materno_rep" id="apellido_materno_rep" placeholder="P. EJ. MENA" onkeypress='return sololetras(event)'>
			                                    </div>
			                                </div>
		                          		</div>
		                          		<div class="col-sm-4">
		                            		<label for="rfc_representante">N° Identificacion*</label>
			                                <div class="form-group valid-required">
			                                    <div class="form-line">
			                                        <input type="text" class="form-control mayusculas file-legal" onkeypress='return solosnumerosyletras(event)' id="rfc_representante" name="rfc_representante" placeholder="P. EJ. BML920313XXX">
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
			                                <label for="curp_rep_legal_registrar">C.U.R.P.</label>
			                                <div class="form-group form-float">
			                                    <div class="form-line" id="validCurp">
			                                        <input type="text" class="form-control mayusculas" name="curp_rep_legal" id="curp_rep_legal_registrar" placeholder="P. EJ. BML920313HMLNNSOS" oninput="validarInputCurp(this)"  >
			                                    </div>
			                                    <span class="curpError text-danger"></span>
			                                </div>
			                            </div>
	                            		<div class="col-sm-4">
	                              		<label for="rfc_img_rep">Copia escaneada del N° Identificacion*</label>
			                                <div class="form-group valid-required">
			                                    <div class="form-line">
			                                        <input type="file" class="form-control mayusculas" data-show-upload="false"  placeholder="Copia escaneada del N° Identificacion"  id="rfc_img_rep" name="">
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
					        <div class="tab-pane fade tab_content3" id="cuenta">
					        	<div class="col-sm-4">
                                	<label for="clabe_registrar">CLABE*</label>
	                                <div class="form-group valid-required">
	                                    <div class="form-line">
	                                        <input type="text" class="form-control mayusculas guardado" name="clabe" id="clabe_registrar" onkeypress='return solonumeros(event)' placeholder="P. EJ. 00211501600326941" maxlength="14" minlength="14" required>
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
	                                   <input type="text" class="form-control mayusculas guardado" name="nombre_contacto" onkeypress='return sololetras(event)' id="nombre_contacto" placeholder="P. EJ.LUIS RAÚL" required maxlength="100">
                                    </div>
                                </div>
                             </div>
                             <div class="col-sm-4">
                                <label for="tlf_ppalContacto_r">Teléfono Principal*</label>
	                                <div class="form-group valid-required">
	                                    <div class="form-line">
	                                        <input type="text" class="form-control telefono guardado" name="telefono_principal_contacto" id="tlf_ppalContacto_r" placeholder="P. EJ.: +00 (000) 000-00-00" required onkeyup="validPhone(this)">
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
	                                        	<input type="email" class="form-control guardado" name="correo_contacto" id="correo_contacto_r" placeholder="P. EJ. ejemplo@dominio.com" required maxlength="30" onchange="validEmail(this)">

	                                        	<span class="emailError text-danger"></span>
	                                    	</div>
                                		</div>
                            		</div>
                            		<div class="col-sm-4">
		                                <label for="coreo_contactp_opc_r">Correo Electrónico Opcional</label>
		                                <div class="form-group valid-required">
	                                    	<div class="form-line">
	                                        	<input type="email" class="form-control" name="coreo_contactp_opc_r" id="coreo_contactp_opc_r" placeholder="P. EJ. ejemplo@dominio.com" maxlength="30" onchange="validEmail(this)">

	                                        	<span class="emailError text-danger"></span>
	                                    	</div>
                                		</div>
                            		</div>
				        </div>
				       
		       	 		<div class="col-sm-4 col-sm-offset-5">
                            <button type="button" onclick="regresar('#cuadro2')" class="btn btn-primary waves-effect">Regresar</button>
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

    <!-- Comienzo del cuadro de consultar -->
    
	<div class="row clearfix ocultar" id="cuadro3">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>Consultar <?php echo $breadcrumbs[0]["nombre_lista_vista"]; ?></h2>
                </div>
                <div class="body">
                	<div class="table-responsive">
                         	<div class="form-group" id="tipopersona_mostrar">
    							<label for="tipopersona_mostrar">Tipo Persona*</label> <br>
    							<input type="radio" disabled name="rad_tipoper_mostrar" id="fisica_mostrar" value="fisica">
    							<label for= "fisica">Física</label>
    							<input type="radio" disabled name="rad_tipoper_mostrar" id="moral_mostrar" value="moral">
    							<label for= "moral">Moral</label>



    							<span style="font-weight: bold; text-align: center;" class="pull-right" id="ficha_view">
    								<img src="" id="img_profile_view" style="width: 100px"><br>
    								<span></span>
    							</span>
    						</div>


    						



                               <ul class="nav nav-tabs">
					        	<li class="active"><a href="#datogeneralMostrar" data-toggle="tab" >Datos Generales*</a></li>
					        	<li><a href="#domicilioMostrar" data-toggle="tab" >Domicilio*</a></li>
					        	<li><a href="#repLegalMostrar" data-toggle="tab" class="pestana_replegalMostrar">Rep Legal</a></li>
					        	<li><a href="#cuentamostrar" data-toggle="tab" >Cuenta*</a></li>
					        	<li><a href="#contacto_mostrar" data-toggle="tab" >Contacto*</a></li>
					        </ul> 
					        <div class="tab-content">

						        <div class="tab-pane fade in active" id="datogeneralMostrar">
						        	<div id="datosGeneralFisica">
						        	<div class="col-sm-8">
		                            	<label for="rfc_img_mostrar">Imagen cliente*</label>
		                           		<div class="form-group valid-required">
	                               			 <div class="form-line input-group">
	                                    		<input type="file" class="form-control"   data-show-upload="false" readonly="true" placeholder="Imagen cliente" name="" id="cliente_img_consultar">
	                               			 </div>
	                            		</div> 
                            		</div>	
					        		<div class="col-sm-4">
	                            		<label for="nombre_cliente_mostrar">Nombre(s)*</label>
		                                <div class="form-group valid-required">
		                                    <div class="form-line">
		                                        <input type="text" class="form-control mayusculas" name="nombre_cliente_mostrar" id="nombre_cliente_mostrar" placeholder="P. EJ.LUIS RAÚL" disabled>
		                                    </div>
		                                </div>
	                            	</div>
		                            <div class="col-sm-4">
	                            		<label for="apellido_paterno_mostrar">Apellido Paterno*</label>
		                                <div class="form-group valid-required">
		                                    <div class="form-line">
		                                        <input type="text" class="form-control mayusculas" name="apellido_paterno_mostrar" id="apellido_paterno_mostrar" placeholder="P. EJ. BELLO" disabled>
		                                    </div>
		                                </div>
		                            </div>
		                            <div class="col-sm-4">
	                            		<label for="apellido_materno_mostrar">Apellido Materno*</label>
		                                <div class="form-group valid-required">
		                                    <div class="form-line">
		                                        <input type="text" class="form-control mayusculas" name="apellido_materno_mostrar" id="apellido_materno_mostrar" placeholder="P. EJ. MENA" disabled>
		                                    </div>
		                                </div>
	                          		</div>
	                          		<div class="col-sm-4">
	                            		<label for="rfc_mostrar">N° Identificacion*</label>
		                                <div class="form-group valid-required">
		                                    <div class="form-line">
		                                        <input type="text" class="form-control mayusculas" name="rfc_mostrar" id="rfc_mostrar" placeholder="P. EJ. BML920313XXX" disabled>
		                                    </div>
		                                </div>
	                            	</div>
		                            <div class="col-sm-4">
	                            		<label for="actividad_economica_mostrar">Actividad Económica*</label>
		                                <div class="form-group valid-required">
		                                    <div class="form-line">
		                                        <input type="text" class="form-control mayusculas" name="actividad_economica_mostrar" id="actividad_economica_mostrar" disabled>
		                                    </div>
		                                </div>
		                            </div>


									<div class="col-sm-4">
										<label for="actividad_economica">Empresa al cual pertenece*</label>
										<select name="empresa_pertenece" id="empresa_pertenece_view" required class="fisicaf form-control">
											<option value="" selected>Seleccione</option>
										</select>
									</div>



		                            <div class="col-sm-4">
		                                <label for="curp_datos_personales_mostrar">C.U.R.P.*</label>
		                                <div class="form-group form-float">
		                                    <div class="form-line" id="validCurp">
		                                        <input type="text" class="form-control mayusculas" name="curp_datos_personales" id="curp_datos_personales_mostrar" placeholder="P. EJ. BML920313HMLNNSOS" disabled>
		                                    </div>
		                                    <span class="curpError text-danger"></span>
		                                </div>
		                            </div>
		                        	<div class="col-sm-4">
	                            		 <label for="correo_cliente_mostrar">Correo Electrónico*</label>
		                                <div class="form-group valid-required">
                                    	<div class="form-line">
                                        <input type="email" class="form-control" name="correo_cliente_mostrar" id="correo_cliente_mostrar" placeholder="P. EJ. ejemplo@dominio.com" disabled>
                                    	</div>
                                		</div>
	                          		</div>
		                         	<div class="col-sm-4">
                                		<label for="fecha_nac_datos_mostrar">Fecha de Nacimiento*</label>
                                		<div class="form-group valid-required">
                                   			 <div class="form-line input-group fecha">
                                        		<input type="text" class="form-control" name="fecha_nac_datos_mostrar" id="fecha_nac_datos_mostrar" placeholder="dd-mm-yyyy" disabled>
                                        		<span class="input-group-addon">
					                       			 <span class="glyphicon glyphicon-calendar"></span>
					                   		 	</span>
                                   			 </div>
                                		</div>
                            		</div>
                            		<div class="col-sm-4">
		                               <label for="telefono_cliente_mostrar">Teléfono*</label>
		                                <div class="form-group valid-required">
	                                    <div class="form-line">
	                                        <input type="text" class="form-control telefono" name="telefono_cliente_mostrar" id="telefono_cliente_mostrar" disabled>
	                                    </div>
                               		 </div>
                            		</div>
                            	
                            		<div class="col-sm-4">
	                            		<label for="nacionalidad_cliente_mostrar">País de Nacionalidad*</label>
		                                <div class="form-group valid-required">
		                                    <div class="form-line">
		                                        <input type="text" class="form-control mayusculas" name="nacionalidad_cliente_mostrar" id="nacionalidad_cliente_mostrar" disabled>
		                                    </div>
		                                </div>
	                          		</div>
	                          		<div class="col-sm-4">
	                            		<label for="pais_origen_mostrar">País de Origen*</label>
		                                <div class="form-group valid-required">
		                                    <div class="form-line">
		                                        <input type="text" class="form-control mayusculas" name="pais_origen_mostrar" id="pais_origen_mostrar" disabled>
		                                    </div>
		                                </div>
	                          		</div>
	                          		<div class="col-sm-8">
		                              <label for="rfc_img_mostrar">Copia escaneada del N° Identificacion*</label>
		                           		<div class="form-group valid-required">
                                   			 <div class="form-line input-group">
                                        		<input type="file" class="form-control"   data-show-upload="false" readonly="true" placeholder="Copia escaneada del N° Identificacion" name="" id="rfc_img_consultar">
                                   			 </div>
                                		</div> 
                            		</div>

						        	

						        	</div>

					        	<div id="datosGeneralMoral" style="display: none;">
					        		<fieldset disabled>
					        			<div class="col-sm-8">
			                            	<label for="rfc_img_mostrar">Imagen cliente*</label>
			                           		<div class="form-group valid-required">
		                               			 <div class="form-line input-group">
		                                    		<input type="file" class="form-control"   data-show-upload="false" readonly="true" placeholder="Imagen cliente" name="" id="cliente_img_moral_consultar">
		                               			 </div>
		                            		</div> 
	                            		</div>
					        			<div class="col-sm-4">
		                            		<label for="razon_social_c">Denominación o Razón Social*</label>
			                                <div class="form-group valid-required">
			                                    <div class="form-line">
			                                        <input type="text" class="moralf form-control mayusculas" name="razon_social_c" id="razon_social_c" maxlength="30" placeholder="P. EJ.AG SITEMAS" required>
		                                    	</div>
		                             		</div>
		                           		</div>
		                           		<div class="col-sm-4">
	                            		<label for="rfc_moral_c">N° Identificacion*</label>
		                                <div class="form-group valid-required">
		                                    <div class="form-line">
		                                        <input type="text" class="moralf form-control mayusculas" name="rfc_moral_c" id="rfc_moral_c" maxlength="13" placeholder="P. EJ. BML920313XXX" required>
		                                    </div>
		                                </div>
	                            		</div>
			                            <div class="col-sm-4">
	                                		<label for="fecha_cons_c">Fecha Constitución*</label>
	                                		<div class="form-group valid-required">
	                                   			 <div class="form-line input-group fecha">
	                                        		<input type="text" class="moralf form-control" name="fecha_cons_c" id="fecha_cons_c" placeholder="dd-mm-yyyy" required>
	                                        		<span class="input-group-addon">
						                       			 <span class="glyphicon glyphicon-calendar"></span>
						                   		 	</span>
	                                   			 </div>
	                                		</div>
                            			</div>
                            			<div class="col-sm-4" >
	                            		<label for="giro_mercantil_c">Giro Mercantil*</label>
		                                <div class="form-group valid-required">
			                                    <div class="form-line">
			                                        <input name="giro_mercantil_c" id="giro_mercantil_c" required class="moralf form-control">
			                                    </div>
			                                </div>
	                            		</div>
			                          	<div class="col-sm-4">
			                                <label for="correo_moral_c">Correo Electrónico*</label>
			                                <div class="form-group valid-required">
		                                    	<div class="form-line">
		                                        <input type="email" class="form-control" name="correo_moral_c" id="correo_moral_c" placeholder="P. EJ. ejemplo@dominio.com" onchange="validEmail(this)">

                            	<span class="emailError text-danger"></span>
	                                    		</div>
                                			</div>
                            			</div>
                            		<div class="col-sm-4">
		                                <label for="telefono_moral_m">Teléfono*</label>
			                                <div class="form-group valid-required">
			                                    <div class="form-line">
			                                        <input type="text" class="form-control telefono" name="telefono_moral_c" id="telefono_moral_c" placeholder="P. EJ.: +00 (000) 000-00-00" onkeyup="validPhone(this)">

                            						<span class="emailError text-danger"></span>
			                                    </div>
	                               		 	</div>
                            			</div>
                            			<div class="col-sm-4">
                            				<label for="acta_constutiva_c">Acta Constitutiva y en su caso los cambios realizados*</label>
		                                <div class="form-group valid-required">
		                                    <div class="form-line">
		                                        <input type="text" class="moralf form-control mayusculas" name="acta_constutiva_c" id="acta_constutiva_c" maxlength="15" placeholder="P. EJ. ALB520" required>
		                                    </div>
		                                </div>
                            			</div>
					        		</fieldset>
                            			<div class="col-sm-6">
				                            <label for="rfc_imag_mo_c">Copia escaneada del N° Identificación*</label>
				                            <div class="form-group valid-required">
				                                <div class="form-line">
				                                    <input type="file" class="form-control"  placeholder="Copia escaneada del RFC" name="" id="rfc_imag_mo_c" readonly="true">
				                                </div>
				                            </div>
				            			</div>
				            		<div class="col-sm-6">
				                         <label for="acta_img_c">Copia escaneada del Acta Constitutiva*</label>
				                            <div class="form-group valid-required">
				                                <div class="form-line">
				                                    <input type="file" class="form-control mayusculas"  name="" id="acta_img_c" readonly="true">
				                                </div>
				                            </div>
				            			</div>
	                          		
					        	</div>
						        	
						        </div>
						        <div class="tab-pane fade" id="domicilioMostrar">
						          	<fieldset disabled>
						        	<div class="col-sm-4">
	                                <label for="calle_contacto_mostrar">Calle*</label>
	                                <div class="form-group valid-required">
	                                    <div class="form-line">
	                                        <input type="text" class="form-control mayusculas" name="calle_contacto" id="calle_contacto_mostrar" placeholder="P. EJ. PRIMAVERA">
	                                    </div>
	                                </div>
	                            </div>
                            <div class="col-sm-4">
                                <label for="exterior_contacto_mostrar">Número Exterior*</label>
                                <div class="form-group valid-required">
                                    <div class="form-line">
                                        <input type="text" class="form-control mayusculas" name="exterior_contacto" id="exterior_contacto_mostrar" placeholder="P. EJ. 33">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label for="interior_contacto_mostrar">Número Interior</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" class="form-control mayusculas" name="interior_contacto" id="interior_contacto_mostrar" placeholder="P. EJ. 2">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label for="codigo_postal_mostrar">Código Postal*</label>
                                <div class="form-group valid-required">
                                    <div class="form-line">
                                        <input type="text" class="form-control" id="codigo_postal_mostrar" onkeypress='return solonumeros(event)' maxlength="5" onchange="buscarCodigosUs(this.value, 'create')">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label for="colonia_mostrar">Colonia*</label>
                                <div class="form-group valid-required">
                                    <div class="form-line">
                                        <input type="text" class="form-control mayusculas" name="colonia_mostrar" id="colonia_mostrar">
                                    </div>
                                </div>
                            </div>
                           <div class="col-sm-4">
                                <label for="municipio_mostrar">Municipio*</label>
                                <div class="form-group valid-required">
                                    <div class="form-line">
                                        <input type="text" class="form-control mayusculas" name="municipio_mostrar" id="municipio_mostrar" >
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label for="ciudad_mostrar">Ciudad*</label>
                                <div class="form-group valid-required">
                                    <div class="form-line">
                                        <input type="text" class="form-control mayusculas" name="ciudad_mostrar" id="ciudad_mostrar" >
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label for="estado_mostrar">Estado*</label>
                                <div class="form-group valid-required">
                                    <div class="form-line">
                                        <input type="text" class="form-control mayusculas" name="estado_mostrar" id="estado_mostrar" placeholder="P. EJ. 2">
                                    </div>
                                </div>
                            </div>
                           	</fieldset>
                             <div class="col-sm-8">
	                            		<label for="domicilio_fiscal_img_c">Copia escaneada del Domicilio Fiscal*</label>
		                                <div class="form-group valid-required">
                                   			 <div class="form-line input-group">
                                        		<input type="file" class="form-control"  placeholder="Copia escaneada del Domicilio Fiscal" name="" id="domicilio_fiscal_img_c" readonly="true">
                                   			 </div>
                                		</div>
		                            </div>
		                            
						        </div>
						        <div class="tab-pane fade" id="repLegalMostrar">
							     	 	<div class="embed-responsive embed-responsive-16by9">
											  <iframe class="embed-responsive-item " id="iframeRepLegalm" allowfullscreen>
											  </iframe>
										</div>
								    </div>
						        <div class="tab-pane fade" id="cuentamostrar">
						        	<div class="embed-responsive embed-responsive-16by9" >
									  <iframe class="embed-responsive-item" id="iframeCuentam"  allowfullscreen>
									  </iframe>
									</div>
						        </div>
					<div class="tab-pane fade" id="contacto_mostrar">
					     <div class="embed-responsive embed-responsive-16by9" >
						  <iframe class="embed-responsive-item" id="iframeContactom"  allowfullscreen>
						  </iframe>
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
                <form name="form_clientePa_actualizar" id="form_clientePa_actualizar" method="post" enctype="multipart/form-data">
                	<div class="form-group" id="tipopersona_editar">
						<label for="tipopersona_editar">Tipo Persona*</label> <br>
						<input type="radio" name="rad_tipoper_editar" id="fisica_editar"  value="fisica">
						<label for= "fisica_editar">Física</label>
						<input type="radio" name="rad_tipoper_editar" id="moral_editar"  value="moral">
						<label for= "moral_editar">Moral</label>


						<span style="font-weight: bold; text-align: center;" class="pull-right" id="ficha_edit">
							<img src="" id="img_profile_edit" style="width: 100px"><br>
							<span></span>
						</span>



			</div>
            <input type="hidden" name="id_clientePagador" id="id_clientePagador_actualizar">
            <input type="hidden" name="id_contacto" id="id_contacto">
            <input type="hidden" name="id_datos_personales" id="id_datos_personales">
            <input type="hidden" name="id_codigo_postal" id="id_codigo_postal">
            <input type="hidden" name="imagen_editar" id="imagen_editar">
            <input type="hidden" name="imagen_acta_e" id="imagen_acta_e">
            <input type="hidden" name="imagen_domicilio_e" id="imagen_domicilio_e">


	        <ul class="nav nav-tabs">
	        	<li class="active"><a href="#datogeneraleditar" data-toggle="tab" >Datos Generales*</a></li>
	        	<li><a href="#domicilioeditar" data-toggle="tab" >Domicilio*</a></li>
	        	<li><a href="#repLegaleditar" data-toggle="tab" class="pestana_replegaleditar">Rep Legal</a></li>
	        	<li><a href="#cuentaEditar" data-toggle="tab" >Cuenta</a></li>
	        	<li><a href="#contactoEditar" data-toggle="tab" >Contacto</a></li>
	        </ul> 
	      	<div class="tab-content">
		        <div class="tab-pane fade in active" id="datogeneraleditar">
		        	<div id="personaFisica_e">
		        		<div class="col-sm-8 img_profile_edit" >
	                		<label for="rfc_img_editar">Imagen Cliente*</label>
	                        <div class="form-group valid-required">
	                            <div class="form-line">
	                                 <input type="file" class="form-control mayusculas fisicae fileeditar"   data-show-upload="false"  placeholder="Imagen cliente" name="" id="cliente_img_editar">
	                            </div>
	                        </div>
	                    </div>
			       		<div class="col-sm-4">
                		<label for="nombre_cliente_editar">Nombre(s)*</label>
                        <div class="form-group valid-required">
                            <div class="form-line">
                                <input type="text" class="form-control mayusculas fisicae" name="nombre_cliente" id="nombre_cliente_editar" placeholder="P. EJ.LUIS RAÚL" onkeypress='return sololetras(event)' required>
                            </div>
                        </div>
                	</div>
                    <div class="col-sm-4">
                		<label for="apellido_paterno_editar">Apellido Paterno*</label>
                        <div class="form-group valid-required">
                            <div class="form-line">
                                <input type="text" class="form-control mayusculas fisicae" name="apellido_paterno" onkeypress='return sololetras(event)' id="apellido_paterno_editar" placeholder="P. EJ. BELLO" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                		<label for="apellido_materno_editar">Apellido Materno*</label>
                        <div class="form-group valid-required">
                            <div class="form-line">
                                <input type="text" class="form-control mayusculas fisicae" name="apellido_materno" onkeypress='return sololetras(event)' id="apellido_materno_editar" placeholder="P. EJ. MENA" required>
                            </div>
                        </div>
              		</div>
              		<div class="col-sm-4">
                		<label for="rfc_editar">N° Identificacion**</label>
                        <div class="form-group valid-required">
                            <div class="form-line">
                                <input type="text" class="form-control mayusculas fisicae" name="rfc_editar" onkeypress='return solosnumerosyletras(event)' id="rfc_editar" placeholder="P. EJ. BML920313XXX" required>
                            </div>
                        </div>
                	</div>
                    <div class="col-sm-4">
                		<label for="fecha_nac_datos_editar">Fecha de Nacimiento*</label>
                		<div class="form-group valid-required">
                   			 <div class="form-line input-group fecha">
                        		<input type="text" class="form-control" name="fecha_nac_datos_editar" id="fecha_nac_datos_editar" placeholder="dd-mm-yyyy">
                        		<span class="input-group-addon">
	                       			 <span class="glyphicon glyphicon-calendar"></span>
	                   		 	</span>
                   			 </div>
                		</div>
                    </div>
                    <div class="col-sm-4">
                        <label for="curp_datos_personales_editar">C.U.R.P.*</label>
                        <div class="form-group form-float">
                            <div class="form-line" id="validCurp">
                                <input type="text" class="form-control mayusculas fisicae" name="curp_datos_personales" id="curp_datos_personales_editar" onkeypress='return solosnumerosyletras(event)' maxlength="18" placeholder="P. EJ. BML920313HMLNNSOS" oninput="validarInputCurp(this)"  >
                            </div>
                            <span class="curpError text-danger"></span>
                        </div>
                    </div>
                	<div class="col-sm-4">
                		<label for="actividad_economica_editar">Actividad Economica*</label>
                        <select name="actividad_economica" id="actividad_economica_editar" class="form-control">
            					<option value="" selected>Seleccione</option>
            						<?php foreach ($actividadesEconomicas as $actividadEconomica): ?>
            					<option value="<?=$actividadEconomica->id_lista_valor;?>"><?=$actividadEconomica->nombre_lista_valor;?></option>
            						<?php endforeach ?>
            				</select>
                	</div>



					<div class="col-sm-4">
						<label for="actividad_economica">Empresa al cual pertenece*</label>
						<select name="empresa_pertenece" id="empresa_pertenece_edit" required class="fisicaf form-control">
							<option value="" selected>Seleccione</option>
						</select>
					</div>




                 	<div class="col-sm-4">
                		<label for="telefono_cliente_editar">Teléfono*</label>
                        <div class="form-group valid-required">
                        <div class="form-line">
                            <input type="text" class="form-control telefono" name="telefono_cliente_editar" id="telefono_cliente_editar" placeholder="P. EJ.: +00 (000) 000-00-00" onkeyup="validPhone(this)">
	                        <span class="emailError text-danger"></span>
                        </div>
            		</div>
               		</div>
            		<div class="col-sm-4">
                        <label for="correo_cliente_editar">Correo Electrónico*</label>
                        <div class="form-group valid-required">
                    	<div class="form-line">
                        <input type="email" class="form-control" name="correo_cliente_editar" id="correo_cliente_editar" placeholder="P. EJ. ejemplo@dominio.com" onchange="validEmail(this)">
							<span class="emailError text-danger"></span>
                    	</div>
                		</div>
            		</div>
                    <div class="col-sm-4">
            				<label for="nacionalidad_cliente_editar">País de Nacionalidad*</label>
                				<select name="nacionalidad_cliente_editar" id="nacionalidad_cliente_editar" class="form-control">
                					<option value="" selected>Seleccione</option>
                						<?php foreach ($nacionalidades as $nacionalidad): ?>
                					<option value="<?=$nacionalidad->id_lista_valor;?>"><?=$nacionalidad->nombre_lista_valor;?></option>
                						<?php endforeach ?>
                				</select>
                   	</div>
           			 <div class="col-sm-4">
        				<label for="pais_origen_editar">País Origen*</label>
            				<select name="pais_origen_editar" id="pais_origen_editar" class="form-control">
            					<option value="" selected>Seleccione</option>
            						<?php foreach ($nacionalidades as $nacionalidad): ?>
            					<option value="<?=$nacionalidad->id_lista_valor;?>"><?=$nacionalidad->nombre_lista_valor;?></option>
            						<?php endforeach ?>
            				</select>
           			 </div>
           			 <br>
           			 <hr>
           			   <div class="col-sm-8 img_n_identificacion_edit" >
                		<label for="rfc_img_editar">Copia escaneada del N° Identificación*</label>
                        <div class="form-group valid-required">
                            <div class="form-line">
                                 <input type="file" class="form-control mayusculas fisicae fileeditar"   data-show-upload="false"  placeholder="Copia escaneada del N° Identificación" name="" id="rfc_img_editar">
                            </div>
                        </div>
                    </div>
		        	
		        </div>
		        	<div id="personaMoral_e" style="display: none;">
		        		<div class="col-sm-8 img_profile_edit" >
	                		<label for="rfc_img_editar">Imagen Cliente*</label>
	                        <div class="form-group valid-required">
	                            <div class="form-line">
	                                 <input type="file" class="form-control mayusculas fileeditar"   data-show-upload="false"  placeholder="Imagen cliente" name="" id="cliente_img_moral_editar">
	                            </div>
	                        </div>
	                    </div>
		        		<div class="col-sm-4">
                    		<label for="razon_social_e">Denominación o Razón Social*</label>
                            <div class="form-group valid-required">
                                <div class="form-line">
                                    <input type="text" class="moralf form-control mayusculas morale" name="razon_social_e" id="razon_social_e" maxlength="30" placeholder="P. EJ.AG SITEMAS" required>
                            	</div>
                     		</div>
                   		</div>
                   		<div class="col-sm-4">
                		<label for="rfc_moral_e">N° Identificacion*</label>
                        <div class="form-group valid-required">
                            <div class="form-line">
                                <input type="text" class="moralf form-control mayusculas morale" onkeypress='return solosnumerosyletras(event)' name="rfc_moral_e" id="rfc_moral_e" maxlength="13" placeholder="P. EJ. BML920313XXX" required>
                            </div>
                        </div>
                		</div>
                        <div class="col-sm-4">
                    		<label for="giro_mercantil_e">Giro Mercantil*</label>
                        	<div class="form-group valid-required">
                                <div class="form-line">
                                   <select name="giro_mercantil_e" id="giro_mercantil_e" required class="moralf form-control morale">
                					<option value="" selected>Seleccione</option>
                						<?php foreach ($giros as $giro): ?>
                					<option value="<?=$giro->id_lista_valor;?>"><?=$giro->nombre_lista_valor;?></option>
            						<?php endforeach ?>
            						</select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                    		<label for="fecha_cons_e">Fecha Constitución*</label>
                    		<div class="form-group valid-required">
                       			 <div class="form-line input-group fecha">
                            		<input type="text" class="moralf form-control morale" name="fecha_cons_e" id="fecha_cons_e" placeholder="dd-mm-yyyy" required>
                            		<span class="input-group-addon">
		                       			 <span class="glyphicon glyphicon-calendar"></span>
		                   		 	</span>
                       			 </div>
                    		</div>
            			</div>
            			<div class="col-sm-4">
            				<label for="correo_moral_e">Correo Electrónico*</label>
                            <div class="form-group valid-required">
                            	<div class="form-line">
                                <input type="email" class="form-control" name="correo_moral_e" id="correo_moral_e" placeholder="P. EJ. ejemplo@dominio.com" onchange="validEmail(this)">
									<span class="emailError text-danger"></span>
                        		</div>
                			</div>
                   		</div>
                        <div class="col-sm-4">
                    	<label for="telefono_moral_m">Teléfono*</label>
                            <div class="form-group valid-required">
                                <div class="form-line">
                                    <input type="text" class="form-control telefono" name="telefono_moral_e" id="telefono_moral_e" placeholder="P. EJ.: +00 (000) 000-00-00" onkeyup="validPhone(this)">
	                                <span class="emailError text-danger"></span>
                                </div>
                   		 	</div>	
                        </div>
                        <div class="col-sm-4">
                			<label for="acta_constutiva_e">Acta Constitutiva y en su caso los cambios realizados*</label>
                            <div class="form-group valid-required">
                                <div class="form-line">
                                    <input type="text" class="moralf form-control mayusculas morale" name="acta_constutiva_e" id="acta_constutiva_e" maxlength="15" placeholder="P. EJ. ALB520" required>
                                </div>
                            </div>
                        </div>
                		<div class="col-sm-4 img-n-identificacion-edit">
                            <label for="rfc_imag_mo_e">Copia escaneada del N° Identificación*</label>
                            <div class="form-group valid-required">
                                <div class="form-line">
                                    <input type="file" class="moralf form-control fileeditar"  placeholder="Copia escaneada del N° Identificación" name="" id="rfc_imag_mo_e" required>
                                </div>
                            </div>
            			</div>
            		<div class="col-sm-4" id="img_acta_constitutiva_edit">
                         <label for="acta_img_e">Copia escaneada del Acta Constitutiva*</label>
                            <div class="form-group valid-required">
                                <div class="form-line">
                                    <input type="file" class="moralf form-control morale fileeditar mayusculas"  placeholder="Copia escaneada del RFC" name="" id="acta_img_e">
                                </div>
                            </div>
            			</div>
		        	</div>
		        </div>
		        <div class="tab-pane fade" id="domicilioeditar">
						   	<div class="col-sm-4">
		                            <label for="calle_contacto_editar">Calle*</label>
		                            <div class="form-group valid-required">
		                                <div class="form-line">
		                                    <input type="text" class="form-control mayusculas" name="calle_contacto" id="calle_contacto_editar" placeholder="P. EJ. PRIMAVERA" required>
		                                </div>
		                            </div>
		                        </div>
		                    <div class="col-sm-4">
		                        <label for="exterior_contacto_editar">Número Exterior*</label>
		                        <div class="form-group valid-required">
		                            <div class="form-line">
		                                <input type="text" class="form-control mayusculas" name="exterior_contacto" onkeypress='return solosnumerosyletras(event)' id="exterior_contacto_editar" placeholder="P. EJ. 33" required>
		                            </div>
		                        </div>
		                    </div>
		                    <div class="col-sm-4">
		                        <label for="interior_contacto_editar">Número Interior</label>
		                        <div class="form-group">
		                            <div class="form-line">
		                                <input type="text" class="form-control mayusculas" name="interior_contacto" onkeypress='return solosnumerosyletras(event)' id="interior_contacto_editar" placeholder="P. EJ. 2">
		                            </div>
		                        </div>
		                    </div>
		                    <div class="col-sm-4">
		                        <label for="codigo_postal_editar">Código Postal*</label>
		                        <div class="form-group valid-required">
		                            <div class="form-line">
		                                <input type="text" class="form-control" id="codigo_postal_editar" onkeypress='return codigoPostal(event)' maxlength="5"  onchange="buscarCodigosUs(this.value, 'edit')">
		                            </div>
		                        </div>
		                    </div>
		                    <div class="col-sm-4">
		                    	<label for="colonia_editar">Colonia*</label>
		                        <select id="colonia_editar" class="form-control form-group" name="colonia">
		                        	<option value="">Seleccione</option>
		                        </select>
		                    </div>
		                    <div class="col-sm-4"  style="padding-bottom: 10px;">
		                        <label for="municipio_editar">Municipio*</label>
		                        <select id="municipio_editar" class="form-control form-group" name="municipio">
		                        	<option value="">Seleccione</option>
		                        </select>
		                    </div>
		                    <div class="col-sm-4">
		                        <label for="ciudad_editar">Ciudad*</label>
		                        <select id="ciudad_editar" class="form-control form-group" name="ciudad">
		                        	<option value="">Seleccione</option>
		                        </select>
		                    </div>
		                    <div class="col-sm-4">
		                        <label for="estado_editar">Estado*</label>
		                        <select id="estado_editar" class="form-control form-group" name="estado">
		                        	<option value="">Seleccione</option>
		                        </select>
		                    </div>
		                           <div class="col-sm-8" id="domicilio_edit">
		                        		<label for="domicilio_fiscal_img_e"> Copia Escaneada domicilio Fiscal*</label>
		                                <div class="form-group valid-required">
		                                    <div class="form-line">
		                                        <input type="file" class="form-control fileeditar"  placeholder="Copia escaneada del Domicilio Fiscal" name="" id="domicilio_fiscal_img_e">
		                                    </div>
		                                </div>
		                            </div>
						        		
						        		
						        	
						        </div>
						        <div class="tab-pane fade" id="repLegaleditar">
							     	<div class="embed-responsive embed-responsive-16by9">
									  <iframe class="embed-responsive-item " id="iframeRepLegal" allowfullscreen>
									  </iframe>
									</div>
						        </div>
						        <div class="tab-pane fade" id="cuentaEditar">
						        	<div class="embed-responsive embed-responsive-16by9" >
									  <iframe class="embed-responsive-item" id="iframeCuenta"  allowfullscreen>
									  </iframe>
									</div>
						        </div>
					        <div class="tab-pane fade" id="contactoEditar">
				       			<div class="embed-responsive embed-responsive-16by9" >
									  <iframe class="embed-responsive-item" id="iframeContacto"  allowfullscreen>
									  </iframe>
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

     

    <script src="<?=base_url();?>assets/cpanel/ClientePagador/js/clientePagador.js"></script>
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
