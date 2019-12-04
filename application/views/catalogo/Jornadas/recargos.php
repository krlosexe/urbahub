<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
             <div class="body">
            	<div class="table-responsive">
            		<form name="form_recargos_actualizar" id="form_recargos_actualizar" method="post">
                		<div class="col-sm-12" >
                        	<table class="table table-bordered table-striped table-hover" id="tableRegistrarServiciosPlan">
                        		<thead>
                        			<tr>
                        				<th>Código</th>
                        				<th>Título</th>
                        				<th>Cantidad</th>
                        				<th>Consumido</th>
                        				<th>Disponible</th>
                        			</tr>
                        		</thead>
                        		<tbody id="tbody_servicios"></tbody>
                        	</table>
                        </div>
                        <div class="col-sm-12" style="padding: 0px;">
                        	<div class="col-sm-4">
                        		<label for="servicio_actualizar">Servicios*</label>
                            	<select name="servicio" id="servicio_actualizar" class="form-control" required>
                            		<option value="" selected>Seleccione</option>
                            		<?php foreach ($servicios as $servicio): ?>
                            			<?php if ($servicio['status']==true){ ?>
                            			<option value="<?= $servicio["id_servicios"]."|".$servicio['tipo'];?>" status="<?=$servicio['status'] ?>" tipo="<?=$servicio['tipo'] ?>"><?= $servicio["descripcion"]; ?></option>
                            			<?php } ?>
                            		<?php endforeach ?>
                            	</select>
                            </div>
                            <div class="col-sm-6">
                        		<label for="valor_actualizar">Valor*</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" class="form-control mayusculas" name="valor" id="valor_actualizar" placeholder="P. EJ. XXXX (X)" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-sm-2" style="padding-top: 25px;">
                            	<button type="button" class="btn btn-primary waves-effect" onclick="agregarServicio('#servicio_actualizar', '#tableRegistrar', '#valor_actualizar')">Agregar</button>
                            </div>
                            <div class="col-sm-12" >
                            	<table class="table table-bordered table-striped table-hover" id="tableRegistrar">
                            		<thead>
                            			<tr>
                            				<th>Código</th>
                            				<th>Título</th>
                            				<th>Valor</th>
                            				<th>Acciones</th>
                            				<th>&nbsp;</th>
                            			</tr>
                            		</thead>
                            		<tbody></tbody>
                            	</table>
                            </div>
                            <div class="col-sm-12" >
                            	<input type="text" name="monto_total_recargo" id="monto_total_recargo" style="float: right;">
                            </div>
                            <div class="col-sm-12" >
                            	<input type="text" name="monto_pagar" id="monto_pagar" style="float: right;">
                            </div>	
                        </div>
            			<br>
            			<div class="col-sm-4 col-sm-offset-5">
                            <button type="button" onclick="regresar('#cuadro2')" class="btn btn-primary waves-effect">Regresar</button>
                            <input type="submit" value="Registrar" class="btn btn-success waves-effect">
                        </div>
                    </form>    
                </div>
            </div>
        </div>
    </div>
</div>