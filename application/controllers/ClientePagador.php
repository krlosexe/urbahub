<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ClientePagador extends CI_Controller
{
    function __construct()
    {
    parent::__construct();
    //$this->load->database();
    $this->load->library('session');
    $this->load->library('form_validation');
    $this->load->model('ClientePagador_model');
    $this->load->model('Usuarios_model');
    $this->load->model('Menu_model');
    //--
    $this->load->helper('consumir_rest');
    $this->load->helper('organizar_sepomex');
    $this->load->helper('array_push_assoc');
    //--
    if (!$this->session->userdata("login")) {
      redirect(base_url()."admin");
    }
  }
  public function index(){
    $datos['permiso'] = $this->Menu_model->verificar_permiso_vista('ClientePagador', $this->session-> userdata('id_rol'));
    $datos['breadcrumbs'] = $this->Menu_model->breadcrumbs('ClientePagador');
    //$datos['actividadesEconomicas'] = $this->ClientePagador_model->armarSelect('actividadEconomica');
    $datos['actividadesEconomicas'] = $this->armarSelect('actividadEconomica');
    
    $arreglo_nacionalidad = consumir_rest('Lista_Valor','buscar', array('cod_static_tipo_valor'=>'NACIONALIDAD'));
    $datos['nacionalidades'] = $arreglo_nacionalidad->data;
    
    $datos['bancos'] = $this->armarSelect('banco');
    
    $datos['plazas'] = $this->armarSelect('plaza');
  
    $datos['giros'] = $this->armarSelect('giro');
    //var_dump($datos['giros']);die;
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
    $this->load->view('catalogo/ClientePagador/index', $datos);
    $this->load->view('cpanel/footer');
  }
  
  /*
  *
  */
  public function cuentas($id_cliente = "", $editable = "")
  {
      $datos['permiso'] = $this->Menu_model->verificar_permiso_vista('ClientePagador', $this->session-> userdata('id_rol'));
      
      $datos['breadcrumbs'] = $this->Menu_model->breadcrumbs('ClientePagador');

      $datos['actividadesEconomicas'] = $this->armarSelect('actividadEconomica');
      
      $arreglo_nacionalidad = consumir_rest('Lista_Valor','buscar', array('cod_static_tipo_valor'=>'NACIONALIDAD'));
      $datos['nacionalidades'] = $arreglo_nacionalidad->data;

      $datos['bancos'] = $this->armarSelect('banco');

      $datos['plazas'] = $this->armarSelect('plaza');

      $datos['tipoCuentas'] = $this->armarSelect('tipoCuenta');

      //$datos['actividadesEconomicas'] = $this->ClientePagador_model->armarSelect('actividadEconomica');
      //$datos['nacionalidades'] = $this->Usuarios_model->nacionalidades();
      //$datos['bancos'] = $this->ClientePagador_model->armarSelect('banco');
      //$datos['plazas'] = $this->ClientePagador_model->armarSelect('plaza');
      //$datos['tipoCuentas'] = $this->ClientePagador_model->armarSelect('tipoCuenta');
      $data['modulos'] = $this->Menu_model->modulos();
      $data['vistas'] = $this->Menu_model->vistas($this->session-> userdata('id_rol'));
      $datos['id_cliente']= $id_cliente;
      $datos['editable'] = $editable;

      $data['modulo_user'] = [];

      foreach ($data['modulos'] as $modulo) {
          foreach ($data['vistas'] as $vista) {
              if((string)$modulo["_id"]->{'$id'} == (string)$vista->id_modulo_vista){
                $data["modulo_user"][] = $modulo["_id"]->{'$id'};
              }
          }
        }

      /*foreach ($data['modulos'] as $modulo) {
          foreach ($data['vistas'] as $vista) {
              if($modulo->id_modulo_vista == $vista->id_modulo_vista){
                $data["modulo_user"][] = $modulo->id_modulo_vista;
              }
          }
        }*/


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
      //$this->load->view('cpanel/menu', $data);
      $this->load->view('catalogo/ClientePagador/cuentas', $datos);
      $this->load->view('cpanel/footer');
  }
  /*
  * Consulta de listado cliente pagador
  */
  public function listado_clientePagador()
  {
    $listado = $this->ClientePagador_model->listarClientePagador();
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

      $arreglo_data["giro_merca_desc"] = is_array($giro_merca->data)?$giro_merca->data[0]->nombre_lista_valor:(is_object($giro_merca->data)?$giro_merca->data->nombre_lista_valor:'');
      //unset($listado[$key]);
      //Hago push assoc de la fila de usuario y sus datos respectivos en sepomex
      $listado2[] = array_push_assoc($arreglo_data,$arreglo_sepomex);
    }
    //var_dump($listado2);die('');  
    echo json_encode($listado2);
  }
  /*
  *
  */
  /*
  *   Consulta de listado cliente pagador nuevo cambio mejora performance
  */
  public function listado_clientePagador_performance(){
    $listado = $this->ClientePagador_model->listarClientePagador();
    
    echo json_encode($listado);
  }
  /*
  * Metodo que consume servicio ag para armar lista de cliente pagador
  */
  public function listado_clientePagador_servicio(){
    $listado2 = array();
    $formulario  = $this->input->post();
    $value = $formulario["data"];
    //Consumo el servicio segun el id del usuario
    $sepomex = consumir_rest('Sepomex','consultar', array('id_codigo_postal'=>$value["id_codigo_postal"]));
    //Transformo el la fila de usuario en un array

    //$arreglo_data = get_object_vars($value);

    //$arreglo_data = $value;

    //LLamo al helper para que organize los resultados de la consulta al servicio
    $arreglo_sepomex = organizar_sepomex($sepomex);
    /*
    * Actividad economica
    */
    if($value["actividad_e_cliente"]!=""){
      $actividad_economica = consumir_rest('Lista_Valor','consultar', array('id_lista_valor'=>$value["actividad_e_cliente"]));

      $arreglo_data["actividad_economica"] = is_array($actividad_economica->data)?$actividad_economica->data[0]->nombre_lista_valor:(is_object($actividad_economica->data)?$actividad_economica->data->nombre_lista_valor:'');
    }else{
      $arreglo_data["actividad_economica"] = "";
    }
    
    /*
    * Pais
    */
    if($value["pais_cliente"]!=""){
      $pais = consumir_rest('Lista_Valor','consultar', array('id_lista_valor'=>$value["pais_cliente"]));
      
      $arreglo_data["pais_origen"] = is_array($pais->data)?$pais->data[0]->nombre_lista_valor:(is_object($pais->data)?$pais->data->nombre_lista_valor:'');
    }else{
      $arreglo_data["pais_origen"] = "";
    }
    /*
    * Nacionalidad
    */
    if($value["nacionalidad_datos_personales"]){
      $nacionalidad = consumir_rest('Lista_Valor','consultar', array('id_lista_valor'=>$value["nacionalidad_datos_personales"]));
      $arreglo_data["pais_nacionalidad"] = is_array($nacionalidad->data)?$nacionalidad->data[0]->nombre_lista_valor:(is_object($nacionalidad->data)?$nacionalidad->data->nombre_lista_valor:'');
    }else{
      $arreglo_data["pais_nacionalidad"] = "";
    }
    
    //--Giro mercantil
    if($value["giro_mercantil"]!=""){
      $giro_merca = consumir_rest('Lista_Valor','consultar', array('id_lista_valor'=>$value["giro_mercantil"]));
      $arreglo_data["giro_merca_desc"] = is_array($giro_merca->data)?$giro_merca->data[0]->nombre_lista_valor:(is_object($giro_merca->data)?$giro_merca->data->nombre_lista_valor:'');
    }else{
      $arreglo_data["giro_merca_desc"] = "";
    }
    //unset($listado[$key]);
    //Hago push assoc de la fila de usuario y sus datos respectivos en sepomex
    $listado2 = array_push_assoc($arreglo_data,$arreglo_sepomex);
    //var_dump($listado2);die('');
    //var_dump($listado2);die('');  
    echo json_encode($listado2);
  }
  /*
  * 
  */
  /***/
  public function rep_legal($id_cliente="", $editable = "")
  {
    $datos['permiso'] = $this->Menu_model->verificar_permiso_vista('ClientePagador', $this->session-> userdata('id_rol'));
    $datos['breadcrumbs'] = $this->Menu_model->breadcrumbs('ClientePagador');
    /*$datos['actividadesEconomicas'] = $this->ClientePagador_model->armarSelect('actividadEconomica');
    $datos['nacionalidades'] = $this->Usuarios_model->nacionalidades();
    $datos['tipoCuentas'] = $this->ClientePagador_model->armarSelect('tipoCuenta');*/
    
    $datos['actividadesEconomicas'] = $this->armarSelect('actividadEconomica');
    
    $arreglo_nacionalidad = consumir_rest('Lista_Valor','buscar', array('cod_static_tipo_valor'=>'NACIONALIDAD'));
    $datos['nacionalidades'] = $arreglo_nacionalidad->data;

    $datos['bancos'] = $this->armarSelect('banco');

    $datos['plazas'] = $this->armarSelect('plaza');

    $datos['tipoCuentas'] = $this->armarSelect('tipoCuenta');

    $data['modulos'] = $this->Menu_model->modulos();
    
    $data['vistas'] = $this->Menu_model->vistas($this->session->userdata('id_rol'));
    
    $datos['id_cliente']= $id_cliente;
    
    $datos['editable']= $editable;
    
    /*foreach ($data['modulos'] as $modulo) {
        foreach ($data['vistas'] as $vista) {
            if($modulo->id_modulo_vista == $vista->id_modulo_vista){
              $data["modulo_user"][] = $modulo->id_modulo_vista;
            }
        }
      }*/

    $data['modulo_user'] = [];

    foreach ($data['modulos'] as $modulo) {
        foreach ($data['vistas'] as $vista) {
            if((string)$modulo["_id"]->{'$id'} == (string)$vista->id_modulo_vista){
              $data["modulo_user"][] = $modulo["_id"]->{'$id'};
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
    $this->load->view('catalogo/ClientePagador/rep_legal', $datos);
    $this->load->view('cpanel/footer');
  }

  public function contacto($id_cliente = "", $editable =""){

    $datos['permiso'] = $this->Menu_model->verificar_permiso_vista('ClientePagador', $this->session-> userdata('id_rol'));
    $datos['breadcrumbs'] = $this->Menu_model->breadcrumbs('ClientePagador');
    $datos['id_cliente']= $id_cliente;
    $datos['editable']= $editable;
    $data['modulos'] = $this->Menu_model->modulos();
    $data['vistas'] = $this->Menu_model->vistas($this->session->userdata('id_rol'));

    $data['modulo_user'] = [];

    foreach ($data['modulos'] as $modulo) {
        foreach ($data['vistas'] as $vista) {
            if((string)$modulo["_id"]->{'$id'} == (string)$vista->id_modulo_vista){
              $data["modulo_user"][] = $modulo["_id"]->{'$id'};
            }
        }
    }

    /*foreach ($data['modulos'] as $modulo) {
        foreach ($data['vistas'] as $vista) {
            if($modulo->id_modulo_vista == $vista->id_modulo_vista){
              $data["modulo_user"][] = $modulo->id_modulo_vista;
            }
        }
      }
    */

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
    //$this->load->view('cpanel/menu', $data);
    $this->load->view('catalogo/ClientePagador/contacto', $datos);
    $this->load->view('cpanel/footer');
  }

  public function listado_cuentasClientePagador($id_cliente="")
  {
    $listado2 = array();  
     $listado = $this->ClientePagador_model->listarCuentasCliente($id_cliente);
     
     foreach ($listado as $key => $value) {
        $arreglo_tipo_cuenta = consumir_rest('Lista_Valor','consultar', array('id_lista_valor'=>$value["tipo_cuenta"]));
        //var_dump($arreglo_esquemas->data[0]->nombre_lista_valor)."-";
        //Transformo el la fila de  en un array
        //$arreglo_data = get_object_vars($value);
        $arreglo_data = $value;

        $arreglo_data["nombre_lista_valor"] = is_array($arreglo_tipo_cuenta->data)?$arreglo_tipo_cuenta->data[0]->nombre_lista_valor:(is_object($arreglo_tipo_cuenta->data)?$arreglo_tipo_cuenta->data->nombre_lista_valor:'');

        $bancos = consumir_rest('Banco','consultar', array('id_banco'=>$value["id_banco"]));
        //$arreglo_data["id_banco"] = $bancos->data[0]->id_banco;
        $arreglo_data["id_banco"] = $bancos->data->id_banco;

        //$arreglo_data["id_banco"] = $value->id_banco;

        //$arreglo_data["nombre_banco"] = $bancos->data[0]->nombre_banco;
        $arreglo_data["nombre_banco"] = $bancos->data->nombre_banco;

        $plazas = consumir_rest('Plaza','consultar', array('id_plaza'=>$value["id_plaza"]));
        
        //$arreglo_data["id_plaza"] = $plazas->data[0]->id_plaza;
        $arreglo_data["id_plaza"] = $plazas->data->id_plaza;

        //$arreglo_data["nombre_plaza"] = $plazas->data[0]->nombre_plaza;
        $arreglo_data["nombre_plaza"] = $plazas->data->nombre_plaza;

        $listado2[] = $arreglo_data;
        //var_dump($listado2);die('');
      }
      echo json_encode($listado2);   
  }
  /*
  * Listado rep legal
  */
  public function listado_repLegal($id_cliente=""){
    $listado = $this->ClientePagador_model->listarRepLegal($id_cliente);
    echo json_encode($listado);
  }
  /*
  * 
  */
    public function listado_contacto($id_cliente="")
  {
    $listado = $this->ClientePagador_model->listarContacto($id_cliente);  
      echo json_encode($listado);
  }

  /*
  * Registro de cliente pagador....
  */

  public function registrar_clientePagador(){
        
        $fecha = new MongoDB\BSON\UTCDateTime();
        
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        
        $formulario = $this->input->post();

        $imgProfile            = $this->input->post('imgProfile');
        $img_n_identificacion  = $this->input->post('img_n_identificacion');
        $img_domicilio         = $this->input->post('img_domicilio');
        $img_acta_constitutiva = $this->input->post('img_acta_constitutiva');

        // datos generales cliente
        $tipo_persona               = (isset($formulario['rad_tipoper'])) ? $formulario['rad_tipoper'] : '';
        $nombre_cliente             = (isset($formulario['nombre_cliente'])) ? $formulario['nombre_cliente'] : '';
        $apellido_paterno_cliente   = (isset($formulario['apellido_paterno_cliente'])) ? $formulario['apellido_paterno_cliente'] : '';
        $apellido_materno_cliente   = (isset($formulario['apellido_materno_cliente'])) ? $formulario['apellido_materno_cliente'] : '';
        $curp_datos_personales      = (isset($formulario['curp_datos_personales'])) ? $formulario['curp_datos_personales'] : '';
        $rfc_cliente                = (isset($formulario['rfc'])) ? $formulario['rfc'] : '';
        $actividad_economica        = (isset($formulario['actividad_economica'])) ? $formulario['actividad_economica'] : '';
        $fecha_nac_datos_personales = (isset($formulario['fecha_nac_datos_personales'])) ? $formulario['fecha_nac_datos_personales'] : '';
        $correo_clente              = (isset($formulario['correo_clente'])) ? $formulario['correo_clente'] : '';
        $telefono_cliente           = (isset($formulario['telefono_cliente'])) ? $formulario['telefono_cliente'] : '';
        $actividad_economica        = (isset($formulario['actividad_economica'])) ? $formulario['actividad_economica'] : '';
        $empresa_pertenece          = (isset($formulario['empresa_pertenece'])) ? $formulario['empresa_pertenece'] : '';
        $pais_nacionalidad          = (isset($formulario['pais_nacionalidad'])) ? $formulario['pais_nacionalidad'] : '';
        $pais_origen                = (isset($formulario['pais_origen'])) ? $formulario['pais_origen'] : '';
        //datos cuando la tipo de persona es moral
        $razon_social              = (isset($formulario['razon_social'])) ? $formulario['razon_social'] : '';
        $rfc_moral                 = (isset($formulario['rfc_moral'])) ? $formulario['rfc_moral'] : '';
        $fecha_cons_r              = (isset($formulario['fecha_cons_r'])) ? $formulario['fecha_cons_r'] : '';
        $acta_constutiva_r         = (isset($formulario['acta_constutiva_r'])) ? $formulario['acta_constutiva_r'] : '';
        $giro_mercantil_r          = (isset($formulario['giro_mercantil_r'])) ? $formulario['giro_mercantil_r'] : '';
        $correo_moral_m            = (isset($formulario['correo_moral_m'])) ? $formulario['correo_moral_m'] : '';
        $telefono_moral_m          = (isset($formulario['telefono_moral_m'])) ? $formulario['telefono_moral_m'] : '';
        // datos domicilio fiscal cliente
        $calle_cliente              = (isset($formulario['calle_cliente'])) ? $formulario['calle_cliente'] : '';
        $exterior_cliente           = (isset($formulario['exterior_cliente'])) ? $formulario['exterior_cliente'] : '';
        $interior_cliente           = (isset($formulario['interior_cliente'])) ? $formulario['interior_cliente'] : '';
        $codigo_postal_domicilio    = (isset($formulario['codigo_postal_domicilio'])) ? $formulario['codigo_postal_domicilio'] : '';
        $colonia                    = (isset($formulario['colonia'])) ? $formulario['colonia'] : '';
            // datos rep legal cliente
        $nombre_representante   = (isset($formulario['nombre_representante'])) ? $formulario['nombre_representante'] : '';
        $apellido_paterno_rep   = (isset($formulario['apellido_paterno_rep'])) ? $formulario['apellido_paterno_rep'] : '';
        $apellido_materno_rep   = (isset($formulario['apellido_materno_rep'])) ? $formulario['apellido_materno_rep'] : '';
        $rfc_representante      = (isset($formulario['rfc_representante'])) ? $formulario['rfc_representante'] : '';
        $curp_rep_legal         = (isset($formulario['curp_rep_legal'])) ? $formulario['curp_rep_legal'] : '';
        $correo_rep_legal       = (isset($formulario['correo_rep_legal'])) ? $formulario['correo_rep_legal'] : '';
        $telf_rep_legal         = (isset($formulario['telf_rep_legal'])) ? $formulario['telf_rep_legal'] : '';
        // datos cuenta cliente
        $clabe            = (isset($formulario['clabe'])) ? $formulario['clabe'] : '';
        $numero_cuenta    = (isset($formulario['numero_cuenta'])) ? $formulario['numero_cuenta'] : '';
        $tipo_cuenta      = (isset($formulario['tipo_cuenta'])) ? $formulario['tipo_cuenta'] : '';
        $banco            = (isset($formulario['banco'])) ? $formulario['banco'] : '';
        $swift            = (isset($formulario['swift'])) ? $formulario['swift'] : '';
        $tipo_plaza     = (isset($formulario['codigo_plaza'])) ? $formulario['codigo_plaza'] : '';
        $sucursal         = (isset($formulario['sucursal'])) ? $formulario['sucursal'] : '';
        // datos contacto cliente
        $nombre_contacto             = (isset($formulario['nombre_contacto'])) ? $formulario['nombre_contacto'] : '';
        $telefono_principal_contacto = (isset($formulario['telefono_principal_contacto'])) ? $formulario['telefono_principal_contacto'] : '';
        $telefono_movil_contacto     = (isset($formulario['telefono_movil_contacto'])) ? $formulario['telefono_movil_contacto'] : '';
        $telefono_casa_contacto      = (isset($formulario['telefono_casa_contacto'])) ? $formulario['telefono_casa_contacto'] : '';
        $telefono_trabajo_contacto   = (isset($formulario['telefono_trabajo_contacto'])) ? $formulario['telefono_trabajo_contacto'] : '';
        $telefono_fax_contacto       = (isset($formulario['telefono_fax_contacto'])) ? $formulario['telefono_fax_contacto'] : '';
        $correo_contacto             = (isset($formulario['correo_contacto'])) ? $formulario['correo_contacto'] : '';
        $coreo_contactp_opc_r        = (isset($formulario['coreo_contactp_opc_r'])) ? $formulario['coreo_contactp_opc_r'] : '';
        $imagen           = (isset($formulario['rfc_img'])) ? $formulario['rfc_img'] : '';
        $imagenDomFiscal  = (isset($formulario['domicilio_fiscal_img'])) ? $formulario['domicilio_fiscal_img'] : '';
        $imagenActa       = (isset($formulario['acta_img_r'])) ? $formulario['acta_img_r'] : '';
        $imagenMoral      = (isset($formulario['rfc_imag_mo'])) ? $formulario['rfc_imag_mo'] : '';
        $imagenLegal      = (isset($formulario['rfc_img_rep'])) ? $formulario['rfc_img_rep'] : '';
        $imagenCliente      = (isset($formulario['cliente_img'])) ? $formulario['cliente_img'] : '';
        $imagenClienteMoral      = (isset($formulario['cliente_img_moral'])) ? $formulario['cliente_img_moral'] : '';
    
    //--Imagen del cliente pagador //-------------------------------------
    //var_dump($imagenDomFiscal);die("xxx");

    $array_img = explode(".", $imgProfile);
    $ext = end($array_img);

    if($ext == "pdf"){
      echo "No se permiten archivos pdf para la foto de perfil";
      return false;
    }

    if(!empty($imgProfile) && $imgProfile != "undefined")
    {
        if(file_exists(sys_get_temp_dir().'/'.$imgProfile))
        {
          rename(sys_get_temp_dir().'/'.$imgProfile,
                                  'assets/cpanel/ClientePagador/images/'.$imgProfile
                                );
                                //unlink(sys_get_temp_dir().'/'.$imagen);                        
        }
    }else if($tipo_persona == "fisica"){
        echo "Debe seleccionar la imagen del cliente";die('');
    }

    if(!empty($imgProfile) && $imgProfile != "undefined" )
    {
        if(file_exists(sys_get_temp_dir().'/'.$imgProfile))
        {
          rename(sys_get_temp_dir().'/'.$imgProfile,
                                  'assets/cpanel/ClientePagador/images/'.$imgProfile
                                );
                                //unlink(sys_get_temp_dir().'/'.$imagen);                        
        }
    }else if($tipo_persona == "moral"){
        //var_dump($imagenClienteMoral);
        echo "Debe seleccionar la imagen del cliente";die('');
    }
    //--------------------------------------------------------------------
    //-Imagen
    if(!empty($img_n_identificacion) && $img_n_identificacion != "undefined" )
    {
        if(file_exists(sys_get_temp_dir().'/'.$img_n_identificacion))
        {
          rename(sys_get_temp_dir().'/'.$img_n_identificacion,
                                  'assets/cpanel/ClientePagador/images/'.$img_n_identificacion
                                );
                                //unlink(sys_get_temp_dir().'/'.$imagen);                        
        }
    }else if($tipo_persona == "fisica"){
        echo "Debe seleccionar la imagen de la copia escaneada del RFC";die('');
    }
    //--
    //--Imagen Moral
    if(!empty($img_n_identificacion) && $img_n_identificacion != "undefined"  )
    {
        if(file_exists(sys_get_temp_dir().'/'.$img_n_identificacion))
        {
          rename(sys_get_temp_dir().'/'.$img_n_identificacion,
                                  'assets/cpanel/ClientePagador/images/'.$img_n_identificacion
                                );
                                //unlink(sys_get_temp_dir().'/'.$imagen);
                               
        }
    }else if($tipo_persona == "moral"){
        echo "Debe seleccionar la imagen de la copia escaneada del RFC";die('');
    }
    //--
    //--Imagen de acta
    if(!empty($img_acta_constitutiva) && $img_acta_constitutiva != "undefined" )
    {
        if(file_exists(sys_get_temp_dir().'/'.$img_acta_constitutiva))
        {
          rename(sys_get_temp_dir().'/'.$img_acta_constitutiva,
                                  'assets/cpanel/ClientePagador/images/'.$img_acta_constitutiva
                                );
                                //unlink(sys_get_temp_dir().'/'.$imagen);
                               
        }
    }
    else if($tipo_persona == "moral"){
        echo "Debe seleccionar la imagen de la copia escaneada del Acta Constitutiva";die('');
    }
    //--
    //-Imagen dominio fiscal
    if(!empty($img_domicilio) && $img_domicilio != "undefined" )
    {
        if(file_exists(sys_get_temp_dir().'/'.$img_domicilio))
        {
          rename(sys_get_temp_dir().'/'.$img_domicilio,
                                  'assets/cpanel/ClientePagador/images/'.$img_domicilio
                                );
                                //unlink(sys_get_temp_dir().'/'.$imagen);
                               
        }
    }else{
        echo "Debe seleccionar la imagen de la copia escaneada del Domicilio Fiscal";die('');
    }
    //--
    
    
    //--
    //--Imagen legal
    if(!empty($imagenLegal) && $imagenLegal != "undefined" )
    {
        if(file_exists(sys_get_temp_dir().'/'.$imagenLegal))
        {
          rename(sys_get_temp_dir().'/'.$imagenLegal,
                                  'assets/cpanel/ClientePagador/images/'.$imagenLegal
                                );
                                //unlink(sys_get_temp_dir().'/'.$imagen);
                               
        }
    }else if(($tipo_persona == "moral")&&($rfc_representante!="")&&($telf_rep_legal!="")&&($correo_rep_legal!="")){
        echo "Debe seleccionar la imagen de la copia escaneada del RFC del representante legal";die('');
    }
    
    // rfc domicilio fiscal
    
        $datosPersonales= array(
                                'nombre_datos_personales'         => trim(mb_strtoupper($nombre_cliente, 'UTF-8')),
                                'apellido_p_datos_personales'     => trim(mb_strtoupper($apellido_paterno_cliente, 'UTF-8')),
                                'apellido_m_datos_personales'     => trim(mb_strtoupper($apellido_materno_cliente, 'UTF-8')),
                                'rfc_datos_personales'            => trim(mb_strtoupper($rfc_cliente, 'UTF-8')),
                                'curp_datos_personales'           => trim(mb_strtoupper($curp_datos_personales, 'UTF-8')),
                                'nacionalidad_datos_personales'   => $pais_nacionalidad,
                                'fecha_nac_datos_personales'      => trim(date("Y-m-d", strtotime($fecha_nac_datos_personales))),
                                'genero_datos_personales' =>"",
                                'edo_civil_datos_personales'=>"",
                                'num_hijosdatos_personales'=>"",
                                'id_usuario'=>"",
                                'id_contacto'=>"",
                                'status'=>true,
                                'eliminado'=>false,
                                'auditoria' => [array(
                                                          "cod_user" => $id_usuario,
                                                          "nomuser" => $this->session->userdata('nombre'),
                                                          "fecha" => $fecha,
                                                          "accion" => "Nuevo registro",
                                                          "operacion" => ""
                                                      )]

                                );
        $datosClientePa = array(
                                'tipo_persona_cliente'   => mb_strtoupper($tipo_persona),
                                'tipo_cliente' => 'CLIENTE',
                                'actividad_e_cliente'    => $actividad_economica,
                                'empresa_pertenece'      => $empresa_pertenece,
                                'rfc_img'                => $img_n_identificacion,
                                'pais_cliente'           => $pais_origen,
                                'dominio_fiscal_img'     => $img_domicilio,
                                'imagenCliente'     => $imgProfile,
                                "giro_mercantil"=> "",
                                "acta_constitutiva"=>"",
                                "acta_img"=> "",
                                'status'=>true,
                                'eliminado'=>false,
                                'auditoria' => [array(
                                                          "cod_user" => $id_usuario,
                                                          "nomuser" => $this->session->userdata('nombre'),
                                                          "fecha" => $fecha,
                                                          "accion" => "Nuevo registro",
                                                          "operacion" => ""
                                                      )]
                                ); 
        $datosDomicilio = array(
                                'correo_contacto'             => $correo_clente,
                                'telefono_principal_contacto' => $telefono_cliente,
                                'calle_contacto'              => $calle_cliente,
                                'id_codigo_postal'            => $colonia,
                                'exterior_contacto'           => $exterior_cliente,
                                'interior_contacto'           => $interior_cliente,
                                'status'=>true,
                                'eliminado'=>false,
                                'auditoria' => [array(
                                                          "cod_user" => $id_usuario,
                                                          "nomuser" => $this->session->userdata('nombre'),
                                                          "fecha" => $fecha,
                                                          "accion" => "Nuevo registro",
                                                          "operacion" => ""
                                                      )]                      
                          );
        $datosDatosPMoral =array(

                               'nombre_datos_personales'    => trim(mb_strtoupper($razon_social,'UTF-8')),     
                               'rfc_datos_personales'       => trim(mb_strtoupper($rfc_moral,'UTF-8')),     
                               'fecha_nac_datos_personales' => trim(date("Y-m-d", strtotime($fecha_cons_r))), 
                                'status'=>true,
                                'eliminado'=>false,
                                'auditoria' => [array(
                                                          "cod_user" => $id_usuario,
                                                          "nomuser" => $this->session->userdata('nombre'),
                                                          "fecha" => $fecha,
                                                          "accion" => "Nuevo registro",
                                                          "operacion" => ""
                                                      )]
                                );
        $datosClienteMoral = array(
                                'tipo_cliente'=>'CLIENTE',
                                'imagenCliente'                => $imgProfile,
                                'acta_constitutiva'      => $acta_constutiva_r,
                                'acta_img'               => $img_acta_constitutiva,
                                'giro_mercantil'         => $giro_mercantil_r, 
                                'tipo_persona_cliente'   => mb_strtoupper($tipo_persona),
                                'rfc_img'                => $img_n_identificacion,
                                'dominio_fiscal_img'     => $img_domicilio,
                                'status'=>true,
                                'eliminado'=>false,
                                'auditoria' => [array(
                                                          "cod_user" => $id_usuario,
                                                          "nomuser" => $this->session->userdata('nombre'),
                                                          "fecha" => $fecha,
                                                          "accion" => "Nuevo registro",
                                                          "operacion" => ""
                                                      )]
        );
        $datosDomicilioMoral =array(
                                'correo_contacto'             => $correo_moral_m,
                                'telefono_principal_contacto' => $telefono_moral_m,
                                'calle_contacto'              => $calle_cliente,
                                'id_codigo_postal'            => $colonia,
                                'exterior_contacto'           => $exterior_cliente,
                                'interior_contacto'           => $interior_cliente,
                                'status'=>true,
                                'eliminado'=>false,
                                'auditoria' => [array(
                                                          "cod_user" => $id_usuario,
                                                          "nomuser" => $this->session->userdata('nombre'),
                                                          "fecha" => $fecha,
                                                          "accion" => "Nuevo registro",
                                                          "operacion" => ""
                                                      )]   
        );
        
        $datosRepLegal = array(
                                  'nombre_datos_personales'      => trim(mb_strtoupper($nombre_representante, 'UTF-8')),   
                                  'apellido_p_datos_personales'  => trim(mb_strtoupper($apellido_paterno_rep, 'UTF-8')),   
                                  'apellido_m_datos_personales'  => trim(mb_strtoupper($apellido_materno_rep, 'UTF-8')),   
                                  'rfc_datos_personales'         => trim(mb_strtoupper($rfc_representante, 'UTF-8')),   
                                  'curp_datos_personales'        => trim(mb_strtoupper($curp_rep_legal, 'UTF-8')),
                                  'status'=>true,
                                  'eliminado'=>false,
                                  'auditoria' => [array(
                                                            "cod_user" => $id_usuario,
                                                            "nomuser" => $this->session->userdata('nombre'),
                                                            "fecha" => $fecha,
                                                            "accion" => "Nuevo registro",
                                                            "operacion" => ""
                                                      )]   
                                  );

                                  
        $datosRepLegal_clipa = array(
                                    'correo_rep_legal'     => $correo_rep_legal ,   
                                    'telf_rep_legal'       => $telf_rep_legal,
                                    'rfc_img'              => $imagenLegal,
                                    'status'=>true,
                                    'eliminado'=>false,
                                    'auditoria' => [array(
                                                              "cod_user" => $id_usuario,
                                                              "nomuser" => $this->session->userdata('nombre'),
                                                              "fecha" => $fecha,
                                                              "accion" => "Nuevo registro",
                                                              "operacion" => ""
                                                          )]
        );
        $datosCuenta   = array(
                                    'clabe_cuenta'         => trim(mb_strtoupper($clabe, 'UTF-8')),
                                    'numero_cuenta'        => $numero_cuenta,
                                    'tipo_cuenta'          => $tipo_cuenta,  
                                    'id_banco'             => $banco,        
                                    'swift_cuenta'         => trim(mb_strtoupper($swift, 'UTF-8')),        
                                    'id_plaza'             => $tipo_plaza,   
                                    'sucursal_cuenta'      => trim(mb_strtoupper($sucursal, 'UTF-8')),
                                    'status'=>true,
                                    'eliminado'=>false,
                                    'auditoria' => [array(
                                                              "cod_user" => $id_usuario,
                                                              "nomuser" => $this->session->userdata('nombre'),
                                                              "fecha" => $fecha,
                                                              "accion" => "Nuevo registro",
                                                              "operacion" => ""
                                                          )]  
                              );  
        $datosContacto = array(
                                'telefono_principal_contacto' => $telefono_principal_contacto,
                                'telefono_movil_contacto'     => $telefono_movil_contacto    ,
                                'telefono_casa_contacto'      => $telefono_casa_contacto     ,
                                'telefono_trabajo_contacto'   => $telefono_trabajo_contacto  ,
                                'telefono_fax_contacto'       => $telefono_fax_contacto      ,
                                'correo_contacto'             => $correo_contacto            ,
                                'id_codigo_postal'            => $colonia,
                                'correo_opcional_contacto'    => $coreo_contactp_opc_r       ,
                                'direccion_contacto'=>"",
                                'calle_contacto'=>"",
                                'interior_contacto'=>"",
                                'exterior_contacto'=>"",
                                'status'=>true,
                                'eliminado'=>false,
                                'auditoria' => [array(
                                                          "cod_user" => $id_usuario,
                                                          "nomuser" => $this->session->userdata('nombre'),
                                                          "fecha" => $fecha,
                                                          "accion" => "Nuevo registro",
                                                          "operacion" => ""
                                                      )]
                              );
       

        if ($tipo_persona == "moral"){            
            //------------------------------ 
            //---
            /*
            *   Valido el correo del cliente pagador
            */
            $this->ClientePagador_model->validarCorreoClientePagador($datosDomicilioMoral["correo_contacto"]);
            /*
            *
            */
            //---
            //--Valido el rfc del cliente
            $res_cliente_rfc= $this->mongo_db->where(array('rfc_datos_personales' => $rfc_moral))->get("datos_personales");
              if(count($res_cliente_rfc)>0){
                echo "<span>Ya existe un cliente con ese RFC</span>";die('');
              } 
            //--     
            $datos['repLegal_cliente_pagador'] = $datosRepLegal_clipa;
            $datos['datosPersonales'] = $datosDatosPMoral;
            $datos['datosClientePa']  = $datosClienteMoral;
            $datos['datosDomicilio'] = $datosDomicilioMoral;

            $this->reglas_validacion('insert','clientemoral');
            if($nombre_representante !=""){
                $datos['datosRepLegal'] = $datosRepLegal;
                $this->reglas_validacion('insert','repLegal');
            }
        }else{
             //---
            /*
            *   Valido el correo del cliente pagador
            */
            $this->ClientePagador_model->validarCorreoClientePagador($datosDomicilio["correo_contacto"]);
            /*
            *
            */
            //---
            //--Valido el rfc del cliente
            $res_cliente_rfc= $this->mongo_db->where(array('rfc_datos_personales' => $rfc_cliente))->get("datos_personales");
              if(count($res_cliente_rfc)>0){
                echo "<span>Ya existe un cliente con ese RFC</span>";die('');
              } 
            //-- 
            $datos['datosPersonales'] = $datosPersonales;
            $datos['datosClientePa']  = $datosClientePa;
            $datos['datosDomicilio'] = $datosDomicilio;
            
                $this->reglas_validacion('insert','clientepagador');    
        }
        
      if ($clabe!= "") {
          $datos['datosCuenta']= $datosCuenta;
      $this->reglas_validacion('insert','cuentaCliente');
      }
      if ($nombre_contacto!= ""){
          $datos['datosContacto']   = $datosContacto;
          $datos['nombre_contacto'] = trim(mb_strtoupper($nombre_contacto, 'UTF-8'));

      $this->reglas_validacion('insert','contacto');
      }
      $this->reglas_validacion('insert','domicilio');
      $this->mensajes_reglas();
      if ($this->form_validation->run() == true) {
        $this->ClientePagador_model->guardarClientePagador($datos);
      }else {
            echo validation_errors();
      }
  }
  /*
  *
  */

  public function actualizar_clientePagador()
  {
   $formulario             = $this->input->post();//print_r($formulario);die;


    $img_profile            = $this->input->post('imgProfile');
    $img_n_identificacion   = $this->input->post('img_n_identificacion');
    $img_domicilio          = $this->input->post('img_domicilio');
    $img_acta_constitutiva  = $this->input->post('img_acta_constitutiva');


    // datos generales cliente
    $id_cliente                 = (isset($formulario['id_clientePagador'])) ? $formulario['id_clientePagador'] : '';
    $id_contacto                = (isset($formulario['id_contacto'])) ? $formulario['id_contacto'] : '';
    $id_datos_personales        = (isset($formulario['id_datos_personales'])) ? $formulario['id_datos_personales'] : '';
    $tipo_persona               = (isset($formulario['rad_tipoper_editar'])) ? $formulario['rad_tipoper_editar'] : '';
    $nombre_cliente             = (isset($formulario['nombre_cliente'])) ? $formulario['nombre_cliente'] : '';
    $apellido_paterno_cliente   = (isset($formulario['apellido_paterno'])) ? $formulario['apellido_paterno'] : '';
    $apellido_materno_cliente   = (isset($formulario['apellido_materno'])) ? $formulario['apellido_materno'] : '';
    $curp_datos_personales      = (isset($formulario['curp_datos_personales'])) ? $formulario['curp_datos_personales'] : '';
    $rfc_cliente                = (isset($formulario['rfc_editar'])) ? $formulario['rfc_editar'] : '';
    $actividad_economica        = (isset($formulario['actividad_economica'])) ? $formulario['actividad_economica'] : '';
    $fecha_nac_datos_personales = (isset($formulario['fecha_nac_datos_editar'])) ? $formulario['fecha_nac_datos_editar'] : '';
    $correo_clente              = (isset($formulario['correo_cliente_editar'])) ? $formulario['correo_cliente_editar'] : '';
    $telefono_principal_contacto= (isset($formulario['telefono_cliente_editar'])) ? $formulario['telefono_cliente_editar'] : '';
    $actividad_economica        = (isset($formulario['actividad_economica'])) ? $formulario['actividad_economica'] : '';
    $empresa_pertenece          = (isset($formulario['empresa_pertenece'])) ? $formulario['empresa_pertenece'] : '';
    $pais_nacionalidad          = (isset($formulario['nacionalidad_cliente_editar'])) ? $formulario['nacionalidad_cliente_editar'] : '';
    $pais_origen                = (isset($formulario['pais_origen_editar'])) ? $formulario['pais_origen_editar'] : '';
    // DATOS TIPO MORAL 
    $razon_social_e             = (isset($formulario['razon_social_e'])) ? $formulario['razon_social_e'] : '';
    $rfc_moral_e                = (isset($formulario['rfc_moral_e'])) ? $formulario['rfc_moral_e'] : '';
    $fecha_cons_e               = (isset($formulario['fecha_cons_e'])) ? $formulario['fecha_cons_e'] : '';
    $acta_constutiva_e          = (isset($formulario['acta_constutiva_e'])) ? $formulario['acta_constutiva_e'] : '';
    $giro_mercantil_e           = (isset($formulario['giro_mercantil_e'])) ? $formulario['giro_mercantil_e'] : '';
    $correo_moral_e             = (isset($formulario['correo_moral_e'])) ? $formulario['correo_moral_e'] : '';
    $telefono_moral_e           = (isset($formulario['telefono_moral_e'])) ? $formulario['telefono_moral_e'] : '';

    // datos domicilio fiscal cliente
    $calle_cliente              = (isset($formulario['calle_contacto'])) ? $formulario['calle_contacto'] : '';
    $exterior_cliente           = (isset($formulario['exterior_contacto'])) ? $formulario['exterior_contacto'] : '';
    $interior_cliente           = (isset($formulario['interior_contacto'])) ? $formulario['interior_contacto'] : '';
    $codigo_postal_domicilio    = (isset($formulario['codigo_postal_domicilio'])) ? $formulario['codigo_postal_domicilio'] : '';
    $colonia                    = (isset($formulario['colonia'])) ? $formulario['colonia'] : '';
        // datos rep legal cliente
    $imagen           = (isset($formulario['rfc_img_editar'])) ? $formulario['rfc_img_editar'] : '';
    $imagenDomFiscal  = (isset($formulario['domicilio_fiscal_img_e'])) ? $formulario['domicilio_fiscal_img_e'] : '';
    $imagenActa       = (isset($formulario['acta_img_e'])) ? $formulario['acta_img_e'] : '';
    $imagenMoral      = (isset($formulario['img_n_identificacion'])) ? $formulario['img_n_identificacion'] : '';
    $imagenCliente      = (isset($formulario['cliente_img_editar'])) ? $formulario['cliente_img_editar'] : '';
    $imagenClienteMoral      = (isset($formulario['cliente_img_moral_editar'])) ? $formulario['cliente_img_moral_editar'] : '';
    //----------------------------------------------------------------------------------
    //--Imagen del cliente pagador
    //var_dump($formulario);die('');

    $array_img = explode(".", $img_profile);
    $ext = end($array_img);

    if($ext == "pdf"){
      echo "No se permiten archivos pdf para la foto de perfil";
      return false;
    }

  
    if ($img_profile == "undefined") {
      echo "La Imagen Cliente es obligatoria";
      return false;
    }

    if ($imagenMoral == "undefined") {
      echo "La Imagen Copia escaneada del N° Identificación es obligatoria";
      return false;
    }




    if($tipo_persona == "moral"){
      if ($img_acta_constitutiva == "undefined") {
        echo "Debe seleccionar la imagen de la copia escaneada del Acta Constitutiva";
        return false;
      }
    } 
    



    
    if(!empty($img_profile))
    {
        if(file_exists(sys_get_temp_dir().'/'.$img_profile))
        {
          rename(sys_get_temp_dir().'/'.$img_profile,
                                  'assets/cpanel/ClientePagador/images/'.$img_profile
                                );
                                //unlink(sys_get_temp_dir().'/'.$imagen);                        
        }
    }else if($tipo_persona == "fisica"){
        echo "Debe seleccionar la imagen del cliente";die('');
    }
    //--
    if(!empty($img_n_identificacion))
    {
      if(file_exists(sys_get_temp_dir().'/'.$img_n_identificacion))
      {
        rename(sys_get_temp_dir().'/'.$img_n_identificacion,
                                'assets/cpanel/ClientePagador/images/'.$img_n_identificacion
                              );
                              //unlink(sys_get_temp_dir().'/'.$imagen);                        
      }
    }else if($tipo_persona == "fisica"){
      echo "Debe seleccionar la imagen de la copia escaneada del RFC";die('');
    }
    //---------------------------------------------------------------------------------
    //--Imagen
    if(!empty($img_n_identificacion))
    {
      if(file_exists(sys_get_temp_dir().'/'.$img_n_identificacion))
      {
        rename(sys_get_temp_dir().'/'.$img_n_identificacion,
                                'assets/cpanel/ClientePagador/images/'.$img_n_identificacion
                              );
                              //unlink(sys_get_temp_dir().'/'.$imagen);                        
      }
    }else if($tipo_persona == "fisica"){
      echo "Debe seleccionar la imagen de la copia escaneada del RFC";die('');
    }
    
    //--
    //--Imagen fiscal
    if(!empty($img_domicilio))
    {
      if(file_exists(sys_get_temp_dir().'/'.$img_domicilio))
      {
        rename(sys_get_temp_dir().'/'.$img_domicilio,
                                'assets/cpanel/ClientePagador/images/'.$img_domicilio
                              );
                              //unlink(sys_get_temp_dir().'/'.$imagen);
                             
      }
    }else{
      echo "Debe seleccionar la imagen de la copia escaneada del Domicilio Fiscal";die('');
    }
   
    //-- Imagen de acta
     if(!empty($img_acta_constitutiva))
    {
      if(file_exists(sys_get_temp_dir().'/'.$img_acta_constitutiva))
      {
        rename(sys_get_temp_dir().'/'.$img_acta_constitutiva,
                                'assets/cpanel/ClientePagador/images/'.$img_acta_constitutiva
                              );
                              //unlink(sys_get_temp_dir().'/'.$imagen);
                             
      }
    }else if($tipo_persona == "moral"){
      echo "Debe seleccionar la imagen de la copia escaneada del Acta Constitutiva";die('');
    }
    //--Imagen moral
    if(!empty($imagenMoral))
    {
      if(file_exists(sys_get_temp_dir().'/'.$imagenMoral))
      {
        rename(sys_get_temp_dir().'/'.$imagenMoral,
                                'assets/cpanel/ClientePagador/images/'.$imagenMoral
                              );
                              //unlink(sys_get_temp_dir().'/'.$imagen);
                             
      }
    }else if($tipo_persona == "moral"){
      echo "Debe seleccionar la imagen de la copia escaneada del RFC";die('');
    }
   
    //--
    //--Imagen legal
    /*if(!empty($imagenLegal))
    {
      if(file_exists(sys_get_temp_dir().'/'.$imagenLegal))
      {
        rename(sys_get_temp_dir().'/'.$imagenLegal,
                                'assets/cpanel/ClientePagador/images/'.$imagenLegal
                              );
                              //unlink(sys_get_temp_dir().'/'.$imagen);
                             
      }
    }else if(($tipo_persona == "moral")&&($rfc_representante!="")&&($telf_rep_legal!="")&&($correo_rep_legal!="")){
      echo "Debe seleccionar la imagen de la copia escaneada del RFC del representante legal";die('');
    }*/
    //--
    
    $datosPersonales= array(
                            'nombre_datos_personales'         => trim(mb_strtoupper($nombre_cliente, 'UTF-8')),
                            'apellido_p_datos_personales'     => trim(mb_strtoupper($apellido_paterno_cliente, 'UTF-8')),
                            'apellido_m_datos_personales'     => trim(mb_strtoupper($apellido_materno_cliente, 'UTF-8')),
                            'rfc_datos_personales'            => trim(mb_strtoupper($rfc_cliente, 'UTF-8')),
                            'curp_datos_personales'           => trim(mb_strtoupper($curp_datos_personales, 'UTF-8')),
                            'nacionalidad_datos_personales'   => $pais_nacionalidad,
                            'fecha_nac_datos_personales'      => trim(date("Y-m-d", strtotime($fecha_nac_datos_personales))),
                            
                            );
    $datosClientePa = array(
                            'imagenCliente'     => $img_profile,
                            'tipo_persona_cliente'   => mb_strtoupper($tipo_persona),
                            'actividad_e_cliente'    => $actividad_economica,
                            'empresa_pertenece'      => $empresa_pertenece,
                            'pais_cliente'           => $pais_origen,
                            'rfc_img'                => $img_n_identificacion,
                            'dominio_fiscal_img'     => $img_domicilio
                            ); 


    $datosContacto = array(
                            'correo_contacto'              => $correo_clente,
                            'telefono_principal_contacto'  => $telefono_principal_contacto,
                            'calle_contacto'               => $calle_cliente,                            
                            'exterior_contacto'            => $exterior_cliente,
                            'interior_contacto'            => $interior_cliente,
                            
                      );

    $datosDatosPMoral =array(
                              'nombre_datos_personales'    => trim(mb_strtoupper($razon_social_e,'UTF-8')),     
                              'rfc_datos_personales'       => trim($rfc_moral_e)   ,     
                              'fecha_nac_datos_personales' => trim(date("Y-m-d", strtotime($fecha_cons_e)))  
                                
                                );
    $datosClienteMoral = array(
                                'imagenCliente'          => $img_profile,
                                'acta_constitutiva'      => $acta_constutiva_e,
                                'acta_img'               => $img_acta_constitutiva,
                                'giro_mercantil'         => $giro_mercantil_e, 
                                'tipo_persona_cliente'   => mb_strtoupper($tipo_persona),
                                'rfc_img'                => $img_n_identificacion,
                                'dominio_fiscal_img'     => $img_domicilio
        );


    $datosDomicilioMoral =array(
                                'correo_contacto'             => $correo_moral_e,
                                'telefono_principal_contacto' => $telefono_moral_e,
                                'calle_contacto'              => $calle_cliente,
                                'id_codigo_postal'            => $colonia,
                                'exterior_contacto'           => $exterior_cliente,
                                'interior_contacto'           => $interior_cliente,
                        );
    
  if  ($tipo_persona == "moral"){
    /*
    *   Editar: Valido el correo del cliente pagador persona moral
    */
    //var_dump($datosDomicilioMoral["correo_contacto"]);die('');
    $this->ClientePagador_model->validarCorreoClientePagadorEditar($id_contacto,$datosDomicilioMoral["correo_contacto"]);
    /*
    *
    */
    //---
       $datos = array(
                  'datosPersonales' => $datosDatosPMoral,
                  'datosClientePa' => $datosClienteMoral,
                  'datosContacto'  => $datosDomicilioMoral);
        $this->reglas_validacion('update','clientemoral');
  }else{
    /*
    *   Editar: Valido el correo del cliente pagador persona fisica
    */
    $this->ClientePagador_model->validarCorreoClientePagadorEditar($id_contacto,$datosContacto["correo_contacto"]);
    /*
    *
    */
    $datos = array(
                  'datosPersonales' => $datosPersonales,
                  'datosClientePa' => $datosClientePa,
                  'datosContacto'  => $datosContacto,);
      $this->reglas_validacion('update','clientepagador');
                   
      }
    
      $this->mensajes_reglas();
    if ($this->form_validation->run() == true) {
        $this->ClientePagador_model->editarClientePagador($id_cliente, $id_contacto,$id_datos_personales, $datos);
       echo json_encode("<span>El registro se ha editado exitosamente!</span>");
      } else {
         echo validation_errors();
      }
  }
  public function registrar_cuentaCliente()
  {
    $fecha = new MongoDB\BSON\UTCDateTime();
        
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
    $datos = array(
                    'id_cliente'           => $id_cliente,
                    'clabe_cuenta'         => trim(mb_strtoupper($clabe, 'UTF-8')),
                    'numero_cuenta'        => $numero_cuenta,
                    'tipo_cuenta'          => $tipo_cuenta,
                    'id_banco'         => $banco,
                    'swift_cuenta'         => trim(mb_strtoupper($swift, 'UTF-8')),
                    'id_plaza'         => $tipo_plaza,
                    'sucursal_cuenta'      => trim(mb_strtoupper($sucursal, 'UTF-8')),
                    'status'=>true,
                    'eliminado'=>false,
                    'auditoria' => [array(
                                                            "cod_user" => $id_usuario,
                                                            "nomuser" => $this->session->userdata('nombre'),
                                                            "fecha" => $fecha,
                                                            "accion" => "Nuevo registro",
                                                            "operacion" => ""
                                                        )] 
                  );
    $this->reglas_validacion('insert','cuentaCliente');
    $this->mensajes_reglas();
    if ($this->form_validation->run()== true) {
    $this->ClientePagador_model->guardarCuentaCliente($datos);
      } else{
        echo validation_errors();
      } 
  }
   public function actualizar_cuentaCliente(){
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
      $this->ClientePagador_model->actualizarCuentaCliente($id_cuenta, $datos);
     //echo json_encode("<span>El registro se ha editado exitosamente!</span>");
    }else{
        echo validation_errors();
    }
  }
  
  public function registrar_repLegalCliente()
  {
    //
    $fecha = new MongoDB\BSON\UTCDateTime();
    $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));

    $formulario             = $this->input->post();
    $id_cliente             = (isset($formulario['id_cliente'])) ? $formulario['id_cliente'] : '';
    $nombre_representante   = (isset($formulario['nombre_representante'])) ? $formulario['nombre_representante'] : '';
    $apellido_paterno_rep   = (isset($formulario['apellido_paterno_rep'])) ? $formulario['apellido_paterno_rep'] : '';
    $apellido_materno_rep   = (isset($formulario['apellido_materno_rep'])) ? $formulario['apellido_materno_rep'] : '';
    $rfc_representante      = (isset($formulario['rfc_representante'])) ? $formulario['rfc_representante'] : '';
    $curp_rep_legal         = (isset($formulario['curp_rep_legal'])) ? $formulario['curp_rep_legal'] : '';
    $correo_rep_legal       = (isset($formulario['correo_rep_legal'])) ? $formulario['correo_rep_legal'] : '';
    $telf_rep_legal         = (isset($formulario['telf_rep_legal'])) ? $formulario['telf_rep_legal'] : '';
    $imagen                 = (isset($formulario['rfc_img_rep'])) ? $formulario['rfc_img_rep'] : '';
     /*------------------------------------------------------------------------------------------------*/
    /*-- Cuando proviene de otro formulario el set-validation no funciona por lo que es necesario validar de forma manual--*/
    /*--Verifico que rfc no sea vacio --*/
    if($rfc_representante==""){
      echo "El campo rfc del rep. legal es obligatorio"; die('');
    }
    /*-- Verifico tlf no sea vacio --*/
    if($telf_rep_legal==""){
      echo "El campo teléfono  del rep. legal es obligatorio"; die('');
    }
    /*-- Verificacion en php del formato de email cuando proviene de otro formulario */
    if (!filter_var($correo_rep_legal, FILTER_VALIDATE_EMAIL)) {
      echo "El campo Correo Electrónico del rep. legal debe contar con un correo válido P. EJ. ejemplo@dominio.com"; die('');
    }
    /*------------------------------------------------------------------------------------------------*/
    if(!empty($imagen))
    {
      if(file_exists(sys_get_temp_dir().'/'.$imagen))
      {
        rename(sys_get_temp_dir().'/'.$imagen,
                                'assets/cpanel/ClientePagador/images/'.$imagen
                              );
                              //unlink(sys_get_temp_dir().'/'.$imagen);                        
      }
    }else{
      echo "Debe seleccionar la imagen de la copia escaneada del RFC del representante legal";die('');
    }
   
    $datosPersonales = array(
                  'nombre_datos_personales'      => trim(mb_strtoupper($nombre_representante, 'UTF-8')),
                  'apellido_p_datos_personales'  => trim(mb_strtoupper($apellido_paterno_rep, 'UTF-8')),
                  'apellido_m_datos_personales'  => trim(mb_strtoupper($apellido_materno_rep, 'UTF-8')),
                  'rfc_datos_personales'         => trim(mb_strtoupper($rfc_representante, 'UTF-8')),
                  'curp_datos_personales'        => trim(mb_strtoupper($curp_rep_legal, 'UTF-8')),
                  'genero_datos_personales' =>"",
                  'fecha_nac_datos_personales'=>"",
                  'edo_civil_datos_personales'=>"",
                  'nacionalidad_datos_personales'=>"",
                  'num_hijosdatos_personales'=>"",
                  'id_usuario'=>"",
                  'id_contacto'=>"",
                  'status'=>true,
                  'eliminado'=>false,
                  'auditoria' => [array(
                                                          "cod_user" => $id_usuario,
                                                          "nomuser" => $this->session->userdata('nombre'),
                                                          "fecha" => $fecha,
                                                          "accion" => "Nuevo registro",
                                                          "operacion" => ""
                                                      )]
    );

    $datosRepLegal = array(
                  'id_cliente'           => $id_cliente,
                  'correo_rep_legal'     => trim($correo_rep_legal),
                  'telf_rep_legal'       => trim($telf_rep_legal),
                  'rfc_img'              => $imagen,
                  'status'=>true,
                  'eliminado'=>false,
                  'auditoria' => [array(
                                                          "cod_user" => $id_usuario,
                                                          "nomuser" => $this->session->userdata('nombre'),
                                                          "fecha" => $fecha,
                                                          "accion" => "Nuevo registro",
                                                          "operacion" => ""
                                                      )]
                );
    $datos = array('datosPersonales' => $datosPersonales,
                  'datosRepLegal'  => $datosRepLegal);
    $this->reglas_validacion('insert','repLegal');
    $this->mensajes_reglas();
    if($this->form_validation->run() == true){
    $this->ClientePagador_model->guardarRepLegalCliente($datos);
    }else{
      validation_errors();
    }
  }
  /*
  * Actualizar rep Legal
  */
  public function actualizar_repLegalCliente(){

    $formulario             = $this->input->post();
    $id_cliente             = (isset($formulario['id_cliente'])) ? $formulario['id_cliente'] : '';
    $id_repLegal            = (isset($formulario['id_repLegal'])) ? $formulario['id_repLegal'] : '';
    $id_datos_personales    = (isset($formulario['id_datos_personales'])) ? $formulario['id_datos_personales'] : '';
    $imagen_editar          = (isset($formulario['imagen_editar'])) ? $formulario['imagen_editar'] : '';
    $nombre_representante   = (isset($formulario['nombre_representante'])) ? $formulario['nombre_representante'] : '';
    $apellido_paterno_rep   = (isset($formulario['apellido_paterno_rep'])) ? $formulario['apellido_paterno_rep'] : '';
    $apellido_materno_rep   = (isset($formulario['apellido_materno_rep'])) ? $formulario['apellido_materno_rep'] : '';
    $rfc_representante      = (isset($formulario['rfc_representante'])) ? $formulario['rfc_representante'] : '';
    $curp_rep_legal         = (isset($formulario['curp_rep_legal'])) ? $formulario['curp_rep_legal'] : '';
    $correo_rep_legal       = (isset($formulario['correo_rep_legal'])) ? $formulario['correo_rep_legal'] : '';
    $telf_rep_legal         = (isset($formulario['telf_rep_legal'])) ? $formulario['telf_rep_legal'] : '';
    $imagen                 = (isset($formulario['rfc_img_rep_e'])) ? $formulario['rfc_img_rep_e'] : '';
    
    /*-- Cuando proviene de otro formulario el set-validation no funciona por lo que es necesario validar de forma manual--*/
    /*--Verifico que rfc no sea vacio --*/
    if($rfc_representante==""){
      echo "El campo rfc del rep. legal es obligatorio"; die('');
    }
    /*-- Verifico tlf no sea vacio --*/
    if($telf_rep_legal==""){
      echo "El campo teléfono  del rep. legal es obligatorio"; die('');
    }
   /*-- Verificacion en php del formato de email cuando proviene de otro formulario */
    if (!filter_var($correo_rep_legal, FILTER_VALIDATE_EMAIL)) {
      echo "El campo Correo Electrónico del rep. legal debe contar con un correo válido P. EJ. ejemplo@dominio.com"; die('');
    }
    /*------------------------------------------------------------------------------------------------*/
    if(!empty($imagen))
    {
      if(file_exists(sys_get_temp_dir().'/'.$imagen))
      {
        rename(sys_get_temp_dir().'/'.$imagen,
                                'assets/cpanel/ClientePagador/images/'.$imagen
                              );
                              //unlink(sys_get_temp_dir().'/'.$imagen);                        
      }
    }else{
      echo "Debe seleccionar la imagen de la copia escaneada del RFC del representante legal";die('');
    }  

    $datosPersonales = array(
                  'nombre_datos_personales'      => trim(mb_strtoupper($nombre_representante, 'UTF-8')),
                  'apellido_p_datos_personales'  => trim(mb_strtoupper($apellido_paterno_rep, 'UTF-8')),
                  'apellido_m_datos_personales'  => trim(mb_strtoupper($apellido_materno_rep, 'UTF-8')),
                  'rfc_datos_personales'         => trim(mb_strtoupper($rfc_representante, 'UTF-8')),
                  'curp_datos_personales'        => trim(mb_strtoupper($curp_rep_legal, 'UTF-8')));

    $datosRepLegal = array(
                  
                  'correo_rep_legal'     => trim($correo_rep_legal),
                  'telf_rep_legal'       => trim($telf_rep_legal),
                  'rfc_img'              => $imagen
                );
    $datos = array('datosPersonales' => $datosPersonales,
                  'datosRepLegal'  => $datosRepLegal);
    
    $this->reglas_validacion('update','repLegal');
    $this->mensajes_reglas();
    if($this->form_validation->run() == true){
        $this->ClientePagador_model->editarRepLegal($id_repLegal,$id_datos_personales, $datos);
        echo json_encode("<span>El registro se ha editado exitosamente!</span>");
    }
    else{
      validation_errors();
    }
  }
  /*
  * Guardar contacto
  */
  public function guardarContacto()
  {
    $fecha = new MongoDB\BSON\UTCDateTime();
    $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
    
    $formulario = $this->input->post();
    $id_cliente = $formulario['id_cliente'];
    $nombre_contacto = trim(mb_strtoupper($formulario['nombre_contacto'], 'UTF-8'));
    $telefono_principal_contacto = $formulario['telefono_principal_contacto'];
    $telefono_movil_contacto = $formulario['telefono_movil_contacto'];
    $telefono_casa_contacto = $formulario['telefono_casa_contacto'];
    $telefono_trabajo_contacto = $formulario['telefono_trabajo_contacto'];
    $telefono_fax_contacto = $formulario['telefono_fax_contacto'];
    $correo_contacto = $formulario['correo_contacto'];
    $coreo_contacto_opc_r = $formulario['coreo_contactp_opc_r'];

    $contacto = array(
                      'id_cliente' =>$id_cliente,
                      'telefono_principal_contacto' => $telefono_principal_contacto,
                      'telefono_movil_contacto'     => $telefono_movil_contacto,
                      'telefono_casa_contacto'      => $telefono_casa_contacto,
                      'telefono_trabajo_contacto'   => $telefono_trabajo_contacto,
                      'telefono_fax_contacto'       => $telefono_fax_contacto,
                      'correo_contacto'             => $correo_contacto,
                      'correo_opcional_contacto'    => $coreo_contacto_opc_r,
                      'id_codigo_postal'=> "",
                      'direccion_contacto'=>"",
                      'calle_contacto'=>"",
                      'interior_contacto'=>"",
                      'exterior_contacto'=>"",
                      'status'=>true,
                      'eliminado'=>false,
                      'auditoria' => [array(
                                          "cod_user" => $id_usuario,
                                          "nomuser" => $this->session->userdata('nombre'),
                                          "fecha" => $fecha,
                                          "accion" => "Nuevo registro",
                                          "operacion" => ""
                                      )]
    );
    /*------------------------------------------------------------------------------------------------*/
    /*-- Verificacion en php del formato de email cuando proviene de otro formulario */
    if (!filter_var($correo_contacto, FILTER_VALIDATE_EMAIL)) {
      echo "El campo Correo Electrónico del contacto debe contar con un correo válido P. EJ. ejemplo@dominio.com"; die('');
    }
    /*------------------------------------------------------------------------------------------------*/

    $this->reglas_validacion('insert','contacto');
    $this->mensajes_reglas();
    if($this->form_validation->run() == true){
    $this->ClientePagador_model->guardarContacto($contacto, $nombre_contacto, $id_cliente);
    }else{
      validation_errors();
    }

  }
  /*
  * Actualizar contacto
  */
  public function actualizar_contacto(){
      $formulario = $this->input->post();
      $nombre_contacto = trim(mb_strtoupper($formulario['nombre_contacto'], 'UTF-8'));
      $id_contacto = $formulario['id_contacto'];
      $id_contacto_cliente = $formulario['id_contacto_cliente'];
      $id_datos_personales= $formulario['id_datos_personales'];
      $correo_contacto = $formulario['correo_contacto_e']; 
      $contacto = array(
                      'telefono_principal_contacto'  => $formulario['telefono_principal_contacto'], 
                      'telefono_movil_contacto'      => $formulario['telefono_movil_contacto'],
                      'telefono_casa_contacto'       => $formulario['telefono_casa_contacto'],
                      'telefono_trabajo_contacto'    => $formulario['telefono_trabajo_contacto'],   
                      'telefono_fax_contacto'        => $formulario['telefono_fax_contacto'],
                      'correo_contacto'              => $formulario['correo_contacto_e'],
                      'correo_opcional_contacto'     => $formulario['coreo_contactp_opc_e'],
                      );
       $datosPersonales = array('nombre_datos_personales'=> $nombre_contacto);
       $datos = array('contacto' => $contacto,'datos_personales' => $datosPersonales, 'id_contacto_cliente' => $id_contacto_cliente);
  /*------------------------------------------------------------------------------------------------*/
  /*-- Verificacion en php del formato de email cuando proviene de otro formulario */
      if (!filter_var($correo_contacto, FILTER_VALIDATE_EMAIL)) {
          echo "El campo Correo Electrónico del contacto debe contar con un correo válido P. EJ. ejemplo@dominio.com"; die('');
        }
  /*------------------------------------------------------------------------------------------------*/
      $this->reglas_validacion('update','contacto');
      $this->mensajes_reglas();
      if($this->form_validation->run() == true){
        $this->ClientePagador_model->actualizar_contacto($id_contacto,$id_datos_personales, $datos);
        //echo json_encode("<span>El registro se ha editado exitosamente!</span>");
      }
      else{
        validation_errors();
      }

  }
  /*
  *
  */
  public function statuscontacto()
  {
    $id = $this->input->post('id');
    $status = $this->input->post('status');
    $this->ClientePagador_model->status($id,$status, 'contacto_cliente');
    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
  }
  /*
  * Status RepLegal
  */
  public function statusRepLegal()
  {
    $id = $this->input->post('id');
    $status = $this->input->post('status');
    $this->ClientePagador_model->status($id,$status, 'repLegal_cliente_pagador');
    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
  }
  /*
  * Status cliente pagador
  */
  public function status_clientePagador()
  {
    $id = $this->input->post('id');
    $status = $this->input->post('status'); 
    $this->ClientePagador_model->status($id,$status, 'cliente_pagador');
    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
  }
  /***/
  public function statusCuenta()
  {
    $id = $this->input->post('id');
    $status = $this->input->post('status'); 
    $this->ClientePagador_model->status($id,$status, 'cuenta_cliente');
    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
  }
  public function eliminar ()
  {
    $id = $this->input->post('id');
    $consulta = consultarClienteCotizacion($id);
    if($consulta){
       echo ("<span>El Cliente NO se puede eliminar ya que tiene una Cotizacion asociada!</span>");
    }else{
    $this->ClientePagador_model->eliminar($id, 'cliente');

    }

    //--Verifico que el cliente no tenga prospecto...
    /*$prospecto = $this->ClientePagador_model->buscarProspecto($id);
    if (count($prospecto)>0){
      echo ("<span>El cliente NO se puede eliminar ya que tiene un prospecto asociado!</span>");
      die('');
    }*/
     //--- Comentado ya que a la fecha 15-04-2019 este modulo cartera clientes no se ha mandado a migrar
     /*$consulta = $this->ClientePagador_model->consultaCarteraCliente($id);
     if (isset($consulta)){
      echo ("<span>El Cliente NO se puede eliminar ya que tiene un Vendedor en cartera de cliente asociado!</span>");
     }else{*/
    //}
  }
  /*
  *  Eliminar cuenta_cliente
  */
  public function eliminar_cuentaCliente (){
    
    $id = $this->input->post('id');
    
    $this->ClientePagador_model->eliminar($id, 'cuenta');
  }
  /*
  * Eliminar repLegal
  */
  public function eliminar_repLegal ()
  {
    $id = $this->input->post('id');
    
    $this->ClientePagador_model->eliminar($id, 'repLegal');
  }
  /*
  *   Eliminar contacto
  */
  public function eliminar_contacto (){
 
    $id = $this->input->post('id');
    
    $this->ClientePagador_model->eliminar($id, 'contacto');
  }
  /*
  * eliminar multiple
  */
  public function eliminar_multiple(){
    $this->ClientePagador_model->eliminar_multiple($this->input->post('id'));
  }
  /*
  *
  */
  /*
  *   Eliminar multiple cuentas
  */
  public function eliminar_multiple_cta(){
    $this->ClientePagador_model->eliminar_multiple_cta($this->input->post('id'));
  }
  /*
  * Eliminar multiple contactos
  */
  public function eliminar_multiple_contacto(){
      $this->ClientePagador_model->eliminar_multiple_contacto($this->input->post('id'));
  }
  /*
  * Eliminar multiple repLegal
  */
  public function eliminar_multiple_repLegal(){
      $this->ClientePagador_model->eliminar_multiple_repLegal($this->input->post('id'));
  }
  /*
  * status_multiple
  */
  public function status_multiple()
  {
    $this->ClientePagador_model->status_multiple($this->input->post('id'), $this->input->post('status'),"cliente_pagador");
    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
  }
  /*
  *
  */

  /*
  * status_multiple cta
  */
  public function status_multiple_cta()
  {
    $this->ClientePagador_model->status_multiple($this->input->post('id'), $this->input->post('status'),"cuenta_cliente");
    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
  }
  
  /*
  * status_multiple contacto
  */
  public function status_multiple_contacto(){
    $this->ClientePagador_model->status_multiple_contacto($this->input->post('id'), $this->input->post('status'));
    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
  }
  /*
  * status_multiple repLegal
  */
  public function status_multiple_repLegal(){
    $this->ClientePagador_model->status_multiple_repLegal($this->input->post('id'), $this->input->post('status'));
    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
  }
  
  public function reglas_validacion($method, $tipo)
  {
   switch ($tipo) {
     case 'clientepagador':
       if($method=="insert"){

      // Reglas para la tabla de cliente
      $this->form_validation->set_rules('rad_tipoper','Tipo persona','required');
      $this->form_validation->set_rules('nombre_cliente','Nombre(s)','required');
      $this->form_validation->set_rules('apellido_paterno_cliente','Apellido Paterno','required');
      $this->form_validation->set_rules('apellido_materno_cliente','Apellido Materno','required');
      $this->form_validation->set_rules('rfc','RFC Cliente','required');
      //$this->form_validation->set_rules('rfc_img','Copia del RFC Escaneada','required');|is_unique[datos_personales.rfc_datos_personales]
      $this->form_validation->set_rules('actividad_economica','Actividad Económica','required');
      $this->form_validation->set_rules('curp_datos_personales','C.U.R.P.','required');
      $this->form_validation->set_rules('fecha_nac_datos_personales','Fecha de Nacimiento','required');
      $this->form_validation->set_rules('correo_clente','Correo Electrónico','required|valid_email');
      $this->form_validation->set_rules('telefono_cliente','Teléfono Cliente','required');
      $this->form_validation->set_rules('pais_nacionalidad','Nacionalidad','required');
      $this->form_validation->set_rules('pais_origen','País Origen','required');
      
      // Reglas para la tabla cuenta

      
    }else if($method=="update"){
      // 
    $this->form_validation->set_rules('rad_tipoper_editar','Tipo persona','required');
      $this->form_validation->set_rules('nombre_cliente','Nombre(s)','required');
      $this->form_validation->set_rules('apellido_paterno','Apellido Paterno','required');
      $this->form_validation->set_rules('apellido_materno','Apellido Materno','required');
      $this->form_validation->set_rules('rfc_editar','RFC Cliente','required');
      //$this->form_validation->set_rules('rfc_img','Copia del RFC Escaneada','required');
      //$this->form_validation->set_rules('actividad_economica','Actividad Económica','required');
      $this->form_validation->set_rules('curp_datos_personales','C.U.R.P.','required');
      $this->form_validation->set_rules('fecha_nac_datos_editar','Fecha de Nacimiento','required');
      $this->form_validation->set_rules('correo_cliente_editar','Correo Electrónico','required|valid_email');
      $this->form_validation->set_rules('telefono_cliente_editar','Teléfono','required');
     // $this->form_validation->set_rules('nacionalidad_cliente_editar','Nacionalidad','required');
      //$this->form_validation->set_rules('pais_origen_editar','País Origen','required');
      
    }
       break;
      case 'clientemoral':
       if($method=="insert"){

      // Reglas para la tabla de cliente
      $this->form_validation->set_rules('rad_tipoper','Tipo persona','required');
      $this->form_validation->set_rules('razon_social','Razon Social','required');
      $this->form_validation->set_rules('rfc_moral','RFC Cliente','required');
      //$this->form_validation->set_rules('rfc_img','Copia del RFC Escaneada','required');
    //  $this->form_validation->set_rules('giro_mercantil_r','Giro Mercantil','required');
      $this->form_validation->set_rules('fecha_cons_r','Fecha de Constitucion','required');
      $this->form_validation->set_rules('correo_moral_m','Correo Electrónico','required|valid_email');
      $this->form_validation->set_rules('telefono_moral_m','Teléfono','required');
      $this->form_validation->set_rules('acta_constutiva_r','Acta Constitutiva','required');
      
      
      // Reglas para la tabla cuenta

      
    }else if($method=="update"){
      // 
    $this->form_validation->set_rules('rad_tipoper_editar','Tipo persona','required');
      $this->form_validation->set_rules('razon_social_e','Razon Social','required');
      
      $this->form_validation->set_rules('rfc_moral_e','RFC Cliente','required');
      //$this->form_validation->set_rules('rfc_img','Copia del RFC Escaneada','required');
   //   $this->form_validation->set_rules('giro_mercantil_e','Giro Mercantil','required');
      $this->form_validation->set_rules('fecha_cons_e','Fecha de Constitucion','required');
      $this->form_validation->set_rules('correo_moral_e','Correo Electrónico','required|valid_email');
      $this->form_validation->set_rules('telefono_moral_e','Teléfono','required');
      $this->form_validation->set_rules('acta_constutiva_e','Acta Constitutiva','required');
      
    }
       break;
      case 'cuentaCliente':
       if($method=="insert"){
        $this->form_validation->set_rules('clabe','CLABE ','required');
         $this->form_validation->set_rules('tipo_cuenta','Tipo de Cuenta','required');
        $this->form_validation->set_rules('banco','Banco','required');
        }else if($method=="update"){
        //$this->form_validation->set_rules('clabe','CLABE ','required');
        $this->form_validation->set_rules('tipo_cuenta','Tipo de Cuenta','required');
        $this->form_validation->set_rules('banco','Banco','required');     
        
      }
       break;

       case 'domicilio':
       if($method=="insert"){      
      // Reglas para la tabla contacto
      $this->form_validation->set_rules('calle_cliente','Calle Cliente','required');
      $this->form_validation->set_rules('exterior_cliente','Número Exterior','required');
     // $this->form_validation->set_rules('colonia','Código Postal','required');
      //$this->form_validation->set_rules('telefono_principal_contacto','Teléfono','required');
        }else if($method=="update"){
       // Reglas para la tabla contacto
      $this->form_validation->set_rules('calle_contacto','Calle Cliente','required');
      $this->form_validation->set_rules('exterior_contacto','Número Exterior','required');
      $this->form_validation->set_rules('colonia','Código Postal','required');
      $this->form_validation->set_rules('telefono_principal_contacto','Teléfono','required');
      }
       break;

      case 'contacto':
      if($method=="insert"){
        $this->form_validation->set_rules('nombre_contacto','Nombre Contacto ','required');
        $this->form_validation->set_rules('telefono_principal_contacto','Teléfono','required');
        $this->form_validation->set_rules('correo_contacto','Correo Electrónico','required|valid_email');

      }
      else if($method=="update"){
        $this->form_validation->set_rules('nombre_contacto','Nombre Contacto ','required');
        $this->form_validation->set_rules('telefono_principal_contacto','Teléfono','required');
        $this->form_validation->set_rules('correo_contacto_e','Correo Electrónico','required|valid_email');

      }
       break;

       case 'repLegal':
        if($method=="insert"){
          $this->form_validation->set_rules('nombre_representante','Nombre del Representante Legal','required');
          $this->form_validation->set_rules('apellido_paterno_rep','Apellido Paterno del Representante Legal','required');
          $this->form_validation->set_rules('apellido_materno_rep','Apellido Materno del Representante Legal','required');
          $this->form_validation->set_rules('rfc_representante','RFC del Reprentante Legal','required');
          $this->form_validation->set_rules('curp_rep_legal','CURP del Representante Legal','required');
          $this->form_validation->set_rules('correo_rep_legal','Correo del Represente Legal','required');
          $this->form_validation->set_rules('telf_rep_legal','Teléfono del Representante Legal','required');
        }else if($method=="update"){
          $this->form_validation->set_rules('nombre_representante','Nombre del Representante Legal','required');
          $this->form_validation->set_rules('apellido_paterno_rep','Apellido Paterno del Representante Legal','required');
          $this->form_validation->set_rules('apellido_materno_rep','Apellido Materno del Representante Legal','required');
          $this->form_validation->set_rules('rfc_representante','RFC del Reprentante Legal','required');
          $this->form_validation->set_rules('curp_rep_legal','CURP del Representante Legal','required');
          $this->form_validation->set_rules('correo_rep_legal','Correo del Represente Legal','required');
          $this->form_validation->set_rules('telf_rep_legal','Teléfono del Representante Legal','required');
        }
         break;
   }
    
  }

  public function mensajes_reglas(){

    $this->form_validation->set_message('required', 'El campo %s es obligatorio');
    $this->form_validation->set_message('numeric', 'El campo %s debe poseer solo numeros enteros');
    $this->form_validation->set_message('is_unique', 'El valor ingresado en el campo %s ya se encuentra en uso');
    $this->form_validation->set_message('valid_email', 'El campo %s debe contar con un correo válido P. EJ. ejemplo@dominio.com');  
  }
  /*---------------------------------------------------------------------------------------*/
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
  /*---------------------------------------------------------------------------------------*/
  public function cargar_elementos_select(){
    return true;
  }
  /*---------------------------------------------------------------------------------------*/
 

}
