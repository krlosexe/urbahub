<?php 

if (!defined('BASEPATH')) exit ('No direct script access allowed');

Class Login_web_model extends CI_Model
{



    public function verificar_correo($data){



        $verificacion = $this->mongo_db->where($data)->get('contacto');

if($verificacion){
   // print_r($verificacion[0]['_id']->{'$id'});
   // print_r("<br>");
       $data_cliente_pagador =  $this->mongo_db->where(array("id_contacto" => $verificacion[0]['_id']->{'$id'} ))->get('cliente_pagador');
    //   print_r("<br>");

        // IMAGEN DE QUIEN LOGEA
       if(isset($data_cliente_pagador[0]["imagenCliente"])){
            $img_login = $data_cliente_pagador[0]["imagenCliente"];
       }else{
            $img_login = "default-img.png";
       }
       // IMAGEN DE QUIEN LOGEA
       //print_r($data_cliente_pagador[0]['id_datos_personales']);
      // print_r("<br>");
         $id_datos_personales = new MongoDB\BSON\ObjectId($data_cliente_pagador[0]['id_datos_personales']);
       $data_datos_personales =  $this->mongo_db->where(array("_id" => $id_datos_personales ))->get('datos_personales');
            // NOMBRES DE QUIEN LOGEA
      // print_r($data_datos_personales[0]['nombre_datos_personales']." ".$data_datos_personales[0]['apellido_p_datos_personales']);   
         $nombre = $data_datos_personales[0]['nombre_datos_personales']." ".$data_datos_personales[0]['apellido_p_datos_personales'];
       // NOMBRES DE QUIEN LOGEA
         return array("nombre" => $nombre, "imagen" => $img_login, "estado_login" => 1, "ruta" => base_url());
}else{
    return false;
}

    

    }


    public function buscar_membresia($correo, $serial){
        $listado = [];
        $resultados = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false, 'serial_acceso' => $serial))->get("membresia");
        $contador = 0;
        $servicios_temp =  $this->mongo_db->where(array('eliminado'=>false))->get("servicios");
        $servicios = array();
        foreach ($servicios_temp as $key => $value) {
            $servicios[$value['_id']->{'$id'}] = $value['descripcion'];
        }
        foreach ($resultados as $clave => $valor) {
            $valores = $valor;

            $valores["id_membresia"] = (string)$valor["_id"]->{'$id'};
            #Consulto datos personales
            $rfc = $valor["identificador_prospecto_cliente"];
            $res_dt = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array("rfc_datos_personales"=>$rfc))->get("datos_personales");
            if(count($res_dt)>0){
                $valores['datos_persona'] = $res_dt[0];
            }else{
                $valores['datos_persona']['correo_contacto'] = "";
            }
            $res_dt2 = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array("rfc_datos_personales"=>$rfc))->get("cliente_pagador");
            if(count($res_dt2)>0){
                $valores['cliente_pagador'] = $res_dt2[0];
                $valores['cliente_pagador']['imagenCliente'] = base_url()."assets/cpanel/ClientePagador/images/".$valores['cliente_pagador']['imagenCliente'];
            }else{
                $valores['cliente_pagador']['imagenCliente'] = base_url()."assets/cpanel/ClientePagador/images/default-img.png";
            }

            #Consulto planes
            $id_planes = new MongoDB\BSON\ObjectId($valor["plan"]);
            $res_planes = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array("_id"=>$id_planes))->get("planes");
            //Debo volverlo a poner  
            //'eliminado'=>false,
            $valores["planes"] = $res_planes[0];

            #Consulto paquete
            $id_paquete = new MongoDB\BSON\ObjectId($valor["paquete"]);
            $res_paquete = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array("_id"=>$id_paquete))->get("paquetes");
            //Debo volverlo a poner  
            //'eliminado'=>false,
            $valores["paquetes"] = $res_paquete[0];

            //--
            $vector_fecha_inicio = explode("-",$valor["fecha_inicio"]);
            
            //$valores["fecha_inicio"] = $vector_fecha_inicio[2]."-".$vector_fecha_inicio[1]."-".$vector_fecha_inicio[0];

            //$vector_fecha_fin = explode("-",$valor["fecha_fin"]);

            //$valores["fecha_fin"] = $vector_fecha_fin[2]."-".$vector_fecha_fin[1]."-".$vector_fecha_fin[0];
            
            $valores["fecha_inicio"] = $valor["fecha_inicio"]->toDateTime();
            $valores["fecha_fin"] = $valor["fecha_fin"]->toDateTime();

            $temp_values = array();
            foreach ($valores["servicios"] as $key22 => $value22) {
                $temp_values[$key22]['servicios'] = $value22->servicios;
                $temp_values[$key22]['descripcion'] = isset($servicios[$value22->servicios])?$servicios[$value22->servicios]:"";
                $temp_values[$key22]['cantidad'] = $value22->cantidad;
                $temp_values[$key22]['disponible'] = $value22->disponible;
                $temp_values[$key22]['monto'] = $value22->monto;
            }
            $valores["servicios"] = $temp_values;

            $temp_values = array();
            foreach ($valores["servicios_c"] as $key22 => $value22) {
                $temp_values[$key22]['servicios'] = $value22->servicios;
                $temp_values[$key22]['descripcion'] = isset($servicios[$value22->servicios])?$servicios[$value22->servicios]:"";
                $temp_values[$key22]['valor'] = $value22->valor;
                $temp_values[$key22]['monto'] = $value22->monto;
            }
            $valores["servicios_c"] = $temp_values;

            unset($valores["auditoria"], $valores["renovaciones"], $valores["historial_token"], $valores['datos_persona']["auditoria"], $valores['planes']["auditoria"], $valores['paquetes']["auditoria"], $valores['paquetes']["servicios"]);

            $contador++;
            $valores["numero"] = $contador;
            if($correo == $valores['datos_persona']['correo_contacto']){
                $listado[] = $valores;
            }
        }
        return $listado;
    }






}