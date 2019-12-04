<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class MiEmpresa extends CI_Controller
{
	private $operaciones;
	function __construct()
	{
    parent::__construct();
    //$this->load->database();
    $this->load->library('session');
    $this->load->model('MiEmpresa_model');
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
    $datos['permiso'] = $this->Menu_model->verificar_permiso_vista('MiEmpresa', $this->session->userdata('id_rol'));
    $data['modulos'] = $this->Menu_model->modulos();
    //Migración Mongo DB
    //$data['vistas'] = $this->Menu_model->vistas($this->session->userdata('id_usuario'));
    $data['vistas'] = $this->Menu_model->vistas($this->session->userdata('id_rol'));
    $datos['breadcrumbs'] = $this->Menu_model->breadcrumbs('MiEmpresa');
    $this->operaciones      = $this->menu_rol_operaciones->control($data['modulos'], $data['vistas']);
    $data['modulos_vistas'] = $this->operaciones;
    $this->load->view('cpanel/header');
    $this->load->view('cpanel/menu', $data);
    $this->load->view('configuracion/MiEmpresa/index', $datos);
    $this->load->view('cpanel/footer');
  }

  public function buscar_codigos()
  {
    $datos=$this->MiEmpresa_model->buscar_codigos($this->input->post('codigo'));
    echo json_encode($datos);
  }

  public function buscar_mi_empresa()
  {
    $bandera  = false;
    $datos=$this->MiEmpresa_model->buscar_mi_empresa();
    if($datos){  
        //--
        foreach ($datos as $key => $value) {
            $sepomex = consumir_rest('Sepomex','consultar', array('id_codigo_postal'=>$value["id_codigo_postal"]));
            //Transformo el la fila de usuario en un array
            //$arreglo_data = get_object_vars($value[0]);
            //LLamo al helper para que organize los resultados de la consulta al servicio
            $arreglo_sepomex = organizar_sepomex($sepomex);
            //unset($listado[$key]);
            //Hago push assoc de la fila de usuario y sus datos respectivos en sepomex
            $listado[] = array_push_assoc($value,$arreglo_sepomex);
            $bandera = true;
        }    
        //--
        if($bandera){
          //Consulto todos los estados/ciudades/municipios/colonias
          $codigo_postal = $listado[0]["d_codigo"];
          //var_dump($listado[0]["d_codigo"]);
          $sepomex_global = consumir_rest('Sepomex','buscar', array('d_codigo'=>$codigo_postal));

          $data = organizar_sepomex($sepomex_global, true);

        }
        else{
          $data = organizar_sepomex("", true);
        }
       


        //Ordeno el json de retorno
        $data=array(
                        'empresa' => $listado,
                        'estados' => $data['estados'],
                        'ciudades' => $data['ciudades'],
                        'municipios' => $data['municipios'],
                        'colonias' => $data['colonias'],
        );
        //--
        echo json_encode($data);
    }  
  }

  public function actualizar_mi_empresa()
  {
    $this->reglas_mi_empresa();
    $this->mensajes_reglas_mi_empresa();
    $fecha = new MongoDB\BSON\UTCDateTime();

    if($this->form_validation->run() == true){
      $dataContacto=array(
          'id_codigo_postal' => $this->input->post('colonia'),
          'telefono_principal_contacto' => trim($this->input->post('telefono_principal_contacto')),
          'telefono_movil_contacto' => trim($this->input->post('telefono_principal_contacto')),
          'correo_opcional_contacto' => trim($this->input->post('correo_opcional_contacto')),
          //'direccion_contacto' => trim(mb_strtoupper($this->input->post('direccion_contacto'), 'UTF-8')),
          'calle_contacto' => trim(mb_strtoupper($this->input->post('calle_contacto'), 'UTF-8')),
          'exterior_contacto' => trim(mb_strtoupper($this->input->post('exterior_contacto'))),
          'interior_contacto' => trim(mb_strtoupper($this->input->post('interior_contacto'))),
          'status'=>true,
          'eliminado'=>false,
          /*'auditoria' => [array(
                                "coduser" => (int) $this->session->userdata('datos_usuario')['COD_USUARIO'],
                                "nomuser" => $this->session->userdata('datos_usuario')['NOM_USUARIO'],
                                "fecha" => new MongoDate(),
                                "accion" => "Nuevo registro",
                                "operacion" => ""
                            )]*/
      );
      $this->MiEmpresa_model->actualizar_mi_empresa($this->input->post('id_mi_empresa'), mb_strtoupper($this->input->post('nombre_mi_empresa'), 'UTF-8'), $this->input->post('rfc_mi_empresa'), $this->input->post('id_contacto'), $dataContacto);
      echo json_encode("<span>Datos editado exitosamente!</span>"); // envio de mensaje exitoso
    }else{
      // enviar los errores
      echo validation_errors();
    }
  }

  public function reglas_mi_empresa()
  {
    $this->form_validation->set_rules('nombre_mi_empresa','Nombre de Empresa','required');
    $this->form_validation->set_rules('rfc_mi_empresa','RFC','required|max_length[14]');
    $this->form_validation->set_rules('telefono_principal_contacto','Teléfono Principal','required');
    $this->form_validation->set_rules('correo_opcional_contacto','Correo Electrónico','required|valid_email');
    $this->form_validation->set_rules('calle_contacto','Calle','required');
    $this->form_validation->set_rules('exterior_contacto','Número exterior','required');
    
    $this->form_validation->set_rules('colonia','Colonia','required');
  }

  public function mensajes_reglas_mi_empresa(){
    $this->form_validation->set_message('required', 'El campo %s es obligatorio');
    $this->form_validation->set_message('max_length', 'El Campo %s debe tener un Máximo de %d Caracteres');
    $this->form_validation->set_message('valid_email', 'El Campo %s debe ser un correo');
  }

}//Fin class Bancos