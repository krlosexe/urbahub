$(document).ready(function(){
	listar();
	registrar_recarga();
	actualizar_recarga();
	//porcentaje('.recarga');
	decimalesInput('.recarga');
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
				"url": url + "Recargas/listar_recargas",
				"dataSrc":""
			},
			"columns":[
				{"data": "id_recarga",
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
							botones += "<span class='activar btn btn-xs btn-warning waves-effect' data-toggle='tooltip' title='Activar'><i class='fa fa-lock' style='margin-bottom:5px'></i></span> ";
						if(borrar == 0)
		              		botones += "<span class='eliminar btn btn-xs btn-danger waves-effect' data-toggle='tooltip' title='Eliminar'><i class='fa fa-trash-o' style='margin-bottom:5px'></i></span>";
		          		return botones;
		          	}
				},
				{"data":"tipoPlazo"},
				{"data":"tipoVendedor"},
				{"data":"recarga",
					render : function(data, type, row) {
						return data.replace('.','.')  + " %";
	          		}
				},
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
	function nuevaRecarga(cuadroOcultar, cuadroMostrar){
		$("#alertas").css("display", "none");
		cuadros("#cuadro1", "#cuadro2");
		$("#form_recarga_registrar")[0].reset();
		$("#tipo_plazo_registrar").focus();
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que realiza el envio del formulario de registro
	*/
	function registrar_recarga(){
		enviarFormulario("#form_recarga_registrar", 'Recargas/registrar_recarga', '#cuadro2');
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
			$("#tipo_plazo_consultar option:selected").removeAttr("selected");
			$("#tipo_vendedor_consultar option:selected").removeAttr("selected");
			$("#cod_esquema_consultar option:selected").removeAttr("selected");

			$("#tipo_plazo_consultar option[value='" + data.tipo_plazo + "']").prop("selected",true);
			$("#tipo_vendedor_consultar option[value='" + data.tipo_vendedor + "']").prop("selected",true);
			$("#cod_esquema_consultar option[value='" + data.cod_esquema + "']").prop("selected",true);
			//document.getElementById('recarga_consultar').value = data.recarga.replace('.','.');
			cuadros('#cuadro1', '#cuadro3');
			document.getElementById('recarga_consultar').value = data.recarga;
		});
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/* 
		Funcion que muestra el cuadro4 para editar el banco.
	*/
	function editar(tbody, table){
		$("#form_recarga_actualizar")[0].reset();
		$(tbody).on("click", "span.editar", function(){
			$("#alertas").css("display", "none");
			var data = table.row( $(this).parents("tr") ).data();

			$("#tipo_plazo_actualizar option:selected").removeAttr("selected");
			$("#tipo_vendedor_actualizar option:selected").removeAttr("selected");
			$("#cod_esquema_actualizar option:selected").removeAttr("selected");

			
			$("#tipo_plazo_actualizar option[value='" + data.tipo_plazo + "']").prop("selected",true);
			$("#tipo_vendedor_actualizar option[value='" + data.tipo_vendedor + "']").prop("selected",true);
			$("#cod_esquema_actualizar option[value='" + data.cod_esquema + "']").prop("selected",true);
			document.getElementById('id_recarga_actualizar').value = data.id_recarga;
			//document.getElementById('recarga_actualizar').value = data.recarga.replace('.','.');
			cuadros('#cuadro1', '#cuadro4');
			$("#id_vendedor_actualizar").focus();
			$("option[status='']").prop('hidden', true);
			document.getElementById('recarga_actualizar').value = data.recarga;
		});
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que realiza el envio del formulario de registro
	*/
	function actualizar_recarga(){
		enviarFormulario("#form_recarga_actualizar", 'Recargas/actualizar_recarga', '#cuadro4');
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que capta y envia los datos a eliminar
	*/
	function eliminar(tbody, table){
		$(tbody).on("click", "span.eliminar", function(){
            var data=table.row($(this).parents("tr")).data();
            eliminarConfirmacion('Recargas/eliminar_recarga', data.id_recarga, "¿Esta seguro de eliminar el registro?");
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
            statusConfirmacion('Recargas/status_recarga', data.id_recarga, 2, "¿Esta seguro de desactivar el registro?", 'desactivar');
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
            statusConfirmacion('Recargas/status_recarga', data.id_recarga, 1, "¿Esta seguro de activar el registro?", 'activar');
        });
	}
	function porcentaje(input){
        $(input).inputmask('999.99', {reverse: true});
    }
/* ------------------------------------------------------------------------------- */
function valida(e){
			tecla = (document.all) ? e.keyCode : e.which;          
			patron =/^([0-9])*[.]?[0-9]*$/;
			tecla_final = String.fromCharCode(tecla);
			return patron.test(tecla_final);
		}
