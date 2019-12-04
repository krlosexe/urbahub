<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Blog extends CI_Controller {


	function __construct()
	{
	parent::__construct();
//	$this->load->database();
	$this->load->model('Entradas_model');
	$this->load->model('Bl_imagenes_model');
	$this->load->model('Bl_textos_model');
	$this->load->model('Meta_etiquetas_model');
	
	  }

		public function index(){

//	primera zona
$consulta_meta_data = $this->Meta_etiquetas_model->meta_etiquetas_web(5);
$data_meta = $consulta_meta_data;
$data["meta_data"] = $data_meta;
$this->load->view('portal/zona1.php', $data);
//	primera zona



			$this->load->view('portal/zona_noticias.php');
			$consulta_bl_imagenes = $this->Bl_imagenes_model->buscar_imagenes(1);
		$data_imagenes = json_decode($consulta_bl_imagenes);

		$data["imagenes"] = $data_imagenes;

// ultima zona
		$consulta_bl_imagenes = $this->Bl_imagenes_model->buscar_imagenes_web("1");
		$data_imagenes = $consulta_bl_imagenes;
		$data["imagenes"] = $data_imagenes;
		$consulta_texto = $this->Bl_textos_model->buscar_texto("7");;
		$data_texto = json_decode($consulta_texto);
		$data['textos'] = $data_texto;
		$this->load->view('portal/zona2.php',$data);
// ultima zona



			/*
		$this->load->view('portal/menu.php');
		$this->load->view('portal/blog');
		$this->load->view('portal/info1');
				$this->load->view('portal/footer.php');
				

*/

	}


	
	

	public function articulo($id)
	{
		


		$data_art =	$this->Entradas_model->cargar_entrada($id);
	
	//print_r($data_art[0]['etiquetas_id']);die();
			$data['nvistas'] =	$data_art[0]['visitas']+ 1;
			$data['titulo'] =	$data_art[0]['titulo'];
			$data['autor'] =	$data_art[0]['autor'];
			$data['fecha'] =	$data_art[0]['fecha'];
			$data['parrafos'] =	$data_art[0]['contenido'];
			$data['imagen'] =	$data_art[0]['imagen'];
			$data['code_face'] = $data_art[0]['codigo_facebook'];
			$data['id_art'] = json_decode(json_encode($data_art[0]['_id']), True)['$id'];
			$data['tc'] =	$data_art[0]['tipo'];

			if (isset($data_art[1]))
			{
				$data['etiquetas'] = $data_art[1];
				foreach($data_art[1] as $data_etiqueta){
					$id_etiqueta = $data_etiqueta;
					$id_obj = json_decode(json_encode($id_etiqueta['_id']), True)['$id'];
					// traer articulos relacionados por etiquetas
					
				$data_rela = $this->Entradas_model->entradas_relacionadas($id_obj, $id);
				}
				$data['relacionados'] = $data_rela;

		//	print_r($data['relacionados']);die();
				
			}





			$this->load->view('portal/zona1_blog.php', $data);
			$this->load->view('portal/zona_noticia_a.php', $data);
			//$this->load->view('portal/zona2_blog.php');
	

			/*
			$this->load->view('portal/menu-blog.php', $data);
			$this->load->view('portal/blog-articulo',$data);
			$this->load->view('portal/info1-blog');
			$this->load->view('portal/footer-blog.php');
			$n =  $data_art[0][0]->visitas + 1;
			$this->Entradas_model->actualizar_visitas_entrada($id, $n);*/



		
	}

	public function guardar_imagen(){
		$nombre_base = "urbanhubblog";
		$nuevo_codigo = $this->Entradas_model->ultimo_id_entrada();

		// #GUARDAR IMAGEN


			$uploaddir = 'assets/img/img-blog/';
			$total_fotos = $nuevo_codigo;
			$nombre =  $total_fotos;
			$namefile = "00".$nombre.".png";
			$entrada = "00".$nombre;
			$uploadfile = $uploaddir . basename($namefile);
			if($_FILES["filenames"]['name'] != ""){
				if (move_uploaded_file($_FILES["filenames"]['tmp_name'], $uploadfile)) {
				echo $entrada;
			} else {
				echo "0";
			}
			}else{
				echo "error";
			}
	}


	public function crear_entrada()
	{

		$nombre_base = "urbanhubblog";
		$nuevo_codigo = $this->Entradas_model->ultimo_id_entrada();



		

		$titulo = $_POST["titulo"];
		$fecha = $_POST["fecha"];
		$autor = $_POST["autor"];
		$descripcion = $_POST["descripcion"];
		$contenido = $_POST["contenido"];
		
		if(isset($_POST["lista_etiquetas"])){
			$etiquetas = $_POST["lista_etiquetas"];
		}else{
			$etiquetas = "";
		}

		$etiquetas = $_POST["lista_etiquetas"];
		$tipo_contenido = $_POST["tipo_contenido"];
		$code_facebook = $nombre_base.$nuevo_codigo;
					
		



		$datos = array(
			'estado_visible' => 1,

			'titulo' => $titulo,

			'autor' => $autor,

			'imagen' => "00".$nuevo_codigo.".png",

			'contenido' => $contenido,

			'codigo_facebook' => $code_facebook,

			'visitas' => 0,

			'fecha' => $fecha,

			'descripcion' => $descripcion,

			'tipo' => $tipo_contenido
			);


		$operacion = $this->Entradas_model->registrar_entrada($datos,$etiquetas);
		print_r($operacion);
	}


	public function actualizar_entrada(){

		$titulo = $_POST["titulo_editar"];

		$tipo_contenido = $_POST["tipo_editar"];

		$fecha = $_POST["fecha_editar"];

		$descripcion = $_POST["descripcion_editar"];

		$autor = $_POST["autor_editar"];

		$contenido = $_POST["contenido_editar"];

		$id = $_POST["entrada"];


		if(isset($_POST["lista_etiquetas"])){
			$etiquetas = $_POST["lista_etiquetas"];
		}else{
			$etiquetas = "";
		}
		
		$datos = array(
			'titulo' => $titulo,

			'autor' => $autor,

			'contenido' => $contenido,

			'fecha' => $fecha,

			'descripcion' => $descripcion,

			'tipo' => $tipo_contenido
			);


			
		$operacion = $this->Entradas_model->actualizar_entrada($id,$datos, $etiquetas);
		print_r($operacion);
	}

	public function actualizar_imagen(){
		$uploaddir = 'assets/img/img-blog/';
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


		public function listar_noticias(){
		$operacion = $this->Entradas_model->listar_noticias();
		return $operacion;
		}

		
		public function listar_eventos(){
			$operacion = $this->Entradas_model->listar_eventos();
			return $operacion;
			}

		




				public function get(){
				$info = $this->Entradas_model->getEntradas();
				print_r($info);
				}







				public function buscar_evento(){

					$etiqueta = $_POST["buscador"];
					
					$operacion = $this->Entradas_model->buscar_evento($etiqueta);
					print_r($operacion);
				}

				

				public function buscar_noticia(){

					$etiqueta = $_POST["buscador"];
					
					$operacion = $this->Entradas_model->buscar_noticia($etiqueta);
					print_r($operacion);
				}

				public function relacionados($id_etiqueta){
					$operacion = $this->Entradas_model->entradas_relacionadas($id_etiqueta);
					print_r($operacion);
				}

				

				
				public function buscar_titulo(){

					$titulo = $_POST["buscador"];

					if($titulo == ""){
						print_r(0);
						die();
					}
					
					$operacion = $this->Entradas_model->buscar_titulo($titulo);
					print_r($operacion);
				}
			
				public function listar_entradas(){
					$operacion = $this->Entradas_model->todas_entradas();
					print_r($operacion);
				}

				public function articulos_relacionados($etiqueta){

					$data['etiqueta'] = $etiqueta;

					$operacion = $this->Entradas_model->entradas_relacionadas_a($etiqueta);
				//	print_r($operacion);die();
					$data['resultado'] = $operacion;
					$this->load->view('portal/zona1_blog.php');
					$this->load->view('portal/zona_relacionados.php', $data);
					$this->load->view('portal/zona2_blog.php');

			
				}

}
