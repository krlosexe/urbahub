<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Proyectos extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
    //$this->load->database();
    $this->load->library('session');
    $this->load->model('Proyectos_model');
     $this->load->model('Productos_model');
    $this->load->model('Menu_model');
    $this->load->library('form_validation');
    if (!$this->session->userdata("login")) {
      redirect(base_url()."admin");
    }
  }

  public function index()
  {
    $datos['permiso'] = $this->Menu_model->verificar_permiso_vista('Proyectos', $this->session->userdata('id_rol'));
    $data['modulos'] = $this->Menu_model->modulos();
    $data['vistas'] = $this->Menu_model->vistas($this->session->userdata('id_usuario'));
    $datos['breadcrumbs'] = $this->Menu_model->breadcrumbs('Proyectos');
    $datos['directores'] = $this->Proyectos_model->directores();
    $datos['inmobiliarias'] = $this->Proyectos_model->inmobiliarias();
    $datos['clasificaciones'] = $this->Proyectos_model->clasificaciones();
    $datos['etapas'] = $this->Proyectos_model->etapas();
    $datos['esquemas'] = $this->Proyectos_model->esquemas();
    foreach ($data['modulos'] as $modulo) {
        foreach ($data['vistas'] as $vista) {
            if($modulo->id_modulo_vista == $vista->id_modulo_vista){
              $data["modulo_user"][] = $modulo->id_modulo_vista;
            }
        }
      }


      $ids = array_unique($data['modulo_user']);
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
    $this->load->view('catalogo/Proyectos/index', $datos);
    $this->load->view('cpanel/footer');
  }

  public function listado_proyectos()
  {
    $listado = $this->Proyectos_model->listado_proyectos();
    echo json_encode($listado);
  }

  public function registrar_proyecto()
  {
    $imagen = $this->input->post('plano');
    
    if(!empty($imagen))
    {
      if(file_exists(sys_get_temp_dir().'/'.$imagen))
      {
        rename(sys_get_temp_dir().'/'.$imagen,
                                'assets/cpanel/Proyectos/planos/'.$imagen
                              );
                              //unlink(sys_get_temp_dir().'/'.$imagen);
                             
      }
    }
    $this->reglas_proyectos('insert');
    $this->mensajes_reglas_proyectos();
    if ($this->form_validation->run() == true) {
      $proyectoArray = array(
        'codigo'            => trim(mb_strtoupper($this->input->post('codigo'), 'UTF-8')),
        'nombre'            => trim(mb_strtoupper($this->input->post('nombre'), 'UTF-8')),
        'descripcion'       => trim(mb_strtoupper($this->input->post('descripcion'), 'UTF-8')),
        'director'          => $this->input->post('director'),
        'plano'             => $imagen,
        'indicador_mora'    => $this->input->post('indicador_mora'),
        'can_dias_vencidos' => trim($this->input->post('dias_vencidos')),
        'porcentaje_mora'   => trim($this->input->post('porcentaje_mora'))
      ); 
      $clasificaciones = $this->input->post('clasificaciones');
      $esquemas        = $this->input->post('esquemas');
      //print_r($imagen);die;
      $this->Proyectos_model->registrar_proyecto($proyectoArray, $this->input->post('inmobiliarias'),$clasificaciones, $esquemas );
      echo json_encode("<span>El proyecto se ha registrado exitosamente!</span>"); // envio de mensaje exitoso
    } else {
      // enviar los errores
      echo validation_errors();
    }
  }

  public function actualizar_proyecto()
  {
    $imagen = $this->input->post('plano');
   
    if(!empty($imagen))
    {
      if(file_exists(sys_get_temp_dir().'/'.$imagen))
      {
        rename(sys_get_temp_dir().'/'.$imagen,'assets/cpanel/Proyectos/planos/'.$imagen);
                             // unlink(sys_get_temp_dir().'/'.$imagen);
                             
      }
    }
    //print_r($imagen);die;
    $this->reglas_proyectos('update');
    $this->mensajes_reglas_proyectos();
    if($this->form_validation->run() == true){
      $proyectoArray = array(
        'codigo' => trim(mb_strtoupper($this->input->post('codigo'), 'UTF-8')),
        'nombre' => trim(mb_strtoupper($this->input->post('nombre'), 'UTF-8')),
        'descripcion' => trim(mb_strtoupper($this->input->post('descripcion'), 'UTF-8')),
        'director' => $this->input->post('director'),
        'indicador_mora'    => $this->input->post('indicador_mora'),
        'can_dias_vencidos' => trim($this->input->post('dias_vencidos')),
        'porcentaje_mora'   => trim($this->input->post('porcentaje_mora'))
      );
      $proyecto_verificado=$this->Proyectos_model->verificar_proyecto($this->input->post(trim(mb_strtoupper($this->input->post('nombre'), 'UTF-8')))); //busca si el nombre del banco esta registrado en la base de datos
      if(count($proyecto_verificado)>0){
        // si es mayor a cero, se verifica si el id recibido del formulario es igual al id que se verifico
        if($proyecto_verificado[0]['id_proyecto'] == $this->input->post('id_proyecto')){
          //si son iguales, quiere decir que es el mismo registro
          $this->Proyectos_model->actualizar_proyecto($proyectoArray, $this->input->post('id_proyecto'), $imagen, $this->input->post('inmobiliarias'), $this->input->post('clasificaciones'), $this->input->post('esquemas'));
          echo json_encode("<span>El proyecto se ha editado exitosamente!</span>"); // envio de mensaje exitoso
        }else{
          //si son diferentes, quiere decir que ya el nombre del banco se encuentra en uso por otro registro
          echo "<span>El codigo del proyecto ingresado ya se encuentra en uso!</span>";
        }
      }else{
        // si conteo del array es igual a 0, se actualiza el registro
        $this->Proyectos_model->actualizar_proyecto($proyectoArray, $this->input->post('id_proyecto'), $imagen, $this->input->post('inmobiliarias'), $this->input->post('clasificaciones'), $this->input->post('esquemas'));
        echo json_encode("<span>El proyecto se ha editado exitosamente!</span>"); // envio de mensaje exitoso
      }
    }else{
      // enviar los errores
      echo validation_errors();
    }
  }


  public function getclasificaciones($proyecto = "")
  {
    $result =  $this->Proyectos_model->buscarClasificacionesget($proyecto);
      echo json_encode($result);
  }
  public function getclasificacionesEtapas($proyecto = "", $etapa = "")
  {
    $result =  $this->Proyectos_model->buscarClasificacionesEtapaget($proyecto, $etapa);
      echo json_encode($result);
  }

  public function reglas_proyectos($method)
  {
    if($method=="insert"){
      $this->form_validation->set_rules('codigo','Código','required|is_unique[proyectos.codigo]');
      $this->form_validation->set_rules('nombre','Nombre','required');
      $this->form_validation->set_rules('descripcion','Descripción','required');
      $this->form_validation->set_rules('director','Director','required');
    }else if($method=="update"){
      $this->form_validation->set_rules('codigo','Código','required');
      $this->form_validation->set_rules('nombre','Nombre','required');
      $this->form_validation->set_rules('descripcion','Descripción','required');
      $this->form_validation->set_rules('director','Director','required');
    }
  }

  public function mensajes_reglas_proyectos(){
    $this->form_validation->set_message('required', 'El campo %s es obligatorio');
    $this->form_validation->set_message('max_length', 'El Campo %s debe tener un Máximo de %d Caracteres');
    $this->form_validation->set_message('numeric', 'El campo %s debe poseer solo numeros enteros');
    $this->form_validation->set_message('is_unique', 'El valor ingresado en el campo %s ya se encuentra en uso');
    $this->form_validation->set_message('matches', 'El valor ingresado en el campo %s no coincide');
  }

  public function eliminar_proyecto()
  {
    $id_proyecto = $this->input->post('id');
    $consulta = $this->Proyectos_model->productoExiste($id_proyecto);
    if(isset($consulta)){
      echo ("<span>El Proyecto NO se puede eliminar ya que tiene un producto asociado!</span>");
    }else{
    $this->Proyectos_model->eliminar_proyecto($id_proyecto);
    }
  }


  public function status_proyecto()
  {
    $this->Proyectos_model->status_proyecto($this->input->post('id'), $this->input->post('status'));
    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
  }
  public function status_clasificacion()
  {
    $id = $this->input->post('id');
    $status = $this->input->post('status');
    $tabla = "proyectos_clasificacion";

    $this->Proyectos_model->status_inmob_clasi($id,$status ,$tabla);
    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
  }
  public function status_inmobilaria()
  {
    $this->Proyectos_model->status_inmob_clasi($this->input->post('id'), $this->input->post('status'), "inmobiliarias_proyectos");
    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
  }
  public function status_esquema()
  {
    $this->Proyectos_model->status_inmob_clasi($this->input->post('id'), $this->input->post('status'), "proyectos_esquemas");
    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
  }

  public function eliminar_multiple_proyecto()
  {
    $ids = $this->input->post('id');
   foreach ($ids as $i => $id) {
      $consulta = $this->Proyectos_model->productoExiste($id);
      if (isset($consulta)){
        echo ("<span>El Proyecto NO se puede eliminar ya que tiene un producto asociado!</span>");
      }else{
        $this->Proyectos_model->eliminar_multiple_proyecto($this->input->post('id'));
      }
    }
  }

  public function status_multiple_proyecto()
  {
    $this->Proyectos_model->status_multiple_proyecto($this->input->post('id'), $this->input->post('status'));
    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
  }

