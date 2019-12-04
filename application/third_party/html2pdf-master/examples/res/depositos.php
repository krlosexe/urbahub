
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
        <p style="position: relative; top: -113px; margin-left: 95px; font-size: 10px;"><b style="color: #F6B501;">**Precios y condiciones sujetos a cambios sin previo aviso. </b></p>
        <p style="position: relative; top: -30px; margin-left: 930px; font-size: 10px;">[[page_cu]]/[[page_nb]]</p>
    </page_footer>
    <!-- <span style="font-size: 20px; font-weight: bold">Démonstration des retour à la ligne automatique, ainsi que des sauts de page automatique</span><br> -->
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


     <div id="data_venta" style="position: absolute; top: -30px; left: 695px; width: 310px;">
        <ul style="font-size: 10px">
            <li style="color: #fff; text-transform: uppercase;"></li>
        </ul>
    </div>



    <div id="data_venta" style="position: absolute; top: -30px; left: 920px; width: 310px;">
        <ul style="font-size: 10px">
            <li style="color: #fff; text-transform: uppercase;"><?= date('d/m/Y')?></li>
        </ul>
    </div>



    <table style="width: 100%; margin-top: 80px">
        <tr>
            <td style="text-align: left; width: 33%; background: #fff; border: none;">
               
            </td>
            <td style="text-align: center; font-size: 13px;;width: 34%;background: #fff; border: none;">
               <b>DEPOSITOS DE VENTA</b>
            </td>
            <td style="text-align: right;    width: 33%;background: #fff; border: none">
               
            </td>
        </tr>
    </table>


    <table style="width: 100%; margin-top: 30px; border-collapse: collapse;">
        <tr style="text-align: center; text-transform: uppercase;">
            <th style="width: 35px">Ventas</th>
            <th style="width: 40px">CLIENTES</th>
            <th style="width: 10px">CLIENTE</th>
            <th style="width: 60px">FECHA DE REALIZACION</th>
            <th style="width: 10px">LOTE</th>
            <?php foreach ($bancos as $banco): ?>
                <th style="padding: 10px"><?= $banco->nombre_banco?></th>
            <?php endforeach ?>
            <th style="width: 10px%">Efectivo</th>
            <th style="width: 10px%">Total</th>
        </tr>

        <?php $totalss = 0; ?>
        <tr style="text-align: center;">
            <td id="total_venta"><?=$data["total_lotes"]?></td>
            <td id="total_clientes"><?= $data["total_clientes"]?></td>
            <td></td>
            <td></td>
            <td></td>
            <?php foreach ($bancos as $banco): ?>
                <td class="monto_banco" data="<?= $banco->numero_cuenta?>">     
                    <?php foreach ($data["totales"] as $total): ?>
                        <?php if ($total[0]->numero_cuenta == $banco->numero_cuenta): ?>
                            <?= number_format($total[0]->total, 2, '.', ',') ?>
                            <?php $totalss = $totalss +  $total[0]->total ?>
                        <?php endif ?>
                    <?php endforeach ?>
                </td>
            <?php endforeach ?>
            <td id="total"><?php echo number_format($data["efectivo"], 2, '.', ',') ?></td>
            <td id="total"><?php echo number_format(($totalss + $data["efectivo"]), 2, '.', ',') ?></td>
        </tr>


        <tbody style="text-align: center;">
            <tr style="border: none">
                <td colspan="8" style="border: none; background: #fff"></td>
            </tr>
            <tr style="border: none; background: #fff; border-bottom: 1px solid #fff;">
                <td colspan="8" style="border: none; background: #fff; border-bottom: 1px solid #fff;""></td>
            </tr>
            <?php foreach ($detalle["detalle"] as $value): ?>
                <tr style="background: #eee; ">
                    <td style="background: #BCBDC0; border: none; border-bottom: 1px solid #fff"><?= $value->cantidad?></td>
                    <td style="background: #BCBDC0; border: none; border-bottom: 1px solid #fff"><?= $value->id_cliente?></td>
                    <td style="background: #BCBDC0; border: none; border-bottom: 1px solid #fff"><?= substr($value->cliente, 0,23)?></td>
                    <td style="background: #BCBDC0; border: none; border-bottom: 1px solid #fff"><?= $value->fecha_contable?></td>
                    <td style="background: #BCBDC0; border: none; border-bottom: 1px solid #fff"><?= $value->lote?></td>
                    <?php foreach ($detalle["cuenta"] as $cuenta): ?>
                        <?php if ($cuenta == $value->numero_cuenta): ?>
                            <td style="background: #BCBDC0; border: none; border-bottom: 1px solid #fff"><?= number_format($value->abono, 2, '.', ',') ?></td>
                        <?php else: ?>
                            <td style="background: #BCBDC0; border: none; border-bottom: 1px solid #fff"></td>
                        <?php endif ?>
                    <?php endforeach ?>

                    <?php if ($value->name_pago == "EFECTIVO"): ?>
                        <td style="background: #BCBDC0; border: none; border-bottom: 1px solid #fff"><?= number_format($value->abono, 2, '.', ',') ?></td>
                        <?php else: ?>
                            <td style="background: #BCBDC0; border: none; border-bottom: 1px solid #fff"></td>
                    <?php endif ?>
                </tr>
            <?php endforeach ?>
        </tbody>

       
    </table>




    


    <table style="margin-top: 30px;  border-collapse: collapse;">
        <thead>
            <tr>
                <th style="width: 35px">#OPER.</th>
                <th style="width: 37px">RECIBO</th>
                <th style="width: 20px">MES</th>
                <th style="width: 20px">TIPO</th>
                <th style="width: 80px">CONCEPTO</th>
                <th style="width: 80px">FECHA DE MOVIMIENTO</th>
                <th style="width: 30px">CARGO</th>
                <th style="width: 85px">ABONO</th>
                <th style="width: 85px">SALDO</th>
                <th style="width: 95px">FORMA DE PAGO</th>
                <th style="width: 52px">BANCO</th>
            </tr>
        </thead>
        <tbody>


        <?php foreach ($cobranza as  $value): ?>
            <tr>
                <td style="width: 35px"><?= $value->operacion?></td>
                <td style="width: 37px"><?= $value->recibo?></td>
                <td style="width: 20px"><?= $value->mes?></td>
                <td style="width: 20px"><?= $value->tipo_operacion?></td>
                <td style="width: 80px"><?= $value->concepto?></td>
                <td style="width: 20px"><?= $value->fecha_movimiento?></td>
                <td style="width: 80px"><?=  number_format($value->cargo, 2, '.', ',');?></td>
                <td style="width: 30px"><?= number_format($value->abono, 2, '.', ',');?></td>
                <td style="width: 85px"><?= number_format($value->saldo, 2, '.', ',');?></td>
                <td style="text-align: center; overflow-wrap: break-word;width: 95px;"><?= $value->fp; ?></td>
                <td style="width: 52px; overflow-wrap: break-word;"><?= $value->nombre_banco?></td>            
           </tr>
        <?php endforeach ?>
        </tbody>
    </table>
    <br>
</page>

