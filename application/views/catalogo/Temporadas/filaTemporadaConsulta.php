<?php foreach ($datos as $clave => $valor) { ?>
<tr id="fila<?php echo $valor["fila"];?>" data="<?php echo $valor["fila"];?>" class="fila">
	<td style="text-align: center; padding: 0px 10px 0px 5px;">
		<input type="checkbox" data="<?php echo $valor["id_temporadas"]; ?>" id="check<?php echo $valor["fila"];?>" onclick="consultar_temporada_id(<?php echo $valor["fila"];?>)" class="chk-col-blue checkitem"/><label for="check<?php echo $valor["fila"];?>"></label>
	</td>
	<td><?php echo $valor["fila"]; ?></td>
	<td>
        <div class="form-group form-float" id="xfec_desde<?php echo $valor["fila"];?>">
    		<div class="form-line input-group fecha">
                <input type="text" class="form-control validar_fecha_calendario" maxlength="8" div_error="#xfec_desde<?php echo $valor["fila"];?>" name="fecha_desde<?php echo $valor["fila"];?>" id="fecha_desde<?php echo $valor["fila"];?>" placeholder="dd-mm-yyyy" value="<?php echo $valor["fecha_desde"] ?>" required>
                <span class="input-group-addon validar_fecha_calendario2">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
        </div>
	</td>
	<td>
		<div class="form-group form-float fecha" id="xfec_hasta<?php echo $valor["fila"];?>">
            <div class="form-line input-group fecha">
                <input type="text" class="form-control validar_fecha_calendario" maxlength="8" div_error="#xfec_hasta<?php echo $valor["fila"];?>" name="fecha_hasta<?php echo $valor["fila"];?>" id="fecha_hasta<?php echo $valor["fila"];?>" placeholder="dd-mm-yyyy" value="<?php echo $valor["fecha_hasta"] ?>" required>
                <span class="input-group-addon validar_fecha_calendario2">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
        </div>
	</td>
	<td>
		<input type="text" class="form-control" id="ajuste_precio<?php echo $valor["fila"];?>" name="ajuste_precio<?php echo $valor["fila"];?>" value="<?php echo $valor["ajuste"] ?>">
	</td>
	<td>
    	<div class="form-check">
    		<input class="form-check-input" type="radio" name="radio_ajustes<?php echo $valor["fila"];?>" id="mas<?php echo $valor["fila"];?>"  <?php if($valor["operacion"]==true){?> checked <?php } ?> value="mas" >
    		<label class="form-check-label" for="mas<?php echo $valor["fila"];?>">
			   Más
			</label>
		</div>	
		<div class="form-check">
    		<input class="form-check-input" type="radio" name="radio_ajustes<?php echo $valor["fila"];?>" id="menos<?php echo $valor["fila"];?>" <?php if($valor["operacion"]==false){?> checked <?php } ?>  value="menos">
    		<label class="form-check-label" for="menos<?php echo $valor["fila"];?>">
			   Menos
			</label>
		</div>
	</td>
	<td style="text-align: center; padding: 0px 10px 0px 5px;">
		<input type="checkbox" id="check_ajuste<?php echo $valor["fila"];?>" <?php if($valor["aplicar"]==true){?>  checked <?php } ?> class="chk-col-blue"/>
		<label for="check_ajuste<?php echo $valor["fila"];?>"></label>
	</td>
</tr>
<?php } ?>