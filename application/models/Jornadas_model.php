<?php 

if (!defined('BASEPATH')) exit ('No direct script access allowed');

Class Jornadas_model extends CI_Model
{

    private $tabla_clientePagador = "cliente_pagador";
    private $tabla_lval       = "lval";
    private $tabla_cuenta_clientePa = "cuenta_cliente";
    private $tabla_repLegal     = "repLegal_cliente_pagador";
    private $tabla_datosPersonales  = "datos_personales";
    private $tabla_contacto     = "contacto";
   
   
    /*
    *   Meses en espayol
    */
    public function meses_en_espayol($mes){
        switch ($mes) {
            case 'January':
                $mes2 = 'Ene';
                break;
            case 'February':
                $mes2 = 'Febr';
                break;
            case 'March':
                $mes2 = 'Mar';
                break;
            case 'April':
                $mes2 = 'Abr';
                break; 
            case 'May':
                $mes2 = 'May';
                break; 
            case 'June':
                $mes2 = 'Jun';
                break;
            case 'July':
                $mes2 = 'Jul';
                break;
            case 'August':
                $mes2 = 'Ago';
                break;
            case 'September':
                $mes2 = 'Sep';
                break;
            case 'October':
                $mes2 = 'Oct';
                break;
            case 'November':
                $mes2 = 'Nov';
                break;
            case 'December':
                $mes2 = 'Dic';
                break;
            //------------------------    
            case 1:
                $mes2 = 'Ene';
                break;
            case 2:
                $mes2 = 'Febr';
                break;
            case 3:
                $mes2 = 'Mar';
                break;
            case 4:
                $mes2 = 'Abr';
                break; 
            case 5:
                $mes2 = 'May';
                break; 
            case 6:
                $mes2 = 'Jun';
                break;
            case 7:
                $mes2 = 'Jul';
                break;
            case 8:
                $mes2 = 'Ago';
                break;
            case 9:
                $mes2 = 'Sep';
                break;
            case 10:
                $mes2 = 'Oct';
                break;
            case 11:
                $mes2 = 'Nov';
                break;
            case 12:
                $mes2 = 'Dic';
                break;                                    
            default:
                # code...
                break;
        }
        return $mes2;
    }
    /*
    *   Meses en numeros
    */
     public function meses_en_numeros($mes){
        switch ($mes) {
            case 'Ene':
                $mes2 = '01';
                break;
            case 'Febr':
                $mes2 = '02';
                break;
            case 'Mar':
                $mes2 = '03';
                break;
            case 'Abr':
                $mes2 = '04';
                break; 
            case 'May':
                $mes2 = '05';
                break; 
            case 'Jun':
                $mes2 = '06';
                break;
            case 'Jul':
                $mes2 = '07';
                break;
            case 'Ago':
                $mes2 = '08';
                break;
            case 'Sep':
                $mes2 = '09';
                break;
            case 'Oct':
                $mes2 = '10';
                break;
            case 'Nov':
                $mes2 = '11';
                break;
            case 'Dic':
                $mes2 = '12';
                break;         
            default:
                # code...
                break;
        }
        return $mes2;
    }
    
    /*
    *   Registro de jornada
    */
    public function registrar_jornadas($data){
        /***/
        $id = new MongoDB\BSON\ObjectId($data["id_membresia"]);
        
        $result =  $this->mongo_db->where(array("eliminado"=>false,"_id"=>$id))->get("membresia");
        if($result){
            $fecha_inicio = $result[0]["fecha_inicio"]->toDateTime();
            $fecha_ini = $fecha_inicio->format('Y-m-d');
            
            $fecha_fin = $result[0]["fecha_fin"]->toDateTime();
            $fecha_fini = $fecha_fin->format('Y-m-d');
            
            $fecha_actual = date('Y-m-d');
            //var_dump($fecha_fini>=$fecha_actual);die('');
            //Si la fecha actual esta dentro de los tiempos de la membresia...
            if(($fecha_ini<=$fecha_actual)&&($fecha_fini>=$fecha_actual)){
                $rs_membresia = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array("eliminado"=>false,"id_membresia"=>$data["id_membresia"],'fecha_hora_fin'=>'Sin salir'))->get("jornadas");
                //valido que no haya entrado esa membresia...
                //var_dump($rs_membresia);die('');
                if(count($rs_membresia) == 0){

                    $insertar1 = $this->mongo_db->insert("jornadas", $data);
                    echo json_encode("<span>La jornada se ha registrado exitosamente!</span>");
                }else{
                    //$fecha_hora_inicio = $rs_membresia[0]["fecha_hora_inicio"]->toDateTime();
                    //$fecha_hora_ini = $fecha_hora_inicio->format('Y-m-d');

                    $fecha_hora_ini = date("Y-m-d",$rs_membresia[0]["fecha_hora_inicio"]);
                    //var_dump($fecha_actual."==".$fecha_hora_ini);die('');
                    if( $fecha_actual==$fecha_hora_ini){
                        echo "<span>¡Ya se encuentra registrada un ingreso de jornada para ese usuario en este dia!</span>";
                    }else{//Si existe pero para otro dia igual guardo...
                        $insertar1 = $this->mongo_db->insert("jornadas", $data);
                        echo json_encode("<span>La jornada se ha registrado exitosamente!</span>");
                    }
                }
            }else{
                echo "<span>¡Su membresía a expirado debe renovarla!</span>";
            }
        }else{
             echo "<span>¡No se encuentran datos asociados a esta membresia!</span>";
        }
        
        /***/
    }
    /*
    *   Actualizacion de membresia
    */
    public function actualizar_membresia($data){
        
        $fecha = new MongoDB\BSON\UTCDateTime();

        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));

        $id_membresia = new MongoDB\BSON\ObjectId($data["id_membresia"]);
        //--
        $res_membresia = $this->mongo_db->limit(1)->where(array('_id'=>$id_membresia,"eliminado"=>false))->set($data)->update("membresia");
        /*var_dump($data);
        var_dump($res_membresia);
        die();    */
        //Auditoria...
        $data_auditoria = array(
                                        'cod_user'=>$id_usuario,
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Modificar membresia',
                                        'operacion'=>''
                                );
        $mod_auditoria = $this->mongo_db->where(array('_id'=>$id_membresia))->push('auditoria',$data_auditoria)->update("membresia");
        echo json_encode("<span>La membresia se ha editado exitosamente!</span>");
    }    
    /*
    *   LIstado de jornadas
    */
    public function listado_jornadas(){
        $listado = [];
        #Recorro la jornada....
        $resultados = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false))->get("jornadas");
        foreach ($resultados as $clave => $valor) {
            
            //--
            #Verifico que la jornada sea del dia de hoy
            //$fecha_hora_inicio = $valor["fecha_hora_inicio"]->toDateTime();
            //$fecha_hora_ini = $fecha_hora_inicio->format('Y-m-d');
            $fecha_hora_ini = new DateTime(date("Y-m-d",$valor["fecha_hora_inicio"]));
            $fecha_actual = new DateTime(date('Y-m-d'));
            //Solo se muestran las jornadas de hoy...
            if($fecha_hora_ini==$fecha_actual){
                $valores = $valor;
                $valores["id_jornada"] = (string)$valor["_id"]->{'$id'};
                $valores["id_membresia"] = $valor["id_membresia"];
                $id_membresia = new MongoDB\BSON\ObjectId($valores["id_membresia"]);
                #Recorro la membresia....
                $res_membresia = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,'_id'=>$id_membresia))->get("membresia");
                    //-------------------------------------------------------------
                    //$valores["id_grupo_empresarial"] =  $res_membresia[0]["id_grupo_empresarial"];
                    $valores["serial_acceso"] = $res_membresia[0]["serial_acceso"];
                    $valores["identificador_prospecto_cliente"] = $res_membresia[0]["identificador_prospecto_cliente"];
                    $valores["tipo_persona"] = $res_membresia[0]["tipo_persona"];
                    $valores["dia_ingreso"] = "";
                    $valores["hora_ingreso"] = "";
                    #Consulto datos personales
                    $rfc = $res_membresia[0]["identificador_prospecto_cliente"];
                    $res_dt = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,"rfc_datos_personales"=>$rfc))->get("datos_personales");
                    
                    $valores["nombre_datos_personales_cliente"] = $res_dt[0]["nombre_datos_personales"];
                    
                    if(isset($res_dt[0]["apellido_p_datos_personales"])){
                        $valores["nombre_datos_personales_cliente"].=" ".$res_dt[0]["apellido_p_datos_personales"];
                    }

                    if(isset($res_dt[0]["apellido_m_datos_personales"])){
                        $valores["nombre_datos_personales_cliente"].= " ".$res_dt[0]["apellido_m_datos_personales"];
                    }

                    #Consulto planes
                    $id_planes = new MongoDB\BSON\ObjectId($res_membresia[0]["plan"]);
                    $res_planes = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array("_id"=>$id_planes))->get("planes");
                    //'eliminado'=>false
                    $valores["planes"] = $res_planes[0]["titulo"]." ".$res_planes[0]["descripcion"];
                    
                    #Consulto usuario
                    $id = new MongoDB\BSON\ObjectId($valor["auditoria"][0]->cod_user);
                    $res_us = $this->mongo_db->where(array('_id'=>$id))->get('usuario');
                    $vector_auditoria = end($valor["auditoria"]);
                    $valores["fec_regins"] = $vector_auditoria->fecha->toDateTime();
                    $valores["correo_usuario"] = $res_us[0]["correo_usuario"];
                    //--
                    //$fecha_inicio = $valor["fecha_hora_inicio"]->toDateTime();
                    $fecha_inicio = new DateTime(date("Y-m-d g:i a",$valor["fecha_hora_inicio"]));
                    $valores["dia_ingreso"] = $fecha_inicio;
                    $valores["hora_ingreso"] = $fecha_inicio;
                    //--
                    if($valor["fecha_hora_fin"]!='Sin salir'){
                        //$fecha_inicio = $valor["fecha_hora_fin"]->toDateTime();
                        $fecha_inicio = new DateTime(date("Y-m-d g:i a",$valor["fecha_hora_fin"]));
                        $valores["dia_salida"] = $fecha_inicio;
                        $valores["hora_salida"] = $fecha_inicio;
                    }else{
                        $valores["dia_salida"] = "Sin salir";
                        $valores["hora_salida"] = "Sin salir";
                    }
                   
                    //$vector_fecha_inicio = explode("-",$valor["fecha_hora_inicio"]->toDateTime());
                    
                    //$valores["dia_ingreso"] = $vector_fecha_inicio[2]."-".$vector_fecha_inicio[1]."-".$vector_fecha_inicio[0];

                    //-------------------------------------------------------------
                #            
                $listado[] = $valores;
            }
            //--

        }
        return $listado;
    }
    /*
    *   Consultar membresia
    */
    public function consultarNrenovacion($id_membresia){

        $id = new MongoDB\BSON\ObjectId($id_membresia);

        $resultados = $this->mongo_db->where(array('_id'=>$id))->get("membresia");

        if($resultados){
            $numeroRenovacion = $resultados[0]["numero_renovacion"];
            return $numeroRenovacion;
        }else{
            echo "<span>Ocurrió un error inesperado</span>";die('');
        }
    }
    /*
    *   Listado de membresia
    */
     public function listado_membresia(){
        $listado = [];
        $resultados = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,'status'=>true))->get("membresia");
        foreach ($resultados as $clave => $valor) {
            if($valor["tipo_persona"]=="fisica" || $valor["tipo_persona"]=="FISICA"){
                //---
                $valores = $valor;
                $valores["id_membresia"] = (string)$valor["_id"]->{'$id'};
                #Consulto datos personales
                $rfc = $valor["identificador_prospecto_cliente"];
                $res_dt = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,"rfc_datos_personales"=>$rfc))->get("datos_personales");
                $nombre_datos_personales = $res_dt[0]["nombre_datos_personales"];

                if(isset($res_dt[0]["apellido_p_datos_personales"])){
                    $nombre_datos_personales.=" ".$res_dt[0]["apellido_p_datos_personales"];
                }

                if(isset($res_dt[0]["apellido_m_datos_personales"])){
                    $nombre_datos_personales.=" ".$res_dt[0]["apellido_m_datos_personales"];
                }

                $valores["nombre_datos_personales"] = $rfc."-".$nombre_datos_personales."(".$valores["serial_acceso"].")";
                //---
                //Consultando al cliente pagador para obtener su imagen
                $valores["id_datos_personales"] = (string)$res_dt[0]["_id"]->{'$id'};
                $res_cliente_pagador = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,'id_datos_personales'=>$valores["id_datos_personales"]))->get($this->tabla_clientePagador);
                #Obtengo la imagen del cliente 
                (isset($res_cliente_pagador[0]["imagenCliente"]))? $valores["imagenCliente"] = $res_cliente_pagador[0]["imagenCliente"]:$valores["imagenCliente"] = "default-img.png";
                //---
                //---
                $listado[] = $valores;
            }
           
            #Consulto planes
            /*$id_planes = new MongoDB\BSON\ObjectId($valor["plan"]);
            $res_planes = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,"_id"=>$id_planes))->get("planes");
            $valores["planes"] = $res_planes[0]["titulo"]." ".$res_planes[0]["descripcion"];
            #Consulto usuario
            $id = new MongoDB\BSON\ObjectId($valor["auditoria"][0]->cod_user);
            $res_us = $this->mongo_db->where(array('_id'=>$id))->get('usuario');
            $vector_auditoria = end($valor["auditoria"]);
            $valores["fec_regins"] = $vector_auditoria->fecha->toDateTime();
            $valores["correo_usuario"] = $res_us[0]["correo_usuario"];
            //--
            $vector_fecha_inicio = explode("-",$valor["fecha_inicio"]);
            
            $valores["fecha_inicio"] = $vector_fecha_inicio[2]."-".$vector_fecha_inicio[1]."-".$vector_fecha_inicio[0];

            $vector_fecha_fin = explode("-",$valor["fecha_fin"]);

            $valores["fecha_fin"] = $vector_fecha_fin[2]."-".$vector_fecha_fin[1]."-".$vector_fecha_fin[0];
            */
        }
        return $listado;
    }
    /*
    *   Listado de membresia segun el filtro obtengo todos sus datos
    */
    public function listado_membresia_filtro($id_membresia,$id_jornadas){
        $listado = [];
        $horas_jornadas = "0";
        $precio = "0,00"; 
        $id = new MongoDB\BSON\ObjectId($id_membresia);
        $resultados = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,'_id'=>$id))->get("membresia");
        //var_dump($resultados);die('');
        foreach ($resultados as $clave => $valor) {
            
            $valores = $valor;
            
            $valores["id_membresia"] = (string)$valor["_id"]->{'$id'};
            /*
            *   Obtengo los datos del cliente
            */
            $valores["identificador_prospecto_cliente"] = $valor["identificador_prospecto_cliente"];

            $rfc = $valor["identificador_prospecto_cliente"];

            $res_dt = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,"rfc_datos_personales"=>$rfc))->get("datos_personales");

            $valores["nombre_datos_personales_cliente"] = $res_dt[0]["nombre_datos_personales"];

            $valores["solo_nombre"] = $res_dt[0]["nombre_datos_personales"];

            if(isset($res_dt[0]["apellido_p_datos_personales"])){
                $valores["nombre_datos_personales_cliente"].=" ".$res_dt[0]["apellido_p_datos_personales"];
                $valores["solo_apellidos_paternos"] = $res_dt[0]["apellido_p_datos_personales"];
            }else{
                $valores["solo_apellidos_paternos"] = "";
            }

            if(isset($res_dt[0]["apellido_m_datos_personales"])){
                $valores["nombre_datos_personales_cliente"].=" ".$res_dt[0]["apellido_m_datos_personales"];
                $valores["solo_apellidos_maternos"] = $res_dt[0]["apellido_m_datos_personales"];
            }else{
                $valores["solo_apellidos_maternos"] = "";
            }
            //Consultando al cliente pagador para obtener su imagen
            $valores["id_datos_personales"] = (string)$res_dt[0]["_id"]->{'$id'};
            $res_cliente_pagador = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,'id_datos_personales'=>$valores["id_datos_personales"]))->get($this->tabla_clientePagador);
            #Obtengo la imagen del cliente 
            (isset($res_cliente_pagador[0]["imagenCliente"]))? $valores["imagenCliente"] = $res_cliente_pagador[0]["imagenCliente"]:$valores["imagenCliente"] = "default-img.png";
            //---
            /*
            *
            */
            #Consulto planes
            $id_planes = new MongoDB\BSON\ObjectId($valor["plan"]);
            //'eliminado'=>false,
            $res_planes = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array("_id"=>$id_planes))->get("planes");
            
            $valores["planes"] = $res_planes[0]["titulo"]." ".$res_planes[0]["descripcion"];
            
            if($res_planes[0]["jornadas_limitadas"]==true){
                $horas_jornadas = "Jornadas ilimitadas";
            }/*else{
                var_dump($res_planes[0]);die('');
                $horas_jornadas = $res_planes[0]["horas_jornadas"]; 
            }*/
            //---
            /*
            *   Nota importante: En vista a que segun documentación entregada por el sr Abrahans marzo 2019, se decidió modificar el funcionamiento del submodulo de membresia por segunda vez, la unica forma de obtener las horas pertenecientes al servicio HORAS DE COWORKING es a través de su campo descripción. Anteriormente que según mi criterio es como deberia funcionar la app, el campo de horas provenia del plan y no de un servicio... en la documentación no se explica de forma clara como relacionar una membresia con las horas de servicio por lo que decidi hacerlo a través de la descripción...
            */
            /*
            Nota importante. Como muestra de las deficiencias presentes en los requerimientos, abril 2019 se define agregar a la colección de servicios el campo tipo_servicios, y se usara el mismo como filtro para consultar las horas de coworking, a través del tipo de servicio horas de coworking
            */
            #Consulto el tipo de servicio horas de coworking
            $res_tipo_serv = $this->mongo_db->where(array('eliminado'=>false,'titulo'=>"HORAS DE COWORKING"))->get('tipo_servicios');
            $id_horas_coworking =  $res_tipo_serv[0]["_id"]->{'$id'};
            $res_serv = $this->mongo_db->where(array('eliminado'=>false,'tipo_servicio'=>$id_horas_coworking))->get('servicios');
            /*---------------------------------------------------------------------------*/
            $id_paquete =  $valor["paquete"];
            $id_paq = new MongoDB\BSON\ObjectId($id_paquete);
            $res_paquetes = $this->mongo_db->where(array('eliminado'=>false,'_id'=>$id_paq))->get('paquetes');
            //$precio = $res_paquetes[0]["precio"];
            $precio =  number_format($res_paquetes[0]["precio"],2);
           
            $servicios = $res_paquetes[0]["servicios"];
            #Recorro c/u de los servicios 
            foreach ($servicios as $clave_serv => $valor_serv) {

                /*$id_servicios = new MongoDB\BSON\ObjectId($valor_serv->id_servicios);
                $res_serv = $this->mongo_db->where(array('eliminado'=>false,'_id'=>$id_servicios,'descripcion'=>"HORAS DE COWORKING"))->get('servicios');
                if(count($res_serv)>0){
                    $horas_jornadas = $valor_serv->valor;
                }*/
                if($valor_serv->id_servicios==$res_serv[0]["_id"]->{'$id'}){
                    $horas_jornadas = $valor_serv->valor;
                }else if($valor_serv->id_servicios==$res_serv[1]["_id"]->{'$id'}){
                    $horas_jornadas = $valor_serv->valor;
                }
                /*----*/
            }
            //---
            /*$valores["plan_horas_jornadas"] = $horas_jornadas;
            
            $valores["plan_valor"] = $res_planes[0]["precio"];*/
            
            /*
            *   Cambiar horas y valor!
            */
            $valores["plan_horas_jornadas"] = $horas_jornadas;
            
            $valores["plan_valor"] = $precio;
            /*
            *
            */
            $valores["fecha_inicio"] = $valor["fecha_inicio"]->toDateTime();

            $valores["fecha_fin"] = $valor["fecha_fin"]->toDateTime();

           
            /*
            *   Consulto todas las jornadas asociadas a esa membresia
            */
            $horas_transcurridas = $this->calcular_horas_jornadas($id_membresia);
            if($id_jornadas!=""){
                $horas_transcurridas_individual = $this->calcular_horas_jornadas_individual($id_jornadas);
            }else{
                $horas_transcurridas_individual = "00:00:00";
            }
            
            //$horas_transcurridas = "00:00:00";
            /*
            *   Se debe cambiar esto ya que se debe calcular de otra forma segun cambio alcance señalado pro el Sr Abrahans Mayo-2013
            */
            if($horas_jornadas!="Jornadas ilimitadas"){
                /*$horas_disponibles_prev = strtotime ( '-'.$horas_jornadas.' hour' , strtotime ($horas_transcurridas) ); 
                $horas_disponibles = strftime("%H:%M:%S:", $horas_disponibles_prev);*/
                //------------------------------------------------------------------
                /*$fecha = new DateTime($horas_jornadas.":00:00");
                $fecha->modify('-'.$horas_transcurridas);
                $horas_disponibles =  $fecha->format('H:i:s');*/

                if($horas_transcurridas=="00:00:00"){
                    $horas_disponibles = $horas_jornadas;
                }else{
                    /*$f1 = new DateTime($horas_jornadas.":00:00");
                    $f2 = new DateTime($horas_transcurridas);
                    $d = $f1->diff($f2);
                    $horas_disponibles = $d->format('%H:%I:%S');*/
                    //---
                    /*$horas_disp = date_create($horas_transcurridas);
                    date_sub($horas_disp, date_interval_create_from_date_string($horas_jornadas.'hours'));
                    $horas_disponibles = date_format($horas_disp, 'H:i:s');*/
                    //---
                    /*$f1 = new DateTime($horas_transcurridas);
                    $horas_disp = $f1->modify("-".$horas_jornadas.' hours');
                    $horas_disponibles = $horas_disp->format('H:i:s');*/
                    //---
                    /*$vector_horas = explode(":",$horas_transcurridas);
                    $hora_sola_disp = (integer)$horas_jornadas-(integer)$vector_horas[0];
                    $horas_disponibles = $hora_sola_disp.":".$vector_horas[1].":".$vector_horas[2];*/
                    //--Restar fechas sin date time
                    $vector_horas = explode(":",$horas_transcurridas);
                    $horas_trans_segundos = $vector_horas[0]*3600;
                    $minutos_trans_segundos = $vector_horas[1]*60;
                    $segundos_trans = $vector_horas[2]+$minutos_trans_segundos+$horas_trans_segundos;
                    $segundos_disponibles= (integer)$horas_jornadas*3600;
                    $segundos_totales = $segundos_disponibles - $segundos_trans;
                    $horas_disponibles_prev = round($segundos_totales/3600,1);
                    $minutos_disp_en_horas = explode(".",$horas_disponibles_prev);
                    //var_dump($minutos_disp_en_horas);die('');
                    if(count($minutos_disp_en_horas)>1){
                        $super_min = "0.".$minutos_disp_en_horas[1];
                    }else{
                        $super_min = "0";
                    }
                    $min = (float)$super_min;
                    $minutos_disponibles = $min*60;
                    
                    if($minutos_disp_en_horas[0]<0){
                        $positivo = -1*($minutos_disp_en_horas[0]);
                        $horas_disponibles = "<label style='danger'>00:00:00</label> Se excedió en:".$positivo."Hrs ".$minutos_disponibles." Min";
                    }else{
                        $horas_disponibles = $minutos_disp_en_horas[0]."Hrs ".$minutos_disponibles." Min";
                    }
                    
                }

                //------------------------------------------------------------------
            }else{
                $horas_disponibles = "Jornadas ilimitadas";
            }
            /*
            *   Obtengo del paquete los servicios relacionados
            */
            if($id_jornadas!=""){
                //var_dump($valor["servicios"]);die('');
                $valores["servicios"] = $this->obtenerServicios($valor["servicios"],$valores["id_membresia"]);
                $valores["servicios_opcionales"] = $this->obtenerServiciosOpcionales($id_jornadas);
                $valores["arreglo_montos"] = $this->ObtenerMontosJornadas($id_jornadas);          
            }
            /*
            *
            */
            $valores["horas_transcurridas"]=$horas_transcurridas;
            //var_dump($valores["horas_transcurridas"]);
            $valores["horas_disponibles"]=$horas_disponibles;

            $valores["horas_transcurridas_x_jornada"] = $horas_transcurridas_individual;
            
            $fecha = new MongoDB\BSON\UTCDateTime();
            $valores["actual"]=$fecha->toDateTime();

            $listado[] = $valores;
        }
        //var_dump($listado);
        return $listado;
    }
    /*
    *   Listado planes
    */
    public function buscarPlanes($id_planes){
        $id = new MongoDB\BSON\ObjectId($id_planes);
        $res_planes = $this->mongo_db->where(array('eliminado'=>false,'_id'=>$id))->get('planes');
        $listado = [];
        $valores = [];

        foreach ($res_planes as $key => $value) {
            //-----------------------
            //--consulto la vigencia
            $rs_vigencia = $this->mongo_db->where(array('eliminado'=>false))->get('vigencia');
            
            if($rs_vigencia[0]["descripcion"]=="Anual"){
                $ayo_vigencia = (integer)date("Y")+1;
                $mes_vigencia = date("m");
            }else if($rs_vigencia[0]["descripcion"]=="Mensual"){
                if(date("m")=="12")
                    $mes_vigencia = 1;
                else
                    $mes_vigencia = (integer)date("m")+1; 
                $ayo_vigencia = date("Y");
            }
            $mes_inicio = $this->meses_en_espayol(date("m"));
            $fecha_inicio = date("d")." ".$mes_inicio." ".date("Y");
            $mes_vigencia_esp = $this->meses_en_espayol($mes_vigencia);
            $vigencia = (string)date("d")." ".(string)$mes_vigencia_esp." ".(string)$ayo_vigencia;
            if($value["jornadas_limitadas"]==true){
                $horas_jornadas = "Jornadas Ilimitadas";
            }else{
                $horas_jornadas = $value["horas_jornadas"]; 
            }
            //----------------------------------------------------
            // Recorrer jornadas...

            //----------------------------------------------------
            $valores[]= array(
                                "valor"=>$value["precio"],
                                "horas_jornadas" =>$horas_jornadas,
                                "inicio"=> $fecha_inicio,
                                "vigencia" => $vigencia,
                                "condicion" => $value["status"]
                    );
            //-----------------------
        
            //--
        }
        return $valores;
    }
    /*
    *   Obtengo los servicios relacionados a ese paquete/plan
    */
    public function obtenerServicios($servicios,$id_membresia){
        $listado = [];
        $numero = 0;
        $servicios2 = $servicios;

        foreach ($servicios2 as $clave => $valor) {
            $id_servicios = new MongoDB\BSON\ObjectId($valor->servicios);
            $rs_servicios = $this->mongo_db->where(array("eliminado"=>false,'_id'=>$id_servicios))->get('servicios');
            if($rs_servicios[0]["tipo"]=="N"){
                //--
                $consumido = ((integer)$valor->cantidad)-((integer)$valor->disponible);
                $valores["id_servicios"] = (string)$valor->servicios;
                $valores["codigo"] = $rs_servicios[0]["cod_servicios"];
                $valores["titulo"] = $rs_servicios[0]["descripcion"];
                $valores["categoria"] = $rs_servicios[0]["categoria"];
                $valores["cantidad"] = $valor->cantidad;
                $valores["consumido"] = $this->consultar_servicio_consumido($valor->servicios,$id_membresia);
                $valores["disponible"] = (integer)$valor->cantidad-(integer)$valores["consumido"];
                //$valores["costo"] = str_replace(",","",$rs_servicios[0]["monto"]);
                $valores["costo"] = str_replace(",","",$valor->monto);
                $listado[]=$valores;
                //--
            }
        }
        return $listado;   
    }
    /*
    *   Obtener servicios opcionales
    */
    public function obtenerServiciosOpcionales($id_jornadas){
        //--Consulto la jornada por id
        $listado = [];
        $id = new MongoDB\BSON\ObjectId($id_jornadas);
        $rs_jornadas = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,'_id'=>$id))->get("jornadas");
        $servicios_opcionales =  $rs_jornadas[0]["servicios"];
        foreach ($servicios_opcionales as $clave_serv => $valor_serv) {
            //--
            //Si el tipo es opcional...
            if($valor_serv->tipo=="opcional"){
                 $id_servicio = new MongoDB\BSON\ObjectId($valor_serv->id_servicio);
                $rs_servicios = $this->mongo_db->where(array("eliminado"=>false,'_id'=>$id_servicio))->get('servicios');
                //"status"=>true,
                $valores["codigo"] = $rs_servicios[0]["cod_servicios"];
                $valores["titulo"] = $rs_servicios[0]["descripcion"];
                $valores["cantidad"] = $valor_serv->cantidad;
                //$valores["costo"] = $valor_serv->monto_individual;
                $valores["costo"] = number_format($valor_serv->monto_individual,2);
                $valores["categoria"] = $rs_servicios[0]["categoria"];
                //$valores["total_servicio"] = $valor_serv->monto_total;
                $valores["total_servicio"] = number_format($valor_serv->monto_total,2);
                $valores["costo_sin_comas"] = str_replace(",","",$valor_serv->monto_individual);
                $valores["total_servicio_sin_coma"] = str_replace(",","",$valor_serv->monto_total);
                $listado[]=$valores;
            }
        }        
        return $listado;   
        //var_dump($servicios_opcionales);die('');
    }
    /*
    *   Obtener montos de jornadas
    */
    public function ObtenerMontosJornadas($id_jornadas){
        $id = new MongoDB\BSON\ObjectId($id_jornadas);
        $rs_jornadas = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,'_id'=>$id))->get("jornadas");

        $monto_pagar = $rs_jornadas[0]["monto_pagar"];
        $monto_total_recargo = $rs_jornadas[0]["monto_total_recargo"];
        $listado =  array(
                            //"monto_pagar"=>$monto_pagar,
                            "monto_pagar"=> number_format($monto_pagar,2),
                            //"monto_total_recargo"=>$monto_total_recargo,
                            "monto_total_recargo"=> number_format($monto_total_recargo,2),
                            "monto_pagar_sin_comas"=>str_replace(",","",$monto_pagar),
                            "monto_total_recargo_sin_comas"=> str_replace(",","",$monto_total_recargo)
        );
        return $listado;
    }
    /*
    *   Consultar servicios membresia
    */
    public function consultar_servicio_consumido($id_servicio,$id_membresia){
        $cantidad = 0;
        //Consulto las jornadas con esa membresia
        $rs_jornadas = $this->mongo_db->where(array("eliminado"=>false,"status"=>true,'id_membresia'=>$id_membresia))->get('jornadas');
        //Recorro las jornadas asociadas a esa membresia
        foreach ($rs_jornadas as $clave_jornadas => $valor_jornadas) {
            //Si tiene servicios
            //var_dump(count($valor_jornadas["servicios"]));die('');
            if(count($valor_jornadas["servicios"])>0){
                //Recorro los servicios
                foreach ($valor_jornadas["servicios"] as $clave_serv => $valor_serv) {
                    //Si el servicio es igual al servicio en cuestion
                    if(($id_servicio==$valor_serv->id_servicio)&&($valor_serv->tipo==="contratados")){
                        $cantidad = $cantidad+$valor_serv->cantidad;
                    }
                }
                //$cantidad = 0;
            }
            //var_dump($valor_jornadas["servicios"]);die('');
        }
        return $cantidad;

    }
    /*
    *   Listado de servicios recargos
    */
    public function listado_servicios_recargos(){
        $rs_servicios = $this->mongo_db->where(array("eliminado"=>false,"tipo"=>"N"))->get('servicios');
        //"status"=>true,
        foreach ($rs_servicios as $clave => $valor) {
           $valor["id_servicios"] =  (string)$valor["_id"]->{'$id'};
           if(isset($valor["monto"])){
                $valor["costo"] = str_replace(",","",$valor["monto"]);
           }else{
                $valor["costo"] = "0,00";
           }


           if ($valor["tipo_servicio"] == "5cacee422e7bddfe4c8b4569") {
               $listado[] = $valor; 
           }
           //$valor["costo"] = number_format($monto,2,'.','');
       }
       return $listado;
    }
    /*
    *   Marcar salida
    */
    public function marcar_salida($id_jornadas,$data,$id_membresia){

        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        
        $fecha = new MongoDB\BSON\UTCDateTime();
        
        $id = new MongoDB\BSON\ObjectId($id_jornadas);
        
        $this->generarRecibo($id_jornadas,$id_membresia);

        //--Reset por realizar varios update
        $res_miempresa = $this->mongo_db->get('mi_empresa');

        //--
        $rs_salida = $this->mongo_db->where(array("eliminado"=>false,"_id"=>$id))->set($data)->update('jornadas');
        
        //--Auditoria
        if($rs_salida){
            #Modificacion realziada 5 julio 2019 @santu1987
            #Luego de marcar salida se genera un registro de recibo
            #---
            $data_auditoria = array(
                                        'cod_user'=>$id_usuario,
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Marcar salida',
                                        'operacion'=>''
                                    );
            $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update("jornadas"); 
        }
       
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
    *   obtenerDatosRecibos
    */
    public function obtenerDatosRecibos($recibos){
        $ultimo_recibo = end($recibos);
        //--Obtengo el numero de recibo
        $numero_recibo = $ultimo_recibo->numero_recibo;
        //--Obtengo el numero de secuencia
        $this->array_sort_by($recibos,"numero_secuencia",$order = SORT_ASC);
        //--
        #Recorro los recibos para obtener el ultimo mes pago
        foreach ($recibos as $clave => $valor) {
            if($valor->pago==1){
                $mes = (integer)$valor->mes;
                $numero_secuencia = $valor->numero_secuencia;
            }
        }
        //--
        $ultimo_recibo = end($recibos);               
        $datos_recibos = array(
                                    "numero_recibo"=>$numero_recibo+1,
                                    "numero_secuencia"=>$numero_secuencia,
                                    "mes"=>$mes,
        );
        return $datos_recibos;
    }
    /*
    *   Se realiza el recibo de la jornada marcada
    */
    public function generarRecibo($id_jornadas,$id_membresia){
        //Paso 1: Obtener el id de cobranza
        #Consulto en mebresia el rfc del cliente
        $id_mem = new MongoDB\BSON\ObjectId($id_membresia);
        $res_membresia = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,'status'=>true,'_id'=>$id_mem))->get("membresia");
        $rfc_cliente = $res_membresia[0]["identificador_prospecto_cliente"];
        #Consulto en datos personales
        $res_dtp = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,'status'=>true,'rfc_datos_personales'=>$rfc_cliente))->get("datos_personales");
        $id_datos_personales = (string)$res_dtp[0]["_id"]->{'$id'};
        #Consulto al Cliente
        $res_cliente = $this->mongo_db->where(array('eliminado'=>false,'status'=>true,'id_datos_personales'=>$id_datos_personales))->get("cliente_pagador");
        $id_cliente = (string)$res_cliente[0]["_id"]->{'$id'};
        #Consulto la ultima cotizacion de este cliente
        $res_cotizacion = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,'status'=>true,'identificador_prospecto_cliente'=>$id_cliente))->get("cotizacion");
        $id_cotizacion = (string)$res_cotizacion[0]["_id"]->{'$id'};
        #Consulto la cobranza realizada a esa ultima cotizacion
        $res_cobranza = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,'status'=>true,'id_venta'=>$id_cotizacion))->get("recibos_cobranzas");

        //-----------------------------------------------------
        #Armo el recibo
        #Consulto el monto de jornadas....
        $id_jor = new MongoDB\BSON\ObjectId($id_jornadas);
        $rs_jornada = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,'status'=>true,'_id'=>$id_jor))->get("jornadas");
        
        $monto = $rs_jornada[0]["monto_pagar"];
        if($monto>0){
            //-----------------------------------------------
             $numero_cobranza =$res_cobranza[0]["numero_cobranza"];

            $id_cobranza = (string)$res_cobranza[0]["_id"]->{'$id'};

            $arr_recibos = $this->obtenerDatosRecibos($res_cobranza[0]["recibos"]);

            $numero_recibo = $arr_recibos["numero_recibo"];

            $numero_secuencia = $arr_recibos["numero_secuencia"];

            $mes = $arr_recibos["mes"];

            $fecha = new MongoDB\BSON\UTCDateTime();

                $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));

            $fecha2 = Date("d-m-Y");
            
            ///-------------------------------
            $data = array(
                      "operacion"=>"",
                      "numero_secuencia"=>$numero_secuencia,
                      "numero_recibo"=>$numero_recibo,
                      "mes"=>$mes,
                      "fecha"=>$fecha,
                      'tipo_operacion'=>'C',
                      'concepto'=>"RECARGOS ".$fecha2,
                      'fecha_movimiento'=>$fecha,
                      'fecha_contable'=>$fecha,
                      'cargo'=>$monto,
                      'abono'=>0,
                      'saldo'=>$monto,
                      'forma_pago'=>'',
                      'banco_pago'=>'',
                      'monto_pago'=>'',
                      'numero_tarjeta'=>'',
                      'cuenta'=>'',
                      'file_comprobante'=>'',
                      'pago'=>0,
                      'status' => true,
                      'tipo_registro'=>'1',
                      'eliminado' => false,
                      'auditoria' => [array(
                                                "cod_user" => $id_usuario,
                                                "nomuser" => $this->session->userdata('nombre'),
                                                "fecha" => $fecha,
                                                "accion" => "Nuevo registro recibo",
                                                "operacion" => ""
                                            )]
                );
            //---
            //var_dump($data);die('');
            $res_recibo = $this->registrarRecibos($id_cobranza,$data);
            if($res_recibo){
            return true;
            }else{
            return false;
            }
            //-----------------------------------------------
        }
        return true;
        //Fin de if $monto>0
       
        //-----------------------------------------------------
    }
    /*
    *   Registrar recibos....
    */
    public function registrarRecibos($id_cobranza,$data){
        $id =  new MongoDB\BSON\ObjectId($id_cobranza);

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
    /*
    *   calcular horas jornadas recorriendo todas las asociadas a la membresia...
    */
    public function calcular_horas_jornadas($id_membresia){
        
        $fecha = new MongoDB\BSON\UTCDateTime();
        
        $res_jornadas = $this->mongo_db->order_by(array('_id' => 'ASC'))->where(array('eliminado'=>false,'status'=>true,'id_membresia'=>$id_membresia))->get("jornadas");
        $cont = 0;
        $e = new DateTime('00:00');
        $f = clone $e;
        foreach ($res_jornadas as $clave_jornada => $valor_jornada) {
            //$fecha_inicio = $valor_jornada["fecha_hora_inicio"]->toDateTime();
            //$fecha_hora_ini = $fecha_inicio->format('Y-m-d H:i:s');
            $fecha_hora_ini = new DateTime(date("Y-m-d H:i:s",$valor_jornada["fecha_hora_inicio"]));
            if((isset($valor_jornada["fecha_hora_fin"]))&&($valor_jornada["fecha_hora_fin"]!="Sin salir")){
                //$fecha_fin = $valor_jornada["fecha_hora_fin"]->toDateTime();
                //$fecha_hora_fini = $fecha_fin->format('Y-m-d H:i:s');
                $fecha_hora_fini = new DateTime(date("Y-m-d H:i:s",$valor_jornada["fecha_hora_fin"]));
            }else{
                $fecha_fin = "";
                $fecha_hora_fini = new DateTime("");

            }
         
            /*$fecha1 = new DateTime($fecha_hora_ini);//fecha inicial
            $fecha2 = new DateTime($fecha_hora_fini);//fecha de cierre*/
            $fecha1 = $fecha_hora_ini;//fecha inicial
            $fecha2 = $fecha_hora_fini;//fecha de cierre
           
            //if(($fecha1!="")&&($fecha2!="")){

                $intervalo_siguiente=$fecha1->diff($fecha2);
                $e->add($intervalo_siguiente);
                $intervalo = $f->diff($e);
            //}
           
            //$intervalo=$fecha1->diff($fecha2);
            /*var_dump($fecha1);
            echo "</br>";
            var_dump($fecha2);
            echo "</br>";
            var_dump($intervalo_siguiente->format('%H:%i:%s'));
            echo "</br>";
            var_dump($intervalo->format('%H:%i:%s'));
            echo "</br></br>";*/
            $cont++;

        }
        if(isset($intervalo)){
            $horas_transcurridas = $intervalo->format('%H:%i:%s');
        }else{
            $horas_transcurridas = "00:00:00";
        }
        //--
        //var_dump($res_jornadas);die('');
        //--
       
        //var_dump($horas_transcurridas);
        return $horas_transcurridas;   
    }
    /*
    * Realiza el calculo por jornada
    */
     /*
    *   calcular horas jornadas
    */
    public function calcular_horas_jornadas_individual($id_jornada){
        
        $fecha = new MongoDB\BSON\UTCDateTime();
        $id = new MongoDB\BSON\ObjectId($id_jornada);
        $res_jornadas = $this->mongo_db->where(array('eliminado'=>false,'status'=>true,'_id'=>$id))->get("jornadas");
        $cont = 0;
        $e = new DateTime('00:00');
        $f = clone $e;
        //---
        //$fecha_inicio = $res_jornadas[0]["fecha_hora_inicio"]->toDateTime();
        //$fecha_hora_ini = $fecha_inicio->format('Y-m-d H:i:s');
        $fecha_hora_ini = new DateTime(date("Y-m-d H:i:s",$res_jornadas[0]["fecha_hora_inicio"]));
        
        if((isset($res_jornadas[0]["fecha_hora_fin"]))&&($res_jornadas[0]["fecha_hora_fin"]!="Sin salir")){
            //$fecha_fin = $res_jornadas[0]["fecha_hora_fin"]->toDateTime();
            //$fecha_hora_fini = $fecha_fin->format('Y-m-d H:i:s');
            $fecha_hora_fini = new DateTime(date("Y-m-d H:i:s",$res_jornadas[0]["fecha_hora_fin"]));
        }else{
            $fecha_fin = "";
            $fecha_hora_fini = new DateTime();
        }
         
        /*$fecha1 = new DateTime($fecha_hora_ini);//fecha inicial
        $fecha2 = new DateTime($fecha_hora_fini);//fecha de cierre*/
        $fecha1 = $fecha_hora_ini;//fecha inicial
        $fecha2 = $fecha_hora_fini;//fecha de cierre 
        $intervalo_siguiente=$fecha1->diff($fecha2);
        $e->add($intervalo_siguiente);
        $intervalo = $f->diff($e);
                //$intervalo=$fecha1->diff($fecha2);
        /*var_dump($fecha1);
        echo "</br>";
        var_dump($fecha2);
        echo "</br>";
        var_dump($intervalo_siguiente->format('%H:%i:%s'));
        echo "</br>";
        var_dump($intervalo->format('%H:%i:%s'));
        echo "</br></br>";*/
        
        if(isset($intervalo)){
            $horas_transcurridas = $intervalo->format('%H:%i:%s');
        }else{
            $horas_transcurridas = "00:00:00";
        }
        //--
        return $horas_transcurridas;   
    }
    /*
    *   Consultar serial existe
    */
    public function consultar_serial_existe($serial_acceso){
        $rs_membresia = $this->mongo_db->where(array("eliminado"=>false,"serial_acceso"=>$serial_acceso))->get("membresia"); 
        if(count($rs_membresia) == 0){
            //--Consulto cada registro de trabajador asociado
            $rs_membresia_todos = $this->mongo_db->where(array("eliminado"=>false))->get("membresia");  
            foreach ($rs_membresia_todos as $clave_membresia => $valor_membresia) {
                foreach ($valor_membresia["trabajadores"] as $clave_trabajador => $valor_trabajador) {
                    if($valor_trabajador->serial_acceso==$serial_acceso){
                        return true;
                    }
                }
            }
        }else{
            return true;
        }
        return false;
    }


    /*
    *   status
    */
    public function status($id, $status, $tabla){
        //-------------------------------------------------------------
        //Migracion Mongo DB
        $id = new MongoDB\BSON\ObjectId($id);
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        $fecha = new MongoDB\BSON\UTCDateTime();
        switch ($status) {
            case '1':
                $status2 = true;
                break;
            case '2':
                $status2 = false;
                break;
        }
        $datos = array(
                        'status'=>$status2,
        );
        $modificar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update($tabla);
        //--Auditoria
        if($modificar){
            $data_auditoria = array(
                                        'cod_user'=>$id_usuario,
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Modificar status',
                                        'operacion'=>''
                                    );
            $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update($tabla); 
        }
        //-------------------------------------------------------------
    }
    /*
    *   Status trabajador
    */
    public function status_datos_trabajador($id,$status){
        //Migracion Mongo DB
        $serial = $id;
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        $fecha = new MongoDB\BSON\UTCDateTime();
        switch ($status) {
            case '1':
                $status2 = true;
                break;
            case '2':
                $status2 = false;
                break;
        }
        $datos = array(
                        'trabajadores.$.status'=>$status2,
        );
        $modificar = $this->mongo_db->where(array('trabajadores.serial_acceso'=>$serial))->set($datos)->update("membresia");
        //--Auditoria
        if($modificar){
            $data_auditoria = array(
                                        'cod_user'=>$id_usuario,
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Modificar status',
                                        'operacion'=>''
                                    );
            $mod_auditoria = $this->mongo_db->where(array('trabajadores.serial_acceso'=>$serial))->push('trabajadores.$.auditoria',$data_auditoria)->update("membresia"); 
        }
        //-------------------------------------------------------------
    }
    /*
    *   Status Multiple trabajador
    */
    public function status_multiple_datos_trabajador($id,$status){
        //Migracion Mongo DB
        $serial = $id;
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        $fecha = new MongoDB\BSON\UTCDateTime();

        $arreglo_id = explode(' ',$id);
        
        foreach ($arreglo_id as $valor) {
            
            $serial = $valor;

            switch ($status) {
                case '1':
                    $status2 = true;
                    break;
                case '2':
                    $status2 = false;
                    break;
            }
            $datos = array(
                            'trabajadores.$.status'=>$status2,
            );
            $modificar = $this->mongo_db->where(array('trabajadores.serial_acceso'=>$serial))->set($datos)->update("membresia");
            //--Auditoria
            if($modificar){
                $data_auditoria = array(
                                            'cod_user'=>$id_usuario,
                                            'nom_user'=>$this->session->userdata('nombre'),
                                            'fecha'=>$fecha,
                                            'accion'=>'Modificar status',
                                            'operacion'=>''
                                        );
                $mod_auditoria = $this->mongo_db->where(array('trabajadores.serial_acceso'=>$serial))->push('trabajadores.$.auditoria',$data_auditoria)->update("membresia"); 
            }
        }    
        //-------------------------------------------------------------
    }
    /*
    *   Eliminar membresia
    */
    public function eliminar ($id, $tipo){
        switch ($tipo){
            case 'membresia':
                    //-----------------------------------------------------------------------------
                    $id = new MongoDB\BSON\ObjectId($id);

                    $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
                    
                    $fecha = new MongoDB\BSON\UTCDateTime();
                    
                    $datos = array(
                                    'eliminado'=>true,
                                    );

                    $eliminar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update("membresia");

                    //--Auditoria
                    if($eliminar){
                        $data_auditoria = array(
                                                    'cod_user'=>$id_usuario,
                                                    'nom_user'=>$this->session->userdata('nombre'),
                                                    'fecha'=>$fecha,
                                                    'accion'=>'Eliminar membresia',
                                                    'operacion'=>''
                                                );
                        $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update("membresia"); 
                        echo json_encode("<span>La membresia se ha eliminado exitosamente!</span>"); 
                    }    
                    //----------------------------------------------------------------------------
              break;
            case 'datos_trabajadores':
                //-----------------------------------------------------------------------------
                $serial = $id;

                $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
                
                $fecha = new MongoDB\BSON\UTCDateTime();
                
                $datos = array(
                                'trabajadores.$.eliminado'=>true,
                                );

                $eliminar = $this->mongo_db->where(array('trabajadores.serial_acceso'=>$serial))->set($datos)->update("membresia");
                //--Auditoria
                if($eliminar){
                    $data_auditoria = array(
                                                'cod_user'=>$id_usuario,
                                                'nom_user'=>$this->session->userdata('nombre'),
                                                'fecha'=>$fecha,
                                                'accion'=>'Eliminar membresia',
                                                'operacion'=>''
                                            );
                    $mod_auditoria = $this->mongo_db->where(array('trabajadores.serial_acceso'=>$serial))->push('trabajadores.$.auditoria',$data_auditoria)->update("membresia"); 

                    echo json_encode("<span>La membresia se ha eliminado exitosamente!</span>"); 
                }    
                //----------------------------------------------------------------------------
            break;
        }
    }      
    /*
    *   Eliminar Multiple
    */    
    public function eliminar_multiple($id_membresia){
        //--------------------------------------------------------------------------------------
        //MIGRACION MONGO DB
        $eliminados=0;
        $noEliminados=0;
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        $fecha = new MongoDB\BSON\UTCDateTime();
        foreach($id_membresia as $membresia){
        //---------------------------------------------------------------------------------
        //--Migracion Mongo DB
            $id = new MongoDB\BSON\ObjectId($membresia);
            $datos = $data=array(
                                    'eliminado'=>true,
            );
            $eliminar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update("membresia");
            //--Auditoria
            if($eliminar){
                $eliminados++;
                $data_auditoria = array(
                                            'cod_user'=>$id_usuario,
                                            'nom_user'=>$this->session->userdata('nombre'),
                                            'fecha'=>$fecha,
                                            'accion'=>'Eliminar membresia',
                                            'operacion'=>''
                                        );
                $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update("membresia");
            }else{
                $noEliminados++;
            }   
            //----------------------------------------------------------------------------------    

        }
        echo json_encode("<span>Registros eliminados: ".$eliminados."</span><br><span>Registros no eliminados (porque tienen dependencia en otras tablas): ".$noEliminados);
        //----------------------------------------------------------------------------
    }
    /*
    *   Eliminar multiple datos trabajador
    */
    public function eliminar_multiple_datos_trabajador($id){
        //--------------------------------------------------------------------------------------
        //MIGRACION MONGO DB
        $eliminados=0;
        $noEliminados=0;
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        $fecha = new MongoDB\BSON\UTCDateTime();
        foreach($id as $datos_trabajadores){
        //---------------------------------------------------------------------------------
        //--Migracion Mongo DB
            $serial = $datos_trabajadores;
            $datos = array(
                            'trabajadores.$.eliminado'=>true,
                            );
            $eliminar = $this->mongo_db->where(array('trabajadores.serial_acceso'=>$serial))->set($datos)->update("membresia");
            //--Auditoria
            if($eliminar){
                $eliminados++;
                $data_auditoria = array(
                                            'cod_user'=>$id_usuario,
                                            'nom_user'=>$this->session->userdata('nombre'),
                                            'fecha'=>$fecha,
                                            'accion'=>'Eliminar datos trabajadores',
                                            'operacion'=>''
                                        );
                $mod_auditoria = $this->mongo_db->where(array('trabajadores.serial_acceso'=>$serial))->push('trabajadores.$.auditoria',$data_auditoria)->update("membresia");
            }else{
                $noEliminados++;
            }   
            //----------------------------------------------------------------------------------    

        }
        echo json_encode("<span>Registros eliminados: ".$eliminados."</span><br><span>Registros no eliminados (porque tienen dependencia en otras tablas): ".$noEliminados);
        //----------------------------------------------------------------------------
    }
    /*
    *   Modificar Status
    */
    public function status_multiple_jornadas($id, $status){
        //---------------------------------------------------------------------------
        //--Migracion Mongo DB
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        
        $fecha = new MongoDB\BSON\UTCDateTime();

        $arreglo_id = explode(' ',$id);
        
        foreach ($arreglo_id as $valor) {
            $id = new MongoDB\BSON\ObjectId($valor);
            //var_dump($id);die('');
            
            switch ($status) {
                case '1':
                    $status2 = true;
                    break;
                case '2':
                    $status2 = false;
                    break;
            }
            $datos = $data=array(
                                    'status'=>$status2,
            );
            $modificar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update("jornadas");
            //var_dump($modificar);die('');
            //--Auditoria
            if($modificar){
                $data_auditoria = "";
                $data_auditoria = array(
                                            'cod_user'=>$id_usuario,
                                            'nom_user'=>$this->session->userdata('nombre'),
                                            'fecha'=>$fecha,
                                            'accion'=>'Modificar status jornadas',
                                            'operacion'=>''
                                        );
                $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update("jornadas"); 
            }
        }
        //---------------------------------------------------------------------------
    }
    /*
    *   Actualizar servicios en jornadas
    */
    public function actualizar_servicios_jornadas($where_array,$data){
        $fecha = new MongoDB\BSON\UTCDateTime();

        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        //--
        $res_jornadas = $this->mongo_db->where($where_array)->push('servicios',$data)->update("jornadas");   
        //var_dump($res_jornadas);die(''); 
        //Auditoria...
        /*$data_auditoria = array(
                                        'cod_user'=>$id_usuario,
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Modificar trabajador membresia ',
                                        'operacion'=>''
                                );
        $mod_auditoria = $this->mongo_db->where($where_array)->push('servicios.$.auditoria',$data_auditoria)->update("jornadas");*/
    }
    public function actualizar_montos($where_array_montos,$data_montos){
        $fecha = new MongoDB\BSON\UTCDateTime();

        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        //--
        $res_jornadas = $this->mongo_db->where($where_array_montos)->set($data_montos)->update("jornadas");   
        $this->mongo_db->_clear();
        //var_dump($res_jornadas);die(''); 
        //Auditoria...
        /*$data_auditoria = array(
                                        'cod_user'=>$id_usuario,
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Modificar jornadas',
                                        'operacion'=>''
                                );
        $mod_auditoria = $this->mongo_db->where($where_array_montos)->push('auditoria',$data_auditoria)->update("jornadas");*/
    }
     /*
    }
    *   Actualizar servicios en membresia
    */
    public function actualizar_servicios_membresia($where_array,$data){
        $fecha = new MongoDB\BSON\UTCDateTime();

        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        //--
        $res_jornadas = $this->mongo_db->get("membresia");   

    
        //Auditoria...
        $data_auditoria = array(
                                        'cod_user'=>$id_usuario,
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Modificar servicio membresia ',
                                        'operacion'=>''
                                );
        $mod_auditoria = $this->mongo_db->where($where_array)->push('servicios.$.auditoria',$data_auditoria)->update("membresia");
    }
    //-----------------------------------------------------------------------------------
}