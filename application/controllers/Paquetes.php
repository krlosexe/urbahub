<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Paquetes extends CI_Controller
{
  private $operaciones;
	function __construct()
  {
    parent::__construct();
    //$this->load->database();
    $this->load->library('session');
    $this->load->model('Paquetes_model');
    $this->load->model('Menu_model');
    $this->load->model('Cotizacion_model');
    $this->load->library('form_validation');
    //--
    $this->load->helper('array_push_assoc');
    //--
    if (!$this->session->userdata("login")) {
      redirect(base_url()."admin");
    }
  }

  public function index()
  {
    $datos['permiso'] = $this->Menu_model->verificar_permiso_vista('paquetes', $this->session->userdata('id_rol'));

    $datos['breadcrumbs'] = $this->Menu_model->breadcrumbs('paquetes');
    //--
    $data['modulos'] = $this->Menu_model->modulos();
    //--Migracion mongo db
    //$data['vistas'] = $this->Menu_model->vistas($this->session->userdata('id_usuario'));
    $data['vistas'] = $this->Menu_model->vistas($this->session->userdata('id_rol'));    
    //--
    $data['planes'] = $this->Paquetes_model->listado_planes();
    
    $data['servicios'] = $this->Paquetes_model->listado_servicios();
    //--
    $this->operaciones      = $this->menu_rol_operaciones->control($data['modulos'], $data['vistas']);
    $data['modulos_vistas'] = $this->operaciones;
    $this->load->view('cpanel/header');
    $this->load->view('cpanel/menu', $data);
    $this->load->view('catalogo/Paquetes/index', $datos);
    $this->load->view('cpanel/footer');
  }

  public function listado_paquetes()
  {
    //--Cambio con servicio ag2
    $listado = $this->Paquetes_model->listado_paquetes_planes_servicios();  
    echo json_encode($listado);
  }

  public function GetPaquete()
  {
    $id = $this->input->post('id_paquete');
    $listado = $this->Paquetes_model->GetPaquete($id);  

    foreach ($listado[0]["servicios"] as $key => $value) {
      $servicios = $this->Cotizacion_model->getDataServicios($value->id_servicios);
      $value->data_service = $servicios[0];
    }
    echo json_encode($listado[0]["servicios"]);
  }
  public function registrar_paquetes()
  {
      $this->reglas_paquetes('insert');
      $this->mensajes_reglas_paquetes();
      $fecha = new MongoDB\BSON\UTCDateTime();
      $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));





      $this->input->post('membresia') == "S" ? $membresia = true : $membresia = false;
     

      if($this->form_validation->run() == true){
          /*$existe = $this->Paquetes_model->verificar_existe_paquetes($this->input->post('plan'),$this->input->post('servicio'),$this->input->post('valor'),'');
          if($existe>0){
            echo "<span>Ya existe un servicio con esas características</span>";die('');
          }*/
          $posicionar = array(
            'posicion' => $this->input->post('posicion_paquetes'),
            'tipo' => 'insert',
          );
          $this->Paquetes_model->posicionar_modulos($posicionar);
          //---------------------------------------------------------------------------
          $existe = $this->Paquetes_model->verificar_paquetes_servicios(trim(strtoupper($this->input->post('codigo'))),trim(strtoupper($this->input->post('descripcion'))),'');
          if($existe>0){
            echo "<span>Ya existe un paquete con esas características</span>";die('');
          }
          //---------------------------------------------------------------------------
          //Verifico si existe un paquete con solo esa descripcion
          $existe = $this->Paquetes_model->verificar_paquetes_servicios('',trim(strtoupper($this->input->post('descripcion'))),'');
          if($existe>0){
            echo "<span>Ya existe un paquete con esa descripción</span>";die('');
          }
          //---------------------------------------------------------------------------
          $existe = $this->Paquetes_model->verificar_paquetes_servicios(trim(strtoupper($this->input->post('codigo'))),'','');
          if($existe>0){
            echo "<span>Ya existe un paquete con ese código</span>";die('');
          }

          $planes_servicios = $this->input->post('planes_servicios');
          
          $arr_servicios = $planes_servicios["servicios"];
          //---------------------------------------------------------------------------
          #Valido que en los servicios  deba ir obligatoriamente horas de coworking u horas ilimitadas
          //----
          $cuantos_servicios_horas = $this->Paquetes_model->verificar_servicios_horas($arr_servicios);

          if($cuantos_servicios_horas==0){
            echo "<span>Debe agregar un servicio horas de coworking u horas ilimitadas</span>";die('');
          }
          //--------------------------------------------------------------------
          $arr_valor = $planes_servicios["valor"];


          $arr_ilimitado  = $planes_servicios["ilimitado"];
          $arr_consumible = $planes_servicios["consumible"];
          




          $arr_posicion = $planes_servicios["posicion"];  
          //$arr_plan = $planes_servicios["plan"];
          
          $cont = 0;

          foreach ($arr_servicios as $key => $value) {
            
            $arreglo_planes_servicios[] = array(
                                                  "id_servicios" => $value,
                                                  "valor"        => $arr_valor[$cont],
                                                  "ilimitado"    => $arr_ilimitado[$cont],
                                                  "consumible"   => $arr_consumible[$cont],
                                                  "posicion"     => (integer)$arr_posicion[$cont],
                                                  'status'       => true,
                                                  'eliminado'    => false,
                                                  'auditoria'    => [array(
                                                                      "cod_user"  => $id_usuario,
                                                                      "nomuser"   => $this->session->userdata('nombre'),
                                                                      "fecha"     => $fecha,
                                                                      "accion"    => "Nuevo registro plan servicio",
                                                                      "operacion" => ""
                                                                  )]
                                                  ); 
            $cont++;
          }
          /*
            'id_servicio' => $value_serv[0],
            'valor' => trim(mb_strtoupper($servicios["valor"][$c][0])),
          */
          //$c = 0;
          //$guardo=0;
          //foreach ($servicios["servicios"] as $clave_serv => $value_serv) {
          //--Cambiando valores a se muestra en la web....
          (trim($this->input->post('indicador_muestra_web_registrar'))=="S")? $muestra_web = true: $muestra_web = false;
          $data = array(
              'codigo' =>  trim(strtoupper($this->input->post('codigo'))),
              'descripcion' => trim(strtoupper($this->input->post('descripcion'))),
              'plan' =>  $this->input->post('plan'),
              'precio' => str_replace(',', '', $this->input->post('precio')),
              'posicion_paquetes'=> (integer)$this->input->post('posicion_paquetes'),
              'muestra_en_web'=>$muestra_web,
              'servicios'=> $arreglo_planes_servicios,
              'membresia' => $membresia,
              'status' => true,
              'eliminado' => false,
              'auditoria' => [array(
                                        "cod_user" => $id_usuario,
                                        "nomuser" => $this->session->userdata('nombre'),
                                        "fecha" => $fecha,
                                        "accion" => "Nuevo registro paquete",
                                        "operacion" => ""
                                    )]
          );

          //var_dump($data);die('');
          //$c++;
          $res = $this->Paquetes_model->registrar_paquetes($data);
          if($res){
             echo json_encode("<span>El paquete se ha registrado exitosamente!</span>");
          }else{
              echo "<span>Ha ocurrido un error inesperado!</span>";die('');
          }
          //}
          //-----------------------------------------------------------------------------
          /*if($guardo==$c){
              echo json_encode("<span>El paquete se ha registrado exitosamente!</span>");
          }else{
              echo "<span>Ha ocurrido un error inesperado!</span>";die('');
          }*/
         //-----------------------------------------------------------------------------
      }else{
          // enviar los errores
          echo validation_errors();
      }

  }

  public function actualizar_paquetes()
  {
      /*$this->reglas_paquetes('update');
      $this->mensajes_reglas_paquetes();
      if($this->form_validation->run() == true){
        $existe = $this->Paquetes_model->verificar_existe_paquetes($this->input->post('plan'),$this->input->post('servicio'),$this->input->post('valor'),$this->input->post('id_paquetes'));
        if($existe>0){
            echo "<span>Ya existe un paquete con esas características</span>";die('');
        }
        $data = array(
            'id_plan' =>  new MongoDB\BSON\ObjectId($this->input->post('plan')),
            'id_servicio' =>  new MongoDB\BSON\ObjectId($this->input->post('servicio')),
            'valor' => trim(mb_strtoupper($this->input->post('valor'))),
            'status' => true,
            'eliminado' => false,
        );    
        $this->Paquetes_model->actualizar_paquetes($this->input->post('id_paquetes'), $data);
      }else{
        // enviar los errores
        echo validation_errors();
      }*/
      //--------------------------------------------------------------------------------------
      $this->reglas_paquetes('update');
      $this->mensajes_reglas_paquetes();
      //var_dump($this->input->post());die('');
      if($this->form_validation->run() == true){
          //--------------------------------------------------------------------------------------------------
          $fecha = new MongoDB\BSON\UTCDateTime();
          $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
         /* $servicios = $this->input->post('servicios');
          $c = 0;
          $c2 = 0;
          $guardo=0;
          foreach ($servicios["servicios"] as $clave_serv => $value_serv) {
              //Verifico si existe el paquete
              $existe = $this->Paquetes_model->verificar_existe_paquetes($this->input->post('plan'),$value_serv[0]);
              //Sino existe el registro lo agrega
              if($existe==0){
                  $data = array(
                      'id_plan' =>  $this->input->post('plan'),
                      'id_servicio' =>  $value_serv[0],
                      'valor' => trim(mb_strtoupper($servicios["valor"][$c][0])),
                      'status' => true,
                      'eliminado' => false,
                      'auditoria' => [array(
                                                "cod_user" => $id_usuario,
                                                "nomuser" => $this->session->userdata('nombre'),
                                                "fecha" => $fecha,
                                                "accion" => "Nuevo registro paquete",
                                                "operacion" => ""
                                            )]
                    );
                    $c2++;
                    $res = $this->Paquetes_model->registrar_paquetes($data);
                    if($res){
                        $guardo++;
                    }
              } 
              $c++;
          }    
          //-----------------------------------------------------------------------------
          if($guardo==$c2){
                echo json_encode("<span>El paquete se ha actualizado exitosamente!</span>");
          }else{
                echo "<span>Ha ocurrido un error inesperado!</span>";die('');
          }*/
          //--------------------------------------------------------------------------------------------------
          //--Tercer cambio, segun indicaciones de Sr Abrahans, Marzo 2019, original mgdb creado en octubre 2018...

          $servicios = $this->input->post('servicios');
          
          (isset($servicios["servicios"]))?$arr_servicios = $servicios["servicios"]:$arr_servicios = [];
          
          (isset($servicios["valor"]))?$arr_valor = $servicios["valor"]:$arr_valor = [];
          //$arr_valor = $servicios["valor"];

          (isset($servicios["ilimitado"]))?$arr_ilimitado = $servicios["ilimitado"]:$arr_ilimitado = [];


          (isset($servicios["consumible"]))?$arr_consumible = $servicios["consumible"]:$arr_consumible = [];
          //$arr_consumible = $servicios["consumible"];
          (isset($servicios["posicion"]))?$arr_posicion = $servicios["posicion"]:$arr_posicion = [];

          (trim($this->input->post('indicador_muestra_web_modificar'))=="S")? $muestra_web = true: $muestra_web = false;



          $this->input->post('membresia') == "S" ? $membresia = true : $membresia = false;


          

          //$arr_posicion = $servicios["posicion"];
          //$arr_plan = $planes_servicios["plan"];
          $cont = 0;

          if(count($arr_servicios)>0){
            //---
            foreach ($arr_servicios as $key => $value) {
              $arreglo_servicios[] = array(
                                                    "id_servicios" => $value,
                                                    "valor" => $arr_valor[$cont],
                                                    "ilimitado" => $arr_ilimitado[$cont],
                                                    "consumible" => $arr_consumible[$cont],
                                                    "posicion" => (integer)$arr_posicion[$cont],
                                                    //"plan" =>  $arr_plan[$cont],
                                                    'status' => true,
                                                    'eliminado' => false,
                                                    'auditoria' => [array(
                                                                              "cod_user" => $id_usuario,
                                                                              "nomuser" => $this->session->userdata('nombre'),
                                                                              "fecha" => $fecha,
                                                                              "accion" => "Nuevo registro servicio",
                                                                              "operacion" => ""
                                                                          )]
                                            ); 
              $cont++;
            }
            //---
          }

          //-------------------------------------------------------------------------------------------
          #Actualizo los datos encabezados de paquetes

          $id_paquetes = $this->input->post('id_paquete');
          $posicionar = array(
            'inicial' => (integer)$this->input->post('inicial'),
            'tipo' => 'update',
            'final' => (integer)$this->input->post('posicion_paquetes'),
          );
          $data = array(
              'codigo' =>  strtoupper($this->input->post('codigo')),
              'descripcion' => trim(strtoupper($this->input->post('descripcion'))),
              'posicion_paquetes'=>(integer)$this->input->post('posicion_paquetes'),
              'plan' => $this->input->post('plan'),
              'precio' => str_replace(',', '', $this->input->post('precio')),
              'muestra_en_web'=>$muestra_web,
              'membresia' => $membresia
          );
          /*var_dump($posicionar);echo "<br>";
          var_dump($data);echo "<br>";*/
          //---------------------------------------------------------------------------
          //Verifico si existe un paquete con solo esa descripcion
          $existe = $this->Paquetes_model->verificar_paquetes_servicios('',$data["descripcion"],$id_paquetes);
          if($existe>0){
            echo "<span>Ya existe un paquete con esa descripción</span>";die('');
          }
          //Verifico si existe un paquete con solo ese codigo
          $existe = $this->Paquetes_model->verificar_paquetes_servicios($data["codigo"],'',$id_paquetes);
          if($existe>0){
            echo "<span>Ya existe un paquete con ese código</span>";die('');
          }
          //Verifico si existe un paquete con esos datos
          $existe = $this->Paquetes_model->verificar_paquetes_servicios($data["codigo"],$data["descripcion"],$id_paquetes);
          if($existe>0){
            echo "<span>Ya existe un paquete con esas características</span>";die('');
          }
          //---------------------------------------------------------------------------
          #Valido que en los servicios  deba ir obligatoriamente horas de coworking u horas ilimitadas
          //----
          $cuantos_servicios_horas = $this->Paquetes_model->verificar_servicios_horasActualizar($id_paquetes,$arr_servicios);

          if($cuantos_servicios_horas==0){
            echo "<span>Debe agregar un servicio horas de coworking u horas ilimitadas</span>";die('');
          }
          //---------------------------------------------------------------------------
          $this->Paquetes_model->posicionar_modulos($posicionar);
          $this->Paquetes_model->actualizar_paquetes_encabezado($id_paquetes,$data);
          //--------------------------------------------------------------------------------------------
          #Actualizo los datos de tabla detalle
          if(count($arr_servicios)>0){
              $this->Paquetes_model->actualizar_paquetes_detalle($id_paquetes,$arreglo_servicios);
          } 
          //--------------------------------------------------------------------------------------------
          echo json_encode("<span>El paquete se ha actualizado exitosamente!</span>");
      }else{
          echo validation_errors();
      }
      //--------------------------------------------------------------------------------------

  }

  public function reglas_paquetes($method)
  {
    if ($method == 'insert'){
      $this->form_validation->set_rules('codigo','Código','required');
      $this->form_validation->set_rules('descripcion','Descripción','required');
    //  $this->form_validation->set_rules('precio','Precio','required');
     
    } else if ($method == 'update'){
      $this->form_validation->set_rules('codigo','Código','required');
      $this->form_validation->set_rules('descripcion','Descripción','required');
      $this->form_validation->set_rules('precio','Precio','required');
    }
  }

  public function mensajes_reglas_paquetes(){
    $this->form_validation->set_message('required', 'El campo %s es obligatorio');
    $this->form_validation->set_message('numeric', 'El campo %s debe poseer solo números');
    $this->form_validation->set_message('is_unique', 'El valor ingresado en el campo %s ya se encuentra en uso');
  }

  public function eliminar_paquetes()
  {
      $id = $this->input->post('id');
      $paquetes = $this->Paquetes_model->buscarMembresia($id);
      if ($paquetes>0){
        echo ("<span>El Paquete NO se puede eliminar ya que tiene una membresia asociada!</span>");
      }else{
        $this->Paquetes_model->actualizarPosicionesPaquetesEliminar($id);
        $this->Paquetes_model->eliminar_paquetes($id);
      }
  }

  public function status_paquetes()
  {
    $this->Paquetes_model->status_paquetes($this->input->post('id'), $this->input->post('status'));
    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
  }

  public function eliminar_multiple_paquetes()
  {
    //--bloque adaptado al cambio de alcance marzo 2019
    $this->Paquetes_model->eliminar_multiple_paquetes($this->input->post('id'));
    //--Bloque anterior al cmabio de alcance.
    //consulto el id de los paquetes recibiendo el id del plan
    /*$id = $this->input->post('id');
    $rs = "";
    $rs_paquetes = $this->Paquetes_model->consultar_paquetes_planes($id);  
    foreach ($rs_paquetes as $clave_paquetes => $valor_paquetes) {
        $rs = $this->Paquetes_model->eliminar_multiple_paquetes($valor_paquetes["_id"]->{'$id'});
    }      
    echo json_encode($rs);*/
    //echo json_encode("<span>Registros eliminados: ".$eliminados."</span><br><span>Registros no eliminados (porque tienen dependencia en otras tablas): ".$noEliminados); 
  }

  public function status_multiple_paquetes(){
    
    $id = $this->input->post('id');

    $status = $this->input->post('status');
    
    $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        
    $fecha = new MongoDB\BSON\UTCDateTime();

    $arreglo_id = explode(' ',$id);
    
    foreach ($arreglo_id as $valor) {
        $id_paquetes = $valor;
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
        $this->Paquetes_model->status_multiple_paquetes($id_paquetes,$datos);
    }

    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso*/
   
    /*
    *   Este bloque fue comentado debido al cambio de alcance Marzo 2019
    */
    /*$id = $this->input->post('id');

    $status = $this->input->post('status');
    
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
    
    $rs_paquetes = $this->Paquetes_model->consultar_paquetes_planes($id);  
    foreach ($rs_paquetes as $clave_paquetes => $valor_paquetes) {
        $rs = $this->Paquetes_model->status_multiple_paquetes($valor_paquetes["_id"]->{'$id'},$datos);
    }
    echo json_encode($rs);*/
  }

  public function operaciones_servicios(){
    $operaciones = $this->Paquetes_model->operaciones_servicios($this->input->post('id'));
    echo json_encode($operaciones);
  }
    
  /*public function eliminar_paquete_servicio(){
    /*$data = array(
                          'servicios.$.eliminado'=> true
                );*/
    /*$id_paquete = new MongoDB\BSON\ObjectId($this->input->post('id_paquete'));            
    $where_array = array(
                          'servicios.id_servicios'=>$this->input->post('id_servicio')
                        );
    //--*/
    //$id_servicios = $this->input->post('id_servicio');
    /*$where_array = array(
                          'servicios' => array('$elementMatch'=>array("id_servicios"=>$id_servicios))
                        );*/
    //---
    //$this->Paquetes_model->eliminar_planes_servicios($where_array,$this->input->post('id_servicio'));     
  //}
  public function eliminar_paquete_servicio(){//($id_servicios, $id_paquete){
      //$data = array('servicios.$.status' => false);
      //var_dump($data);die('');
      $id_servicios = $this->input->post('id_servicio');
      $id_paquete = $this->input->post('id_paquete');
      //$where_array = array('servicios.id_servicios' =>$id_servicios);
      //$this->Paquetes_model->eliminar_planes_servicios($where_array,$data); 
      $this->Paquetes_model->eliminar_planes_servicios($id_servicios, $id_paquete); 
  }
  /*--- ---*/
  /*
  * Contar Modulos
  */
  public function contar_modulos(){
    $contador = $this->Paquetes_model->contar_modulos();
    echo json_encode($contador);
  }

  /*
  *   Generar posiciones en paquetes: Creado para modificar paquetes y agregar campo posicion
  */
  public function generarPosiciones(){
      $this->Paquetes_model->generarPosiciones(); 
  }
  /*
  * 
  */
  public function generarPosicionesPaquetes(){
      $this->Paquetes_model->generarPosicionesPaquetes(); 
  }
  /*
  * 
  */
  public function generarMostrarWebPaquetes(){
      $this->Paquetes_model->generarMostrarWebPaquetes(); 
  }
  /***/
}//Fin class 
