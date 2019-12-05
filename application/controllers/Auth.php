<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller{

	public function __construct()
	{
		parent::__construct();
		//$this->load->database();
		$this->load->library('session');
		$this->load->model('Usuarios_model');
		$this->load->model('MiCorreo_model');
    	$this->load->library('form_validation');
   	    $this->load->helper('array_push_assoc');
	}

	public function index()
	{
		if ($this->session->userdata("login")) {
			redirect(base_url()."inicio");
		} else {
			//--
			/*$this->load->library('mongo_db', array('activate'=>'newdb'),'mongo_db');
		
			$res = $this->mongo_db->get('usuario');
			foreach ($res as $clave => $valor) {
				//Transformo la fila en un array
				$id = new MongoDB\BSON\ObjectId($valor["_id"]->{'$id'});
				$arreglo_datos_personales["datos_personales"] = $this->mongo_db->where(array('id_usuario' => $id))->get('datos_personales');
				
				$listado[] = array_push_assoc($valor,$arreglo_datos_personales);

			}
			die(json_encode($listado));*/
			//--
			$this->load->view('cpanel/header');
			$this->load->view('admin/login');
			$this->load->view('cpanel/footer');
		}
	}

	public function login()
	{
		//--Valido que haya capturado el token
		//var_dump(ERROR_TOKEN);
		if(ERROR_TOKEN!=""){
			$this->session->set_flashdata('error',ERROR_TOKEN);
 			//redirect(base_url());
		}
		//---
		$correo_usuario = $this->input->post('correo_usuario');
		$clave_usuario = $this->input->post('clave_usuario');
		$res = $this->Usuarios_model->login($correo_usuario, sha1($clave_usuario));
		//var_dump($res[0]["datos_personales"][0]["nombre_datos_personales"]);die('aaa');
		//var_dump($res->nombre_datos_personales . " " . $res->apellido_p_datos_personales . " " . $res->apellido_m_datos_personales);die('aqui');
		if (!$res) {
			$this->session->set_flashdata('error','!Los datos introducidos son incorrectos.!');
 			redirect(base_url()."admin");
		} else {
			if ($res[0]["status"] == true){
				//--
				//valido si el usuario tiene un rol activo
				//--
				$res_roles = $this->Usuarios_model->verificar_roles($res[0]["id_rol"]);
				if($res_roles){
					//--
					$data = array(
					'id_usuario' => $res[0]["_id"]->{'$id'},
					'correo_usuario' => $res[0]["correo_usuario"],
					'avatar_usuario' => $res[0]["avatar_usuario"],
					'id_rol' => $res[0]["id_rol"],
					'nombre' => $res[0]["datos_personales"][0]["nombre_datos_personales"] . " " . $res[0]["datos_personales"][0]["apellido_p_datos_personales"]. " " . $res[0]["datos_personales"][0]["apellido_m_datos_personales"] ,
					'login' => TRUE,
					'clave' => sha1($clave_usuario),
					'ip'=> $this->input->ip_address()
					);
					$this->session->set_userdata($data);
					$this->Usuarios_model->ultima_conexion($this->session->userdata('id_usuario'));
					redirect(base_url()."inicio");
					//--
				}else{
					//echo "Sin roles";
					$this->session->set_flashdata('error','!Lo sentimos, su rol no esta activo en el sistema.!');
					redirect(base_url()."admin");
				}
				
			} else if ($res[0]["status"] == false) {
				$this->session->set_flashdata('error','!Lo sentimos, pero ud. se encuentra bloqueado del sistema.!');
 				redirect(base_url()."admin");
			}
		}
	}

	public function recu_clave()
	{
		$datos['mensaje_result'] = $this->enviar_clave();
		$this->load->view('cpanel/header');
		$this->load->view('admin/recu_clave', $datos);
		$this->load->view('cpanel/footer');
	}

	private function enviar_clave(){
		$resultado['correo'] = "";
		$resultado['texto'] = "";
	    $resultado['tipo'] = "danger";
	    $resultado['mostrar'] = "none";
		if($this->input->method() === 'post'){
			$resultado['mostrar'] = "block";
			$resultado['correo'] = $this->input->post('correo_usuario');

			$this->form_validation->set_rules('correo_usuario','Correo Electrónico','required|valid_email');
			$this->form_validation->set_message('required', 'El campo %s es obligatorio');
			$this->form_validation->set_message('valid_email', 'El campo %s no es valido');

			if($this->form_validation->run() == true){

				$res = $this->Usuarios_model->listar_usuarios(null, $resultado['correo']);
				//var_dump($res);die('');
				if(count($res) > 0){
					$res = $res[0];

					////Generar contraseña aleatoria
						$largo = 10;
						$cadena_base =  'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
						$cadena_base .= '0123456789' ;
						//$cadena_base .= '!@#%^&*()_,./<>?;:[]{}\|=+';
						 
						$password = '';
						$limite = strlen($cadena_base) - 1;
						 
						for ($i=0; $i < $largo; $i++){
							$password .= $cadena_base[rand(0, $limite)];
						}
					////////////////////////////////

						$usuarioArray['clave_usuario'] = sha1($password);
				        $idArray['id_usuario'] = $res["id_usuario"];
						
						$mensaje = strtoupper($res["nombre_datos_personales"]." ".$res["apellido_p_datos_personales"])." tus datos de acceso son los siguientes: <br><br>Correo Electrónico: ".$res["correo_usuario"]."<br>Contraseña: ".$password;

						if($this->MiCorreo_model->enviar_correo("Recuperar Contraseña", $mensaje, $res["correo_usuario"], strtoupper($res["nombre_datos_personales"]." ".$res["apellido_p_datos_personales"]))){
							$resultado['texto'] = "Datos enviados al correo electrónico.";
					    	$resultado['tipo'] = "success";
					    	$resultado['correo'] = "";
					    	$this->Usuarios_model->actualizar_usuario($usuarioArray, null, null, $idArray, "", false);
						}
						else{
							$resultado['texto'] = "Fallo al enviar datos.";
						}
					
				}
				else{
					$resultado['texto'] = "Correo Electrónico no encontrado.";
				}
			}
			else{
		        // enviar los errores
		    	$resultado['texto'] = validation_errors();
		    }
		}
		return $resultado;
	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect(base_url()."admin");
	}

}