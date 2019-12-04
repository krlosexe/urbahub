<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<html lang="es-MX" style="margin: 20px 50px 20px 50px;">
	<head>
		<title>Cotización UrbanHub</title>

		<link rel="stylesheet" href="./assets/template/dompdf/css/dompdf.css" type="text/css"/>
		<style type="text/css">
			/*th {
				text-align: left;
			}
			td {
				border-bottom: 1px solid #000000;
				margin-left: 50px;
				margin-right: 70px;
				text-transform: uppercase;
			}*/
			table {
				margin-left: 5px;
				margin-right: 10px;
				/*font-size: 15px;*/
				width: 100%;
				text-transform: uppercase;
			}
			/*hr {
				page-break-after: always;
				border: 0;
				margin: 0;
				padding: 0;
			}*/*/
		</style>
		
	</head>
	<body>	
			<br>

			<img src="./assets/template/dompdf/img/banner_superior.png" style="width: 620px;position: absolute; right: 5px;">

			<br><br><br><br><br><br><br><br><br>
			<?php 
				
			?>
			<p style="float: right;margin-right: 30px;font-size: 15px;">
				<!--<?php 
					/*$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
					$fecha2 = (array)$arreglo_datos["fec_regins"];
					$fecha_arr = $fecha2["date"];
					$fecha = explode(" ",$fecha_arr);
					$fecha_temp = strtotime($fecha[0]); */
				?>
				<?=date('d', $fecha_temp)." de ".$meses[date('n', $fecha_temp)-1]. " del ".date('Y', $fecha_temp)?></p>-->
			
			<br><br>
			
			<h3 style="position: absolute; right: 30px;">Cliente: <?=$nombres_clientes ?></h3>
			<br><br><br>
			
			<h3 style="position: absolute; right: 30px;">Vendedor: <?=$vendedor ?></h3>
			
			<br><br><br>

			<div style="width: 100%;"><table><tr><td style="width: 100%; text-align: right;font-size: 12px;margin-right: 60px;">Cobranza número:</span> <span style="border: 2px solid yellow;background-color: yellow;font-size: 18px;">&nbsp;&nbsp;&nbsp;<?=$cobranza["numero_cobranza"]?>&nbsp;&nbsp;&nbsp;</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr></table></div>
			<h3 style="position: absolute; right: 30px;">Estado de cuenta:</h3>
			<br><br><br>
			<div style="width: 100%;"><table><tr><td style="width: 100%;border-top: 2px solid #528bbb; border-bottom: 2px solid #528bbb;text-align: left;font-size: 14px;margin-left: 60px;color: #023a73;font-weight: bold;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Datos generales</td></tr></table></div>
			<div style="width: 100%;margin-bottom: 10px;background-color: #bbd1e0;">
				<div style="float:left;">
					<span style="font-weight: bold;padding-left: 5px;">
					#Cotización:
					</span>
					<span>
						<?=$numero_cotizacion; ?>
					</span>
				</div>
				<div style="margin-left:50px;float:left;">
					<span style="font-weight: bold">
					Vigencia:
					</span>
					<span>
						<?=$vigencia;?>
					</span>
				</div>
				<div style="margin-left:50px;float:left;">
					<span style="font-weight: bold">
					Estatus:
					</span>
					<span>
						<?=$cobranza["condicion"]; ?>
					</span>
				</div>
				<div style="margin-left:60px;float:left;">
					<span style="font-weight: bold">
					Fecha:
					</span>
					<?php 
					$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
					$fecha2 = (array)$cobranza["fec_regins"];
					$fecha_arr = $fecha2["date"];
					$fecha = explode(" ",$fecha_arr);
					$fecha_temp = strtotime($fecha[0]); 
				?>
					<span>
						<?=date('d', $fecha_temp)." de ".$meses[date('n', $fecha_temp)-1]. " del ".date('Y', $fecha_temp)?></p>
					</span>
				</div>
				<div style="clear: both"></div>
			</div>			
			<br>
			<!-- Detalles: -->
			<div style="width: 100%;"><table><tr><td style="width: 100%; border-bottom: 2px solid #528bbb;border-top: 2px solid #528bbb;text-align: left;font-size: 14px;margin-left: 60px;color: #023a73;font-weight: bold;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DETALLE</td></tr></table></div>

			<div style="width: 100%;background-color: #bbd1e0;">
				<table style="text-align: center;font-size: 12px;font-weight: bold;">
					<thead>
						<tr style="color: #3D72C8;">
							<th colspan="1">#</th>
							<th colspan="1">CONCEPTO</th>
							<th colspan="1">MONTOS</th>
						</tr>
					</thead>
					<tbody>
        				<tr>
            				<td colspan="1" style="text-align: center;">1</td>
            				<td colspan="1">Inscripción</td>
            				<td colspan="1"><?=$monto_inscripcion?></td>
            			</tr>
            			<tr>
            				<td colspan="1" style="text-align: center;">2</td>
            				<td colspan="1">Mensualidad</td>
            				<td colspan="1"><?=$monto_mensualidad_total?></td>
            			</tr>
					</tbody>
				</table>
			</div>
			<!-- -->
			<!-- Estados de cuentas: -->
			<br><br><br>
			<div style="width: 100%;"><table><tr><td style="width: 100%; border-bottom: 2px solid #528bbb;border-top: 2px solid #528bbb;text-align: left;font-size: 14px;margin-left: 60px;color: #023a73;font-weight: bold;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ESTADO DE CUENTAS</td></tr></table></div>

			<div style="width: 100%;background-color: #bbd1e0;">
				<table class="table table-bordered table-striped table-hover tableTotalRegistrar" id="tableCobranza">
            		<thead>
            			<tr style="font-size: 13px; background: #bbd1e0;color: #3D72C8;">
            				<th style="text-align: center; padding: 5px !important;"># oper.</th>
            				<th style="text-align: center; padding: 5px !important;">Recibo</th>
            				<th style="text-align: center; padding: 5px !important;">Mes</th>
            				<th style="text-align: center; padding: 5px !important;">Tipo</th>
            				<th style="text-align: center; padding: 5px !important;">Concepto</th>
            				<th style="text-align: center; padding: 5px !important;">Fecha de Movimiento</th>
            				<th style="text-align: center; padding: 5px !important;">Cargo</th>
            				<th style="text-align: center; padding: 5px !important;">Abono</th>
            				<th style="text-align: center; padding: 5px !important;">Saldo</th>
            			</tr>
            		</thead>
            		<tbody style="text-align: center;">
            			<?php foreach($recibos as $recibo){ ?>
            				<tr style="padding-bottom: 20px;">
            					<!-- border-bottom: 0px solid #528bbb;border-collapse: collapse; -->
	            				<td style="font-weight: bold;"> 
	            					<?=$recibo->operacion?>
	            				</td>
	            				<td style="font-weight: bold;"> 
	            					<?=$recibo->numero_secuencia?>
	            				</td>
	            				<td style="font-weight: bold;"> 
	            					<?=$recibo->mes?>
	            				</td>
								<td style="font-weight: bold;"> 
									<?=$recibo->tipo_operacion?>
								</td>
								<td style="font-weight: bold;"> 
									<?=$recibo->concepto?>
								</td>
								<?php
									$fechaC = (array)$recibo->fecha_contable;
									$fecha_arr = $fechaC["date"];
									$fecha = explode(" ",$fecha_arr);
									$fecha_contable = strtotime($fecha[0]); 
								?>
								<td style="font-weight: bold;">
									<?=date('d', $fecha_contable)."-".date('m', $fecha_contable). "-".date('Y', $fecha_contable)?></p>
								</td>
								<td style="font-weight: bold;"> 
									<?=number_format($recibo->cargo,2)?>
								</td>
	            				<td style="font-weight: bold;"> 
	            					<?=number_format($recibo->abono,2)?>
	            				</td>
	            				<td style="font-weight: bold;"> 
	            					<?=number_format($recibo->saldo,2)?>
	            				</td>
            				</tr>
            			<?php $monto_total = $saldo_total_pendiente; }	?>
            		</tbody>
            		<tfoot>
					    <tr>
					    	<td colspan="7"></td>
					      	<td colspan="1" style="text-align: center;background-color: #1b2831;color: #d4e5f1;">SALDO TOTAL</td>
					      	<td colspan="1" style="text-align: right;font-size: 16px;color: #3D72C8;">$<?=number_format($monto_total,2)?></td>
					    </tr>
					</tfoot>
            	</table>
			</div>
			<!-- -->

			<img src="./assets/template/dompdf/img/banner_inferior.png" style="width: 685px;">

	</body>
</html>
