$(document).ready(function(){
	//elegirFecha_Cumple('.fecha');
	telefonoInput('.telefono');
	busqueda = false;
});


/* ------------------------------------------------------------------------------- */
    /*
        Funcion que busca los codigos
    */
    /*function buscarCodigos(codigo, estado, ciudad, municipio, colonia){
    	if (!busqueda){
    		busqueda = true;

	        eliminarOptions(estado);
	        eliminarOptions(ciudad);
	        eliminarOptions(municipio);
	        eliminarOptions(colonia);
	        if(codigo.length>4){
	            var url=document.getElementById('ruta').value;
	            $.ajax({
	                url:url+'Perfil/buscar_codigos',
	                type:'POST',
	                dataType:'JSON',
	                data:{'codigo':codigo},
	                beforeSend: function(){
	                    mensajes('info', '<span>Buscando, espere por favor... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
	                },
	                error: function (repuesta) {
	                    mensajes('danger', '<span>Ha ocurrido un error, por favor intentelo de nuevo</span>');         
	                },
	                success: function(respuesta){
	                    $("#alertas").html('');
	                    respuesta.estados.result_object.forEach(function(campo, index){
	                        agregarOptions("#" + estado, campo.d_estado, campo.d_estado);
	                    });
	                    respuesta.ciudades.result_object.forEach(function(campo, index){
	                        if(campo.d_ciudad!=""){
	                            agregarOptions('#' + ciudad, campo.d_ciudad, campo.d_ciudad);
	                            $("#" + ciudad).css('border-color', '#ccc');
	                        }else{
	                            agregarOptions('#' + ciudad, "N/A", "NO APLICA");
	                            $("#" + ciudad).css('border-color', '#a94442');
	                        }
	                    });
	                    respuesta.municipios.result_object.forEach(function(campo, index){
	                        agregarOptions("#"+ municipio, campo.d_mnpio, campo.d_mnpio);
	                    });
	                    respuesta.colonias.result_object.forEach(function(campo, index){
	                        agregarOptions("#"+ colonia, campo.id_codigo_postal, campo.d_asenta);
	                    });
	                }
	            });
	        }else{
	            warning('Debe colocar al menos 5 caracteres para continuar.');
	        }
	    }
    }*/
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
    /*
        funcion que detecta la tecla enter para la busqueda de los codigos postales.
    */
    $("#codigo_postal_actualizar").keydown(function(e) {
        if(e.which == 13) {
            if (!busqueda){
                buscarCodigos(document.getElementById('codigo_postal_actualizar').value, 'estado_actualizar', 'ciudad_actualizar', 'municipio_actualizar', 'colonia_actualizar');
                busqueda = true;
            }
        }
    });
    $("#codigo_postal_actualizar").change(function() {
    		busqueda = false;
            buscarCodigos(document.getElementById('codigo_postal_actualizar').value, 'estado_actualizar', 'ciudad_actualizar', 'municipio_actualizar', 'colonia_actualizar');
    });
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
    /*
        funcion que pone en false la variable busqueda, esto es para que no se vaya
        a disparar dos veces la funcion buscarCodigo.
    */
    $("#codigo_postal_actualizar").focus(function() {
        busqueda = false;
    });

    
/* ------------------------------------------------------------------------------- */

 	$('.ima_error').on("error", function () {
 		var url=document.getElementById('ruta').value;
    	$( this ).attr( "src", url+"assets/cpanel/Usuarios/images/default.png" );
	});