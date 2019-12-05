<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cotizacion_model extends CI_Model {
    private $tabla_clientePagador = "cliente_pagador";
    private $tabla_lval       = "lval";
    private $tabla_cuenta_clientePa = "cuenta_cliente";
    private $tabla_repLegal     = "repLegal_cliente_pagador";
    private $tabla_datosPersonales  = "datos_personales";
    private $tabla_contacto     = "contacto";
    private $tabla_paquetes = "paquetes";
    
    /*
    *   Listado cotizacion
    */
    public function listado_cotizacion(){
        $listado = [];
        $resultados = $this->mongo_db->order_by(array('numero_cotizacion' => 'DESC'))->where(array('eliminado'=>false))->get("cotizacion");
        $contador = 0;
        foreach ($resultados as $clave => $valor) {
            $valores = $valor;

            $valores["id_cotizacion"] = (string)$valor["_id"]->{'$id'};
            /*
            *   Verifico si la cotizacion tiene cobranza con el primer recibo cancelado
            */
            $res_cotizacion_cobranza = $this->mongo_db->where(array("id_venta"=>$valores["id_cotizacion"],"condicion"=>"VENTAS" ))->get("recibos_cobranzas");
            if(count($res_cotizacion_cobranza)>0){
                $valores["tiene_cobranza"] = true;
            }else{
                $valores["tiene_cobranza"] = false;
            }
            #Consulto datos personales
            
            $rfc = $valor["identificador_prospecto_cliente"];
            
            $id_cliente = new MongoDB\BSON\ObjectId($rfc);
            
            $res_cliente_pagador = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,'_id'=>$id_cliente))->get($this->tabla_clientePagador);
            
            $id_datos_personales =  new MongoDB\BSON\ObjectId($res_cliente_pagador[0]["id_datos_personales"]);
            
            $valores["tipo_cliente"] = $res_cliente_pagador[0]["tipo_cliente"];

            $res_dt = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array("_id"=>$id_datos_personales))->get("datos_personales");
            
            if(count($res_dt)>0){
                $valores["datos_clientes"] = $res_dt[0]["rfc_datos_personales"]."-".$res_dt[0]["nombre_datos_personales"];
            }else{
                $valores["datos_clientes"] = "";
            }
            //--------------
            $id_vendedor = new MongoDB\BSON\ObjectId($valor["id_vendedor"]);
            
            #Consultar vendedores
            $res_vendedor = $this->mongo_db->where(array("_id"=>$id_vendedor))->get("vendedores");
            $id_us_ve = new MongoDB\BSON\ObjectId($res_vendedor[0]["id_usuario"]);
            $res_dp2 = $this->mongo_db->where(array('id_usuario'=>$id_us_ve))->get('datos_personales');            
            if(count($res_dp2)>0){
                $valores["datos_vendores"] = $res_dp2[0]["nombre_datos_personales"]." ".$res_dp2[0]["apellido_p_datos_personales"];
            }else{
                $valores["datos_vendores"] = "";
            }
            //--------------
            // #Consulto planes
            // $id_planes = new MongoDB\BSON\ObjectId($valor["plan"]);
            // $res_planes = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array("_id"=>$id_planes))->get("planes");
            // //Debo volverlo a poner  
            // //'eliminado'=>false,
            // $valores["planes"] = $res_planes[0]["titulo"]." ".$res_planes[0]["descripcion"];
            // #Consulto paquetes
            // $id_paquetes = new MongoDB\BSON\ObjectId($valor["paquete"]);
            // $res_paquetes = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array("_id"=>$id_paquetes))->get("paquetes");
            // //Debo volverlo a poner  
            // //'eliminado'=>false,
            // $valores["paquetes"] = $res_paquetes[0]["codigo"]." ".$res_planes[0]["descripcion"];
             #Consulto usuario
             $id = new MongoDB\BSON\ObjectId($valor["auditoria"][0]->cod_user);
             $res_us = $this->mongo_db->where(array('_id'=>$id))->get('usuario');
             $vector_auditoria = end($valor["auditoria"]);
             $valores["fec_regins"] = $vector_auditoria->fecha->toDateTime();
            
            isset($res_us[0]["correo_usuario"])?$valores["correo_usuario"] = $res_us[0]["correo_usuario"]:$valores["correo_usuario"] = "";
            //--
            $valores["fecha_cotizacion"] = $valor["fecha_cotizacion"]->toDateTime();
            $contador++;
            $valores["numero"] = $contador;
            //---Obtengo y configuro los montos ------///
            $valores["cantidad_usuarios"] = $valor["cantidad_usuarios"];
            $valores["monto_inscripcion"] = number_format($valor["monto_inscripcion"],2);
            $valores["monto_mensualidad_individual"] = number_format($valor["monto_mensualidad_individual"],2);
            $valores["monto_mensualidad_total"] = number_format($valor["monto_mensualidad_total"],2);
            $valores["monto_total"] = number_format($valor["monto_total"],2);
            //--Montos ocultos...
            $valores["monto_inscripcion_oculto"] = str_replace(",","",$valor["monto_inscripcion"]);
            $valores["monto_mensualidad_individual_oculto"] = str_replace(",","",$valor["monto_mensualidad_individual"]);
            $valores["monto_mensualidad_total_oculto"] = str_replace(",","",$valor["monto_mensualidad_total"]);
            $valores["monto_total_oculto"] = str_replace(",","",$valor["monto_total"]);
            /*
            *   Consulto si la cotización tiene registro de correo enviado
            */
            $res_cotizacion_correo = $this->mongo_db->where(array("id_cotizacion"=>$valores["id_cotizacion"] ))->get("cotizacion_correo");
            if(count($res_cotizacion_correo)>0){
                $valores["tiene_correo"] = true;
            }else{
                $valores["tiene_correo"] = false;
            }
            
            //--- ---//
            $listado[] = $valores;
        }
        return $listado;
    }
    /*
    *   Función que consulta los vendedores...
    */
    public function getVendedores(){
        //Migracion Mongo db
        if($this->session->userdata('id_rol')!="5b8dc599d06020d3e9a9eb90"){
            $id_user = $this->session->userdata('id_usuario');
            $resultados = $this->mongo_db->where(array('id_usuario'=>$id_user,'eliminado'=>false,'status'=>true))->get("vendedores");
        }else{
            $resultados = $this->mongo_db->where(array('eliminado'=>false,'status'=>true))->get("vendedores");
        }
        $listado = [];
        //var_dump($resultados);die('');

        foreach ($resultados as $valor) {
          $valor["id_vendedor"] = (string)$valor["_id"]->{'$id'};
          $id_us_ve = new MongoDB\BSON\ObjectId($valor["id_usuario"]);
          $res_dp2 = $this->mongo_db->where(array('id_usuario'=>$id_us_ve))->get('datos_personales');
          $valor["nombre_vendedor"] = $res_dp2[0]["nombre_datos_personales"];
          $valor["apellido_vendedor"] = $res_dp2[0]["apellido_p_datos_personales"];
          $listado[] = $valor; 
        }

        return $listado;
    }
    /*
    *   Listado de clientes segun rfc
    */
    public function consultarClientePagadorRfc($rfc,$tipo_persona){
        //-----------------------------------------------------------------------------------
        $listado = [];
        $valores = [];   
        #1-Consulto cliente pagador
        //
        $id_cliente = new MongoDB\BSON\ObjectId($rfc);

        $res_cliente_pagador = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,'_id'=>$id_cliente,"tipo_persona_cliente"=>$tipo_persona))->get($this->tabla_clientePagador);
      
        if(count($res_cliente_pagador)>0){
        //----------------------------------
            foreach ($res_cliente_pagador as $clave => $valor) {
                $valores["id_cliente"] = (string)$valor["_id"]->{'$id'};
                (isset($valor["actividad_e_cliente"]))? $valores["actividad_e_cliente"] = $valor["actividad_e_cliente"]:$valores["actividad_e_cliente"] = "";
                
                $valores["rfc_img"] = $valor["rfc_img"];
                
                (isset($valor["pais_cliente"]))? $valores["pais_cliente"] = $valor["pais_cliente"]:$valores["pais_cliente"] = "";
                
                $valores["tipo_persona_cliente"] = $valor["tipo_persona_cliente"];
                $valores["dominio_fiscal_img"] = $valor["dominio_fiscal_img"];
                
                (isset($valor["acta_constitutiva"]))? $valores["acta_constitutiva"] = $valor["acta_constitutiva"]:$valores["acta_constitutiva"] = "";

                (isset($valor["acta_img"])) ? $valores["acta_img"] = $valor["acta_img"]: $valores["acta_img"] = "";
                (isset($valor["giro_mercantil"])) ? $valores["giro_mercantil"] = $valor["giro_mercantil"]:$valores["giro_mercantil"] = "";

                (isset($valor["pasaporte"])) ? $valores["pasaporte"] = $valor["pasaporte"]:$valores["pasaporte"] = "";
                (isset($valor["tipo_cliente"])) ? $valores["tipo_cliente"] = $valor["tipo_cliente"]: $valores["tipo_cliente"]="";

                $valores["id_datos_personales"] = new MongoDB\BSON\ObjectId($valor["id_datos_personales"]);
                //---
                #cambio para mostrar la imagen del cliente abril 2019
               (isset($valor["imagenCliente"]))? $valores["imagenCliente"] = $valor["imagenCliente"]:$valores["imagenCliente"] = "default-img.png";
                //---
                //-----------------------------------------------------------------------------------
                //--Consulto contactos
                $id_contacto = new MongoDB\BSON\ObjectId($valor["id_contacto"]);
                $res_contacto = $this->mongo_db->where(array("_id"=>$id_contacto))->get($this->tabla_contacto);
                foreach ($res_contacto as $clave_contacto => $valor_contacto) {
                    $valores["id_codigo_postal"] = $valor_contacto["id_codigo_postal"];
                    $valores["telefono_principal_contacto"] = $valor_contacto["telefono_principal_contacto"];
                    (isset($valor_contacto["correo_contacto"]))? $valores["correo_contacto"] = $valor_contacto["correo_contacto"]:$valores["correo_contacto"] = "";

                    (isset($valor_contacto["telefono_movil_contacto"]))? $valores["telefono_movil_contacto"]=$valor_contacto["telefono_movil_contacto"]: $valores["telefono_movil_contacto"] ="";
                    
                    (isset($valor_contacto["direccion_contacto"])) ? $valores["direccion_contacto"] = $valor_contacto["direccion_contacto"]:$valores["direccion_contacto"] ="";

                    (isset($valor_contacto["calle_contacto"])) ? $valores["calle_contacto"] = $valor_contacto["calle_contacto"]:$valores["calle_contacto"] = "";

                    (isset($valor_contacto["exterior_contacto"]))? $valores["exterior_contacto"] = $valor_contacto["exterior_contacto"]:$valores["exterior_contacto"] ="";

                    (isset($valor_contacto["interior_contacto"])) ? $valores["interior_contacto"] = $valor_contacto["interior_contacto"]:$valores["interior_contacto"] ="";

                    #$valores["status"] = $valor_contacto["status"];
                }
                //--------------------------------------------------------------
                //-----------------------------------------------------------------------------------
                //--Consulto usuario
                $id_registro = $valor["auditoria"][0]->cod_user;
                $id = new MongoDB\BSON\ObjectId($id_registro);
                $res_us_rg = $this->mongo_db->where(array("_id"=>$id))->get("usuario");
                foreach ($res_us_rg as $clave_us_reg => $valor_us_reg) {
                    $valores["user_regis"] = $valor_us_reg["correo_usuario"];
                    $valores["id_rol"] = (string)$valor_us_reg["id_rol"];
                    $valores["correo_usuario"] = $valor_us_reg["correo_usuario"];
                }
                //$valores["fec_regins"] = $valor["auditoria"][0]->fecha;
                $vector_auditoria = end($valor["auditoria"]);
                
                $valores["fec_regins"] = $vector_auditoria->fecha->toDateTime();
                
                $valores["status"] = $valor["status"];
                //-----------------------------------------------------------------------------------
                //aqui pongo error en false si la data esta ok...
                $valores["error"] = false;
                //--------------------------------------------------------------
                 #2-Consulto datos personales....
                $id_dt = new MongoDB\BSON\ObjectId($valores["id_datos_personales"]);
                $res_datos_personales = $this->mongo_db->where(array('eliminado'=>false,'_id'=>$id_dt))->get('datos_personales');
                
                if(count($res_datos_personales)==0){
                    //aqui va un error si no existe en datos personales
                    //$listado[0]["error"] = true;
                    $valores["error"] = true;
                }else {
                    foreach ($res_datos_personales as $clave_dt => $valor_dt) {
                        
                        $valores["id_datos_personales"] = (string)$valor_dt["_id"]->{'$id'};
                        
                        $valores["id_contacto"] = (string)$valor_dt["id_contacto"];
                        $valores["nombre_datos_personales"] = $valor_dt["nombre_datos_personales"];

                        (isset($valor_dt["apellido_p_datos_personales"]))? $valores["apellido_p_datos_personales"] = $valor_dt["apellido_p_datos_personales"]: $valores["apellido_p_datos_personales"] ="";

                        (isset($valor_dt["apellido_m_datos_personales"]))? $valores["apellido_m_datos_personales"] = $valor_dt["apellido_m_datos_personales"]: $valores["apellido_m_datos_personales"] ="";
                        
                        (isset($valor_dt["rfc_datos_personales"]))? $valores["rfc_datos_personales"] = $valor_dt["rfc_datos_personales"]:$valores["rfc_datos_personales"] = "";
                      
                    } //Fin datos personales
                }
                //----------------------------------------------------------------
                $listado[] = $valores;
                //----------------------------------------------------------------

            }//Fin de ForEach de cliente Pagador
        //----------------------------------    
        }else{
            //aqui va otro error si no esta en clientes
            $valores["error"] = true;
        }
        //Fin cliente pagador
       
        //-------------------------------------------------------------------------------
        return $listado;
    } 
    /*
    *   listado_clientes
    */
    public function listado_clientes($tipo){
        $listado = [];
        $resultados = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,'status'=>true,'tipo_persona_cliente'=>$tipo,'tipo_cliente'=>'CLIENTE'))->get("cliente_pagador");
        
        foreach ($resultados as $clave => $valor) {
            
            $valores = $valor;
            $valores["id_datos_personales"] = $valor["id_datos_personales"];
            $valores["id_clientes"] = (string)$valor["_id"]->{'$id'};
            #Consulto datos personales
            $id_dt =  new MongoDB\BSON\ObjectId($valores["id_datos_personales"]);
            $res_dt = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,"_id"=>$id_dt))->get("datos_personales");
            $nombre_datos_personales = $res_dt[0]["nombre_datos_personales"];
            $rfc = $res_dt[0]["rfc_datos_personales"];
            if($rfc!=""){
                $valores["rfc"] = $rfc;
                if(isset($res_dt[0]["apellido_p_datos_personales"])){
                    $nombre_datos_personales.=" ".$res_dt[0]["apellido_p_datos_personales"];
                }

                if(isset($res_dt[0]["apellido_m_datos_personales"])){
                    $nombre_datos_personales.=" ".$res_dt[0]["apellido_m_datos_personales"];
                }

                $valores["nombre_datos_personales"] = $rfc."-".$nombre_datos_personales;
                
                $listado[] = $valores;
            }
        }
        return $listado;
    }
    /*
    *   Listado de planes
    */
    public function listado_planes(){
        
        $listado = [];
        $resultados = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false))->get("planes");
        foreach ($resultados as $clave => $valor) {
            //----------------------------------------------------
            //---Consulto paquetes: Si el plan no forma parte de un paquete no deberia listarse....
            $id_planes = (string)$valor["_id"]->{'$id'};
            //$rs_paquetes = $this->mongo_db->where(array('eliminado'=>false,'id_plan'=>$id_planes))->get('paquetes');
            //if(count($rs_paquetes)>0){
            //------------------------------------------------
            $auditoria = $valor["auditoria"][0];
            //--usuario en cuestion
            $id = new MongoDB\BSON\ObjectId($valor["auditoria"][0]->cod_user);
            $res_us = $this->mongo_db->where(array('_id'=>$id))->get('usuario');
            //var_dump($res_us[0]["auditoria"]->status);die('');
            //$valor["fec_regins"] = $res_us[0]["auditoria"][0]->fecha->toDateTime();
            $vector_auditoria = end($valor["auditoria"]);
            $valor["fec_regins"] = $vector_auditoria->fecha->toDateTime();
            
            (isset($res_us[0]["correo_usuario"]))? $valor["correo_usuario"] = $res_us[0]["correo_usuario"]:$valor["correo_usuario"] = "";
            $valor["status"] = $valor["status"];
            $valor["id_planes"] = (string)$valor["_id"]->{'$id'};
            
            if(isset($valor["jornadas_limitadas"])){
                ($valor["jornadas_limitadas"]== true)? $valor["ind_jornada"] = "S" : $valor["ind_jornada"] = "N";
            }else{
                $valor["jornadas_limitadas"] = "";
                $valor["ind_jornada"] = "";
            }

            if(isset($valor["plan_empresarial"])){
                ($valor["plan_empresarial"]== true)? $valor["ind_plan_empresarial"] = "S" : $valor["ind_plan_empresarial"] = "N";
            }else{
                $valor["plan_empresarial"] = "";
                $valor["ind_plan_empresarial"] = "";
            }

            (!isset($valor["horas_jornadas"]))? $valor["horas_jornadas"] = ""  : $valor["horas_jornadas"] = $valor["horas_jornadas"];
            //--Consulto la vigencia
            $id_vigencia = new MongoDB\BSON\ObjectId($valor["id_vigencia"]);            
            $res_vigencia = $this->mongo_db->where(array('_id'=>$id_vigencia))->get('vigencia');
            $valor["vigencia"] = $res_vigencia[0]["descripcion"];
           

            $listado[] = $valor;
            //------------------------------------------------
            //}
            //----------------------------------------------------
        }    
        //--
        $listado2 = $listado;
        return $listado2;    
    }
    /*
    *   Listado paquetes
    */
    public function listado_paquetes(){
        //------------------------------------------------------------------------------
        //--Modificacion con Mongo db
        //---------------------------------------------------------------------------
        $listado = [];
        $resultados = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false))->get($this->tabla_paquetes);
        foreach ($resultados as $clave => $valor) {
            $auditoria = $valor["auditoria"][0];
            //var_dump($auditoria->cod_user);die('');
            //--usuario en cuestion
            $id = new MongoDB\BSON\ObjectId($valor["auditoria"][0]->cod_user);
            $res_us = $this->mongo_db->where(array('_id'=>$id))->get('usuario');
            //var_dump($res_us[0]["auditoria"]->status);die('');
            //$valor["fec_regins"] = $res_us[0]["auditoria"][0]->fecha->toDateTime();
            $vector_auditoria = end($valor["auditoria"]);
            $valor["fec_regins"] = $vector_auditoria->fecha->toDateTime();
            
            //$valor["correo_usuario"] = $res_us[0]["correo_usuario"];
            (isset($res_us[0]["correo_usuario"]))? $valor["correo_usuario"] = $res_us[0]["correo_usuario"]:$valor["correo_usuario"] = "";
            $valor["status"] = $valor["status"];
            $valor["id_paquete"] = (string)$valor["_id"]->{'$id'};
            $valor["descripcion_paquete"] = (string)$valor["descripcion"]; 
           
            //-------------------------------------------------            
            $listado[] = $valor;
        }    
        //--
        $listado2 = $listado;
        return $listado2;
        //---------------------------------------------------------------------------
    }
     /*
    *   Metodo para buscar paquetes...
    */
    public function buscarPaquetes($id_planes){
        //------------------------------------------
            $res_paquetes = $this->mongo_db->where(array('eliminado'=>false))->get('paquetes');
            $listado = [];
            $valores = [];
            $paquetes = [];
            //--Recorro los paquetes
            foreach ($res_paquetes as $key => $value) {
                //--Recorro los planes_servicios
                //$planes_servicios = $value["planes_servicios"];
                //foreach ($planes_servicios as $key_planes => $value_planes) {
                if($value["plan"]==$id_planes){
                    $value["id_paquete"] = (string)$value["_id"]->{'$id'};
                    $paquetes[] = $value;
                }
                //}
                //--
            }
            $valores = $paquetes;
            return $valores;
        //------------------------------------------    
    }



    public function getPlan($id_planes){
        //------------------------------------------

            $id = new MongoDB\BSON\ObjectId($id_planes);
            $res_plan = $this->mongo_db->where(array('_id' => $id, 'eliminado'=>false))->get('planes');
            

            return $res_plan;
         
        //------------------------------------------    
    }



    public function getPaquete($id_paquete){
        //------------------------------------------

            $id = new MongoDB\BSON\ObjectId($id_paquete);
            $res = $this->mongo_db->where(array('_id' => $id, 'eliminado'=>false))->get('paquetes');
            

            return $res;
         
        //------------------------------------------    
    }





    /*
    *   buscarPlanesPaquetesTablas...
    */
    /*
    *   Nota importante: En vista a que segun documentación entregada por el sr Abrahans marzo 2019, se decidió modificar el funcionamiento del submodulo de membresia por segunda vez, la unica forma de obtener las horas pertenecientes al servicio HORAS DE COWORKING es a través de su campo descripción. Anteriormente que según mi criterio es como deberia funcionar la app, el campo de horas provenia del plan y no de un servicio... en la documentación no se explica de forma clara como relacionar una membresia con las horas de servicio por lo que decidi hacerlo a través de la descripción...
    */
    /*
    Nota importante. Como muestra de las deficiencias presentes en los requerimientos, abril 2019 se define agregar a la colección de servicios el campo tipo_servicios, y se usara el mismo como filtro para consultar las horas de coworking, a través del tipo de servicio horas de coworking
    */
    public function buscarPlanesPaquetesTabla($id_planes,$id_paquete){
        $id = new MongoDB\BSON\ObjectId($id_planes);
        
        #Consulto precio paquetes
        
        $id_paq = new MongoDB\BSON\ObjectId($id_paquete);
        $res_paquetes = $this->mongo_db->where(array('eliminado'=>false,'_id'=>$id_paq))->get('paquetes');
        $precio = number_format($res_paquetes[0]["precio"],2);
        $precio_oculto = str_replace(",","",$res_paquetes[0]["precio"]);

        $servicios = $res_paquetes[0]["servicios"];
        
        //-----------------------------------------------
        #Consulto el tipo de servicio horas de coworking
        $res_tipo_serv = $this->mongo_db->where(array('eliminado'=>false,'titulo'=>"HORAS DE COWORKING"))->get('tipo_servicios');
        $id_horas_coworking =  $res_tipo_serv[0]["_id"]->{'$id'};
        $res_serv_tipo = $this->mongo_db->where(array('eliminado'=>false,'tipo_servicio'=>$id_horas_coworking))->get('servicios');
        $horas_jornadas = "0";
        $servicio_arr = [];
        //------------------------------------------------
        $this->array_sort_by($servicios,"posicion",$order = SORT_ASC);
        #Recorro c/u de los servicios 
        foreach ($servicios as $clave => $valor) {
            /*var_dump($res_serv_tipo[0]["_id"]->{'$id'});
            var_dump($valor->id_servicios);
            echo "<br>";*/
            if($valor->eliminado==false){
                if($valor->id_servicios==$res_serv_tipo[0]["_id"]->{'$id'}){
                    $horas_jornadas = $valor->valor;
                }else if ($valor->id_servicios==$res_serv_tipo[1]["_id"]->{'$id'}){
                    $horas_jornadas = $valor->valor;
                }
                $id_servicios = new MongoDB\BSON\ObjectId($valor->id_servicios);
                $res_serv = $this->mongo_db->where(array('eliminado'=>false,'_id'=>$id_servicios))->get('servicios');
                $valores_servicios["id_servicios"] = $valor->id_servicios;
                $valores_servicios["id_servicios_mongo"] = $id_servicios;
                $valores_servicios["codigo_servicios"] = $res_serv[0]["cod_servicios"];
                $valores_servicios["titulo_servicios"] = $res_serv[0]["descripcion"];
                $valores_servicios["disponible"] = $valor->valor;
                $servicio_arr[] = $valores_servicios;
            }
            
        }
        //--
        
        #Planes:  

        $id_pl = new MongoDB\BSON\ObjectId($id_planes);
        $res_planes = $this->mongo_db->where(array('eliminado'=>false,'_id'=>$id_pl))->get('planes');
        $listado = [];
        $valores = [];

        #Consulto la vigencia

        $id_vigencia = new MongoDB\BSON\ObjectId($res_planes[0]["id_vigencia"]);

        $rs_vigencia = $this->mongo_db->where(array('eliminado'=>false,'_id'=>$id_vigencia))->get('vigencia');

        if($rs_vigencia[0]["descripcion"]=="Anual"){
            $vigencia = $res_planes[0]["tiempo_contrato"]." años";
        }else if($rs_vigencia[0]["descripcion"]=="Mensual"){
            $vigencia = $res_planes[0]["tiempo_contrato"]." meses";
        }

        
        $valores[]= array(
                            "servicios"=>$servicio_arr,
                            "valor"=>$precio,
                            "valor_oculto"=>$precio_oculto,
                            "vigencia" => $vigencia,
                            "horas_jornadas"=>$horas_jornadas
        );

        return $valores;
    }
    /*
    *   Consultar Monto
    */
    public function consultarMontoInscripcion(){
        $res_configuracion = $this->mongo_db->where(array('eliminado'=>false,))->get('mi_empresa');
        if(count($res_configuracion)>0){
            $monto_inscripcion = number_format($res_configuracion[0]["monto_inscripcion"],2);
            $monto_inscripcion_oculto = str_replace(",","",$monto_inscripcion);
        }else{
            $monto_inscripcion = "0,00";
            $monto_inscripcion_oculto = $monto_inscripcion;
        }
        $montos = array("monto_inscripcion"=>$monto_inscripcion,"monto_inscripcion_oculto"=>$monto_inscripcion_oculto);
        return $montos;
    }
    /*
    *   Ordenar array
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
    /***/
    /*
    *   ObtenerServicios
    */
    public function obtenerServicios($paquete){
        $listado = [];
        $servicios_n = [];
        $servicios_c = [];
        $id =  new MongoDB\BSON\ObjectId($paquete);
        $paquetes =  $this->mongo_db->where(array('_id'=>$id,'status'=>true,'eliminado'=>false))->get("paquetes");
        /*var_dump($planes);
        echo "</br>";
        var_dump($paquetes);
        die('');*/
        foreach ($paquetes as $clave => $valor) {
           $arreglo_servicios = $valor["servicios"]; 
           $this->array_sort_by($arreglo_servicios,"posicion",$order = SORT_ASC);
           foreach ($arreglo_servicios as $key_serv => $value_serv) {
                //---
                if($value_serv->eliminado==false){
                    //---
                    $id = new MongoDB\BSON\ObjectId($value_serv->id_servicios);
                    //Consulto servicios
                    //#Modificacion gsantucci 13-06-2019 realizada segun tarea 123, en la que se indica que la membresia debe almacenar servicios de tipo caracter no consumible para se mostrado ne pestaña de saldos.
                    //$servicios =  $this->mongo_db->where(array('_id'=>$id,'status'=>true,'eliminado'=>false,'tipo'=>'N'))->get("servicios");
                    $servicios =  $this->mongo_db->where(array('_id'=>$id,'eliminado'=>false))->get("servicios");
                    if(count($servicios)){
                       //Si el servicio es de tipo numerico 
                        if($servicios[0]["tipo"]=="N"){
                           $valores["servicios"] = (string)$servicios[0]["_id"]->{'$id'};
                           $valores["cantidad"] =  $value_serv->valor;
                           $valores["disponible"] = $value_serv->valor;
                           $valores["monto"] = number_format($servicios[0]["monto"],2);
                           $servicios_n[]=$valores; 
                        }else{
                            $valores2["servicios"] = (string)$servicios[0]["_id"]->{'$id'};
                            $valores2["valor"] =  $value_serv->valor;
                            $valores2["monto"] = number_format($servicios[0]["monto"],2); 
                            $servicios_c[]=$valores2; 
                        }
                        //---

                    }
                    //---
                }
                
                //---
            } 
            $listado = array("servicios_n"=>$servicios_n,"servicios_c"=>$servicios_c);
           //--
           //die('');

        }
        return $listado;
    }
    /*
    *   Consultar numero de membresia 
    */
    public function obtener_numero_cotizacion(){
         $rs_cotizacion = $this->mongo_db->limit(1)->order_by(array('_id' => 'DESC'))->get('cotizacion');

         if(count($rs_cotizacion)>0){
            $numero_cotizacion = (integer)$rs_cotizacion[0]["numero_cotizacion"]+1;
         }else{
            $numero_cotizacion = 1;
         }
         return $numero_cotizacion;
    }
    /*
    *   Registro de cotizacion
    */
    public function registrar_cotizacion($data){
        /***/
        $insertar1 = $this->mongo_db->insert("cotizacion", $data);
            echo json_encode("<span>La cotización se ha registrado exitosamente!</span>");
        /***/
    }
    /*
    *   Actualizar cotizaciones
    */
    public function actualizar_cotizacion($data){
        
        $fecha = new MongoDB\BSON\UTCDateTime();

        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));

        $id_cotizacion = new MongoDB\BSON\ObjectId($data["id_cotizacion"]);
        //--
        $res_cotizacion = $this->mongo_db->where(array('_id'=>$id_cotizacion,"eliminado"=>false))->set($data)->update("cotizacion");

        //var_dump($data);
        //var_dump($res_membresia);
        //die();    
        //Auditoria...
        $data_auditoria = array(
                                        'cod_user'=>$id_usuario,
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Modificar Cotizacion',
                                        'operacion'=>''
                                );
        $mod_auditoria = $this->mongo_db->where(array('_id'=>$id_cotizacion))->push('auditoria',$data_auditoria)->update("cotizacion");
        return true;
        //echo json_encode("<span>La cotización se ha editado exitosamente!</span>");
    }
    /*
    *   Buscar cotizacion
    */  
    public function buscar($id_cotizacion){
        $lisatdo = [];
        $listado2 = [];
        $id = new MongoDB\BSON\ObjectId($id_cotizacion);
        //--
        $res_cotizacion = $this->mongo_db->where(array('_id'=>$id,"eliminado"=>false))->get("cotizacion");
        foreach ($res_cotizacion as $key => $value) {
           $valor = $value; 
           $vector_auditoria = reset($valor["auditoria"]);
           $valor["fec_regins"] = $vector_auditoria->fecha->toDateTime();
           //--
           #Consulto el cliente...
           //nombre_prospecto
           $rfc = $valor["identificador_prospecto_cliente"];

           $id_cliente = new MongoDB\BSON\ObjectId($rfc);
            
           $res_cliente_pagador = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,'_id'=>$id_cliente))->get($this->tabla_clientePagador);
           
           //--Consulto contacto
           $id_contacto = new MongoDB\BSON\ObjectId($res_cliente_pagador[0]["id_contacto"]);
           $res_contacto = $this->mongo_db->where(array("_id"=>$id_contacto))->get($this->tabla_contacto);
           $valor["correo"] = $res_contacto[0]["correo_contacto"];

           //--Consulto datos personales 
           $id_datos_personales =  new MongoDB\BSON\ObjectId($res_cliente_pagador[0]["id_datos_personales"]);
            
           $res_datos_personales = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array("_id"=>$id_datos_personales))->get("datos_personales");

           //--------------------------------------------------------------------
           //$res_datos_personales = $this->mongo_db->where(array('eliminado'=>false,'rfc_datos_personales'=>$rfc))->get('datos_personales');
            foreach ($res_datos_personales as $clave_dt => $valor_dt) {
                //--
                $valores["id_datos_personales"] = (string)$valor_dt["_id"]->{'$id'};
                
                $valores["id_contacto"] = (string)$valor_dt["id_contacto"];
                $valores["nombre_datos_personales"] = $valor_dt["nombre_datos_personales"];

                (isset($valor_dt["apellido_p_datos_personales"]))? $valores["apellido_p_datos_personales"] = $valor_dt["apellido_p_datos_personales"]: $valores["apellido_p_datos_personales"] ="";

                (isset($valor_dt["apellido_m_datos_personales"]))? $valores["apellido_m_datos_personales"] = $valor_dt["apellido_m_datos_personales"]: $valores["apellido_m_datos_personales"] ="";
                
                $valor["nombre_prospecto"] =  $valores["nombre_datos_personales"]." ".$valores["apellido_p_datos_personales"]." ".$valores["apellido_m_datos_personales"];
            }    
           //--------------------------------------------------------------------
           //--
           $listado[] = $valor;
        }
        $listado2 = $listado;
        return $listado2;
        //--
    }
    /*
    *   Buscar Planes segun cotizacion
    */
    public function buscar_plan($id_plan){

        $id = new MongoDB\BSON\ObjectId($id_plan);

        $res_planes = $this->mongo_db->where(array('_id'=>$id,"eliminado"=>false))->get("planes");
        return $res_planes;
    }
    /*
    *   Buscar Planes segun cotizacion
    */
    public function buscar_servicios($id_plan,$servicios,$servicios_c){
        $arr = [];
        $cadena_servicios = "";
        $servi = "";
        #Recorro los servicios comunes
        foreach ($servicios as $clave_serv => $valor_serv) {
           $arr[]=$valor_serv;
        }
        #Recorro los servicios c
        foreach ($servicios_c as $clave_serv => $valor_serv) {
           $arr[]=$valor_serv;
        }
        #
        //$this->array_sort_by($arr,"posicion",$order = SORT_ASC);
        foreach ($arr as $key => $value) {
            $id_servicios = new MongoDB\BSON\ObjectId($value->servicios);
            $res_serv = $this->mongo_db->where(array('_id'=>$id_servicios))->get('servicios');
            if(isset($value->cantidad)){
                $cadena_servicios = $value->cantidad." ".$res_serv[0]["descripcion"]."<br>";
            }else{
                $cadena_servicios = $res_serv[0]["descripcion"]."<br>";
            }
            
            $servi = $servi."".$cadena_servicios;
        }
        return $servi;
    }
    /*
    *   Registrar pdf
    */
     public function registrar_pdf($data){
        //cotizacion_correo
        $fecha = new MongoDB\BSON\UTCDateTime();

        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));

        $id_cotizacion = new MongoDB\BSON\ObjectId($data["id_cotizacion"]);
        //---
        $insertar2 = $this->mongo_db->insert("cotizacion_correo", $data);

        //Registro el correo en cotizacion_correo
        if($insertar2){
            return true;
        }
        return null;
    }
    /*
    *   Cancelar cotizacion
    */
    public function cancelar($id,$condicion){
        $id = new MongoDB\BSON\ObjectId($id);
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        $fecha = new MongoDB\BSON\UTCDateTime();
      
        $datos = array(
                        'condicion'=>$condicion,
        );
        $modificar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update("cotizacion");
        //--Auditoria
        if($modificar){
            $data_auditoria = array(
                                        'cod_user'=>$id_usuario,
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Cancelar cotizacion',
                                        'operacion'=>''
                                    );
            $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update("cotizacion"); 
        }
        //------------------------------------------------------------
    }
     /*
    *   Aprobar cotizacion : Es una sobrecarga del procedimiento anterior, te preguntaras por qué?, la respuesta es muy sencilla, esto se debe a que la aprobación va relacionada con la cobranza, y como no esta del todo definido.... bueno porsia lo coloco en procesos separados....
    */
    public function aprobar($id,$condicion){
        $id = new MongoDB\BSON\ObjectId($id);
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        $fecha = new MongoDB\BSON\UTCDateTime();
      
        $datos = array(
                        'condicion'=>$condicion,
        );
        $modificar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update("cotizacion");
        //--Auditoria
        if($modificar){
            $data_auditoria = array(
                                        'cod_user'=>$id_usuario,
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Aprobar cotizacion',
                                        'operacion'=>''
                                    );
            $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update("cotizacion"); 
        }
        //------------------------------------------------------------
    }
    /*
    *   Obtener numero cobranza
    */
    public function obtener_numero_cobranza(){
        $rs_cobranzas = $this->mongo_db->limit(1)->order_by(array('_id' => 'DESC'))->get('recibos_cobranzas');

        if(count($rs_cobranzas)>0){
            $numero_cobranza = (integer)$rs_cobranzas[0]["numero_cobranza"]+1;
        }else{
            $numero_cobranza = 1;
        }
        return $numero_cobranza;
    } 
    

    /*
    *   Obtener monto total   
    */
    public function obtener_monto_total($id){

        $id_cotizacion = new MongoDB\BSON\ObjectId($id);

        $rs_cotizacion = $this->mongo_db->where(array('_id'=>$id_cotizacion))->get('cotizacion');
        
        if(count($rs_cotizacion)>0){
            $monto_total_cotizacion = (float)$rs_cotizacion[0]["monto_total"];
        }else{
            $monto_total_cotizacion = 0;
        }
        return $monto_total_cotizacion;
    }
    /*
    *   Generar recibo
    */
    public function generarRecibo($id){
        $id_cotizacion = new MongoDB\BSON\ObjectId($id);
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        $fecha = new MongoDB\BSON\UTCDateTime();
        #Armo los datos
        $numero_cobranza = $this->obtener_numero_cobranza();
        $numero_corrida = $this->obtener_numero_corrida();
        $numero_recibo = 1;
        $numero_secuencia = 1;
        $monto_total = $this->obtener_monto_total($id);
        #
        $datos = array(
                        'id_venta'=>$id,
                        'numero_corrida'=>$numero_corrida,
                        'numero_cobranza'=>$numero_cobranza,
                        'recibos'=>[
                                        array(
                                                "operacion"=>"",
                                                "numero_secuencia"=>$numero_secuencia,
                                                "numero_recibo"=>$numero_recibo,
                                                "mes"=>"1",
                                                "fecha"=>$fecha,
                                                "tipo_operacion"=>"C",
                                                "concepto"=>"AFILIACION",
                                                "fecha_movimiento"=>$fecha,
                                                "fecha_contable"=>$fecha,
                                                "cargo"=>$monto_total,
                                                "abono"=>"0",
                                                "saldo"=>$monto_total,
                                                "forma_pago"=>"",
                                                "banco_pago"=>"",
                                                "monto_pago"=>"",
                                                "numero_tarjeta"=>"",
                                                "cuenta"=>"",
                                                "file_comprobante"=>"",
                                                "pago"=>0,
                                                "tipo_registro"=>0,
                                                'auditoria' => [    array(
                                                                            "cod_user" => $id_usuario,
                                                                            "nomuser" => $this->session->userdata('nombre'),
                                                                            "fecha" => $fecha,
                                                                            "accion" => "Nuevo registro recibo",
                                                                            "operacion" => ""
                                                )]
                                        ),
                        ],
                        'eliminado'=>false,
                        'status'=>true,
                        'condicion'=>"COTIZACION",
                        'status_pago'=>0,
                        'auditoria' => [array(
                                                    "cod_user" => $id_usuario,
                                                    "nomuser" => $this->session->userdata('nombre'),
                                                    "fecha" => $fecha,
                                                    "accion" => "Nuevo registro recibo",
                                                    "operacion" => ""
                                                )]   
        );
        $insertar2 = $this->mongo_db->insert("recibos_cobranzas", $datos);

        //Registro el correo en cotizacion_correo
        if($insertar2){
            return true;
        }
        return null;
    }


    public function saveCobranza($datos){
        $insertar2 = $this->mongo_db->insert("recibos_cobranzas", $datos);
    }
    /*
    *   Consultar cotizacion por id
    */
    public function consultar_cotizacion($id){
        $id_cotizacion = new MongoDB\BSON\ObjectId($id);
        $resultados = $this->mongo_db->where(array('eliminado'=>false,'_id' => $id_cotizacion))->get("cotizacion");
        return $resultados;
    }
    /*
    *   Obtiene la fecha de los meses a generar
    */
    public function calcularFechaMes($id_cotizacion){
        $resultados = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false))->get("recibos_cobranzas");
        $recibos = $resultados[0]["recibos"];
        $ultimo_recibo = end($recibos);
        $ultima_fecha = $ultimo_recibo->fecha_movimiento->toDateTime();
        $ufecha = (array)$ultima_fecha;
        $super_fecha = explode(" ", (string)$ufecha["date"]);
        $nueva_fecha = date("d-m-Y",strtotime($super_fecha[0]."+ 1 month"));
        $n_fecha = new MongoDB\BSON\UTCDateTime(strtotime($nueva_fecha)*1000);
        return $n_fecha; 
    }
    /*
    *   Obtiene el numero de cobranza según el id de la cotizacion
    */
    public function obtener_numero_cobranzaById($id){
        $resultados = $this->mongo_db->where(array('eliminado'=>false,'id_venta' => $id))->get("recibos_cobranzas");
        $id_cobranza = (string)$resultados[0]["_id"]->{'$id'};
        $datos = array(
                        "id_cobranza"=>$id_cobranza,
                        "numero_cobranza"=>$resultados["0"]["numero_cobranza"]
                );
        return $datos;
    }
    /*
    *   Obtener numero de corrida
    */
    public function obtener_numero_corrida(){
        $resultados = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false))->get("recibos_cobranzas");
        if(count($resultados)>0)
            $numero_corrida = $resultados["0"]["numero_corrida"]+1;
        else
            $numero_corrida = 1;
        return $numero_corrida;
    }
    /*
    *   Obtiene el numero de recibo siguiente para esa cobranza
    */
    public function obtener_numero_reciboById($id){
        $resultados = $this->mongo_db->where(array('eliminado'=>false,'id_venta' => $id))->get("recibos_cobranzas");
        $recibos = end($resultados[0]["recibos"]);
        $numero_recibo = $recibos->numero_recibo+1;
        return $numero_recibo; 
    }
    /*
    *   Obtiene el numero de secuencia siguiente para esa cobranza
    */
    public function obtener_numero_secuenciaById($id){
        $resultados = $this->mongo_db->where(array('eliminado'=>false,'id_venta' => $id))->get("recibos_cobranzas");
        $recibos = end($resultados[0]["recibos"]);
        $numero_secuencia = $recibos->numero_secuencia+1;
        return $numero_secuencia; 
    }
    /*
    *   Obtiene el numero de recibo siguiente para esa cobranza
    */
    public function obtener_mes_reciboById($id){
        $resultados = $this->mongo_db->where(array('eliminado'=>false,'id_venta' => $id))->get("recibos_cobranzas");
        $recibos = end($resultados[0]["recibos"]);

        if($recibos->mes=="0"){
            $recibos_mes=1;
        }else{
            $recibos_mes = (integer)$recibos->mes+1;
        }
       
        return $recibos_mes; 
    }
    /*
    *   Registrar recibos....
    */
    public function registrarRecibos($id_cobranza,$data){
        $id =  new MongoDB\BSON\ObjectId($id_cobranza);
        /*var_dump($id_cobranza);echo "<br>";
        var_dump($data);
        die('');*/
       
        $recibos_cobranza = $this->mongo_db->where(array('_id'=>$id))->push('recibos',$data)->update('recibos_cobranzas');

        $res_miempresa = $this->mongo_db->get('mi_empresa');

        if($recibos_cobranza){
            $datos = array(
                        "status_pago"=>0
                  );  
            //--
            $mod_comision = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update('recibos_cobranzas');

            return true;

        }else{
           return false;
        }
    }



    public function getDataServicios($id_servicios)
    {
        $id = new MongoDB\BSON\ObjectId($id_servicios);
        $resultados = $this->mongo_db->where(array('eliminado'=>false,'_id' => $id))->get("servicios");
       
        return $resultados;
    }
}

/* End of file Cotizacion_model.php */
/* Location: ./application/models/Cotizacion_model.php */
?>
