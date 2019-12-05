$(document).ready(function(){
	listar();
	registrar_lista_vista();
	actualizar_lista_vista();
});

/* ------------------------------------------------------------------------------- */
	/* 
		Funcion para cargar los datos de la base de datos en la tabla.
	*/
	function listar(cuadro){
		$('#tabla tbody').off('click');
		cuadros(cuadro, "#cuadro1");
		var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
		var table=$("#tabla").DataTable({
			"destroy":true,
			"stateSave": true,
			"serverSide":false,
			"ajax":{
				"method":"POST",
				"url":url+"MisCuentas/listado_bancos",
				"dataSrc":""
			},
			"columns":[

				{"data": "id_lista_vista",
					render : function(data, type, row) {
						return "<input type='checkbox' class='checkitem chk-col-blue' id='item"+data+"' value='"+data+"'><label for='item"+data+"'></label>"
					}
				},
				{"data": null,
					render : function(data, type, row) {
						var botones = "";
						if(consultar == 0)
							botones += "<span class='consultar btn btn-xs btn-info waves-effect' data-toggle='tooltip' title='Consultar'><i class='fa fa-eye' style='margin-bottom:5px'></i></span> ";
						if(actualizar == 0)
							botones += "<span class='editar btn btn-xs btn-primary waves-effect' data-toggle='tooltip' title='Editar'><i class='fa fa-pencil-square-o' style='margin-bottom:5px'></i></span> ";
						if(data.status == true && actualizar == 0)
							botones += "<span class='desactivar btn btn-xs btn-warning waves-effect' data-toggle='tooltip' title='Desactivar'><i class='fa fa-unlock' style='margin-bottom:5px'></i></span> ";
						else if(data.status == false && actualizar == 0)
							botones+="<span class='activar btn btn-xs btn-warning waves-effect' data-toggle='tooltip' title='Activar'><i class='fa fa-lock' style='margin-bottom:5px'></i></span> ";
						if(borrar == 0)
							botones += "<span class='eliminar btn btn-xs btn-danger waves-effect' data-toggle='tooltip' title='Eliminar'><i class='fa fa-trash-o' style='margin-bottom:5px'></i></span>";
		              	return botones;
		          	}
				},
				{"data":"clabe_cuenta"},
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
	function nuevoListaVista(cuadroOcultar, cuadroMostrar){
		$("#alertas").css("display", "none");
		cuadros("#cuadro1", "#cuadro2");
		$("#form_lista_vista_registrar")[0].reset();
		$("#nombre_modulo_vista_registrar").focus();
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que realiza el envio del formulario de registro
	*/
	function registrar_lista_vista(){
		enviarFormulario("#form_lista_vista_registrar", 'MisCuentas/store', '#cuadro2');
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/* 
		Funcion que muestra el cuadro3 para la consulta del banco.
	*/
	function ver(tbody, table){
		$(tbody).on("click", "span.consultar", function(){
			$("#alertas").css("display", "none");
			var data = table.row( $(this).parents("tr") ).data();

			$("#clabe_mostrar").val(data.clabe_cuenta)
			$("#numero_cuenta_mostrar").val(data.numero_cuenta)
			$("#tipo_cuenta_mostrar").val(data.tipo_cuenta)
			$("#banco_mostrar").val(data.id_banco)
			$("#swift_mostrar").val(data.swift_cuenta)
			$("#codigo_plaza_mostrar").val(data.id_plaza)
			$("#sucursal_mostrar").val(data.sucursal_cuenta)

			cuadros('#cuadro1', '#cuadro3');
		});
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/* 
		Funcion que muestra el cuadro4 para editar el banco.
	*/
	function editar(tbody, table){
		$("#form_cuenta_clientePa_editar")[0].reset();
		$(tbody).on("click", "span.editar", function(){
			$("#alertas").css("display", "none");
			var data = table.row( $(this).parents("tr") ).data();
			
			$("#clabe_editar").val(data.clabe_cuenta)
			$("#numero_cuenta_editar").val(data.numero_cuenta)
			$("#tipo_cuenta_editar").val(data.tipo_cuenta)
			$("#banco_editar").val(data.id_banco)
			$("#swift_editar").val(data.swift_cuenta)
			$("#codigo_plaza_editar").val(data.id_plaza)
			$("#sucursal_editar").val(data.sucursal_cuenta)

			$("#id_cuenta_actualizar").val(data._id.$id)
			cuadros('#cuadro1', '#cuadro4');
		});
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que capta y envia los datos a eliminar
	*/
	function eliminar(tbody, table){
		$(tbody).on("click", "span.eliminar", function(){
            var data = table.row($(this).parents("tr")).data();
            eliminarConfirmacion('MisCuentas/delete', data._id.$id, "¿Esta seguro de eliminar el registro?");
        });
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que realiza el envio del formulario de registro
	*/
	function actualizar_lista_vista(){
		enviarFormulario("#form_cuenta_clientePa_editar", 'MisCuentas/update', '#cuadro4');
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que capta y envia los datos a desactivar
	*/
	function desactivar(tbody, table){
		$(tbody).on("click", "span.desactivar", function(){
            var data=table.row($(this).parents("tr")).data();
            statusConfirmacion('MisCuentas/statusCuenta', data._id.$id, 2, "¿Esta seguro de desactivar el registro?", 'desactivar');
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
            statusConfirmacion('MisCuentas/statusCuenta', data._id.$id, 1, "¿Esta seguro de activar el registro?", 'activar');
        });
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que hace un count de las lista vista por modulos registrados y el resultado se 
		despliega en un select para la seleccion de la posicion de la lista vista.
	*/
	function contador_listaVista(value, select, selected){
		eliminarOptions("posicion_lista_vista_registrar");
		eliminarOptions("posicion_lista_vista_actualizar");
		if (value != "") {
			$.ajax({
		        url:document.getElementById('ruta').value + 'ListaVista/contador_listaVista',
		        type:'POST',
		        dataType:'JSON',
		        data:{'id': value},
		        error: function () {
                    contador_listaVista(value, select);
                },
		        success: function (respuesta) {
		            var contador = Object.keys(respuesta).length;
		            //alert(contador);
		            if (select == 'registrar' || (selected == 0 && value != document.getElementById('id_modulo_vista_hidden').value))
		            	contador++;
		            for (var i = 1; i <= contador; i++)
		            	agregarOptions("#posicion_lista_vista_" + select, i, i);
		            if (select == 'actualizar')
		            	$("#posicion_lista_vista_actualizar option[value='"+selected+"']").attr("selected","selected");
		            if (value == document.getElementById('id_modulo_vista_hidden').value)
		            	$("#posicion_lista_vista_actualizar option[value='"+document.getElementById('posicion_lista_vista_hidden').value+"']").attr("selected","selected");
		        }
		    });
		} else {
			warning('¡Debe seleccionar un modulo!');
		}
	}
/* ------------------------------------------------------------------------------- */

