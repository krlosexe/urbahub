$(document).ready(function(){
	listar();
	registrar_inmobiliaria();
	actualizar_inmobiliaria();
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
				"url": url + "Inmobiliarias/listado_inmobiliarias",
				"dataSrc":""
			},
			"columns":[
				{"data": "id_inmobiliaria",
					render : function(data, type, row) {
						return "<input type='checkbox' class='checkitem chk-col-blue' id='item"+data+"' value='"+data+"'><label for='item"+data+"'></label>"
					}
				},
				{"data":"codigo"},
				{"data":"nombre",
					render : function(data, type, row) {
						var descripcion = data;
						if (descripcion.length > 15)
							descripcion = data.substr(0,14) + "..."
						return descripcion;
	          		}
				},
				{"data": null,
					render : function(data, type, row) {
						var nombres = data.nombres + " " + data.paterno + " " + data.materno;
						if (nombres.length > 15)
							descripcion = nombres.substr(0,14) + "..."
						return descripcion;
	          		}
				},
				{"data":"nombre_lista_valor",
					render : function(data, type, row) {
						var descripcion = data;
						if (descripcion.length > 15)
							descripcion = data.substr(0,14) + "..."
						return descripcion;
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
	function nuevoInmobiliaria(cuadroOcultar, cuadroMostrar){
		$("#alertas").css("display", "none");
		cuadros("#cuadro1", "#cuadro2");
		$("#form_inmobiliaria_registrar")[0].reset();
		$("#codigo_registrar").focus();
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que realiza el envio del formulario de registro
	*/
	function registrar_inmobiliaria(){
		enviarFormulario("#form_inmobiliaria_registrar", 'Inmobiliarias/registrar_inmobiliaria', '#cuadro2');
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
			document.getElementById('codigo_consultar').value=data.codigo;
			document.getElementById('nombre_consultar').value=data.nombre;
			$("#coordinador_consultar option[value='" + data.id_coordinador + "']").attr("selected","selected");
			document.getElementById('localidad_consultar').value=data.localidad;
			cuadros('#cuadro1', '#cuadro3');
		});
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/* 
		Funcion que muestra el cuadro4 para editar el banco.
	*/
	function editar(tbody, table){
		$("#form_inmobiliaria_actualizar")[0].reset();
		$(tbody).on("click", "span.editar", function(){

			$("#alertas").css("display", "none");
			var data = table.row( $(this).parents("tr") ).data();
			document.getElementById('id_inmobiliaria_editar').value = data.id_inmobiliaria;
			document.getElementById('codigo_editar').value=data.codigo;
			document.getElementById('nombre_editar').value=data.nombre;
			$("#coordinador_editar option[value='" + data.id_coordinador + "']").attr("selected","selected");
			document.getElementById('localidad_editar').value=data.localidad;
			cuadros('#cuadro1', '#cuadro4');
			$("#codigo_editar").focus();
		});
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que realiza el envio del formulario de registro
	*/
	function actualizar_inmobiliaria(){
		enviarFormulario("#form_inmobiliaria_actualizar", 'Inmobiliarias/actualizar_inmobiliaria', '#cuadro4');
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que capta y envia los datos a eliminar
	*/
	function eliminar(tbody, table){
		$(tbody).on("click", "span.eliminar", function(){
            var data=table.row($(this).parents("tr")).data();
            eliminarConfirmacion('Inmobiliarias/eliminar_inmobiliaria', data.id_inmobiliaria, "¿Esta seguro de eliminar el registro?");
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
            statusConfirmacion('Inmobiliarias/status_inmobiliaria', data.id_inmobiliaria, 2, "¿Esta seguro de desactivar el registro?", 'desactivar');
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
            statusConfirmacion('Inmobiliarias/status_inmobiliaria', data.id_inmobiliaria, 1, "¿Esta seguro de activar el registro?", 'activar');
        });
	}
/* ------------------------------------------------------------------------------- */
