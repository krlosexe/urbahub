$(document).ready(function(){
	listar();
	actualizar_mi_correo();
});


/* ------------------------------------------------------------------------------- */
	/* 
		Funcion que muestra el cuadro3 para la consulta de la plaza.
	*/
	function listar(){
		var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
        $.ajax({
            url:url+'MiCorreo/buscar_mi_correo',
            type:'POST',
            dataType:'JSON',
            error: function (repuesta) {
                listar();              
            },
            success: function(respuesta){
                $("#alertas").html('');
                if(Object.keys(respuesta).length>0){
                	document.getElementById('servidor_smtp').value=respuesta[0].servidor_smtp;
                	document.getElementById('puerto').value=respuesta[0].puerto;
                	document.getElementById('usuario').value=respuesta[0].usuario;
                    document.getElementById('clave').value=respuesta[0].clave;
                    document.getElementById('nombre').value=respuesta[0].nombre;
                    document.getElementById('correo').value=respuesta[0].correo;
                    document.getElementById('smtp_auto').value=respuesta[0].smtp_auto;
                	///document.getElementById('id_mi_correo').value=respuesta[0].id_mi_correo;
                    //--Migracion mongo db
                    document.getElementById('id_mi_correo').value = respuesta[0]._id.$id;
                    //--------------------------
                }
            }
        });
	}
/* ------------------------------------------------------------------------------- */


/* ------------------------------------------------------------------------------- */
	/*
		Funcion que realiza el envio del formulario de registro
	*/
	function actualizar_mi_correo(){
		enviarFormularioCorreo("#form_correo_actualizar", 'MiCorreo/actualizar_mi_correo');
	}
/* ------------------------------------------------------------------------------- */
 function enviarFormularioCorreo(form, controlador, cuadro){
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
/*---------------------------------------------------------------------------*/