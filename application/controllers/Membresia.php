<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Membresia extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        //$this->load->database();
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->model('Membresia_model');
        $this->load->model('ClientePagador_model');
        $this->load->model('Usuarios_model');
        $this->load->model('Menu_model');
        $this->load->model('Paquetes_model');
        //--
        $this->load->helper('consumir_rest');
        $this->load->helper('organizar_sepomex');
        $this->load->helper('array_push_assoc');
        //--
        if (!$this->session->userdata("login")) {
          redirect(base_url()."admin");
        }
    }
    /*
    *   Metodo que arma el index...
    */
    public function index(){

        //--Cambio con servicio ag2
        //die('Error 955: Взяв под свой контроль свою команду ....');
        $nacionalidades = consumir_rest('Lista_Valor','buscar', array('cod_static_tipo_valor'=>'NACIONALIDAD'));
        $edo_civil = consumir_rest('Lista_Valor','buscar', array('cod_static_tipo_valor'=>'EDOCIVIL'));
        $sexos = consumir_rest('Lista_Valor','buscar', array('cod_static_tipo_valor'=>'SEXO'));
        $datos['nacionalidades'] = $nacionalidades->data;
        $datos['estadosCiviles'] = $edo_civil->data;
        $datos['sexos'] = $sexos->data;
        $data['planes'] = $this->Membresia_model->listado_planes();
        //var_dump($data['planes']);die('');
        $data['paquetes'] = $this->Membresia_model->listado_paquetes();
        $data['clientes_fisica'] = $this->Membresia_model->listado_clientes('FISICA');
        $data['clientes_moral'] = $this->Membresia_model->listado_clientes('MORAL');
        //----
        $datos['permiso'] = $this->Menu_model->verificar_permiso_vista('membresia', $this->session-> userdata('id_rol'));
        $datos['breadcrumbs'] = $this->Menu_model->breadcrumbs('membresia');
        //$datos['actividadesEconomicas'] = $this->ClientePagador_model->armarSelect('actividadEconomica');
        $datos['actividadesEconomicas'] = $this->armarSelect('actividadEconomica');
        
        //$arreglo_nacionalidad = consumir_rest('Lista_Valor','buscar', array('cod_static_tipo_valor'=>'NACIONALIDAD'));
        //$datos['nacionalidades'] = $arreglo_nacionalidad->data;
        
        $datos['bancos'] = $this->armarSelect('banco');
        
        $datos['plazas'] = $this->armarSelect('plaza');
      
        $datos['giros'] = $this->armarSelect('giro');

        $datos['tipoCuentas'] = $this->armarSelect('tipoCuenta');

        //$datos['nacionalidades'] = $this->Usuarios_model->nacionalidades();
        //$datos['bancos'] = $this->ClientePagador_model->armarSelect('banco');
        //$datos['plazas'] = $this->ClientePagador_model->armarSelect('plaza');
        //$datos['giros'] = $this->ClientePagador_model->armarSelect('giro');
        //$datos['tipoCuentas'] = $this->ClientePagador_model->armarSelect('tipoCuenta');
        
        $data['modulos'] = $this->Menu_model->modulos();

        $data['modulos'] = (array)$data['modulos'];

        $data['vistas'] = $this->Menu_model->vistas($this->session-> userdata('id_rol'));

        $data['modulo_user'] = [];
        foreach ($data['modulos'] as $modulo) {
            foreach ($data['vistas'] as $vista) {
                if((string)$modulo["_id"]->{'$id'} == (string)$vista->id_modulo_vista){
                  $data["modulo_user"][] = $modulo["_id"]->{'$id'};
                }
            }
        }

        $ids = array_unique($data['modulo_user']);
        $data['modulos_enconctrados'] = [];
        foreach ($ids as $value) {
            $data['modulos_enconctrados'][] = $this->Menu_model->modulosbyid($value);
        } 
         
        $oneDim = array();
        foreach($data['modulos_enconctrados'] as $i) {
            $oneDim[] = $i[0];
        }

        $data['modulos_vistas'] = $oneDim;
        $this->load->view('cpanel/header');
        $this->load->view('cpanel/menu', $data);
        $this->load->view('catalogo/Membresia/index', $datos);
        $this->load->view('cpanel/footer');
    }
    /*---------------------------------------------------------------------------------------*/
    /*
    *   Metodo que arma los select
    */
    public function armarSelect($tipo) 
    {
        switch ($tipo) 
        {
            case 'banco':
                $ocupaciones = consumir_rest('Banco','buscar', array());
                $resultados = $ocupaciones->data;
                //$resultados = $this->db->get($tipo);
                break;
            case 'tipoCuenta':
                $tipo_cuenta = consumir_rest('Lista_Valor','buscar', array('cod_static_tipo_valor'=>'TIPOCUENTA'));
                $resultados = $tipo_cuenta->data;
                //$this->db->where('tipolval', 'TIPOCUENTA');
                //$resultados = $this->db->get($this->tabla_lval);
                break;
            case 'actividadEconomica':
                $ocupaciones = consumir_rest('Lista_Valor','buscar', array('cod_static_tipo_valor'=>'OCUPACION'));
                $resultados = $ocupaciones->data;
                //$this->db->order_by("nombre_lista_valor", "asc");
                //$this->db->where('tipolval', 'OCUPACION');
                //$resultados = $this->db->get($this->tabla_lval);
                break;
            case 'plaza':
                $plazas = consumir_rest('Plaza','buscar', array());
                $resultados = $plazas->data;

            //    $resultados = $this->db->get($tipo);
                break;
            case 'giro':
                $giro = consumir_rest('Lista_Valor','buscar', array('cod_static_tipo_valor'=>'GIROMERCA'));
                $resultados = $giro->data;
            //$this->db->order_by("nombre_lista_valor", "asc");
            //$this->db->where('tipolval', 'GIROMERCA');
            //    $resultados = $this->db->get($this->tabla_lval);
            break;
            
        }
        return $resultados;
    }
    public function listado_membresia(){
        $listado = [];
        $listado2 = [];
        $listado = $this->Membresia_model->listado_membresia();
        foreach ($listado as $value) {
            $arreglo_data = $value;                       
            $listado2[] = $arreglo_data;
        }
        echo json_encode($listado2);
    } 
    /*
    *   planes_paquetes_saldos
    */
    public function planes_paquetes_saldos(){
        $id_membresia = $this->input->post("id_membresia");
        $renovacion = $this->input->post("numero_renovacion");
        $arreglo_renovacion = explode("*", $renovacion);
        $numero_renovacion = (int)$arreglo_renovacion[0];
        $actual_renovacion = $arreglo_renovacion[1];
        $listado = $this->Membresia_model->listado_planes_paquetes($id_membresia,$numero_renovacion,$actual_renovacion);
        echo json_encode($listado);
    }
    /*
    *   Listado recargos
    */
    public function listadoRecargosSaldos(){
        $listado = [];
        $listado2 = [];
        $id_membresia = $this->input->post("id_membresia"); 
        $renovacion = $this->input->post("numero_renovacion");
        $arreglo_renovacion = explode("*", $renovacion);
        $numero_renovacion = (int)$arreglo_renovacion[0];
        $actual_renovacion = $arreglo_renovacion[1];
        $fecha = $this->input->post("fecha");
        $listado = $this->Membresia_model->listado_recargos_saldos($id_membresia,$fecha,$numero_renovacion,$actual_renovacion);
        $jornadas = $this->Membresia_model->listado_jornadas_saldos_dos($id_membresia,$fecha,$numero_renovacion,$actual_renovacion);
        //------------------------------------------------------------------------------
        #Aqui recorro listado, y si el servicio es horas de coworking, debo asignarle los valores del arreglo de jornadas...
        foreach ($listado as $clave => $valor) {
            $id_servicio = new MongoDB\BSON\ObjectId($valor["id_servicios"]);
            //Si es id de horas de coworking
            if($valor["id_servicios"]=="5c9e4542e31dd9188068de42"){
                $valor["contratados"] = $jornadas["contratados"];
                $valor["consumidos"] = $jornadas["consumidos"];
                $valor["disponibles"] = $jornadas["disponible"];
            }



            if ($valor["servicios"] == "SALA DE JUNTAS") {

                $data = $this->TotalHorasConsumidas($id_membresia, $renovacion, $fecha);
                $valor["consumidos"]  = $data["horas_total"];
                $valor["disponibles"] = $valor["contratados"] - $data["horas_total"];            
            }
            //Si es un tipo de servicio salas
            #Consulto el tipo de servicio salas
            /*$res_tipos_servicios = $this->mongo_db->where(array('eliminado'=>false,'titulo'=>"SALAS"))->get("tipo_servicios");
            $id_tipo = $res_tipos_servicios[0]["_id"]->{'$id'};
            #Consulto el servicio
            $res_servicio = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,'_id'=>$id_servicio))->get("servicios");
            if($res_servicio){
                //--
                if($res_servicio[0]["tipo_servicio"]==$id_tipo){
                    $id_servicio_sala = $res_servicio[0]["_id"]->{'$id'};
                    $listado_res = $this->Membresia_model->listado_reservaciones_saldos_dos($id_membresia,$fecha,$numero_renovacion,$actual_renovacion,$id_servicio_sala);

                        $valor["contratados"] = $listado_res["horas_contratadas"];
                        $valor["consumidos"] = $listado_res["horas_consumidas"];
                        $valor["disponibles"] = $listado_res["horas_disponibles"];
                }
                //--
            }*/

            $listado2[] = $valor;
        }
        //var_dump($listado);die('');
        //------------------------------------------------------------------------------
        /*foreach ($listado as $value) {
            $arreglo_data = $value;                   
            $listado2[] = $arreglo_data;
        }*/
        echo json_encode($listado2);
    }/*
    *   Listado recargos
    */
    public function listadoRecargosAdicionalesSaldos(){
        $listado = [];
        $listado2 = [];
        $id_membresia = $this->input->post("id_membresia");
        $renovacion = $this->input->post("numero_renovacion");
        $arreglo_renovacion = explode("*", $renovacion);
        $numero_renovacion = (int)$arreglo_renovacion[0];
        $actual_renovacion = $arreglo_renovacion[1];
        $fecha = $this->input->post("fecha");
        $listado = $this->Membresia_model->listado_recargos_adicionales_saldos($id_membresia,$fecha,$numero_renovacion,$actual_renovacion);
        /*foreach ($listado as $value) {
            $arreglo_data = $value;                   
            $listado2[] = $arreglo_data;
        }*/
        echo json_encode($listado);
    }
    /*
    *   Listado recargos
    */
    public function listadoReservacionesSaldos(){
        $listado = [];
        $listado2 = [];
        $id_membresia = $this->input->post("id_membresia");
        $renovacion = $this->input->post("numero_renovacion");
        $arreglo_renovacion = explode("*", $renovacion);
        $numero_renovacion = (int)$arreglo_renovacion[0];
        $actual_renovacion = $arreglo_renovacion[1];
        $fecha = $this->input->post("fecha");
        $listado = $this->Membresia_model->listado_reservaciones_saldos($id_membresia,$fecha,$numero_renovacion,$actual_renovacion);
        /*foreach ($listado as $value) {
            $arreglo_data = $value;                   
            $listado2[] = $arreglo_data;
        }*/
        echo json_encode($listado);
    }



    public function TotalHorasConsumidas($id_membresia, $renovacion, $fecha){
        $listado  = [];
        $listado2 = [];

        // $id_membresia = $this->input->post("id_membresia");
        // $renovacion   = $this->input->post("numero_renovacion");

        $arreglo_renovacion = explode("*", $renovacion);
        $numero_renovacion  = (int)$arreglo_renovacion[0];
        $actual_renovacion  = $arreglo_renovacion[1];
        $listado = $this->Membresia_model->listado_reservaciones_saldos($id_membresia,$fecha,$numero_renovacion,$actual_renovacion);
        
        $horasCosumidas = array();
        $horas_total = 0;
        foreach ($listado as $value) {
            $arreglo_data = $value;     
            if ($value["condicion"] == "LIBERADA" && $value["sala"] == "SALA DE JUNTAS") {
                $listado2[]        = $arreglo_data;
                $hora_consumida  = $this->sumarHoras($value["horas_consumidas"]);

                $horas_total = $horas_total + $hora_consumida;
            }              
        }

        return $array = array("horas_total" => $horas_total);
    }



    public function sumarHoras($hora) {

        $parts_total = explode(":", $hora);

        $horas = $parts_total[0];
        $min   = $parts_total[1];
        if ($horas > 0) {
            $hora_consumida = $horas;
            if ($min > 15) {
                $hora_consumida = $hora_consumida + 1;
            }

        }else{
            if ($min > 15) {
               $hora_consumida  = 1;
            }else{
                $hora_consumida = 0;
            }
            
        }

        return $hora_consumida;
    }











    public function TotalHorasConsumidas2(){
        $listado  = [];
        $listado2 = [];

         $id_membresia = $this->input->post("id_membresia");
         $renovacion   = $this->input->post("numero_renovacion");
         $fecha        = $this->input->post("fecha");

        $arreglo_renovacion = explode("*", $renovacion);
        $numero_renovacion  = (int)$arreglo_renovacion[0];
        $actual_renovacion  = $arreglo_renovacion[1];
        $listado = $this->Membresia_model->listado_reservaciones_saldos($id_membresia,$fecha,$numero_renovacion,$actual_renovacion);
        
        $horasCosumidas = array();

        $horas_total = 0;
        foreach ($listado as $value) {
            $arreglo_data = $value;     
            if ($value["condicion"] == "LIBERADA" && $value["sala"] == "SALA DE JUNTAS") {
                $listado2[]        = $arreglo_data;
                $hora_consumida  = $this->sumarHoras($value["horas_consumidas"]);

                $horas_total = $horas_total + $hora_consumida;
            }              
        }

        $array = array("hora_total" => $horas_total,"reservas" => $listado2);

        echo json_encode($array);
    }




    /*
    *   Listado Jornadas
    */ 
    public function listadoJornadasSaldos(){
        $listado = [];
        $listado2 = [];
        $id_membresia = $this->input->post("id_membresia");
        $renovacion = $this->input->post("numero_renovacion");
        $arreglo_renovacion = explode("*", $renovacion);
        $numero_renovacion = (int)$arreglo_renovacion[0];
        $actual_renovacion = $arreglo_renovacion[1];
        $fecha = $this->input->post("fecha");
        $listado = $this->Membresia_model->listado_jornadas_saldos($id_membresia,$fecha,$numero_renovacion,$actual_renovacion);
        /*foreach ($listado as $value) {
            $arreglo_data = $value;                       
            $listado2[] = $arreglo_data;
        }*/
        echo json_encode($listado); 
    }
    /*
    *   Metodo que realiza la consulta de cliente pagador
    */
    public function consultarClientePagadorRfc(){
        //verifico si esta en cliente
        $tipo_persona = mb_strtoupper($this->input->post("rad_tipoper"));
        $listado = [];
        $listado2 = [];
        if($tipo_persona=="FISICA"){
            $rfc_cliente = $this->input->post("rfc_cliente_registrar_fisica");
        }else{

            $rfc_cliente = $this->input->post("rfc_cliente_registrar_moral");
        }
        //$this->input->post()
        //var_dump($this->input->post());die('xxx');
        if($rfc_cliente!=""){
            $listado = $this->Membresia_model->consultarClientePagadorRfc($rfc_cliente,$tipo_persona);
            //var_dump($listado);die('');
            if(!$listado[0]["error"]){
            //------------------------------------
                foreach ($listado as $value) {
                  //Consumo el servicio segun el id del usuario
                  $sepomex = consumir_rest('Sepomex','consultar', array('id_codigo_postal'=>$value["id_codigo_postal"]));
                  //Transformo el la fila de usuario en un array
                  
                  //$arreglo_data = get_object_vars($value);
                  
                  $arreglo_data = $value;

                  //LLamo al helper para que organize los resultados de la consulta al servicio
                  $arreglo_sepomex = organizar_sepomex($sepomex);

                  $actividad_economica = consumir_rest('Lista_Valor','consultar', array('id_lista_valor'=>$value["actividad_e_cliente"]));
                    
                  $arreglo_data["actividad_economica"] = is_array($actividad_economica->data)?$actividad_economica->data[0]->nombre_lista_valor:(is_object($actividad_economica->data)?$actividad_economica->data->nombre_lista_valor:'');

                  $pais = consumir_rest('Lista_Valor','consultar', array('id_lista_valor'=>$value["pais_cliente"]));
                    
                  $arreglo_data["pais_origen"] = is_array($pais->data)?$pais->data[0]->nombre_lista_valor:(is_object($pais->data)?$pais->data->nombre_lista_valor:'');

                  $nacionalidad = consumir_rest('Lista_Valor','consultar', array('id_lista_valor'=>$value["nacionalidad_datos_personales"]));
                    
                  $arreglo_data["pais_nacionalidad"] = is_array($nacionalidad->data)?$nacionalidad->data[0]->nombre_lista_valor:(is_object($nacionalidad->data)?$nacionalidad->data->nombre_lista_valor:'');

                 //--Giro mercantil
                  $giro_merca = consumir_rest('Lista_Valor','consultar', array('id_lista_valor'=>$value["giro_mercantil"]));
                  //al subir al servidor quitar
                  /*$arreglo_data["giro_merca_desc"] = is_array($giro_merca->data)?$giro_merca->data[0]->nombre_lista_valor:(is_object($giro_merca->data)?$giro_merca->data->nombre_lista_valor:'');*/

                  //unset($listado[$key]);
                  //Hago push assoc de la fila de usuario y sus datos respectivos en sepomex
                  $listado2[] = array_push_assoc($arreglo_data,$arreglo_sepomex);
                } 
            //------------------------------------    
            }
             
        }
        echo(json_encode($listado2));
    }
    /*
    *   Metodo que realiza la consulta de cliente pagador al modificar.... esto deberia unificarse con la consulta anterior, pero por rapidez.... bueh :/
    */
    public function consultarClientePagadorRfcModificar(){
        //verifico si esta en cliente
        $tipo_persona = mb_strtoupper($this->input->post("tipo_per"));
        $listado = [];
        $listado2 = [];
        
        $rfc_cliente = $this->input->post("rfc_cliente");
        
        //var_dump($this->input->post());die('');
        if($rfc_cliente!=""){
            $listado = $this->Membresia_model->consultarClientePagadorRfc($rfc_cliente,$tipo_persona);
            //var_dump(($listado[0]));die('');
            if($listado[0]!=""){
            //------------------------------------
                foreach ($listado as $value) {
                  //Consumo el servicio segun el id del usuario
                  $sepomex = consumir_rest('Sepomex','consultar', array('id_codigo_postal'=>$value["id_codigo_postal"]));
                  //Transformo el la fila de usuario en un array
                  
                  //$arreglo_data = get_object_vars($value);
                  
                  $arreglo_data = $value;

                  //LLamo al helper para que organize los resultados de la consulta al servicio
                  $arreglo_sepomex = organizar_sepomex($sepomex);

                  $actividad_economica = consumir_rest('Lista_Valor','consultar', array('id_lista_valor'=>$value["actividad_e_cliente"]));
                    
                  $arreglo_data["actividad_economica"] = is_array($actividad_economica->data)?$actividad_economica->data[0]->nombre_lista_valor:(is_object($actividad_economica->data)?$actividad_economica->data->nombre_lista_valor:'');

                  $pais = consumir_rest('Lista_Valor','consultar', array('id_lista_valor'=>$value["pais_cliente"]));
                    
                  $arreglo_data["pais_origen"] = is_array($pais->data)?$pais->data[0]->nombre_lista_valor:(is_object($pais->data)?$pais->data->nombre_lista_valor:'');

                  $nacionalidad = consumir_rest('Lista_Valor','consultar', array('id_lista_valor'=>$value["nacionalidad_datos_personales"]));
                    
                  $arreglo_data["pais_nacionalidad"] = is_array($nacionalidad->data)?$nacionalidad->data[0]->nombre_lista_valor:(is_object($nacionalidad->data)?$nacionalidad->data->nombre_lista_valor:'');

                 //--Giro mercantil
                  $giro_merca = consumir_rest('Lista_Valor','consultar', array('id_lista_valor'=>$value["giro_mercantil"]));

                  //$arreglo_data["giro_merca_desc"] = is_array($giro_merca->data)?$giro_merca->data[0]->nombre_lista_valor:(is_object($giro_merca->data)?$giro_merca->data->nombre_lista_valor:'');
                  //unset($listado[$key]);
                  //Hago push assoc de la fila de usuario y sus datos respectivos en sepomex
                  $listado2[] = array_push_assoc($arreglo_data,$arreglo_sepomex);
                } 
            //------------------------------------    
            }
             
        }
        echo(json_encode($listado2));
    }
    /*
    *   Metodo para realizar la consulta de planes
    */
    //Ya no se usa... 
    public function consultarPlan(){
        $id_plan = $this->input->post("id_plan");
        $planes = $this->Membresia_model->buscarPlanes($id_plan);
        echo(json_encode($planes));
    }
    /*
    *   Funcion que alimenta la tabla
    */
    public function consultarPlanPaquetesTablas(){
        $id_plan = $this->input->post("id_plan");
        $id_paquete = $this->input->post("paquete");

        if($id_paquete!="")
            $planes = $this->Membresia_model->buscarPlanesPaquetesTabla($id_plan,$id_paquete);
        else
            $planes = "";
        echo(json_encode($planes));
    }
    /*
    *   Metodo para realizar la consulta de paquetes
    */
    public function consultarPaquetes(){
        $id_plan = $this->input->post("id_plan");
        $paquetes = $this->Membresia_model->buscarPaquetes($id_plan);
        echo(json_encode($paquetes));
    }
    /*
    *   MEtodo que registra la membresia
    */
    public function registrar_membresia(){

        $fecha = new MongoDB\BSON\UTCDateTime();
        
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        
        $formulario = $this->input->post();
        //var_dump($formulario);die('');
        //---Planes:
        $plan = (isset($formulario["plan_membresia_registrar"]))? $formulario["plan_membresia_registrar"]: "";
        $paquete = (isset($formulario["paquetes_membresia_registrar"]))? $formulario["paquetes_membresia_registrar"]: "";
        $fecha_inicio = (isset($formulario["plan_fecha_inicio"]))? $formulario["plan_fecha_inicio"]: "";
        $fecha_fin = (isset($formulario["plan_fecha_fin"]))? $formulario["plan_fecha_fin"]: "";
        $valor = (isset($formulario["plan_valor"]))? $formulario["plan_valor"]: "";
        $tipo_persona = $this->input->post("rad_tipoper");
        //--Costo del pla/paquete
        //---Para persona fisica
        if($tipo_persona=="fisica"){
            
            $serial = (isset($formulario["serial_acceso_registrar_fisica"]))? $formulario["serial_acceso_registrar_fisica"]:"";

            $grupo_empresarial =(isset($formulario["grupo_empresarial"]))? $formulario["grupo_empresarial"]: "";

            $rfc_cliente = (isset($formulario["rfc_cliente_registrar_fisica"]))? $formulario["rfc_cliente_registrar_fisica"]: "";
            $this->reglas_validacion('insert','datosBasicosFisica');

        }else if($this->input->post("rad_tipoper")=="moral"){        
        //---Para persona moral
            $serial = (isset($formulario["serial_acceso_registrar_moral"]))? $formulario["serial_acceso_registrar_moral"]:"";
            
            $rfc_cliente = (isset($formulario["rfc_cliente_registrar_moral"]))? $formulario["rfc_cliente_registrar_moral"]: "";

            $grupo_empresarial = "";

            $this->reglas_validacion('insert','datosBasicosMoral');
        }
        $this->mensajes_reglas();
        //----------------------------------------------------------------------
        $vector_fecha_inicio = explode(" ",$fecha_inicio);
        $mes_numero_inicio = $this->Membresia_model->meses_en_numeros($vector_fecha_inicio["1"]);
        $vector_fecha_fin = explode(" ",$fecha_fin);
        $mes_numero_fin = $this->Membresia_model->meses_en_numeros($vector_fecha_fin["1"]);
       
        $fecha_inicio_c =$vector_fecha_inicio[0]."-".$mes_numero_inicio."-".$vector_fecha_inicio["2"];
        $fecha_ini = strtotime($fecha_inicio_c)*1000;

        $fecha_fin_c = $vector_fecha_fin[0]."-".$mes_numero_fin."-".$vector_fecha_fin["2"];
        $fecha_fini = strtotime($fecha_fin_c)*1000;

        $numero_membresia = $this->Membresia_model->obtener_numero_membresia();

        $numero_renovacion = 1; 
        /*
        * Consulto los servicios asociados....
        */
        //$servicios =  $this->obtenerServicios($paquete);
        #Modificacion realizada por Gianni Santucci 13-06-2019, se deben almacenar los servicios de tipo caracteres
        $arr_Serv =  $this->obtenerServicios($paquete);
        $servicios = $arr_Serv["servicios_n"];
        $servicios_c = $arr_Serv["servicios_c"];
        //echo '<pre>' . var_export($servicios, true) . '</pre>';die;

        $serial = trim(mb_strtoupper($serial));
        /*
        *
        */
        if($this->form_validation->run() == true){
          $data = array(
                          'serial_acceso'          => $serial,
                          'n_membresia'            =>  $numero_membresia,
                          'numero_renovacion'=>$numero_renovacion,
                          'id_grupo_empresarial'   => '1',
                          'tipo_persona' => $tipo_persona,
                          'identificador_prospecto_cliente'   =>  $rfc_cliente,
                          'plan' => $plan,
                          'paquete' => $paquete,
                          'fecha_inicio' =>  $this->mongo_db->date($fecha_ini),
                          'fecha_fin' =>  $this->mongo_db->date($fecha_fini),
                          'valor'=> str_replace(",","",$valor),
                          'status' => true,
                          'eliminado' => false,
                          'trabajadores' => [],
                          'renovaciones' => [],
                          'cancelado'=>false,
                          'servicios'=>$servicios,
                          'servicios_c'=>$servicios_c,
                          'auditoria' => [array(
                                                    "cod_user" => $id_usuario,
                                                    "nomuser" => $this->session->userdata('nombre'),
                                                    "fecha" => $fecha,
                                                    "accion" => "Nuevo registro membresia",
                                                    "operacion" => ""
                                                )]
          );
          //var_dump($data);die('');
          $this->Membresia_model->registrar_membresia($data);
        }else{
             // enviar los errores
            echo validation_errors();
        }
        //----------------------------------------------------------------------
    }
    /*
    * Obtener servicios segun planes
    */
    function obtenerServicios($paquete){
        $servicios = $this->Membresia_model->obtenerServicios($paquete);
        return $servicios;
    }
    /*
    * Metodo que actualiza la membresia
    */
    public function actualizar_membresia(){
        $fecha = new MongoDB\BSON\UTCDateTime();
        
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        
        $formulario = $this->input->post();
        //--Para persona fisica
        if($formulario["tipo_persona_actualizar"]=="fisica" || $formulario["tipo_persona_actualizar"]=="FISICA"){
            $serial = trim(mb_strtoupper($formulario["serial_acceso_actualizar_fisica"]));
        }else if($formulario["tipo_persona_actualizar"]=="moral" || $formulario["tipo_persona_actualizar"]=="MORAL"){
            $serial = trim(mb_strtoupper($formulario["serial_acceso_actualizar_moral"]));
        }
        //--
        //Valido que no exista otro registro con ese serial
        $this->Membresia_model->validarSerialEditar($formulario["id_membresia_actualizar"],$serial);
                //--
        if($serial!=""){
            $data = array(
                          'id_membresia' => $formulario["id_membresia_actualizar"],
                          'serial_acceso'=>$serial
            );
            $this->Membresia_model->actualizar_membresia($data); 
        }
          
        //--Para persona moral    
        //---Planes:
        /*$plan = (isset($formulario["plan_membresia_actualizar"]))? $formulario["plan_membresia_actualizar"]: "";
        $fecha_inicio = (isset($formulario["plan_fecha_inicioE"]))? $formulario["plan_fecha_inicioE"]: "";
        $fecha_fin = (isset($formulario["plan_fecha_finE"]))? $formulario["plan_fecha_finE"]: "";
        $valor = (isset($formulario["plan_valorE"]))? $formulario["plan_valorE"]: "";
        $tipo_persona = $formulario["tipo_persona_actualizar"];
        //---
        //Validaciones de fechas...
        $vector_fecha_inicio = explode(" ",$fecha_inicio);
        ///var_dump($formulario["plan_fecha_inicio"]);die('');
        $mes_numero_inicio = $this->Membresia_model->meses_en_numeros($vector_fecha_inicio["1"]);
        $vector_fecha_fin = explode(" ",$fecha_fin);
        $mes_numero_fin = $this->Membresia_model->meses_en_numeros($vector_fecha_fin["1"]);
       
        $fecha_inicio_c =$vector_fecha_inicio[0]."-".$mes_numero_inicio."-".$vector_fecha_inicio["2"];
        $fecha_ini = strtotime($fecha_inicio_c)*1000;
        
        $fecha_fin_c = $vector_fecha_fin[0]."-".$mes_numero_fin."-".$vector_fecha_fin["2"];
        $fecha_fini = strtotime($fecha_fin_c)*1000;
        //---
        if($tipo_persona=="fisica"){
          $this->reglas_validacion('update','datosBasicosFisica');
        }else if($tipo_persona=="moral"){
            $this->reglas_validacion('update','datosBasicosMoral');
            //var_dump($this->reglas_validacion('update','datosBasicosMoral'));die('');  
        }
        $this->mensajes_reglas();
        if($this->form_validation->run() == true){
            $data = array(
                          'id_membresia' => $formulario["id_membresia_actualizar"],
                          'plan' => $plan,
                          'fecha_inicio' => $this->mongo_db->date($fecha_ini),
                          'fecha_fin' => $this->mongo_db->date($fecha_fini),
                          'valor'=> $valor,
                          'status' => true,
                          'eliminado' => false,
            );
            $this->Membresia_model->actualizar_membresia($data);    
        }*/

    }
    public function reglas_validacion($method, $tipo)
    {
        switch ($tipo) {
            case 'renovaciones':
                if($method=="insert"){
                    $this->form_validation->set_rules('plan_renovaciones_registrar','Planes','required');
                    $this->form_validation->set_rules('paquetes_renovaciones_registrar','Paquetes','required');
                }  
                break;  
            case 'datosBasicosFisica':  
                if($method=="insert"){
                    // Reglas para la tabla de cliente
                    $this->form_validation->set_rules('rad_tipoper','Tipo persona','required');
                    $this->form_validation->set_rules('serial_acceso_registrar_fisica','Serial de acceso','required');
                    $this->form_validation->set_rules('rfc_cliente_registrar_fisica','Identificación (Prospecto/CLiente)','required');
                }else
                if($method=="update"){
                    // Reglas para la tabla de cliente
                    $this->form_validation->set_rules('serial_acceso_actualizar_fisica','Serial de acceso','required');
                    $this->form_validation->set_rules('rfc_cliente_actualizar_fisica','Identificación (Prospecto/CLiente)','required');
                    $this->form_validation->set_rules('plan_membresia_actualizar','Planes','required');
                }
                break;
            case 'datosBasicosMoral':  
                if($method=="insert"){
                    // Reglas para la tabla de cliente
                    $this->form_validation->set_rules('rad_tipoper','Tipo persona','required');
                    $this->form_validation->set_rules('serial_acceso_registrar_moral','Serial de acceso','required');
                    $this->form_validation->set_rules('rfc_cliente_registrar_moral','Identificación (Prospecto/CLiente)','required');
                }
                /*else
                if($method=="update"){
                    // Reglas para la tabla de cliente
                    $this->form_validation->set_rules('serial_acceso_actualizar_moral','Serial de acceso','required');
                    $this->form_validation->set_rules('rfc_cliente_actualizar_moral','Identificación (Prospecto/CLiente)','required');
                    $this->form_validation->set_rules('plan_membresia_actualizar','Planes','required');
                }*/
                break; 
            case 'datosTrabajadores':
                if($method=="insert"){
                    $this->form_validation->set_rules('serial_acceso_moral_dt','Serial acceso','required');
                    $this->form_validation->set_rules('nombre_dt','Nombre(s)','required');
                    $this->form_validation->set_rules('apellido_paterno_moral_dt','Apellido Paterno)','required');
                    $this->form_validation->set_rules('apellido_materno_dt','Apellido Materno','required');
                    $this->form_validation->set_rules('genero_registrar_dt','Género','required');
                    $this->form_validation->set_rules('edo_civil_registrar_dt','Estado Civil','required');
                    $this->form_validation->set_rules('nacionalidad_registrar_dt','Nacionalidad','required');
                    $this->form_validation->set_rules('fecha_nacimiento_dt','Fecha Nacimiento','required');
                    $this->form_validation->set_rules('telefono_registrar_dt','Teléfono','required');
                    $this->form_validation->set_rules('correo_registrar_dt','Correo Electrónico','required');
                }
                if($method=="update"){
                    $this->form_validation->set_rules('serial_acceso_moral_dt_actualizar','Serial acceso','required');
                    $this->form_validation->set_rules('nombre_dt_actualizar','Nombre(s)','required');
                    $this->form_validation->set_rules('apellido_paterno_moral_dt_actualizar','Apellido Paterno)','required');
                    $this->form_validation->set_rules('apellido_materno_dt_actualizar','Apellido Materno','required');
                    $this->form_validation->set_rules('genero_dt_actualizar','Género','required');
                    $this->form_validation->set_rules('edo_civil_dt_actualizar','Estado Civil','required');
                    $this->form_validation->set_rules('nacionalidad_dt_actualizar','Nacionalidad','required');
                    $this->form_validation->set_rules('fecha_nacimiento_dt_actualizar','Fecha Nacimiento','required');
                    $this->form_validation->set_rules('telefono_dt_actualizar','Teléfono','required');
                    $this->form_validation->set_rules('correo_dt_actualizar','Correo Electrónico','required');
                }        
        }        
    }
    
    public function mensajes_reglas(){
      $this->form_validation->set_message('required', 'El campo %s es obligatorio');
      $this->form_validation->set_message('numeric', 'El campo %s debe poseer solo números');
    }
    /*
    *   Status Membresia
    */
    public function status_membresia(){
        $id = $this->input->post('id');
        $status = $this->input->post('status'); 
        $this->Membresia_model->status($id,$status, 'membresia');
        echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
    }
    /*
    * Eliminar membresia
    */
    public function eliminar (){
        $id = $this->input->post('id');
        //--Verifico que la membresia no cuente con jornadas...
        $existe_jornadas = $this->Membresia_model->buscarJornadas($id);
        if ($existe_jornadas>0){
          echo ("<span>La membresia no se puede eliminar si tiene una jornada asociada!</span>");
          die('');
        }
         //--Verifico que la membresia no cuente con reservaciones...
        $existe_reservaciones = $this->Membresia_model->buscarReservaciones($id);
        if ($existe_reservaciones>0){
          echo ("<span>La membresia no se puede eliminar si tiene una reservación asociada!</span>");
          die('');
        }
        //---
        /*$consulta = $this->Membresia_model->consultaCarteraCliente($id);
        if (isset($consulta)){
            echo ("<span>El Cliente NO se puede eliminar ya que tiene un Vendedor en cartera de cliente asociado!</span>");
        }else{
            $this->ClientePagador_model->eliminar($id, 'cliente');
        }*/
        $this->Membresia_model->eliminar($id, 'membresia');
    }
    /*
    *   Eliminar multiple
    */
    public function eliminar_multiple(){
        $this->Membresia_model->eliminar_multiple($this->input->post('id'));
    }
    /*
    * Status multiple
    */
    public function status_multiple(){
        $this->Membresia_model->status_multiple_membresia($this->input->post('id'), $this->input->post('status'));
        echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
    }
    /*------------------------------------------------------------------------------------------------*/
    //-Metodos de datos trabajadores...
    /*
    *  Index de datos trabajadores
    */
    public function datos_trabajadores($id_membresia="",$editable =""){

        $nacionalidades = consumir_rest('Lista_Valor','buscar', array('cod_static_tipo_valor'=>'NACIONALIDAD'));
        $edo_civil = consumir_rest('Lista_Valor','buscar', array('cod_static_tipo_valor'=>'EDOCIVIL'));
        $sexos = consumir_rest('Lista_Valor','buscar', array('cod_static_tipo_valor'=>'SEXO'));
        $datos['nacionalidades'] = $nacionalidades->data;
        $datos['estadosCiviles'] = $edo_civil->data;
        $datos['sexos'] = $sexos->data;
        $data['planes'] = $this->Paquetes_model->listado_planes();
        //----
        $datos["editable"] = $editable;
        $datos["id_membresia"] = $id_membresia;
        $datos['permiso'] = $this->Menu_model->verificar_permiso_vista('membresia', $this->session-> userdata('id_rol'));
        $datos['breadcrumbs'] = $this->Menu_model->breadcrumbs('membresia');
        //$datos['actividadesEconomicas'] = $this->ClientePagador_model->armarSelect('actividadEconomica');
        $datos['actividadesEconomicas'] = $this->armarSelect('actividadEconomica');
        
        //$arreglo_nacionalidad = consumir_rest('Lista_Valor','buscar', array('cod_static_tipo_valor'=>'NACIONALIDAD'));
        //$datos['nacionalidades'] = $arreglo_nacionalidad->data;
        
        $datos['bancos'] = $this->armarSelect('banco');
        
        $datos['plazas'] = $this->armarSelect('plaza');
      
        $datos['giros'] = $this->armarSelect('giro');

        $datos['tipoCuentas'] = $this->armarSelect('tipoCuenta');

        //$datos['nacionalidades'] = $this->Usuarios_model->nacionalidades();
        //$datos['bancos'] = $this->ClientePagador_model->armarSelect('banco');
        //$datos['plazas'] = $this->ClientePagador_model->armarSelect('plaza');
        //$datos['giros'] = $this->ClientePagador_model->armarSelect('giro');
        //$datos['tipoCuentas'] = $this->ClientePagador_model->armarSelect('tipoCuenta');
        
        $data['modulos'] = $this->Menu_model->modulos();

        $data['modulos'] = (array)$data['modulos'];

        $data['vistas'] = $this->Menu_model->vistas($this->session-> userdata('id_rol'));
        
        $datos['editable'] = $editable;
        
        $data['modulo_user'] = [];
        foreach ($data['modulos'] as $modulo) {
            foreach ($data['vistas'] as $vista) {
                if((string)$modulo["_id"]->{'$id'} == (string)$vista->id_modulo_vista){
                  $data["modulo_user"][] = $modulo["_id"]->{'$id'};
                }
            }
          }


          $ids = array_unique($data['modulo_user']);
          $data['modulos_enconctrados'] = [];
          foreach ($ids as $value) {
            $data['modulos_enconctrados'][] = $this->Menu_model->modulosbyid($value);
          } 

         

          $oneDim = array();
        foreach($data['modulos_enconctrados'] as $i) {
          $oneDim[] = $i[0];
        }
       
        $data['modulos_vistas'] = $oneDim;
        $this->load->view('cpanel/header');
        $this->load->view('catalogo/Membresia/datos_trabajadores',$datos);//$datos
        $this->load->view('cpanel/footer');
    }
    /*
    *  Index de saldos
    */
    public function saldos($datos_saldos="",$editable =""){
        $datos_membresia = explode("_",$datos_saldos); 
        $vector_datos = array(
                                "id_membresia"=>$datos_membresia[0],
                                "rfc"=>$datos_membresia[1],
                                "serial"=>$datos_membresia[2],
                                "nombre"=>$datos_membresia[3],
                                "apellido_paterno"=>$datos_membresia[4],
                                "apellido_materno"=>$datos_membresia[5],
                                "numero_renovacion"=>$datos_membresia[6]
                        );
        $datos["membresia"] = $vector_datos;
        //--Consulto todas las renovaciones asociadas a esta membresía
        $datos["renovaciones"] =$this->Membresia_model->consultarRenovaciones($datos_membresia[0]);
        $datos["cuantas_renovaciones"] = count($datos["renovaciones"]);
        //--Consulto las fechas que serviran de filtros....
        //$datos["fechas_membresia"] =$this->Membresia_model->consultarFechasMembresia($datos_membresia[0]);
        //--
        $nacionalidades = consumir_rest('Lista_Valor','buscar', array('cod_static_tipo_valor'=>'NACIONALIDAD'));
        $edo_civil = consumir_rest('Lista_Valor','buscar', array('cod_static_tipo_valor'=>'EDOCIVIL'));
        $sexos = consumir_rest('Lista_Valor','buscar', array('cod_static_tipo_valor'=>'SEXO'));
        $datos['nacionalidades'] = $nacionalidades->data;
        $datos['estadosCiviles'] = $edo_civil->data;
        $datos['sexos'] = $sexos->data;
        $data['planes'] = $this->Paquetes_model->listado_planes();
        //----
        $datos["editable"] = $editable;
        $datos["id_membresia"] = $datos["membresia"]["id_membresia"];
        $datos['permiso'] = $this->Menu_model->verificar_permiso_vista('membresia', $this->session-> userdata('id_rol'));
        $datos['breadcrumbs'] = $this->Menu_model->breadcrumbs('membresia');
        
        $data['modulos'] = $this->Menu_model->modulos();

        $data['modulos'] = (array)$data['modulos'];

        $data['vistas'] = $this->Menu_model->vistas($this->session-> userdata('id_rol'));
        
        $datos['editable'] = $editable;
        
        $data['modulo_user'] = [];
        foreach ($data['modulos'] as $modulo) {
            foreach ($data['vistas'] as $vista) {
                if((string)$modulo["_id"]->{'$id'} == (string)$vista->id_modulo_vista){
                  $data["modulo_user"][] = $modulo["_id"]->{'$id'};
                }
            }
          }


          $ids = array_unique($data['modulo_user']);
          $data['modulos_enconctrados'] = [];
          foreach ($ids as $value) {
            $data['modulos_enconctrados'][] = $this->Menu_model->modulosbyid($value);
          } 

         

          $oneDim = array();
        foreach($data['modulos_enconctrados'] as $i) {
          $oneDim[] = $i[0];
        }
       
        $data['modulos_vistas'] = $oneDim;
        $this->load->view('cpanel/header');
        $this->load->view('catalogo/Membresia/saldos',$datos);//$datos
        $this->load->view('cpanel/footer');
    }
    /*
    *   Pestaña de renovaciones
    */
    public function renovaciones($datos_renovacion,$editable){
    //--------------------------------------------------------------------------------
        
        $datos_membresia = explode("_",$datos_renovacion); 

        $vector_datos = array(
                                "id_membresia"=>$datos_membresia[0],
                                "paquete"=>$datos_membresia[1],
                                "plan"=>$datos_membresia[2],
                                "cliente"=>$datos_membresia[3],
                                "numero_membresia"=>$datos_membresia[4],
                                "numero_renovacion"=>$datos_membresia[5],
                        );
        $datos["membresia"] = $vector_datos;

        $datos['planes'] = $this->Paquetes_model->listado_planes();
        
        //----
        
        $datos["editable"] = $editable;
        
        $datos["id_membresia"] = $datos["membresia"]["id_membresia"];
        
        $datos['permiso'] = $this->Menu_model->verificar_permiso_vista('membresia', $this->session-> userdata('id_rol'));

        $datos['breadcrumbs'] = $this->Menu_model->breadcrumbs('membresia');
        
        $data['modulos'] = $this->Menu_model->modulos();

        $data['modulos'] = (array)$data['modulos'];

        $data['vistas'] = $this->Menu_model->vistas($this->session-> userdata('id_rol'));
        
        $datos['editable'] = $editable;
        
        $data['modulo_user'] = [];
        foreach ($data['modulos'] as $modulo) {
            foreach ($data['vistas'] as $vista) {
                if((string)$modulo["_id"]->{'$id'} == (string)$vista->id_modulo_vista){
                  $data["modulo_user"][] = $modulo["_id"]->{'$id'};
                }
            }
          }


        $ids = array_unique($data['modulo_user']);
        $data['modulos_enconctrados'] = [];
        foreach ($ids as $value) {
            $data['modulos_enconctrados'][] = $this->Menu_model->modulosbyid($value);
        } 
        $oneDim = array();
        foreach($data['modulos_enconctrados'] as $i) {
          $oneDim[] = $i[0];
        }
        //
        $this->load->view('cpanel/header');
        $this->load->view('catalogo/Membresia/renovaciones',$datos);//$datos
        $this->load->view('cpanel/footer');
        //
    //--------------------------------------------------------------------------------    
    }
    /*
    *  Procesar renovación...
    */
    public function renovar_membresia(){
        $fecha = new MongoDB\BSON\UTCDateTime();
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        $formulario = $this->input->post();
        $id_membresia = $formulario["membresia"];
        $numero_renovacion = $formulario["renovacion"];
        //var_dump($numero_renovacion);die('');
        $respuesta = array("message"=>"","success"=>"");
        //Verifico si puede o no renovar...
        $puede_renovar = $this->Membresia_model->consultar_membresia_cancelar($id_membresia);
        //Verifico si tiene jornada activa
        $tiene_jornada = $this->Membresia_model->consultar_existe_jornada($id_membresia,$numero_renovacion);
        if($tiene_jornada>0){
            $respuesta["message"] = "¡Esta membresía cuenta con jornada activa!";
            $respuesta["success"] = false;
            echo json_encode($respuesta);die('');
        }
        //Verificio si tiene reservaciones activa
        $tiene_reserva = $this->Membresia_model->consultar_existe_reserva($id_membresia,$numero_renovacion);
        if($tiene_reserva>0){
            $respuesta["message"] = "¡Esta membresía cuenta con reserva activa!";
            $respuesta["success"] = false;
            echo json_encode($respuesta);die('');
        }
        //var_dump($tiene_jornada);die('');
        if($puede_renovar>0){
            //------------------------------
            #Paso 1: Consultar la membresia...
            $membresia_ant = $this->Membresia_model->consultar_membresia_renovacion($id_membresia);
            #Paso 2; Armar arreglo
            $arreglo_membresia = $this->armar_arreglo_membresia($membresia_ant);
            //die(json_encode($membresia_ant, JSON_PRETTY_PRINT));
            #Paso 3 : Hacer update dentro del la coleccion membresia...
            $registro_renovacion = $this->Membresia_model->registrar_renovacion($arreglo_membresia,$id_membresia);
            if($registro_renovacion){
                #Paso 4 :  armo el arreglo que susutituirá a la colección actual que fue respaldada...
                $arreglo_membresia_renovada = $this->armar_membresia_renovada($formulario);
                //echo json_encode("<span>LРемонт ведется, он не готов на 100%!</span>");
            }else{
                $respuesta["message"] = "Ocurrió un error inesperado al registrar la renovación!!";
                $respuesta["success"] = false;
                echo json_encode($respuesta);die('');
            }
            //------------------------------
        }else{
           $respuesta["message"] = "No puede renovar una membresia no cancelada!";
           $respuesta["success"] = false;
           echo json_encode($respuesta);die('');
        }
        //
        /*var_dump($formulario);
        die('');*/
    }
    /*
    *   Armar arreglo membresia
    */
    public function armar_arreglo_membresia($membresia_ant){
        //--
        $membresia = $membresia_ant[0];

        $trabajadores = $membresia["trabajadores"];

        $servicios = $membresia["servicios"];

        $servicios_c = $membresia["servicios_c"];

        $auditoria = $membresia["auditoria"];

        $data = array(
                          'numero_renovacion' => $membresia["numero_renovacion"],
                          'serial_acceso'          => $membresia["serial_acceso"],
                          'n_membresia'            => $membresia["n_membresia"],
                          'id_grupo_empresarial'   => '1',
                          'tipo_persona' => $membresia["tipo_persona"],
                          'identificador_prospecto_cliente'   =>  $membresia["identificador_prospecto_cliente"],
                          'plan' => $membresia["plan"],
                          'paquete' => $membresia["paquete"],
                          'fecha_inicio' =>  $membresia["fecha_inicio"],
                          'fecha_fin' =>  $membresia["fecha_fin"],
                          'valor'=> str_replace(",","",$membresia["valor"]),
                          'status' => true,
                          'eliminado' => false,
                          'trabajadores' => $trabajadores,
                          'servicios'=> $servicios,
                          'servicios_c'=>$servicios_c,
                          'auditoria' => $auditoria 
        );

        return $data;
        //--
    }
    /*
    *   Armar arreglo de la membresia renovada
    */
    public function armar_membresia_renovada($datos){
        
        $fecha = new MongoDB\BSON\UTCDateTime();
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        //--Armo la estructura nueva basada en los planes/paquetes seleccionados
        $plan = $datos["plan"];
        $paquete = $datos["paquete"];
        $fecha_inicio = $datos["plan_fecha_inicio"];
        $fecha_fin = $datos["plan_fecha_fin"];
        $valor = $datos["plan_valor"];
        $id_membresia = $datos["membresia"]; 
        //----------
        //--Armo la estructura de lops datos de fecha
        $vector_fecha_inicio = explode(" ",$fecha_inicio);
        $mes_numero_inicio = $this->Membresia_model->meses_en_numeros($vector_fecha_inicio["1"]);
        $vector_fecha_fin = explode(" ",$fecha_fin);
        $mes_numero_fin = $this->Membresia_model->meses_en_numeros($vector_fecha_fin["1"]);
       
        $fecha_inicio_c =$vector_fecha_inicio[0]."-".$mes_numero_inicio."-".$vector_fecha_inicio["2"];
        $fecha_ini = strtotime($fecha_inicio_c)*1000;

        $fecha_fin_c = $vector_fecha_fin[0]."-".$mes_numero_fin."-".$vector_fecha_fin["2"];
        $fecha_fini = strtotime($fecha_fin_c)*1000;
        #Modificacion realizada por Gianni Santucci 13-06-2019, se deben almacenar los servicios de tipo caracteres
        $arr_Serv =  $this->obtenerServicios($paquete);
        $servicios = $arr_Serv["servicios_n"];
        $servicios_c = $arr_Serv["servicios_c"];
        
        $numero_renovacion = $this->Membresia_model->obtener_numero_renovacion('',$id_membresia);
        //var_dump($servicios);die('');
        //----------
        //--Armo arreglo data_renovacion

        //---
        $data_renovacion = array(
                          'id_membresia' => $id_membresia,  
                          'plan' => $plan,
                          'paquete' => $paquete,
                          'fecha_inicio' =>  $this->mongo_db->date($fecha_ini),
                          'fecha_fin' =>  $this->mongo_db->date($fecha_fini),
                          'valor'=> str_replace(",","",$valor),
                          'numero_renovacion'=> $numero_renovacion,
                          'status' => true,
                          'eliminado' => false,
                          'cancelado' => false,
                          'servicios'=>$servicios,
                          'servicios_c'=>$servicios_c,
                          'auditoria' => [array(
                                                    "cod_user" => $id_usuario,
                                                    "nomuser" => $this->session->userdata('nombre'),
                                                    "fecha" => $fecha,
                                                    "accion" => "Renovación de membresia",
                                                    "operacion" => ""
                                                )]
          );
          //var_dump($data_renovacion);die('');
          $this->Membresia_model->renovar_membresia($data_renovacion);
        //--
    }
    /*
    * Listado datos trabajadores
    */
    public function listado_datos_trabajadores($id_membresia){
        $listado = [];
        $listado2 = [];
        $listado = $this->Membresia_model->listado_membresia_trabajadores($id_membresia);
        foreach ($listado as $value) {
            $arreglo_data = $value;
            //--
            $grupo_empresarial = consumir_rest('Lista_Valor','consultar', array('id_lista_valor'=>$value["id_genero"]));

            $arreglo_data["grupo_empresarial"] = is_array($grupo_empresarial->data)?$grupo_empresarial->data[0]->nombre_lista_valor:(is_object($grupo_empresarial->data)?$grupo_empresarial->data->nombre_lista_valor:'');

            //--
            $genero = consumir_rest('Lista_Valor','consultar', array('id_lista_valor'=>$value["id_genero"]));
            $arreglo_data["genero"] = is_array($genero->data)?$genero->data[0]->nombre_lista_valor:(is_object($genero->data)?$genero->data->nombre_lista_valor:'');
            //--
            $edo_civil = consumir_rest('Lista_Valor','consultar', array('id_lista_valor'=>$value["id_estado_civil"]));
            $arreglo_data["edo_civil"] = is_array($genero->data)?$genero->data[0]->nombre_lista_valor:(is_object($genero->data)?$genero->data->nombre_lista_valor:'');
            //--
            $nacionalidad = consumir_rest('Lista_Valor','consultar', array('id_lista_valor'=>$value["nacionalidad"]));
            $arreglo_data["pais_nacionalidad"] = is_array($nacionalidad->data)?$nacionalidad->data[0]->nombre_lista_valor:(is_object($nacionalidad->data)?$nacionalidad->data->nombre_lista_valor:'');

            $actividad_economica = consumir_rest('Lista_Valor','consultar', array('id_lista_valor'=>$value["id_actividad_economica"]));

            $arreglo_data["actividad_economica"] = is_array($actividad_economica->data)?$actividad_economica->data[0]->nombre_lista_valor:(is_object($actividad_economica->data)?$actividad_economica->data->nombre_lista_valor:'');

            $arreglo_data["id_datos_trabajadores"] = $value["serial_acceso"];

            $listado2[] = $arreglo_data;
        }
        echo json_encode($listado2);
    }
    /*
    * Guardar datos trabajadores
    */
    public function guardarTrabajador(){
        $fecha = new MongoDB\BSON\UTCDateTime();
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        $formulario = $this->input->post();

        //-----------------------------------
        //--Armo datos de trabajadores....
        $id_membresia = (isset($formulario["id_membresia"]))? $formulario["id_membresia"]: "";
        $serial_acceso = (isset($formulario["serial_acceso_moral_dt"]))? $formulario["serial_acceso_moral_dt"]: "";
        $grupo_empresarial = (isset($formulario["grupo_empresarial_dt"]))? $formulario["grupo_empresarial_dt"]: "";
        $nombre = (isset($formulario["nombre_dt"]))? $formulario["nombre_dt"]: "";
        $apellido_paterno = (isset($formulario["apellido_paterno_moral_dt"]))? $formulario["apellido_paterno_moral_dt"]: "";
        $apellido_materno = (isset($formulario["apellido_materno_dt"]))? $formulario["apellido_materno_dt"]: "";
        $genero_registrar = (isset($formulario["genero_registrar_dt"]))? $formulario["genero_registrar_dt"]: "";
        $edo_civil = (isset($formulario["edo_civil_registrar_dt"]))? $formulario["edo_civil_registrar_dt"]: "";
        $nacionalidad = (isset($formulario["nacionalidad_registrar_dt"]))? $formulario["nacionalidad_registrar_dt"]: "";
        $fecha_nacimiento = (isset($formulario["fecha_nacimiento_dt"]))? $formulario["fecha_nacimiento_dt"]: "";
        $curp = (isset($formulario["curp_registrar_dt"]))? $formulario["curp_registrar_dt"]: "";
        $pasaporte = (isset($formulario["pasaporte_registrar_dt"]))? $formulario["pasaporte_registrar_dt"]: "";
        $telefono = (isset($formulario["telefono_registrar_dt"]))? $formulario["telefono_registrar_dt"]: "";
        $correo = (isset($formulario["correo_registrar_dt"]))? $formulario["correo_registrar_dt"]: "";
        $actividad_economica = (isset($formulario["actividad_economica_dt"]))? $formulario["actividad_economica_dt"]: "";
        //-----------------------------------
        //Validar que el serial no este asociado a otra membresia--
        $existe_serial = $this->Membresia_model->consultar_serial_existe($serial_acceso);
        if($existe_serial){
            echo "<span>¡Ya se encuentra otra membresía con ese serial!</span>";die('');
        }
        //-----------------------------------
        //--Para carga de la imagen...
        //------------------------------------------------------
        $config['upload_path'] = "assets/cpanel/Membresia/images/"; //ruta donde carga el archivo
        $config['file_name'] = time(); //nombre temporal del archivo
        $config['allowed_types'] = "gif|jpg|jpeg|png";
        $config['overwrite'] = true; //sobreescribe si existe uno con ese nombre
        $config['max_size'] = "2000000"; //tamaño maximo de archivo
        $this->load->library('upload', $config);
        if($this->upload->do_upload('avatar_usuario')){
          $imagen = $this->upload->data()['file_name'];
        }else{
          $imagen = "";
        }
        //-----------------------------------
        $this->reglas_validacion('insert','datosTrabajadores');
        $this->mensajes_reglas();
        if($this->form_validation->run() == true){
            $data = array(
                          'id_membresia'          => $id_membresia,
                          'serial_acceso'            => $serial_acceso,
                          'id_grupo_empresarial'   => $grupo_empresarial,
                          'nombre' => $nombre,
                          'apellido_paterno'   =>  $apellido_paterno,
                          'apellido_materno' => $apellido_materno,
                          'id_genero' => $genero_registrar,
                          'id_estado_civil' => $edo_civil,
                          'nacionalidad' => $nacionalidad,
                          'fecha_nacimiento'=> trim(date("Y-m-d", strtotime($fecha_nacimiento))),
                          'curp' => $curp,
                          'pasaporte' => $pasaporte,
                          'telefono' => $telefono,
                          'correo' => $correo,
                          'id_actividad_economica' => $actividad_economica,
                          'imagen'=>$imagen,
                          'status' => true,
                          'eliminado' => false,
                          "cancelado"=> false,
                          'auditoria' => [array(
                                                    "cod_user" => $id_usuario,
                                                    "nomuser" => $this->session->userdata('nombre'),
                                                    "fecha" => $fecha,
                                                    "accion" => "Nuevo registro membresia",
                                                    "operacion" => ""
                                                )]
              );
            //var_dump($data);die('');
            $this->Membresia_model->registrar_datos_trabajadores($data);
        }else{
             // enviar los errores
            echo validation_errors();
        }
        //-----------------------------------
        //var_dump($formulario);die('');
    }
    /*
    * Actualizar trabajadores
    */
    public function actualizar_trabajadores(){
        $fecha = new MongoDB\BSON\UTCDateTime();
        
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        
        $formulario = $this->input->post();
        //var_dump($formulario);
        //---Planes:
        $id_membresia = $formulario["id_membresia_actualizar"];
        $serial_acceso = (isset($formulario["serial_acceso_moral_dt_actualizar"]))? $formulario["serial_acceso_moral_dt_actualizar"]: "";
        $grupo_empresarial = (isset($formulario["grupo_empresarial_dt_actualizar"]))? $formulario["grupo_empresarial_dt_actualizar"]: "";
        $nombre = (isset($formulario["nombre_dt_actualizar"]))? $formulario["nombre_dt_actualizar"]: "";
        $apellido_paterno = (isset($formulario["apellido_paterno_moral_dt_actualizar"]))? $formulario["apellido_paterno_moral_dt_actualizar"]: "";
        $apellido_materno = (isset($formulario["apellido_materno_dt_actualizar"]))? $formulario["apellido_materno_dt_actualizar"]: "";
        $genero = (isset($formulario["genero_dt_actualizar"]))? $formulario["genero_dt_actualizar"]: "";
        $edo_civil = (isset($formulario["edo_civil_dt_actualizar"]))? $formulario["edo_civil_dt_actualizar"]: "";
        $nacionalidad = (isset($formulario["nacionalidad_dt_actualizar"]))? $formulario["nacionalidad_dt_actualizar"]: "";
        $fecha_nacimiento = (isset($formulario["fecha_nacimiento_dt_actualizar"]))? $formulario["fecha_nacimiento_dt_actualizar"]: "";
        $curp = (isset($formulario["curp_dt_actualizar"]))? $formulario["curp_dt_actualizar"]: "";
        $pasaporte = (isset($formulario["pasaporte_dt_actualizar"]))? $formulario["pasaporte_dt_actualizar"]: "";
        $telefono =  (isset($formulario["telefono_dt_actualizar"]))? $formulario["telefono_dt_actualizar"]: "";
        $correo =  (isset($formulario["correo_dt_actualizar"]))? $formulario["correo_dt_actualizar"]: "";
        $actividad_economica =  (isset($formulario["actividad_economica_dt_actualizar"]))? $formulario["actividad_economica_dt_actualizar"]: "";
        /*
        * Bloque para imagenes...
        */
        $config['upload_path'] = "assets/cpanel/Membresia/images/"; //ruta donde carga el archivo
        $config['file_name'] = time(); //nombre temporal del archivo
        $config['allowed_types'] = "gif|jpg|jpeg|png";
        $config['overwrite'] = true; //sobreescribe si existe uno con ese nombre
        $config['max_size'] = "2000000"; //tamaño maximo de archivo
        $this->load->library('upload', $config);
        if($this->upload->do_upload('avatar_usuario')){
          $imagen = $this->upload->data()['file_name'];
        }else{
          $imagen = "";
        }
        //var_dump($imagen);die('');
        //---
        $this->reglas_validacion('update','datosTrabajadores');
        $this->mensajes_reglas();
        if($this->form_validation->run() == true){
            if($imagen!=""){
                $data = array(
                          'trabajadores.$.serial_acceso'            => $serial_acceso,
                          'trabajadores.$.id_grupo_empresarial'   => $grupo_empresarial,
                          'trabajadores.$.nombre' => $nombre,
                          'trabajadores.$.apellido_paterno'   =>  $apellido_paterno,
                          'trabajadores.$.apellido_materno' => $apellido_materno,
                          'trabajadores.$.id_genero' => $genero,
                          'trabajadores.$.id_estado_civil' => $edo_civil,
                          'trabajadores.$.nacionalidad' => $nacionalidad,
                          'trabajadores.$.fecha_nacimiento'=> trim(date("Y-m-d", strtotime($fecha_nacimiento))),
                          'trabajadores.$.curp' => $curp,
                          'trabajadores.$.pasaporte' => $pasaporte,
                          'trabajadores.$.telefono' => $telefono,
                          'trabajadores.$.correo' => $correo,
                          'trabajadores.$.id_actividad_economica' => $actividad_economica,
                          'trabajadores.$.imagen' => $imagen,
                );
            }else{
                $data = array(
                          'trabajadores.$.serial_acceso'            => $serial_acceso,
                          'trabajadores.$.id_grupo_empresarial'   => $grupo_empresarial,
                          'trabajadores.$.nombre' => $nombre,
                          'trabajadores.$.apellido_paterno'   =>  $apellido_paterno,
                          'trabajadores.$.apellido_materno' => $apellido_materno,
                          'trabajadores.$.id_genero' => $genero,
                          'trabajadores.$.id_estado_civil' => $edo_civil,
                          'trabajadores.$.nacionalidad' => $nacionalidad,
                          'trabajadores.$.fecha_nacimiento'=> trim(date("Y-m-d", strtotime($fecha_nacimiento))),
                          'trabajadores.$.curp' => $curp,
                          'trabajadores.$.pasaporte' => $pasaporte,
                          'trabajadores.$.telefono' => $telefono,
                          'trabajadores.$.correo' => $correo,
                          'trabajadores.$.id_actividad_economica' => $actividad_economica,
                );
            }
            
            //var_dump($data);die('');
            $where_array = array(
                                  'trabajadores.id_membresia' =>$id_membresia ,
                                  'trabajadores.serial_acceso'=>$serial_acceso,
                                  'trabajadores.eliminado'=>false
                                );
            $this->Membresia_model->actualizar_trabajadores($where_array,$data);    
        }
    }
    /*
    * Eliminar datos de trabajadores
    */
    public function eliminar_datos_trabajador(){
        $id = $this->input->post('id');
        //var_dump($id);die('');
        $this->Membresia_model->eliminar($id, 'datos_trabajadores');
    }
    /*
    * Status datos de trabajador
    */
    public function status_datos_trabajador(){
        $id = $this->input->post('id');
        $status = $this->input->post('status'); 
        $this->Membresia_model->status_datos_trabajador($id,$status);
        echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
    }
    /*
    *   Cancelar membresia
    */
    public function cancelar_membresia(){
        $id_membresia = $this->input->post('id');
        $numero_renovacion = $this->input->post('status');
        //Verifico si tiene jornada activa
        $tiene_jornada = $this->Membresia_model->consultar_existe_jornada($id_membresia,$numero_renovacion);
        if($tiene_jornada>0){
            echo "<span>¡Esta membresía cuenta con jornada activa!</span>";
            die('');
        }
        //Verificio si tiene reservaciones activa
        $tiene_reserva = $this->Membresia_model->consultar_existe_reserva($id_membresia,$numero_renovacion);
        if($tiene_reserva>0){
            echo "<span>¡Esta membresía cuenta con reserva activa!</span>";
            die('');
        }

        $status = true; 
        $this->Membresia_model->cancelar_membresia($id_membresia,$status);
        echo json_encode("<span>Cambios realizados exitosamente!</span>");
    }
    /*
    * Status multiples datos de trabajador
    */
    public function status_multiple_datos_trabajadores(){
        $id = $this->input->post('id');
        $status = $this->input->post('status'); 
        $this->Membresia_model->status_multiple_datos_trabajador($id,$status);
        echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
    }
    /*
    *   Eliminar multiple
    */
    public function eliminar_multiple_datos_trabajador(){
        $this->Membresia_model->eliminar_multiple_datos_trabajador($this->input->post('id'));
    }
   /*---------------------------------------------------------------------------------------*/
}      