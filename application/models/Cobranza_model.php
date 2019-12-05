<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cobranza_model extends CI_Model {
//--------------------------------------------
/*
*   Bloque de metodos realizados por @santu1987
*/
/*
* Listado de la cotizacion
*/
public function listado_cobranza(){

     $listado = [];
     $resultados = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false))->get("recibos_cobranzas");
     $contador = 0;
     foreach ($resultados as $clave => $valor) {
         $valores = $valor;
        $valores["id_cobranza"] = (string)$valor["_id"]->{'$id'};
        $valores["id_cotizacion"] = (string)$valor["id_venta"];
        $valores["numero_corrida"] = $valor["numero_corrida"];
        $id_cotizacion =  new MongoDB\BSON\ObjectId($valor["id_venta"]);
        $valores["condicion"] = $valor["condicion"];
        $valores["recibos"] = $valor["recibos"];
        //---
        /*#Recorro el arreglo de recibos...
        foreach ($valores["recibos"] as $clave_recibos => $valor_recibos) {
            //--------------------------------------
            #Consulto los archivos comprobantes de pago
            $res_comprobantes = $this->Cobranza_model->listado_comprobantes($valores["id_cobranza"],$valor_recibos->numero_recibo);
            //var_dump($res_comprobantes);die;
            $valores["comprobantes"] = $res_comprobantes; 
            //--------------------------------------
        }*/
        //---
        #Calculo el saldo total:
        $saldo_acumulado = $this->calcularSaldoTotal($valores["recibos"]);
        //---
        #Consulto la cotizacion
        $datos_cotizacion = $this->mongo_db->where(array('eliminado'=>false,'_id'=>$id_cotizacion))->get("cotizacion");

        $valores["numero_cotizacion"] = $datos_cotizacion[0]["numero_cotizacion"];
        
        #Consulto datos personales 
        //$rfc = $datos_cotizacion[0]["identificador_prospecto_cliente"];

        $rfc = (string)$valor["id_cliente"];

        $id_cliente  = new MongoDB\BSON\ObjectId($rfc);
        $id_facturar = new MongoDB\BSON\ObjectId((string)$valor["id_facturar"]);

        $res_cliente_pagador = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,'_id'=>$id_cliente))->get("cliente_pagador");
        $res_facturar        = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,'_id'=>$id_facturar))->get("cliente_pagador");
        
        #Consultar info de bancos segun clientes
        $bancos_cuentas = $this->mongo_db->where(array('id_cliente'=>$rfc))->get('cuenta_cliente'); 
        $banco_info = []; 
        foreach ($bancos_cuentas as $clave_bancos => $valor_bancos) {
            if(isset($valor_bancos["id_banco"])){
                $valores_bancos["id_banco"] = $valor_bancos["id_banco"];
                $valores_bancos["clabe_cuenta"] = $valor_bancos["clabe_cuenta"];
                $banco_info[]=$valores_bancos;
            }
        }
        $valores["banco_info"] = $banco_info;
        #----       
        $id_dt          = new MongoDB\BSON\ObjectId($res_cliente_pagador[0]["id_datos_personales"]);
        $id_dt_facturar = new MongoDB\BSON\ObjectId($res_facturar[0]["id_datos_personales"]);

        $res_dt          = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array("_id"=>$id_dt))->get("datos_personales");
        $res_dt_facturar = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array("_id"=>$id_dt_facturar))->get("datos_personales");
        
        if(count($res_dt)>0){
          isset($res_dt[0]["apellido_p_datos_personales"])? $apellido_datos_personales = $res_dt[0]["apellido_p_datos_personales"] : $apellido_datos_personales ="";

            $valores["datos_clientes"] = $res_dt[0]["rfc_datos_personales"]."-".$res_dt[0]["nombre_datos_personales"]." ".$apellido_datos_personales;
            //---------------------------------------------------------
            #Consulto el cliente
            $valores["id_datos_personales"] = $res_dt[0]["_id"]->{'$id'};;
            $res_cliente_pagador = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,'id_datos_personales'=>$valores["id_datos_personales"]))->get("cliente_pagador");
            //$valores["imagenCliente"] = $res_cliente_pagador[0]["imagenCliente"];
            (isset($res_cliente_pagador[0]["imagenCliente"]))? $valores["imagenCliente"] = $res_cliente_pagador[0]["imagenCliente"]:$valores["imagenCliente"] = "default-img.png";
            $valores["id_clientes"] = $res_cliente_pagador[0]["_id"]->{'$id'};;
            //---------------------------------------------------------
        }else{
            $valores["datos_clientes"] = "";
        }



        if(count($res_dt_facturar)>0){
          isset($res_dt_facturar[0]["apellido_p_datos_personales"])? $apellido_datos_personales = $res_dt_facturar[0]["apellido_p_datos_personales"] : $apellido_datos_personales ="";

            $valores["datos_facturar"] = $res_dt_facturar[0]["rfc_datos_personales"]."-".$res_dt_facturar[0]["nombre_datos_personales"]." ".$apellido_datos_personales;
            //---------------------------------------------------------
            #Consulto el cliente
            $valores["id_datos_personales_facturar"] = $res_dt_facturar[0]["_id"]->{'$id'};;
            $res_cliente_pagador = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,'id_datos_personales'=>$valores["id_datos_personales"]))->get("cliente_pagador");
            //$valores["imagenCliente"] = $res_cliente_pagador[0]["imagenCliente"];
            (isset($res_cliente_pagador[0]["imagenCliente"]))? $valores["imagenClienteFacturar"] = $res_cliente_pagador[0]["imagenCliente"]:$valores["imagenClienteFacturar"] = "default-img.png";
            $valores["id_clientes_facturar"] = $res_cliente_pagador[0]["_id"]->{'$id'};;
            //---------------------------------------------------------
        }else{
            $valores["datos_facturar"] = "";
        }
        
         $id_vendedor = new MongoDB\BSON\ObjectId($datos_cotizacion[0]["id_vendedor"]);
        
         #Consultar vendedores
         $res_vendedor = $this->mongo_db->where(array("_id"=>$id_vendedor))->get("vendedores");
         $id_us_ve = new MongoDB\BSON\ObjectId($res_vendedor[0]["id_usuario"]);
         $res_dp2 = $this->mongo_db->where(array('id_usuario'=>$id_us_ve))->get('datos_personales');            
         if(count($res_dp2)>0){
             $valores["datos_vendores"] = $res_dp2[0]["nombre_datos_personales"]." ".$res_dp2[0]["apellido_p_datos_personales"];
         }else{
             $valores["datos_vendores"] = "";
         }
       


    //     #Consulto paquetes

         if(isset($valor["paquete"])){
          $id_paquetes = new MongoDB\BSON\ObjectId((string)$valor["paquete"]);
          $res_paquetes = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array("_id"=>$id_paquetes))->get("paquetes");
         }
         



        #Consulto planes
        $id_planes = new MongoDB\BSON\ObjectId($res_paquetes[0]["plan"]);
        $res_planes = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array("_id"=>$id_planes))->get("planes");
        //Debo volverlo a poner  
        //'eliminado'=>false,
        $valores["planes"] = $res_planes[0]["titulo"]." ".$res_planes[0]["descripcion"];
        
        //Debo volverlo a poner  
        //'eliminado'=>false,
        $valores["paquetes"] = $res_paquetes[0]["codigo"]." ".$res_planes[0]["descripcion"];
        $valores["productos"] = $valores["planes"]."/".$valores["paquetes"];
        $valores["saldo"] = number_format($saldo_acumulado,2);
        $valores["saldo_oculto"] = $saldo_acumulado;
        $valores["vigencia"] = $datos_cotizacion[0]["vigencia"];
        $valores["monto_inscripcion"] = number_format($datos_cotizacion[0]["monto_inscripcion"],2);
        $valores["monto_inscripcion_oculto"] = $datos_cotizacion[0]["monto_inscripcion"];
        $valores["monto_mensualidad_total"] = number_format($datos_cotizacion[0]["monto_mensualidad_total"],2);
        $valores["monto_mensualidad_total_oculto"] = $datos_cotizacion[0]["monto_mensualidad_total"];
        #Consulto usuario
        $id = new MongoDB\BSON\ObjectId($valor["auditoria"][0]->cod_user);
        $res_us = $this->mongo_db->where(array('_id'=>$id))->get('usuario');
        $vector_auditoria = end($valor["auditoria"]);
        $valores["fec_regins"] = $vector_auditoria->fecha->toDateTime();
        //$valores["correo_usuario"] = $res_us[0]["correo_usuario"];
        isset($res_us[0]["correo_usuario"])?$valores["correo_usuario"] = $res_us[0]["correo_usuario"]:$valores["correo_usuario"] = "";
        $listado[] = $valores;
     }
    return $listado;
}
/*
*   Calculo de saldos
*/
public function calcularSaldoTotal($recibos){
    $acum_saldo = 0;
    foreach ($recibos as $clave_recibos => $valor_recibos) {
        if($valor_recibos->pago==0){
            $acum_saldo = $acum_saldo + $valor_recibos->saldo;
        }
    }
    return $acum_saldo;
}
/*
* consultarCobranza
*/
public function consultarCobranza($id_cotizacion){
    $resultadosCobranza = $this->mongo_db->where(array('id_cotizacion'=>$id_cotizacion))->get("cotizacion");
    return $resultadosCobranza;
}
/*
* listado_cobranza_recibos
*/
public function listado_cobranza_recibos($id_cobranza){
    $id = new MongoDB\BSON\ObjectId($id_cobranza);
    $resultadosCobranza = $this->mongo_db->where(array('_id'=>$id))->get("recibos_cobranzas");
    return $resultadosCobranza;
}
/*
* Listado de comprobantes
*/
public function listado_comprobantes($id_cobranza,$numero_recibo){
    $listado = [];
    $id_recibo = $numero_recibo;
    $resultadosComprobantes = $this->mongo_db->where(array('id_cobranza'=>$id_cobranza,'numero_recibo'=>$id_recibo,'eliminado'=>false))->get("recibos_cobranza_comprobantes");
    /*var_dump($id_recibo); echo "<br>";
    var_dump($id_cobranza); echo "<br>";
    var_dump($resultadosComprobantes);echo "------<br>";*/

    foreach ($resultadosComprobantes as $key => $value) {
      $valores = $value;
      $valores["id_recibos_cobranza_comprobantes"] = (string)$value["_id"]->{'$id'};
      $listado[] = $valores;
    }
    //var_dump($resultadosComprobantes);die('');
    return $listado;
}
/*
* Trae la lista de recibos pendientes a la fecha
*/
public function getrecibopendiente($id_cotizacion){
    $resultadosCobranza = $this->mongo_db->order_by(array('_id' => 'ASC'))->where(array('id_venta'=>$id_cotizacion,'status_pago'=>0))->get("recibos_cobranzas");
    return $resultadosCobranza;   
}
/*
* Obtengo el ultimo numero de operacion
*/
public function obtener_ultima_operacion($id_cobranza){
    $id = new MongoDB\BSON\ObjectId($id_cobranza);
    $rs_cobranza = $this->mongo_db->where(array('_id' => $id))->get('recibos_cobranzas');
    //---
    $recibos = $rs_cobranza["0"]["recibos"];
    $ingreso = 0;
    $operacion = "";
    $numero_recibo = "";
    $concepto = "";
    $pendiente_pagar= 1;
    $recibo_ant = "";
    $cuantos = 0;
    ///----------------------------------------------------------------
    #Obtengo el numero de recibo siguiente
    $ultimo_rec = end($recibos);
    $numero_recibo = (integer)$ultimo_rec->numero_recibo+1;
    //-----------------------------------------------------------------
    $this->array_sort_by($recibos,"numero_recibo",$order = SORT_ASC);               
    //var_dump($recibos);die('');
    foreach ($recibos as $clave => $valor) {
        //---------------------------------------
        if(($valor->pago==0)&&($ingreso==0)){
            
            $concepto = $valor->concepto;
            $ingreso = 1;
            $recibo_ant = (integer)$valor->numero_recibo;
            $tipo_registro = $valor->tipo_registro;
        }
        //Verifico si hay algun recibo sin pagar
        if($valor->pago==0){
            $cuantos++;
        }
        ($cuantos>1? $pendiente_pagar=0:$pendiente_pagar=1);
        //---------------------------------------
        //$operacion = (integer)$valor->operacion+1;
        
        //---------------------------------------
    }
    // "operacion"=>$operacion,
    $datos_recibos =  array(
                              "numero_recibo"=>$numero_recibo,
                              "concepto"=>$concepto,
                              "pendiente"=>$pendiente_pagar,
                              "recibo_ant"=>$recibo_ant,
                              "tipo_registro"=>$tipo_registro,
    );
    //---
    return $datos_recibos;
}
/*
*
*/
public function array_sort_by(&$arrIni, $col, $order = SORT_ASC){
      $arrAux = array();
      foreach ($arrIni as $key=> $row)
      {
          $arrAux[$key] = is_object($row) ? $arrAux[$key] = $row->$col : $row[$col];
          $arrAux[$key] = strtolower($arrAux[$key]);
      }
      array_multisort($arrAux, $order, $arrIni);
  }
