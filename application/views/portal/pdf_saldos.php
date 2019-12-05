<html>
<head>
    <title>Saldos</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
</head>

<style>
    .text-center {
        text-align: center
    }

    .text-left {
        text-align: left
    }

body{
    font-family: Roboto !important
}
</style>
<body>

    <img src="https://stfranksanchez.000webhostapp.com/images/header.jpg" alt="" style="width: 100%;
    margin: 0;
    padding: 0;
    transform: scale(1.012);">



    <div style="width: 90%; margin-left: auto; margin-right: auto; display: block">
                    <h3 style="text-align: center; font-family: serif;">CONSULTA DE SALDO</h3>


                    <?php 

//print_r($servicios);

                    ?>

<table class="text-left" style="width: 100%;
margin-left: auto;
margin-right: auto;
display: inline-table;border-collapse: collapse;">
<tr>
        <th>
            <p><strong>Membresia:</strong> <?= $m ?> </p>
            <p><strong>Nombre:</strong> <?= $n ?></p>
            <p><strong>Apellido:</strong> <?= $a ?> </p>
        </th>
        <th>
            <p><strong>Fecha:</strong><?= $f1 ?></p>
            <p><strong>Status:</strong><?= $s ?></p>
            <p><strong>Prox. Pago:</strong><?= $f2 ?></p>
        </th>
        <th>
                <p><strong>Plan Contratado: </strong></p>
                <p><?= $planes ?></p>
              
        </th>
</tr>


</table>






<table  class="text-center" style="width: 100%;
margin-left: auto;
margin-right: auto;
display: inline-table;border-collapse: collapse;    margin-top: 20px;">
    <tr style="background: #eae033;">
        <th width="40%" >Servicios</th>
        <th width="20%">Contratados</th>
        <th width="20%">Consumidos</th>
        <th width="20%">Disponibles</th>
    </tr>

    <?php 

foreach($servicios as $servicio){
print_r("<tr><td>".$servicio['descripcion']."</td><td>".$servicio['cantidad']."</td><td>".$servicio['consumidos']."</td><td>".$servicio['disponible']."</td></tr>");
}


                ?>
  


</table>


    
    
    </div>







        <img src="https://stfranksanchez.000webhostapp.com/images/footer.jpg" alt="" style="width: 100%;
        margin: 0;
        padding: 0;
        transform: scale(1.012);    margin-top: 25%;">
 
</body>
</html>