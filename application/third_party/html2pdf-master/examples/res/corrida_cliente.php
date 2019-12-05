
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

<page backtop="25mm" backbottom="25mm" backleft="20mm" backright="20mm">
    <page_header>
        <img src="<?= $head_page2?>" style="width: 100%;"> 
    </page_header>
    

    <page_footer>
        <img src="<?= $footer_page?>" style="width: 100%;"> 
        
        <p style="position: relative; top: -85px; margin-left: 75px; font-size: 10px;">&nbsp;</p>
        <p style="position: relative; top: -30px; margin-left: 690px; font-size: 10px;">[[page_cu]]/[[page_nb]]</p>
    </page_footer>
    <br>
    <br>


    <div id="head" style="position: absolute; top: -95px; left: -94px; z-index: 1000; width: 811px;">
        <img src="<?= $head_page?>" style="width: 100%; height: 230px">
    </div>

    <div id="logo" style="position: absolute; top: -66px; left: -0px;">
        <img src="<?= $logo ?>" width="210" height="130">
    </div>

    <div id="empresa" style="position: absolute; top: -100px; left: 375px; width: 310px;">
        <ul style="font-size: 11px">
            <li><b><?= $nombre_empresa."<br>" ?></b></li>
            <li style="color: #fff">Direccion: <?= $direccion ?></li>
            <li style="color: #fff">Telefono:  <?= $telefono ?></li>
            <li style="color: #fff">Email:     <?= $correo ?></li>
        </ul>
    </div>


    <div id="data_venta" style="position: absolute; top: 0px; left: 375px; width: 310px;">
        <ul style="font-size: 10px">
            <li style="color: #fff; text-transform: uppercase;">Proyecto: <?= $proyecto ?> </li>
           <!-- <li style="color: #fff; text-transform: uppercase;">Cliente:  <?= $cliente ?></li>-->
            <li style="color: #fff; text-transform: uppercase;">Vendedor:  <?= $vendedor ?></li>
            <li style="color: #fff; text-transform: uppercase;">#corrida:  <?= $corrida ?></li>
            <li style="color: #fff; text-transform: uppercase;">Estatus:  <?= $status ?></li>
        </ul>
    </div>



    <div id="data_venta" style="position: absolute; top: 0px; left: 600px; width: 310px;">
        <ul style="font-size: 10px">
            <li style="color: #fff; text-transform: uppercase;"><?= date('d/m/Y')?></li>
        </ul>
    </div>






    <table style="width: 100%; margin-top: 100px">
        <tr>
            <td style="text-align: left; width: 33%; background: #fff; border: none;">
               
            </td>
            <td style="text-align: center;  font-size: 13px;  width: 34%;background: #fff; border: none;">
               <b>DATOS DEL CLIENTE</b>
            </td>
            <td style="text-align: right;    width: 33%;background: #fff; border: none">
               
            </td>
        </tr>
    </table>


    <table style="width: 100%; margin-top: 30px; ">
        <tr>
            <th style="width: 35%; padding-top: 15px ; padding-bottom: 15px;">
               NOMBRE
            </th>
            <th style="width: 25%; padding-top: 15px ; padding-bottom: 15px;">
               NACIONALIDAD
            </th>
            <th style="width: 15%; padding-top: 15px ; padding-bottom: 15px;">
              EDAD
            </th>

            <th style="width: 25%; padding-top: 15px ; padding-bottom: 15px;">
              RFC
            </th>

      

        </tr>

        <tbody>
        <tr>
                        <td>
                        <?=  $nombre_cliente?>
                        </td>
                        
                        <td>
                        <?= $nacionalidad ?>
                        </td>

                        <td>
                        <?= $fecha_n ?>
                        </td>

                        <td>
                        <?= $rfc ?>
                        </td>
        </tr>
        
        </tbody>
    </table>




    <table style="width: 100%; margin-top: 30px;  border-collapse: collapse;">
        <tr>

            <th style="background: #BCBDC0; color: #001259; width: 75px; padding-top: 15px ; padding-bottom: 15px;">
               CALLE
            </th>

            <th style="background: #BCBDC0; color: #001259; width: 70px; padding-top: 15px ; padding-bottom: 15px;">
               COLONIA
            </th>

            <th style="background: #BCBDC0; color: #001259; width: 90px; padding-top: 15px ; padding-bottom: 15px;">
               MUNICIPIO
            </th>

            <th style="background: #BCBDC0; color: #001259; width: 90px; padding-top: 15px ; padding-bottom: 15px;">
               CIUDAD
            </th>

            <th style="background: #BCBDC0; color: #001259; width: 50px; padding-top: 15px ; padding-bottom: 15px;">
              ESTADO
            </th>
            <th style="background: #BCBDC0; color: #001259; width: 30px; padding-top: 15px ; padding-bottom: 15px;">
              CÓDIGO POSTAL
            </th>
            <th style="background: #BCBDC0; color: #001259; width: 50px; padding-top: 15px ; padding-bottom: 15px;">
              SOCIEDAD CONYUGAL
            </th>
        </tr>

        <tbody>
            <tr>
            <td style="width: 50px; text-transform:uppercase"><?= $calle ?></td>
            <td style="width: 50px; text-transform:uppercase"><?= $colonia ?></td>
            <td style="width: 50px; text-transform:uppercase"><?= $municipio ?></td>
            <td style="width: 50px; text-transform:uppercase"><?= $ciudad ?></td>
            <td style="width: 50px; text-transform:uppercase"><?= $estado ?></td>
            <td style="width: 50px; text-transform:uppercase "><?= $code_p ?></td>
            <td style="width: 50px; text-transform:uppercase"><?= $s_conyugal ?></td>



              <!--td><?= number_format($venta->monto_descuento + $venta->monto_descuento_especial / $venta->tasa_cambio_monto, 2, '.', ',')  ?></td>
                <td><?= number_format($venta->monto_recargo / $venta->tasa_cambio_monto, 2, '.', ',')  ?></td>
                <td><?=  (($venta->monto_recargo * 100) / $venta->monto_total)."%"  ?></td>
                <td><?=  number_format((($venta->monto_total - ($venta->monto_descuento + $venta->monto_descuento_especial)) + $venta->monto_recargo) / $venta->tasa_cambio_monto , 2, '.', ',')   ?></td>
                <td><?= number_format($venta->anticipo_monto / $venta->tasa_cambio_monto, 2, '.', ',')  ?></td>
                <td><?= $venta->anticipo_porcentaje."%" ?></td> -->
            </tr>
        </tbody>

        <!--
        <tr>

            <th colspan="2">
              MONTO DE LA RESERVA
            </th>
            <th colspan="2">
               SALDO DEL ANTICIPO POSTERIOR A LA RESERVA
            </th>

            <th>
               PLAZO DEL <br> SALDO
            </th>
            <th>
               SALDO
            </th>
        </tr>
 
        <tbody>
            <tr>
                <td colspan="2"><?= number_format($venta->reserva_anticipo / $venta->tasa_cambio_monto, 2, '.', ',') ?></td>
                <td colspan="2"><?= number_format($venta->saldo_anticipo / $venta->tasa_cambio_monto, 2, '.', ',') ?></td>
                <td><?= $venta->name_plazo ?></td>
                <td><?= number_format($venta->saldo / $venta->tasa_cambio_monto, 2, '.', ',') ?></td>
            </tr>
        </tbody> 
        -->
    </table>
    

    
    <table style="width: 100%; margin-top: 5px; ">
        <tr>
            <th style="width: 35%; padding-top: 15px ; padding-bottom: 15px;">
               NOMBRE DEL CONYUGE
            </th>
            <th style="width: 25%; padding-top: 15px ; padding-bottom: 15px;">
               NACIONALIDAD
            </th>
            <th style="width: 15%; padding-top: 15px ; padding-bottom: 15px;">
              EDAD
            </th>

            <th style="width: 25%; padding-top: 15px ; padding-bottom: 15px;">
              RFC
            </th>

      

        </tr>

        <tbody>
        <tr>
                        <td style="text-transform:uppercase">
                        <?=  $nombre_conyuge?>
                        </td>
                        
                        <td style="text-transform:uppercase">
                        <?= $nacionalidad_conyuge ?>
                        </td>

                        <td style="text-transform:uppercase">
                        <?= $edad_contugue ?>
                        </td>

                        <td style="text-transform:uppercase">
                        <?= $rfc_conyuge ?>
                        </td>
        </tr>
        
        </tbody>
    </table>



    <table style="width: 100%; margin-top: 30px;  border-collapse: collapse;">
        <tr>

            <th style="background: #BCBDC0; color: #001259; width: 20%; padding-top: 15px ; padding-bottom: 15px;">
               EXPEDIENTE CATASTRAL
            </th>

            <th style="background: #BCBDC0; color: #001259; width: 20%; padding-top: 15px ; padding-bottom: 15px;">
               METRO CUADRADO (M2)
            </th>

            <th style="background: #BCBDC0; color: #001259; width: 20%; padding-top: 15px ; padding-bottom: 15px;">
               NÚMERO DE LOTE
            </th>

            <th style="background: #BCBDC0; color: #001259; width: 20%; padding-top: 15px ; padding-bottom: 15px;">
               ZONA DE ENTREGA
            </th>

            <th style="background: #BCBDC0; color: #001259; width: 20%; padding-top: 15px ; padding-bottom: 15px;">
              VALOR POR (M2)
            </th>
        </tr>

        <tbody>
        <?php foreach ($productos as  $value): ?>
                    <tr>
                        <td><?php if($value->n_catastral == 0 or $value->n_catastral == "0" ){  print_r("");}else{print_r($value->n_catastral);}; ?></td>
                        <td><?= number_format($value->superficie, 4, ',', '.') ?></td>
                        <td><?= $value->name_producto ?></td>
                        <td><?= $value->nom_zona ?></td>
                        <td><?= number_format(($value->precio_m2) / ($value->superficie), 2, '.', ',') ?></td>   
                    </tr>
                <?php endforeach ?>
        </tbody>

    </table>
    

    

    <table style="width: 100%; margin-top: 30px;  border-collapse: collapse;">
    <tr>
            <th style="background: #FBB02B; color: #3E3E3F; padding: 7px;width: 16.6%">
               PRECIO DE LISTA
            </th>
            <th style="background: #FBB02B; color: #3E3E3F; padding: 7px;width: 16.6%">
              DESCUENTOS
            </th>
            <th style="background: #FBB02B; color: #3E3E3F; padding: 7px;width: 16.6%">
            ANTICIPO 
            </th>
            <th style="background: #FBB02B; color: #3E3E3F; padding: 7px;width: 16.6%">
            ENGANCHE 
            </th>
            <th style="background: #FBB02B; color: #3E3E3F; padding: 7px;width: 16.6%">
            PLAZOS 
            </th>
            <th style="background: #FBB02B; color: #3E3E3F; padding: px;width: 16.6%">
            MONTO <br> CUOTA 
            </th>
        </tr>

        <tbody>
              <tr style="background: #BCBDC0">
                    <td style="background: #BCBDC0; border: none; border-bottom: 1px solid #fff"><?= number_format($preciolista / $venta->tasa_cambio_monto, 2, '.', ',') ?></td>
                    <td style="background: #BCBDC0; border: none; border-bottom: 1px solid #fff"><?= number_format($descuento / $venta->tasa_cambio_monto, 2, '.', ',') ?></td>
                
                    <td style="background: #BCBDC0; border: none; border-bottom: 1px solid #fff"><?= number_format($anticipo / $venta->tasa_cambio_monto, 2, '.', ',')  ?></td>

                    <td style="background: #BCBDC0; border: none; border-bottom: 1px solid #fff"><?= number_format($enganche / $venta->tasa_cambio_monto, 2, '.', ',')  ?></td>
                    <td style="background: #BCBDC0; border: none; border-bottom: 1px solid #fff"><?= $cuotas  ?></td>
                    <td style="background: #BCBDC0; border: none; border-bottom: 1px solid #fff"><?= number_format($m_cuota / $venta->tasa_cambio_monto, 2, '.', ',')  ?></td>
                </tr>
        </tbody>
    </table>



    <table style="width: 100%; margin-top: 30px; background: none;  border-collapse: collapse;">
    <tr>
        <th style="width:33%; background: none"> </th>
        <th style="width:33%;background: none""> </th>
        <th style="width:33%;background: none""> </th>
    </tr>

            <tbody>
            <tr>
                <td  style="padding-left: 10px;padding-right: 10px">
                    
                        <hr style="border: #2f3c81 solid 2px">
                    <?=  $elaboro  ?>
                    <br>
                    ELABORO
                </td>
                <td  style="padding-left: 10px;padding-right: 10px">
                    
                        <hr style="border: #2f3c81 solid 2px">
                        <?=  $nombre_cliente?>
                        <br>
                CLIENTE
                </td>
                <td  style="padding-left: 10px;padding-right: 10px">
                    
                        <hr style="border: #2f3c81 solid 2px">
                        <?= $vendedor ?>
                        <br>
                ASESOR DE VENTAS
                </td>
            </tr>
            </tbody>

</table>

<br>

<br>

<br>
<br>
<br>
<br>




    <br>
</page>