public function buscarInmobiliariasVendedor()
  {
    $inmobiliarias = $this->Proyectos_model->buscarInmobiliariasVendedor($this->input->post('proyecto'));
    echo json_encode($inmobiliarias);
  }
  public function buscarInmobiliarias()
  {
    $inmobiliarias = $this->Proyectos_model->buscarInmobiliarias($this->input->post('proyecto'));
    echo json_encode($inmobiliarias);
  }
  public function buscarEsquemas()
  {
    $esquemas = $this->Proyectos_model->buscarEsquemas($this->input->post('proyecto'));
    echo json_encode($esquemas);
  }
  public function buscarEsquemasID()
  {
    $id_proyectos_esquemas = $this->input->post('id_proyectos_esquemas');
    $id_proyecto = $this->input->post('proyecto');
    $inmobiliarias = $this->Proyectos_model->buscarEsquemasID($id_proyecto, $id_proyectos_esquemas);
    echo json_encode($inmobiliarias);
  }
   public function buscarInmobiliariasporId()
  {
    $id_inmobiliaria_proyecto = $this->input->post('id_inmobiliaria_proyecto');
    $id_proyecto = $this->input->post('proyecto');
    $inmobiliarias = $this->Proyectos_model->buscarInmobiliariasID($id_proyecto, $id_inmobiliaria_proyecto);
    echo json_encode($inmobiliarias);
  }
  public function editarClasificacionAjax()
  {
    $id_proyecto_clasificacion = $this->input->post('id_proyecto_clasificacion');
    $precio = trim(str_replace(",", "",$this->input->post('precio')));
    $this->Proyectos_model->editarClasificacionAjax($id_proyecto_clasificacion,$precio); 
  }

  public function eliminar_inmobiliaria_proyecto()
  {
    $id= $this->input->post('id'); //print_r($id);die;
    $id_inmobiliaria_proyecto= $this->input->post('id_inmobiliaria_proyecto');
    $consulta = $this->Proyectos_model->inmobiliariaExiste($id);
    if(isset($consulta)){
      echo ("La Inmobiliaria NO se puede eliminar ya que tiene un Registro asociado!");
    }else{
    $this->Proyectos_model->eliminar_inmobiliaria_proyecto($id, $id_inmobiliaria_proyecto);
    
    }

  }

   public function eliminar_proyectos_esquemas()
  {
    $id= $this->input->post('id'); //print_r($id);die;
    $id_proyectos_esquemas= $this->input->post('id_proyectos_esquemas');
    //$consulta = $this->Proyectos_model->esquemasExiste($id);
    //if(isset($consulta)){
     // echo ("La Inmobiliaria NO se puede eliminar ya que tiene un Registro asociado!");
    //}else{
    $this->Proyectos_model->eliminar_proyectos_esquemas($id, $id_proyectos_esquemas);
    
    //}

  }

  public function buscarClasificaciones()
  {
    $clasificaciones = $this->Proyectos_model->buscarClasificaciones($this->input->post('proyecto'));
    echo json_encode($clasificaciones);
  }
  public function buscarClasificacionesID()
  {
    $id_proyecto = $this->input->post('proyecto');
    $id_proyecto_clasificacion = $this->input->post('id_proyecto_clasificacion');
    $clasificaciones = $this->Proyectos_model->buscarClasificacionesID($id_proyecto, $id_proyecto_clasificacion);
    echo json_encode($clasificaciones);
  }

  public function eliminar_clasificacion_proyecto()
  {
    $id = $this->input->post('id');
    $id_proyecto_clasificacion = $this->input->post('id_proyecto_clasificacion');
    $consulta = $this->Proyectos_model->clasificacionExiste($id);
    if(isset($consulta)){
      echo ("<span>La Clasificación NO se puede eliminar ya que tiene un producto asociado!</span>");
    }else{
    $this->Proyectos_model->eliminar_clasificacion_proyecto($id_proyecto_clasificacion);
    }
  }

  public function consulta_clasificacion_existe()
  {
    $id = $this->input->post('id');
    $consulta = $this->Proyectos_model->clasificacionExiste($id);
    if(isset($consulta)){ 
      echo ("<span>La Clasificación NO se puede eliminar ya que tiene un producto asociado!</span>");
    }else{
    echo json_encode($consulta);
    }
  }



  public function getclientesproyecto($proyecto)
  {
      $result =  $this->Proyectos_model->getclientesproyecto($proyecto);
      echo json_encode($result);
  }


  public function getvendedorproyecto($proyecto, $cliente)
  {
      $result =  $this->Proyectos_model->getvendedorproyecto($proyecto, $cliente);
      echo json_encode($result);
  }

  public function getImbobiliariaVendedor($vendedor, $proyecto)
  {
    $result =  $this->Proyectos_model->getImbobiliariaVendedor($vendedor, $proyecto);
      echo json_encode($result);
  }

}
