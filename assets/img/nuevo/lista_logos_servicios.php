
<?php

$directorio = opendir("./logos_servicios/"); //ruta actual
while ($archivo = readdir($directorio)) //obtenemos un archivo y luego otro sucesivamente
{
    if (is_dir($archivo))//verificamos si es o no un directorio
    {
        //echo "[".$archivo . "]<br />"; //de ser un directorio lo envolvemos entre corchetes
    }
    else
    {
       // echo $archivo . "<br />";
        $files[] = $archivo;
    }
}

$json = json_encode($files);
print_r($json);







?>