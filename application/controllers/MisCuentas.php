<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class MisCuentas extends CI_Controller
{
  private $operaciones;
  function __construct()
  {
    parent::__construct();
    //$this->load->database();
    $this->load->library('session');
    $this->load->model('Modulos_model');
    $this->load->model('Menu_model');
    $this->load->model('MiCuenta_model');
    $this->load->library('form_validation');
    $this->load->helper('consumir_rest');
    if (!$this->session->userdata("login")) {
      redirect(base_url()."admin");
    }
  }

  public function index()
  {
    $datos['permiso'] = $this->Menu_model->verificar_permiso_vista('MisCuentas', $this->session->userdata('id_rol'));
    $data['modulos'] = $this->Menu_model->modulos();
    //$data['vistas'] = $this->Menu_model->vistas($this->session->userdata('id_usuario'));
    //--Modificacion para Mongo DB
    $data['vistas'] = $this->Menu_model->vistas($this->session->userdata('id_rol'));    
    $datos['breadcrumbs'] = $this->Menu_model->breadcrumbs('MisCuentas');
    $this->operaciones      = $this->menu_rol_operaciones->control($data['modulos'], $data['vistas']);
    $data['modulos_vistas'] = $this->operaciones;



    $datos['bancos'] = $this->armarSelect('banco');
    
    $datos['plazas'] = $this->armarSelect('plaza');
  
    $datos['tipoCuentas'] = $this->armarSelect('tipoCuenta');


    $this->load->view('cpanel/header');
    $this->load->view('cpanel/menu', $data);
    $this->load->view('configuracion/MisCuentas/index', $datos);
    $this->load->view('cpanel/footer');
  }



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



  public function listado_bancos()
  { 
 
     $listado = $this->MiCuenta_model->listarCuentas();                            
     echo json_encode($listado);
  }


  public function GetBancosCobranza()
  {
    $result = $this->MiCuenta_model->listarCuentasCobranza();     

    $listado = [];
    foreach ($result as $key => $value) {

      $bancos = $this->armarSelect('banco');

      foreach ($bancos as $key => $banco) {
         
         if ($banco->id_banco == $value["id_banco"]) {
           $value["name_banco"] = $banco->nombre_banco;
         }
      }
      
      $listado[] = $value;
    }   

    echo json_encode($listado);
  }


  public function GetCuentasByBanco()
  {
    $id_banco =  $this->input->get('id_banco');

    $resultados = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('id_banco' => $id_banco, 'eliminado'=>false))->get("mis_cuentas");

    echo json_encode($resultados);

  }
  public function store()
  {


    $fecha      = new MongoDB\BSON\UTCDateTime();
    $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));


    $formulario       = $this->input->post();
    $clabe            = (isset($formulario['clabe'])) ? $formulario['clabe'] : '';
    $id_cliente       = (isset($formulario['id_cliente'])) ? $formulario['id_cliente'] : '';
    $numero_cuenta    = (isset($formulario['numero_cuenta'])) ? $formulario['numero_cuenta'] : '';
    $tipo_cuenta      = (isset($formulario['tipo_cuenta'])) ? $formulario['tipo_cuenta'] : '';
    $banco            = (isset($formulario['banco'])) ? $formulario['banco'] : '';
    $swift            = (isset($formulario['swift'])) ? $formulario['swift'] : '';
    $tipo_plaza       = (isset($formulario['codigo_plaza'])) ? $formulario['codigo_plaza'] : '';
    $sucursal         = (isset($formulario['sucursal'])) ? $formulario['sucursal'] : '';

    if ($tipo_plaza == '') {
      $tipo_plaza = null;
    }

    $datos = array(
                  'clabe_cuenta'         => $clabe,
                  'numero_cuenta'        => $numero_cuenta,
                  'tipo_cuenta'          => $tipo_cuenta,
                  'id_banco'             => $banco,
                  'swift_cuenta'         => $swift,
                  'id_plaza'             => $tipo_plaza,
                  'sucursal_cuenta'      => $sucursal,
                  'status' => true,
                  'eliminado' => false,
                   
                  'auditoria' => [array(
                                      "cod_user" => $id_usuario,
                                      "nomuser"  => $this->session->userdata('nombre'),
                                      "fecha"    => $fecha,
                                      "accion"    => "Nuevo registro esquema",
                                      "operacion" => ""
                                  )]
            );
    
                  
    $this->reglas_validacion('insert');
    $this->mensajes_reglas();
    if ($this->form_validation->run()== true) {
  
       $this->MiCuenta_model->guardarCuenta($datos);
       echo json_encode("<span>La cuenta se ha registrado exitosamente!</span>");
      } else{
        echo validation_errors();
      } 
  }

  public function update(){

      $formulario          = $this->input->post(); 
      $numero_cuenta       = (isset($formulario['numero_cuenta'])) ? $formulario['numero_cuenta'] : '';
      $tipo_cuenta         = (isset($formulario['tipo_cuenta'])) ? $formulario['tipo_cuenta'] : '';
      $banco               = (isset($formulario['banco'])) ? $formulario['banco'] : '';
      $swift               = (isset($formulario['swift'])) ? $formulario['swift'] : '';
      $tipo_plaza          = (isset($formulario['codigo_plaza'])) ? $formulario['codigo_plaza'] : '';
      $sucursal            = (isset($formulario['sucursal'])) ? $formulario['sucursal'] : '';
      $id_cuenta           = (isset($formulario['id_cuenta'])) ? $formulario['id_cuenta'] : '';



      $datos = array(
                    'numero_cuenta'        => trim(mb_strtoupper($numero_cuenta, 'UTF-8')),
                    'tipo_cuenta'          => $tipo_cuenta,
                    'id_banco'            => $banco,
                    'swift_cuenta'         => trim(mb_strtoupper($swift, 'UTF-8')),
                    'id_plaza'             => $tipo_plaza,
                    'sucursal_cuenta'      => trim(mb_strtoupper($sucursal, 'UTF-8'))
                  );

      $this->reglas_validacion('update','cuentaCliente');
      $this->mensajes_reglas();
      if ($this->form_validation->run()== true) {
        $this->MiCuenta_model->actualizarCuentaCliente($id_cuenta, $datos);
        echo json_encode("<span>El registro se ha editado exitosamente!</span>");
     }else{
      echo validation_errors();
     }

  }







  public function getcuentabanco($id_banco)
  {
    $cuenta = $this->MiCuenta_model->getcuentabanco($id_banco);
    echo json_encode($cuenta);
  }

  public function reglas_validacion($method)
  {
    if($method=="insert"){
       $this->form_validation->set_rules('clabe','CLABE ','required');
       $this->form_validation->set_rules('numero_cuenta','Numero de Cuenta ','required');
      // $this->form_validation->set_rules('tipo_cuenta'  ,'Tipo de Cuenta','required');
      // $this->form_validation->set_rules('banco','Banco','required');
    }else if($method=="update"){
      //$this->form_validation->set_rules('clabe','CLABE ','required');
     // $this->form_validation->set_rules('tipo_cuenta','Tipo de Cuenta','required');
      $this->form_validation->set_rules('numero_cuenta','Numero de Cuenta ','required');
     // $this->form_validation->set_rules('banco','Banco','required');     
    }
  }

  public function mensajes_reglas(){
    $this->form_validation->set_message('required', 'El campo %s es obligatorio');
    $this->form_validation->set_message('min_length', 'El Campo %s debe tener un Mínimo de %d Caracteres');
    $this->form_validation->set_message('max_length', 'El Campo %s debe tener un Máximo de %d Caracteres');
    $this->form_validation->set_message('numeric', 'El campo %s debe poseer solo numeros enteros');
    $this->form_validation->set_message('is_unique', 'El valor ingresado en el campo %s ya se encuentra en uso');
  }

  public function delete ()
  {
    $id = $this->input->post('id');

    $id_cuenta = new MongoDB\BSON\ObjectId($id);

    $data = array(
      "eliminado" => true
    );
  
    if ($this->validCuenta($id)) {
      echo "La Cuenta se encuentra asociada a otro registro";
      return false;
    }else{
      $modificar1 = $this->mongo_db->where(array('_id'=>$id_cuenta))->set($data)->update("mis_cuentas");
      echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
    }
    
    
  }



  public function validCuenta($id)
  {
     $resultados = $this->mongo_db->where(array('recibos.cuenta'=>$id))->get("recibos_cobranzas");

     if ($resultados) {
       return true;
     }else{
      return false;
     }
  }




  public function statusCuenta()

  {
    $id = $this->input->post('id');
    $status = $this->input->post('status'); 


    $id_cuenta = new MongoDB\BSON\ObjectId($id);
    $data = array(
      "status" => $status == 1 ? true : false
    );
    $modificar1 = $this->mongo_db->where(array('_id'=>$id_cuenta))->set($data)->update("mis_cuentas");

    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
  }




 public function eliminar_multiple()
  {
    $this->MiCuenta_model->eliminar_multiple($this->input->post('id'));
  }

 public function status_multiple()
  {
    $this->MiCuenta_model->status_multiple($this->input->post('id'), $this->input->post('status'), 'mis_cuentas');
    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso

  }

}//Fin class Bancos