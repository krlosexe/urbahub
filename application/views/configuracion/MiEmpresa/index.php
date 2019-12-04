<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
	<?php if(($permiso[0]->general==1 && $permiso[0]->actualizar==1) OR $permiso[0]->status==false): ?>
		<script src="<?=base_url();?>assets/cpanel/js/permiso.js"></script>
	<?php endif ?>
	<body class="theme-blue">
		<input type="hidden" id="ruta" value="<?=base_url();?>" name="ruta">
		<section class="content">
	        <div class="container-fluid">
	        	<div id="alertas"></div>
	        	<div class="block-header">
	                <ol class="breadcrumb breadcrumb-col-cyan">
                        <li><a href="javascript:void(0);"><?php echo  $breadcrumbs[0]["nombre_modulo_vista"]; ?></a></li>
                        <li><?php echo  $breadcrumbs[0]["nombre_lista_vista"]; ?></li>
                    </ol>
	            </div>
				<div class="row clearfix">
	                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	                    <div class="card">
	                        <div class="header">
	                            <h2>Datos de <?php echo $breadcrumbs[0]["nombre_lista_vista"];; ?></h2>
	                        </div>
	                        <div class="body">
	                        	<div class="table-responsive">
		                            <form name="form_empresa_actualizar" id="form_empresa_actualizar" method="post">
			                            <div class="col-sm-3">
			                                <label for="nombre_mi_empresa">Nombre de Empresa*</label>
			                                <div class="form-group">
			                                    <div class="form-line">
			                                        <input type="text" class="form-control mayusculas" name="nombre_mi_empresa" id="nombre_mi_empresa" required placeholder="P. EJ. AG SISTEMAS">
			                                    </div>
			                                </div>
			                            </div>
			                            <div class="col-sm-3">
			                                <label for="rfc_mi_empresa">RFC*</label>
			                                <div class="form-group">
			                                    <div class="form-line">
			                                        <input type="text" class="form-control mayusculas" name="rfc_mi_empresa" id="rfc_mi_empresa" required placeholder="P. EJ. AGS070707GK3" maxlength="14">
			                                    </div>
			                                </div>
			                            </div>
			                            <div class="col-sm-3">
			                                <label for="telefono_principal_contacto">Teléfono Principal*</label>
			                                <div class="form-group">
			                                    <div class="form-line">
			                                        <input type="text" class="form-control telefono" name="telefono_principal_contacto" id="telefono_principal_contacto" placeholder="P. EJ.: +00 (000) 000-00-00" onkeypress='return solonumeros(event)'>
			                                    </div>
			                                </div>
			                            </div>
			                            <div class="col-sm-3">
			                                <label for="correo_opcional_contacto">Correo Electrónico*</label>
			                                <div class="form-group">
			                                    <div class="form-line">
			                                        <input type="email" class="form-control" name="correo_opcional_contacto" id="correo_opcional_contacto" required placeholder="P. EJ. ejemplo@domino.com">
			                                    </div>
			                                </div>
			                            </div>
			                            <!--<div class="col-sm-4">
			                                <label for="direccion_contacto">Domicilio</label>
			                                <div class="form-group">
			                                    <div class="form-line">
			                                        <input type="text" class="form-control mayusculas" name="direccion_contacto" id="direccion_contacto" placeholder="P. EJ. INSURGENTE">
			                                    </div>
			                                </div>
			                            </div>-->
			                            <div class="col-sm-4">
			                                <label for="calle_contacto">Calle*</label>
			                                <div class="form-group">
			                                    <div class="form-line">
			                                        <input type="text" class="form-control mayusculas" name="calle_contacto" id="calle_contacto" placeholder="P. EJ. PRIMAVERA">
			                                    </div>
			                                </div>
			                            </div>
			                            <div class="col-sm-4">
			                                <label for="exterior_contacto">Número Exterior*</label>
			                                <div class="form-group">
			                                    <div class="form-line">
			                                        <input type="text" class="form-control mayusculas" name="exterior_contacto" id="exterior_contacto" placeholder="P. EJ. 33">
			                                    </div>
			                                </div>
			                            </div>
			                            <div class="col-sm-4">
			                                <label for="interior_contacto">Número Interior</label>
			                                <div class="form-group">
			                                    <div class="form-line">
			                                        <input type="text" class="form-control mayusculas" name="interior_contacto" id="interior_contacto" placeholder="P. EJ. 2">
			                                    </div>
			                                </div>
			                            </div>
			                            <input type="hidden" name="id_contacto" id="id_contacto" value="0">
			                            <div class="col-sm-4">
			                                <label for="codigo_postal">Código Postal*</label>
			                                <div class="form-group">
			                                    <div class="form-line">
			                                        <input type="text" class="form-control" id="codigo_postal" onkeypress='return solonumeros(event)' maxlength="6" onchange="buscarCodigos(this.value)">
			                                    </div>
			                                </div>
			                            </div>
			                            <div class="col-sm-4" style="padding-bottom: 10px;">
			                                <label for="colonia">Colonia*</label>
	                                        <select id="colonia" required class="form-control form-group" name="colonia">
	                                        	<!--<option value="">Seleccione</option>-->
	                                        </select>
			                            </div>
			                            <div class="col-sm-4">
			                                <label for="municipio">Municipio*</label>
	                                        <select id="municipio" required class="form-control form-group" name="municipio">
	                                        	<!--<option value="">Seleccione</option>-->
	                                        </select>
			                            </div>
			                            <div class="col-sm-4"></div>
			                            <div class="col-sm-4">
			                                <label for="ciudad">Ciudad*</label>
	                                        <select id="ciudad" required class="form-control form-group" name="ciudad">
	                                        	<!--<option value="">Seleccione</option>-->
	                                        </select>
			                            </div>
			                            <div class="col-sm-4">
			                                <label for="estado">Estado*</label>
	                                        <select id="estado" required class="form-control form-group" name="estado">
	                                        	<!--<option value="">Seleccione</option>-->
	                                        </select>
			                            </div>
			                            
			                            <input type="hidden" name="id_mi_empresa" id="id_mi_empresa" value="0">
                            			<div class="col-sm-4 col-sm-offset-5 actualizar ocultar">
                            				<span class="btn btn-danger waves-effect" onclick="listar()">Cancelar</span>
	                                        <input type="submit" value="Actualizar" class="btn btn-success waves-effect">
		                                </div>
		                            </form>
		                        </div>
	                        </div>
	                    </div>
	                </div>
	            </div>
			</div>
		</section>
	</body>
	<script src="<?=base_url();?>assets/template/plugins/jquery-inputmask/jquery.inputmask.bundle.js"></script>
    <script src="<?=base_url();?>assets/cpanel/MiEmpresa/js/miEmpresa.js"></script>
    <script>
		$("#mv<?php echo $permiso[0]->id_modulo_vista ?>").attr('class', 'active');
		$("#lv<?php echo $permiso[0]->id_lista_vista ?>").attr('class', 'active');
		var actualizar = <?php echo $permiso[0]->actualizar ?>;
		if(actualizar==0)
			$(".actualizar").removeClass('ocultar');
	</script>
</html>