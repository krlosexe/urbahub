<?php 
	/**
	* COntrol de Permisos
	*/
	class Generar_token 
	{
		private $CI;
		function __construct()
		{


			$this->invocacion();
		}

		public function invocacion()
		{			
			$this-> CI = & get_instance();
			$this->CI->load->helper('rest_token');
			
		    $result = rest_token($this->CI->input->ip_address());

		    if(!$result)
		    	//echo "Contacte al administrador, error en autenticación de TOKEN";
		    	define("ERROR_TOKEN","Contacte al administrador, error en autenticación de TOKEN");
		   	else{
		   			if(!$result->header->result){
		   				//echo $result->header->error;
		   				define("ERROR_TOKEN",$result->header->error);
		   			}else{
		   				define("ERROR_TOKEN","");
			   			define("TOKEN",$result->data->codigo_historial_token);
		   			}
		   			//var_dump($result->header->cod_error);
		   		    //echo "ESTE ES EL TOKEN:".TOKEN." esta es la ip:".$result->data->ip_historial_token;exit();
		   	}
		   	
		}
	}
 ?>
