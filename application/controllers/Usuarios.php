<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Usuarios extends CI_Controller
{
  private $operaciones;
	function __construct()
  {
    parent::__construct();
    //$this->load->database();
    $this->load->library('session');
    $this->load->model('Usuarios_model');
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
    $datos['permiso'] = $this->Menu_model->verificar_permiso_vista('Usuarios', $this->session->userdata('id_rol'));
    $data['modulos'] = $this->Menu_model->modulos();
    //$data['vistas'] = $this->Menu_model->vistas($this->session->userdata('id_usuario'));
    //--Modificacion para Mongo DB
    $data['vistas'] = $this->Menu_model->vistas($this->session->userdata('id_rol'));    
    //--
    //--Cambio con servicio ag2
    $nacionalidades = consumir_rest('Lista_Valor','buscar', array('cod_static_tipo_valor'=>'NACIONALIDAD'));
    $edo_civil = consumir_rest('Lista_Valor','buscar', array('cod_static_tipo_valor'=>'EDOCIVIL'));
    $sexos = consumir_rest('Lista_Valor','buscar', array('cod_static_tipo_valor'=>'SEXO'));
    //----
    $datos['nacionalidades'] = $nacionalidades->data;
    $datos['estadosCiviles'] = $edo_civil->data;
    $datos['sexos'] = $sexos->data;
    //$datos['nacionalidades'] = $this->Usuarios_model->nacionalidades();
    //$datos['estadosCiviles'] = $this->Usuarios_model->estados_civiles();
    //$datos['sexos'] = $this->Usuarios_model->sexos();
    $datos['roles'] = $this->Usuarios_model->roles();
    $datos['roles_consulta'] = $this->Usuarios_model->roles_consulta();
    //var_dump($datos['roles_consulta']);die('');
    $datos['breadcrumbs'] = $this->Menu_model->breadcrumbs('Usuarios');
    $this->operaciones      = $this->menu_rol_operaciones->control($data['modulos'], $data['vistas']);
    $data['modulos_vistas'] = $this->operaciones;
    $this->load->view('cpanel/header');
    $this->load->view('cpanel/menu', $data);
    $this->load->view('perfiles/Usuarios/index', $datos);
    $this->load->view('cpanel/footer');
  }

  public function listado_usuarios(){

    $listado = $this->Usuarios_model->listar_usuarios();
    $listado2 = array();    
    foreach ($listado as $key => $value) {
    //var_dump($value);die('');
      if($value["id_usuario"] != $this->session->userdata('id_usuario') AND (string)$value["id_usuario"] != "1"){
        //$listado2[] = $value;
        //Consumo el servicio segun el id del usuario
        $sepomex = consumir_rest('Sepomex','consultar', array('id_codigo_postal'=>$value["id_codigo_postal"]));
        //Transformo el la fila de usuario en un array
        //$arreglo_data = get_object_vars($value);
        //LLamo al helper para que organize los resultados de la consulta al servicio
        $arreglo_sepomex = organizar_sepomex($sepomex);

        //unset($listado[$key]);
        //Hago push assoc de la fila de usuario y sus datos respectivos en sepomex
        //$listado2[] = array_push_assoc($arreglo_data,$arreglo_sepomex);
        $listado2[] = array_push_assoc($value,$arreglo_sepomex);
        //Consulto todos los estados/ciudades/municipios/colonias segun el codigo postal
        //$sepomex_select = consumir_rest('Sepomex','buscar', array('d_codigo'=>$arreglo_sepomex->d_codigo)); 
     }
     //---

     //---
    }
    echo json_encode($listado2);
  }

  public function array_push_assoc(array &$arrayDatos, array $values){
    $arrayDatos = array_merge($arrayDatos, $values);
    return $arrayDatos;
  }

  public function buscar_codigos()
  {
    //$datos = $this->Usuarios_model->buscar_codigos($this->input->post('codigo'));
    //echo json_encode($datos);
    //--Cambio con servicio ag2
    $sepomex = consumir_rest('Sepomex','buscar', array('d_codigo'=>$this->input->post('codigo')));
    //isset($sepomex)? $data = $sepomex->data : $data="";
    isset($sepomex)? $temp_data = $sepomex->data : $temp_data="";
    if(is_array($temp_data)){
      foreach ($temp_data as $key => $value) {
        if(isset($data['estados'])){
          if(!(array_search($value->d_estado, array_column($data['estados'], 'd_estado')) !== False)) {
            $data['estados'][] = $value;
          }
        }
        else{
          $data['estados'][] = $value;
        }

        if(isset($data['ciudades'])){
          if(!(array_search($value->d_ciudad, array_column($data['ciudades'], 'd_ciudad')) !== False)) {
            $data['ciudades'][] = $value;
          }
        }
        else{
          $data['ciudades'][] = $value;
        }

        if(isset($data['municipios'])){
          if(!(array_search($value->d_mnpio, array_column($data['municipios'], 'd_mnpio')) !== False)) {
            $data['municipios'][] = $value;
          }
        }
        else{
          $data['municipios'][] = $value;
        }

        if(isset($data['colonias'])){
          if(!(array_search($value->d_asenta, array_column($data['colonias'], 'd_asenta')) !== False)) {
            $data['colonias'][] = $value;
          }
        }
        else{
          $data['colonias'][] = $value;
        }

      }
    }
    else{
      $data = $temp_data;
    }
        header('Content-type:application/json;charset=utf-8');
    echo json_encode($data);
    //-- 
  }

  public function consultar_proyecto()
  {
  $id_usuario = $this->input->post('id_usuario');
  $respuesta =  $this->Usuarios_model->consultar_proyecto($id_usuario);
    if (isset($respuesta))
    {
     echo json_encode(true);
    }else{
      echo json_encode(false);
    }
  }
    public function consultar_inmobiliaria()
  {
  $id_usuario = $this->input->post('id_usuario');
  $respuesta =  $this->Usuarios_model->consultar_inmobiliaria($id_usuario);
    if (isset($respuesta))
    {
     echo json_encode(true);
    }else{
      echo json_encode(false);
    }
  }
  /*
  * Verificar tlf
  */
  public function verificar_tlf($tlf){
      $tlf = str_replace("-","",$tlf);
      $tlf = str_replace("+","",$tlf);
      $tlf  = str_replace("(","",$tlf);
      $tlf  = str_replace(")","",$tlf);
      $tlf  = str_replace("_","",$tlf);
      $tlf =  preg_replace('[\s+]','', $tlf);
      $valor = strlen($tlf);
      if($valor<12){
          $respuesta = false;
      }else{
          $respuesta = true;
      }
      return $respuesta;
  }
  /*
  *
  */
  public function registrar_usuario()
  {
    //------------------------------------------------------
    //Migracion MONGO DB
    $fecha = new MongoDB\BSON\UTCDateTime();
    $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
    //------------------------------------------------------
    $config['upload_path'] = "assets/cpanel/Usuarios/images/"; //ruta donde carga el archivo
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
    $this->reglas_usuarios('insert');
    $this->mensajes_reglas_usuarios();
    $usuario_verificado=$this->Usuarios_model->verificar_usuario($this->input->post('correo_usuario')); //busca si el nombre del banco esta registrado en la base de datos
    $curp_verificado = $this->Usuarios_model->verificar_curp(trim(mb_strtoupper($this->input->post('curp_datos_personales'), 'UTF-8')));
    //var_dump(count($usuario_verificado)>0);
    //die();
    if(count($usuario_verificado)>0 ){
      echo "<span>El correo del usuario ingresado ya se encuentra en uso!</span>";die('');
    }

    if(count($curp_verificado)>0 ){
      echo "<span>El curp del usuario ingresado ya se encuentra en uso!</span>";die('');
    }
    /*
    *   Validar tlf
    */
    $tlf = trim($this->input->post('telefono_principal_contacto'));
    if(!$this->verificar_tlf($tlf)){
         echo "<span>El campo teléfono debe tener 12 caracteres!</span>";die('');
    }
    /*
    *
    */
    #Valido la longitudd de caracteres de la clave
    if(strlen($this->input->post('clave_usuario'))<6){
      echo "<span>El campo contraseña debe tener un mínimo de 6 caracteres!</span>";die('');
    }
    /*
    *
    */

    //--
    //$this->verificar_usuario(trim($this->input->post('correo_usuario')),trim(mb_strtoupper($this->input->post('curp_datos_personales'), 'UTF-8'));
    if ($this->form_validation->run() == true) {
      $usuarioArray = array(
        'id_rol' => new MongoDB\BSON\ObjectId($this->input->post('id_rol')),
        'correo_usuario' => trim($this->input->post('correo_usuario')),
        'clave_usuario' => sha1($this->input->post('clave_usuario')),
        'avatar_usuario' => $imagen,
        'fec_ult_acceso_usuario' => '',
        'status'=>true,
        'eliminado'=>false,
        'auditoria' => [array(
                                "cod_user" => $id_usuario,
                                "nomuser" => $this->session->userdata('nombre'),
                                "fecha" => $fecha,
                                "accion" => "Nuevo registro usuario",
                                "operacion" => ""
                            )]

      );
      $contactoArray = array(
        'id_codigo_postal' => $this->input->post('colonia'),
        'telefono_principal_contacto' => trim($this->input->post('telefono_principal_contacto')),
        'telefono_movil_contacto' => trim($this->input->post('telefono_principal_contacto')),
        'direccion_contacto' => trim(mb_strtoupper($this->input->post('direccion_contacto'), 'UTF-8')),
        'calle_contacto' => trim(mb_strtoupper($this->input->post('calle_contacto'), 'UTF-8')),
        'interior_contacto' => trim(mb_strtoupper($this->input->post('interior_contacto'))),
        'exterior_contacto' => trim(mb_strtoupper($this->input->post('exterior_contacto'))),
        'status'=>true,
        'eliminado'=>false,
        'auditoria' => [array(
                                "cod_user" => $id_usuario,
                                "nomuser" => $this->session->userdata('nombre'),
                                "fecha" => $fecha,
                                "accion" => "Nuevo registro usuario",
                                "operacion" => ""
                            )]
      );
      $personalArray = array(
        'nombre_datos_personales' => trim(mb_strtoupper($this->input->post('nombre_datos_personales'), 'UTF-8')),
        'apellido_p_datos_personales' => trim(mb_strtoupper($this->input->post('apellido_p_datos_personales'), 'UTF-8')),
        'apellido_m_datos_personales' => trim(mb_strtoupper($this->input->post('apellido_m_datos_personales'), 'UTF-8')),
        'fecha_nac_datos_personales' => trim(date("Y-m-d", strtotime($this->input->post('fecha_nac_datos_personales')))),
        'nacionalidad_datos_personales' => $this->input->post('nacionalidad_datos_personales'),
        'curp_datos_personales' => trim(mb_strtoupper($this->input->post('curp_datos_personales'), 'UTF-8')),
        'edo_civil_datos_personales' => $this->input->post('edo_civil_datos_personales'),
        'genero_datos_personales' => $this->input->post('genero_datos_personales'),
        'status'=>true,
        'eliminado'=>false,
        'auditoria' => [array(
                                "cod_user" => $id_usuario,
                                "nomuser" => $this->session->userdata('nombre'),
                                "fecha" => $fecha,
                                "accion" => "Nuevo registro usuario",
                                "operacion" => ""
                            )]
      );
      $this->Usuarios_model->registrar_usuario($usuarioArray, $contactoArray, $personalArray);
      echo json_encode("<span>El usuario se ha registrado exitosamente!</span>"); // envio de mensaje exitoso
    } else {
      // enviar los errores
      echo validation_errors();
    }
  }

  public function actualizar_usuario()
  {
    $config['upload_path'] = "assets/cpanel/Usuarios/images/"; //ruta donde carga el archivo
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
    /*
    *   Validar tlf
    */
    $tlf = trim($this->input->post('telefono_principal_contacto'));
    if(!$this->verificar_tlf($tlf)){
         echo "<span>El campo teléfono debe tener 12 caracteres!</span>";die('');
    }
    /*
    *
    */
    #Valido la longitudd de caracteres de la clave
    if(strlen($this->input->post('clave_usuario'))<6){
      echo "<span>El campo contraseña debe tener un mínimo de 6 caracteres!</span>";die('');
    }
    /*
    *
    */
    $this->reglas_usuarios('update');
    $this->mensajes_reglas_usuarios();
    if($this->form_validation->run() == true){
      $id_rol = $this->input->post('id_rol');
      $usuarioArray = array(
        'id_rol' => $id_rol,
        'correo_usuario' => trim($this->input->post('correo_usuario')),
      );
      if (!isset($id_rol)){
        unset($usuarioArray['id_rol']);
      }
      if($this->input->post('clave_usuario') != ""){
        $usuarioArray['clave_usuario'] = sha1($this->input->post('clave_usuario'));
      }
      $contactoArray = array(
        'id_codigo_postal' => $this->input->post('colonia'),
        'telefono_principal_contacto' => trim($this->input->post('telefono_principal_contacto')),
        'telefono_movil_contacto' => trim($this->input->post('telefono_principal_contacto')),
        'direccion_contacto' => trim(mb_strtoupper($this->input->post('direccion_contacto'), 'UTF-8')),
        'calle_contacto' => trim(mb_strtoupper($this->input->post('calle_contacto'), 'UTF-8')),
        'interior_contacto' => trim(mb_strtoupper($this->input->post('interior_contacto'))),
        'exterior_contacto' => trim(mb_strtoupper($this->input->post('exterior_contacto'))),
      );
      $personalArray = array(
        'nombre_datos_personales' => trim(mb_strtoupper($this->input->post('nombre_datos_personales'), 'UTF-8')),
        'apellido_p_datos_personales' => trim(mb_strtoupper($this->input->post('apellido_p_datos_personales'), 'UTF-8')),
        'apellido_m_datos_personales' => trim(mb_strtoupper($this->input->post('apellido_m_datos_personales'), 'UTF-8')),
        'fecha_nac_datos_personales' => trim(date("Y-m-d", strtotime($this->input->post('fecha_nac_datos_personales')))),
        'nacionalidad_datos_personales' => $this->input->post('nacionalidad_datos_personales'),
        'curp_datos_personales' => trim(mb_strtoupper($this->input->post('curp_datos_personales'), 'UTF-8')),
        'edo_civil_datos_personales' => $this->input->post('edo_civil_datos_personales'),
        'genero_datos_personales' => $this->input->post('genero_datos_personales'),
      );
      $idArray = array(
        'id_usuario' => $this->input->post('id_usuario'),
        'id_contacto' => $this->input->post('id_contacto'),
        'id_datos_personales' => $this->input->post('id_datos_personales'),
      );
      $usuario_verificado=$this->Usuarios_model->verificar_usuario($this->input->post('correo_usuario')); //busca si el nombre del banco esta registrado en la base de datos
      if(count($usuario_verificado)>0){
        // si es mayor a cero, se verifica si el id recibido del formulario es igual al id que se verifico
        if($usuario_verificado[0]['_id']->{'$id'} == $this->input->post('id_usuario')){
          //si son iguales, quiere decir que es el mismo registro
          $this->Usuarios_model->actualizar_usuario($usuarioArray, $contactoArray, $personalArray, $idArray, $imagen);
          echo json_encode("<span>El usuario se ha editado exitosamente!</span>"); // envio de mensaje exitoso
        }else{
          //si son diferentes, quiere decir que ya el nombre del banco se encuentra en uso por otro registro
          echo "<span>El correo del usuario ingresado ya se encuentra en uso!</span>";
        }
      }else{
        // si conteo del array es igual a 0, se actualiza el registro
        $this->Usuarios_model->actualizar_usuario($usuarioArray, $contactoArray, $personalArray, $idArray, $imagen);
        echo json_encode("<span>El usuario se ha editado exitosamente!</span>"); // envio de mensaje exitoso
      }
    }else{
      // enviar los errores
      echo validation_errors();
    }
  }

  public function reglas_usuarios($method)
  {
    if($method=="insert"){

      // Reglas para la tabla de usuario
      $this->form_validation->set_rules('correo_usuario','Correo Electrónico','required|valid_email[usuario.correo_usuario]');
      $this->form_validation->set_rules('correo_confirmar','Confirmar Correo Electrónico','required|valid_email|matches[correo_usuario]');
      $this->form_validation->set_rules('id_rol','Tipo de Rol','required');
      $this->form_validation->set_rules('clave_usuario','Contraseña','required');
      $this->form_validation->set_rules('repetir_clave','Repetir Contraseña','required|matches[clave_usuario]');

      // Reglas para la tabla contacto
      $this->form_validation->set_rules('colonia','Código Postal','required');
      $this->form_validation->set_rules('telefono_principal_contacto','Teléfono','required');

      // Reglas para la tabla
      $this->form_validation->set_rules('nombre_datos_personales','Nombre(s)','required');
      $this->form_validation->set_rules('apellido_p_datos_personales','Apellido Paterno','required');
      $this->form_validation->set_rules('apellido_m_datos_personales','Apellido Materno','required');
      $this->form_validation->set_rules('fecha_nac_datos_personales','Fecha de Nacimiento','required');
      $this->form_validation->set_rules('nacionalidad_datos_personales','Nacionalidad','required');
      $this->form_validation->set_rules('curp_datos_personales','C.U.R.P.','required');
      $this->form_validation->set_rules('edo_civil_datos_personales','Estado Civil','required');
      $this->form_validation->set_rules('genero_datos_personales','Género.','required');

    }else if($method=="update"){
      // Reglas para la tabla de usuario
      $this->form_validation->set_rules('correo_usuario','Correo Electrónico','required|valid_email');
      $this->form_validation->set_rules('correo_confirmar','Confirmar Correo Electrónico','required|valid_email|matches[correo_usuario]');
     // $this->form_validation->set_rules('id_rol','Tipo de Rol','required');
      if($this->input->post('clave_usuario') != ""){
        $this->form_validation->set_rules('clave_usuario','Contraseña','required');
        $this->form_validation->set_rules('repetir_clave','Repetir Contraseña','required|matches[clave_usuario]');
      }

      // Reglas para la tabla contacto
      $this->form_validation->set_rules('colonia','Código Postal','required');
      $this->form_validation->set_rules('telefono_principal_contacto','Teléfono','required');

      // Reglas para la tabla
      $this->form_validation->set_rules('nombre_datos_personales','Nombre(s)','required');
      $this->form_validation->set_rules('apellido_p_datos_personales','Apellido Paterno','required');
      $this->form_validation->set_rules('apellido_m_datos_personales','Apellido Materno','required');
      $this->form_validation->set_rules('fecha_nac_datos_personales','Fecha de Nacimiento','required');
      $this->form_validation->set_rules('nacionalidad_datos_personales','Nacionalidad','required');
      $this->form_validation->set_rules('curp_datos_personales','C.U.R.P.','required');
      $this->form_validation->set_rules('edo_civil_datos_personales','Estado Civil','required');
      $this->form_validation->set_rules('genero_datos_personales','Género.','required');
    }
  }

  public function mensajes_reglas_usuarios(){
    $this->form_validation->set_message('required', 'El campo %s es obligatorio');
    $this->form_validation->set_message('max_length', 'El Campo %s debe tener un Máximo de %d Caracteres');
    $this->form_validation->set_message('numeric', 'El campo %s debe poseer solo numeros enteros');
    $this->form_validation->set_message('is_unique', 'El valor ingresado en el campo %s ya se encuentra en uso');
    $this->form_validation->set_message('matches', 'El valor ingresado en el campo %s no coincide');
  }

  public function eliminar_usuario()
  {
    $this->Usuarios_model->eliminar_usuario($this->input->post('id'));
  }

  public function status_usuario()
  {
    $this->Usuarios_model->status_usuario($this->input->post('id'), $this->input->post('status'));
    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
  }

  public function eliminar_multiple_usuario()
  {
    $this->Usuarios_model->eliminar_multiple_usuario($this->input->post('id'));
  }

  public function status_multiple_usuario()
  {
    $this->Usuarios_model->status_multiple_usuario($this->input->post('id'), $this->input->post('status'));
    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
  }

}//Fin class Bancos
