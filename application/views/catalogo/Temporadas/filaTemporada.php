<tr id="fila<?php echo $fila;?>" data="<?php echo $fila;?>" class="fila">
	<td style="text-align: center; padding: 0px 10px 0px 5px;">
		<input type="checkbox" id="check<?php echo $fila;?>" onclick="consultar_temporada_id(<?php echo $fila;?>)" class="chk-col-blue checkitem" data="" /><label for="check<?php echo $fila;?>"></label>
	</td>
	<td><?php echo $fila; ?></td>
	<td>
        <div class="form-group form-float" id="xfec_desde<?php echo $fila;?>">
    		<div class="form-line input-group fecha">
                <input type="text" class="form-control validar_fecha_calendario" maxlength="8" div_error="#xfec_desde<?php echo $fila;?>" name="fecha_desde<?php echo $fila;?>" id="fecha_desde<?php echo $fila;?>" placeholder="dd-mm-yyyy" required>
                <span class="input-group-addon validar_fecha_calendario2">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
        </div>
	</td>
	<td>
		<div class="form-group form-float fecha" id="xfec_hasta<?php echo $fila;?>">
            <div class="form-line input-group fecha">
                <input type="text" class="form-control validar_fecha_calendario" maxlength="8" div_error="#xfec_hasta<?php echo $fila;?>" name="fecha_hasta<?php echo $fila;?>" id="fecha_hasta<?php echo $fila;?>" placeholder="dd-mm-yyyy" required>
                <span class="input-group-addon validar_fecha_calendario2">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
        </div>
	</td>
	<td>
		<input type="text" class="form-control" id="ajuste_precio<?php echo $fila;?>" name="ajuste_precio<?php echo $fila;?>">
	</td>
	<td>
    	<div class="form-check">
    		<input class="form-check-input" type="radio" name="radio_ajustes<?php echo $fila;?>" id="mas<?php echo $fila;?>" value="mas" checked>
    		<label class="form-check-label" for="mas<?php echo $fila;?>">
			   Más
			</label>
		</div>	
		<div class="form-check">
    		<input class="form-check-input" type="radio" name="radio_ajustes<?php echo $fila;?>" id="menos<?php echo $fila;?>" value="menos">
    		<label class="form-check-label" for="menos<?php echo $fila;?>">
			   Menos
			</label>
		</div>
	</td>
	<td style="text-align: center; padding: 0px 10px 0px 5px;">
		<input type="checkbox" id="check_ajuste<?php echo $fila;?>" class="chk-col-blue"/>
		<label for="check_ajuste<?php echo $fila;?>"></label>
	</td>
</tr>