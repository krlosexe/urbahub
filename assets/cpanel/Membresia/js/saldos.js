$(document).ready(function(){
	$("#fecha_saldos_c").datetimepicker({
        format: 'D-MM-YYYY',
    });
    consultarPlanesPaquetes();
	listarRecargasSaldos();
	listarRecargasAdicionalesSaldos();
	listarJornadasSaldos();
	listarReservacionesSaldos();
	calc_salajuntas();
});
/*
*	consultarPlanesPaquetes
*/
function consultarPlanesPaquetes(){
	//---
	var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
	id_membresia = $("#id_membresia_saldosC").val()
	numero_renovacion = $("#renovaciones_saldos_c").val()
	//---
	$.ajax({
        url:url + "Membresia/planes_paquetes_saldos",
        type:"POST",
        dataType:"JSON",
        data:{
                        "id_membresia":id_membresia,
                        "numero_renovacion":numero_renovacion
        },
        cache:false,
        beforeSend: function(){
            mensajes('info', '<span>Buscando datos, espere por favor... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
        },
        error: function (repuesta) {
            $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
            var errores=repuesta.responseText;
            if(errores!="")
                mensajes('danger', errores);
            else
                mensajes('danger', "<span>Ha ocurrido un error, por favor intentelo de nuevo.</span>");        
        },
        success: function(respuesta){
        	$("#alertas").html('');
        	$('input[type="submit"]').removeAttr('disabled'); //activa el input submit
			console.log(respuesta);
			if(respuesta!=""){
			//----------------------
				$("#planesSaldos").html(respuesta["plan"]);
				$("#paquetesSaldos").html(respuesta["paquete"]);
			//----------------------	
			}
		}	
	});	
} 
/*
* Filtrar consultas por fecha
*/
function filtrarDatatable(){
	mensajes('info', '<span>Buscando datos, espere por favor... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
	fecha = $("#fecha_saldos_c").val();
	consultarPlanesPaquetes();
	listarRecargasSaldos(fecha);
	listarRecargasAdicionalesSaldos(fecha);
	listarJornadasSaldos(fecha);
	listarReservacionesSaldos(fecha);
	mensajes('success', '<span>Consulta procesada</span>');

}
/***/
/*
*	Listado saldos jornadas
*/
function listarJornadasSaldos(fecha=""){
	id_membresia = $("#id_membresia_saldosC").val()
	numero_renovacion = $("#renovaciones_saldos_c").val()
	$('#tablaJornadas tbody').off('click');
	var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
	var table=$("#tablaJornadas").DataTable({
		"destroy":true,
		"stateSave": true,
		"serverSide":false,
		"ajax":{
			"method":"POST",
			"url": url + "Membresia/listadoJornadasSaldos",
			"dataSrc":"",
			"type":'POST',
	        "dataType":'JSON',
	        "data":{
                        "id_membresia":id_membresia,
                        "numero_renovacion":numero_renovacion,
                        "fecha":fecha
        	},
		},
		"columns":[
			{"data":"actual",
					render : function(data, type, row) {
						
						a = data.date.split(".");
						dato = a[0].split(" ")
						fecha = cambiarFormatoFecha(dato[0]);
						dato2 = dato[1].split(".")
						sinEspacio = dato2[0];
						id = fecha+sinEspacio
						singuion = id.replace(/-/g,"");
						b = singuion.replace(/:/g,"");
						return b;
	          		}
				},
			{"data":"contratados"},
			{"data":"consumidos"},
			{"data":"disponibles"}			
		],
		"language": idioma_espanol,
		"dom": 'Bfrtip',
		"responsive": true,
		"buttons":[
			'copy', 'csv', 'excel', 'pdf', 'print'
		]
	});
}
/***/
/*
*	Listado Reservaciones Saldos
*/
function listarReservacionesSaldos(fecha=""){
	id_membresia = $("#id_membresia_saldosC").val()
	numero_renovacion = $("#renovaciones_saldos_c").val()
	$('#tablaReservaciones tbody').off('click');
	var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
	var table=$("#tablaReservaciones").DataTable({
		"destroy":true,
		"stateSave": true,
		"serverSide":false,
		"ajax":{
			"method":"POST",
			"url": url + "Membresia/listadoReservacionesSaldos",
			"dataSrc":"",
			"type":'POST',
	        "dataType":'JSON',
	        "data":{
                        "id_membresia":id_membresia,
                        "numero_renovacion":numero_renovacion,
                        "fecha":fecha
        	},
		},
		"columns":[
			{"data":"id_reservaciones"},
			{"data":"sala"},
			{"data":"horas_contratadas"},
			{"data":"horas_consumidas"},
			{"data":"horas_disponibles"}			
		],
		"language": idioma_espanol,
		"dom": 'Bfrtip',
		"responsive": true,
		"buttons":[
			'copy', 'csv', 'excel', 'pdf', 'print'
		]
	});
}


function calc_salajuntas() {
	var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
	id_membresia = $("#id_membresia_saldosC").val()
	numero_renovacion = $("#renovaciones_saldos_c").val()
	//---
	$.ajax({
        url:url + "Membresia/TotalHorasConsumidas2",
        type:"POST",
        dataType:"JSON",
        data:{
            "id_membresia":id_membresia,
            "numero_renovacion":numero_renovacion
        },
        cache:false,
        beforeSend: function(){
            mensajes('info', '<span>Buscando datos, espere por favor... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
        },
        error: function (repuesta) {
            $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
            var errores=repuesta.responseText;
            if(errores!="")
                mensajes('danger', errores);
            else
                mensajes('danger', "<span>Ha ocurrido un error, por favor intentelo de nuevo.</span>");        
        },
        success: function(respuesta){
        	$("#alertas").html('');
        	$('input[type="submit"]').removeAttr('disabled'); //activa el input submit
        	console.log("SALDOS");
			console.log(respuesta);
		}	
	});
}
/***/
/*
*	Listado recargas saldos 
*/
function listarRecargasSaldos(fecha=""){
	//---
	id_membresia = $("#id_membresia_saldosC").val()
	numero_renovacion = $("#renovaciones_saldos_c").val()

	//$('#tablaRecargos tbody').off('click');
	var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
	var table=$("#tablaRecargos").DataTable({
		"destroy":true,
		"stateSave": true,
		"serverSide":false,
		"order": [[ 3, 'desc' ], [ 0, 'asc' ]],
		"ajax":{
			"method":"POST",
			"url": url + "Membresia/listadoRecargosSaldos",
			"dataSrc":"",
			"type":'POST',
	        "dataType":'JSON',
	        "data":{
                        "id_membresia":id_membresia,
                        "fecha":fecha,
                        "numero_renovacion":numero_renovacion
        	},
		},
		"columns":[
			//{"data":"id_servicios"},
			{"data":"servicios",
				render : function(data, type, row) {
					return data+"<input type='hidden' value='"+data+"' />";
	          	}
			},
			{"data":"contratados"},
			{"data":"consumidos"},
			{"data":"disponibles"}			
		],
		"language": idioma_espanol,
		"dom": 'Bfrtip',
		"responsive": true,
		"buttons":[
			'copy', 'csv', 'excel', 'pdf', 'print'
		],

	});
	//---
}
/***/
/***/
/*
*	Listado recargas saldos 
*/
function listarRecargasAdicionalesSaldos(fecha=""){
	//---
	id_membresia = $("#id_membresia_saldosC").val()
	numero_renovacion = $("#renovaciones_saldos_c").val()
	$('#tablaRecargosAdicionales tbody').off('click');
	var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
	var table=$("#tablaRecargosAdicionales").DataTable({
		"destroy":true,
		"stateSave": true,
		"serverSide":false,
		"ajax":{
			"method":"POST",
			"url": url + "Membresia/listadoRecargosAdicionalesSaldos",
			"dataSrc":"",
			"type":'POST',
	        "dataType":'JSON',
	        "data":{
                        "id_membresia":id_membresia,
                        "numero_renovacion":numero_renovacion,
                        "fecha":fecha
        	},
		},
		"columns":[
			{"data":"fecha",
			render : function(data, type, row) {
						
						a = data.date.split(".");
						dato = a[0].split(" ")
						fecha = cambiarFormatoFecha(dato[0]);
						dato2 = dato[1].split(".")
						sinEspacio = dato2[0];
						id = fecha+sinEspacio
						singuion = id.replace(/-/g,"");
						b = singuion.replace(/:/g,"");
						return b;
	          		}

		},
			{"data":"titulo"},
			{"data":"cantidad"}		
		],
		"language": idioma_espanol,
		"dom": 'Bfrtip',
		"responsive": true,
		"buttons":[
			'copy', 'csv', 'excel', 'pdf', 'print'
		]
	});
	//---
}
/***/