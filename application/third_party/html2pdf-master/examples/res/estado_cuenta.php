
<style>
    tr, td, th{
        padding: 5px;
        text-align: center;
    }

    table {
        border: none;
        font-size: 11px;
        }

    th, td {
        border: 1px solid #fff;
        
    }

    th{
        background: #001259;
        color: #fff;
        font-weight: lighter;
    }
    td{
        background: #fff;
        color: #3E3E3F;
    }

    ul{
        list-style: none;
    }

    li{
        color: #fff;
        padding-bottom: 3px;
    }
</style>
<?php 
    // $this->load->model('Proyectos_model');
    // $cobranza = $this->Cobranza_model->getcobranzaventa($id_venta);
?>

<page backtop="35mm" backbottom="35mm" backleft="20mm" backright="20mm">
    <page_header>
        <img src="<?= $head_page2?>" style="width: 100%;"> 
    </page_header>
    <page_footer>
       <img src="<?= $footer_page?>" style="width: 100%;"> 
        <p style="position: relative; top: -113px; margin-left: 75px; font-size: 10px;"><b style="color: #F6B501;">**Precios y condiciones sujetos a cambios sin previo aviso. </b></p>
        <p style="position: relative; top: -30px; margin-left: 990px; font-size: 10px;">[[page_cu]]/[[page_nb]]</p>
    </page_footer>
   
    <br>
    <br>


    <div id="head" style="position: absolute; top: -130px; left: -94px; z-index: 1000; width: 1140px;">
        <img src="<?= $head_page?>" style="width: 100%; height: 230px">
    </div>

    <div id="logo" style="position: absolute; top: -100px; left: -0px;">
        <img src="<?= $logo ?>" width="210" height="130">
    </div>

    <div id="empresa" style="position: absolute; top: -135px; left: 695px; width: 310px;">
        <ul style="font-size: 11px">
            <li><b><?= $nombre_empresa."<br>" ?></b></li>
            <li style="color: #fff">Direccion: <?= $direccion ?></li>
            <li style="color: #fff">Telefono:  <?= $telefono ?></li>
            <li style="color: #fff">Email:     <?= $correo ?></li>
        </ul>
    </div>


     <div id="data_venta" style="position: absolute; top: -35px; left: 695px; width: 310px;">
        <ul style="font-size: 10px">
            <li style="color: #fff; text-transform: uppercase;">Proyecto: <?= $proyecto ?> </li>
            <li style="color: #fff; text-transform: uppercase;">Cliente:  <?= $cliente ?></li>
            <li style="color: #fff; text-transform: uppercase;">Vendedor:  <?= $vendedor ?></li>
            <li style="color: #fff; text-transform: uppercase;">#corrida:  <?= $corrida ?></li>
            <li style="color: #fff; text-transform: uppercase;">Estatus:  <?= $status ?></li>
            <li style="color: #fff; text-transform: uppercase;">Lote:  <?= $lotes ?></li>
        </ul>
    </div>



    <div id="data_venta" style="position: absolute; top: -30px; left: 920px; width: 310px;">
        <ul style="font-size: 10px">
            <li style="color: #fff; text-transform: uppercase;"><?= date('d/m/Y')?></li>
        </ul>
    </div>


    <table style="width: 100%; margin-top: 60px">
        <tr>
            <td style="text-align: left; width: 33%; background: #fff; border: none;">
               
            </td>
            <td style="text-align: center;  font-size: 14px;  width: 34%;background: #fff; border: none;">
               <b>ESTADO DE CUENTA</b>
            </td>
            <td style="text-align: right;    width: 33%;background: #fff; border: none">
               
            </td>
        </tr>
    </table>


    <table style="width: 100%; margin-top: 30px;  border-collapse: collapse;">
        <tr style="text-transform: uppercase;">
            <th style="padding: 10px; width: 93px">
             Cuota Proxima Pendiente
            </th>
            <th style="padding: 10px; width: 93px">
              Saldo Cuota <br> Pendiente
            </th>

            <th style="padding: 10px; width: 93px">
              Monto Pagado
            </th>


            <th style="padding: 10px; width: 93px">
               Saldo Total <br> Pendiente
            </th>
            <th  style="padding: 10px; width: 93px">
               Dias de Morosidad
            </th>
            <th style="padding: 10px; width: 93px">
              % de Mora
            </th>
            <th style="padding: 10px; width: 93px">
               Monto por Morosidad
            </th>
        </tr>

        <tbody>  
            <tr>
                <td><?= $cuota_pendiente ?></td>
                <td><?= number_format($saldo_cuota_pendiente, 2, '.', ',') ?></td>
                <td><?= number_format(round($monto_pagado,2), 2, '.', ',') ?></td>
                <td><?= number_format($saldo_total_pendiente, 2, '.', ',') ?></td>
                <td><?= $dias_mora ?></td>
                <td><?= $porcentaje_mora ?></td>
                <td><?= number_format($monto_mora, 2, '.', ',') ?></td>
            </tr>
        </tbody>
    </table>



    <table style="width: 100%; margin-top: 30px; margin-left: 00px;  border-collapse: collapse;">
        <tr style="text-transform: uppercase;">
            <th style="background: #BCBDC0; color: #001259; width: 50px">
               #prod
            </th>
            <th style="background: #BCBDC0; color: #001259; width: 80px; padding: 12px">
               <br>Fecha de Venta
            </th>
            <th style="background: #BCBDC0; color: #001259; width: 80px; padding: 12px">
               Monto Total 
            </th>

            <th style="background: #BCBDC0; color: #001259; width: 60px">
               DESCUENTO
            </th>

            <th style="background: #BCBDC0; color: #001259; width: 80px">
               FINANCIAMIENTO
            </th>

            <th style="background: #BCBDC0; color: #001259; width: 90px">
               PORCENAJE FINANCIAMIENTO
            </th>

            <th style="background: #BCBDC0; color: #001259; width: 70px">
               <br>PRECIO FINAL
            </th>


            <th style="background: #BCBDC0; color: #001259; width: 60px; padding: 12px">
               Anticipo
            </th>
            <th style="background: #BCBDC0; color: #001259; width: 60px; padding: 12px">
               Saldo
            </th>
            
        </tr>

        <tbody>
            <tr>
                <td><?= $cantidad_producto ?></td>
                <td><?= $venta->fecha_regsitro ?></td>
                <td><?= number_format($venta->monto_total, 2, '.', ',') ?></td>
                <td><?= number_format($venta->monto_descuento + $venta->monto_descuento_especial, 2, '.', ',')  ?></td>
                <td><?= number_format($venta->monto_recargo, 2, '.', ',') ?></td>
                <td><?=  (($venta->monto_recargo * 100) / $venta->monto_total)."%"  ?></td>
                <td><?=  number_format((($venta->monto_total - ($venta->monto_descuento + $venta->monto_descuento_especial)) + $venta->monto_recargo) , 2, '.', ',')  ?></td>
                <td><?= number_format($venta->anticipo_monto, 2, '.', ',') ?></td>
                <td><?= number_format($venta->saldo, 2, '.', ',') ?></td>
                
            </tr>
        </tbody>


       
         <tr style="text-transform: uppercase;">
            <th colspan="2"  style="padding: 12px;width: 70px">
               Plazos
            </th>
            <th colspan="2" style="padding: 12px;width: 90px">
               Mensualidad
            </th>
            <th colspan="3">
               Forma de Pago
            </th>
            <th colspan="2" style="padding: 12px;width: 90px">
               Monto Cuotas
            </th>
        </tr>

        <tbody>
            <tr>
                <td colspan="2"><?= $venta->name_plazo ?></td>
                <td colspan="2"><?= number_format($venta->mensualidad, 2, '.', ',') ?></td>
                <td colspan="3"><?= $venta->name_fp ?></td>
                <td colspan="2"><?= number_format($venta->monto_cuotas, 2, '.', ',') ?></td>
            </tr>
        </tbody> 
        
    </table>



    <table style="margin-top: 30px;  border-collapse: collapse;">
        <thead>
            <tr>
                <th style="background: #FBB02B; color: #3E3E3F;width: 20px">#OPER.</th>
                <th style="background: #FBB02B; color: #3E3E3F;width: 37px">RECIBO</th>
                <th style="background: #FBB02B; color: #3E3E3F;width: 20px">MES</th>
                <th style="background: #FBB02B; color: #3E3E3F;width: 20px">TIPO</th>
                <th style="background: #FBB02B; color: #3E3E3F;width: 80px">CONCEPTO</th>
                <th style="background: #FBB02B; color: #3E3E3F;width: 90px">FECHA DE MOVIMIENTO</th>
                <th style="background: #FBB02B; color: #3E3E3F;width: 20px">EST</th>
                <th style="background: #FBB02B; color: #3E3E3F;width: 30px">CARGO</th>
                <th style="background: #FBB02B; color: #3E3E3F;width: 85px">ABONO</th>
                <th style="background: #FBB02B; color: #3E3E3F;width: 65px">SALDO</th>
                <th style="background: #FBB02B; color: #3E3E3F;width: 95px">FORMA DE PAGO</th>
                <th style="background: #FBB02B; color: #3E3E3F;width: 62px">BANCO</th>
            </tr>
        </thead>
        <tbody>


        <?php foreach ($cobranza as  $value): ?>
            <?php if ($value->status == 1): ?>
                <?php $status = "PAG" ?>
            <?php else: ?>
                <?php $status = "PEN" ?>
            <?php endif ?>
            <tr>
                <td style=" background: #BCBDC0; border: none; border-bottom: 1px solid #fff;width: 20px"><?= $value->operacion?></td>
                <td style=" background: #BCBDC0; border: none; border-bottom: 1px solid #fff;width: 37px"><?= $value->recibo?></td>
                <td style=" background: #BCBDC0; border: none; border-bottom: 1px solid #fff;width: 20px"><?= $value->mes?></td>
                <td style=" background: #BCBDC0; border: none; border-bottom: 1px solid #fff;width: 20px"><?= $value->tipo_operacion?></td>
                <td style=" background: #BCBDC0; border: none; border-bottom: 1px solid #fff;width: 80px"><?= $value->concepto?></td>
                <td style=" background: #BCBDC0; border: none; border-bottom: 1px solid #fff;width: 20px"><?= $value->fecha_movimiento?></td>
                <td style=" background: #BCBDC0; border: none; border-bottom: 1px solid #fff;width: 20px"><?= $status?></td>
                <td style=" background: #BCBDC0; border: none; border-bottom: 1px solid #fff;width: 80px"><?=  number_format($value->cargo, 2, '.', ',');?></td>
                <td style=" background: #BCBDC0; border: none; border-bottom: 1px solid #fff;width: 30px"><?= number_format($value->abono, 2, '.', ',');?></td>
                <td style=" background: #BCBDC0; border: none; border-bottom: 1px solid #fff;width: 65px"><?= number_format($value->saldo, 2, '.', ',');?></td>
                <td style=" background: #BCBDC0; border: none; border-bottom: 1px solid #fff;text-align: center; overflow-wrap: break-word;width: 95px;"><?= $value->fp; ?></td>
                <td style=" background: #BCBDC0; border: none; border-bottom: 1px solid #fff;width: 52px; overflow-wrap: break-word; font-size: 10px"><?= $value->nombre_banco?></td>            
        </tr>
        <?php endforeach ?>
        </tbody>
    </table>
    <br>
</page>

