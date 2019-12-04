<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
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
		                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                    <div class="card">
		                        <div class="header">
		                            <h2>
		                                <?php echo $breadcrumbs[0]["nombre_lista_vista"]; ?>
		                            </h2>
		                            <ul class="header-dropdown m-r--5">
		                                <button class="btn btn-primary waves-effect registrar ocultar" id="agregar_temporadas"><i class='fa fa-plus-circle' style="color: white; font-size: 18px;"></i> |Agregar temporada</button>
		                                <button class="btn btn-danger waves-effect registrar ocultar" onclick="eliminarTemporada()"><i class="fa fa-times" aria-hidden="true" style="color: white; font-size: 18px;"></i> | Eliminar</button>
		                                <button class="btn btn-success waves-effect registrar ocultar" onclick="registrarTemporada()"><i class="fa fa-floppy-o" aria-hidden="true" style="color: white; font-size: 18px;"></i> |Registrar temporada</button>
		                            </ul>
		                        </div>
		                        <!-- Cuerpo de temporadas -->
		                        <div class="body">
		                            <div class="">
		                                <table class="table table-bordered table-striped table-hover" id="tabla">
		                                    <thead>
		                                    	<th style="text-align: center; padding: 0px 10px 0px 5px;">
	                                        		
	                                        	</th>
	                                        	<th style="width:5%">N°</th>
		                                        <th style="width:30%">
		                                        	Desde
		                                        </th>
		                                        <th style="width:30%">
		                                        	Hasta
		                                        </th>
		                                        <th style="width:10%">
		                                        	Ajuste %
		                                        </th>
		                                        <th>
		                                        	Condición
		                                        </th>
		                                        <th>Aplicar</th>
		                                    </thead>
		                                    <tbody id="tbody-temporada" data="0">
			                                    <!--<tr id="fila1" data="1" class="fila">
		                                    		<td style="text-align: center; padding: 0px 10px 0px 5px;">
		                                        		<input type="checkbox" id="check1" class="checkitem chk-col-blue"/><label for="check1"></label>
		                                        	</td>
		                                        	<td>1</td>
			                                    	<td>
						                                <div class="form-group form-float" id="xfec_desde1">
				                                    		<div class="form-line input-group fecha">
						                                        <input type="text" class="form-control validar_fecha_calendario" maxlength="8" div_error="#xfec_desde1" name="fecha_desde1" id="fecha_desde1" placeholder="dd-mm-yyyy" required>
						                                        <span class="input-group-addon validar_fecha_calendario2">
											                        <span class="glyphicon glyphicon-calendar"></span>
											                    </span>
						                                    </div>
						                                </div>
			                                    	</td>
			                                    	<td>
			                                    		<div class="form-group form-float" id="xfec_hasta1">
						                                    <div class="form-line input-group fecha">
						                                        <input type="text" class="form-control validar_fecha_calendario" maxlength="8" div_error="#xfec_hasta1" name="fecha_hasta1" id="fecha_hasta1" placeholder="dd-mm-yyyy" required>
						                                        <span class="input-group-addon validar_fecha_calendario2">
											                        <span class="glyphicon glyphicon-calendar"></span>
											                    </span>
						                                    </div>
						                                </div>
			                                    	</td>
			                                    	<td>
			                                    		<input type="text" class="form-control" id="ajuste_precio1" name="ajuste_precio1">
			                                    	</td>
			                                    	<td>
				                                    	<div class="form-check">
				                                    		<input class="form-check-input" type="radio" name="radio_ajustes1" id="mas1" value="mas" checked>
				                                    		<label class="form-check-label" for="mas1">
															   Más
															</label>
														</div>	
			                                    		<div class="form-check">
				                                    		<input class="form-check-input" type="radio" name="radio_ajustes1" id="menos1" value="menos">
				                                    		<label class="form-check-label" for="menos1">
															   Menos
															</label>
														</div>
			                                    	</td>
			                                    	<td style="text-align: center; padding: 0px 10px 0px 5px;">
		                                        		<input type="checkbox" id="check_ajuste1" class="chk-col-blue"/>
		                                        		<label for="check_ajuste1"></label>
		                                        	</td>
			                                    </tr>-->	
		                                    </tbody>
		                                </table>
		                            </div>
		                        </div>                
		                        <!-- -->
		                        <div class="body">
		                            <div class="table-responsive">
		                                <table class="table table-bordered table-striped table-hover" id="tabla">
		                                    <thead>
		                                        <tr>
		                                            <th>Plan</th>
		                                            <th>Nombre plan</th>
		                                            <th>Temporada</th>
		                                            <th>Desde</th>
		                                            <th>Hasta</th>
		                                            <th>Costo orig.</th>
		                                            <th>Ajuste</th>
		                                            <th>Costo temporada</th>
		                                            <th style="width: 17%;">Condición</th>
		                                        </tr>
		                                    </thead>
		                                    <tbody id="tbody-mapa-temporada"></tbody>
		                                </table>
		                                
		                            </div>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        <!-- Cierre del cuadro de la tabla -->
			</div>
		</section>
	</body>

    <script src="<?=base_url();?>assets/template/plugins/jquery-inputmask/jquery.inputmask.bundle.js"></script>
    <script src="<?=base_url();?>assets/cpanel/Temporadas/js/temporadas.js"></script>
    <script src="<?=base_url();?>assets/template/plugins/momentjs/moment.js"></script>
    <script src="<?=base_url();?>assets/template/plugins/bootstrap-datetimepicker-master/js/bootstrap-datetimepicker.min.js"></script>
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
