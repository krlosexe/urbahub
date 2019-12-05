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
        	<div id="alertas">
                <div class="alert alert-info" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <span>Buscando datos, espere por favor... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>
                </div>      
            </div>
        	<div class="block-header">
  
            </div>
            <!-- -->
            <div class="row clearfix" id="cuadro2">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>Renovaciones de membresía</h2>
                        </div>
                        
                        <div class="body">
                        	<div class="col-sm-12">
	                        	<table class="table table-bordered table-striped table-hover" id="tableInmobiliariaRegistrar">
	                        		<thead>
	                        			<tr>
	                        				<th>Membresía</th>
	                        				<th>Id renovación</th>
	                        				<th>Horas contratadas</th>
	                        				<th>Valor</th>
	                        				<th>Inicio</th>
	                        				<th>Vigencia</th>
	                        			</tr>
	                        		</thead>
	                        		<tbody>
	                        			<tr>
	                        				<th id="numero_membresia_renovacion"><?php echo $membresia['numero_membresia'];?></th>
	                        				<th id="id_renovacion_renovacion"><?php echo $membresia['numero_renovacion'];?></th>
	                        				<th id="horas_jornadas_renovacion"></th>
	                        				<th id="precio_plan_renovacion"></th>
	                        				<th id="fecha_inicio_renovacion"></th>
	                        				<th id="fecha_fin_renovacion"></th>
	                        			</tr>
	                        		</tbody>
	                        	</table>
	                        </div>	
                        	<!-- -->
                        	<div class="col-sm-12">
                        		Seleccione el plan/paquete que desea para renovar su membresía
                        	</div>
                            <div class="col-sm-12">
                            	<input type="hidden" class="form-control mayusculas" name="id_membresia_renovacion" id="id_membresia_renovacion"  value="<?php echo $membresia['id_membresia'];?>">
                                <input type="hidden" class="form-control mayusculas" name="numero_renovacion" id="numero_renovacion"  value="<?php echo $membresia['numero_renovacion'];?>">
                            	<input type="hidden" class="form-control mayusculas" name="id_paquete_renovacion" id="id_paquete_renovacion"  value="<?php echo $membresia['paquete'];?>">
                            	<input type="hidden" class="form-control mayusculas" name="id_cliente_renovacion" id="id_cliente_renovacion"  value="<?php echo $membresia['cliente'];?>">

                            </div>
                            <div class="col-sm-6 paquetes-membresia" style="    float: right;">
                        		<label for="paquetes_renovaciones_registrar">Paquetes*</label>
                            	<select name="paquetes_renovaciones_registrar" id="paquetes_renovaciones_registrar" required class="form-control" onchange="consultarPlanRenovacion(this.value,'','guardar');">
                            		<option value="" >Seleccione</option>
                            	</select>
                            </div>
                            <!-- -->
                        	<div class="col-sm-6 planes-membresia" style="    float: right;padding: 0px;">
                        		<label for="plan_renovaciones_registrar">Planes*</label>

                            	<select name="plan_renovaciones_registrar" id="plan_renovaciones_registrar" required class="form-control" onchange="consultarPaquetesrenovacion(this.value,'');">
                            		<option value="">Seleccione</option>
                            		<?php foreach ($planes as $plan): ?>
                            			<?php if ($plan['status']==true){ ?> 

                            				<option value="<?= $plan["id_planes"]; ?>" status="<?=$plan['status'] ?>" <? if($membresia["plan"]==$plan["id_planes"]){ echo "selected";} ?> ><?= $plan["titulo"]." ".$plan["descripcion"];?></option>
                            			<?php } ?> 
                            		<?php endforeach ?>
                            	</select>
                            	
                            	<input type="hidden" name="plan_horas_renovacion" id="plan_horas_renovacion">
                            	<input type="hidden" name="plan_fecha_inicio_renovacion" id="plan_fecha_inicio_renovacion">
                            	<input type="hidden" name="plan_fecha_fin_renovacion" id="plan_fecha_fin_renovacion">
                            	<input type="hidden" name="plan_valor_renovacion" id="plan_valor_renovacion">
                            </div>
                            <div class="col-sm-12 col-sm-offset-5">
					            <button type="button" onclick="renovarMembresia('#cuadro4')" class="btn btn-primary waves-effect">Renovar</button>
					        </div>    
						    <div style="clear: both;"></div>
					    </div>
                    </div> 
                </div>
            </div>
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
    <script src="<?=base_url();?>assets/cpanel/Membresia/js/renovaciones.js"></script>
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