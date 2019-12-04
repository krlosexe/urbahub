<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Esquemas extends CI_Controller
{
  private $operaciones;
	function __construct()
  {
    parent::__construct();
    //$this->load->database();
    $this->load->library('session');
    $this->load->model('Esquemas_model');
    $this->load->model('Menu_model');
    $this->load->library('form_validation');
    //--
    $this->load->helper('consumir_rest');
    $this->load->helper('organizar_sepomex');
    $this->load->helper('array_push_assoc');
    //--
    if (!$this->session->userdata("login")) {
      redirect(base_url()."admin");
    }
  }

  public function index()
  {
    $datos['permiso'] = $this->Menu_model->verificar_permiso_vista('Esquemas', $this->session->userdata('id_rol'));
    //$datos['tipos_esquemas'] = $this->Esquemas_model->tipos_esquemas();
    $esquemas = consumir_rest('Lista_Valor','buscar', array('cod_static_tipo_valor'=>'ESQUEMAS'));

    $datos["tipos_esquemas"] = $esquemas->data;

    $datos['breadcrumbs'] = $this->Menu_model->breadcrumbs('Esquemas');
    //--
    $data['modulos'] = $this->Menu_model->modulos();
    //--Migracion mongo db
    //$data['vistas'] = $this->Menu_model->vistas($this->session->userdata('id_usuario'));
    $data['vistas'] = $this->Menu_model->vistas($this->session->userdata('id_rol'));    

    $this->operaciones      = $this->menu_rol_operaciones->control($data['modulos'], $data['vistas']);
    $data['modulos_vistas'] = $this->operaciones;
    $this->load->view('cpanel/header');
    $this->load->view('cpanel/menu', $data);
    $this->load->view('catalogo/Esquemas/index', $datos);
    $this->load->view('cpanel/footer');
  }

  public function listado_esquema()
  {
    //--Cambio con servicio ag2
    $esquemas = consumir_rest('Lista_Valor','buscar', array('cod_static_tipo_valor'=>'ESQUEMAS'));
    $listado = $this->Esquemas_model->listado_esquema();
    $listado2 = [];
    foreach ($listado as $key => $value) {
      $arreglo_esquemas = consumir_rest('Lista_Valor','consultar', array('id_lista_valor'=>$value["tipo"]));
      //Transformo el la fila de usuario en un array
      //var_dump($arreglo_esquemas->data[0]);die('');
      //$arreglo_data = get_object_vars($value);
      $arreglo_data = $value;
      $arreglo_data["nombre_lista_valor"] = is_array($arreglo_esquemas->data)?$arreglo_esquemas->data[0]->nombre_lista_valor:(is_object($arreglo_esquemas->data)?$arreglo_esquemas->data->nombre_lista_valor:'');
      $listado2[] = $arreglo_data;
    }
    
    echo json_encode($listado2);
  }

  /*
  * Verificar existe un esquema con ese codigo o esa descripcion
  */
  
  public function verificar_existe($tipo,$codigo,$descripcion){
      $res = $this->Esquemas_model->verificar_existe($tipo,$codigo,$descripcion);
      return count($res);
  }
  /*
  * Verificar si existe codigo
  */
  public function verificar_existe_codigo($codigo){
      $res = $this->Esquemas_model->verificar_existe_codigo($codigo);
      return count($res);
  }
  /*
  * Registrar esquema
  */
  public function registrar_esquema()
  {
    $this->reglas_esquema('insert');
    $this->mensajes_reglas_esquema();

    $fecha = new MongoDB\BSON\UTCDateTime();
    $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
    //-------------
    $existe_codigo = $this->verificar_existe_codigo(mb_strtoupper($this->input->post('cod_esquema')));
    if($existe_codigo>0){
       echo "<span>Existe un esquema con ese código </span>";die('');
    }
    //-------------
    if($this->form_validation->run() == true){
        //-Verifico si existe un esquema con ese codigo y esa descripcion
        $existe = $this->verificar_existe(trim($this->input->post('tipo')),mb_strtoupper($this->input->post('cod_esquema')),trim(mb_strtoupper($this->input->post('descripcion'))));
        //--Sino existe
        if($existe==0){
        //--------------------------------------------------------------------------------------------
            $data = array(
            'tipo' => $this->input->post('tipo'),
            'cod_esquema' => trim(mb_strtoupper($this->input->post('cod_esquema'))),
            'descripcion' => trim(mb_strtoupper($this->input->post('descripcion'))),
            'status' => true,
            'eliminado' => false,
            'auditoria' => [array(
                                      "cod_user" => $id_usuario,
                                      "nomuser" => $this->session->userdata('nombre'),
                                      "fecha" => $fecha,
                                      "accion" => "Nuevo registro esquema",
                                      "operacion" => ""
                                  )]
            );
            $this->Esquemas_model->registrar_esquema($data);
        //--------------------------------------------------------------------------------------------  
        }else{//Si existe....
            echo "<span>Existe un esquema con esos datos</span>";die('');
        }

    }else{
      // enviar los errores
      echo validation_errors();
    }
  }

  public function actualizar_esquema()
  {
    $this->reglas_esquema('update');
    $this->mensajes_reglas_esquema();
    if($this->form_validation->run() == true){
      $data = array(
        'tipo' => $this->input->post('tipo'),
        'cod_esquema' => trim(mb_strtoupper($this->input->post('cod_esquema'))),
        'descripcion' => trim(mb_strtoupper($this->input->post('descripcion'))),
      );
      $this->Esquemas_model->actualizar_esquema($this->input->post('id_esquema'), $data);
    }else{
      // enviar los errores
      echo validation_errors();
    }
  }

  public function reglas_esquema($method)
  {
    if ($method == 'insert'){
      $this->form_validation->set_rules('tipo','Tipo','required');
      $this->form_validation->set_rules('cod_esquema','Código de Esquema','required');
      $this->form_validation->set_rules('descripcion','Descripción','required');
    } else if ($method == 'update'){
      $this->form_validation->set_rules('tipo','Tipo','required');
      $this->form_validation->set_rules('cod_esquema','Código de Esquema','required');
      $this->form_validation->set_rules('descripcion','Descripción','required');
    }
  }

  public function mensajes_reglas_esquema(){
    $this->form_validation->set_message('required', 'El campo %s es obligatorio');
    $this->form_validation->set_message('numeric', 'El campo %s debe poseer solo números');
    $this->form_validation->set_message('is_unique', 'El valor ingresado en el campo %s ya se encuentra en uso');
  }

  public function eliminar_esquema()
  {
    $id = $this->input->post('id');
    $descuentos = $this->Esquemas_model->buscarDescuentos($id);
    $comisiones = $this->Esquemas_model->buscarComisiones($id);
    $recargos = $this->Esquemas_model->buscarRecargos($id);
    if (count($descuentos)>0 || count($comisiones)>0 || count($recargos)>0){
      echo ("<span>El Esquema NO se puede eliminar ya que tiene una comisión/descuento/recargo asociado!</span>");
    }else{
        $this->Esquemas_model->eliminar_esquema($id);
    }
  }

  public function status_esquema()
  {
    $this->Esquemas_model->status_esquema($this->input->post('id'), $this->input->post('status'));
    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
  }

  public function eliminar_multiple_esquema(){
    $ids = $this->input->post('id');
    $this->Esquemas_model->eliminar_multiple_esquema($this->input->post('id'));
   
    /*foreach ($ids as $i => $id) {
        $descuentos = $this->Esquemas_model->buscarDescuentos($id);
        $comisiones = $this->Esquemas_model->buscarComisiones($id);
        if (count($descuentos)>0 || count($comisiones)>0){
          echo ("<span>El Esquema NO se puede eliminar ya que tiene una comisión/descuento asociado!</span>");
        }else{
              $this->Esquemas_model->eliminar_multiple_esquema($this->input->post('id'));
            }
    }*/
  }  

  public function status_multiple_esquema()
  {
    $this->Esquemas_model->status_multiple_esquema($this->input->post('id'), $this->input->post('status'));
    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
  }

}//Fin class Bancos
