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
                <!-- -->
                <div class="row clearfix" id="cuadro2">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="header">
                                <h2>Saldos de membresía</h2>
                            </div>
                            <div class="body">
                                <div class="table-responsive">
                                    <form name="form_saldos_consultar" id="form_saldos_consultar" method="post"  enctype="multipart/form-data">
                                        <input type="hidden" class="form-control mayusculas" name="id_membresia_saldosC" id="id_membresia_saldosC"  value="<?php echo $membresia['id_membresia'];?>">
                                        <input type="hidden" class="form-control mayusculas" name="id_numero_renovacion_saldosC" id="id_numero_renovacion_saldosC"  value="<?php echo $membresia['numero_renovacion'];?>">
                                        <div>
                                        <!--Saldos -->
                                        <!--<div id="">
                                            <div class="col-sm-4">
                                                <label for="nombre_cliente">Serial de acceso</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="text" class="form-control mayusculas" name="serial_acceso_saldos_mostrar" id="serial_acceso_saldos_mostrar" autocomplete="off" maxlength="30" placeholder="P. EJ.123456" disabled value="<?php echo $membresia['serial'];?>">
                                                    </div>
                                                </div>
                                            </div>
                                                
                                            
                                            <div class="col-sm-4">
                                                <label for="rfc_cliente_saldos_mostrar">Identificación (Prospecto/CLiente)</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="text" class="form-control mayusculas" name="rfc_cliente_saldos_mostrar" autocomplete="off" id="rfc_cliente_saldos_mostrar" maxlength="30" placeholder="P. EJ.CONRA19901234" disabled value="<?php echo $membresia['rfc'];?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <label for="nombre_saldos_mostrar">Nombre(s)</label>
                                                <div class="form-group valid-required">
                                                    <div class="form-line">
                                                        <input type="text" class="form-control mayusculas" name="nombre_saldos_mostrar" autocomplete="off" id="nombre_saldos_mostrar" maxlength="30" placeholder="P. EJ.LUIS RAÚL" disabled value="<?php echo $membresia['serial'];?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <label for="apellido_paterno_fisica_mostrar">Apellido Paterno</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="text" class="form-control mayusculas" name="apellido_paterno_saldos_mostrar" autocomplete="off" maxlength="15" id="apellido_paterno_saldos_mostrar" placeholder="P. EJ. BELLO" disabled value="<?php echo $membresia['apellido_paterno'];?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <label for="apellido_materno_saldos_mostrar">Apellido Materno</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="text" class="form-control mayusculas" name="apellido_materno_saldos_mostrar" autocomplete="off" maxlength="15" id="apellido_materno_saldos_mostrar" placeholder="P. EJ. MENA" disabled value="<?php echo $membresia['apellido_materno'];?>">
                                                    </div>
                                                </div>
                                            </div>
                                            
                                        </div>-->
                                        <!-- -->
                                        <!-- Tabla | Graficos -->
                                        <div class="col-lg-12">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <h3>Servicios/Saldos</h3>
                                                    <hr>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="col-sm-12" >
                                                        <!--
                                                        <div class="col-sm-6">
                                                            <label for="fecha_saldos-c">Fecha*</label>
                                                            <div class="form-group valid-required">
                                                                 <div class="form-line input-group fecha">
                                                                    <input type="text" class="form-control" name="fecha_saldos_c" id="fecha_saldos_c" placeholder="dd-mm-yyyy" required>
                                                                    <span class="input-group-addon">
                                                                         <span class="glyphicon glyphicon-calendar"></span>
                                                                    </span>
                                                                 </div>
                                                                 
                                                            </div>
                                                        </div> -->
                                                        <div class="col-sm-6" style="z-index: 99999999;">
                                                            <label for="plan_membresia_registrar">Renovaciones*</label>
                                                            <select name="renovaciones_saldos_c" id="renovaciones_saldos_c" required class="form-control">
                                                                <option value="" selected>Seleccione</option>
                                                                <?php $c=1; 

                                                                ?>
                                                                <?php foreach ($renovaciones as $renova): 
                                                                    if($c==$cuantas_renovaciones){
                                                                        $id_reservacion = $c."*actual";
                                                                    }else{
                                                                        $id_reservacion = $c."*";
                                                                    }
                                                                ?>
                                                                    <option value="<?= $id_reservacion ?>" <?php if($c==$cuantas_renovaciones){ echo "selected";} ?> ><?= $renova; ?></option>
                                                                <?php $c++; ?>    
                                                                <?php endforeach ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div onclick="filtrarDatatable()" class="btn btn-primary waves-effect" style="z-index: 99999999;margin-top: 25px;">Filtrar</div>
                                                        </div>
                                                          
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="row">    
                                                            <div class="col-sm-6">
                                                                <h4>Plan</h4>
                                                                <div id="planesSaldos">
                                                                    
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <h4>Paquetes</h4>
                                                                <div id="paquetesSaldos">
                                                                    
                                                                </div>
                                                                </hr>
                                                            </div>
                                                        </div>    
                                                    </div>    
                                                    <div class="col-lg-12">
                                                        <h4>Recargos: Servicios contratados</h4>
                                                        <hr>
                                                    </div>
                                                    <div class="col-sm-12" >
                                                        <table class="table table-bordered table-striped" id="tablaRecargos">
                                                            <thead>
                                                                <tr>
                                                                    <th>Servicios</th>
                                                                    <th>Contratados</th>
                                                                    <th>Consumidos</th>
                                                                    <th>Disponibles</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody></tbody>
                                                        </table>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <h4>Recargos: Servicios adicionales</h4>
                                                        <hr>
                                                    </div>
                                                    <div class="col-sm-12" >
                                                        <table class="table table-bordered table-striped" id="tablaRecargosAdicionales">
                                                            <thead>
                                                                <tr>
                                                                    <th>Id Servicios</th>
                                                                    <th>Servicios</th>
                                                                    <th>Cantidad</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody></tbody>
                                                        </table>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <h4>Jornadas</h4>
                                                        <hr>
                                                    </div>
                                                    <div class="col-sm-12" >
                                                        <table class="table table-bordered table-striped" id="tablaJornadas">
                                                            <thead>
                                                                <tr>
                                                                    <th>Jornada</th>
                                                                    <th>Contratados</th>
                                                                    <th>Consumidos</th>
                                                                    <th>Disponible</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody></tbody>
                                                        </table>
                                                    </div>
                                                     <div class="col-lg-12">
                                                        <h4>Reservaciones</h4>
                                                        <hr>
                                                    </div>
                                                    <div class="col-sm-12" >
                                                        <table class="table table-bordered table-striped" id="tablaReservaciones">
                                                            <thead>
                                                                <tr>
                                                                    <th>Reservación</th>
                                                                    <th>Sala</th>
                                                                    <th>Contratados</th>
                                                                    <th>Consumidos</th>
                                                                    <th>Disponible</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr></tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- -->
                                    </form>
                                </div> 
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
    <script src="<?=base_url();?>assets/cpanel/Membresia/js/saldos.js"></script>
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
