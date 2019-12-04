
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



<page backtop="40mm" backbottom="35mm" backleft="20mm" backright="20mm">
    <page_header>
        <img src="<?= $head_page2?>" style="width: 100%;"> 
    </page_header>
    <page_footer>
        <img src="<?= $footer_page?>" style="width: 100%;"> 
        <!-- <p style="position: relative; top: -113px; margin-left: 95px; font-size: 10px;"><b style="color: #F6B501;">**Precios y condiciones sujetos a cambios sin previo aviso. </b></p> -->
        <p style="position: relative; top: -130px; margin-left: 930px; font-size: 10px;">[[page_cu]]/[[page_nb]]</p>
    </page_footer>
    <!-- <span style="font-size: 20px; font-weight: bold">Démonstration des retour à la ligne automatique, ainsi que des sauts de page automatique</span><br> -->
    <br>
    <br>


    <div id="head" style="position: absolute; top: -130px; left: -94px; z-index: 1000; width: 1400px;">
        <img src="<?= $head_page?>" style="width: 100%; height: 230px">
    </div>
    
    <?php if ($logo_proyecto != NULL): ?>
        <div id="logo" style="position: absolute; top: -100px; left: -0px;">
            <img src="<?= $logo ?>" width="210" height="130">
        </div>
    <?php endif ?>


    <div id="empresa" style="position: absolute; top: -135px; left: 875px; width: 310px;">
        <ul style="font-size: 11px">
            <li><b><?= $nombre_empresa."<br>" ?></b></li>
            <li style="color: #fff">Direccion: <?= $direccion ?></li>
            <li style="color: #fff">Telefono:  <?= $telefono ?></li>
            <li style="color: #fff">Email:     <?= $correo ?></li>
        </ul>
    </div>


     <div id="data_venta" style="position: absolute; top: -30px; left: 875px; width: 310px;">
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
               <b>CUENTAS POR COBRAR</b>
            </td>
            <td style="text-align: right;    width: 33%;background: #fff; border: none">
               
            </td>
        </tr>
    </table>

    <table style="width: 100%; margin-top: 30px; margin-left: -50px; border-collapse: collapse;">
        <thead>
        <tr style="text-align: center; text-transform: uppercase;">
                <th style="background: #FFC000; color: #000; width: 7px">#</th>
                <th style="background: #FFC000; color: #000; width: 70px">Prospecto <br> o <br> Cliente</th>
                <th style="background: #FFC000; color: #000; width: 50px">Producto</th>
                <th style="background: #FFC000; color: #000; width: 50px">Etapa</th>
                <th style="background: #FFC000; color: #000; width: 50px">Plazos</th>
                <th style="background: #FFC000; color: #000; width: 60px"># Cuotas Pendientes</th>
                <th style="background: #FFC000; color: #000; width: 60px">Fecha de Operación</th>
                <th style="background: #FFC000; color: #000; width: 100px">Precio Venta</th>
                <th style="background: #FFC000; color: #000; width: 100px">Enganche (Anticipo)</th>
                <th style="background: #FFC000; color: #000; width: 100px">Mensualidad</th>
                <th style="background: #FFC000; color: #000; width: 100px">Monto Pagado</th>
                <th style="background: #FFC000; color: #000; width: 100px">Saldo Total</th>
                <th style="background: #FFC000; color: #000; width: 100px">Saldo Vencido</th>
            </tr>   
        </thead>


        <?php foreach ($reporte["data"] as $key => $data): ?>
            <tr>
                <td style="background: #BCBDC0; border: none; border-bottom: 1px solid #fff"><?= $data['id_venta']?></td>
                <td style="background: #BCBDC0; border: none; border-bottom: 1px solid #fff; width: 70px"><?= trim($data['nombre_cliente'])?> <br> <?= trim($data['apellido_p_cliente'])?> </td>
                <td style="background: #BCBDC0; border: none; border-bottom: 1px solid #fff; width: 50px"><?= $data['name_producto']?></td>
                <td style="background: #BCBDC0; border: none; border-bottom: 1px solid #fff"><?= $data['etapa']?></td>
                <td style="background: #BCBDC0; border: none; border-bottom: 1px solid #fff"><?= $data['name_plazo']?></td>
                <td style="background: #BCBDC0; border: none; border-bottom: 1px solid #fff"><?= $data['cuotas']?></td>
                <td style="background: #BCBDC0; border: none; border-bottom: 1px solid #fff"><?= $data['fecha']?></td>
                <td style="background: #BCBDC0; border: none; border-bottom: 1px solid #fff"><?= number_format($data['monto_total'], 2, '.', ',')?></td>
                <td style="background: #BCBDC0; border: none; border-bottom: 1px solid #fff"><?= number_format($data['enganche'], 2, '.', ',')?></td>
                
               <?php 
                    if($data['mensualidad'] == ""){
                        print_r('<td style="background: #BCBDC0; border: none; border-bottom: 1px solid #fff">'.number_format(0, 2, '.', ',').'</td>');
                    }else{
                        print_r('<td style="background: #BCBDC0; border: none; border-bottom: 1px solid #fff">'.number_format($data['mensualidad'], 2, '.', ',').'</td>');
                    }
                ?>
                <td style="background: #BCBDC0; border: none; border-bottom: 1px solid #fff"><?= number_format($data['monto_pagado'], 2, '.', ',')?></td>
                <td style="background: #BCBDC0; border: none; border-bottom: 1px solid #fff"><?= number_format($data['saldof'], 2, '.', ',')?></td>
                <td style="background: #BCBDC0; border: none; border-bottom: 1px solid #fff"><?= number_format($data['total_mensualidades'], 2, '.', ',')?></td>

            </tr>
        <?php endforeach ?>
    </table>
</page>