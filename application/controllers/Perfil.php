<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Perfil extends CI_Controller
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
    $data['modulos'] = $this->Menu_model->modulos();
    //$data['vistas'] = $this->Menu_model->vistas($this->session->userdata('id_usuario'));
    /*$datos['nacionalidades'] = $this->Usuarios_model->nacionalidades();
    $datos['estadosCiviles'] = $this->Usuarios_model->estados_civiles();
    $datos['sexos'] = $this->Usuarios_model->sexos();*/
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
  
    //---
    $datos['roles'] = $this->Usuarios_model->roles();
    $datos['arreglo_datos'] = $this->Usuarios_model->listar_usuarios($this->session->userdata('id_usuario'))[0];

    $datos['mensaje_result'] = $this->actualizar($datos['arreglo_datos']);
    $datos['arreglo_datos'] = $this->Usuarios_model->listar_usuarios($this->session->userdata('id_usuario'))[0];
    //Consumo el servicio segun el id del usuario
    $sepomex = consumir_rest('Sepomex','consultar', array('id_codigo_postal'=>$datos['arreglo_datos']["id_codigo_postal"]));
    //LLamo al helper para que organize los resultados de la consulta al servicio
    $datos["arreglo_sepomex"] = organizar_sepomex($sepomex);
    //echo($datos["arreglo_sepomex"]["d_codigo"]);die('');
    $sepomex_global = consumir_rest('Sepomex','buscar', array('d_codigo'=>$datos["arreglo_sepomex"]["d_codigo"]));
    //--------------------------------------------------------
    isset($sepomex_global)? $temp_data = $sepomex_global->data : $temp_data="";
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
    $datos["super_sepomex"]=array(
                    'estados' => $data['estados'],
                    'ciudades' => $data['ciudades'],
                    'municipios' => $data['municipios'],
                    'colonias' => $data['colonias'],
    );
    //--------------------------------------------------------
    //Ordeno el json de retorno
    /*$datos["super_sepomex"]=array(
                    'estados' => $sepomex_global->data->estados,
                    'ciudades' => $sepomex_global->data->ciudades,
                    'municipios' => $sepomex_global->data->municipios,
                    'colonias' => $sepomex_global->data->colonias,
    );*/
    //var_dump($datos["super_sepomex"]);die("");
    //var_dump($datos["super_sepomex"]["ciudades"][0]->d_ciudad);die("");

    $this->operaciones      = $this->menu_rol_operaciones->control($data['modulos'], $data['vistas']);
    $data['modulos_vistas'] = $this->operaciones;
    $this->load->view('cpanel/header');
    $this->load->view('cpanel/menu', $data);
    $this->load->view('admin/perfil', $datos);
    $this->load->view('cpanel/footer');
  }

  public function buscar_codigos()
  {
    $datos = $this->Usuarios_model->buscar_codigos($this->input->post('codigo'));
    echo json_encode($datos);
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
  private function actualizar($arreglo)
  {
    $resultado['texto'] = "";
    $resultado['tipo'] = "danger";
    $resultado['mostrar'] = "none";

    if($this->input->method() === 'post'){
      $resultado['mostrar'] = "block";
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
          $resultado['texto'] = "<span>El campo teléfono debe tener 12 caracteres!</span>";
          return $resultado;
      }
      /***/
     
      $this->reglas();
      if($this->form_validation->run() == true){
        $usuarioArray = array(
          //'id_rol' => $this->input->post('id_rol'),
          'correo_usuario' => trim($this->input->post('correo_usuario')),
        );
        if($this->input->post('clave_usuario') != ""){
          $usuarioArray['clave_usuario'] = sha1($this->input->post('clave_usuario'));
          //--
          #Valido la longitudd de caracteres de la clave
          if(strlen($this->input->post('clave_usuario'))<6){
            $resultado['texto'] = "<span>El campo contraseña debe tener un mínimo de 6 caracteres!</span>";
            return $resultado;
          }
          //--

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
        /*$personalArray = array(
          'nombre_datos_personales' => trim(mb_strtoupper($this->input->post('nombre_datos_personales'), 'UTF-8')),
          'apellido_p_datos_personales' => trim(mb_strtoupper($this->input->post('apellido_p_datos_personales'), 'UTF-8')),
          'apellido_m_datos_personales' => trim(mb_strtoupper($this->input->post('apellido_m_datos_personales'), 'UTF-8')),
          'fecha_nac_datos_personales' => trim(date("Y-m-d", strtotime($this->input->post('fecha_nac_datos_personales')))),
          'nacionalidad_datos_personales' => $this->input->post('nacionalidad_datos_personales'),
          'curp_datos_personales' => trim(mb_strtoupper($this->input->post('curp_datos_personales'), 'UTF-8')),
          'edo_civil_datos_personales' => $this->input->post('edo_civil_datos_personales'),
          'genero_datos_personales' => $this->input->post('genero_datos_personales'),
        );*/
        
        $idArray = array(
          'id_usuario' => $this->session->userdata('id_usuario'),
          'id_contacto' => $arreglo["id_contacto"],
          'id_datos_personales' => $arreglo["id_datos_personales"],
        );
        $usuario_verificado=$this->Usuarios_model->verificar_usuario($this->input->post('correo_usuario')); //busca si el nombre del banco esta registrado en la base de datos
        //var_dump($usuario_verificado);die('');
        if(count($usuario_verificado)>0){
          // si es mayor a cero, se verifica si el id recibido del formulario es igual al id que se verifico
          if($usuario_verificado[0]['_id']->{'$id'} == $this->session->userdata('id_usuario')){
            //si son iguales, quiere decir que es el mismo registro
            $this->Usuarios_model->actualizar_usuario($usuarioArray, $contactoArray, /*$personalArray*/null, $idArray, $imagen);
            $resultado['texto'] = "<span>El usuario se ha editado exitosamente!</span>"; // envio de mensaje exitoso
            $resultado['tipo'] = "success";
          }else{
            //si son diferentes, quiere decir que ya el nombre del banco se encuentra en uso por otro registro
            $resultado['texto'] = "<span>El correo del usuario ingresado ya se encuentra en uso!</span>";
          }
        }else{
          // si conteo del array es igual a 0, se actualiza el registro
          $this->Usuarios_model->actualizar_usuario($usuarioArray, $contactoArray, $personalArray, $idArray, $imagen);
          $resultado['texto'] = "<span>El usuario se ha editado exitosamente!</span>"; // envio de mensaje exitoso
          $resultado['tipo'] = "success";
        }
      }else{
        // enviar los errores
        $resultado['texto'] = validation_errors();
      }
    }

    return $resultado;
  }

  public function reglas()
  {
      // Reglas para la tabla de usuario
      $this->form_validation->set_rules('correo_usuario','Correo Electrónico','required|valid_email');
      //$this->form_validation->set_rules('correo_confirmar','Confirmar Correo Electrónico','required|valid_email|matches[correo_usuario]');
      //$this->form_validation->set_rules('id_rol','Tipo de Rol','required');
      //$this->form_validation->set_rules('clave_usuario_actual','Contraseña Actual','required');
      if($this->input->post('clave_usuario') != ""){
        $this->form_validation->set_rules('clave_usuario','Contraseña','required');
        $this->form_validation->set_rules('repetir_clave','Repetir Contraseña','required|matches[clave_usuario]');
      }

      // Reglas para la tabla contacto
      $this->form_validation->set_rules('colonia','Código Postal','required');
      $this->form_validation->set_rules('telefono_principal_contacto','Teléfono','required');

      // Reglas para la tabla
      /*$this->form_validation->set_rules('nombre_datos_personales','Nombre(s)','required');
      $this->form_validation->set_rules('apellido_p_datos_personales','Apellido Paterno','required');
      $this->form_validation->set_rules('apellido_m_datos_personales','Apellido Materno','required');
      $this->form_validation->set_rules('fecha_nac_datos_personales','Fecha de Nacimiento','required');
      $this->form_validation->set_rules('nacionalidad_datos_personales','Nacionalidad','required');
      $this->form_validation->set_rules('curp_datos_personales','C.U.R.P.','required');
      $this->form_validation->set_rules('edo_civil_datos_personales','Estado Civil','required');
      $this->form_validation->set_rules('genero_datos_personales','Género.','required');*/

      $this->form_validation->set_message('required', 'El campo %s es obligatorio');
      $this->form_validation->set_message('max_length', 'El Campo %s debe tener un Máximo de %d Caracteres');
      $this->form_validation->set_message('numeric', 'El campo %s debe poseer solo numeros enteros');
      $this->form_validation->set_message('is_unique', 'El valor ingresado en el campo %s ya se encuentra en uso');
      $this->form_validation->set_message('matches', 'El valor ingresado en el campo %s no coincide');
  }

}//Fin class Perfil
