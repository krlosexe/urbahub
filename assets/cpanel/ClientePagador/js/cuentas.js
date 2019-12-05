$(document).ready(function(){
	elegirFecha('.fecha');
	listar();
	registrar_cuentaCliente();
	actualizar_cuentaCliente();
	busqueda = false;
	

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
				"url": url + "ClientePagador/listado_cuentasClientePagador/"+id_cliente,
				"dataSrc":""
			},
			"columns":[
				{"data": "id_cuenta_cliente",
					render : function(data, type, row) {
						return "<input type='checkbox' class='checkitem chk-col-blue' id='item"+data+"' value='"+data+"'><label for='item"+data+"'></label>"
					}
				},
				{"data": null,
					render : function(data, type, row) {
						var botones = "";
						if(consultar == 0)
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
				{"data":"clabe_cuenta"},
				{"data":"nombre_lista_valor"},
				{"data":"nombre_banco"},
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
		$("#form_cuenta_clientePa_registrar")[0].reset();
		$("#clabe_registrar").focus();
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que realiza el envio del formulario de registro
	*/
	function registrar_cuentaCliente(){
		enviarFormulario("#form_cuenta_clientePa_registrar", 'ClientePagador/registrar_cuentaCliente', '#cuadro2');
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/* 
		Funcion que muestra el cuadro3 para la consulta
	*/
	function ver(tbody, table){
		$(tbody).on("click", "span.consultar", function(){
			var data = table.row( $(this).parents("tr") ).data();
			document.getElementById('clabe_mostrar').value = data.clabe_cuenta;
			document.getElementById('numero_cuenta_mostrar').value = data.numero_cuenta;
			document.getElementById('banco_mostrar').value = data.nombre_banco;
			document.getElementById('tipo_cuenta_mostrar').value = data.nombre_lista_valor;
			document.getElementById('swift_mostrar').value = data.swift_cuenta;
			document.getElementById('codigo_plaza_mostrar').value = data.nombre_plaza;
			document.getElementById('sucursal_mostrar').value = data.sucursal_cuenta;
			cuadros('#cuadro1', '#cuadro3');
		});
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/* 
		Funcion que muestra el cuadro4 para editar
	*/
	function editar(tbody, table){
		$("#form_cuenta_clientePa_editar")[0].reset();
		$(tbody).on("click", "span.editar", function(){
			var data = table.row( $(this).parents("tr") ).data();
			document.getElementById('id_cuenta_actualizar').value = data.id_cuenta_cliente;
			document.getElementById('clabe_editar').value = data.clabe_cuenta;
			document.getElementById('numero_cuenta_editar').value = data.numero_cuenta;
			$("#tipo_cuenta_editar option[value='" + data.tipo_cuenta + "']").prop("selected",true);
			$("#banco_editar option[value='" + data.id_banco + "']").prop("selected",true);
			document.getElementById('swift_editar').value = data.swift_cuenta;
			$("#codigo_plaza_editar option[value='" + data.id_plaza + "']").prop("selected",true);
			document.getElementById('sucursal_editar').value = data.sucursal_cuenta;
			cuadros('#cuadro1', '#cuadro4');
			
		});
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que realiza el envio del formulario de registro
	*/
	function actualizar_cuentaCliente(){
		enviarFormulario("#form_cuenta_clientePa_editar", 'ClientePagador/actualizar_cuentaCliente', '#cuadro4');
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que capta y envia los datos a eliminar
	*/
	function eliminar(tbody, table){
		$(tbody).on("click", "span.eliminar", function(){
            var data=table.row($(this).parents("tr")).data();
            eliminarConfirmacion('ClientePagador/eliminar_cuentaCliente', data.id_cuenta_cliente, "¿Esta seguro de eliminar el registro?");
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
            statusConfirmacion('ClientePagador/statusCuenta', data.id_cuenta_cliente, 2, "¿Esta seguro de desactivar el registro?", 'desactivar');
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
            statusConfirmacion('ClientePagador/statusCuenta', data.id_cuenta_cliente, 1, "¿Esta seguro de activar el registro?", 'activar');
        });
	}
/* ------------------------------------------------------------------------------- */

