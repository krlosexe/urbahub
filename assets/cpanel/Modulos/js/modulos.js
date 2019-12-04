$(document).ready(function(){
	listar();
	registrar_modulo();
	actualizar_modulo();
});

/* ------------------------------------------------------------------------------- */
	/* 
		Funcion para cargar los datos de la base de datos en la tabla.
	*/
	function listar(cuadro){
		contarModulos();
		$('#tabla tbody').off('click');
		cuadros(cuadro, "#cuadro1");
		var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
		var table=$("#tabla").DataTable({
			"destroy":true,
			"stateSave": true,
			"serverSide":false,
			"ajax":{
				"method":"POST",
				"url":url+"Modulos/listado_modulos",
				"dataSrc":""
			},
			"columns":[
				{"data": "id_modulo_vista",
					render : function(data, type, row) {
						return "<input type='checkbox' class='checkitem chk-col-blue' id='item"+data+"' value='"+data+"'><label for='item"+data+"'></label>"
					}
				},
				{"data": null,
					render : function(data, type, row) {
						var botones = "";
						console.log(data.status);
						console.log(data.nombre_modulo_vista);
						console.log(actualizar);
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
				{"data":"nombre_modulo_vista"},
				{"data":"descripcion_modulo_vista",
					render : function(data, type, row) {
						var descripcion = data;
						if (data != null)
							if (data.length > 30)
								descripcion = data.substr(0,29) + "..."
						return descripcion;
					}
				},
				{"data":"posicion_modulo_vista"},
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
	function nuevoModulo(cuadroOcultar, cuadroMostrar){
		$("#alertas").css("display", "none");
		cuadros("#cuadro1", "#cuadro2");
		$("#form_modulo_registrar")[0].reset();
		$("#nombre_modulo_vista_registrar").focus();
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que realiza el envio del formulario de registro
	*/
	function registrar_modulo(){
		enviarFormulario("#form_modulo_registrar", 'Modulos/registrar_modulo', '#cuadro2');
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
			document.getElementById('nombre_modulo_vista_consultar').value=data.nombre_modulo_vista;
			document.getElementById('descripcion_modulo_vista_consultar').value=data.descripcion_modulo_vista;
			$('#posicion_modulo_vista_consultar').find('option').remove().end().append('<option value="'+data.posicion_modulo_vista+'">'+data.posicion_modulo_vista+'</option>');
			cuadros('#cuadro1', '#cuadro3');
		});
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/* 
		Funcion que muestra el cuadro4 para editar el banco.
	*/
	function editar(tbody, table){
		$("#form_modulo_actualizar")[0].reset();
		$(tbody).on("click", "span.editar", function(){
			$("#alertas").css("display", "none");
			var data = table.row( $(this).parents("tr") ).data();
			document.getElementById('nombre_modulo_vista_actualizar').value=data.nombre_modulo_vista;
			document.getElementById('descripcion_modulo_vista_actualizar').value=data.descripcion_modulo_vista;
			$("#posicion_modulo_vista_actualizar option[value='"+data.posicion_modulo_vista+"']").attr("selected","selected");
			document.getElementById('inicial').value=data.posicion_modulo_vista;
			//document.getElementById('id_modulo_vista_actualizar').value=data.id_modulo_vista;
			//--Migracion mongo db ..
			document.getElementById('id_modulo_vista_actualizar').value=data["_id"]["$id"];
			//--
			cuadros('#cuadro1', '#cuadro4');
			$("#nombre_modulo_vista_actualizar").focus();
		});
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que capta y envia los datos a eliminar
	*/
	function eliminar(tbody, table){
		$(tbody).on("click", "span.eliminar", function(){
            var data=table.row($(this).parents("tr")).data();
            eliminarConfirmacion('Modulos/eliminar_modulo', data.id_modulo_vista, "¿Esta seguro de eliminar el registro?");
        });
	}
/* ------------------------------------------------------------------------------- */
/*
        Funcion anonima captar los value de los checkbox seleccionamos
    */
    function eliminarMultipleModulos(controlador){
        var id=$(".checkitem:checked").map(function(){
            return $(this).val();
        }).get();
        if(Object.keys(id).length>0)
            eliminarConfirmacionModulos(controlador, id, "¿Esta seguro de eliminar los registros seleccionados?");
        else
            swal({
                title: "Debe seleccionar al menos una fila.",
                type: "warning",
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Aceptar!",
                closeOnConfirm: true
            });
    }
/* ------------------------------------------------------------------------------- */
    /*
        Funcion que se encarga eliminar un registro seleccionado.
    */
    function eliminarConfirmacionModulos(controlador, id, title){
        swal({
            title: title,
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Si, Eliminar!",
            cancelButtonText: "No, Cancelar!",
            closeOnConfirm: true,
            closeOnCancel: false
        },
        function(isConfirm){
            if (isConfirm) {
                swal.close();
                var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
                $.ajax({
                    url:url+controlador,
                    type: 'POST',
                    dataType: 'JSON',
                    data:{
                        id:id,
                    },
                    beforeSend: function(){
                        mensajes('info', '<span>Eliminando datos, espere por favor... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
                    },
                    error: function (repuesta) {
                        var errores=repuesta.responseText;
                        mensajes('danger', errores);
                    },
                    success: function(respuesta){
                        //listar();
                        //$("#checkall").prop("checked", false);
                        //mensajes('success', respuesta);
                        orderModulosMongo(controlador,respuesta);
                    }
                });
            } else {
                swal("Cancelado", "No se ha eliminado el registro", "error");
            }
        });
    }
/* ------------------------------------------------------------------------------- */
function orderModulosMongo(controlador,respuesta_eliminacion){
	var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
    $.ajax({
        url:"/Modulos/orden_eliminar",
        type: 'POST',
        dataType: 'JSON',
        data:{  },
        /*beforeSend: function(){
            mensajes('info', '<span>Eliminando datos, espere por favor... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
        },*/
        error: function (repuesta) {
            var errores=repuesta.responseText;
            mensajes('danger', errores);
        },
        success: function(respuesta){
            listar();
            $("#checkall").prop("checked", false);
            mensajes('success', respuesta_eliminacion);
        }
    });
}
/* ------------------------------------------------------------------------------- */
	/*
		Funcion que realiza el envio del formulario de registro
	*/
	function actualizar_modulo(){
		enviarFormulario("#form_modulo_actualizar", 'Modulos/actualizar_modulo', '#cuadro4');
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que capta y envia los datos a desactivar
	*/
	function desactivar(tbody, table){
		$(tbody).on("click", "span.desactivar", function(){
            var data=table.row($(this).parents("tr")).data();
           
            var id_modulo_vista = data["_id"]["$id"];

            console.log(id_modulo_vista);

            statusConfirmacion('Modulos/status_modulo', id_modulo_vista, 2, "¿Esta seguro de desactivar el registro?", 'desactivar');
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
            statusConfirmacion('Modulos/status_modulo', data.id_modulo_vista, 1, "¿Esta seguro de activar el registro?", 'activar');
        });
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que hace un count de los modulos registrados y el resultado se 
		despliega en un select para la seleccion de la posicion del modulo.
	*/
	function contarModulos(){
		$('#posicion_modulo_vista_registrar').find('option').remove().end().append('<option value="">Seleccione</option>');
		$('#posicion_modulo_vista_actualizar').find('option').remove().end().append('<option value="">Seleccione</option>');
		$.ajax({
	        url:document.getElementById('ruta').value + 'Modulos/contar_modulos',
	        type:'POST',
	        dataType:'JSON',
	        error: function() {
				contarModulos();
	        },
	        success: function(respuesta){
	            var selectRegistrar = Object.keys(respuesta).length +1;
	            var selectActualizar = Object.keys(respuesta).length;
	            for(var i = 1; i <= selectRegistrar; i++)
	            	agregarOptions("#posicion_modulo_vista_registrar", i, i);
	            for(var i = 1; i <= selectActualizar; i++)
	            	agregarOptions("#posicion_modulo_vista_actualizar", i, i);
	        }
	    });
	}
/* ------------------------------------------------------------------------------- */
