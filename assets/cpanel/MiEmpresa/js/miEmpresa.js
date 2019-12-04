$(document).ready(function(){
    telefonoInput('.telefono');
	listar();
	actualizar_mi_empresa();
    var busqueda = false;
});


/* ------------------------------------------------------------------------------- */
	/* 
		Funcion que muestra el cuadro3 para la consulta de la plaza.
	*/
	function listar(){
		var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
        $.ajax({
            url:url+'MiEmpresa/buscar_mi_empresa',
            type:'POST',
            dataType:'JSON',
            error: function (repuesta) {
                listar();              
            },
            success: function(respuesta){
                $("#alertas").html('');
                eliminarOptions2('estado');
                eliminarOptions2('ciudad');
                eliminarOptions2('municipio');
                eliminarOptions2('colonia');
                if(Object.keys(respuesta.empresa).length>0){
                    respuesta.estados.forEach(function(campo, index){
                        agregarOptions('#estado', campo.d_estado, campo.d_estado);
                    });
                    respuesta.ciudades.forEach(function(campo, index){
                        if(campo.d_ciudad!=""){
                            agregarOptions('#ciudad', campo.d_ciudad, campo.d_ciudad);
                            //$("#ciudad").css('border-color', '#ccc');
                        }else{
                            agregarOptions('#ciudad', "N/A", "NO APLICA");
                            //$("#ciudad").css('border-color', '#a94442');
                        }
                    });
                    respuesta.municipios.forEach(function(campo, index){
                        agregarOptions('#municipio', campo.d_mnpio, campo.d_mnpio);
                    });
                    respuesta.colonias.forEach(function(campo, index){
                        agregarOptions('#colonia', campo.id_codigo_postal, campo.d_asenta);
                    });
                    document.getElementById('nombre_mi_empresa').value=respuesta.empresa[0].nombre_mi_empresa;
                    document.getElementById('rfc_mi_empresa').value=respuesta.empresa[0].rfc_mi_empresa;
                    //document.getElementById('id_mi_empresa').value=respuesta.empresa[0].id_mi_empresa;
                    //--Migracion mongo db
                    document.getElementById('id_mi_empresa').value = respuesta.empresa[0]._id.$id;
                    console.log(respuesta.empresa[0]);
                    console.log(respuesta.empresa[0]._id.$id);
                    //--------------------------
                    document.getElementById('telefono_principal_contacto').value=respuesta.empresa[0].telefono_principal_contacto;
                    document.getElementById('correo_opcional_contacto').value=respuesta.empresa[0].correo_opcional_contacto;
                    //document.getElementById('direccion_contacto').value=respuesta.empresa[0].direccion_contacto;
                    document.getElementById('calle_contacto').value=respuesta.empresa[0].calle_contacto;
                    document.getElementById('exterior_contacto').value=respuesta.empresa[0].exterior_contacto;
                    document.getElementById('interior_contacto').value=respuesta.empresa[0].interior_contacto;
                    document.getElementById('id_contacto').value=respuesta.empresa[0].id_contacto.$oid;
                    console.log(respuesta.empresa[0].id_contacto.$oid);
                    document.getElementById('codigo_postal').value=respuesta.empresa[0].d_codigo;
                    $("#estado option[value='"+respuesta.empresa[0].d_estado+"']").attr("selected","selected");
                    if(respuesta.empresa[0].d_ciudad!=""){
                        $("#ciudad option[value='"+respuesta.empresa[0].d_ciudad+"']").attr("selected","selected");
                    }else{
                        $("#ciudad option[value='N/A']").attr("selected","selected");
                    }
                    $("#municipio option[value='"+respuesta.empresa[0].d_mnpio+"']").attr("selected","selected");
                    $("#colonia option[value='"+respuesta.empresa[0].id_codigo_postal+"']").attr("selected","selected");
                }
            }
        });
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que realiza el envio del formulario de registro
	*/
	function actualizar_mi_empresa(){
		enviarFormularioEmpresa("#form_empresa_actualizar", 'MiEmpresa/actualizar_mi_empresa');
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
    function enviarFormularioEmpresa(form, controlador, cuadro){
        $(form).submit(function(e){
            e.preventDefault(); //previene el comportamiento por defecto del formulario al darle click al input submit
            var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
            var formData=new FormData($(form)[0]); //obtiene todos los datos de los inputs del formulario pasado por parametros
            var method = $(this).attr('method'); //obtiene el method del formulario
            $('input[type="submit"]').attr('disabled','disabled'); //desactiva el input submit
            $.ajax({
                url:url+controlador,
                type:method,
                dataType:'JSON',
                data:formData,
                cache:false,
                contentType:false,
                processData:false,
                beforeSend: function(){
                    mensajes('info', '<span>Guardando datos, espere por favor... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
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

                    if (respuesta.success == false) {
                         mensajes('danger', respuesta.message);
                         $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
                    }else{
                        $('html, body').animate({scrollTop:0}, 'slow');//Sube al top de la página...
                        $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
                        mensajes('success', respuesta);
                        
                    }

                }

            });
        });
    }
/* ------------------------------------------------------------------------------- */
    /*
        funcion que detecta la tecla enter para la busqueda de los codigos postales.
    */
    $("#codigo_postal").keydown(function(e) {
        if(e.which == 13) {
            if (!busqueda){
                buscarCodigos(document.getElementById('codigo_postal').value);
                busqueda = true;
            }

        }
    });
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
    /*
        funcion que pone en false la variable busqueda, esto es para que no se vaya
        a disparar dos veces la funcion buscarCodigo.
    */
    $("#codigo_postal").focus(function() {
        busqueda = false;
    });
/* ------------------------------------------------------------------------------- */