<?php 
	/**
	* COntrol de Permisos
	*/
	class Menu_rol_operaciones 
	{
		private $CI;
		function __construct()
		{
			$this-> CI = & get_instance();
			$this->CI->load->model('Menu_model');
		}

		public function control($modulos, $vistas)
		{
		    foreach ($modulos as $modulo) {
		    	foreach ($vistas as $vista) {
		   	   		/*if($modulo->id_modulo_vista == $vista->id_modulo_vista){
		   	   			$data["modulo_user"][] = $modulo->id_modulo_vista;
		   	   		}*/
		   	   		
		   	   		if($modulo["_id"]->{'$id'} == $vista->id_modulo_vista){
		   	   			$data["modulo_user"][] = $vista->id_modulo_vista;
		   	   		}
		   		}
		    }
		    if(isset($data)){
		    	//---------------------------------------------------------------------------
		    	$ids = array_unique($data['modulo_user']);
			    foreach ($ids as $value) {
			    	$data['modulos_enconctrados'][] = $this->CI->Menu_model->modulosbyid($value);
			    } 

			    $oneDim = array();
				foreach($data['modulos_enconctrados'] as $i) {
				  $oneDim[] = $i[0];
				}
				return $data['modulos_vistas'] = (object)$oneDim;
		    	//---------------------------------------------------------------------------
		    }else{
		    	return $data['modulos_vistas'] = "";
		    }
		    
		}
	}
 ?>
