<?php
defined('BASEPATH') OR exit('No direct script access allowed');


require_once("assets/php/mailer/class.phpmailer.php");
require_once("assets/php/mailer/class.smtp.php");
require_once("assets/php/mailer/PHPMailerAutoload.php");



class Panel extends CI_Controller {


	 
	function __construct()
	{
	parent::__construct();
//	$this->load->database();
	$this->load->model('Acceso_model');
	$this->load->model('Estadistica_model');
	$this->load->model('Entradas_model');
	$this->load->model('Meta_etiquetas_model');
	$this->load->model('MiCorreo_model');
	$this->load->model('Banner_model');
	
	  }
  
public function index(){
	$this->load->view('panel/login');
}

public function pd(){
	$operacion = $this->Acceso_model->truncate_acceso();
	print_r($operacion);
}
public function p(){
	
	$user = "admin";
	$pass = "123456";

	$operacion = $this->Acceso_model->verificar_usuario($user, $pass);
	print_r($operacion);
}
public function login_ws()
	{
		$user = $this->input->post("user");
		$passw = $this->input->post("pass");

		//print_r($user." ".$pass);


		$operacion = $this->Acceso_model->login($user, $passw);
		print_r($operacion);
}
public function cambio_contra_bd(){
	$pass_old = $_POST['password_old'];
	$pass = $_POST['password_1'];
	$pass2 = $_POST['password_2'];
	
	$destinatario = $this->Entradas_model->correo_admin();

	$body = '

	<div style="background: #b7b7b7; padding-top: 15px; padding-bottom: 15px;
	margin: 0">
		<div style="width:50%;
					 margin-left: auto;
					margin-right: auto;
				color: #081e41;
			
				background-size: container;
				-moz-background-size: container;
				-webkit-background-size: container;
				-o-background-size: container;
				overflow: hidden; 
				box-shadow: 0 0 40px rgba(0, 0, 0, 0.8);">
	
		<div style="background: #ffffffec; height: 90%;">
				<div>
					<img style="margin-left: auto; margin-right: auto; display: block; padding-top: 5%" src="http://www.siteag.ag2-group.com/urban/assets/img/complementos/logo1.png" width="200PX" alt="LOGO">
				</div>
				<div style="text-align: center">
					<h2>Estimado usuario la contraseña, a sido cambiada por la siguiente: <br><strong style="color: "#E65100">'.$pass.'</strong></h2>
				</div>
				<div>
					<p style="text-align: justify; padding-left: 5%; padding-right: 5%; color: #081e41">
					</p>
				</div>
				<div style="background: #000000c2; text-align: center; height: 50px; padding-top: 15  px; padding-bottom: 25px;">
					<h4><a style="color: #ffffffec !important; text-decoration-line: none" href="https://urbanhub.mx/panel">www.UrbanHub.mx</a></h4>
				</div>
		</div>
	</div>
	</div>
		';
	if($pass == $pass2){

		
			$host = "mail.urbanhub.mx";
			$puerto = "465";
			$usuario = "info@urbanhub.mx";
			$clave = "coworking2019";
			
			/*
			$host = $res[0]['servidor_smtp'];
			$puerto = $res[0]['puerto'];
			$usuario = $res[0]['usuario'];
			$clave = $res[0]['clave'];
			*/

	
			$operacion = $this->Acceso_model->cambio_contraseña($pass_old, $pass);
		//print_r($operacion);
	//die();
			if( $operacion == 1){
				//echo "3";
				
				$res = $this->MiCorreo_model->buscar_mi_correo();
	$mail = new PHPMailer();
	$mail->SMTPOptions = array(
		'ssl' => array(
			'verify_peer' => false,
			'verify_peer_name' => false,
			'allow_self_signed' => true
		)
	);
	$mail->SMTPDebug = SMTP::DEBUG_SERVER;
	$mail->SMTPDebug = 0; //Alternative to above constant
			$mail->isSMTP();
			

			$mail->Host = $host; 
			$mail->Port = $puerto; 
			$mail->SMTPSecure = "ssl"; 
			$mail->SMTPAuth = true;
			$mail->Username = $usuario;
			$mail->Password = $clave;
			$mail->CharSet = 'UTF-8';
			$mail->From = $usuario;
			$mail->FromName = "UrbanHub Cambio de Contraseña";
			$mail->Subject = "UrbanHub Web";
			$mail->msgHTML($body);
			$mail->Body = $body;
			$mail->AddAddress($destinatario, 'Sitio web');
			//$mail->AddAddress("contact@ag2.com.mx", "site ag2");
			$mail->AddBCC("sacrotzenil@gmail.com", 'UrbanHub');
			$exito = $mail->Send();
			$mail->ClearAddresses();
				if($exito){  echo "1"; 
				} else { echo "2";  }
				exit();
				
			}else{
				print_r(3);
			}
			

	}else{
		echo "4";
	}





}

public function generarCodigo($longitud) {
    $key = '';
    $pattern = '1234567890abcdefghijklmnopqrstuvwxyz';
    $max = strlen($pattern)-1;
    for($i=0;$i < $longitud;$i++) $key .= $pattern{mt_rand(0,$max)};
    return $key;
}
public function reinicio_contra_bd(){

	$pass =	$this->generarCodigo(12);
	$newp = $pass;
	$destinatario = $this->Entradas_model->correo_admin();
	$body = '

	<div style="background: #b7b7b7; padding-top: 15px; padding-bottom: 15px;
	margin: 0">
		<div style="width:50%;
					margin-left: auto;
					margin-right: auto;
				color: #081e41;
			
				background-size: container;
				-moz-background-size: container;
				-webkit-background-size: container;
				-o-background-size: container;
				overflow: hidden; 
				box-shadow: 0 0 40px rgba(0, 0, 0, 0.8);">

		<div style="background: #ffffffec; height: 90%;">
				<div>
					<img style="margin-left: auto; margin-right: auto; display: block; padding-top: 5%" src="http://www.siteag.ag2-group.com/urban/assets/img/complementos/logo1.png" width="200PX" alt="LOGO">
				</div>
				<div style="text-align: center">
					<h2>Estimado usuario en vista de que no recuerda la contraseña, las misma a sido cambiada por la siguiente: <br><strong style="color: "#E65100">'.$newp.'</strong></h2>
				</div>
				<div>
					<p style="text-align: justify; padding-left: 5%; padding-right: 5%; color: #081e41">
					</p>
				</div>
				<div style="background: #000000c2; text-align: center; height: 50px; padding-top: 15  px; padding-bottom: 25px;">
					<h4><a style="color: #ffffffec !important; text-decoration-line: none" href="https://urbanhub.mx/panel">www.UrbanHub.mx</a></h4>
				</div>
		</div>
	</div>
	</div>
	';
	$res = $this->MiCorreo_model->buscar_mi_correo();

			$host = "mail.urbanhub.mx";
			$puerto = "465";
			$usuario = "info@urbanhub.mx";
			$clave = "coworking2019";
			
			/*
			$host = $res[0]['servidor_smtp'];
			$puerto = $res[0]['puerto'];
			$usuario = $res[0]['usuario'];
			$clave = $res[0]['clave'];
			*/
				$mail = new PHPMailer();
				$mail->SMTPOptions = array(
					'ssl' => array(
						'verify_peer' => false,
						'verify_peer_name' => false,
						'allow_self_signed' => true
					)
				);
				$mail->SMTPDebug = SMTP::DEBUG_SERVER;
				$mail->SMTPDebug = 0; //Alternative to above constant
						$mail->isSMTP();
						
						$mail->Host = $host; 
						$mail->Port = $puerto; 
						$mail->SMTPSecure = "ssl"; 
						$mail->SMTPAuth = true;
						$mail->Username = $usuario;
						$mail->Password = $clave;
						$mail->CharSet = 'UTF-8';
						$mail->From = $usuario;
						$mail->FromName = "UrbanHub Reinicio de Contraseña";
				$mail->Subject = "UrbanHub Web";
				$mail->msgHTML($body);
				$mail->Body = $body;
				$mail->AddAddress($destinatario, 'Sitio web');
				//$mail->AddAddress("contact@ag2.com.mx", "site ag2");
				$mail->AddBCC("sacrotzenil@gmail.com", 'UrbanHub');
				$exito = $mail->Send();
						$mail->ClearAddresses();
				if($exito){  echo "1"; 
					
					$operacion = $this->Acceso_model->reinicio_contraseña($pass);
				} else { echo "2"; 
				}
			
}
public function conteo(){
	$tipo_evento = $this->Entradas_model->conteo_e();
	print_r($tipo_evento);

	$tipo_noticia = $this->Entradas_model->conteo_n();
	print_r($tipo_noticia);

	
	$tipo_oculta = $this->Entradas_model->conteo_o();
	print_r($tipo_oculta);
}
public function consultar_estadisticas() {

		$operacion = $this->Estadistica_model->consultar();
		print_r($operacion);
}
public function actualizar_estadisticas($campo) {
	$operacion = $this->Estadistica_model->actualizar_data($campo);
	print_r($operacion);
}
public function cargar_entrada($id){
	$this->Entradas_model->cargar_entrada_panel($id);
}
public function inicio(){
	
	$te = $this->Entradas_model->conteo_e();
	
	$data['te'] = $te;

	$tn = $this->Entradas_model->conteo_n();
	$data['tn'] = $tn;
	
	$to = $this->Entradas_model->conteo_o();
	$data['to'] = $to;


	$this->load->view('panel/header');
	$this->load->view('panel/graficas', $data);
	$this->load->view('panel/footer');


}
public function cambio_de_correo(){
	$this->load->view('panel/header');
	$this->load->view('panel/cambio_de_correo');
	$this->load->view('panel/footer');
}
public function cambio_email(){
	$pass_v = $_POST['password_old'];
	$correo = $_POST['email'];
	
	$operacion = $this->Entradas_model->cambio_correo($pass_v,$correo);
	print_r($operacion);
}
public function nuevo_contenido(){
	$this->load->view('panel/header');
	$this->load->view('panel/nuevo_contenido');
	$this->load->view('panel/footer');
}
public function etiquetas(){
	$this->load->view('panel/header');
	$this->load->view('panel/etiquetas');
	$this->load->view('panel/footer');
}
public function lista_eventos(){
	$this->load->view('panel/header');
	$this->load->view('panel/lista_eventos');
	$this->load->view('panel/footer');
}
public function lista_noticias(){
	$this->load->view('panel/header');
	$this->load->view('panel/lista_noticias');
	$this->load->view('panel/footer');
}
public function cambiar_estado_entrada(){
	$id = $this->input->post("id");
	$accion = $this->input->post("accion");
	$this->Entradas_model->actualizar_estado_entrada($id, $accion); 
				print_r(1);
			
			
}
public function carrusel_inicial(){
	$this->load->view('panel/header');
	$this->load->view('panel/carrusel_inicial');
	$this->load->view('panel/footer');
}
public function galeria(){
	$this->load->view('panel/header');
	$this->load->view('panel/galeria');
	$this->load->view('panel/footer');
}
public function soporte(){
	$this->load->view('panel/header');
	$this->load->view('panel/soporte');
	$this->load->view('panel/footer');
}
public function cambio_contra(){
	$this->load->view('panel/header');
	$this->load->view('panel/cambio_contraseña');
	$this->load->view('panel/footer');
}
public function actualizar_galeria(){
	$uploaddir = 'assets/img/galeria/galerias/todas/';
	$namefile = $_FILES["filenames"]['name'];
	$uploadfile = $uploaddir . basename($namefile);
	if($_FILES["filenames"]['name'] != ""){
		if (move_uploaded_file($_FILES["filenames"]['tmp_name'], $uploadfile)) {
		echo "1";
	} else {
		echo "0";
	}
	}else{
		echo "error";
	}

}
public function actualizar_carrusel(){
	$uploaddir = 'assets/img/slider/img-com/';
	$namefile = $_FILES["filenames"]['name'];
	$uploadfile = $uploaddir . basename($namefile);
	if($_FILES["filenames"]['name'] != ""){
		if (move_uploaded_file($_FILES["filenames"]['tmp_name'], $uploadfile)) {
		echo "1";
	} else {
		echo "0";
	}
	}else{
		echo "error";
	}
}
public function envio_correo_1(){
	
	$nombre = $_POST['nombre'];
	$ciudad =$_POST['ciudad'];
	$email = $_POST['email'];
	$telefono =$_POST['telefono'];
	$Message = $_POST['mensaje'];


	$body = '

	<div style="background: #b7b7b7; padding-top: 15px; padding-bottom: 15px;
	margin: 0">
		<div style="width:50%;
						margin-left: auto;
					margin-right: auto;
				color: #081e41;
			
				background-size: container;
				-moz-background-size: container;
				-webkit-background-size: container;
				-o-background-size: container;
				overflow: hidden; 
				box-shadow: 0 0 40px rgba(0, 0, 0, 0.8);">
	
		<div style="background: #ffffffec; height: 90%;">
				<div>
					<img style="margin-left: auto; margin-right: auto; display: block; padding-top: 5%" src="http://www.siteag.ag2-group.com/urban/assets/img/complementos/logo1.png" width="200PX" alt="LOGO">
				</div>
				<div style="text-align: center">
					<h2>
						Gracias por Contactarnos</h2>
				</div>
				<div>
					<p style="text-align: justify; padding-left: 5%; padding-right: 5%; color: #081e41">
						
	Gracias por contactarnos pronto un miembro de nuestro equipo se pondrá en contacto con usted
					</p>
				</div>
	
				<div style="padding-left: 5%; color: #081e41">
					<h4 style="text-align: left; margin-left: 10%">Informacion de contacto:</h4>
					<p>Nombre: '.$nombre.'</p>
					<p>Ciudad: '.$ciudad.'</p>
					<p>Telefono: '.$telefono.'</p>
					<p>Correo: '.$email.'</p>
					<p>Mensaje: '.$Message.'</p>
				</div>
	
				<div style="background: #000000c2; text-align: center; height: 50px; padding-top: 15  px; padding-bottom: 25px;">
					<h4><a style="color: #ffffffec !important; text-decoration-line: none" href="#">www.UrbanHub.mx</a></h4>
				</div>
		</div>
	</div>
	</div>
	';



	if(($email == '') || ($Message == '')) {
		echo "0";
		}else{
			$res = $this->MiCorreo_model->buscar_mi_correo();
			$host = "mail.urbanhub.mx";
		$puerto = "465";
		$usuario = "info@urbanhub.mx";
		$clave = "coworking2019";
		
		/*
		$host = $res[0]['servidor_smtp'];
		$puerto = $res[0]['puerto'];
		$usuario = $res[0]['usuario'];
		$clave = $res[0]['clave'];
		*/
		$mail = new PHPMailer();
		$mail->SMTPOptions = array(
			'ssl' => array(
				'verify_peer' => false,
				'verify_peer_name' => false,
				'allow_self_signed' => true
			)
		);
		$mail->SMTPDebug = SMTP::DEBUG_SERVER;
		$mail->SMTPDebug = 0; //Alternative to above constant
				$mail->isSMTP();
				

				$mail->Host = $host; 
				$mail->Port = $puerto; 
				$mail->SMTPSecure = "ssl"; 
				$mail->SMTPAuth = true;
				$mail->Username = $usuario;
				$mail->Password = $clave;
				$mail->CharSet = 'UTF-8';
				$mail->From = $usuario;
						$mail->FromName = "UrbanHub-WebSite";
				$mail->Subject = "UrbanHub Web";
				
				$mail->msgHTML($body);
				$mail->Body = $body;
				$mail->AddAddress($email, $nombre);
				//$mail->AddAddress("contact@ag2.com.mx", "site ag2");
				$mail->AddBCC("sacrotzenil@gmail.com", 'UrbanHub');
				$mail->AddBCC($this->Entradas_model->correo_admin(), 'Sitio web');
				
				$exito = $mail->Send();
				$mail->ClearAddresses();
		if($exito){  echo "1"; } else { echo "2";  }
		}
}		
public function envio_correo_2(){

				
		$nombre = $_POST['nombre'];
		$email = $_POST['email'];
		$telefono =$_POST['telefono'];
		$Message = $_POST['mensaje'];

		$body = '

		<div style="background: #b7b7b7; padding-top: 15px; padding-bottom: 15px;
		margin: 0">
			<div style="width:50%;
						margin-left: auto;
						margin-right: auto;
					color: #081e41;
				
					background-size: container;
					-moz-background-size: container;
					-webkit-background-size: container;
					-o-background-size: container;
					overflow: hidden; 
					box-shadow: 0 0 40px rgba(0, 0, 0, 0.8);">

			<div style="background: #ffffffec; height: 90%;">
					<div>
						<img style="margin-left: auto; margin-right: auto; display: block; padding-top: 5%" src="http://www.siteag.ag2-group.com/urban/assets/img/complementos/logo1.png" width="200PX" alt="LOGO">
					</div>
					<div style="text-align: center">
						<h2>
							Gracias por Contactarnos</h2>
					</div>
					<div>
						<p style="text-align: justify; padding-left: 5%; padding-right: 5%; color: #081e41">
							
		Gracias por contactarnos pronto un miembro de nuestro equipo se pondrá en contacto con usted
						</p>
					</div>

					<div style="padding-left: 5%; color: #081e41">
						<h4 style="text-align: left; margin-left: 10%">Informacion de contacto:</h4>
						<p>Nombre: '.$nombre.'</p>
						<p>Telefono: '.$telefono.'</p>
						<p>Correo: '.$email.'</p>
						<p>Mensaje: '.$Message.'</p>
					</div>

					<div style="background: #000000c2; text-align: center; height: 50px; padding-top: 15  px; padding-bottom: 25px;">
						<h4><a style="color: #ffffffec !important; text-decoration-line: none" href="#">www.UrbanHub.mx</a></h4>
					</div>
			</div>
		</div>
		</div>
		';
		if(($email == '') || ($Message == '')) {
			echo "0";
			}else{
				$res = $this->MiCorreo_model->buscar_mi_correo();

				$host = "mail.urbanhub.mx";
				$puerto = "465";
				$usuario = "info@urbanhub.mx";
				$clave = "coworking2019";
				
				/*
				$host = $res[0]['servidor_smtp'];
				$puerto = $res[0]['puerto'];
				$usuario = $res[0]['usuario'];
				$clave = $res[0]['clave'];
				*/
			$mail = new PHPMailer();
			$mail->SMTPOptions = array(
				'ssl' => array(
					'verify_peer' => false,
					'verify_peer_name' => false,
					'allow_self_signed' => true
				)
			);
			$mail->SMTPDebug = SMTP::DEBUG_SERVER;
			$mail->SMTPDebug = 0; //Alternative to above constant
					$mail->isSMTP();
					
					$mail->Host = $host; 
					$mail->Port = $puerto; 
					$mail->SMTPSecure = "ssl"; 
					$mail->SMTPAuth = true;
					$mail->Username = $usuario;
					$mail->Password = $clave;
					$mail->CharSet = 'UTF-8';
					$mail->From = $usuario;
					$mail->FromName = "UrbanHub-WebSite";
					$mail->Subject = "UrbanHub Web";
					$mail->msgHTML($body);
					$mail->Body = $body;
					$mail->AddAddress($email, $nombre);
					//$mail->AddAddress("contact@ag2.com.mx", "site ag2");
					$mail->AddBCC("sacrotzenil@gmail.com", 'UrbanHub');
					$mail->AddBCC($this->Entradas_model->correo_admin(), 'Sitio web');
					$exito = $mail->Send();
					$mail->ClearAddresses();
			if($exito){  echo "1"; } else { echo "2";  }
			}
}
public function sendemail(){
	

	$res = $this->MiCorreo_model->buscar_mi_correo();

	$host = "mail.urbanhub.mx";
		$puerto = "465";
		$usuario = "info@urbanhub.mx";
		$clave = "coworking2019";
		$this->load->library('email');



		$config['protocol'] = 'smtp';

		$config['smtp_host'] = $host;

		$config['smtp_user'] = $usuario;

		$config['smtp_pass'] = $clave;

		$config['smtp_port'] = $puerto;

		$config['charset'] = 'utf-8';

		$config['mailtype'] = 'html';

		$config['wordwrap'] = TRUE;

		$config['newline']    = "\r\n";



		$this->email->initialize($config);

		
		$this->email->from($res[0]['usuario']);

		$this->email->to("sacrotzenil@gmail.com", "Frank");
		$this->email->subject("demo de email");

		$this->email->message("demo de email desde CI");
		/*return*/ $this->email->send();

		print_r("envio de correo");

	
}
public function gestor_imagen(){
	//print_r("llego");

	
	$this->load->view('panel/header');
	$this->load->view('panel/gestor_imagenes');
	$this->load->view('panel/footer');
}
public function gestor_texto(){
	//print_r("llego");
	$this->load->view('panel/header');
	$this->load->view('panel/Gestor_textos');
	$this->load->view('panel/footer');
}
public function Meta_etiquetas(){
	//print_r("llego");

	$consulta_meta_etiquetas = $this->Meta_etiquetas_model->listar_meta_etiquetas();
	$data['lista_meta_etiquetas'] = $consulta_meta_etiquetas;
	$this->load->view('panel/header');
	$this->load->view('panel/Meta_etiquetas', $data);
	$this->load->view('panel/footer');

}
public function m(){


	
	$body = "CUERPO DE CORREO";
	$res = $this->MiCorreo_model->buscar_mi_correo();

	$host = "mail.urbanhub.mx";
	$puerto = "465";
	$usuario = "info@urbanhub.mx";
	$clave = "coworking2019";
	
	/*
	$host = $res[0]['servidor_smtp'];
	$puerto = $res[0]['puerto'];
	$usuario = $res[0]['usuario'];
	$clave = $res[0]['clave'];
	*/


	$mail = new PHPMailer();
	$mail->SMTPOptions = array(
		'ssl' => array(
			'verify_peer' => false,
			'verify_peer_name' => false,
			'allow_self_signed' => true
		)
	);
	$mail->SMTPDebug = SMTP::DEBUG_SERVER;
	$mail->SMTPDebug = 4; //Alternative to above constant
			$mail->isSMTP();
			
			$mail->Host = $host; 
			//$mail->Host = 'mail.ag2.com.mx'; 
			$mail->Port = $puerto; 
			$mail->SMTPSecure = "ssl"; 
			$mail->SMTPAuth = true;
		//	$mail->Username = "contact@ag2.com.mx";
		//	$mail->Password = "fjVEs84-lYy=";
			$mail->Username = $usuario;
			$mail->Password = $clave;
			$mail->CharSet = 'UTF-8';
			$mail->From = $usuario;
			$mail->FromName = "UrbanHub-WebSite";
			$mail->Subject = "UrbanHub Web";
			$mail->msgHTML($body);
			$mail->Body = $body;
			$mail->AddAddress("frank_daniel_12@outlook.com", 'Sitio web');
			//$mail->AddAddress("contact@ag2.com.mx", "site ag2");
			$mail->AddBCC("sacrotzenil@gmail.com", 'UrbanHub');
			$mail->AddBCC($this->Entradas_model->correo_admin(), 'Sitio web');
			$exito = $mail->Send();
			$mail->ClearAddresses();
	if($exito){  echo "1"; } else { echo "2";  }

}

public function nuevo_banner(){
	$this->load->view('panel/header');
	$this->load->view('panel/nuevo_banner');
	$this->load->view('panel/footer');
}

public function lista_banner(){
	$this->load->view('panel/header');
	$this->load->view('panel/lista_banner');
	$this->load->view('panel/footer');
}

public function guardar_imagen_banner(){

	$nuevo_codigo = $this->Entradas_model->ultimo_id_entrada();

	// #GUARDAR IMAGEN
	$uploaddir = 'assets/img/img-banner/';
	if (!file_exists($carpeta)) {
		mkdir($uploaddir, 0777, true);
	}
		$total_fotos = $nuevo_codigo;
		$nombre =  $total_fotos;
		$namefile = $this->generarCodigo(20).".png";
		$uploadfile = $uploaddir . basename($namefile);
		if($_FILES["filenames"]['name'] != ""){
			if (move_uploaded_file($_FILES["filenames"]['tmp_name'], $uploadfile)) {
			echo $namefile;
		} else {
			echo "0";
		}
		}else{
			echo "error";
		}
}

public function guardar_imagen_svg(){

	$nuevo_codigo = $this->Entradas_model->ultimo_id_entrada();

	// #GUARDAR IMAGEN
	$uploaddir = 'assets/img/img-banner/';
	if (!file_exists($carpeta)) {
		mkdir($uploaddir, 0777, true);
	}
		$total_fotos = $nuevo_codigo;
		$nombre =  $total_fotos;
		$namefile = $this->generarCodigo(20).".svg";
		$uploadfile = $uploaddir . basename($namefile);
		if($_FILES["filenames"]['name'] != ""){
			if (move_uploaded_file($_FILES["filenames"]['tmp_name'], $uploadfile)) {
			echo $namefile;
		} else {
			echo "0";
		}
		}else{
			echo "error";
		}
}

public function crear_banner(){
	$operacion = $this->Banner_model->crear_banner($_REQUEST);
	print_r($operacion);
}

public function listar_banner(){
	$operacion = $this->Banner_model->list();
	print_r(json_encode($operacion));
}


public function listar_banner_ws(){
	$operacion = $this->Banner_model->lista();
	print_r($operacion);
}
public function cargar_banner($id){
		$operacion = $this->Banner_model->listar_un_banner($id);
		print_r(json_encode($operacion[0]));
}


		
public function actualizar_estado_banner(){
	$id = $this->input->post("id");
	$accion = $this->input->post("accion");
	$this->Banner_model->actualizar_estado_banner($id, $accion); 
	print_r(1);
}

public function actualizar_banner(){

	$data = array("titulo" => $_POST['titulo'],
				   "parrafo" => $_POST['parrafo'],
				   "imgfondo" => $_POST['imgfondo'],
					"imglogo" => $_POST['imglogo'],
					"transparencia" => $_POST['transparencia'],
					"colortexto" => $_POST['colortexto'],
					"visible" => $_POST['visible']);

	$id = $this->input->post("id");
	$this->Banner_model->actualizar_banner($id, $data); 
	print_r(1);
}

public function nuevo_beneficios(){
	$this->load->view('panel/header');
	$this->load->view('panel/nuevo_beneficio');
	$this->load->view('panel/footer');
}

public function crear_beneficio(){
	$operacion = $this->Banner_model->crear_beneficio($_REQUEST);
	print_r($operacion);
}


public function listar_b(){
	$operacion = $this->Banner_model->lista_b();
	print_r(json_encode($operacion));
}


public function lista_beneficios(){
	$this->load->view('panel/header');
	$this->load->view('panel/lista_beneficios');
	$this->load->view('panel/footer');
}


public function actualizar_estado_beneficio(){
	$id = $this->input->post("id");
	$accion = $this->input->post("accion");
	$this->Banner_model->actualizar_estado_beneficio($id, $accion); 
	print_r(1);
}

public function cargar_beneficio($id){
	$operacion = $this->Banner_model->listar_un_beneficio($id);
	print_r(json_encode($operacion[0]));
}

public function actualizar_beneficio(){

	$data = array("titulo" => $_POST['titulo'],
				   "parrafo" => $_POST['parrafo'],
					"imglogo" => $_POST['imglogo'],
					"url" => $_POST['url'],
					"visible" => $_POST['visible']);
	$id = $this->input->post("id");
	$this->Banner_model->actualizar_beneficio($id, $data); 
	print_r(1);
}

}

