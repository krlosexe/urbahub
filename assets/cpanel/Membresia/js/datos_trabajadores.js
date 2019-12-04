$(document).ready(function(){
	elegirFecha_Cumple('.fecha');
	telefonoInput('.telefono');
	listar();
	registrar_trabajadores();
	actualizar_trabajadores();
});

/* ------------------------------------------------------------------------------- */
	/* 
		Funcion para cargar los datos de la base de datos en la tabla.
	*/
	function listar(cuadro){
		$('#tabla tbody').off('click');
		cuadros(cuadro, "#cuadro1");
		var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
		var id_membresia = document.getElementById('id_membresia').value;
		//console.log(id_cliente);
		var table=$("#tabla").DataTable({
			"destroy":true,
			"stateSave": true,
			"serverSide":false,
			"ajax":{
				"method":"POST",
				"url": url + "Membresia/listado_datos_trabajadores/"+id_membresia,
				"dataSrc":""
			},
			//"url": url + "Membresia/listado_datos_trabajadores/"+id_membresia,
			"columns":[
				{"data": "id_datos_trabajadores",
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
						if(data.status == true && actualizar == 0 && editable == 1)
							botones += "<span class='desactivar btn btn-xs btn-warning waves-effect' data-toggle='tooltip' title='Desactivar'><i class='fa fa-unlock' style='margin-bottom:5px'></i></span> ";
						else if(data.status == false && actualizar == 0 && editable == 1)
							botones += "<span class='activar btn btn-xs btn-warning waves-effect' data-toggle='tooltip' title='Activar'><i class='fa fa-lock' style='margin-bottom:5px'></i></span> ";
						if(borrar == 0 && editable == 1)
		              		botones += "<span class='eliminar btn btn-xs btn-danger waves-effect' data-toggle='tooltip' title='Eliminar'><i class='fa fa-trash-o' style='margin-bottom:5px'></i></span>";
		          		return botones;
		          	}
				},
				{"data":"nombre_datos_personales"},
				{"data":"correo_datos_personales"},
				{"data":"telefono_datos_personales"},
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
		$("#form_datos_trabajador_registrar")[0].reset();
		$("#serial_acceso_moral_dt").focus();
		$("#imagen_registrar").attr('src', "http://placehold.it/180");
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que realiza el envio del formulario de registro
	*/
	function registrar_trabajadores(){
		enviarFormulario("#form_datos_trabajador_registrar", 'Membresia/guardarTrabajador', '#cuadro2');
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/* 
		Funcion que muestra el cuadro3 para la consulta
	*/
	function ver(tbody, table){
		$("#form_datos_trabajador_registrar")[0].reset()
		$("#form_datos_trabajador_actualizar")[0].reset()
		$(tbody).on("click", "span.consultar", function(){
			var data = table.row( $(this).parents("tr") ).data()
			console.log(data);
			$("#serial_acceso_moral_dt_mostrar").val(data.serial_acceso)
			$("#grupo_empresarial_dt_mostrar").val(data.grupo_empresarial)
			$("#nombre_dt_mostrar").val(data.nombre)
			$("#apellido_paterno_moral_dt_mostrar").val(data.apellido_paterno)
			$("#apellido_materno_dt_mostrar").val(data.apellido_materno)
			$("#genero_registrar_dt_mostrar").val(data.genero)
			$("#edo_civil_dt_mostrar").val(data.edo_civil)
			$("#nacionalidad_dt_mostrar").val(data.pais_nacionalidad) 
			$("#fecha_nacimiento_dt_mostrar").val(data.fecha_nacimiento)
			$("#curp_dt_mostrar").val(data.curp)
			$("#pasaporte_dt_mostrar").val(data.pasaporte)
			$("#telefono_dt_mostrar").val(data.telefono_datos_personales)
			$("#correo_dt_mostrar").val(data.correo_datos_personales)
			$("#actividad_economica_dt_mostrar").val(data.actividad_economica) 
			if(data.imagen!=""){
				$("#imagen_consultar").attr('src', document.getElementById('ruta').value+'assets/cpanel/Membresia/images/'+data.imagen
);
			}else{
				$("#imagen_consultar").attr('src', "http://placehold.it/180");
			}
			cuadros('#cuadro1', '#cuadro3');
		});
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/* 
		Funcion que muestra el cuadro4 para editar
	*/
	function editar(tbody, table){
		$("#form_datos_trabajador_actualizar")[0].reset();
		$(tbody).on("click", "span.editar", function(){
			var data = table.row( $(this).parents("tr") ).data();
			console.log(data);
			//alert(data.nombre);
			$("#serial_acceso_moral_dt_actualizar").val(data.serial_acceso);
			$("#grupo_empresarial_dt_actualizar option[value='" +data.id_grupo_empresarial+ "']").prop("selected",true);
			$("#nombre_dt_actualizar").val(data.nombre);
			$("#apellido_paterno_moral_dt_actualizar").val(data.apellido_paterno)
			$("#apellido_materno_dt_actualizar").val(data.apellido_materno)
			$("#genero_dt_actualizar option[value='"+data.id_genero+"']").prop("selected",true);
			$("#edo_civil_dt_actualizar option[value='"+data.id_estado_civil+"']").prop("selected",true);
			$("#nacionalidad_dt_actualizar option[value='"+data.nacionalidad+"']").prop("selected",true);
			$("#fecha_nacimiento_dt_actualizar").val(data.fecha_nacimiento);
			$("#curp_dt_actualizar").val(data.curp);
			$("#pasaporte_dt_actualizar").val(data.pasaporte);
			$("#telefono_dt_actualizar").val(data.telefono_datos_personales);
			$("#correo_dt_actualizar").val(data.correo_datos_personales);
			$("#actividad_economica_dt_actualizar option[value='"+data.id_actividad_economica+"']").prop("selected",true);
			if(data.imagen!=""){
				$("#imagen_actualizar").attr('src', document.getElementById('ruta').value+'assets/cpanel/Membresia/images/'+data.imagen);
			}else{
				$("#imagen_actualizar").attr('src', "http://placehold.it/100");
			}
			cuadros('#cuadro1', '#cuadro4');
		});
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que realiza el envio del formulario de registro
	*/
	function actualizar_trabajadores(){
		enviarFormulario("#form_datos_trabajador_actualizar", 'Membresia/actualizar_trabajadores', '#cuadro4');
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que capta y envia los datos a eliminar
	*/
	function eliminar(tbody, table){
		$(tbody).on("click", "span.eliminar", function(){
            var data=table.row($(this).parents("tr")).data();
            eliminarConfirmacion('Membresia/eliminar_datos_trabajador', data.serial_acceso, "¿Esta seguro de eliminar el registro?");
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
            statusConfirmacion('Membresia/status_datos_trabajador', data.serial_acceso, 2, "¿Esta seguro de desactivar el registro?", 'desactivar');
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
            statusConfirmacion('Membresia/status_datos_trabajador', data.serial_acceso, 1, "¿Esta seguro de activar el registro?", 'activar');
        });
	}

