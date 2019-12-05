//--Bloque de llamados
$(document).ready(function(){
	plan = $("#plan_renovaciones_registrar").val();
	paquete = $("#id_paquete_renovacion").val()
	consultarPaquetesrenovacion(plan,paquete);
	consultarPlanRenovacion(paquete,plan)
});
//--Bloque de funciones
/*
*	Consulta de paquete renovación
*/
function consultarPaquetesrenovacion(plan,paquete){

	if(paquete==""){
		$("#id_paquete_renovacion").val("")
	}
//----------------------------------------
	var controlador = "Membresia/consultarPaquetes"
    var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
    $('input[type="submit"]').attr('disabled','disabled'); //desactiva el input submit
    var id_plan = plan
    $.ajax({
        url:url+controlador,
        type:"POST",
        dataType:"JSON",
        data:{
                        "id_plan":id_plan,
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
				eliminarOptions("paquetes_renovaciones_registrar")
				respuesta.forEach(function(campo, index){
					if(campo.status==true)
	                	agregarOptions("#paquetes_renovaciones_registrar", campo.id_paquete, campo.descripcion);
	            });
	        	$("#paquetes_renovaciones_registrar option[value='"+paquete+"']").prop("selected",true);
				//----------------------	
			}else{
				
				//Blanqueo la tabla de la parte superior!
				$("#horas_jornadas_renovacion").html("");
	            $("#precio_plan_renovacion").html("");
	            $("#fecha_inicio_renovacion").html("");
	            $("#fecha_fin_renovacion").html("");
	            //Doy valor a las cajas para el envio por POST
	            $("#plan_horas_renovacion").val("");
	            $("#plan_valor_renovacion").val("");
	            $("#plan_fecha_inicio_renovacion").val("");
	            $("#plan_fecha_fin_renovacion").val("");
	            //--
				eliminarOptions("paquetes_renovaciones_registrar")
            	$("#paquetes_renovaciones_registrar option[value='']").prop("selected",true);
			}
			//--
			
		}	
	});	
//----------------------------------------
}
/*
*	Consulta de planes renovaciones, tabla superior
*/
function consultarPlanRenovacion(paquete,id_plan){
	
	if(id_plan == ""){
		id_plan = $("#plan_renovaciones_registrar").val();
	}
	$("#id_paquete_renovacion").val(paquete);
	var controlador = "Membresia/consultarPlanPaquetesTablas"
    var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
    $('input[type="submit"]').attr('disabled','disabled'); //desactiva el input submit
  
    $.ajax({
        url:url+controlador,
        type:"POST",
        dataType:"JSON",
        data:{
                        "id_plan":id_plan,
                        "paquete":paquete
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
			if(respuesta.length>0){
				if(respuesta[0]["horas_jornadas"]=="0"){
					mensajes('danger', "<span>No puede seleccionar el paquete porque no tiene asociado el servicio horas de coworking!</span>");        
					$("#horas_jornadas_renovacion").html("");
		            $("#precio_plan_renovacion").html("");
		            $("#fecha_inicio_renovacion").html("");
		            $("#fecha_fin_renovacion").html("");
		            //Doy valor a las cajas para el envio por POST
		            $("#plan_horas_renovacion").val("");
		            $("#plan_valor_renovacion").val("");
		            $("#plan_fecha_inicio_renovacion").val("");
		            $("#plan_fecha_fin_renovacion").val("");
		            //--
				}else{
					$("#horas_jornadas_renovacion").html(respuesta[0]["horas_jornadas"]);
		            $("#precio_plan_renovacion").html(respuesta[0]["valor"]);
		            $("#fecha_inicio_renovacion").html(respuesta[0]["inicio"]);
		            $("#fecha_fin_renovacion").html(respuesta[0]["vigencia"]);
		            //Doy valor a las cajas para el envio por POST
		            $("#plan_horas_renovacion").val(respuesta[0]["horas_jornadas"]);
		            $("#plan_valor_renovacion").val(respuesta[0]["valor"]);
		            $("#plan_fecha_inicio_renovacion").val(respuesta[0]["inicio"]);
		            $("#plan_fecha_fin_renovacion").val(respuesta[0]["vigencia"]);
		            //--
				}
				
			}
		}	
	});	
}
/*
*	Renovar membresia
*/
function renovarMembresia(cuadro){
	//---
	swal({
        title: '¿Esta seguro de renovar la membresía?',
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Si, Renovar!",
        cancelButtonText: "No, Cancelar!",
        closeOnConfirm: true,
        closeOnCancel: false
    },
    function(isConfirm){
        if (isConfirm) {
            swal.close();
			//-------------------------------------------------------------------------
			var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>

			var cliente = $("#id_cliente_renovacion").val();

			var paquete = $("#paquetes_renovaciones_registrar").val();

			var plan = $("#plan_renovaciones_registrar").val();

			var membresia =  $("#id_membresia_renovacion").val();

			var plan_fecha_inicio = $("#plan_fecha_inicio_renovacion").val();

			var plan_fecha_fin = $("#plan_fecha_fin_renovacion").val();

			var plan_valor = $("#plan_valor_renovacion").val();

			var renovacion = $("#numero_renovacion").val();

			$.ajax({
			                url:url+'Membresia/renovar_membresia',
			                type:'POST',
			                dataType:'JSON',
			                data:{
			                		'cliente':cliente,
			                		'paquete':paquete,
			                		'plan':plan,
			                		'membresia':membresia,
			                		'plan_fecha_inicio':plan_fecha_inicio,
			                		'plan_fecha_fin':plan_fecha_fin,
			                		'plan_valor':plan_valor,
			                		'renovacion':renovacion
			               	},
			                beforeSend: function(){
			                    mensajes('info', '<span>Buscando, espere por favor... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
			                },
			                error: function (repuesta) {
			                    mensajes('danger', '<span>Ha ocurrido un error, por favor intentelo de nuevo</span>');         
			                },
			                success: function(respuesta){
			                	//-----------------------------------------------
				                if (respuesta.success == false) {
			                         mensajes('danger', respuesta.message);
			                         $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
			                    }else{
			                    	$('input[type="submit"]').removeAttr('disabled'); //activa el input submit
			                        setTimeout(function(){
			                        	mensajes('success', respuesta);
			                        	swal({
									        title: respuesta,
									        type: "success",
									        showCancelButton: false,
									        confirmButtonColor: "#DD6B55",
									        confirmButtonText: "Ok",
									        closeOnConfirm: true,
									        closeOnCancel: false
									    },
									    function(isConfirm){
									  		window.parent.listar("#cuadro4")
									    });
									    //-------
			                        },2000);
			                    }
			                    //-------------------------------------------------
			                }
			            });
			//------------------------------------------------------------------------
		}else {
                swal("Cancelado", "No se ha renovado la membresía", "error");
            }	
	})
}	
/*
*
*/
