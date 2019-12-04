<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<html lang="es-MX">
<head>
	<title>Cotización UrbanHub</title>
  <style>
    @page { margin: 180px 50px; }
    #header { position: fixed; left: 0px; top: -180px; right: 0px; height: 150px;text-align: center; }
    #footer { position: fixed; left: 0px; bottom: -100px; right: 0px; height: 150px; }
    /*#footer .page:after { content: counter(PAGE_COUNT); }*/

    table {
		margin-left: 5px;
		margin-right: 10px;
		/*font-size: 15px;*/
		width: 100%;
		
		text-transform: uppercase;
	}


  </style>
<body>
  <div id="header">
    <img src="./assets/template/dompdf/img/banner_superior.png" style="width: 620px;margin-top:10px;right: 5px;">
  </div>
  <div id="footer">
    <p class="page"><img src="./assets/template/dompdf/img/banner_inferior.png" style="width: 685px;"><!-- pagina --><?php $PAGE_NUM ?> </p>
  </div>
  <div id="content">
   	

   	<p style="float: right;margin-right: 30px;font-size: 15px;">
		<?php 
			$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
			$fecha2 = (array)$arreglo_datos["fec_regins"];
			$fecha_arr = $fecha2["date"];
			$fecha = explode(" ",$fecha_arr);
			$fecha_temp = strtotime($fecha[0]); 
		?>
		<?=date('d', $fecha_temp)." de ".$meses[date('n', $fecha_temp)-1]. " del ".date('Y', $fecha_temp)?>
	</p>
	
	<br><br>
	
	<h3 style="position: absolute; right: 30px;">Atte.  <?=$arreglo_datos["nombre_prospecto"];?></h3>
	
	<br><br>
	<h3 style="position: absolute; right: 30px;"></h3>

	<br><br><br>




	<div style="width: 100%;"><table><tr><td style="width: 100%; border-bottom: 2px solid #528bbb;text-align: right;font-size: 12px;margin-right: 60px;">P<span style="text-transform: lowercase;">or éste medio le envío el siguiente presupuesto:</span> <span style="border: 2px solid yellow;background-color: yellow;font-size: 18px;">&nbsp;&nbsp;&nbsp;<?=$arreglo_datos["numero_cotizacion"]?>&nbsp;&nbsp;&nbsp;</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr></table></div>

	<div style="width: 100%;"><table><tr><td style="width: 100%; border-bottom: 2px solid #528bbb;text-align: left;font-size: 14px;margin-left: 60px;color: #023a73;font-weight: bold;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PLANES & PAQUETES</td></tr></table></div>

	

	<?php $total_mensual = 0 ?>	
			<?php foreach ($arreglo_datos["data_plan"] as $key => $value): ?>
				
				<?php $total_mensual = $total_mensual + ($arreglo_datos["data_planes"][$key]["paquete"]["precio"] * $value->cant_trabajadore) ?>

				<div style="width: 100%;background-color: #bbd1e0;border-bottom: 2px solid #528bbb;">
					<table style="text-align: center;font-size: 12px;font-weight: bold;">
						<thead>
							<tr style="color: #3D72C8;">
								<th colspan="1">TIPO</th>
								<th colspan="1">VIGENCIA</th>
								<th colspan="1">USUARIOS</th>
								<th colspan="3" style="text-align: left;">SERVICIOS</th>
								<th colspan="1" style="text-align: right;">P.U / MENSUAL</th>
							</tr>
						</thead>
							
						<tbody>
							

	            				<tr>
	                				<td colspan="1" style="text-align: left;"><?= $arreglo_datos["data_planes"][$key]["paquete"]["descripcion"] ?></td>
	                				<td colspan="1"><?=$value->plazo?></td>
	                				<td colspan="1"><?=$value->cant_trabajadore;?></td>
	                				<td colspan="3" style="text-align: left;">
	                					<?php foreach ($arreglo_datos["data_planes"][$key]["paquete"]["servicios"]["data_service"] as $key2 => $value2): ?>
	                						<?= $value2["descripcion"]." - ".number_format($value2["monto"], 2)."<br>" ?>
	                					<?php endforeach ?>
	                				</td>
	                				<td colspan="1" style="text-align: right;">$<?=number_format($arreglo_datos["data_planes"][$key]["paquete"]["precio"] * $value->cant_trabajadore,2) ?></td>
	                			</tr>
	                    	
						</tbody>
						<tfoot>
							<tr>
						    	<td colspan="5"></td>
						      	<td colspan="1" style="text-align: center;color: #3D72C8;">STAR UP FEE</td>
						      	<td colspan="1" style="text-align: right;color: #000;">$<?=number_format($value->cant_trabajadore * 2000,2)?></td>
						    </tr>
						    <tr>
						    	<td colspan="5"></td>
						      	<td colspan="1" style="text-align: center;background-color: #1b2831;color: #d4e5f1;">SUB-TOTAL</td>
						      	<td colspan="1" style="text-align: right;font-size: 16px;color: #3D72C8;">$<?=number_format(($arreglo_datos["data_planes"][$key]["paquete"]["precio"] * $value->cant_trabajadore) + $value->cant_trabajadore * 2000,2)?></td>
						    </tr>
						</tfoot>
					</table>
				</div>

				<br><br>
			<?php endforeach ?>


			


			<br>

			<div style="width: 50%;">
				<table style="text-align: center;font-size: 12px;font-weight: bold;">
					<tr>
					    <td colspan="1" style="text-align: center;background-color: #1b2831;color: #d4e5f1;">TOTAL</td>
					    <td colspan="1" style="background-color: #bbd1e0;text-align: right;font-size: 16px;color: #3D72C8;">$<?=number_format($total_mensual,2)?> MXN</td>
					</tr>
				</table>
			</div>
			
			<div style="width: 50%;position: absolute; left: 5px;text-align: left;font-size: 12px;font-weight: bold;">
				* Esta Cotización tiene vigencia de 30 días<br>
				* Precios en moneda nacional<br>
				* Nuestros precios incluyen IVA.
			</div>

			<div style="width: 40%;position: absolute; right: 5px;text-align: right;font-size: 12px;font-weight: bold;color: #023a73;border-top: 2px solid #528bbb;">
				Paulina Cruz Rodriguez<br>
				Gerente de Operaciones<br>
				984 254 5636 / paulina.cruz@urbanhub.mx
			</div>

			<br><br><br><br><br>

			<div style="width: 100%;">
				<table style="text-align: center;font-size: 12px;font-weight: bold;background-color: #bbd1e0;">
					<tr style="color: #3D72C8;">
					    <th>REQUISITOS DE CONTRATACIÓN</th>
					    <th>DOCUMENTACIÓN</th>
					</tr>
					<tr>
					    <td>Primer mes de renta</td>
					    <td>Copia Identificación oficial</td>
					</tr>
					<tr>
					    <td>Star Up Fee (Pago único $ 2,000.00 MXN)</td>
					    <td>Comprobante de Domicilio</td>
					</tr>
					<tr>
					    <td>Firma contrato</td>
					    <td>CURP</td>
					</tr>
					<tr>
					    <td>Solicitud de servicios</td>
					    <td>RFC</td>
					</tr>
					<tr>
					    <td>Reglas de convivencia </td>
					    <td>Carta simple describiendo actividad profesional</td>
					</tr>
					<tr>
					    <td></td>
					    <td>Datos tarjeta de crédito</td>
					</tr>
				</table>
			</div>








   <!--  <p style="page-break-before: always;">the second page</p> -->
  </div>
</body>
</html>

<script type="text/php">
if ( isset($pdf) ) { 
    $pdf->page_script('
        if ($PAGE_COUNT > 1) {
            $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
            $size = 12;
            $pageText = "" . $PAGE_NUM . "/" . $PAGE_COUNT;
            $y = 15;
            $x = 520;
            $pdf->text($x, $y, $pageText, $font, $size);
        } 
    ');
}
</script>