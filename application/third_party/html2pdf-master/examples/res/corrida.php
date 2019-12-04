
<style>
    tr, td, th{
        padding: 5px;
        text-align: center;
    }

    table {
        border: none;
        font-size: 11px;
        }


    th{
        
        font-weight: lighter;
    }
  
    ul{
        list-style: none;
    }

    li{
        color: #fff;
        padding-bottom: 3px;
    }


    table {
		margin-left: 5px;
		margin-right: 10px;
		/*font-size: 15px;*/
		width: 100%;
		
		text-transform: uppercase;
	}


</style>
<?php 
    // $this->load->model('Proyectos_model');
    // $cobranza = $this->Cobranza_model->getcobranzaventa($id_venta);
?>

<page backtop="45mm" backbottom="40mm" backleft="20mm" backright="20mm">
    <page_header>
        <br><br>
        <img src="<?= $head_page?>" style="width: 85%; margin-left: 50px"> 
    </page_header>
    

    <page_footer>
        <img src="<?= $footer_page?>" style="width: 100%;"> 
        
        <!-- <p style="position: relative; top: -45px; margin-left: 75px; font-size: 10px; color: #fff">**Precios y condiciones sujetos a cambios sin previo aviso. </p> -->
        <p style="position: relative; top: -50px; margin-left: 690px; font-size: 14px;color: #fff">[[page_cu]]/[[page_nb]]</p>
    </page_footer>
    <br>
    <table style="width: 94%; margin-top: 20px">
        <tr>
            <td style="text-align: left; width: 33%; background: #fff; border: none;">
               
            </td>
            <td style="text-align: center;  font-size: 13px;  width: 24%;background: #fff; border: none;">
              
            </td>
            <td style="text-align: right;    width: 50%;background: #fff; border: none">
            <p style="float: right;font-size: 15px;">
                <?php 
                    $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
                    $fecha2 = (array)$temp_correo["arreglo_datos"]["fec_regins"];
                    $fecha_arr = $fecha2["date"];
                    $fecha = explode(" ",$fecha_arr);
                    $fecha_temp = strtotime($fecha[0]); 
                ?>
                <?=date('d', $fecha_temp)." de ".$meses[date('n', $fecha_temp)-1]. " del ".date('Y', $fecha_temp)?>
            </p>
	
	        <h3 style="margin: 0">Atte.  <?=$temp_correo["arreglo_datos"]["nombre_prospecto"];?></h3>
            <br><br>
            </td>
        </tr>
    </table>


    <div style="width: 100%;"><table><tr><td style="width: 100%; border-bottom: 2px solid #528bbb;text-align: right;font-size: 12px;margin-right: 60px;">P<span style="text-transform: lowercase;">or éste medio le envío el siguiente presupuesto:</span> <span style="border: 2px solid yellow;background-color: yellow;font-size: 18px;">&nbsp;&nbsp;&nbsp;<?=$temp_correo["arreglo_datos"]["numero_cotizacion"]?>&nbsp;&nbsp;&nbsp;</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr></table></div>

	<div style="width: 100%;"><table><tr><td style="width: 100%; border-bottom: 2px solid #528bbb;text-align: left;font-size: 14px;margin-left: 60px;color: #023a73;font-weight: bold;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $membresia == true ? 'PLANES & PAQUETES' : 'Servicios' ?></td></tr></table></div>



	<?php if ($membresia == true): ?>
		<?php $total_mensual = 0 ?>	
   		<?php $count = 0 ?>	

			<?php foreach ($temp_correo["arreglo_datos"]["data_plan"] as $key => $value): ?>

           		 <?php $count++ ?>	

				<?php $total_mensual = $total_mensual + ($temp_correo["arreglo_datos"]["data_planes"][$key]["paquete"]["precio"] * $value->cant_trabajadore) ?>

				<div style="width: 100%;background-color: #bbd1e0;border-bottom: 2px solid #528bbb;">
					<table style="text-align: center;font-size: 12px;font-weight: bold;">
						<thead>
							<tr style="color: #3D72C8;">
								<th colspan="1" style="width:100px">TIPO</th>
								<th colspan="1">VIGENCIA</th>
								<th colspan="1">USUARIOS</th>
								<th colspan="3" style="text-align: left;width:245px">SERVICIOS</th>
								<th colspan="1" style="text-align: right;">P.U / MENSUAL</th>
							</tr>
						</thead>
							
						<tbody>
							

	            				<tr>
	                				<td colspan="1" style="text-align: left;"><?= $temp_correo["arreglo_datos"]["data_planes"][$key]["paquete"]["descripcion"] ?></td>
	                				<td colspan="1"><?=$value->plazo?></td>
	                				<td colspan="1"><?=$value->cant_trabajadore;?></td>
	                				<td colspan="3" style="text-align: left;">
	                					<?php foreach ($temp_correo["arreglo_datos"]["data_planes"][$key]["paquete"]["servicios"]["data_service"] as $key2 => $value2): ?>
	                						<?= $value2["descripcion"]." - ".number_format($value2["monto"], 2)."<br>" ?>
	                					<?php endforeach ?>
	                				</td>
	                				<td colspan="1" style="text-align: right;">$<?=number_format($temp_correo["arreglo_datos"]["data_planes"][$key]["paquete"]["precio"] * $value->cant_trabajadore,2) ?></td>
	                			</tr>
	                    	
						</tbody>
						<tfoot>
							<tr>
						    	<td colspan="5"></td>
						      	<td colspan="1" style="text-align: right;color: #3D72C8;"> <?= $membresia == true ? 'STAR UP FEE '.$count : '' ?> </td>
						      	<td colspan="1" style="text-align: right;color: #000;"><?= $membresia == true ? '$'.number_format($value->cant_trabajadore * 1000,2) : '' ?></td>
						    </tr>
						    <tr>
						    	<td colspan="5"></td>
						      	<td colspan="1" style="text-align: right;background-color: #1b2831;color: #d4e5f1;">SUB-TOTAL</td>
						      	<td colspan="1" style="text-align: right;font-size: 16px;color: #3D72C8;">$<?=number_format(($temp_correo["arreglo_datos"]["data_planes"][$key]["paquete"]["precio"] * $value->cant_trabajadore) + $value->cant_trabajadore * 1000,2)?></td>
						    </tr>
						</tfoot>
					</table>
				</div>

				<br><br>

                <?php if ($count < sizeof($temp_correo["arreglo_datos"]["data_plan"])): ?>
                    <div style="page-break-after: always"></div>
	            <?php endif ?>

                
			<?php endforeach ?>
		<?php endif ?>



		<?php if ($membresia == false): ?>
			<div style="width: 100%;background-color: #bbd1e0;border-bottom: 2px solid #528bbb;">
				<table style="text-align: center;font-size: 12px;font-weight: bold;">
					<thead>
						<tr style="color: #3D72C8;">
							<th style="width:100px">SERVICIOS</th>
							<th></th>
							<th></th>
							<th style="text-align: center; width:130px">Monto PU</th>
							<th style="text-align: center;width:130px">Cantidad</th>
							<th style="text-align: center;">Monto Total</th>
						</tr>
					</thead>
					<tbody>
			<?php $count = 0 ?>	
			<?php foreach ($data_service as $key => $value): ?>

						
							

				<tr>
					<td style="text-align: center;"><?= $value->name_service ?></td>
					<td></td>
					<td></td>
					<td  style="text-align: center;">$<?=number_format($value->monto,2) ?></td>
					<td  style="text-align: center;">
						<?=$value->cantidad;?>
					</td>
					<td  style="text-align: center;">$<?=number_format(($value->cantidad * $value->monto),2) ?></td>
				</tr>
	                    	
			
			<?php endforeach ?>

			</tbody>
			<!-- <tfoot>
				<tr>
					<td colspan="5"></td>
					<td colspan="1" style="text-align: right;color: #3D72C8;"> <?= $membresia == true ? 'STAR UP FEE '.$count : '' ?> </td>
					<td colspan="1" style="text-align: right;color: #000;"><?= $membresia == true ? '$'.number_format($value->cant_trabajadore * 1000,2) : '' ?></td>
				</tr>
				<tr>
					<td colspan="5"></td>
					<td colspan="1" style="text-align: right;background-color: #1b2831;color: #d4e5f1;">SUB-TOTAL</td>
					<td colspan="2" style="text-align: right;font-size: 16px;color: #3D72C8;">$<?=number_format(($temp_correo["arreglo_datos"]["monto_total"]),2)?></td>
				</tr>
			</tfoot> -->
		</table>
	</div>

	<br><br>


	<!-- <?php if ($count < sizeof($data_service)): ?>
		<div style="page-break-after: always"></div>
	<?php endif ?> -->

		<?php endif ?>

    
	

            <br>

			<div style="width: 50%;">
				<table style="text-align: center;font-size: 12px;font-weight: bold;">
					<tr>
					    <td colspan="1" style="text-align: center;background-color: #1b2831;color: #d4e5f1;">TOTAL</td>
					    <td colspan="1" style="background-color: #bbd1e0;text-align: right;font-size: 16px;color: #3D72C8;">$<?=number_format(($temp_correo["arreglo_datos"]["monto_total"]),2)?> MXN</td>
					</tr>
				</table>
			</div>



            <div style="width: 50%;">
				<table style="text-align: left;font-size: 9px;font-weight: bold;">
					<tr>
					    <td style="text-align: left; text-tranform: lowercase">* Esta Cotización tiene vigencia de 30 días</td>
					</tr>
                    <tr>
					    <td style="text-align: left; text-tranform: lowercase">* Precios en moneda nacional</td>
					</tr>
                    <tr>
					    <td style="text-align: left; text-tranform: lowercase">* Nuestros precios incluyen IVA.</td>
					</tr>
                    
				</table>
			</div>


			
			<div style="width: 50%;position: relative; left: 5px; top: -100px; text-align: left;font-size: 12px;font-weight: bold;">
				<br>
				<br>
				
			</div>

			<div style="width: 40%;position: relative; right: 5px;text-align: right;font-size: 12px;font-weight: bold;color: #023a73;border-top: 2px solid #528bbb;">
				Paulina Cruz Rodriguez<br>
				Gerente de Operaciones<br>
				984 254 5636 / paulina.cruz@urbanhub.mx
			</div>

			<br><br>
		    

			<?php if ($membresia == true): ?>
				<?php if ($tipo_persona == "moral"): ?>
					<div style="width: 100%;">
						<table style="text-align: center;font-size: 9px;font-weight: bold;background-color: #bbd1e0;">
							<tr style="color: #3D72C8;">
								<th>REQUISITOS DE CONTRATACIÓN</th>
								<th>DOCUMENTACIÓN</th>
							</tr>
							<tr>
								<td>Primer mes de renta</td>
								<td>Acta Constitutiva</td>
							</tr>
							<tr>
								<td>Star Up Fee (Pago único $ 1,000.00 MXN)</td>
								<td>Poder del Representante legal notarizado</td>
							</tr>
							<tr>
								<td>Firma contrato</td>
								<td>Identificacion oficial, Curp y comp. de domicilio del representante legal no mayor<br> a 3 meses</td>
							</tr>
							<tr>
								<td>Solicitud de servicios</td>
								<td>Alta ante hacienda y rfc</td>
							</tr>
							<tr>
								<td>Reglas de convivencia </td>
								<td>opinion de cumplimiento de obligaciones fiscales</td>
							</tr>
							<tr>
								<td></td>
								<td>Carta en hoja membretada describiendo la actividad de la empresa</td>
							</tr>
						</table>
					</div>
				<?php endif ?>







				<?php if ($tipo_persona == "fisica"): ?>
					<div style="width: 100%;">
						<table style="text-align: center;font-size: 12;font-weight: bold;background-color: #bbd1e0;">
							<tr style="color: #3D72C8;">
								<th>REQUISITOS DE CONTRATACIÓN</th>
								<th>DOCUMENTACIÓN</th>
							</tr>
							<tr>
								<td>Primer mes de renta</td>
								<td>Copia de identificacion oficial</td>
							</tr>
							<tr>
								<td>Star Up Fee (Pago único $ 1,000.00 MXN)</td>
								<td>Comprobante de domicilio fiscal no mayor a 3 meses</td>
							</tr>
							<tr>
								<td>Firma contrato</td>
								<td>Curp</td>
							</tr>
							<tr>
								<td>Solicitud de servicios</td>
								<td>Alta ante hacienda y rfc</td>
							</tr>
							<tr>
								<td>Reglas de convivencia </td>
								<td>opinion de cumplimiento de obligaciones fiscales</td>
							</tr>
							<tr>
								<td></td>
								<td>Carta simple describiendo su actividad profesional</td>
							</tr>
						</table>
					</div>
				<?php endif ?>
			<?php endif ?>

			





			

            



    <br>
</page>