/*
*   array_sort_by2
*/
public function array_sort_by2(&$arrIni,$col1,$col2){
    /*$arrAux = array();
    foreach ($arrIni as $key=> $row)
    {
        $arrAux[$key] = is_object($row) ? $arrAux[$key] = $row->$col : $row[$col];
        $arrAux[$key] = strtolower($arrAux[$key]);
    }*/
    //array_multisort($arrAux, $order, $arrIni);
    array_multisort(array_column($arrIni, $col1),  SORT_ASC,
              array_column($arrIni,$col2), SORT_ASC,
              $arrIni);
}

/*
* obtenerNumeroOperacion
*/
public function obtenerNumeroOperacion($id_cobranza){
    $id = new MongoDB\BSON\ObjectId($id_cobranza);
    $rs_cobranza = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('_id' => $id))->get('recibos_cobranzas');
    //---
    $recibos = $rs_cobranza["0"]["recibos"];
    $ingreso = 0;
    $operacion = 0;
    $recibos_op = [];
    $this->array_sort_by($recibos,"numero_secuencia",$order = SORT_ASC); 

    foreach ($recibos as $clave => $valor) {
        /*if(($valor->operacion!="")&&($ingreso==0)){
            $operacion = $valor->operacion+1;
            $ingreso=1;
        }*/
        /*var_dump($valor->operacion);echo "-";
        var_dump($operacion);echo"<br>";*/
        if($valor->operacion!=""){
            $recibos_op[] =$valor;
        }
    }
    if(count($recibos_op)>0){
        $this->array_sort_by($recibos_op,"operacion",$order = SORT_DESC); 
        $operacion = $recibos_op[0]->operacion+1;
    }
    
    //---
    if($operacion==0){
      $operacion=1;
    }
    return $operacion;
    //---
}
/*
* registrar_cotizacion
*/
public function registrar_cobranza($id_cobranza,$data,$pendiente_pagar,$recibo_ant, $id_unico){
  //---

  
    //var_dump($recibo_ant);die('');
    $id = new MongoDB\BSON\ObjectId($id_cobranza);

    // if($pendiente_pagar==1){
    //     $datos = array(
    //                     "condicion"=>"VENTAS",
    //                     "status_pago"=>1
    //               );  
    //     #Modificar recibo de cobranzas
    //     $mod_comision = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update('recibos_cobranzas');
    // }
    
    //---
    $this->mongo_db->where(array('_id'=>$id))->push('recibos',$data)->update('recibos_cobranzas');
      

    $datos_recibos = array(
          "recibos.$.pago" => 1,
      );
    $where_array = array('recibos._id' =>  new MongoDB\BSON\ObjectId($id_unico));            
    //--
    #Reseteo
      $res_x = $this->mongo_db->get("mi_empresa");
    #Modifico el anterior....
    $res_mod_ant = $this->mongo_db->where($where_array)->set($datos_recibos)->update("recibos_cobranzas");  
    return $id_recibo;
}
/*
* SaveComprobanteCobranza
*/
public function SaveComprobanteCobranza($data_file){
  #Verifico si existe el comprobante
  $rs_comprobante = $this->mongo_db->where(array("file"=>$data_file["file"],"eliminado"=>false))->get("recibos_cobranza_comprobantes");

  if(count($rs_comprobante)==0){
      $insertar1 = $this->mongo_db->insert("recibos_cobranza_comprobantes", $data_file);
      if($insertar1)
          return true;
      else
          return false;  
  }else{
        return false;
  }
  # 
}
/*
* UpdateComprobanteCobranza
*/
/*public function UpdateComprobanteCobranza($id_cobranza,$data_file){
    $id =  new MongoDB\BSON\ObjectId($id_cobranza);
    //---Verifico si existe el recibo
    $rs_comprobante = $this->mongo_db->where(array('id_cobranza'=>$id_cobranza))->get('recibos_cobranza_comprobantes');
    if(count($rs_comprobante)>0){
        //---
        $comprobante_cobranza = $this->mongo_db->where(array('_id'=>$id))->set($data_file)->update('recibos_cobranza_comprobantes');
        //---
    }else{
        $comprobante_cobranza = $this->mongo_db->insert("recibos_cobranza_comprobantes", $data_file);
    }
    
    if($comprobante_cobranza)
          return true;
      else
          return false;  
}*/
/*
* editPago
*/
public function editPago($id_cobranza,$numero_recibo, $data){

    $fecha = new MongoDB\BSON\UTCDateTime();
    $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));

    $id = new MongoDB\BSON\ObjectId($id_cobranza);
    $numero_recibo = (integer)$numero_recibo;
    $where_array = array('_id' => $id, 'recibos.numero_recibo' => $numero_recibo);

    $res_planes_servicios = $this->mongo_db->where($where_array)->set($data)->update("recibos_cobranzas"); 

    /*var_dump($rs_cobranza);echo "<br>";
    var_dump($res_planes_servicios);echo "<br>";
    var_dump($where_array);echo "<br>";
    var_dump($data);echo "<br>";    
    die(''); */

    //Auditoria...
    $data_auditoria = array(
                                    'cod_user'=>$id_usuario,
                                    'nom_user'=>$this->session->userdata('nombre'),
                                    'fecha'=>$fecha,
                                    'accion'=>'Modificar cotizacion ',
                                    'operacion'=>''
                            );
    $mod_auditoria = $this->mongo_db->where($where_array)->push('recibos.$.auditoria',$data_auditoria)->update("recibos_cobranzas");
    return true;  
    //No le coloque mensaje debido a que de ea forma se manejo en el crmvenmtas
}
/*
* Delete comprobantes
*/
public function deleteComprobante($id_cobranza){
  $id = new MongoDB\BSON\ObjectId($id_cobranza);
  $datos = array(
                      "eliminado"=>true
                );  
  $mod_comision = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update('recibos_cobranza_comprobantes');
}
/*
* Obtener informacion de la cotizacion según id para armar reporte
*/
//--------------------------------------------------------------------
public function getCotizacionById($id_cotizacion){
  #Paso1: Consulto las ventas
  $id = new MongoDB\BSON\ObjectId($id_cotizacion);
  $res_cotizacion = $this->mongo_db->where(array('eliminado'=>false,'_id'=>$id))->get("cotizacion");
  foreach ($res_cotizacion as $clave => $valor) {
      
      $valores["id_cotizacion"] = $res_cotizacion[0]["_id"]->{'$id'};
      $valores["condicion"] = $res_cotizacion[0]["condicion"];
      $valores["numero_cotizacion"] = $res_cotizacion[0]["numero_cotizacion"];
      $valores["monto_inscripcion"] = $res_cotizacion[0]["monto_inscripcion"];
      $valores["monto_mensualidad_total"] = $res_cotizacion[0]["monto_mensualidad_total"];
      $valores["vigencia"] = $res_cotizacion[0]["vigencia"];
      #Obtener datos de vendedores
      $id_vendedor = new MongoDB\BSON\ObjectId($valor["id_vendedor"]);
      
      #Consultar vendedores
      $res_vendedor = $this->mongo_db->where(array("_id"=>$id_vendedor))->get("vendedores");
      $id_us_ve = new MongoDB\BSON\ObjectId($res_vendedor[0]["id_usuario"]);
      $res_dp2 = $this->mongo_db->where(array('id_usuario'=>$id_us_ve))->get('datos_personales');            
      if(count($res_dp2)>0){
          $valores["datos_vendores"] = $res_dp2[0]["nombre_datos_personales"]." ".$res_dp2[0]["apellido_p_datos_personales"];
          $valores["nombre_vendedor"] = $res_dp2[0]["nombre_datos_personales"];
          $valores["apellido_p_vendedor"] = $res_dp2[0]["apellido_p_datos_personales"];
          $valores["apellido_m_vendedor"] = $res_dp2[0]["apellido_m_datos_personales"];
      }else{
          $valores["datos_vendores"] = "";
      }
      
      #Consulto datos clientes...
      $rfc = $valor["identificador_prospecto_cliente"];
      $id =  new MongoDB\BSON\ObjectId($rfc);
      //Consulto el cliente
      $res_cliente = $this->mongo_db->where(array("_id"=>$id))->get("cliente_pagador");
      //Consulto datos personales
      $id_dt = new MongoDB\BSON\ObjectId($res_cliente[0]["id_datos_personales"]);
      $res_dt = $this->mongo_db->where(array('_id' =>  $id_dt))->get("datos_personales");
      
      if(count($res_dt)>0){
          $rfc2 = $res_dt[0]["rfc_datos_personales"];
          $valores["datos_clientes"] = $rfc2."-".$res_dt[0]["nombre_datos_personales"];
          
          isset($valores["apellido_m_datos_personales"])?$valores["apellido_m_datos_personales"]=$res_dt[0]["apellido_m_datos_personales"]:$valores["apellido_m_datos_personales"]="";
          
          isset($valores["apellido_p_datos_personales"])?$valores["apellido_p_datos_personales"]=$res_dt[0]["apellido_p_datos_personales"]:$valores["apellido_p_datos_personales"]="";
          $valores["nombres_clientes"] = $res_dt[0]["nombre_datos_personales"]." ".$valores["apellido_p_datos_personales"]." ".$valores["apellido_m_datos_personales"];
          
      }else{
          $valores["datos_clientes"] = "";
      }

      $id_datos_personales = (string)$res_dt[0]["_id"]->{'$id'};
      $res_cp = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array("id_datos_personales"=>$id_datos_personales))->get("cliente_pagador");
      if(count($res_cp)>0){
        $valores["tipo_cliente"] = $res_cp[0]["tipo_cliente"];
        $valores["id_cliente"] = $res_cp[0]["_id"]->{'$id'};
      }
      #Datos de cotizacion
      $valores["dias_vigencia"] = $valor["vigencia"];
      #Consulto la cobranza
      //$res_cobranza = $this->mongo_db->where(array('eliminado'=>false,'id_venta'=>$valores["id_cotizacion"]))->get("recibos_cobranzas");
      //$valores["fp"] = $res_cobranza[0][""];
      //var_dump($res_cobranza[0]);die('');
      #Consulto usuario
      $id = new MongoDB\BSON\ObjectId($valor["auditoria"][0]->cod_user);
      $res_us = $this->mongo_db->where(array('_id'=>$id))->get('usuario');
      $vector_auditoria = end($valor["auditoria"]);
      $valores["fec_regins"] = $vector_auditoria->fecha->toDateTime();
      $valores["correo_usuario"] = $res_us[0]["correo_usuario"];
      $listado[] = $valores;
      //var_dump($valores);die('');
      #
  }
  return $listado;
}
//--------------------------------------------------------------------
/*public function getventasbyid($id){
     $this->db->select('v.*, 

                        inmobiliarias.nombre as nombre_inmobiliaria, 

                        py.nombre as nombre_proyecto,
                        py.logo as logo_proyecto,
                        py.director as id_director,

                        dpv.nombre_datos_personales as nombre_vendedor,
                        dpv.apellido_p_datos_personales as apellido_p_vendedor,
                        dpv.apellido_m_datos_personales as apellido_m_vendedor,

                        dpc.nombre_datos_personales as nombre_cliente,
                        dpc.apellido_p_datos_personales as apellido_p_cliente,
                        dpc.apellido_m_datos_personales as apellido_m_cliente,
                        cp.tipo_cliente,
                        cp.id_cliente as id_cliente,

                        vdtp.id_producto as id_producto_detalle,

                        vda.*,

                        es.descripcion as nombre_esquema,

                        pd.descripcion as nombre_producto,

                        lve.descriplval as etapa,

                        lvz.descriplval as zona,

                        a.fec_regins as fecha_regsitro,
                        ur.correo_usuario as user_regis,

                        plz.descriplval as name_plazo,
                        dv.descriplval as dias_vigencia,



                        fp.descriplval as name_fp,

                        vd.firma as firma_vendedor

                        ');
     
     $this->db->join('vendedores vd', 'vd.id_vendedor = v.id_vendedor', 'left');
     $this->db->join('datos_personales dpv', 'dpv.id_usuario = vd.id_usuario', 'left');
     $this->db->join('ventas_detalle_producto vdtp', 'vdtp.id_venta = v.id_venta', 'left');
     $this->db->join('ventas_detalle_anticipo_plazo vda', 'vda.id_venta = v.id_venta', 'left');
     $this->db->join('lval plz', 'plz.codlval = vda.plazo_saldo', 'left');
     $this->db->join('lval dv', 'dv.codlval = vda.dias_vigencia', 'left');
     $this->db->join('cliente_pagador cp', 'cp.id_cliente = v.id_cliente_prospecto', 'left');
     $this->db->join('datos_personales dpc', 'dpc.id_datos_personales = cp.id_datos_personales', 'left');
     
     $this->db->join('productos pd', 'pd.id_producto = vdtp.id_producto', 'left');
     $this->db->join('lval lve', 'lve.codlval = pd.etapas', 'left');
     $this->db->join('proyectos_clasificacion pc', 'pc.id_proyecto_clasificacion = pd.cod_proyecto_clasificacion', 'left');
     $this->db->join('descuentos ds', 'ds.id_descuento = vda.id_descuento', 'left');
     $this->db->join('esquemas es', 'es.id_esquema = ds.cod_esquema', 'left');
     $this->db->join('lval lvz', 'lvz.codlval = pc.clasificacion', 'left');
     $this->db->join('lval fp', 'fp.codlval = vda.id_forma_pago', 'left');
     $this->db->join('auditoria a', 'v.id_venta = a.cod_reg','left');
     $this->db->join('usuario ur', 'a.usr_regins = ur.id_usuario');
     $this->db->join('inmobiliarias', 'inmobiliarias.id_inmobiliaria = v.id_inmobiliaria', 'left');
     $this->db->join('proyectos py', 'py.id_proyecto = v.id_proyecto', 'left');
     $this->db->where('a.tabla', "ventas");
     $this->db->where('v.id_venta', $id);
     
     $this->db->order_by('v.id_venta', 'desc');
     $resultados = $this->db->get('ventas v');
     return $resultados->row();
}*/
/*
* getabonos
*/
public function getabonos($id_venta){
    
    /*$this->db->where('id_venta', $id_venta);
    $this->db->where('tipo_operacion', "A");
    $recibos = $this->db->get('recibos_cobranza');
    return $recibos->result();*/
    //---


    $res_cobranza = $this->mongo_db->where(array('eliminado'=>false,'id_venta'=>$id_venta))->get("recibos_cobranzas");

    if($res_cobranza){
        //--
        $recibos = $res_cobranza[0]["recibos"];
       
        foreach ($recibos as $clave => $valor) {
             $valores = $valor;
            
            $valores->fecha_movimiento = $valor->fecha_contable->date;
         
            $listado[]=$valores;
        }
        $recibo = (array)$listado;
        //$this->array_sort_by($recibo,"numero_secuencia",$order = SORT_ASC);
        $this->array_sort_by2($recibo,"numero_secuencia","operacion");  
        //--
    }else{
        $recibo =  "";
    }
    return $recibo;
    //---
}  
/*
*   getcobranzaventa
*/
public function getcobranzaventa($id_venta){
    /*$this->db->where('id_venta', $id_venta);
    $this->db->order_by('recibo', 'asc');
    $this->db->order_by('operacion', 'asc');
    $this->db->order_by('id', 'asc');
    $this->db->select('recibos_cobranza.*, b.nombre_banco, lv.descriplval as fp, mc.numero_cuenta');
    $this->db->join('mis_cuentas mc', 'mc.id_cuenta = recibos_cobranza.banco_pago', 'left');
    $this->db->join('banco b', 'b.id_banco = mc.id_banco', 'left');
    $this->db->join('lval lv', 'lv.codlval = recibos_cobranza.forma_pago', 'left');
    $result = $this->db->get('recibos_cobranza');
    return $result->result();*/
    //---------------------------------------------------------------------------------
    $res_cobranza = $this->mongo_db->where(array('eliminado'=>false,'id_venta'=>$id_venta))->get("recibos_cobranzas");
    
    if($res_cobranza){
        $cobranza = $res_cobranza;
    }else{
        $cobranza = "";
    }
    return $cobranza;
    //---------------------------------------------------------------------------------
}
/*
* Consulta de datos del cliente
*/
public function getcliente($id_cliente){
    #-Consulto los datos del cliente...
    $id = new MongoDB\BSON\ObjectId($id_cliente);
    $res_cliente_pagador = $this->mongo_db->where(array('eliminado'=>false,'_id'=>$id))->get("cliente_pagador");
    $datos = [];
    if(count($res_cliente_pagador)>0){
      #-Consulto los datos personales
      $id_dt = new MongoDB\BSON\ObjectId($res_cliente_pagador[0]["id_datos_personales"]);
      $res_dt = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array("_id"=>$id_dt))->get("datos_personales");
      
      if(count($res_dt)>0){
         
          isset($res_dt[0]["apellido_p_datos_personales"])?$apellido_p_datos_personales=$res_dt[0]["apellido_p_datos_personales"]:$apellido_p_datos_personales=="";
          
          isset($res_dt[0]["apellido_m_datos_personales"])?$apellido_m_datos_personales=$res_dt[0]["apellido_m_datos_personales"]:$apellido_m_datos_personales=="";
          
          $nombre_cliente = $res_dt[0]["nombre_datos_personales"]." ".$apellido_p_datos_personales." ".$apellido_m_datos_personales;
      }
      #-Consulto los datos de contacto
      $id_cont = new MongoDB\BSON\ObjectId($res_cliente_pagador[0]["id_contacto"]);
      $res_cont = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array("_id"=>$id_cont))->get("contacto");
      $correo_cliente = $res_cont[0]["correo_contacto"];
      //--
      $datos= array(
                            "correo_cliente"=>$correo_cliente,
                            "nombre_cliente"=>$nombre_cliente  
                          );
      //--
    }
    return $datos;
}
public function consultar_cuenta_cliente($data){
    //--
    $res_cuenta_cliente = $this->mongo_db->where(array('eliminado'=>false,'id_banco'=>$data["banco"],'id_cliente'=>$data["id_cliente"]))->get("cuenta_cliente");
    //--
    return $res_cuenta_cliente;
    //--
}
/*
*   Consultar cuentas
*/
public function consultar_cuenta_cliente_id($id_cuenta){
    //--
    $id = new MongoDB\BSON\ObjectId($id_cuenta);
    $res_cuenta_cliente = $this->mongo_db->where(array('_id'=>$id))->get("cuenta_cliente");
    //--
    return $res_cuenta_cliente;
    //--
}
/*
* Fin de bloque de metodos realizados por @santu1987
*/
//---------------------------------------------
  public function getipospagos()
  {
    $this->db->where('lv.tipolval', 'FORMAPAGO');
    $this->db->select('lv.codlval, lv.descriplval');
    $result = $this->db->get('lval lv');
    return $result->result();
  }

  /*public function getcobranzaventa($id_venta)
  {
    $this->db->where('id_venta', $id_venta);
    $this->db->order_by('recibo', 'asc');
    $this->db->order_by('operacion', 'asc');
    $this->db->order_by('id', 'asc');
    $this->db->select('recibos_cobranza.*, b.nombre_banco, lv.descriplval as fp, mc.numero_cuenta');
    $this->db->join('mis_cuentas mc', 'mc.id_cuenta = recibos_cobranza.banco_pago', 'left');
    $this->db->join('banco b', 'b.id_banco = mc.id_banco', 'left');
    $this->db->join('lval lv', 'lv.codlval = recibos_cobranza.forma_pago', 'left');
    $result = $this->db->get('recibos_cobranza');
    return $result->result();
  }*/


  /*public function getabonos($id_venta){
    
      $this->db->where('id_venta', $id_venta);
      $this->db->where('tipo_operacion', "A");
      $recibos = $this->db->get('recibos_cobranza');

      return $recibos->result();
    
  }*/



  public function getenganche($id_venta)
  {
    $this->db->where('id_venta', $id_venta);
    $this->db->where('status', 0);
    $this->db->select('recibos_cobranza.*');
    $result = $this->db->get('recibos_cobranza');
    return $result->row();
  }


  
  public function getoperaciones($id_venta, $recibo)
  {
    $this->db->where('id_venta', $id_venta);
    $this->db->where('tipo_operacion', 'A');
    $this->db->select('MAX(operacion) as operacion');
    $result = $this->db->get('recibos_cobranza');
    return $result->row();
  }

  public function savepago($data, $id_recibo)
  { 
    $update = array('status' => 1);
    $this->db->where('id', $id_recibo);
    $this->db->update('recibos_cobranza', $update);
    

    $insert =  $this->db->insert('recibos_cobranza', $data);
    $id_cobranza = $this->db->insert_id();

    return $id_cobranza;
  }

  public function SaveDevolution($data)
  {
  
    $insert =  $this->db->insert('recibos_cobranza', $data);
  }

  public function getdatarecibo($id)
  {
    $this->db->where('id', $id);
    return $this->db->get('recibos_cobranza')->row();
  }
  public function savemora($data)
  { 
    $this->db->insert('recibos_cobranza', $data);


    $id_venta   = $data["id_venta"];
    $monto_mora = $data["monto_mora"];

    $this->db->where('id_venta', $id_venta);
    $venta = $this->db->get('ventas_detalle_anticipo_plazo')->row();

    $saldo = $venta->saldo;

    $new_saldo = $saldo + $monto_mora;

    $data2 = array('saldo' => $new_saldo);
    $this->db->where('id_venta', $id_venta);
    $this->db->update('ventas_detalle_anticipo_plazo', $data2);
  }



  public function getdiasmoraproyecto($id_venta)
  {
    $this->db->select('py.indicador_mora, py.can_dias_vencidos, py.porcentaje_mora');
    $this->db->join('proyectos py', 'py.id_proyecto = v.id_proyecto');
    $this->db->where('id_venta', $id_venta);
    $result = $this->db->get('ventas v');
    return $result->row();
  }

  /*public function getcliente($id_cliente)
  {
    $this->db->where('cp.id_cliente', $id_cliente);
    $this->db->select('ct.correo_contacto, dp.nombre_datos_personales, dp.apellido_p_datos_personales, dp.apellido_m_datos_personales');
    $this->db->join('contacto ct', 'ct.id_contacto = cp.id_contacto');
    $this->db->join('datos_personales dp', 'dp.id_datos_personales = cp.id_datos_personales');
    $result = $this->db->get('cliente_pagador cp');
    return $result->row();
  }*/


   

    public function saldototalpendiente($id_venta)
    {
      $this->db->where('id_venta', $id_venta);
      $this->db->where('status', 0);
      $this->db->select('SUM(saldo) as saldo_pendiente');
      $result = $this->db->get('recibos_cobranza');
      return $result->row();
    }

    public function mora_pendiente($id_venta)
    {
      $this->db->where('id_venta', $id_venta);
      $this->db->where('mora', 1);
      $this->db->where('status', 0);
      $this->db->select('dias_mora, porcentaje_mora, monto_mora');
      $result = $this->db->get('recibos_cobranza');
      return $result->row();
    }

    public function getMoraPagadas($venta)
    {
      $this->db->where('id_venta', $venta);
      $this->db->where('concepto', "PAGO DE INTERES POR MORA");
      $this->db->select('SUM(abono) as abono');
      $result = $this->db->get('recibos_cobranza');
      return $result->row();
    }


    public function getfechacoutaoriginal($id_venta, $recibo)
    {
      $this->db->where('id_venta', $id_venta);
      $this->db->where('recibo', $recibo);
      $this->db->where('tipo_operacion', 'C');
      $result = $this->db->get('recibos_cobranza');
      return $result->row()->fecha;
    }

    public function getmisbancos()
    { 
      $this->db->where('a.status', 1);
      $this->db->where('a.tabla', "mis_cuentas");
      $this->db->join('auditoria a', 'a.cod_reg = mis_cuentas.id_cuenta');
      $this->db->join('banco b', 'b.id_banco = mis_cuentas.id_banco');
      $this->db->group_by('b.id_banco');
      $result = $this->db->get('mis_cuentas');
      return $result->result();
    }


    public function updatesaldo($id_venta, $monto_pago)
    {
      $this->db->where('id_venta', $id_venta);
      $venta = $this->db->get('ventas_detalle_anticipo_plazo')->row();

      $saldo = $venta->saldo;

      $new_saldo = $saldo - $monto_pago;

      if ($monto_pago > $saldo) {
        $new_saldo = 0;
      }

      $data = array('saldo' => $new_saldo);
      $this->db->where('id_venta', $id_venta);
      $this->db->update('ventas_detalle_anticipo_plazo', $data);

    }


    public function liquidar($id_venta, $sts_producto)
    {

      $this->db->where('id_venta', $id_venta);
      $result = $this->db->get('ventas_detalle_producto');
      $productos =  $result->result();


      $update = array('status' => 2);
      $this->db->where('id_venta', $id_venta);
      $this->db->update('ventas', $update);

      foreach ($productos as  $value) {
        $data = array('STSPRODUCTO' => $sts_producto);
        $this->db->where('id_producto', $value->id_producto);
        $this->db->update('productos', $data);
      }
    }


    public function generar_credito($id_cobranza, $monto)
    {
      $data = array('id_operacion' => $id_cobranza,
                    'monto'        => $monto
      );
      $this->db->insert('notas_credito', $data);
    }


    public function buscar_credito($id_venta)
    {
      $this->db->where('rc.id_venta', $id_venta);
      $this->db->where('nc.status', 1);
      $this->db->join('recibos_cobranza rc', 'rc.id = nc.id_operacion');
      $creditos = $this->db->get('notas_credito nc')->row();

      return $creditos;
    }

    public function updatecredito($id_operacion)
    {
      $data = array('status' => 0);
      $this->db->where('id_operacion', $id_operacion);
      $this->db->update('notas_credito', $data);
    }

    public function deleteabonos($id_venta)
    {
      $this->db->where('id_venta', $id_venta);
      $this->db->where('tipo_operacion', "A");
      $this->db->delete('recibos_cobranza');


      $data = array('status' => 0);
      $this->db->where('id_venta', $id_venta);
      $this->db->update('recibos_cobranza', $data);
    }


    public function getoperaciones2($id_venta)
    {

      $this->db->where('id_venta', $id_venta);
      $this->db->where('operacion != ', null);
      $this->db->group_by('operacion');
      $result = $this->db->get('recibos_cobranza')->result();
      return $result;
    }


    public function getoperaciondetalle($operacion, $id_venta)
    {
      $this->db->where('id_venta', $id_venta);
      $this->db->where('operacion', $operacion);
      $result = $this->db->get('recibos_cobranza')->result();
      return $result;
    }

    public function getrecibo($recibo, $id_venta)
    {
      $this->db->where('id_venta', $id_venta);
      $this->db->where('recibo', $recibo);
      $result = $this->db->get('recibos_cobranza')->row();
      return $result;
    }


    public function getcargos($id_venta)
    {
      $this->db->where('id_venta', $id_venta);
      $this->db->where('tipo_operacion', "C");
      $this->db->where('mes !=',0);
      $result = $this->db->get('recibos_cobranza')->result();
      return $result;
    }

    public function liberarRecibo($id_venta)
    {
      $data = array('status' => 0);
      $this->db->where('id_venta', $id_venta);
      $this->db->update('recibos_cobranza', $data);
    }
    public function update_recibo($update, $id_venta)
    { 
      $this->db->where('id_venta', $id_venta);
      $this->db->update('recibos_cobranza', $update);
    }


    public function getMorasByVenta($id_venta)
    {
      $this->db->where('tipo_operacion', "C");
      $this->db->where('id_venta', $id_venta);
      $this->db->like('concepto', 'INTERES POR MORA');
      return $this->db->get('recibos_cobranza')->result();
    }



    /*public function SaveComprobanteCobranza($data)
    {
      $this->db->insert('recibos_cobranza_comprobantes', $data);
    }*/

    public function getcomprobantesrecibo($id)
    {
      $this->db->where('id_recibos', $id);
       return $this->db->get('recibos_cobranza_comprobantes')->result();
    }

    /*public function editPago($id_recibo, $data)
    {
      $this->db->where('id', $id_recibo);
      $this->db->update('recibos_cobranza', $data);
    }*/

    /*public function deleteComprobante($id)
    {
      $this->db->where('id_recibos_cobranza_comprobantes', $id);
      $this->db->delete('recibos_cobranza_comprobantes');
    }*/



    public function GetComprobantesRecibos()
    {
      $this->db->where('file_comprobante != ', NULL);
      $this->db->where('file_comprobante != ', "null");
      return $this->db->get('recibos_cobranza')->result();
    }

    public function insertComprobanteVenta($id, $id_cotizacion, $file)
    {
      $this->db->where('id_venta', $id_cotizacion);
      $this->db->where('id_recibos', $id);
      $this->db->delete('recibos_cobranza_comprobantes');

      $data = array('id_venta' => $id_cotizacion, "id_recibos" => $id, "file" => $file );
      $this->db->insert('recibos_cobranza_comprobantes', $data);
    }


}

/* End of file Cobranza_model.php */
/* Location: ./application/models/Cobranza_model.php */
/*
  37 metodos
*/
