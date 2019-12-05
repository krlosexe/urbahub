$(document).ready(function(){
	listar();
	registrar_esquema_comision();
	actualizar_esquema_comision();
	porcentajeInput('.porcentaje');
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
				"url": url + "EsquemaComision/listado_esquema_comision",
				"dataSrc":""
			},
			"columns":[
				{"data": "id_esquema_comision",
					render : function(data, type, row) {
						return "<input type='checkbox' class='checkitem chk-col-blue' id='item"+data+"' value='"+data+"'><label for='item"+data+"'></label>"
					}
				},
				{"data":"idVendedor"},
				{"data":"tipoVendedor"},
				{"data":"num_ventas_mes"},
				{"data":"tipoPlazo"},
				{"data":"porctj_comision",
					render : function(data, type, row) {
						return data.replace('.',',')  + " %";
	          		}
				},
				{"data":"fec_regins",
					render : function(data, type, row) {
						return cambiarFormatoFecha(data);
	          		}
				},
				{"data":"correo_usuario"},
				{"data": null,
					render : function(data, type, row) {
						var botones = "";
						if(consultar == 0)
							botones += "<span class='consultar btn btn-xs btn-info waves-effect' data-toggle='tooltip' title='Consultar'><i class='fa fa-eye' style='margin-bottom:5px'></i></span> ";
						if(actualizar == 0)
							botones += "<span class='editar btn btn-xs btn-primary waves-effect' data-toggle='tooltip' title='Editar'><i class='fa fa-pencil-square-o' style='margin-bottom:5px'></i></span> ";
						if(data.status == 1 && actualizar == 0)
							botones += "<span class='desactivar btn btn-xs btn-warning waves-effect' data-toggle='tooltip' title='Desactivar'><i class='fa fa-unlock' style='margin-bottom:5px'></i></span> ";
						else if(data.status == 2 && actualizar == 0)
							botones += "<span class='activar btn btn-xs btn-warning waves-effect' data-toggle='tooltip' title='Activar'><i class='fa fa-lock' style='margin-bottom:5px'></i></span> ";
						if(borrar == 0)
		              		botones += "<span class='eliminar btn btn-xs btn-danger waves-effect' data-toggle='tooltip' title='Eliminar'><i class='fa fa-trash-o' style='margin-bottom:5px'></i></span>";
		          		return botones;
		          	}
				}
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
	function nuevoEsquemaComision(cuadroOcultar, cuadroMostrar){
		cuadros("#cuadro1", "#cuadro2");
		limpiarFormularioRegistrar("#form_esquema_comision_registrar");
		$("#id_vendedor_registrar").focus();
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/* 
		Funcion para limpiar el formulario de registrar.
	*/
	function limpiarFormularioRegistrar(form){
		$(form)[0].reset();
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que realiza el envio del formulario de registro
	*/
	function registrar_esquema_comision(){
		enviarFormulario("#form_esquema_comision_registrar", 'EsquemaComision/registrar_esquema_comision', '#cuadro2');
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/* 
		Funcion que muestra el cuadro3 para la consulta del banco.
	*/
	function ver(tbody, table){
		$(tbody).on("click", "span.consultar", function(){
			var data = table.row( $(this).parents("tr") ).data();
			$("#id_vendedor_consultar option[value='" + data.id_vendedor + "']").attr("selected","selected");
			$("#tipo_vendedor_consultar option[value='" + data.tipo_vendedor + "']").attr("selected","selected");
			$("#tipo_plazo_consultar option[value='" + data.tipo_plazo + "']").attr("selected","selected");
			document.getElementById('num_ventas_mes_consultar').value = data.num_ventas_mes;
			document.getElementById('porctj_comision_consultar').value = data.porctj_comision.replace('.',',');
			cuadros('#cuadro1', '#cuadro3');
		});
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/* 
		Funcion que muestra el cuadro4 para editar el banco.
	*/
	function editar(tbody, table){
		$(tbody).on("click", "span.editar", function(){
			var data = table.row( $(this).parents("tr") ).data();
			$("#id_vendedor_actualizar option[value='" + data.id_vendedor + "']").attr("selected","selected");
			$("#tipo_vendedor_actualizar option[value='" + data.tipo_vendedor + "']").attr("selected","selected");
			$("#tipo_plazo_actualizar option[value='" + data.tipo_plazo + "']").attr("selected","selected");
			document.getElementById('id_esquema_comision_actualizar').value = data.id_esquema_comision;
			document.getElementById('num_ventas_mes_actualizar').value = data.num_ventas_mes;
			document.getElementById('porctj_comision_actualizar').value = data.porctj_comision.replace('.',',');
			cuadros('#cuadro1', '#cuadro4');
			$("#id_vendedor_actualizar").focus();
		});
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que realiza el envio del formulario de registro
	*/
	function actualizar_esquema_comision(){
		enviarFormulario("#form_esquema_comision_actualizar", 'EsquemaComision/actualizar_esquema_comision', '#cuadro4');
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que capta y envia los datos a eliminar
	*/
	function eliminar(tbody, table){
		$(tbody).on("click", "span.eliminar", function(){
            var data=table.row($(this).parents("tr")).data();
            eliminarConfirmacion('EsquemaComision/eliminar_esquema_comision', data.id_esquema_comision, "¿Esta seguro de eliminar el registro?");
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
            statusConfirmacion('EsquemaComision/status_esquema_comision', data.id_esquema_comision, 2, "¿Esta seguro de desactivar el registro?", 'desactivar');
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
            statusConfirmacion('EsquemaComision/status_esquema_comision', data.id_esquema_comision, 1, "¿Esta seguro de activar el registro?", 'activar');
        });
	}
/* ------------------------------------------------------------------------------- */