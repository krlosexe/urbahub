<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cotizacion extends CI_Controller {
  private $operaciones;
  function __construct(){
      parent::__construct();
      /*$this->load->database();*/
      $this->load->library('session');
      $this->load->model('Menu_model');
      $this->load->model('Cotizacion_model');
      $this->load->model('MiCorreo_model');
      $this->load->library('form_validation');
      $this->load->model('Prospecto_model');
      $this->load->model('Servicios_model');
      if (!$this->session->userdata("login")) {
        redirect(base_url());
      }
      //--
      $this->load->helper('consumir_rest');
      $this->load->helper('organizar_sepomex');
      $this->load->helper('array_push_assoc');
      //--
    }

  public function index()
  {
    //--
    $datos['clientes_fisica'] = $this->Cotizacion_model->listado_clientes('FISICA');
    $datos['clientes_moral'] = $this->Cotizacion_model->listado_clientes('MORAL');
    $datos["prospectos"]         = $this->Prospecto_model->listarProspecto();
 

        $datos['planes'] = $this->Cotizacion_model->listado_planes();
        $datos['paquetes'] = $this->Cotizacion_model->listado_paquetes();
        $vendedores = $this->Cotizacion_model->getVendedores();
        $datos['vendedores'] = $vendedores;
        $datos['inscripcion'] = $this->Cotizacion_model->consultarMontoInscripcion();
    //--
    $datos['permiso']       = $this->Menu_model->verificar_permiso_vista('Cotizacion', $this->session->userdata('id_rol'));
      $data['modulos']        = $this->Menu_model->modulos();
      $data['vistas']         = $this->Menu_model->vistas($this->session-> userdata('id_rol'));
      $datos['breadcrumbs']   = $this->Menu_model->breadcrumbs('Cotizacion');
      
      $this->operaciones      = $this->menu_rol_operaciones->control($data['modulos'], $data['vistas']);
      $data['modulos_vistas'] = $this->operaciones;

      $this->load->view('cpanel/header');
      $this->load->view('cpanel/menu', $data);
      $this->load->view('ventas/Cotizacion/index', $datos);
      $this->load->view('cpanel/footer');
      
  }
  /*
  * Listado de cotizaciones
  */
   public function listado_cotizacion(){
      $listado = [];
      $listado2 = [];
      $listado = $this->Cotizacion_model->listado_cotizacion();
      foreach ($listado as $value) {
          $arreglo_data = $value;                       
          $listado2[] = $arreglo_data;
      }
      echo json_encode($listado2);
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
            $rfc_cliente = $this->input->post("rfc_cotizacion_registrar_fisica");
        }else{

            $rfc_cliente = $this->input->post("rfc_cotizacion_registrar_moral");
        }
        //$this->input->post()
        //var_dump($this->input->post());die('xxx');
        if($rfc_cliente!=""){
            $listado = $this->Cotizacion_model->consultarClientePagadorRfc($rfc_cliente,$tipo_persona);
            //var_dump($listado);die('');
            if(!$listado[0]["error"]){
            //------------------------------------
                foreach ($listado as $value) {
                  //Consumo el servicio segun el id del usuario
                  $arreglo_data = $value;
                  //unset($listado[$key]);
                  //Hago push assoc de la fila de usuario y sus datos respectivos en sepomex
                  $listado2[] = $arreglo_data;
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
            $listado = $this->Cotizacion_model->consultarClientePagadorRfc($rfc_cliente,$tipo_persona);
            //var_dump(($listado[0]));die('');
            if($listado[0]!=""){
            //------------------------------------
                foreach ($listado as $value) {
                  //Consumo el servicio segun el id del usuario                  
                  $arreglo_data = $value;
                  $listado2[] = $arreglo_data;
                } 
            //------------------------------------    
            }
               
        }
        echo(json_encode($listado2));
    }
    /*
    *   Metodo para realizar la consulta de paquetes
    */
    public function consultarPaquetes(){
        $id_plan = $this->input->post("id_plan");
        $paquetes = $this->Cotizacion_model->buscarPaquetes($id_plan);
        echo(json_encode($paquetes));
    }
    /*
    *   Funcion que alimenta la tabla
    */
    public function consultarPlanPaquetesTablas(){
        $id_plan = $this->input->post("id_plan");
        $id_paquete = $this->input->post("paquete");
        if($id_paquete!="")
            $planes = $this->Cotizacion_model->buscarPlanesPaquetesTabla($id_plan,$id_paquete);
        else
            $planes = "";
        echo(json_encode($planes));
    }
    /*
    * Obtener servicios segun planes
    */
    public function obtenerServicios($paquete){
        $servicios = $this->Cotizacion_model->obtenerServicios($paquete);
        return $servicios;
    }
    /*
    * Registrar cotizacion
    */
    public function registrar_cotizacion(){

        //----------------------------------------------------------------
        $fecha = new MongoDB\BSON\UTCDateTime();
        
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        
        $formulario = $this->input->post();
        //--------------------------------------------------------------
        #Obteniendo datos....
        $tipo_persona = $this->input->post("rad_tipoper");
        //--Costo del pla/paquete
        //---Para persona fisica


       

        $data_service = array();
        foreach($this->input->post("service") as $key => $value){

          $data = array(
            "service"  => $value,
            "monto"    =>  $this->input->post("monto_service")[$key],
            "cantidad" =>  $this->input->post("cantidad_service")[$key]
          );

          $data_service[] = $data;

        }



        $this->input->post('membresia') == "S" ? $membresia = true : $membresia = false;


        if($tipo_persona=="fisica"){
            //-----------------------
            
            $rfc_cliente = (isset($formulario["rfc_cotizacion_registrar_fisica"]))? $formulario["rfc_cotizacion_registrar_fisica"]: "";
            
            $correo = (isset($formulario["correo_fisica_registrar"]))? $formulario["correo_fisica_registrar"]: "";
            
            $telefono = (isset($formulario["telefono_fisica_registrar"]))? $formulario["telefono_fisica_registrar"]: "";
            
            $id_vendedor = (isset($formulario["id_vendedor"]))? $formulario["id_vendedor"]: "";
            
            $plan = (isset($formulario["plan_cotizacion_registrar_fisica"]))? $formulario["plan_cotizacion_registrar_fisica"]: "";
            
            $paquete = (isset($formulario["paquetes_cotizacion_registrar_fisica"]))? $formulario["paquetes_cotizacion_registrar_fisica"]: "";
            
            $vigencia = (isset($formulario["vigencia_registrar_fisica"]))? $formulario["vigencia_registrar_fisica"]: "";
            $cantidad_usuarios = "1";
            //--Montos:
            $monto_inscripcion = (isset($formulario["monto_inscripcion_registrar_fisica"]))? $formulario["monto_inscripcion_registrar_fisica"]: "";
            
            $monto_mensualidad_individual = (isset($formulario["monto_paquete_registrar_fisica"]))? $formulario["monto_paquete_registrar_fisica"]: "";
            
            $monto_mensualidad_total = (isset($formulario["monto_paquete_registrar_fisica"]))? $formulario["monto_paquete_registrar_fisica"]: "";
            
            $monto_total = (isset($formulario["monto_total_registrar_fisica"]))? $formulario["monto_total_registrar_fisica"]: "";
            //---
            $this->reglas_validacion('insert','datosBasicosFisica');

        }else if($this->input->post("rad_tipoper")=="moral"){  



        //---Para persona moral
            $rfc_cliente = (isset($formulario["rfc_cotizacion_registrar_moral"]))? $formulario["rfc_cotizacion_registrar_moral"]: "";
            
            $correo = (isset($formulario["correo_moral_registrar"]))? $formulario["correo_moral_registrar"]: "";
            
            $telefono = (isset($formulario["telefono_moral_registrar"]))? $formulario["telefono_moral_registrar"]: "";
            
            $id_vendedor = (isset($formulario["id_vendedor_moral"]))? $formulario["id_vendedor_moral"]: "";
            
            $plan = (isset($formulario["plan_cotizacion_registrar_fisica"]))? $formulario["plan_cotizacion_registrar_fisica"]: "";
            
            $paquete = (isset($formulario["paquetes_cotizacion_registrar_fisica"]))? $formulario["paquetes_cotizacion_registrar_fisica"]: "";
            
            $vigencia = (isset($formulario["vigencia_registrar_fisica"]))? $formulario["vigencia_registrar_fisica"]: "";
            $cantidad_usuarios = "1";
            //--Montos:
            $monto_inscripcion = (isset($formulario["monto_inscripcion_registrar_fisica"]))? $formulario["monto_inscripcion_registrar_fisica"]: "";
            
            $monto_mensualidad_individual = (isset($formulario["monto_paquete_registrar_fisica"]))? $formulario["monto_paquete_registrar_fisica"]: "";
            
            $monto_mensualidad_total = (isset($formulario["monto_paquete_registrar_fisica"]))? $formulario["monto_paquete_registrar_fisica"]: "";
            
            $monto_total = (isset($formulario["monto_total_registrar_fisica"]))? $formulario["monto_total_registrar_fisica"]: "";
            //---
            
            $this->reglas_validacion('insert','datosBasicosMoral');



               

        }
        $this->mensajes_reglas_cotizacion();



        


        //-------------------------------------------------------------
        #Armo el arreglo de servicios

        //$arr_Serv =  $this->obtenerServicios($paquete);
        $servicios = $arr_Serv["servicios_n"];
        $servicios_c = $arr_Serv["servicios_c"];
        #Obtengo el numero de cotizacion
        $numero_cotizacion = $this->Cotizacion_model->obtener_numero_cotizacion();


        $planes            = $this->input->post('plan_id');
        $paquetes          = $this->input->post('paquete_id');
        $plazos            = $this->input->post('plazos');
        $cant_trabajadores = $this->input->post('cant_trabajadores');

        $data_plan = array();
        foreach ($planes as $key => $value) {
            $data_array = array();

            $data_array["id_plan"]           = $value;
            $data_array["id_paquete"]        = $paquetes[$key];
            $data_array["plazo"]             = $plazos[$key];
            $data_array["cant_trabajadore"]  = $cant_trabajadores[$key];

            $data_plan[] = $data_array;
        }

        //--------------------------------------------------------------
        #Armando el arreglo para realizar el proceso de guardar
        if($this->form_validation->run() == true){
          $data = array(
                          'numero_cotizacion'               => $numero_cotizacion,
                          'tipo_persona'                    => $tipo_persona,
                          'identificador_prospecto_cliente' => $rfc_cliente,
                          'id_vendedor'                     => $id_vendedor,
                          //'correo' => $correo,
                          'telefono'                        => $telefono,
                          'plan'                            => $plan,
                          'paquete'                         => $paquete,
                          'vigencia'                        => $vigencia,
                          'fecha_cotizacion'                => $fecha,
                          'membresia'                       => $membresia,
                          'servicios'                       => $servicios,
                          'servicios_c'                     => $servicios_c,
                          "cantidad_usuarios"               => $cantidad_usuarios,
                          "monto_inscripcion"               => str_replace(',', '', $monto_inscripcion),
                          "monto_mensualidad_individual"    => str_replace(',', '', $monto_mensualidad_individual),
                          "monto_mensualidad_total"         => str_replace(',', '', $monto_mensualidad_total),
                          "monto_total"                     => str_replace(',', '', $monto_total),
                          "data_plan"                       => $data_plan,
                          "data_service"                    => $data_service,
                          "condicion"                       => "COTIZACION",
                          'status'                          => true,
                          'eliminado'                       => false,
                          'auditoria'                       => [array(
                                                                "cod_user" => $id_usuario,
                                                                "nomuser" => $this->session->userdata('nombre'),
                                                                "fecha" => $fecha,
                                                                "accion" => "Nuevo registro cotizacion",
                                                                "operacion" => ""
                                                            )]
          );

          $this->Cotizacion_model->registrar_cotizacion($data);
        }else{
            echo validation_errors();
        }  
        //---------------------------------------------------------------
    }   





    public function GetDataPlan()
    {
      $id_plan = $this->input->post('id_plan');
      $plan    = $this->Cotizacion_model->getPlan($id_plan);

      $id_paquete = $this->input->post('id_paquete');
      $paquete    = $this->Cotizacion_model->getPaquete($id_paquete);


      $data = array('plan' => $plan[0], 'paquete' => $paquete[0]);
      
      echo json_encode($data);
    }
    /*
    * Actualizar cotizacion
    */
    public function actualizar_cotizacion(){



      $fecha = new MongoDB\BSON\UTCDateTime();
      $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
      $formulario = $this->input->post();
      ///SI va aactualizar
     //--------------------------------------------------------------
      #Obteniendo datos....
      $tipo_persona = $this->input->post("rad_tipoperE");
      //--Costo del pla/paquete
      $id_cotizacion = $formulario["id_cotizacion_actualizar"];
      //---Para persona fisica
      if($tipo_persona=="fisica"){
          //-----------------------
          
          $id_vendedor = (isset($formulario["id_vendedor_actualizar"]))? $formulario["id_vendedor_actualizar"]: "";
          
          $plan = (isset($formulario["plan_cotizacion_actualizar_fisica"]))? $formulario["plan_cotizacion_actualizar_fisica"]: "";
          
          $paquete = (isset($formulario["paquetes_cotizacion_actualizar_fisica"]))? $formulario["paquetes_cotizacion_actualizar_fisica"]: "";
          
          $vigencia = (isset($formulario["vigencia_actualizar_fisica"]))? $formulario["vigencia_actualizar_fisica"]: "";
          $cantidad_usuarios = "1";
          //--Montos:
          $monto_inscripcion = (isset($formulario["monto_inscripcion_actualizar_fisica"]))? $formulario["monto_inscripcion_actualizar_fisica"]: "";
          
          $monto_mensualidad_individual = (isset($formulario["monto_paquete_actualizar_fisica"]))? $formulario["monto_paquete_actualizar_fisica"]: "";
          
          $monto_mensualidad_total = (isset($formulario["monto_paquete_actualizar_fisica"]))? $formulario["monto_paquete_actualizar_fisica"]: "";
          
          $monto_total = (isset($formulario["monto_total_actualizar_fisica"]))? $formulario["monto_total_actualizar_fisica"]: "";
          //---
          $this->reglas_validacion('update','datosBasicosFisica');

      }else if($this->input->post("rad_tipoperE")=="moral"){        
      //---Para persona moral          
          $id_vendedor = (isset($formulario["id_vendedor_moral_actualizar"]))? $formulario["id_vendedor_moral_actualizar"]: "";
          
          $plan = (isset($formulario["plan_cotizacion_actualizar_fisica"]))? $formulario["plan_cotizacion_actualizar_fisica"]: "";
          
          $paquete = (isset($formulario["paquetes_cotizacion_actualizar_fisica"]))? $formulario["paquetes_cotizacion_actualizar_fisica"]: "";
          
          $vigencia = (isset($formulario["vigencia_actualizar_fisica"]))? $formulario["vigencia_actualizar_fisica"]: "";
          $cantidad_usuarios = "1";
          //--Montos:
          $monto_inscripcion = (isset($formulario["monto_inscripcion_actualizar_fisica"]))? $formulario["monto_inscripcion_actualizar_fisica"]: "";
          
          $monto_mensualidad_individual = (isset($formulario["monto_paquete_actualizar_fisica"]))? $formulario["monto_paquete_actualizar_fisica"]: "";
          
          $monto_mensualidad_total = (isset($formulario["monto_paquete_actualizar_fisica"]))? $formulario["monto_paquete_actualizar_fisica"]: "";
          
          $monto_total = (isset($formulario["monto_total_actualizar_fisica"]))? $formulario["monto_total_actualizar_fisica"]: "";
          //---
          $this->reglas_validacion('update','datosBasicosMoral');
          //---
      }
      $this->mensajes_reglas_cotizacion();
      //-------------------------------------------------------------
      #Armo el arreglo de servicios
    //  $arr_Serv =  $this->obtenerServicios($paquete);
      $servicios = $arr_Serv["servicios_n"];
      $servicios_c = $arr_Serv["servicios_c"];
      #Obtengo el numero de cotizacion
      //--------------------------------------------------------------
      if($this->form_validation->run() == true){


        $planes            = $this->input->post('plan_id');
        $paquetes          = $this->input->post('paquete_id');
        $plazos            = $this->input->post('plazos');
        $cant_trabajadores = $this->input->post('cant_trabajadores');


        foreach ($planes as $key => $value) {
              $data_array = array();

              $data_array["id_plan"]           = $value;
              $data_array["id_paquete"]        = $paquetes[$key];
              $data_array["plazo"]             = $plazos[$key];
              $data_array["cant_trabajadore"]  = $cant_trabajadores[$key];

              $data_plan[] = $data_array;
          }





        $data = array(
                        'id_cotizacion'                => $id_cotizacion,
                        'id_vendedor'                  => $id_vendedor,
                        'plan'                         => $plan,
                        'paquete'                      => $paquete,
                        'vigencia'                     => $vigencia,
                        'servicios'                    => $servicios,
                        'servicios_c'                  => $servicios_c,
                        "cantidad_usuarios"            => $cantidad_usuarios,
                        "monto_inscripcion"            => str_replace(',', '', $monto_inscripcion),
                        "monto_mensualidad_individual" => str_replace(',', '',$monto_mensualidad_individual),
                        "monto_mensualidad_total"      => str_replace(',', '',$monto_mensualidad_total),
                        "monto_total"                  => str_replace(',', '',$monto_total),
                        "data_plan"                    => $data_plan,
                        'status'                       => true,
                        'eliminado'                    => false
        );



       
        $actualizar = $this->Cotizacion_model->actualizar_cotizacion($data);
        if($actualizar){
            echo json_encode("<span>La cotización se ha editado exitosamente!</span>");
        } 
      } else{
          echo validation_errors();
      }
    //------------------------------------------------------------------------------- 
    }
    /*
    * Imprimir cotizacion
    */
    public function imprimir_cotizacion($id_cotizacion,$proceso){
        $this->generarPDF($id_cotizacion,$proceso);
    }
    /*
    * Generar pdf
    */
    public function generarPDF($id_cotizacion,$proceso){
        $fecha = new MongoDB\BSON\UTCDateTime();
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        #Consulto la cotizacion
        $temp_correo['arreglo_datos'] = $this->Cotizacion_model->buscar($id_cotizacion);
        
        $data_planes = array();
        foreach ($temp_correo["arreglo_datos"][0]["data_plan"] as $key => $value) {


          $id_plan = $value->id_plan;
          $plan    = $this->Cotizacion_model->getPlan($id_plan);
          
          $id_paquete = $value->id_paquete;
          $paquete    = $this->Cotizacion_model->getPaquete($id_paquete);


          $services = array();
           foreach ($paquete[0]["servicios"] as $key => $value) {
             $servicios = $this->Cotizacion_model->getDataServicios($value->id_servicios);
           //  echo json_encode($servicios[0])."<br><br>";

             $servcies[] = $servicios[0];
           }

           $paquete[0]["servicios"]["data_service"] = $servcies;
           
           $data = array('plan' => $plan[0], 'paquete' => $paquete[0]);

          $data_planes[] = $data;
        }


        // $data = array('plan' => $plan[0], 'paquete' => $paquete[0]);


        if(count($temp_correo['arreglo_datos']) > 0){
            
            $temp_correo['arreglo_datos'] = $temp_correo['arreglo_datos'][0];

             $temp_correo["arreglo_datos"]["data_planes"] = $data_planes;
            //var_dump($temp_correo['arreglo_datos']);die('');
            #Consulto los planes
            $temp_correo['arreglo_datos_planes'] = $this->Cotizacion_model->buscar_plan($temp_correo['arreglo_datos']['plan']);
            #Consulto los servicios
            $temp_correo['arreglo_datos_servicios'] = $this->Cotizacion_model->buscar_servicios($id_cotizacion,$temp_correo['arreglo_datos']["servicios"],$temp_correo['arreglo_datos']["servicios_c"]);
            #Cargo liobreria pdf
            $this->load->library('libdompdf');
            #Cargo la vista 
            $html = $this->load->view('pdf/formato', $temp_correo, true);
            $nombre_pdf = date("YmdHis")."_".$id_cotizacion.".pdf";
            $final_ruta = "assets/outpdf/";
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
              $final_ruta = "assets\\outpdf\\";
            }
            

            //$this->libdompdf->->set_option("isPhpEnabled", true);
            $this->libdompdf->load_view($html, $nombre_pdf, array(''), str_replace("application\\", "", str_replace("application/", "", APPPATH)).$final_ruta);
            //----
            if($proceso=="email"){
              $rest_correo = 0;
              /*if($this->MiCorreo_model->enviar_correo("Cotización UrbanHub", "Estmiado Sr(a) ".$temp_correo['arreglo_datos']["nombre_prospecto"]." utilizamos esta vía para hacerle llegar el detalle de la cotización: ".'<a href="'.base_url().'assets/outpdf/'.$nombre_pdf.'">'.base_url().'assets/outpdf/'.$nombre_pdf.'</a>', $temp_correo['arreglo_datos']["correo"], $temp_correo['arreglo_datos']["nombre_prospecto"])){
                    $rest_correo = 1;
              }*/
              ///-------------------------------------------------------------------------
              $this->load->library('email');
              //$htmlContent = '<h1>HTML email testing by CodeIgniter Email Library</h1>';
              $htmlContent = "Estimado Sr(a) ".$temp_correo['arreglo_datos']["nombre_prospecto"]." utilizamos esta vía para hacerle llegar el detalle de la cotización:";
              
              $config['mailtype'] = 'html';
              $res = $this->MiCorreo_model->buscar_mi_correo();
                //var_dump($res);die('');
                if(count($res) > 0){
                  $res = $res[0];
                $correo_remitente = $res["usuario"];
                //$correo_remitente = "info@urbanhub.com.mx";
                  if(!empty($res["correo"])){
                    if($res["correo"] != ""){
                      $correo_remitente = $res["correo"];
                    }
                  }

                  //$nombre_remitente = "CRMUrbanHub";
                  if(!empty($res["nombre"])){
                    if($res["nombre"]!= ""){
                      $nombre_remitente = $res["nombre"];
                    }
                  }
              } 
              $this->email->initialize($config);
              $this->email->from($correo_remitente, $nombre_remitente);
              $this->email->to($temp_correo['arreglo_datos']["correo"], $temp_correo['arreglo_datos']["nombre_prospecto"]);
              $this->email->subject('Cotización');
              $this->email->message($htmlContent);
              $this->email->attach('assets/outpdf/'.$nombre_pdf);
              if ($this->email->send()) {
                  $rest_correo = 1;
              }else{
                  return false;
              }
              /*var_dump($rest_correo);echo "<br>";
              var_dump($correo_remitente);echo "<br>";
              var_dump($nombre_remitente);echo "<br>";
              var_dump($temp_correo['arreglo_datos']["correo"]);echo "<br>";
              var_dump($temp_correo['arreglo_datos']["nombre_prospecto"]);echo "<br>";
              die('');*/
              ///--------------------------------------------------------------------------

              $this->Cotizacion_model->registrar_pdf(array(
                                    'nombre_pdf_cotizacion' => $nombre_pdf,
                                    'id_cotizacion' => $id_cotizacion,
                                    'estado_envio' => $rest_correo,
                                    'status' => true,
                                    'eliminado' => false,
                                    'auditoria' => [array(
                                                              "cod_user" => $id_usuario,
                                                              "nomuser" => $this->session->userdata('nombre'),
                                                              "fecha" => $fecha,
                                                              "accion" => "Nuevo registro  pdf cotizacion",
                                                              "operacion" => ""
                                                          )]
                                  ));
              echo "<script>window.close();</script>";
               
            }
            else{
              echo "<script>window.open('".base_url().'assets/outpdf/'.$nombre_pdf."', '_self');</script>";
            }
            return true;
          //----
        }        
      return false;
    }
    /*
    * Reglas validacion
    */ 
    public function reglas_validacion($method, $tipo){
      switch ($tipo) {
            case 'datosBasicosFisica':  
                if($method=="insert"){
                    // Reglas para la tabla de cliente
                    $this->form_validation->set_rules('rad_tipoper','Tipo persona','required');
                    $this->form_validation->set_rules('rfc_cotizacion_registrar_fisica','Identificación (Prospecto/CLiente)','required');
                    $this->form_validation->set_rules('id_vendedor','Id Vendedor','required');
                    //$this->form_validation->set_rules('plan_cotizacion_registrar_fisica','Planes','required');
                   // $this->form_validation->set_rules('paquetes_cotizacion_registrar_fisica','Paquetes','required');
                   // $this->form_validation->set_rules('vigencia_registrar_fisica','Vigencia','required');
                  //  $this->form_validation->set_rules('monto_inscripcion_registrar_moral','Monto Inscripción','required');
                    // $this->form_validation->set_rules('monto_paquete_registrar_fisica','Monto Mensualidad','required');
                    // $this->form_validation->set_rules('monto_total_registrar_fisica','Monto Total','required');
                    //
                }else
                if($method=="update"){
                   // Reglas para la tabla de cliente
                    $this->form_validation->set_rules('rad_tipoperE','Tipo persona','required');
                    $this->form_validation->set_rules('id_vendedor_actualizar','Id Vendedor','required');
                    // $this->form_validation->set_rules('plan_cotizacion_actualizar_fisica','Planes','required');
                    // $this->form_validation->set_rules('paquetes_cotizacion_actualizar_fisica','Paquetes','required');
                    // $this->form_validation->set_rules('vigencia_actualizar_fisica','Vigencia','required');
                   // $this->form_validation->set_rules('monto_inscripcion_actualizar_fisica','Monto Inscripción','required');
                    // $this->form_validation->set_rules('monto_paquete_actualizar_fisica','Monto Mensualidad','required');
                    // $this->form_validation->set_rules('monto_total_actualizar_fisica','Monto Total','required');    
                    //
                    //
                }
                break;
            case 'datosBasicosMoral':
                if($method=="insert"){
                   // Reglas para la tabla de cliente
                    $this->form_validation->set_rules('rad_tipoper','Tipo persona','required');
                    $this->form_validation->set_rules('rfc_cotizacion_registrar_moral','Identificación (Prospecto/CLiente)','required');
                    $this->form_validation->set_rules('id_vendedor_moral','Id Vendedor','required');
                    // $this->form_validation->set_rules('plan_cotizacion_registrar_moral','Planes','required');
                    // $this->form_validation->set_rules('paquetes_cotizacion_registrar_moral','Paquetes','required');
                    // $this->form_validation->set_rules('vigencia_registrar_moral','Vigencia','required');
                   // // $this->form_validation->set_rules('monto_inscripcion_registrar_moral','Monto Inscripción','required');
                   //  $this->form_validation->set_rules('monto_paquete_registrar_fisica','Monto Mensualidad','required');
                   //  $this->form_validation->set_rules('monto_total_registrar_fisica','Monto Total','required');
                   //  $this->form_validation->set_rules('cantidad_trabajadores_moral','Cantidad usuarios','required');    
                    //
                    //
                }else if($method=="update"){
                   // Reglas para la tabla de cliente
                    $this->form_validation->set_rules('rad_tipoperE','Tipo persona','required');
                    $this->form_validation->set_rules('id_vendedor_moral_actualizar','Id Vendedor','required');
                    // $this->form_validation->set_rules('plan_cotizacion_actualizar_moral','Planes','required');
                    // $this->form_validation->set_rules('paquetes_cotizacion_actualizar_moral','Paquetes','required');
                    // $this->form_validation->set_rules('vigencia_actualizar_moral','Vigencia','required');
                    // $this->form_validation->set_rules('monto_inscripcion_actualizar_moral','Monto Inscripción','required');
                    // $this->form_validation->set_rules('monto_paquete_actualizar_moral','Monto Mensualidad','required');
                    // $this->form_validation->set_rules('monto_total_paquete_actualizar_moral','Monto Total','required');
                    // $this->form_validation->set_rules('cantidad_trabajadores_actualizar_moral','Cantidad usuarios','required');    
                    //
                    //
                }
                break;    
      }
    }       
    /***/    
    public function mensajes_reglas_cotizacion(){
      $this->form_validation->set_message('required', 'El campo %s es obligatorio');
      $this->form_validation->set_message('numeric', 'El campo %s debe poseer solo números');
    }
    /***/
    public function cancelar_cotizacion(){
      $formulario = $this->input->post();
      $this->Cotizacion_model->cancelar($this->input->post('id'), 'CANCELADO');
      echo json_encode("<span>Cambios realizados exitosamente!</span>");
    }
    /*
    * Cotizacion
    */
    public function aprobar_cotizacion(){
      $id_usuario       = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));  
      $paquetes         = $this->input->post("paquete");
      $id_cotizacion    = $this->input->post("id_cotizacion");
      $mensualidad      = $this->input->post("mensualidad");
      $fecha_aprobacion = $this->input->post("fecha_aprobacion");
      $fecha            = new MongoDB\BSON\UTCDateTime();
      $rs_cotizacion    = $this->Cotizacion_model->consultar_cotizacion($id_cotizacion)[0];

      $dias_prorrata = 0;
      $array_fecha_aprobacion = explode("-", $fecha_aprobacion);
      $dia_fecha_aprobacion   = $array_fecha_aprobacion[2];
      $year_fecha_aprobacion  = $array_fecha_aprobacion[0];
      $mes_fecha_aprobacion   = $array_fecha_aprobacion[1];
      
      if($dia_fecha_aprobacion > 5){
        $dias_prorrata = (date('t', strtotime($fecha_aprobacion)) - $dia_fecha_aprobacion) + 1;
      }
      

      foreach($paquetes as $key => $value){

        $numero_secuencia = 0;
        $paquete  = $this->Cotizacion_model->getPaquete($value)[0];
        $plan     = $this->Cotizacion_model->getPlan($paquete["plan"])[0];

        if($plan["id_vigencia"] == "5bb6760db5d368b23d1770f2"){
          $vigencia = "Mensual";
        }else{
          $vigencia = "Anual";
        }

        $plan["tiempo_contrato"];


        $numero_secuencia++;
        $inscripcion = array('_id'          => new MongoDB\BSON\ObjectId(),
                          "operacion"        => "",
                          "numero_secuencia" => $numero_secuencia,
                          "numero_recibo"    => 0,
                          "mes"              => 1,
                          "fecha"            => new DateTime($fecha_aprobacion),
                          "tipo_operacion"   => "C",
                          "concepto"         => "INSCRIPCION",
                          "fecha_movimiento" => new DateTime($fecha_aprobacion),
                          "fecha_contable"   => new DateTime($fecha_aprobacion),
                          "cargo"            => 1000,
                          "abono"            => "0",
                          "saldo"            => 1000,
                          "forma_pago"       => "",
                          "banco_pago"       => "",
                          "monto_pago"       => "",
                          "numero_tarjeta"   => "",
                          "cuenta"           => "",
                          "file_comprobante" => "",
                          "pago"             => 0,
                          "tipo_registro"    => 0,
                          "status"           => 0,
                          'auditoria'       => [array("cod_user"  => $id_usuario,
                                                  "nomuser"     => $this->session->userdata('nombre'),
                                                  "fecha"       => $fecha,
                                                  "accion"      => "Nuevo registro recibo",
                                                  "operacion"   => ""
                                              )]  
                        );
          
        $ArrayRecibos   = array();
        $ArrayRecibos[] = $inscripcion;







        $sum = 0;
        if($dias_prorrata != 0){
          $numero_secuencia++;
            $data_prorrata  =    array('_id'          => new MongoDB\BSON\ObjectId(),
                                      "operacion"       => "",
                                      "numero_secuencia" => $numero_secuencia,
                                      "numero_recibo"    => 1,
                                      "mes"              => 0,
                                      "fecha"            => new DateTime($fecha_aprobacion),
                                      "tipo_operacion"   => "C",
                                      "concepto"         => "Prorrata",
                                      "fecha_movimiento" => new DateTime($fecha_aprobacion),
                                      "fecha_contable"   => new DateTime($fecha_aprobacion),
                                      "cargo"            => ($mensualidad[$key] / 30) * $dias_prorrata,
                                      "abono"            => "0",
                                      "saldo"            => ($mensualidad[$key] / 30) * $dias_prorrata,
                                      "forma_pago"       => "",
                                      "banco_pago"       => "",
                                      "monto_pago"       => "",
                                      "numero_tarjeta"   => "",
                                      "cuenta"           => "",
                                      "file_comprobante" => "",
                                      "pago"             => 0,
                                      "tipo_registro"    => 0,
                                      "status"           => 0,
                                      'auditoria'       => [array("cod_user"  => $id_usuario,
                                                                "nomuser"     => $this->session->userdata('nombre'),
                                                                "fecha"       => $fecha,
                                                                "accion"      => "Nuevo registro recibo",
                                                                "operacion"   => ""
                                                            )]   
                                    );

            $ArrayRecibos[] = $data_prorrata;

            $sum = 1;
        }



        $dia_init  = 01;
        $mes_init  = $mes_fecha_aprobacion + 1;
        $new_fecha = $year_fecha_aprobacion."-".$mes_init."-".$dia_init;

        $new_fecha = new DateTime($new_fecha);

        for ($i=1; $i <= $plan["tiempo_contrato"]; $i++) { 
          $numero_secuencia++;
          $data  =    array('_id'          => new MongoDB\BSON\ObjectId(),
                        "operacion"        => "",
                        "numero_secuencia" => $numero_secuencia,
                        "numero_recibo"    => $i + $sum,
                        "mes"              => $i,
                        "fecha"            => $new_fecha,
                        "tipo_operacion"   => "C",
                        "concepto"         => "MENSUALIDAD",
                        "fecha_movimiento" => $new_fecha,
                        "fecha_contable"   => $new_fecha,
                        "cargo"            => $mensualidad[$key],
                        "abono"            => "0",
                        "saldo"            => $mensualidad[$key],
                        "forma_pago"       => "",
                        "banco_pago"       => "",
                        "monto_pago"       => "",
                        "numero_tarjeta"   => "",
                        "cuenta"           => "",
                        "file_comprobante" => "",
                        "pago"             => 0,
                        "tipo_registro"    => 0,
                        'auditoria'       => [array("cod_user"  => $id_usuario,
                                                  "nomuser"     => $this->session->userdata('nombre'),
                                                  "fecha"       => $fecha,
                                                  "accion"      => "Nuevo registro recibo",
                                                  "operacion"   => ""
                                              )]   
                      );

          $ArrayRecibos[] = $data;
          
          $new_fecha = date("Y-m-d",strtotime($new_fecha->format("Y-m-d")."+ 1 month")); 
          $new_fecha = new DateTime($new_fecha);


          
 
        }

        


        $datos = array(
                      'id_venta'        => $id_cotizacion,
                      'paquete'         => $value,
                      'numero_corrida'  => $rs_cotizacion["numero_cotizacion"],
                      'numero_cobranza' => 1,
                      'inscripcion'     => 1000,
                      'id_cliente'      => $this->input->post("cliente")[$key],
                      'id_facturar'     => $this->input->post("facturar")[$key],
                      'mensualidad'     => $mensualidad[$key],
                      'recibos'         => $ArrayRecibos,
                      'eliminado'       => false,
                      'status'          => true,
                      'condicion'       => "COTIZACION",
                      'status_pago'     => 0,
                      'auditoria'       => [array("cod_user"  => $id_usuario,
                                                  "nomuser"   => $this->session->userdata('nombre'),
                                                  "fecha"     => $fecha,
                                                  "accion"    => "Nuevo registro recibo",
                                                  "operacion" => ""
                                              )]   
            );
     
          $data = array(
            'id_cotizacion'     => $id_cotizacion,
            'fecha_aprobacion'  => $fecha_aprobacion,
            'condicion'         => 'APROBADO'  
          );

       $actualizar = $this->Cotizacion_model->actualizar_cotizacion($data);
       $this->Cotizacion_model->saveCobranza($datos);
      }

      echo json_encode("<span>Cambios realizados exitosamente, la solicitud ha sido aprobada!</span>");
    }





    public function AprobarCotizacionOtrosServicios(){

      $id_usuario       = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));  
      $fecha            = new DateTime(date("Y-m-d"));


      $services = $this->input->post("data_service");
      $ArrayRecibos   = array();
      $count = 0;

     
      $listado = $this->Servicios_model->listado_esquema();
      $numero_secuencia = 0;
      foreach($services as $key => $value){
        $numero_secuencia++;
        $resultados = $this->mongo_db->where(array('_id' => new MongoDB\BSON\ObjectId($value["service"])))->get("servicios")[0];

        $count++;
          $data  =    array('_id'          => new MongoDB\BSON\ObjectId(),
            "operacion"        => "",
            "numero_secuencia" => $numero_secuencia,
            "numero_recibo"    => $count,
            "mes"              => $count,
            "fecha"            => $fecha,
            "tipo_operacion"   => "C",
            "concepto"         => $resultados["descripcion"],
            "fecha_movimiento" => $fecha,
            "fecha_contable"   => $fecha,
            "cargo"            => $value["monto"],
            "abono"            => "0",
            "saldo"            => $value["monto"],
            "forma_pago"       => "",
            "banco_pago"       => "",
            "monto_pago"       => "",
            "numero_tarjeta"   => "",
            "cuenta"           => "",
            "file_comprobante" => "",
            "pago"             => 0,
            "tipo_registro"    => 0,
            'auditoria'       => [array("cod_user"  => $id_usuario,
                                      "nomuser"     => $this->session->userdata('nombre'),
                                      "fecha"       => new MongoDB\BSON\UTCDateTime(),
                                      "accion"      => "Nuevo registro recibo",
                                      "operacion"   => ""
                                  )]   
          );

          $ArrayRecibos[] = $data;
      }

      
        $datos = array(
          'id_venta'        => $this->input->post("id_cotizacion"),
          'numero_corrida'  => $this->input->post("numero_cotizacion"),
          'numero_cobranza' => 1,
          'id_cliente'      => $this->input->post("id_cliente"),
          'id_facturar'     => $this->input->post("id_cliente"),
          
          'recibos'         => $ArrayRecibos,
          'eliminado'       => false,
          'status'          => true,
          'condicion'       => "COTIZACION",
          'status_pago'     => 0,
          'auditoria'       => [array("cod_user"  => $id_usuario,
                                      "nomuser"   => $this->session->userdata('nombre'),
                                      "fecha"       => new MongoDB\BSON\UTCDateTime(),
                                      "accion"    => "Nuevo registro recibo",
                                      "operacion" => ""
                                  )]   
        );



        $data = array(
          'id_cotizacion'     => $this->input->post("id_cotizacion"),
         // 'fecha_aprobacion'  => $fecha_aprobacion,
          'condicion'         => 'APROBADO'  
        );

     $actualizar = $this->Cotizacion_model->actualizar_cotizacion($data);
     $this->Cotizacion_model->saveCobranza($datos);

     echo json_encode("<span>Cambios realizados exitosamente, la solicitud ha sido aprobada!</span>");

     


    }
    /*
    * Aceptar cotizacion
    */
    public function aceptar_cotizacion(){
        $formulario = $this->input->post();
       isset($formulario["carta_aceptar_cotizacion"])? $cartaCotizacion = $formulario["carta_aceptar_cotizacion"]: $cartaCotizacion ="";
        if(!empty($cartaCotizacion)){
            if(file_exists(sys_get_temp_dir().'/'.$cartaCotizacion))
            {
              rename(sys_get_temp_dir().'/'.$cartaCotizacion,
                                      'assets/cpanel/Cotizacion/images/'.$cartaCotizacion
                                    );
                                    //unlink(sys_get_temp_dir().'/'.$imagen);                        
            }
        }else{
            echo "no-carta";die('');
        }
        //--Registro la carta de actividad comercial

        $data = array(
                        'id_cotizacion'=>$formulario["id_cotizacionA"],
                        'carta_actividad_comercial'=>$formulario["carta_aceptar_cotizacion"],
                        'condicion'=>'VENTAS'  

        );
        $actualizar = $this->Cotizacion_model->actualizar_cotizacion($data);

        if($actualizar){
            $recibos = $this->generarRecibos($formulario["id_cotizacionA"]);
            var_dump($recibos);die;
            if($recibos){
                echo json_encode("<span>La cotización se ha editado exitosamente!</span>");
           }
        }    
    }
  /*
  * Genero los recibos una vez aceptada la cotizacion
  */
  public function generarRecibos($id_cotizacion){
      #consulto la cotizacion
      $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));  
      $rs_cotizacion = $this->Cotizacion_model->consultar_cotizacion($id_cotizacion);
      $monto = $rs_cotizacion[0]["monto_mensualidad_total"];
      $arr_vigenicia = explode(" ", $rs_cotizacion[0]["vigencia"]);
      $vigencia = (integer)$arr_vigenicia[0];
      
      $arr_cobranza = $this->Cotizacion_model->obtener_numero_cobranzaById($id_cotizacion);
            
      $numero_cobranza = $arr_cobranza["numero_cobranza"];

      $id_cobranza = $arr_cobranza["id_cobranza"];
      
      $numero_recibo = $this->Cotizacion_model->obtener_numero_reciboById($id_cotizacion);

      $numero_secuencia = $this->Cotizacion_model->obtener_numero_secuenciaById($id_cotizacion);
      //$fecha = new MongoDB\BSON\UTCDateTime();
      $p = 0;
      if(count($rs_cotizacion)>0){
        //---
        if($vigencia>1){
        //---
            for($c=2;$c<=$vigencia;$c++){
                $operacion = $c+1;
                //$mes = $this->Cotizacion_model->obtener_mes_reciboById($id_cotizacion);
                //----
                /*$data =  array(
                                    "id_venta"=>$id_cotizacion,
                                    "numero_cobranza"=>$numero_cobranza,
                                    "monto"=>$monto,
                                    "vigenica"=>$vigencia,
                                    "operacion"=>$c,
                                    "numero_recibo"=>$numero_recibo,
                                    "mes"=>$mes,
                                    "fecha"=>$fecha,
                                    "tipo_operacion"=>"C",
                                    "concepto"=>" MENSUALIDAD ".$c,
                                    "fecha_movimiento"=>$fecha,
                                    "fecha_contable"=>$fecha,
                                    "cargo"=>$monto,
                                    "abono"=>"0",
                                    "saldo"=>$monto,
                                    "forma_pago"=>"",
                                    "banco_pago"=>"",
                                    "monto_pago"=>$monto,
                                    "numero_tarjeta"=>"",
                                    "cuenta"=>"",
                                    "file_comprobante"=>"",
                                    "pago"=>0
                );*/
                $fecha = $this->Cotizacion_model->calcularFechaMes($id_cotizacion);

                $data = array(
                              "operacion"=>"",
                              "numero_secuencia"=>$numero_secuencia,
                              "numero_recibo"=>$numero_recibo,
                              "mes"=>$c,
                              "fecha"=>$fecha,
                              'tipo_operacion'=>'C',
                              'concepto'=>" MENSUALIDAD ",
                              'fecha_movimiento'=>$fecha,
                              'fecha_contable'=>$fecha,
                              'cargo'=>$monto,
                              'abono'=>0,
                              'saldo'=>$monto,
                              'forma_pago'=>'',
                              'banco_pago'=>'',
                              'monto_pago'=>$monto,
                              'numero_tarjeta'=>'',
                              'cuenta'=>'',
                              'file_comprobante'=>'',
                              'pago'=>0,
                              'tipo_registro'=>0,
                              'status' => true,
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
                $res_recibo = $this->Cotizacion_model->registrarRecibos($id_cobranza,$data);
                if($res_recibo){
                    $p++;
                    $numero_recibo++;
                    $numero_secuencia++;
                }
            //---
            }
            //--Fin de for
        //--
        //Fin de if($vigencia>1)          
        }
      }
      //---
      if($p>0)
          return true;
      else
          return false;
      //--- 
  }



  public function array_sort_by(&$arrIni, $col, $order = SORT_ASC)
  {
      $arrAux = array();
      foreach ($arrIni as $key=> $row)
      {
          $arrAux[$key] = is_object($row) ? $arrAux[$key] = $row->$col : $row[$col];
          $arrAux[$key] = strtolower($arrAux[$key]);
      }
      array_multisort($arrAux, $order, $arrIni);
  }



  public function pdf($id_cotizacion, $save = 0)
  {
    
    $fecha = new MongoDB\BSON\UTCDateTime();
    $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
    #Consulto la cotizacion
    $temp_correo['arreglo_datos'] = $this->Cotizacion_model->buscar($id_cotizacion);
    
    $membresia = $temp_correo["arreglo_datos"][0]["membresia"];
    
    $tipo_persona = $temp_correo['arreglo_datos'][0]["tipo_persona"];

  
    foreach ($temp_correo["arreglo_datos"][0]["data_plan"] as $key => $value){
      $id_plan = $value->id_plan;
      $plan    = $this->Cotizacion_model->getPlan($id_plan)[0];

      $value->posicion_plan = $plan["posicion_planes"];
      
    }

    $data_service = [];
    foreach ($temp_correo["arreglo_datos"][0]["data_service"] as $key => $value){
     
      $servicios = $this->Cotizacion_model->getDataServicios($value->service)[0];
      $value->name_service = $servicios["descripcion"];

      $data_service[] = $value;
    }


 
    $this->array_sort_by($temp_correo["arreglo_datos"][0]["data_plan"], 'posicion_plan', $order = SORT_ASC);


    $data_planes = array();
    foreach ($temp_correo["arreglo_datos"][0]["data_plan"] as $key => $value) {
      $id_plan = $value->id_plan;
      $plan    = $this->Cotizacion_model->getPlan($id_plan);
      
      $id_paquete = $value->id_paquete;
      $paquete    = $this->Cotizacion_model->getPaquete($id_paquete);


      $servcies = array();


      $this->array_sort_by($paquete[0]["servicios"], 'posicion', $order = SORT_ASC);

        foreach ($paquete[0]["servicios"] as $key => $value) {

          $servicios = $this->Cotizacion_model->getDataServicios($value->id_servicios);
         // echo json_encode($value->posicion)."<br><br>";

          $servcies[] = $servicios[0];
        }

        $paquete[0]["servicios"]["data_service"] = $servcies;
        
        $data = array('plan' => $plan[0], 'paquete' => $paquete[0]);

      $data_planes[] = $data;
    }
  
    
    if(count($temp_correo['arreglo_datos']) > 0){
      $temp_correo['arreglo_datos'] = $temp_correo['arreglo_datos'][0];
     
      $temp_correo["arreglo_datos"]["data_planes"] = $data_planes;
      //var_dump($temp_correo['arreglo_datos']);die('');
      #Consulto los planes
      if($membresia){
        $temp_correo['arreglo_datos_planes'] = $this->Cotizacion_model->buscar_plan($temp_correo['arreglo_datos']['plan']);
      }
      
      
      #Consulto los servicios
      $temp_correo['arreglo_datos_servicios'] = $this->Cotizacion_model->buscar_servicios($id_cotizacion,$temp_correo['arreglo_datos']["servicios"],$temp_correo['arreglo_datos']["servicios_c"]);
    }

 

    $head_page =  __DIR__.'../../../assets/template/dompdf/img/banner_superior.png';

    $footer_page =  __DIR__.'../../../assets/template/dompdf/img/banner_inferior.png';
    


    require 'application/third_party/html2pdf-master/vendor/autoload.php';
		ob_start();
		include 'application/third_party/html2pdf-master/examples/res/corrida.php';
		$content = ob_get_clean();
		$html2pdf = new Spipu\Html2Pdf\Html2Pdf('P', 'A4', 'en', true, 'UTF-8', array(0, 0, 0, 0));
    
    $html2pdf->writeHTML($content);
    if ($save == 1) {
			$html2pdf->output(__DIR__.'../../../assets/outpdf/corrida.pdf', 'F');
		}else{
			$html2pdf->output();
    }
  }







  public function sendventaemail($id_venta)
	{
		$this->pdf($id_venta, $save = 1);

    $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
    #Consulto la cotizacion
    $temp_correo['arreglo_datos'] = $this->Cotizacion_model->buscar($id_venta);

		$rest_correo = 0;
    /*if($this->MiCorreo_model->enviar_correo("Cotización UrbanHub", "Estmiado Sr(a) ".$temp_correo['arreglo_datos']["nombre_prospecto"]." utilizamos esta vía para hacerle llegar el detalle de la cotización: ".'<a href="'.base_url().'assets/outpdf/'.$nombre_pdf.'">'.base_url().'assets/outpdf/'.$nombre_pdf.'</a>', $temp_correo['arreglo_datos']["correo"], $temp_correo['arreglo_datos']["nombre_prospecto"])){
          $rest_correo = 1;
    }*/
    ///-------------------------------------------------------------------------
    $this->load->library('email');
    //$htmlContent = '<h1>HTML email testing by CodeIgniter Email Library</h1>';
    $htmlContent = "Estimado Sr(a) ".$temp_correo['arreglo_datos'][0]["nombre_prospecto"]." utilizamos esta vía para hacerle llegar el detalle de la cotización:";
    
    $config['mailtype'] = 'html';
    $res = $this->MiCorreo_model->buscar_mi_correo();
      //var_dump($res);die('');
      if(count($res) > 0){
        $res = $res[0];
      $correo_remitente = $res["usuario"];
      //$correo_remitente = "info@urbanhub.com.mx";
        if(!empty($res["correo"])){
          if($res["correo"] != ""){
            $correo_remitente = $res["correo"];
          }
        }

        //$nombre_remitente = "CRMUrbanHub";
        if(!empty($res["nombre"])){
          if($res["nombre"]!= ""){
            $nombre_remitente = $res["nombre"];
          }
        }
    } 
    echo $htmlContent;
    $this->email->initialize($config);
    $this->email->from($correo_remitente, $nombre_remitente);
    $this->email->to($temp_correo['arreglo_datos'][0]["correo"], $temp_correo['arreglo_datos'][0]["nombre_prospecto"]);
    $this->email->subject('Cotización');
    $this->email->message($htmlContent);
    $this->email->attach('assets/outpdf/corrida.pdf');
    if ($this->email->send()) {
        $rest_correo = 1;
        echo "<script>window.close();</script>";
    }else{
      echo "A ocurrido un error";
    }


  }
  


}



