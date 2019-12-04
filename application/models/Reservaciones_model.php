<?php 

if (!defined('BASEPATH')) exit ('No direct script access allowed');

Class Reservaciones_model extends CI_Model
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
    *   obtener_numero_reservacion
    */
    public function obtener_numero_reservacion(){
        $rs_reservaciones = $this->mongo_db->limit(1)->order_by(array('_id' => 'DESC'))->get('reservaciones');

        if(count($rs_reservaciones)>0){
            $numero_reservaciones = (integer)$rs_reservaciones[0]["n_reservaciones"]+1;
        }else{
            $numero_reservaciones = 1;
        }
        return $numero_reservaciones;
    }
    /*
    *   Validar fecha ingreso
    */
    public function validar_fecha_ingreso($id_reservaciones,$fecha){
        $id = new MongoDB\BSON\ObjectId($id_reservaciones);
        $rs_reservaciones = $this->mongo_db->where_lte('hora_inicio', $fecha)->where(array("eliminado"=>false,"_id"=>$id))->get('reservaciones');
        //var_dump($fecha);
        //var_dump($rs_reservaciones);die('');
        return count($rs_reservaciones);
    }
     /*
    *   Validar fecha ingreso con fecha fin
    */
    public function validar_fecha_ingreso_fin($id_reservaciones,$fecha){
        $id = new MongoDB\BSON\ObjectId($id_reservaciones);
        $rs_reservaciones = $this->mongo_db->where_lte('hora_fin', $fecha)->where(array("eliminado"=>false,"_id"=>$id))->get('reservaciones');
        /*var_dump($fecha);
        var_dump($rs_reservaciones);
        die('');*/
        return count($rs_reservaciones);
    }
    /*
    *   Registro de reservaciones
    */
    public function registrar_reservaciones($data){
        /***/
        $res_operaciones = $this->mongo_db->where(array("eliminado"=>false))->get("configuracion_reservaciones");
        #Fecha de la reservacion
        $fecha_reservacion = $data["fecha"];
        #Verifico si la reservacion esta dentro de las horas de operaciones...
        $hora_inicio = $data["fecha_inicial_validacion"];
        $hora_fin = $data["fecha_final_validacion"];

        $hora_inicio_mongo = $data["hora_inicio"];
        $hora_fin_mongo = $data["hora_fin"];
        //
        $inicio_operaciones = strtotime($res_operaciones[0]["hora_inicio_operaciones"]);

        $fin_operaciones =  strtotime($res_operaciones[0]["hora_fin_operaciones"]);
    
        $hoy_date = strtotime(date("Y-m-d g:i a"));

        /*var_dump($hoy_date);
        var_dump($hora_inicio_mongo);
        var_dump($hora_inicio_mongo<$hoy_date);*/
        //die('');
        if($hora_inicio_mongo<$hoy_date){
             echo "<span>¡La hora de inicio no puede ser menor que la fecha/hora actual!</span>";die('');
        }
        /*var_dump($hora_inicio);
        var_dump($hora_fin);
        var_dump($inicio_operaciones);
        var_dump($fin_operaciones);
        var_dump($hora_inicio>$fin_operaciones);
        var_dump( $hora_inicio>$fin_operaciones); 
        die('');*/
    
        if(( $hora_inicio < $inicio_operaciones)||($hora_inicio>$fin_operaciones)){
             echo "<span>¡La hora de inicio se encuentra fuera del rango de apertura de operaciones!</span>";die('');
        }
        if(( $hora_fin < $inicio_operaciones)||($hora_fin>$fin_operaciones)){
             echo "<span>¡La hora final se encuentra fuera del rango de apertura de operaciones!</span>";die('');
        }
        #Verifico si el cliente ya cuenta con una reservacion en otra sala en ese horario
        $res_existe_reservacion_otra_sala = $this->mongo_db->where(array("eliminado"=>false,"status"=>true,"id_membresia"=>$data["id_membresia"]))->where('hora_inicio', $hora_inicio_mongo)->where('hora_fin', $hora_fin_mongo)->get("reservaciones");
        
        if(count($res_existe_reservacion_otra_sala)>0){
            echo "<span>¡validacion 0: El cliente ya realizó una reservación en ese horario!</span>";die('');
        }
        #Verifico si existe una reservacion con esa sala en ese rango de fecha...
        #Verificacion 1: Empiezo antes y termino despues- Si la hora de inicio db es mayor igual a la hora de inicio form  y hora fin db menor igual a la hora fin form
        $res_existe_reservacion = $this->mongo_db->where(array("eliminado"=>false,"status"=>true,'id_servicio_sala'=>$data["id_servicio_sala"]))->where_gte('hora_inicio', $hora_inicio_mongo)->where_lte('hora_fin', $hora_fin_mongo)->get("reservaciones");
        if(count($res_existe_reservacion)>0){
            echo "<span>¡validacion 1:La sala se encuentra reservada por otro cliente!</span>";die('');
        }
        //------------------------------------------------------------------------------------
        #Verificacion 2: Empiezo antes y termino antes- Si la hora inicio bd es mayor a hora inicio form , y la hora fin bd mayor a la hora fin...
        $res_existe_reservacion2 = $this->mongo_db->where(array("eliminado"=>false,"status"=>true,'id_servicio_sala'=>$data["id_servicio_sala"],"fecha"=>$fecha_reservacion))->where_gte('hora_inicio', $hora_inicio_mongo)->where_gte('hora_fin', $hora_fin_mongo)->get("reservaciones");

        if(count($res_existe_reservacion2)>0){
           //--Pruebas:
            /*echo "<br> Hora Inicio db";
            var_dump($res_existe_reservacion2[0]["hora_inicio"]);
            echo "<br> Hora Fin db";
            var_dump($res_existe_reservacion2[0]["hora_fin"]);
            echo "<br> Hora Inicio Mongo";
            var_dump($hora_inicio_mongo);
            echo "<br> Hora Fin Mongo";
            var_dump($hora_fin_mongo);
            die('');*/
            echo "<span>¡validacion 2 : La sala se encuentra reservada por otro cliente!</span>";die('');
        }
        //-----------------------------------------------------------------------------------
        #Verificacion 3: Inicio despues y termino antes -Si la hora inicio bd es menor o igual a la hora de inicio form y hora inicio  menor igual a alguna hora fin, la hora fin mayor a alguna hora fin
        $res_existe_reservacion3 = $this->mongo_db->where(array("eliminado"=>false,"status"=>true,'id_servicio_sala'=>$data["id_servicio_sala"]))->where_lte('hora_inicio', $hora_inicio_mongo)->where_gte('hora_fin', $hora_fin_mongo)->get("reservaciones");

        if(count($res_existe_reservacion3)>0){
            echo "<span>¡validacion 3: La sala se encuentra reservada por otro cliente!</span>";die('');
        }
        //---------------------------------------------------------------------------------
        #Verificacion 4: Si la hora inicio es menor a alguna hora inicio, y la hora fin bd menor a la hora fin form...
        $res_existe_reservacion4 = $this->mongo_db->where(array("eliminado"=>false,"status"=>true,'id_servicio_sala'=>$data["id_servicio_sala"]))->where_lte('hora_inicio', $hora_inicio_mongo)->where_gte('hora_fin', $hora_inicio_mongo)->where_lte('hora_fin', $hora_fin_mongo)->get("reservaciones");

        if(count($res_existe_reservacion4)>0){
            echo "<span>¡validacion 4: La sala se encuentra reservada por otro cliente!</span>";die('');
        }
      

        #Inserto
        $insertar1 = $this->mongo_db->insert("reservaciones", $data);
        echo json_encode("<span>La reservación se ha registrado exitosamente!</span>");
        /***/
        /*
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
                    $fecha_hora_inicio = $rs_membresia[0]["fecha_hora_inicio"]->toDateTime();
                    $fecha_hora_ini = $fecha_hora_inicio->format('Y-m-d');
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
        */
        /***/
    }
    /*
    *   Listado de membresia segun el filtro obtengo todos sus datos
    */
    public function listado_membresia_filtro($id_membresia){
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
            $listado[] = $valores;
        }
        //var_dump($listado);
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
    *   Listado de membresia
    */
     public function listado_membresia(){
        $listado = [];
        $resultados = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,'status'=>true,"cancelado"=>false))->get("membresia");
        foreach ($resultados as $clave => $valor) {
            if($valor["tipo_persona"]=="fisica"){
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
        }
        return $listado;
    } 
    /*
    *
    */
    public function listado_salas(){
        $listado = [];
        
        #Consulto el tipo de servicio...
        
        $res_tipo_servicio = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,'status'=>true,'titulo'=>"SALAS"))->get("tipo_servicios");
                
        $id_tipo_servicio_salas = $res_tipo_servicio[0]["_id"]->{'$id'};
        
        #Consulto los servicios...
        $resultados = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,'status'=>true,'tipo_servicio'=>$id_tipo_servicio_salas))->get("servicios");
        
        foreach ($resultados as $calves => $valor) {
            $valor["id_salas"] = (string)$valor["_id"]->{'$id'};
            $listado[] = $valor; 
        }
        
        return $listado;
    }  
    /*
    *   Funcion para realizar la busqueda de salas segun id
    */
    public function buscarSalas($sala){
        $listado = [];
        
        #Consulto el tipo de servicio...
        $id = new MongoDB\BSON\ObjectId($sala);
        //'eliminado'=>false,'status'=>true,
        $res_servicios = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array("_id"=>$id))->get("servicios");

        foreach ($res_servicios as $claves => $valor) {
            $valor["id_salas"] = (string)$valor["_id"]->{'$id'};
            $valor["monto"] =  number_format($valor["monto"],2);
            $listado[] = $valor; 
        }
        return $listado;
    }
    /*
    *   LIstado de jornadas
    */
    public function listado_reservaciones(){
        $listado = [];
        #Recorro la jornada....
        $resultados = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false))->get("reservaciones");

        foreach ($resultados as $clave => $valor) {
            
            //--
            #Verifico que la jornada sea del dia de hoy
            $fecha_hora_inicio = $valor["fecha"]->toDateTime();

            $fecha_hora_ini = $fecha_hora_inicio->format('Y-m-d');

            $fecha_actual = date('Y-m-d');

            //var_dump($fecha_actual);die('');
            //Solo se muestran las rservaciones de hoy...
            if($fecha_hora_ini==$fecha_actual){
                $valores = $valor;

                $valores["id_reservaciones"] = (string)$valor["_id"]->{'$id'};
                $valores["precio"] = number_format($valores["precio"],2);
                $valores["id_membresia"] = $valor["id_membresia"];
                $id_membresia = new MongoDB\BSON\ObjectId($valores["id_membresia"]);
                #Recorro la membresia....
                $res_membresia = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,'_id'=>$id_membresia))->get("membresia");
                    //-------------------------------------------------------------
                    //$valores["id_grupo_empresarial"] =  $res_membresia[0]["id_grupo_empresarial"];
                    $valores["n_membresia"] = $res_membresia[0]["n_membresia"];

                    $valores["identificador_prospecto_cliente"] = $res_membresia[0]["identificador_prospecto_cliente"];
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

                    #consulto la sala 
                    $id_sala = new MongoDB\BSON\ObjectId($valores["id_servicio_sala"]);
                    $res_sala = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,'_id'=>$id_sala))->get("servicios");
                    $valores["sala"] = $res_sala[0]["descripcion"];
                    #Consulto usuario
                    $id = new MongoDB\BSON\ObjectId($valor["auditoria"][0]->cod_user);
                    $res_us = $this->mongo_db->where(array('_id'=>$id))->get('usuario');
                    $vector_auditoria = reset($valor["auditoria"]);
                    $valores["fec_regins"] = $vector_auditoria->fecha->toDateTime();
                    $valores["correo_usuario"] = $res_us[0]["correo_usuario"];
                    //--
                    if($valores["hora_ingreso"]!=""){
                        //$fecha_inicio = $valor["hora_ingreso"]->toDateTime();
                        $fecha_inicio = new DateTime(date("Y-m-d g:i a",$valor["hora_ingreso"]));
                        $valores["hora_ingreso"] = $fecha_inicio;
                       
                    }else{
                        $valores["hora_ingreso"] = "";
                    }
                    //--Hora inicio
                    if($valores["hora_inicio"]!=""){
                        //$fecha_inicio_h = $valor["hora_inicio"]->toDateTime();
                        $fecha_inicio_h = new DateTime(date("Y-m-d g:i a",$valor["hora_inicio"]));
                        $valores["hora_inicio"] = $fecha_inicio_h;
                        
                    }else{
                        $valores["hora_inicio"] = "";
                    }
                    //--Hora fin 
                    if($valores["hora_fin"]!=""){
                        //$fecha_fin_h = $valor["hora_fin"]->toDateTime();
                        $fecha_fin_h = new DateTime(date("Y-m-d g:i a" ,$valor["hora_fin"]));
                        $valores["hora_fin"] = $fecha_fin_h;
                    }else{
                        $valores["hora_fin"] = "";
                    }
                    //--
                    if($valor["hora_salida"]!='Sin salir'){
                        //$fecha_fin = $valor["hora_salida"]->toDateTime();
                        $fecha_fin = new DateTime(date("Y-m-d g:i a",$valor["hora_salida"]));
                        $valores["hora_salida"] = $fecha_fin;
                    }else{
                        $valores["hora_salida"] = "Sin salir";
                    }
                    $valores["hora_liberada"] = $valores["hora_salida"];
                    $fecha_reservacion = $valor["fecha"]->toDateTime();
                    $valores["fecha_reservacion"] = $fecha_reservacion;
                    //------------------------------------------------------------
                    //$fecha1 = new DateTime($fecha_fin);//fecha inicial
                    //$fecha2 = new DateTime($fecha_inicio);//fecha de cierre
                    #Calculo de horas contratadas    
                    $intervalo =$fecha_fin_h->diff($fecha_inicio_h);
                    
                    if(isset($intervalo)){
                        $valores["horas_contratadas"] = $intervalo->format('%H:%i:%s');
                     }else{
                        $valores["horas_contratadas"] = "";
                     }
                    /*-----------------------------------------------------------------------------*/
                    #Calculo de horas consumidas
                    if($valores["condicion"]=="REGISTRADA"){
                        #Si la condicion es registrada: se resta la hora actual a la hora de ingreso
                        if($valores["hora_ingreso"]!=""){
                            $hoy = new DateTime("now");
                            $intervalo2 =$fecha_inicio->diff($hoy);
                            $intervaloConsmuidas = $intervalo2;
                            if(isset($intervalo2)){
                                $valores["horas_consumidas"] = $intervalo2->format('%H:%i:%s');
                            }else{
                                $valores["horas_consumidas"] = "";
                            }
                            #LAs horas consumidas 2 es la direfencia desde la hora de inicio hasta la hora actual....
                            //--Calculo la hora consumida con relacion a la hora de inicio
                            $intervalo9 =$fecha_inicio_h->diff($hoy);
                            $intervaloConsmuidas2 = $intervalo9;
                            if(isset($intervalo9)){
                                $valores["horas_consumidas2"] = $intervalo9->format('%H:%i:%s');
                            }else{
                                $valores["horas_consumidas2"] = "";
                            }
                            //----------
                        }else{
                            $valores["horas_consumidas"] = "";
                            $valores["horas_consumidas2"] = "";
                        } 
                    } if($valores["condicion"]=="LIBERADA"){
                        #Si la condicione s liberada: se resta la hora salida a la de ingreso
                        $intervalo3 =$fecha_fin->diff($fecha_inicio);
                        $intervaloConsmuidas = $intervalo3;

                        if(isset($intervalo3)){
                            $valores["horas_consumidas"] = $intervalo3->format('%H:%i:%s');
                        }else{
                            $valores["horas_consumidas"] = "";
                        }
                        #LAs horas consumidas 2 es la direfencia desde la hora de inicio hasta la hora actual....
                        //--Calculo la hora consumida con relacion a la hora de inicio
                        $intervalo10 =$fecha_fin->diff($fecha_inicio_h);
                        $intervaloConsmuidas2 = $intervalo10;
                        if(isset($intervalo10)){
                            $valores["horas_consumidas2"] = $intervalo10->format('%H:%i:%s');
                        }else{
                            $valores["horas_consumidas2"] = "";
                        }
                    }else if(($valores["condicion"]=="RESERVADA")||($valores["condicion"]=="CANCELADA")){
                        $valores["horas_consumidas"] = "";
                        $valores["horas_consumidas2"] = "";
                    }
                    //------------------------------------------------------------
                    #Horas por consumir
                    if(($valores["horas_contratadas"]!="")&&($valores["horas_consumidas2"]!="")){
                        //var_dump($intervalo<$intervaloConsmuidas);die('');

                        $horas_uno = new DateTime($valores["horas_contratadas"]);
                        $horas_dos = new DateTime($valores["horas_consumidas2"]);
                         #Verifico si se excede
                        if($horas_dos>$horas_uno){
                            $valores["horas_disponibles"] = "";
                        }else{
                            //----------------------------------------------------------------
                            $intervaloDisponibles = $horas_uno->diff($horas_dos);
                            $valores["horas_disponibles"] = $intervaloDisponibles->format('%H:%i:%s'); 
                            /*if($valores["horas_disponibles"]>$valores["horas_contratadas"]){
                                $valores["horas_disponibles"] = "";
                            }*/
                            //---------------------------------------------------------------
                        }
                        
                    }else{
                        $valores["horas_disponibles"] = "";
                    }
                    //------------------------------------------------------------
                    ///xxx

                    //-------------------------------------------------------------
                #            
                $listado[] = $valores;
            }
            //--

        }
        return $listado;
    }
   /*
    *   LIstado de jornadas Todas
    */
    public function listado_reservaciones_todas(){
        $listado = [];
        #Recorro la jornada....
        $resultados = $this->mongo_db->order_by(array('_id' => 'ASC'))->where(array('eliminado'=>false))->get("reservaciones");

        foreach ($resultados as $clave => $valor) {
            //--
            #Verifico que la jornada sea del dia de hoy
            $fecha_hora_inicio = $valor["fecha"]->toDateTime();

            $fecha_hora_ini = $fecha_hora_inicio->format('Y-m-d');

            $fecha_actual = date('Y-m-d');
            //Solo se muestran las rservaciones de hoy...
            $valores = $valor;
            $valores["precio"] = number_format($valores["precio"],2);
            $valores["id_reservaciones"] = (string)$valor["_id"]->{'$id'};
            $valores["id_membresia"] = $valor["id_membresia"];
            $id_membresia = new MongoDB\BSON\ObjectId($valores["id_membresia"]);
            #Recorro la membresia....
            $res_membresia = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,'_id'=>$id_membresia))->get("membresia");
            //-------------------------------------------------------------
            //$valores["id_grupo_empresarial"] =  $res_membresia[0]["id_grupo_empresarial"];
            $valores["n_membresia"] = $res_membresia[0]["n_membresia"];


            $valores["identificador_prospecto_cliente"] = $res_membresia[0]["identificador_prospecto_cliente"];
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

            #consulto la sala 
            $id_sala = new MongoDB\BSON\ObjectId($valores["id_servicio_sala"]);
            $res_sala = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,'_id'=>$id_sala))->get("servicios");
            $valores["sala"] = $res_sala[0]["descripcion"];
            #Consulto usuario
            $id = new MongoDB\BSON\ObjectId($valor["auditoria"][0]->cod_user);
            $res_us = $this->mongo_db->where(array('_id'=>$id))->get('usuario');
            $vector_auditoria = reset($valor["auditoria"]);
            $valores["fec_regins"] = $vector_auditoria->fecha->toDateTime();
            isset($res_us[0]["correo_usuario"])?$valores["correo_usuario"] = $res_us[0]["correo_usuario"]:$valores["correo_usuario"] ="";
            //--
            if($valores["hora_ingreso"]!=""){
                //$fecha_inicio = $valor["hora_ingreso"]->toDateTime();
                $fecha_inicio = new DateTime(date("Y-m-d g:i a",$valor["hora_ingreso"]));
                $valores["hora_ingreso"] = $fecha_inicio;
               
            }else{
                $valores["hora_ingreso"] = "";
            }
            //--Hora inicio
            if($valores["hora_inicio"]!=""){
                //$fecha_inicio_h = $valor["hora_inicio"]->toDateTime();
                $fecha_inicio_h = new DateTime(date("Y-m-d g:i a",$valor["hora_inicio"]));
                $valores["hora_inicio"] = $fecha_inicio_h;
                
            }else{
                $valores["hora_inicio"] = "";
            }
            //--Hora fin 
            if($valores["hora_fin"]!=""){
                //$fecha_fin_h = $valor["hora_fin"]->toDateTime();
                $fecha_fin_h = new DateTime(date("Y-m-d g:i a",$valor["hora_fin"]));
                $valores["hora_fin"] = $fecha_fin_h;
            }else{
                $valores["hora_fin"] = "";
            }
            //--
            if($valor["hora_salida"]!='Sin salir'){
                //$fecha_fin = $valor["hora_salida"]->toDateTime();
                $fecha_fin = new DateTime(date("Y-m-d g:i a",$valor["hora_salida"]));
                $valores["hora_salida"] = $fecha_fin;
            }else{
                $valores["hora_salida"] = "Sin salir";
            }
            $valores["hora_liberada"] = $valores["hora_salida"];
            $fecha_reservacion = $valor["fecha"]->toDateTime();
            $valores["fecha_reservacion"] = $fecha_reservacion;
            //------------------------------------------------------------
            //$fecha1 = new DateTime($fecha_fin);//fecha inicial
            //$fecha2 = new DateTime($fecha_inicio);//fecha de cierre
            #Calculo de horas contratadas    
            $intervalo =$fecha_fin_h->diff($fecha_inicio_h);
            
            if(isset($intervalo)){
                $valores["horas_contratadas"] = $intervalo->format('%H:%i:%s');
             }else{
                $valores["horas_contratadas"] = "";
             }
            /*-----------------------------------------------------------------------------*/
            #Calculo de horas consumidas
            if($valores["condicion"]=="REGISTRADA"){
                #Si la condicion es registrada: se resta la hora actua a la hora de ingreso
                if($valores["hora_ingreso"]!=""){
                    $hoy = new DateTime("now");
                    $intervalo2 =$fecha_inicio->diff($hoy);
                    $intervaloConsmuidas = $intervalo2;
                    if(isset($intervalo2)){
                        $valores["horas_consumidas"] = $intervalo2->format('%H:%i:%s');
                    }else{
                        $valores["horas_consumidas"] = "";
                    }
                }else{
                    $valores["horas_consumidas"] = "";
                } 
            } if($valores["condicion"]=="LIBERADA"){
                #Si la condicione s liberada: se resta la hora salida a la de ingreso
                $intervalo3 =$fecha_fin->diff($fecha_inicio);
                $intervaloConsmuidas = $intervalo3;

                if(isset($intervalo3)){
                    $valores["horas_consumidas"] = $intervalo3->format('%H:%i:%s');
                }else{
                    $valores["horas_consumidas"] = "";
                }
            }else if(($valores["condicion"]=="RESERVADA")||($valores["condicion"]=="CANCELADA")){
                $valores["horas_consumidas"] = "";
            }
            //------------------------------------------------------------
            #Horas por consumir
            if(($valores["horas_contratadas"]!="")&&($valores["horas_consumidas"]!="")){
                //var_dump($intervalo<$intervaloConsmuidas);die('');
                
                $horas_uno = new DateTime($valores["horas_contratadas"]);
                $horas_dos = new DateTime($valores["horas_consumidas"]);
                $intervaloDisponibles = $horas_uno->diff($horas_dos);
                $valores["horas_disponibles"] = $intervaloDisponibles->format('%H:%i:%s'); 
                if($valores["horas_disponibles"]>$valores["horas_contratadas"]){
                    $valores["horas_disponibles"] = "";
                }
            }else{
                $valores["horas_disponibles"] = "";
            }
            //------------------------------------------------------------
            $listado[] = $valores;        
            //--

        }
        return $listado;
    }
    /*
    *   Consulta de congiguracion de reservaciones
    */
    public function consultar_configuracion(){
        $listado = [];
        #Recorro la jornada....
        $resultados = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false))->get("configuracion_reservaciones");
        foreach ($resultados as $clave => $valor) {
            $valores = $valor;
            $listado[]= $valores;
        }
        return $valores;
    }
    /*
    *   Marcar Ingreso
    */
    public function marcarReservaciones($id_reservaciones,$data){

        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        
        $fecha = new MongoDB\BSON\UTCDateTime();
        
        $id = new MongoDB\BSON\ObjectId($id_reservaciones);
        
        $rs_salida = $this->mongo_db->where(array("eliminado"=>false,"_id"=>$id))->set($data)->update('reservaciones');
        
        //--Auditoria
        if($rs_salida){
            $data_auditoria = array(
                                        'cod_user'=>$id_usuario,
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Marcar Ingreso/salida',
                                        'operacion'=>''
                                    );
            $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update("reservaciones"); 
        }
        //--
    }
    /*
    *   generarRecibos
    */
    public function generarRecibos($id_reservaciones,$id_membresia,$monto_total,$concepto){
        //------------------------------
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        
        $fecha = new MongoDB\BSON\UTCDateTime();
        //--------------------------------------------------------------------------------
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
        ///-----
        if(count($res_cotizacion)>0){
            $id_cotizacion = (string)$res_cotizacion[0]["_id"]->{'$id'};
            #Consulto la cobranza realizada a esa ultima cotizacion
            $res_cobranza = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,'status'=>true,'id_venta'=>$id_cotizacion))->get("recibos_cobranzas");
            //-----------------------------------------------------
            #Armo el recibo
            #Consulto el monto de la reservacion....
            if($monto_total>0){
                //-----------------------------------------------
                $numero_cobranza =$res_cobranza[0]["numero_cobranza"];

                $id_cobranza = (string)$res_cobranza[0]["_id"]->{'$id'};

                $arr_recibos = $this->obtenerDatosRecibos($res_cobranza[0]["recibos"]);

                $numero_recibo = $arr_recibos["numero_recibo"];

                $numero_secuencia = $arr_recibos["numero_secuencia"];

                $mes = $arr_recibos["mes"];

                $fecha = new MongoDB\BSON\UTCDateTime();

                $fecha2 = Date("d-m-Y");

                $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));

                ///-------------------------------
                $data = array(
                                  "operacion"=>"",
                                  "numero_secuencia"=>$numero_secuencia,
                                  "numero_recibo"=>$numero_recibo,
                                  "mes"=>$mes,
                                  "fecha"=>$fecha,
                                  'tipo_operacion'=>'C',
                                  'concepto'=>$concepto,
                                  'fecha_movimiento'=>$fecha,
                                  'fecha_contable'=>$fecha,
                                  'cargo'=>$monto_total,
                                  'abono'=>0,
                                  'saldo'=>$monto_total,
                                  'forma_pago'=>'',
                                  'banco_pago'=>'',
                                  'monto_pago'=>'',
                                  'numero_tarjeta'=>'',
                                  'cuenta'=>'',
                                  'file_comprobante'=>'',
                                  'pago'=>0,
                                  'status' => true,
                                  'tipo_registro'=>'2',
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
                $res_recibo = $this->registrarRecibos($id_cobranza,$data);
                if($res_recibo){
                    return true;
                }else{
                    return false;
                }
                //-----------------------------------------------
            }
            return true;
            //------------------------------
        }else{
            return true;
        }
       
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
    *   array_sort_by
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
    *   Cancelar reservaciones
    */
    public function cancelarReservaciones($id_reservaciones,$data){

        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        
        $fecha = new MongoDB\BSON\UTCDateTime();
        
        $id = new MongoDB\BSON\ObjectId($id_reservaciones);
        
        $rs_cancelar = $this->mongo_db->where(array("eliminado"=>false,"_id"=>$id))->set($data)->update('reservaciones');
        
        //--Auditoria
        if($rs_cancelar){
            $data_auditoria = array(
                                        'cod_user'=>$id_usuario,
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Cancelar reservaciones',
                                        'operacion'=>''
                                    );
            $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update("reservaciones"); 
        }
       
    }
    /*
    * consultarHorasIngreso
    */
    public function consultarHorasInicio($id_reservaciones){
        
        $id = new MongoDB\BSON\ObjectId($id_reservaciones);
        
        $res_reservaciones = $this->mongo_db->where(array('eliminado'=>false,'_id'=>$id))->get("reservaciones");
        
        $horas_inicio_reservaciones = $res_reservaciones[0]["hora_inicio"];
        
        //$hora_inicio = $horas_inicio_reservaciones->toDateTime();
        
        $hora_inicio = new DateTime(date("Y-m-d g:i a",$horas_inicio_reservaciones));
       
        //return $hora_inicio->format('Y-m-d H:i:s');
        return $hora_inicio;
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
            $fecha_inicio = $valor_jornada["fecha_hora_inicio"]->toDateTime();
            $fecha_hora_ini = $fecha_inicio->format('Y-m-d H:i:s');
            
            if((isset($valor_jornada["fecha_hora_fin"]))&&($valor_jornada["fecha_hora_fin"]!="Sin salir")){
                $fecha_fin = $valor_jornada["fecha_hora_fin"]->toDateTime();
                $fecha_hora_fini = $fecha_fin->format('Y-m-d H:i:s');
            }else{
                $fecha_fin = "";
                $fecha_hora_fini = "";
            }
         
            $fecha1 = new DateTime($fecha_hora_ini);//fecha inicial
            $fecha2 = new DateTime($fecha_hora_fini);//fecha de cierre
            
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
        $fecha_inicio = $res_jornadas[0]["fecha_hora_inicio"]->toDateTime();
        $fecha_hora_ini = $fecha_inicio->format('Y-m-d H:i:s');
        
        if((isset($res_jornadas[0]["fecha_hora_fin"]))&&($res_jornadas[0]["fecha_hora_fin"]!="Sin salir")){
            $fecha_fin = $res_jornadas[0]["fecha_hora_fin"]->toDateTime();
            $fecha_hora_fini = $fecha_fin->format('Y-m-d H:i:s');
        }else{
            $fecha_fin = "";
            $fecha_hora_fini = "";
        }
         
        $fecha1 = new DateTime($fecha_hora_ini);//fecha inicial
        $fecha2 = new DateTime($fecha_hora_fini);//fecha de cierre
            
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
    *   Modificar Status
    */
    public function status_multiple_reservaciones($id, $status){
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
            $modificar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update("reservaciones");
            //var_dump($modificar);die('');
            //--Auditoria
            if($modificar){
                $data_auditoria = "";
                $data_auditoria = array(
                                            'cod_user'=>$id_usuario,
                                            'nom_user'=>$this->session->userdata('nombre'),
                                            'fecha'=>$fecha,
                                            'accion'=>'Modificar status reservaciones',
                                            'operacion'=>''
                                        );
                $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update("reservaciones"); 
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
        $res_jornadas = $this->mongo_db->where($where_array)->set($data)->update("membresia");   
        
        var_dump($res_jornadas);die(''); 
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
    /*
    *   consultarMonto
    */
    public function consultarDatosReservaciones($idReservaciones){
        

        $id = new MongoDB\BSON\ObjectId($idReservaciones);
        
        $res_reservaciones = $this->mongo_db->where(array('_id'=>$id))->get("reservaciones");
 
        $id_membresia = $res_reservaciones[0]["id_membresia"];

        $monto_total = $res_reservaciones[0]["precio"];

        $datos_reservaciones = array(
                                        "id_membresia"=>$id_membresia,
                                        "monto_total"=>$monto_total
        );
        
        return $datos_reservaciones;
    }
    //-----------------------------------------------------------------------------------
}