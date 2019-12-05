$(document).ready(function(){
	listar();
	registrar_comision();
	actualizar_comision();
	//porcentaje('.porcentaje');
	decimalesInput('.comision');

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
				"url": url + "Comision/listado_comision",
				"dataSrc":""
			},
			"columns":[
				{"data": "id_comision",
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
				{"data":"idVendedor"},
				{"data":"tipoVendedor"},
				{"data":"ind_ventas_mes",
					render : function(data, type, row) {
						if (data == "S") {
							return "SI";
						}else{
							return "NO";
						}
	          		}
				},
				{"data":"cantidad_min_ventas_mes"},
				{"data":"cantidad_max_ventas_mes"},
				{"data":"tipoPlazo"},
				{"data":"porctj_comision",
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
	function nuevoComision(cuadroOcultar, cuadroMostrar){
		cuadros("#cuadro1", "#cuadro2");
		$("#alertas").css("display", "none");
		$("#form_comision_registrar")[0].reset();
		$("#id_vendedor_registrar").focus();
		$("#num_ventas_max_mes_registrar, #num_ventas_min_mes_registrar").attr("disabled", "disabled").removeAttr("required");
		$("#indicador_venta_registrar").val("N");

	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que realiza el envio del formulario de registro
	*/
	function registrar_comision(){
		enviarFormulario("#form_comision_registrar", 'Comision/registrar_comision', '#cuadro2');
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/* 
		Funcion que muestra el cuadro3 para la consulta del banco.
	*/
	function ver(tbody, table){
		$(tbody).on("click", "span.consultar", function(){
			var data = table.row( $(this).parents("tr") ).data();
			$("#alertas").css("display", "none");
			
			/*$("#id_vendedor_consultar option[value='" + data.id_vendedor + "']").attr("selected","selected");
			$("#tipo_vendedor_consultar option[value='" + data.tipo_vendedor + "']").attr("selected","selected");
			$("#tipo_plazo_consultar option[value='" + data.tipo_plazo + "']").attr("selected","selected");
			$("#cod_esquema_consultar option[value='" + data.cod_esquema + "']").attr("selected","selected");*/

			$("#id_vendedor_consultar option").removeAttr("selected");
			$("#id_vendedor_consultar option[value='" + data.id_vendedor + "']").prop("selected",true);

			$("#tipo_vendedor_consultar option").removeAttr("selected");
			$("#tipo_vendedor_consultar option[value='" + data.tipo_vendedor + "']").prop("selected",true);

			$("#tipo_plazo_consultar option").removeAttr("selected");
			$("#tipo_plazo_consultar option[value='" + data.tipo_plazo + "']").prop("selected",true);

			$("#cod_esquema_consultar option").removeAttr("selected");
			$("#cod_esquema_consultar option[value='" + data.cod_esquema + "']").prop("selected",true);
			
			document.getElementById('num_ventas_max_mes_consultar').value = data.cantidad_max_ventas_mes;
			document.getElementById('num_ventas_min_mes_consultar').value = data.cantidad_min_ventas_mes;
			//document.getElementById('porctj_comision_consultar').value = data.porctj_comision.replace('.','.');
			document.getElementById('porctj_comision_consultar').value = data.porctj_comision;
			cuadros('#cuadro1', '#cuadro3');
		});
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/* 
		Funcion que muestra el cuadro4 para editar el banco.
	*/
	function editar(tbody, table){
		$("#form_comision_actualizar")[0].reset();
		$(tbody).on("click", "span.editar", function(){
			$("#alertas").css("display", "none");
			var data = table.row( $(this).parents("tr") ).data();

			$("#id_vendedor_actualizar option[value='" + data.id_vendedor + "']").prop("selected",true);
			//$("#id_vendedor_actualizar option[value='" + data.id_vendedor + "']").attr("selected","selected");
			//$("#tipo_vendedor_actualizar option[value='" + data.tipo_vendedor + "']").attr("selected","selected");
			
			$("#tipo_vendedor_actualizar option").removeAttr("selected");
			$("#tipo_vendedor_actualizar option[value='" + data.tipo_vendedor + "']").prop("selected",true);

			$("#tipo_plazo_actualizar option[value='" + data.tipo_plazo + "']").prop("selected",true);
			$("#cod_esquema_actualizar option[value='" + data.cod_esquema + "']").prop("selected",true);
			document.getElementById('id_comision_actualizar').value = data.id_comision;
			document.getElementById('num_ventas_max_mes_actualizar').value = data.cantidad_max_ventas_mes;
			document.getElementById('num_ventas_min_mes_actualizar').value = data.cantidad_min_ventas_mes;
			document.getElementById('porctj_comision_actualizar').value = data.porctj_comision;
			//document.getElementById('porctj_comision_actualizar').value = data.porctj_comision.replace('.','.');

			$("#num_ventas_max_mes_actualizar, #num_ventas_min_mes_actualizar").attr("disabled", "disabled").removeAttr("required");
			$("#indicador_venta_actualizar").val(data.ind_ventas_mes);

			if (data.ind_ventas_mes == "S") {
				$("#indicador_actualizar").attr("checked","checked");
				$("#num_ventas_max_mes_actualizar").removeAttr("disabled");
				$("#num_ventas_min_mes_actualizar").removeAttr("disabled");
			}else{
				$("#indicador_actualizar").removeAttr("checked");
			}
			
			cuadros('#cuadro1', '#cuadro4');
			$("#id_vendedor_actualizar").focus();
			$("option[status='']").prop('hidden', true);
		});
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que realiza el envio del formulario de registro
	*/
	function actualizar_comision(){
		enviarFormulario("#form_comision_actualizar", 'Comision/actualizar_comision', '#cuadro4');
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que capta y envia los datos a eliminar
	*/
	function eliminar(tbody, table){
		$(tbody).on("click", "span.eliminar", function(){
            var data=table.row($(this).parents("tr")).data();
            eliminarConfirmacion('Comision/eliminar_comision', data.id_comision, "¿Esta seguro de eliminar el registro?");
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
            statusConfirmacion('Comision/status_comision', data.id_comision, 2, "¿Esta seguro de desactivar el registro?", 'desactivar');
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
            statusConfirmacion('Comision/status_comision', data.id_comision, 1, "¿Esta seguro de activar el registro?", 'activar');
        });
	}
	function porcentaje(input){
        $(input).inputmask('999.99', {reverse: true});
    }
/* ------------------------------------------------------------------------------- */



$("#indicador_registrar").on("change", function(){
	//alert($(this).val());

	if ($("#indicador_registrar").is(':checked')) {
		//console.log("si");

		$("#num_ventas_max_mes_registrar, #num_ventas_min_mes_registrar").removeAttr("disabled").attr("required", "required");
		$("#num_ventas_min_mes_registrar").focus();
		$("#indicador_venta_registrar").val("S");
	}else{
		//console.log("no");
		$("#num_ventas_max_mes_registrar, #num_ventas_min_mes_registrar").attr("disabled", "disabled").removeAttr("required");
		$("#num_ventas_max_mes_registrar, #num_ventas_min_mes_registrar").val("");
		$("#indicador_venta_registrar").val("N");
	}
});



$("#indicador_actualizar").on("change", function(){
	//alert($(this).val());
	if ($("#indicador_actualizar").is(':checked')) {
		//console.log("si");

		$("#num_ventas_max_mes_actualizar, #num_ventas_min_mes_actualizar").removeAttr("disabled").attr("required", "required");
		$("#num_ventas_min_mes_actualizar").focus();
		$("#indicador_venta_actualizar").val("S");
	}else{
		//console.log("no");
		$("#num_ventas_max_mes_actualizar, #num_ventas_min_mes_actualizar").attr("disabled", "disabled").removeAttr("required");
		$("#num_ventas_max_mes_actualizar, #num_ventas_min_mes_actualizar").val("");
		$("#indicador_venta_actualizar").val("N");
	}
});
