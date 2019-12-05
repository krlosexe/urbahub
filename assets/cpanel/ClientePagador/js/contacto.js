$(document).ready(function(){
	telefonoInput('.telefono');
	listar();
	registrar_contacto();
	actualizar_contacto();
	

});

/* ------------------------------------------------------------------------------- */
	/* 
		Funcion para cargar los datos de la base de datos en la tabla.
	*/
	function listar(cuadro){
		$('#tabla tbody').off('click');
		cuadros(cuadro, "#cuadro1");
		var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
		var id_cliente = document.getElementById('id_cliente').value;
		//console.log(id_cliente);
		var table=$("#tabla").DataTable({
			"destroy":true,
			"stateSave": true,
			"serverSide":false,
			"ajax":{
				"method":"POST",
				"url": url + "ClientePagador/listado_contacto/"+id_cliente,
				"dataSrc":""
			},
			"columns":[
				{"data": "id_contacto_cliente",
					render : function(data, type, row) {
						return "<input type='checkbox' class='checkitem chk-col-blue' id='item"+data+"' value='"+data+"'><label for='item"+data+"'></label>"
					}
				},
				{"data": null,
					render : function(data, type, row) {
						var botones = "";
						if(consultar == 0 )
							botones += "<span class='consultar btn btn-xs btn-info waves-effect' data-toggle='tooltip' title='Consultar'><i class='fa fa-eye' style='margin-bottom:5px'></i></span> ";
						if(actualizar == 0 && editable == 1)
							botones += "<span class='editar btn btn-xs btn-primary waves-effect' data-toggle='tooltip' title='Editar'><i class='fa fa-pencil-square-o' style='margin-bottom:5px'></i></span> ";
						if(data.status == true && actualizar == 0  && editable == 1)
							botones += "<span class='desactivar btn btn-xs btn-warning waves-effect' data-toggle='tooltip' title='Desactivar'><i class='fa fa-unlock' style='margin-bottom:5px'></i></span> ";
						else if(data.status == false && actualizar == 0  && editable == 1)
							botones += "<span class='activar btn btn-xs btn-warning waves-effect' data-toggle='tooltip' title='Activar'><i class='fa fa-lock' style='margin-bottom:5px'></i></span> ";
						if(borrar == 0  && editable == 1)
		              		botones += "<span class='eliminar btn btn-xs btn-danger waves-effect' data-toggle='tooltip' title='Eliminar'><i class='fa fa-trash-o' style='margin-bottom:5px'></i></span>";
		          		return botones;
		          	}
				},
				{"data":"nombre_datos_personales"},
				{"data":"correo_contacto"},
				{"data":"telefono_principal_contacto"},
				{"data":"fec_regins",
					render : function(data, type, row) {
						var valor = data.date;
						fecha = valor.split(" ");
						return cambiarFormatoFecha(fecha[0]);
	          		}
				},
				{"data":"correo_usuario"},
				
			],
			"language": idioma_espanol,
			"dom": 'Bfrtip',
			"responsive": true,
			"buttons":[
				'copy', 'csv', 'excel', 'pdf', 'print'
			]
		});
		ver("#tabla tbody", table);
		editar("#tabla tbody", table);
		eliminar("#tabla tbody", table);
		desactivar("#tabla tbody", table);
		activar("#tabla tbody", table);
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */

	/* 
		Funcion que muestra el cuadro2 para mostrar el formulario de registrar.
	*/
	function nuevoRegistro(cuadroOcultar, cuadroMostrar){
		cuadros("#cuadro1", "#cuadro2");
		$("#form_contacto_registrar")[0].reset();
		$("#nombre_contacto").focus();
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que realiza el envio del formulario de registro
	*/
	function registrar_contacto(){
		enviarFormulario("#form_contacto_registrar", 'ClientePagador/guardarContacto', '#cuadro2');
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/* 
		Funcion que muestra el cuadro3 para la consulta
	*/
	function ver(tbody, table){
		$(tbody).on("click", "span.consultar", function(){
			var data = table.row( $(this).parents("tr") ).data();
			document.getElementById('nombre_contacto_m').value=data.nombre_datos_personales;
			document.getElementById('tlf_ppalContacto_m').value = data.telefono_principal_contacto;
			document.getElementById('tfl_movilContacto_m').value = data.telefono_movil_contacto;
			document.getElementById('coreo_contactp_opc_m').value = data.correo_opcional_contacto;
			document.getElementById('correo_contacto_m').value=data.correo_contacto;
			document.getElementById('tlf_trabajo_m').value=data.telefono_trabajo_contacto;
			document.getElementById('tlf_fax_m').value=data.telefono_fax_contacto;
			document.getElementById('tlf_casa_m').value=data.telefono_casa_contacto;
			cuadros('#cuadro1', '#cuadro3');
		});
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/* 
		Funcion que muestra el cuadro4 para editar
	*/
	function editar(tbody, table){
		$("#form_contacto_editar")[0].reset();
		$(tbody).on("click", "span.editar", function(){
			var data = table.row( $(this).parents("tr") ).data();
			document.getElementById('id_contacto_actualizar').value=data.id_contacto;
			document.getElementById('id_datos_personales_e').value=data.id_datos_personales;
			document.getElementById('id_contacto_cliente_e').value=data.id_contacto_cliente;			
			document.getElementById('nombre_contacto_e').value=data.nombre_datos_personales;
			document.getElementById('tlf_ppalContacto_e').value = data.telefono_principal_contacto;
			document.getElementById('tfl_movilContacto_e').value = data.telefono_movil_contacto;
			document.getElementById('coreo_contactp_opc_e').value = data.correo_opcional_contacto;
			document.getElementById('correo_contacto_e').value=data.correo_contacto;
			document.getElementById('tlf_trabajo_e').value=data.telefono_trabajo_contacto;
			document.getElementById('tlf_fax_e').value=data.telefono_fax_contacto;
			document.getElementById('tlf_casa_e').value=data.telefono_casa_contacto;
			cuadros('#cuadro1', '#cuadro4');
			
		});
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que realiza el envio del formulario de registro
	*/
	function actualizar_contacto(){
		enviarFormulario("#form_contacto_editar", 'ClientePagador/actualizar_contacto', '#cuadro4');
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que capta y envia los datos a eliminar
	*/
	function eliminar(tbody, table){
		$(tbody).on("click", "span.eliminar", function(){
            var data=table.row($(this).parents("tr")).data();
            eliminarConfirmacion('ClientePagador/eliminar_contacto', data.id_contacto_cliente, "¿Esta seguro de eliminar el registro?");
        });
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que capta y envia los datos a desactivar
	*/
	function desactivar(tbody, table){
		$(tbody).on("click", "span.desactivar", function(){
            var data=table.row($(this).parents("tr")).data();
            statusConfirmacion('ClientePagador/statuscontacto', data.id_contacto_cliente, 2, "¿Esta seguro de desactivar el registro?", 'desactivar');
        });
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que capta y envia los datos a desactivar
	*/
	function activar(tbody, table){
		$(tbody).on("click", "span.activar", function(){
            var data=table.row($(this).parents("tr")).data();
            statusConfirmacion('ClientePagador/statuscontacto', data.id_contacto_cliente, 1, "¿Esta seguro de activar el registro?", 'activar');
        });
	}

