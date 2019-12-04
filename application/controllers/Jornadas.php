<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Jornadas extends CI_Controller
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
        $this->load->model('Jornadas_model');

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
        $data['planes'] = $this->Paquetes_model->listado_planes();
        //----
        $datos['permiso'] = $this->Menu_model->verificar_permiso_vista('jornadas', $this->session-> userdata('id_rol'));
        $datos['breadcrumbs'] = $this->Menu_model->breadcrumbs('jornadas');
        //$datos['actividadesEconomicas'] = $this->ClientePagador_model->armarSelect('actividadEconomica');
        $datos['membresia'] = $this->Jornadas_model->listado_membresia();

        //$datos['actividadesEconomicas'] = $this->armarSelect('actividadEconomica');
             
        $data['modulos'] = $this->Menu_model->modulos();

        $data['modulos'] = (array)$data['modulos'];

        $data['vistas'] = $this->Menu_model->vistas($this->session-> userdata('id_rol'));

        $datos['servicios_recargos'] = $this->Jornadas_model->listado_servicios_recargos();

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
        $datos["id_membresia"] = "";
        $data['modulos_vistas'] = $oneDim;
        $this->load->view('cpanel/header');
        $this->load->view('cpanel/menu', $data);
        $this->load->view('catalogo/Jornadas/index', $datos);
        $this->load->view('cpanel/footer');
    }
    /*
    *   Metodo que recibe el id de cliente pagador
    */
    public function from_to_membresia($id_membresia){

        //--Cambio con servicio ag2
        //var_dump($id_cliente_pagador);
        //die('Error 955: Взяв под свой контроль свою команду ....');
        $data['planes'] = $this->Paquetes_model->listado_planes();
        //----
        $datos['permiso'] = $this->Menu_model->verificar_permiso_vista('jornadas', $this->session-> userdata('id_rol'));
        $datos['breadcrumbs'] = $this->Menu_model->breadcrumbs('jornadas');
        //$datos['actividadesEconomicas'] = $this->ClientePagador_model->armarSelect('actividadEconomica');
        $datos['membresia'] = $this->Jornadas_model->listado_membresia();

        //$datos['actividadesEconomicas'] = $this->armarSelect('actividadEconomica');
             
        $data['modulos'] = $this->Menu_model->modulos();

        $data['modulos'] = (array)$data['modulos'];

        $data['vistas'] = $this->Menu_model->vistas($this->session-> userdata('id_rol'));

        $datos['servicios_recargos'] = $this->Jornadas_model->listado_servicios_recargos();

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
        $datos["id_membresia"] = $id_membresia;
        $data['modulos_vistas'] = $oneDim;
        $this->load->view('cpanel/header');
        $this->load->view('cpanel/menu', $data);
        $this->load->view('catalogo/Jornadas/index', $datos);
        $this->load->view('cpanel/footer');
    }
    /*---------------------------------------------------------------------------------------*/
    /*
    *   Metodo que arma los select
    */
    /*public function armarSelect($tipo) 
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
    }*/
    /*
    *   Metodo para realizar la consulta de planes
    */
    public function consultarPlan(){
        $id_plan = $this->input->post("id_plan");
        $planes = $this->Jornadas_model->buscarPlanes($id_plan);
        echo(json_encode($planes));
    }
    /*
    *
    */
    public function listado_jornadas(){
        $listado = [];
        $listado2 = [];
        $listado = $this->Jornadas_model->listado_jornadas();
        foreach ($listado as $value) {
            $arreglo_data = $value;
            /*$grupo_empresarial = consumir_rest('Lista_Valor','consultar', array('id_lista_valor'=>$value["id_grupo_empresarial"]));
            ($value["id_grupo_empresarial"]!="") ? $arreglo_data["grupo_empresarial"] = $grupo_empresarial->data[0]->nombre_lista_valor:$arreglo_data["grupo_empresarial"] = "";*/
                       
            $listado2[] = $arreglo_data;
        }
        echo json_encode($listado2);
    } 
    /*
    * Consulta de datos de membresia
    */
    public function consultarMembresia(){
        $formulario = $this->input->post();
        if(isset($formulario["id_jornadas"])){
            $id_jornadas = $formulario["id_jornadas"];
        }else{
            $id_jornadas = "";
        }
        $membresia = $this->Jornadas_model->listado_membresia_filtro($formulario["id_membresia"],$id_jornadas);
        echo(json_encode($membresia));
    }
    
    
    /*
    *   MEtodo que registra la membresia
    */
    public function registrar_jornadas(){

        $fecha = new MongoDB\BSON\UTCDateTime();
        $fecha2 = date('Y-m-d g:i a');
        $fecha_jornada = strtotime($fecha2);
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        
        $formulario = $this->input->post();
        
        $id_membresia = (isset($formulario["cliente_jornada_registrar"]))? $formulario["cliente_jornada_registrar"]: "";
        //---
        #Modificacion 31052019: consulto el numero de renovacion
        $numero_renovacion = $this->Jornadas_model->consultarNrenovacion($id_membresia );
        //---
        $this->reglas_validacion();
    
        if($this->form_validation->run() == true){
          $data = array(
                          
                          'id_membresia' => $id_membresia,
                          'numero_renovacion' => $numero_renovacion,
                          'fecha_hora_inicio' =>  $fecha_jornada,
                          'fecha_hora_fin' => 'Sin salir',
                          'status' => true,
                          'eliminado' => false,
                          'servicios'=>[],
                          'monto_pagar'=>0,
                          'monto_total_recargo'=>0,
                          'auditoria' => [array(
                                                    "cod_user" => $id_usuario,
                                                    "nomuser" => $this->session->userdata('nombre'),
                                                    "fecha" => $fecha,
                                                    "accion" => "Nuevo registro membresia",
                                                    "operacion" => ""
                                                )]
          );
          $this->Jornadas_model->registrar_jornadas($data);
        }else{
             // enviar los errores
            echo validation_errors();
        }
        //----------------------------------------------------------------------
    }
    /*
    * Metodo que actualiza la membresia
    */
    /*public function actualizar_membresia(){
        $fecha = new MongoDB\BSON\UTCDateTime();
        
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        
        $formulario = $this->input->post();
        //var_dump($formulario);die('');

        //---Planes:
        $plan = (isset($formulario["plan_membresia_actualizar"]))? $formulario["plan_membresia_actualizar"]: "";
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
       
        $fecha_fin_c = $vector_fecha_fin[0]."-".$mes_numero_fin."-".$vector_fecha_fin["2"];
        
        //---
        if($tipo_persona=="fisica"){
          $this->reglas_validacion('update','datosBasicosFisica');
        }else if($tipo_persona=="moral"){
            $this->reglas_validacion('update','datosBasicosMoral');
            
        }
        if($this->form_validation->run() == true){
            $data = array(
                          'id_membresia' => $formulario["id_membresia_actualizar"],
                          'plan' => $plan,
                          'fecha_inicio' => trim(date("Y-m-d", strtotime($fecha_inicio_c))),
                          'fecha_fin' =>trim(date("Y-m-d", strtotime($fecha_fin_c))),
                          'valor'=> $valor,
                          'status' => true,
                          'eliminado' => false,
            );
            $this->Membresia_model->actualizar_membresia($data);    
        }

    }*/
    public function reglas_validacion(){
        $this->form_validation->set_rules('cliente_jornada_registrar','Identificación (Prospecto/CLiente)','required');
    }
    
    
    /*
    *   Status Membresia
    */
    public function status_jornadas(){
        $id = $this->input->post('id');
        $status = $this->input->post('status'); 
        $this->Jornadas_model->status($id,$status, 'jornadas');
        echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
    }
    /*
    * Eliminar membresia
    */
    public function eliminar (){
        $id = $this->input->post('id');
        //--Verifico que el cliente no tenga prospecto...
        /*$prospecto = $this->Membresia_model->buscarProspecto($id);
        if (count($prospecto)>0){
          echo ("<span>El cliente NO se puede eliminar ya que tiene un prospecto asociado!</span>");
          die('');
        }
        //---
        $consulta = $this->Membresia_model->consultaCarteraCliente($id);
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
        $this->Jornadas_model->status_multiple_jornadas($this->input->post('id'), $this->input->post('status'));
        echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
    }
    /*------------------------------------------------------------------------------------------------*/
    //-Metodos de datos trabajadores...
    /*
    *  Index de datos trabajadores
    */
    public function recargos($id_membresia="",$editable =""){

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
        $this->load->view('catalogo/Jornadas/recargos',$datos);//$datos
        $this->load->view('cpanel/footer');
    }
    /*
    *   Registrar recoargos
    */
    public function registrarRecargos(){
        $fecha = new MongoDB\BSON\UTCDateTime();
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        $formulario = $this->input->post();
        //var_dump($formulario);die('');
        $id_membresia = $formulario["id_membresia"];
        $id_jornada = $formulario["id_jornada"];
        $id_jornada_mongo =  new MongoDB\BSON\ObjectId($id_jornada);
        //---Registro montos en la jornada que existe...
        /*$data_montos = array(
                          'monto_total_recargo'=> number_format($formulario["monto_total_recargo"],2,".",","),
                          'monto_pagar'=> number_format($formulario["monto_pagar"],2,".",","),
        );*/
        $data_montos = array(
                          'monto_total_recargo'=> number_format($formulario["monto_total_recargo"],2,".",""),
                          'monto_pagar'=> number_format($formulario["monto_pagar"],2,".",""),
        );
        $where_array_montos = array(
                                      '_id'=>$id_jornada_mongo 
                                );
        $this->Jornadas_model->actualizar_montos($where_array_montos,$data_montos);
        //---Para servicios opcionales
        $servicios_opcionales = $formulario["arreglo_servicios_opcionales"];
        $arreglo_servicios_opcionales = explode("*",$servicios_opcionales);
        if($arreglo_servicios_opcionales[0]!=0){
            foreach ($arreglo_servicios_opcionales as $clave_serv_op => $valor_serv_op) {
                $fila_servicios_op = explode("|",$valor_serv_op);
                $data = array(
                              'id_servicio'=> $fila_servicios_op[0],
                              'cantidad'=> $fila_servicios_op[2],
                              'monto_individual'=> number_format($fila_servicios_op[1],2,".",""),
                              'monto_total'=> number_format($fila_servicios_op[2]*$fila_servicios_op[1],2,".",""),
                              'tipo'=>'opcional',
                              'estatus'=>true,
                              'eliminado'=>false,
                              'auditoria' => [array(
                                                        "cod_user" => $id_usuario,
                                                        "nomuser" => $this->session->userdata('nombre'),
                                                        "fecha" => $fecha,
                                                        "accion" => "Nuevo registro servicio",
                                                        "operacion" => ""
                                                    )]
                );
                $where_array = array(
                                      'id_membresia' =>$id_membresia,
                                      '_id'=>$id_jornada_mongo 
                                    );
                $this->Jornadas_model->actualizar_servicios_jornadas($where_array,$data);    
            }
        }
       
        //--Para servicios contratados
        $servicios_contratados = $formulario["arreglo_servicios_contratados"];
        $arreglo_servicios_contratados = explode("*",$servicios_contratados);
        if($arreglo_servicios_contratados[0]!=0){
            foreach ($arreglo_servicios_contratados as $clave_serv_contratados => $valor_serv_contratados) {
                $fila_servicios_cont = explode("|",$valor_serv_contratados);
                $data = array(
                              'id_servicio'=> $fila_servicios_cont[0],
                              'cantidad'=> $fila_servicios_cont[2],
                              'monto_individual'=> number_format($fila_servicios_cont[1],2,".",""),
                              'monto_total'=> number_format($fila_servicios_cont[2]*$fila_servicios_cont[1],2,".",""),
                              'tipo'=>'contratados',
                              'estatus'=>true,
                              'eliminado'=>false,
                              'auditoria' => [array(
                                                        "cod_user" => $id_usuario,
                                                        "nomuser" => $this->session->userdata('nombre'),
                                                        "fecha" => $fecha,
                                                        "accion" => "Nuevo registro servicio",
                                                        "operacion" => ""
                                                    )]
                );
                $where_array = array(
                                      'id_membresia' =>$id_membresia,
                                      '_id'=>$id_jornada_mongo 
                                    );
                $this->Jornadas_model->actualizar_servicios_jornadas($where_array,$data);  
                ///---
                //--Actualizar membresia 
                /*$data_membresia = array(
                                  'servicios.$.cantidad'=> $fila_servicios_cont[3],
                );
                $where_array_membresia = array(
                                          'servicios.id_servicio' =>$fila_servicios_cont[0],
                                        );*/
                //var_dump($where_array_membresia);die('');
                //$this->Jornadas_model->actualizar_servicios_membresia($where_array_membresia,$data_membresia); 
                //--
            }  
        }
        
        
        //---
        echo json_encode("<span>Servicios asociados a esta jornada!</span>");      

    }
    /*
    *
    */
    /*
    *   Marcar Salida
    */
    public function marcarSalida(){
        $fecha = new MongoDB\BSON\UTCDateTime();
        $fecha2 = date('Y-m-d g:i a');
        $fecha_jornada = strtotime($fecha2);
        //$fecha_jornada = new MongoDB\BSON\UTCDatetime(strtotime($fecha2)*1000);
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        $formulario = $this->input->post();
        $data = array(
                        'fecha_hora_fin' =>  $fecha_jornada
                    );
        $this->Jornadas_model->marcar_salida($formulario["id_jornadas"],$data,$formulario["id_membresia"]);
        echo json_encode("<span>Se realizado el marcaje de la salida para este usuario!</span>"); // envio de mensaje exitoso
    }
    /*
    * Listado datos trabajadores
    */
    /*public function listado_datos_trabajadores($id_membresia){
        $listado = [];
        $listado = $this->Membresia_model->listado_membresia_trabajadores($id_membresia);
        foreach ($listado as $value) {
            $arreglo_data = $value;
            //--
            $grupo_empresarial = consumir_rest('Lista_Valor','consultar', array('id_lista_valor'=>$value["id_genero"]));
            ($value["id_grupo_empresarial"]!="") ? $arreglo_data["grupo_empresarial"] = $grupo_empresarial->data[0]->nombre_lista_valor:$arreglo_data["grupo_empresarial"] = "";
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
            //var_dump($actividad_economica);die('');
            ($value["id_actividad_economica"]!="") ? $arreglo_data["actividad_economica"] = $actividad_economica->data[0]->nombre_lista_valor:$arreglo_data["actividad_economica"] = "";

            $arreglo_data["id_datos_trabajadores"] = $value["serial_acceso"];

            $listado2[] = $arreglo_data;
        }
        echo json_encode($listado2);
    }*/
    /*
    * Guardar datos trabajadores
    */
    /*public function guardarTrabajador(){
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
            echo "<span>¡Ya se encuentra otra membresía cone se serial!</span>";die('');
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
    }*/
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
        //---
        $this->reglas_validacion('update','datosTrabajadores');
       
        if($this->form_validation->run() == true){
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
        var_dump($id);die('');
        $this->Membresia_model->eliminar($id, 'datos_trabajadores');
    }
    /*
    * Status datos de trabajador
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
