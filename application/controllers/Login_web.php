<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Login_web extends CI_Controller
{
	private $operaciones;
	function __construct()
	{
    parent::__construct();
    //$this->load->database();
    $this->load->model('Login_web_model');
    $this->load->library('Dom_pdf');
    $this->load->helper('cookie');

    /*if (!$this->session->userdata("login")) {
      redirect(base_url());
    }*/
  }

  public function index()
  {
// CASO 1 LLEGA TODO VACIO
    if($_POST['usuario'] == "" && $_POST['pass'] == ""){
        $respuesta = array("error" => "Datos_vacio ");
    }
// CASO 2 LLEGA EL CORREO Y NO EL SERIAL
    if(($_POST['usuario'] != "") && ($_POST['pass'] == "")){
        $correo_usuario = array("correo_contacto" => $_POST['usuario'],'eliminado'=>false);

          $verificacion_correo =  $this->Login_web_model->verificar_correo($correo_usuario);

          if($verificacion_correo){
           $respuesta =  $verificacion_correo;

          }else{
            $respuesta = array("estado_login" => 0);
          }
    }
// CASO 3 LLEGA TODO
    if($_POST['usuario'] != "" && $_POST['pass'] != ""){
        $respuesta = array("etapa" => "segunda etapa");
        
        $verificacion_final =  $this->Login_web_model->buscar_membresia($_POST['usuario'], $_POST['pass']);

        if($verificacion_final){
          $respuesta = array("estado_login" => 4);

          $cookie= array(
            'name'   => 'usuario',
            'value'  => $_POST['usuario'],
            'expire' => '36000',
        );
        $this->input->set_cookie($cookie);

        $cookie= array(
          'name'   => 'pass',
          'value'  => $_POST['pass'],
          'expire' => '36000',
      );
      $this->input->set_cookie($cookie);


        }else{
          $respuesta = array("estado_login" => 3);
        }
    }
    print_r(json_encode($respuesta));
  }


  

 public function pd()
{


             
  
  $traer_data =  $this->Login_web_model->buscar_membresia(get_cookie("usuario"), get_cookie("pass"));


  //print_r($traer_data[0]['servicios']);die();
  
  
  /*
  foreach($traer_data[0]['planes'] as $plan){
    print_r($plan['titulo']);
  };
  die();*/
  $date_bruta_1 = json_encode($traer_data[0]['fecha_inicio']);
  $date_bruta_2 = json_encode($traer_data[0]['fecha_fin']);
  $resultado_1 = substr($date_bruta_1, 9, 10);

  $resultado_2 = substr($date_bruta_2, 9, 10);

  $date_i = new DateTime($resultado_1);
  $date_i->format('d-m-Y');

  $date_f = new DateTime($resultado_2);
 

  //print_r( $date_i->format('d-m-Y')); die();
if($traer_data[0]['status'] == 1){
  $estatus = "Activo";
}else{
  $estatus = "Inactivo";
}
// ID DE MEMBRESIA

//print_r($traer_data[0]['_id']->{'$id'});die();
//print_r($traer_data[0]['servicios']);die();



$datos['n'] = $traer_data[0]['datos_persona']['nombre_datos_personales']; // $_POST['data'][0]['nombre'];
$datos['a'] = $traer_data[0]['datos_persona']['apellido_p_datos_personales'];// $_POST['data'][0]['apellido'];
$datos['m'] = $traer_data[0]['n_membresia'];// $_POST['data'][0]['membresia'];
$datos['s'] = $estatus; // $_POST['data'][0]['status'];
$datos['f1'] = $date_i->format('d-m-Y'); // $_POST['data'][0]['fecha_i'];
$datos['f2'] = $date_f->format('d-m-Y');
$datos['planes'] = $traer_data[0]['planes']['titulo']; // $_POST['data'][0]['fecha_i'];
$datos['servicios'] = $traer_data[0]['servicios'];

//  $html = $this->load->view('portal/pdf_saldos', $data, true);
  // Cargamos la librería


  $this->load->view('portal/pdf_saldos', $datos);
  $html = $this->output->get_output();
  


  // Convert to PDF
  $this->dompdf->load_html($html);        
  $this->dompdf->render();
  $this->dompdf->stream("reporte_saldos_".date('d-m-Y').".pdf");

  /*
// definamos un nombre para el archivo. No es necesario agregar la extension .pdf
$filename = 'comprobante_pago';
// generamos el PDF. Pasemos por encima de la configuración general y definamos otro tipo de papel
$this->pdfgenerator->generate($html, $filename, true, 'Letter', 'portrait');
*/
}
  

 public function pdw()
{
   $this->load->view('portal/pdf_saldos');
}
}