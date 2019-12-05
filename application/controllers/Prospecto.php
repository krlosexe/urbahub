<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Prospecto extends CI_Controller

{

  function __construct()
  {

      parent::__construct();

      //$this->load->database();

      $this->load->library('session');

      $this->load->library('form_validation');
      //--
      $this->load->helper('consumir_rest');
      $this->load->helper('organizar_sepomex');
      $this->load->helper('array_push_assoc');
      //--

      $this->load->model('Prospecto_model');

      $this->load->model('Usuarios_model');

      $this->load->model('ClientePagador_model');

      $this->load->model('Menu_model');

      $this->load->model('vendedores_model');

      if (!$this->session->userdata("login")) {
        redirect(base_url()."admin");
      }
  }

  public function index()

  {

    $datos['actividadesEconomicas'] = $this->armarSelect('actividadEconomica');

    //$datos['nacionalidades'] = $this->Usuarios_model->nacionalidades();
  
    $arreglo_nacionalidad = consumir_rest('Lista_Valor','buscar', array('cod_static_tipo_valor'=>'NACIONALIDAD'));
    
    $datos['nacionalidades'] = $arreglo_nacionalidad->data;

    $datos['bancos'] = $this->armarSelect('banco');

    $datos['plazas'] = $this->armarSelect('plaza');

    $datos['giros'] = $this->armarSelect('giro');

    $datos['tipoCuentas'] = $this->armarSelect('tipoCuenta');

    $datos['permiso'] = $this->Menu_model->verificar_permiso_vista('Prospecto', $this->session-> userdata('id_rol'));

    //var_dump($datos['permiso']);die('');
    
    $datos['breadcrumbs'] = $this->Menu_model->breadcrumbs('Prospecto');

    $data['modulos'] = $this->Menu_model->modulos();

    $data['vistas'] = $this->Menu_model->vistas($this->session->userdata('id_rol'));

    $data['modulos'] = (array)$data['modulos'];

    
    foreach ($data['modulos'] as $modulo) {

        foreach ($data['vistas'] as $vista) {
            if($modulo["_id"]->{'$id'} == $vista->id_modulo_vista){
              //$data["modulo_user"][] = $modulo->id_modulo_vista;
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

    $vendedores = $this->Prospecto_model->getVendedores();

    $datos['vendedores'] = $vendedores;

    $data['modulos_vistas'] = $oneDim;

    $this->load->view('cpanel/header');

    $this->load->view('cpanel/menu', $data);

    $this->load->view('catalogo/Prospecto/index', $datos);

    $this->load->view('cpanel/footer');

  }

   public function listado_prospecto()

  {

    $listado = $this->Prospecto_model->listarProspecto();
    /*var_dump($listado);die('');
    foreach ($listado as $i => $value) {

      $listado[$i]['vendedor'] = $listado[$i]['nombre_vendedor']." ".$listado[$i]['apellido_vendedor'];

      $listado[$i]['prospecto'] = $listado[$i]['nombre_datos_personales']." ".$listado[$i]['apellido_p_datos_personales'];

    }*/
    echo json_encode($listado);

  }


/***/
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
/***/


  public function getProspecto()

  {

    $id      = $this->input->get('id');

    $listado = $this->Prospecto_model->getProspecto($id);

    

    $listado->prospecto = $listado->nombre_datos_personales." ".$listado->nombre_datos_personales;

  

    echo json_encode($listado);

  }



  public function registrar_prospecto(){

    $fecha = new MongoDB\BSON\UTCDateTime();
    
    $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));

    $formulario = $this->input->post();

    $tipo_persona = $formulario['rad_tipoper'];

    $rfc_fisico  = $formulario['rfc_fisico'];

    $id_vendedor = $formulario['id_vendedor'];

    (isset($formulario['id_proyecto']))? $id_proyecto = $formulario['id_proyecto']:$id_proyecto = "";

    $id_cliente = $formulario['id_cliente'];

    $id_datos_personales = $formulario['id_datos_personales'];

    $id_contacto = $formulario['id_contacto'];

    $nombre_prospecto = $formulario['nombre_prospecto'];

    $apellido_paterno_prospecto = $formulario['apellido_paterno_prospecto']; 

    $apellido_materno_prospecto = $formulario['apellido_materno_prospecto'];

    $telefono_principal_prospecto = $formulario['telefono_principal_prospecto'];

    /*$telefono_movil = $formulario['telefono_movil'];

    $telefono_casa  = $formulario['telefono_casa'];

    $telefono_trabajo =$formulario['telefono_trabajo'];

    $telefono_fax = $formulario['telefono_fax_r'];*/

    $correo_fisico = $formulario['correo_fisico'];

    $correo = $formulario['correo'];

    $coreo_opc_r  = $formulario['coreo_opc_r'];

    $rfc_moral  = $formulario['rfc_moral'];

    $razon_social_r = $formulario['razon_social_r'];

    $telefono_principal = $formulario['telefono_principal'];

    $observaciones_moral = $formulario['observaciones_moral'];

    $observaciones_fisica = $formulario['observaciones_fisica'];
    /*
    *   Validar tlf
    */
    if($tipo_persona=="moral"){
        
        $tlf_principal = $telefono_principal;

        $telefono_movil = "";

        $telefono_casa  = "";

        $telefono_trabajo = "";

        $telefono_fax = $formulario['telefono_fax_moral_r'];

    }else{
         
         $tlf_principal = $telefono_principal_prospecto;

         $telefono_movil = $formulario['telefono_movil'];

         $telefono_casa  = $formulario['telefono_casa'];

         $telefono_trabajo =$formulario['telefono_trabajo'];
         
         $telefono_fax = $formulario['telefono_fax_r'];

    }
    $telefono_arr = array(

                                'telefono_principal_prospecto'=>$tlf_principal ,
                                
                                'telefono_movil'=>$telefono_movil,
                                
                                'telefono_casa'=>$telefono_casa,

                                'telefono_trabajo'=>$telefono_trabajo,

                                'telefono_fax'=>$telefono_fax,

    );
    
    $mensaje_telefono_arr = array(

                                'telefono_principal_prospecto'=>"telfono principal",
                                
                                'telefono_movil'=>"telefono movil",

                                'telefono_casa'=>"telefono casa",

                                'telefono_trabajo'=>"telefono trabajo",

                                'telefono_fax'=>"telefono fax",

    );
    $cont = 0;
  
    foreach ($telefono_arr as $clave =>$tlf) {

      if(!$this->verificar_tlf($tlf)){
         echo "<span>El campo ".$mensaje_telefono_arr[$clave]." debe tener 12 caracteres!</span>";die('');
      }

      /*if($clave=="telefono_fax"){
          var_dump($this->verificar_tlf($tlf));
          var_dump($formulario['telefono_fax']);
          die('');
      }*/
    }
   
    /*
    *
    */
    
    if ($id_cliente == ""){

      if($tipo_persona == "fisica"){

          $datosPersonales= array(

                                        'nombre_datos_personales'         => trim(mb_strtoupper($nombre_prospecto, 'UTF-8')),

                                        'apellido_p_datos_personales'     => trim(mb_strtoupper($apellido_paterno_prospecto, 'UTF-8')),

                                        'apellido_m_datos_personales'     => trim(mb_strtoupper($apellido_materno_prospecto, 'UTF-8')),

                                        'rfc_datos_personales'            => trim(mb_strtoupper($rfc_fisico, 'UTF-8')),
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

                                  'tipo_cliente'           => 'PROSPECTO',
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
          $datos_contacto = array(

                                  'telefono_principal_contacto' => $telefono_principal_prospecto,

                                  'telefono_movil_contacto'     => $telefono_movil    ,

                                  'telefono_casa_contacto'      => $telefono_casa     ,

                                  'telefono_trabajo_contacto'   => $telefono_trabajo  ,

                                  'telefono_fax_contacto'       => $telefono_fax      ,

                                  'correo_contacto'             => strtolower($correo_fisico)     ,

                                  'correo_opcional_contacto'    => strtolower($coreo_opc_r) ,

                                  'id_codigo_postal'            => 1,
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

          $prospecto_vendedor = array(

                              'id_vendedor'   => $id_vendedor,

                              'id_proyecto'   => $id_proyecto,

                              'tipo_cliente'  => "PROSPECTO",

                              'observacion'    => $observaciones_fisica,
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

          $datos = array(   'datosPersonales'     => $datosPersonales,

                            'datosClientePa'     => $datosClientePa,

                            'datos_contacto'     => $datos_contacto,

                            'prospecto_vendedor' => $prospecto_vendedor
          );  

          $this->reglas_validacion('insert','prospecto');
          $this->mensajes_reglas();

          //---Para F´isico:Por alguna razon mensaje reglas no funciona...
          if($correo_fisico==""){
              echo "El campo correo electrónico es obligatorio";die('');
          }
          //-------------------------------------------------------------

          $rs = $this->Prospecto_model->getemail($correo_fisico);
                    
          if ($resul = $this->Prospecto_model->getemail($correo_fisico)) {
             echo "EL prospecto ya se encuentra registrado<br>El Prospecto fue registrado por ";
             echo $resul[0]["nombre_datos_personales"]." ".$resul[0]["apellido_p_datos_personales"]." ".$resul[0]["apellido_m_datos_personales"].", ";
             echo $resul[0]["correo_usuario"];
          }else{
              if ($this->form_validation->run() == true) { 
                  $this->Prospecto_model->guardarProspecto($datos);
              }else {
                echo validation_errors();
              }
          }

      }else{
           $datosPersonales= array(

                                    'nombre_datos_personales'         => trim(mb_strtoupper($razon_social_r, 'UTF-8')),

                                    'rfc_datos_personales'            => trim(mb_strtoupper($rfc_moral, 'UTF-8')),
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

                                    'tipo_cliente'           => 'PROSPECTO',
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
             $datos_contacto = array(

                                    'telefono_principal_contacto' => $telefono_principal,

                                    'telefono_fax_contacto'       => $telefono_fax      ,

                                    'correo_contacto'             => strtolower($correo)     ,

                                    'id_codigo_postal'            => 1,
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
            $prospecto_vendedor = array(

                                'id_vendedor'   => $id_vendedor,

                                'id_proyecto'   => $id_proyecto,

                                'tipo_cliente'  => "PROSPECTO",

                                'observacion'    => $observaciones_moral,
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

            $datos = array('datosPersonales'     => $datosPersonales,

                            'datosClientePa'     => $datosClientePa,

                            'datos_contacto'     => $datos_contacto,

                            'prospecto_vendedor' => $prospecto_vendedor

          );

          $this->reglas_validacion('insert','prospecto_moral');

          $this->mensajes_reglas();
          //---Para moral:Por alguna razon mensaje reglas no funciona y ya estaba funcionando de esa forma al momento de yo migrar a mongo db...
          if($correo==""){
              echo "El campo correo electrónico es obligatorio";die('');
          }
          //-------------------------------------------------------------
          if ($resul = $this->Prospecto_model->getemail($correo)) {
             echo "EL prospecto ya se encuentra registrado<br>El Prospecto fue registrado por ";
             echo $resul[0]["nombre_datos_personales"]." ".$resul[0]["apellido_p_datos_personales"]." ".$resul[0]["apellido_m_datos_personales"].", ";
             echo $resul[0]["correo_usuario"];
          }else{
              
              if ($this->form_validation->run() == true) { 

                  $this->Prospecto_model->guardarProspecto($datos);

              }else {

                    echo validation_errors();

                  }
          }//fin de if ($resul = $this->Prospecto_model->getemail($correo)) {
      }//FIN DE if($tipo_persona == "fisica")

    }else{
      //---Para moral:Por alguna razon mensaje reglas no funciona y ya estaba funcionando de esa forma al momento de yo migrar a mongo db...
      /*if($telefono_principal_prospecto==""){
        echo "El campo teléfono es obligatorio";die('');
      }*/
      if($tipo_persona == "fisica"){
        $datos_contacto = array(

                                'telefono_principal_contacto' => $telefono_principal_prospecto,

                                'telefono_movil_contacto'     => $telefono_movil    ,

                                'telefono_casa_contacto'      => $telefono_casa     ,

                                'telefono_trabajo_contacto'   => $telefono_trabajo  ,

                                'telefono_fax_contacto'       => $telefono_fax      ,

                                'correo_contacto'             => strtolower($correo_fisico)     ,

                                'correo_opcional_contacto'    => strtolower($coreo_opc_r)       ,

                          );

        $this->Prospecto_model->updateContato($id_contacto, $datos_contacto);

        $prospecto_vendedor = array(

                                'id_cliente'    => $id_cliente,

                                'id_proyecto'   => $id_proyecto,

                                'id_vendedor'   => $id_vendedor,

                                'tipo_cliente'  => "PROSPECTO",

                                'observacion'    => $observaciones_fisica
                            );

        $this->Prospecto_model->guardarProspectoVendedor($prospecto_vendedor);

      }else{

          $datos_contacto = array(
                                      'telefono_principal_contacto' => $telefono_principal_prospecto,

                                      'telefono_fax_contacto'       => $telefono_fax      ,

                                      'correo_contacto'             => $correo     ,

                                );

          $this->Prospecto_model->updateContato($id_contacto, $datos_contacto);

          $prospecto_vendedor = array(

                                  'id_cliente'    => $id_cliente,

                                  'id_proyecto'   => $id_proyecto,

                                  'id_vendedor'   => $id_vendedor,

                                  'tipo_cliente'  => "PROSPECTO",

                                  'observacion'    => $observaciones_moral

                              );
          $this->Prospecto_model->guardarProspectoVendedor($prospecto_vendedor);

      }//fin de if if($tipo_persona == "fisica")
    }

}
  /*
  * Registrar Cliente
  */
  public function registrar_cliente(){

      $formulario = $this->input->post(); 

      if ($formulario['rad_tipoper_cliente'] == "moral_cliente") {

           $rfc_cliente = (isset($formulario['rfc_moral'])) ? $formulario['rfc_moral'] : '';

      }else{

           $rfc_cliente = (isset($formulario['rfc'])) ? $formulario['rfc'] : '';

      }
      
      $cliente = $this->Prospecto_model->obtenerCliente2($rfc_cliente, $formulario['id_cliente']);
      if (count($cliente)>0) {
         echo "EL Cliente ya se encuentra registrado<br>El Cliente fue registrado por ";
         echo $cliente[0]["nombre_datos_personales"]." ".$cliente[0]["apellido_p_datos_personales"]." ".$cliente[0]["apellido_m_datos_personales"].", ";
         echo $cliente[0]["correo_usuario"];

      }else{
            // datos generales cliente
            $tipo_persona               = (isset($formulario['rad_tipoper_cliente'])) ? $formulario['rad_tipoper_cliente'] : '';

            $nombre_cliente             = (isset($formulario['nombre_cliente'])) ? $formulario['nombre_cliente'] : '';

            $id_cliente                 = (isset($formulario['id_cliente'])) ? $formulario['id_cliente'] : '';

            $id_prospecto                 = (isset($formulario['id_prospecto'])) ? $formulario['id_prospecto'] : '';

            $id_datos_personales        = (isset($formulario['id_datos_personales'])) ? $formulario['id_datos_personales'] : '';

            $id_contacto                = (isset($formulario['id_contacto'])) ? $formulario['id_contacto'] : '';

            $id_proyecto                = $formulario['id_proyecto'];

            $id_vendedor                = $formulario['id_vendedor'];

            $apellido_paterno_cliente   = (isset($formulario['apellido_paterno_cliente'])) ? $formulario['apellido_paterno_cliente'] : '';

            $apellido_materno_cliente   = (isset($formulario['apellido_materno_cliente'])) ? $formulario['apellido_materno_cliente'] : '';

            $curp_datos_personales      = (isset($formulario['curp_datos_personales'])) ? $formulario['curp_datos_personales'] : '';

            $rfc_cliente                = (isset($formulario['rfc'])) ? $formulario['rfc'] : '';

            $actividad_economica        = (isset($formulario['actividad_economica'])) ? $formulario['actividad_economica'] : '';

            $fecha_nac_datos_personales = (isset($formulario['fecha_nac_datos_personales'])) ? $formulario['fecha_nac_datos_personales'] : '';

            $correo_clente              = (isset($formulario['correo_clente'])) ? $formulario['correo_clente'] : '';

            $telefono_cliente           = (isset($formulario['telefono_cliente'])) ? $formulario['telefono_cliente'] : '';

            $actividad_economica        = (isset($formulario['actividad_economica'])) ? $formulario['actividad_economica'] : '';

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
            
            if ($tipo_plaza == '') {

                $tipo_plaza = null;

            }

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

            $imagen           = (isset($formulario['img_n_identificacion'])) ? $formulario['img_n_identificacion'] : '';

            $imagenDomFiscal  = (isset($formulario['img_domicilio'])) ? $formulario['img_domicilio'] : '';

            $imagenActa       = (isset($formulario['acta_img_r'])) ? $formulario['acta_img_r'] : '';

            $imagenMoral      = (isset($formulario['img_n_identificacion'])) ? $formulario['img_n_identificacion'] : '';

            $imagenLegal      = (isset($formulario['rfc_img_rep'])) ? $formulario['rfc_img_rep'] : '';
            
            $imagenCliente      = (isset($formulario['imgProfile'])) ? $formulario['imgProfile'] : '';
            
            $imagenClienteMoral  = (isset($formulario['imgProfile'])) ? $formulario['imgProfile'] : '';
          //------------------------------------------------------------------------------  

             if($imagenCliente != "undefined"){
              if(file_exists(sys_get_temp_dir().'/'.$imagenCliente))
                {
                  rename(sys_get_temp_dir().'/'.$imagenCliente,
                                          'assets/cpanel/ClientePagador/images/'.$imagenCliente
                                        );
                                        //unlink(sys_get_temp_dir().'/'.$imagen);                        
                }
            }else if($tipo_persona == "fisica_cliente"){
           
                echo "Debe seleccionar la imagen del cliente";die('');
            }

          if($imagenClienteMoral != "undefined"){
              if(file_exists(sys_get_temp_dir().'/'.$imagenClienteMoral))
              {
                rename(sys_get_temp_dir().'/'.$imagenClienteMoral,
                                        'assets/cpanel/ClientePagador/images/'.$imagenClienteMoral
                                      );
                                      //unlink(sys_get_temp_dir().'/'.$imagen);                        
              }
          }else if($tipo_persona == "moral_cliente"){
              //var_dump($imagenClienteMoral);
              echo "Debe seleccionar la imagen del cliente";die('');
          }





          if(!empty($imagen) && $imagen != "undefined"){

              if(file_exists(sys_get_temp_dir().'/'.$imagen)){

                  rename(sys_get_temp_dir().'/'.$imagen,

                                        'assets/cpanel/ClientePagador/images/'.$imagen

                                      );

                                      //unlink(sys_get_temp_dir().'/'.$imagen);                        
              }
          }else if($tipo_persona == "fisica_cliente"){
              echo "Debe seleccionar la imagen de la Copia escaneada del N° Identificacion";die('');
          }
          //-------------------------------------------------------------------------------
          if(!empty($imagenDomFiscal) && $imagenDomFiscal != "undefined" ){

              if(file_exists(sys_get_temp_dir().'/'.$imagenDomFiscal)){

                  rename(sys_get_temp_dir().'/'.$imagenDomFiscal,

                                        'assets/cpanel/ClientePagador/images/'.$imagenDomFiscal

                                      );

                                      //unlink(sys_get_temp_dir().'/'.$imagen);

              }

          }else{
             echo "Debe seleccionar la imagen de la copia escaneada del Domicilio Fiscal";die('');
          }
          //--------------------------------------------------------------------------------
          if(!empty($imagenActa) && $imagenActa != "undefined" ){

              if(file_exists(sys_get_temp_dir().'/'.$imagenActa)){

                rename(sys_get_temp_dir().'/'.$imagenActa,

                                        'assets/cpanel/ClientePagador/images/'.$imagenActa

                                      );

                                      //unlink(sys_get_temp_dir().'/'.$imagen);
               }
          }
          else if($tipo_persona == "moral_cliente"){
              echo "Debe seleccionar la imagen de la copia escaneada del Acta Constitutiva";die('');
          }
          //--------------------------------------------------------------------------------
          if(!empty($imagenMoral) && $imagenMoral != "undefined"){

              if(file_exists(sys_get_temp_dir().'/'.$imagenMoral)){

                rename(sys_get_temp_dir().'/'.$imagenMoral,

                                        'assets/cpanel/ClientePagador/images/'.$imagenMoral

                                      );

                                  //unlink(sys_get_temp_dir().'/'.$imagen);
              }
          }else if($tipo_persona == "moral_cliente"){
              echo "Debe seleccionar la imagen de la copia escaneada del N° Identificacion";die('');
          }
          //--------------------------------------------------------------------------------
          /*var_dump($tipo_persona);
          var_dump($rfc_representante);
          var_dump($telf_rep_legal);
          var_dump($correo_rep_legal);
          die('');*/
          //-----------------------------------------------------------------------------
         
          //-----------------------------------------------------------------------------
          if(!empty($imagenLegal) && $imagenLegal != "undefined"){

              if(file_exists(sys_get_temp_dir().'/'.$imagenLegal)){

                rename(sys_get_temp_dir().'/'.$imagenLegal,

                                        'assets/cpanel/ClientePagador/images/'.$imagenLegal

                                      );

                                      //unlink(sys_get_temp_dir().'/'.$imagen);
              }
          }
          else if(($tipo_persona == "moral_cliente")&&($rfc_representante!="")&&($telf_rep_legal!="")&&($correo_rep_legal!="")){
              echo "Debe seleccionar la imagen de la copia escaneada del RFC del representante legal";die('');
          }
          //-------------------------------------------------------------------------------
        $datosPersonales= array(

                                    'nombre_datos_personales'         => trim(mb_strtoupper($nombre_cliente, 'UTF-8')),

                                    'apellido_p_datos_personales'     => trim(mb_strtoupper($apellido_paterno_cliente, 'UTF-8')),

                                    'apellido_m_datos_personales'     => trim(mb_strtoupper($apellido_materno_cliente, 'UTF-8')),

                                    'rfc_datos_personales'            => trim(mb_strtoupper($rfc_cliente, 'UTF-8')),

                                    'curp_datos_personales'           => trim(mb_strtoupper($curp_datos_personales, 'UTF-8')),

                                    'nacionalidad_datos_personales'   => $pais_nacionalidad,

                                    'id_contacto'                     => $id_contacto,

                                    'id_datos_personales'             => $id_datos_personales,

                                    'fecha_nac_datos_personales'      => trim(date("Y-m-d", strtotime($fecha_nac_datos_personales))),

                                    

                                    ); //print_r($datosPersonales);die;

        $datosClientePa = array(

                                'tipo_persona_cliente'   => 'FISICA',

                                'actividad_e_cliente'    => $actividad_economica,

                                'rfc_img'                => $imagen,

                                'pais_cliente'           => $pais_origen,

                                'dominio_fiscal_img'     => $imagenDomFiscal,

                                'imagenCliente'     => $imagenCliente,

                                'tipo_cliente'           => 'CLIENTE',

                                'id_cliente'             => $id_cliente,

                                'id_contacto'            => $id_contacto,

                                'id_datos_personales'    => $id_datos_personales

                                ); 

        $datosDomicilio = array(

                                'correo_contacto'             => $correo_clente,

                                'telefono_principal_contacto' => $telefono_cliente,

                                'calle_contacto'              => $calle_cliente,

                                'id_codigo_postal'            => $colonia,

                                'id_contacto'                 => $id_contacto,

                                'exterior_contacto'           => $exterior_cliente,

                                'interior_contacto'           => $interior_cliente,                      

                          );

        $datosDatosPMoral =array(

                               'nombre_datos_personales'    => trim(mb_strtoupper($razon_social,'UTF-8')),     

                               'rfc_datos_personales'       => trim(mb_strtoupper($rfc_moral,'UTF-8')),

                               'id_datos_personales'        => $id_datos_personales,     

                               'fecha_nac_datos_personales' => trim(date("Y-m-d", strtotime($fecha_cons_r))),     

                                

                                );

        $datosClienteMoral = array(
                                
                                'imagenCliente'          => $imagenClienteMoral,

                                'acta_constitutiva'      => $acta_constutiva_r,

                                'acta_img'               => $imagenActa,

                                'giro_mercantil'         => $giro_mercantil_r, 

                                'tipo_persona_cliente'   => 'MORAL',

                                'rfc_img'                => $imagenMoral,

                                'dominio_fiscal_img'     => $imagenDomFiscal,

                                'tipo_cliente'           => 'CLIENTE',

                                'id_cliente'             => $id_cliente,

                                'id_contacto'            => $id_contacto,

                                'id_datos_personales'    => $id_datos_personales

        );

        $datosDomicilioMoral =array(

                                'correo_contacto'             => $correo_moral_m,

                                'telefono_principal_contacto' => $telefono_moral_m,

                                'calle_contacto'              => $calle_cliente,

                                'id_codigo_postal'            => $colonia,

                                'exterior_contacto'           => $exterior_cliente,

                                'interior_contacto'           => $interior_cliente,

                                'id_contacto'                 => $id_contacto   

        );

        

        $datosRepLegal = array(

                                  'nombre_datos_personales'      => trim(mb_strtoupper($nombre_representante, 'UTF-8')),   

                                  'apellido_p_datos_personales'  => trim(mb_strtoupper($apellido_paterno_rep, 'UTF-8')),   

                                  'apellido_m_datos_personales'  => trim(mb_strtoupper($apellido_materno_rep, 'UTF-8')),   

                                  'rfc_datos_personales'         => trim(mb_strtoupper($rfc_representante, 'UTF-8')),   

                                  'curp_datos_personales'        => trim(mb_strtoupper($curp_rep_legal, 'UTF-8')),   

                                  );

        $datosRepLegal_clipa = array(

                                    'correo_rep_legal'     => $correo_rep_legal ,   

                                    'telf_rep_legal'       => $telf_rep_legal,

                                    'rfc_img'              => $imagenLegal

        );

        $datosCuenta   = array(

                                    'clabe_cuenta'         => trim(mb_strtoupper($clabe, 'UTF-8')),

                                    'numero_cuenta'        => $numero_cuenta,

                                    'tipo_cuenta'          => $tipo_cuenta,  

                                    'id_banco'             => $banco,        

                                    'swift_cuenta'         => trim(mb_strtoupper($swift, 'UTF-8')),        

                                    'id_plaza'             => $tipo_plaza,

                                   

                                    'sucursal_cuenta'      => trim(mb_strtoupper($sucursal, 'UTF-8')),  

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

                              );

        $carteraCliente = array('id_vendedor' => $id_vendedor, 

                                'id_proyecto' => $id_proyecto, 

                                'id_cliente'  => $id_cliente,

                                'tipo_cliente' => 'CLIENTE');



        if ($tipo_persona == "moral_cliente"){

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

              $id = array('id_cliente' => $id_cliente, 'id_contacto' => $id_contacto, 'id_datos_personales' => $id_datos_personales );

              //print_r($datos);die;
              //descomentar  
              $res_guardar_cliente_pagador = $this->Prospecto_model->guardarClientePagador($datos, $id);
              $prospecto_vendedor = array(

                                    'tipo_cliente'  => "CLIENTE"

                                );
              //var_dump($id_prospecto);die('');
              $this->Prospecto_model->actualizarProspectoVendedor($id_prospecto, $prospecto_vendedor, $id_cliente, $id_proyecto);

              //$this->Prospecto_model->guardarCarteraCliente($carteraCliente);
        }else {

                echo validation_errors();
        }
    }
  }  
  /***/
  public function getProyecto($id_vendedor = ""){

     $proyecto = $this->Prospecto_model->obtenerProyecto($id_vendedor);

     echo json_encode($proyecto);

  }

  public function clienteExiste()

  {

    $rfc  = $this->input->post('rfc');

    $cliente = $this->Prospecto_model->obtenerCliente($rfc);

    echo json_encode($cliente);

  }



  public function trasladarProspecto(){

    $id_cliente = $this->input->post('id_cliente');print_r($id_cliente);die;

  }
  /*
  * consultarCliente
  */
  public function consultarCliente(){

      $id_cliente = $this->input->post('id_cliente');

      $tipo_cliente = $this->input->post('tipo_cliente');

      $consulta = $this->Prospecto_model->consultaClientePorID($id_cliente, $tipo_cliente);
      if(isset($consulta)){
          print_r($consulta);die;
      }
  }
  /***/
  public function actualizar_prospecto(){

    $formulario = $this->input->post(); 

    $tipo_persona = $formulario['tipo_persona_e'];

    $id_cliente = $formulario['id_cliente'];

    $id = $formulario['id_prospecto'];

    $id_datos_personales = $formulario['id_datos_personales'];

    $id_contacto = $formulario['id_contacto'];

    $telefono_principal_prospecto = $formulario['telefono_principal_prospecto_e'];

    $telefono_movil = $formulario['telefono_movil_contacto_e'];

    $telefono_casa  = $formulario['telefono_casa_contacto_e'];

    $telefono_trabajo =$formulario['telefono_trabajo_contacto'];

    $telefono_fax = $formulario['telefono_fax_e'];

    $telefono_fax_moral = $formulario['telefono_fax'];

    $correo_fisico = $formulario['correo_contacto'];

    $coreo_opc_r  = $formulario['coreo_contactp_opc_e'];

    $telefono_principal = $formulario['telefono_moral_e'];

    $correo_moral = $formulario['correo_moral_e'];

    $observaciones_fisica_editar = $formulario['observaciones_fisica_editar'];

    $observaciones_moral_editar = $formulario['observaciones_moral_editar'];
    

    if($tipo_persona == "FISICA"){
        if($correo_fisico==""){
            echo "El campo correo electrónico es obligatorio";die('');
        }
        if ($resul = $this->Prospecto_model->getemailActualizar($correo_fisico,$id_contacto)) {
             echo "EL prospecto ya se encuentra registrado<br>El Prospecto fue registrado por ";
             echo $resul[0]["nombre_datos_personales"]." ".$resul[0]["apellido_p_datos_personales"]." ".$resul[0]["apellido_m_datos_personales"].", ";
             echo $resul[0]["correo_usuario"];
             die('');
        }
        //---Por alguna razon mensaje reglas no funciona y ya estaba funcionando de esa forma al momento de yo migrar a mongo db...
        if($telefono_principal_prospecto==""){
            echo "El campo teléfono es obligatorio";die('');
        }
        $datos_contacto = array(

                                'telefono_principal_contacto' => $telefono_principal_prospecto,

                                'telefono_movil_contacto'     => $telefono_movil    ,

                                'telefono_casa_contacto'      => $telefono_casa     ,

                                'telefono_trabajo_contacto'   => $telefono_trabajo  ,

                                'telefono_fax_contacto'       => $telefono_fax      ,

                                'correo_contacto'             => $correo_fisico     ,

                                'correo_opcional_contacto'    => $coreo_opc_r       ,

                          );
        //---
        //Valido el correo de persona fisica
        if($datos_contacto["correo_contacto"]==$datos_contacto["correo_opcional_contacto"]){
          echo "<span>El correo contacto no puede ser igual al correo opcional</span>";die('');
        }
        //---
        $this->Prospecto_model->updateContato($id_contacto, $datos_contacto);
        $prospecto = array('observacion' => $observaciones_fisica_editar);
        $this->Prospecto_model->updateprospecto($id, $prospecto);   
    }else{

      if($correo_moral==""){
          echo "El campo correo electrónico es obligatorio";die('');
      }
      if ($resul = $this->Prospecto_model->getemailActualizar($correo_moral,$id_contacto)) {
             echo "EL prospecto ya se encuentra registrado<br>El Prospecto fue registrado por ";
             echo $resul[0]["nombre_datos_personales"]." ".$resul[0]["apellido_p_datos_personales"]." ".$resul[0]["apellido_m_datos_personales"].", ";
             echo $resul[0]["correo_usuario"];
             die('');
        }
      //---Por alguna razon mensaje reglas no funciona y ya estaba funcionando de esa forma al momento de yo migrar a mongo db...
      if($telefono_principal==""){
          echo "El campo teléfono es obligatorio";die('');
      }
      $datos_contacto = array(
                                  'telefono_principal_contacto'   => $telefono_principal,

                                  'telefono_fax_contacto'       => $telefono_fax_moral,

                                  'correo_contacto'             => $correo_moral,
      );
      $this->Prospecto_model->updateContato($id_contacto, $datos_contacto);
      $prospecto = array('observacion' => $observaciones_moral_editar);
      $this->Prospecto_model->updateprospecto($id, $prospecto);
    }

  echo json_encode("<span>El Prospecto se ha Actualizado exitosamente!</span>"); 
  
  }

  public function eliminar (){

     $id_cliente = $this->input->post('id_cliente');

     $id_proyecto = $this->input->post('id_proyecto');

     $id = $this->input->post('id'); //print_r($id);die;

     $existe = $this->Prospecto_model->buscarClienteCorrida($id_cliente);
     
     if ($this->Prospecto_model->buscarClienteCorrida($id_cliente)) {

         echo "<span>No se puede eliminar el registro porque tiene dependencia en otras tablas!</span>";

     }else{

        $this->Prospecto_model->eliminar($id_cliente, $id_proyecto, $id);

     }
  }

  public function status_prospecto(){

      $id = $this->input->post('id');

      $status = $this->input->post('status'); 

      $this->Prospecto_model->status($id,$status, 'prospecto_vendedor');

      echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
  }

  public function carteraCliente()

  {

    $id_cliente = $this->input->post('id_cliente');

    $id_vendedor = $this->input->post('id_vendedor');

    $id_proyecto = $this->input->post('id_proyecto');



    $carteraCliente = $this->Prospecto_model->carteraCliente($id_cliente, $id_proyecto, $id_vendedor);

    echo json_encode($carteraCliente);

  }

  public function reglas_validacion($method, $tipo){

      switch ($tipo) {

          case 'prospecto':

                if($method=="insert"){
                    // Reglas para la tabla de cliente

                    // $this->form_validation->set_rules('rad_tipoper_cliente','Tipo persona','required');

                    $this->form_validation->set_rules('nombre_prospecto','Nombre(s)','required');

                    $this->form_validation->set_rules('apellido_paterno_prospecto','Apellido Paterno','required');

                    $this->form_validation->set_rules('apellido_materno_prospecto','Apellido Materno','required');

                   // $this->form_validation->set_rules('rfc_fisico','RFC','required|is_unique[datos_personales.rfc_datos_personales]');

                    //$this->form_validation->set_rules('rfc_img','Copia del RFC Escaneada','required');

                    $this->form_validation->set_rules('telefono_principal_prospecto','Telefono Principal','required');

                    // $this->form_validation->set_rules('curp_datos_personales','C.U.R.P.','required');

                    // $this->form_validation->set_rules('fecha_nac_datos_personales','Fecha de Nacimiento','required');

                    $this->form_validation->set_rules('correo_fisico','Correo Electrónico','required|valid_email');

                    $this->form_validation->set_rules('coreo_opc_r','Correo Electrónico opcional','valid_email');

                      

                    // Reglas para la tabla cuenta
                }
               break;

          case 'prospecto_moral':

                if($method=="insert"){
                    // Reglas para la tabla de cliente

                    //$this->form_validation->set_rules('rad_tipoper_cliente','Tipo persona','required');

                    //$this->form_validation->set_rules('razon_social','Razon Social','required');
                    
                    //$this->form_validation->set_rules('rfc_moral_cliente','RFC Cliente','required');

                    //$this->form_validation->set_rules('rfc_img','Copia del RFC Escaneada','required');

                    //$this->form_validation->set_rules('giro_mercantil_r','Giro Mercantil','required');

                    //$this->form_validation->set_rules('fecha_cons_r','Fecha de Constitucion','required');

                    //$this->form_validation->set_rules('correo','Correo Electrónico','required');
                    $this->form_validation->set_rules('correo','Correo Electrónico','required|valid_email');

                    //$this->form_validation->set_rules('telefono_moral_m','Teléfono','required');

                    //$this->form_validation->set_rules('acta_constutiva_r','Acta Constitutiva','required');

                    // Reglas para la tabla cuenta
                }
                break;
          case 'clientepagador':

              if($method=="insert"){
                // Reglas para la tabla de cliente

                //$this->form_validation->set_rules('rad_tipoper_cliente','Tipo persona','required');

                //$this->form_validation->set_rules('razon_social','Razon Social','required');

                //$this->form_validation->set_rules('rfc_moral_cliente','RFC Cliente','required');

                //$this->form_validation->set_rules('rfc_img','Copia del RFC Escaneada','required');

                //$this->form_validation->set_rules('giro_mercantil_r','Giro Mercantil','required');

                //$this->form_validation->set_rules('fecha_cons_r','Fecha de Constitucion','required');

                $this->form_validation->set_rules('correo_clente','Correo Electrónico','required');

                //$this->form_validation->set_rules('telefono_moral_m','Teléfono','required');

                //$this->form_validation->set_rules('acta_constutiva_r','Acta Constitutiva','required');

                // Reglas para la tabla cuenta
              }
               break;
          case 'cuentaCliente':

              if($method=="insert"){

                  $this->form_validation->set_rules('clabe','CLABE ','required');
                  //|is_unique[cuenta_cliente.clabe_cuenta]

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

                  $this->form_validation->set_rules('colonia','Código Postal','required');

                  //$this->form_validation->set_rules('telefono_principal_contacto','Teléfono','required');

                    }else if($method=="update"){

                   // Reglas para la tabla contacto

                  $this->form_validation->set_rules('calle_contacto','Calle Cliente','required');

                  $this->form_validation->set_rules('exterior_contacto','Número Exterior','required');

                  $this->form_validation->set_rules('colonia','Código Postal','required');

                  //$this->form_validation->set_rules('telefono_principal_contacto','Teléfono','required');
              }
              break;
          case 'contacto':

              if($method=="insert"){

                  $this->form_validation->set_rules('nombre_contacto','Nombre Contacto ','required');

                   $this->form_validation->set_rules('telefono_principal_contacto','Teléfono','required');

                  $this->form_validation->set_rules('correo_contacto','Correo','required');

                  }else if($method=="update"){

                  $this->form_validation->set_rules('nombre_contacto','Nombre Contacto ','required');

                  $this->form_validation->set_rules('telefono_principal_contacto','Teléfono','required');

                  $this->form_validation->set_rules('correo_contacto','Correo','required');     
              }
              break;
          case 'repLegal':

              if($method=="insert"){

                  $this->form_validation->set_rules('nombre_representante','Nombre del Representante Legal','required');

                  $this->form_validation->set_rules('apellido_paterno_rep','Apellido Paterno del Representante Legal','required');

                  $this->form_validation->set_rules('apellido_materno_rep','Apellido Materno del Representante Legal','required');

                  //$this->form_validation->set_rules('rfc_representante','RFC del Reprentante Legal','required');

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

    $this->form_validation->set_message('valid_email', 'Ingrese un Correo Valido P. EJ. ejemplo@dominio.com');    

  }




  /*
  * Eliminar multiple
  */
  public function eliminar_multiple(){
    $this->Prospecto_model->eliminar_multiple($this->input->post('id'));
  }
  /*
  *
  */



  public function status_multiples()

  {

    $this->Prospecto_model->status_multiple($this->input->post('id'), $this->input->post('status'), "prospecto_vendedor");

    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso

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
      if($valor>=1 && $valor<12 ){
          $respuesta = false;
      }else{
          $respuesta = true;
      }
      return $respuesta;
  }
  /*
  *
  */
   



  

}