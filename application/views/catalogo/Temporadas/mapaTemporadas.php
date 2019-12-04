<?php foreach ($datos as $clave => $valor) { ?>
<tr>
	<th><?php echo $valor["codigo_plan"];?></th>
    <th><?php echo $valor["nombre_plan"];?></th>
    <th><?php echo $valor["temporada"];?></th>
    <th><?php echo $valor["fecha_desde"];?></th>
    <th><?php echo $valor["fecha_hasta"];?></th>
    <th><?php echo $valor["costo_original"];?></th>
    <th><?php echo $valor["ajuste"];?></th>
    <th><?php echo $valor["costo_temporada"];?></th>
    <th style="width: 17%;"></th>
</tr>
<?php } ?>