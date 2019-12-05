/* ------------------------------------------------------------------------------- */
    /*
        Variable para el idioma del datatable.
    */
    var idioma_espanol = {
        "sProcessing":     "Procesando...",
        "sLengthMenu":     "Mostrar _MENU_ registros",
        "sZeroRecords":    "No se encontraron resultados",
        "sEmptyTable":     "Ningún dato disponible en esta tabla",
        "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
        "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
        "sInfoPostFix":    "",
        "sSearch":         "Buscar:",
        "sUrl":            "",
        "sInfoThousands":  ",",
        "sLoadingRecords": "Cargando...",
        "oPaginate": {
            "sFirst":    "Primero",
            "sLast":     "Último",
            "sNext":     "Siguiente",
            "sPrevious": "Anterior"
        },
        "oAria": {
            "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
        }
    }
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
    /*
        Function que deshabilita las taclas en un input, se utiliza usando el
        evento onKeyUp.
    */
    function deshabilitarteclas(e){
        key=e.keyCode || e.which;
        teclado=String.fromCharCode(key);
        numeros="";
        especiales="8";//los numeros de esta linea son especiales y es para las flechas
        teclado_escpecial=false;
        for(var i in especiales)
            if (key==especiales[i])
                teclado_escpecial=true;
        if (numeros.indexOf(teclado)==-1 && !teclado_escpecial)
            return false;
    }
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
    /*
        Funcion que se encarga de aceptar solo numeros en un input, se utiliza usando el
        evento onKeyUp.
    */
    function solonumeros(e){
        key=e.keyCode || e.which;
        teclado=String.fromCharCode(key);
        numeros="1234567890.-";
        especiales="8-9-17-37-38-46";//los numeros de esta linea son especiales y es para las flechass
        teclado_escpecial=false;
        for(var i in especiales)
            if (key==especiales[i])
                teclado_escpecial=true;
        if (numeros.indexOf(teclado)==-1 && !teclado_escpecial)
            return false;
    }

    function solosnumerosyletras(e) {
         key=e.keyCode || e.which;
        teclado=String.fromCharCode(key);
        numeros="qwertyuiopasdfghjklñzxcvbnmQWERTYUIOPASDFGHJKLÑZXCVBNM1234567890-";
        especiales="8-9-17-37-38-46";//los numeros de esta linea son especiales y es para las flechass
        teclado_escpecial=false;
        for(var i in especiales)
            if (key==especiales[i])
                teclado_escpecial=true;
        if (numeros.indexOf(teclado)==-1 && !teclado_escpecial)
            return false;
    }
/* ------------------------------------------------------------------------------- */


/* ------------------------------------------------------------------------------- */
    /*
        Funcion que se encargbar de aceptar solo letras en un input, se utiliza 
        usando el evento onKeyUp.
    */
    function sololetras(e){
        key=e.keyCode || e.which;
        teclado=String.fromCharCode(key);
        numeros="qwertyuiopasdfghjklñzxcvbnmQWERTYUIOPASDFGHJKLÑZXCVBNM ";
        especiales="8-9-17-37-38-46";//los numeros de esta linea son especiales y es para las flechass
        teclado_escpecial=false;
        for(var i in especiales)
            if (key==especiales[i])
                teclado_escpecial=true;
        if (numeros.indexOf(teclado)==-1 && !teclado_escpecial)
            return false;
    }
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
    /* 
        Funcion para mostrar y ocultar los cuadros (div).
    */
    function cuadros(cuadroOcultar, cuadroMostrar){
        $(cuadroOcultar).slideUp("slow"); //oculta el cuadro.
        $(cuadroMostrar).slideDown("slow"); //muestra el cuadro.
    }
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
    /* 
        Funcion para regresar al listado.
    */
    function regresar(cuadroOcultar){
        listar(cuadroOcultar);
    }
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
    /*
        Funcion que envia los datos de los formularios.
    */
    function enviarFormulario(form, controlador, cuadro){
        $(form).submit(function(e){
            e.preventDefault(); //previene el comportamiento por defecto del formulario al darle click al input submit
            var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
            var formData=new FormData($(form)[0]); //obtiene todos los datos de los inputs del formulario pasado por parametros
            console.log(formData);
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
                        $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
                        mensajes('success', respuesta);
                        if(cuadro!="")
                            listar(cuadro);
                    }

                }

            });
        });
    }
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
    /*
        Funcion que muestra los mensajes al usuario.
        type = [default, primary, info, warning, success, danger]
    */
    function mensajes(type, msj){
        html='<div class="alert alert-'+type+'" role="alert">';
        html+='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
        html+=msj;
        html+='</div>';
        return $("#alertas").html(html).css("display", "block");
    }
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
    /*
        Functio que realiza el cambio del formato de fecha que trae el campo
        de la base de datos.
    */
    function cambiarFormatoFecha(date) {
      var info = date.split('-');
      //console.log(info)
      var fecha = info[2]+'-'+info[1]+'-'+info[0];
      return fecha;
    }


    function cambiarFormatoFechaTime(date) {
      var partes = date.split(' ');

      var info=partes[0].split('-');
      var fecha=info[2]+'-'+info[1]+'-'+info[0]+' '+partes[1];
      return fecha;
    }
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
    /*
        Funcion que se encarga eliminar un registro seleccionado.
    */
    function eliminarConfirmacion(controlador, id, title){
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
                        listar();
                        $("#checkall").prop("checked", false);
                        mensajes('success', respuesta);
                    }
                });
            } else {
                swal("Cancelado", "No se ha eliminado el registro", "error");
            }
        });
    }
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
    /*
        Funcion que se encarga de cambiar el status de un registro seleccionado.
        status -> valor (1, 2, n...)
        confirmButton -> activar, desactivar
    */
    function statusConfirmacion(controlador, id, status, title, confirmButton){
        swal({
            title: title,
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Si, "+confirmButton+"!",
            cancelButtonText: "No, Cancelar!",
            closeOnConfirm: true,
            closeOnCancel: false
        },
        function(isConfirm){
            if (isConfirm) {
                var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
                $.ajax({
                    url:url+controlador,
                    type: 'POST',
                    dataType: 'JSON',
                    data:{
                        id:id,
                        status:status
                    },
                    beforeSend: function(){
                        mensajes('info', '<span>Guardando cambios, espere por favor... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
                    },
                    error: function (repuesta) {
                        var errores=repuesta.responseText;
                        mensajes('danger', errores);
                    },
                    success: function(respuesta){
                        listar();
                        $("#checkall").prop("checked", false);
                        mensajes('success', respuesta);
                    }
                });
            } else {
                swal("Cancelado", "Proceso cancelado", "error");
            }
        });
    }
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
    /*
        Funcion anonima para seleccionar y deseleccionar los checkbox de las filas
    */
    $("#checkall").change(function(){
        $(".checkitem").prop("checked", $(this).prop("checked"));
    });
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
    /*
        Funcion anonima captar los value de los checkbox seleccionamos
    */
    function eliminarMultiple(controlador){
        var id=$(".checkitem:checked").map(function(){
            return $(this).val();
        }).get();
        if(Object.keys(id).length>0)
            eliminarConfirmacion(controlador, id, "¿Esta seguro de eliminar los registros seleccionados?");
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

/* ------------------------------------------------------------------------------- */
    /*
        Funcion anonima captar los value de los checkbox seleccionamos
    */
    function statusMultiple(controlador, status, confirmButton){
        var id=$(".checkitem:checked").map(function(){
            return $(this).val();
        }).get().join(' ');
        if(Object.keys(id).length>0)
            statusConfirmacion(controlador, id, status, "¿Esta seguro de "+confirmButton+" los registros seleccionados?", confirmButton);
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

/* ------------------------------------------------------------------------------- */
    /*
        Funcion para los input para telefonos
    */
    function telefonoInput(input){
        $(input).inputmask('+99 (999) 999-99-99', { placeholder: '+__ (___) ___-__-__' });
    }
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
    /*
        Funcion para los input para telefonos
    */
    function porcentajeInput(input){
        $(input).inputmask('decimal', { rightAlign: true, groupSeparator: ',', autoGroup: true, radixPoint: '.' });
    }
/* ------------------------------------------------------------------------------- */
function decimalesInput(input){
        //$(input).inputmask('decimal',"(.999){+|1},00", { rightAlign: true, groupSeparator: ',', autoGroup: true, radixPoint: '.' });
        //$(input).inputmask('decimal',{ rightAlign: true, groupSeparator: ',', autoGroup: true, radixPoint: '.' });
        $(input).inputmask('decimal',{
              radixPoint:".",
              groupSeparator: ",",
              autoGroup: true,
              digits: 2,
              digitsOptional: false,
              placeholder: '0,000.00',
              rightAlign: true,
        })    
}   
 
/* ------------------------------------------------------------------------------- */
    /*
        Funcion para limpiar los selects
    */
    function eliminarOptions(select){
        $('#' + select).children('option:not(:first)').remove();
    }
/* ------------------------------------------------------------------------------- */
    function eliminarOptions2(select){
        $('#' + select).children().remove();
    }
/* ------------------------------------------------------------------------------- */
/* ------------------------------------------------------------------------------- */
    function eliminarOptions3(select){
        $('#' + select).empty();
    }
/* ------------------------------------------------------------------------------- */

    /*
        Funcion para mostrar un sweetalert warning
    */
    function warning(title){
        swal({
            title: title,
            type: "warning",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Aceptar!",
            closeOnConfirm: true
        });
    }
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
    /*
        Funcion para agregar options a los selects
    */
    function agregarOptions(select, value, text){
        $(select).append($('<option>', { 
            value: value,
            text : text
        }));
        $(select + ' :nth-child(2)').prop('selected', true);
    }
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
    /*
        Funcion para agregar options a los selects
    */
    function elegirFecha(date){
        $(date).datetimepicker({
            format: 'D-MM-YYYY'
        });
    }
    function elegirFecha_Cumple(date){
        $(date).datetimepicker({
            format: 'D-MM-YYYY',
            maxDate: moment(),
        });
    }
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
    //Función para validar una CURP
    function curpValida(curp) {
        var re = /^([A-Z][AEIOUX][A-Z]{2}\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])[HM](?:AS|B[CS]|C[CLMSH]|D[FG]|G[TR]|HG|JC|M[CNS]|N[ETL]|OC|PL|Q[TR]|S[PLR]|T[CSL]|VZ|YN|ZS)[B-DF-HJ-NP-TV-Z]{3}[A-Z\d])(\d)$/,
        validado = curp.match(re);
        if (!validado)  //Coincide con el formato general?
            return false;
        //Validar que coincida el dígito verificador
        function digitoVerificador(curp17) {
            //Fuente https://consultas.curp.gob.mx/CurpSP/
            var diccionario  = "0123456789ABCDEFGHIJKLMNÑOPQRSTUVWXYZ",
                lngSuma      = 0.0,
                lngDigito    = 0.0;
            for(var i=0; i<17; i++)
                lngSuma = lngSuma + diccionario.indexOf(curp17.charAt(i)) * (18 - i);
            lngDigito = 10 - lngSuma % 10;
            if (lngDigito == 10) return 0;
            return lngDigito;
        }
        if (validado[2] != digitoVerificador(validado[1])) 
            return false;    
        return true; //Validado
    }
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
    //Handler para el evento cuando cambia el input
    //Lleva la CURP a mayúsculas para validarlo
    function validarInputCurp(input) {
        var curp = input.value.toUpperCase(),
            resultado = $("#validCurp"),
            valido = "No válido"; 
        if (curpValida(curp)) { // -> Acá se comprueba
            valido = "Válido";
            $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
            resultado.removeClass('focused error');
            $(".curpError").html('');
        } else {
            $('input[type="submit"]').attr('disabled','disabled'); //desactiva el input submit
            resultado.addClass('focused error');
            $(".curpError").html('El C.U.R.P. ingresado es incorrecto');
        }
    }
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
    // funcion para validar correo
    function validarCorreo(validar, confirmar, error) {
        var correo1 = $(validar).val(),
            correo2 = $(confirmar).val(),
            resultado = $(error);
        if(correo1==correo2) {
            $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
            resultado.removeClass('focused error');
            $(".correoError").html('');
        } else {
            $('input[type="submit"]').attr('disabled','disabled'); //desactiva el input submit
            resultado.addClass('focused error');
            $(".correoError").html('Los correos no coinciden');
        }
    }
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
    // funcion para validar correo
    function validarClave(validar, confirmar, error) {
        var clave1 = $(validar).val(),
            clave2 = $(confirmar).val(),
            resultado = $(error);
        if(clave1==clave2) {
            $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
            resultado.removeClass('focused error');
            $(".claveError").html('');
        } else {
            $('input[type="submit"]').attr('disabled','disabled'); //desactiva el input submit
            resultado.addClass('focused error');
            $(".claveError").html('Las contraseñas no coinciden');
        }
    }
/* ------------------------------------------------------------------------------- */


/* ------------------------------------------------------------------------------- */
    /*
        Funcion que muestra la vista previa de la imagen y valida el tipo del file
    */
    function readURL(input, img, avatar){
        var val = $(avatar).val();
        switch(val.substring(val.lastIndexOf('.') + 1).toLowerCase()){
            case 'gif': case 'jpg': case 'jpeg': case 'png':
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $(img).attr('src', e.target.result);
                    };
                    reader.readAsDataURL(input.files[0]);
                }
                break;
            default:
                $(avatar).val('');
                swal({
                    title: "El archivo seleccionado no es una imagen.",
                    type: "error",
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Aceptar!",
                    closeOnConfirm: true
                });
                break;
        }
    }
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
    /*
        Funcion que muestra un loading.
    */
    function loading(){
        var loading = '<div class="demo-preloader" style="text-align: center;"><div class="preloader pl-size-xl">';
        loading += '<div class="spinner-layer pl-blue"><div class="circle-clipper left">';
        loading += '<div class="circle"></div></div><div class="circle-clipper right">';
        loading += '<div class="circle"></div></div></div></div></div>';
        return loading;
    }
/* ------------------------------------------------------------------------------- */


/* ------------------------------------------------------------------------------- */
  //Función para validar un RFC
// Devuelve el RFC sin espacios ni guiones si es correcto
// Devuelve false si es inválido
// (debe estar en mayúsculas, guiones y espacios intermedios opcionales)
function rfcValido(rfc, aceptarGenerico = true) {
    const re       = /^([A-ZÑ&]{3,4}) ?(?:- ?)?(\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])) ?(?:- ?)?([A-Z\d]{2})([A\d])$/;
    var   validado = rfc.match(re);

    if (!validado)  //Coincide con el formato general del regex?
        return false;

    //Separar el dígito verificador del resto del RFC
    const digitoVerificador = validado.pop(),
          rfcSinDigito      = validado.slice(1).join(''),
          len               = rfcSinDigito.length,

    //Obtener el digito esperado
          diccionario       = "0123456789ABCDEFGHIJKLMN&OPQRSTUVWXYZ Ñ",
          indice            = len + 1;
    var   suma,
          digitoEsperado;

    if (len == 12) suma = 0
    else suma = 481; //Ajuste para persona moral

    for(var i=0; i<len; i++)
        suma += diccionario.indexOf(rfcSinDigito.charAt(i)) * (indice - i);
    digitoEsperado = 11 - suma % 11;
    if (digitoEsperado == 11) digitoEsperado = 0;
    else if (digitoEsperado == 10) digitoEsperado = "A";

    //El dígito verificador coincide con el esperado?
    // o es un RFC Genérico (ventas a público general)?
    if ((digitoVerificador != digitoEsperado)
     && (!aceptarGenerico || rfcSinDigito + digitoVerificador != "XAXX010101000"))
        return false;
    else if (!aceptarGenerico && rfcSinDigito + digitoVerificador == "XEXX010101000")
        return false;
    return rfcSinDigito + digitoVerificador;
}


//Handler para el evento cuando cambia el input
// -Lleva la RFC a mayúsculas para validarlo
// -Elimina los espacios que pueda tener antes o después
function validarInputRfc(input) {
    var rfc         = input.value.trim().toUpperCase(),
        resultado   = document.getElementById("resultado"),
        valido;
    var rfcCorrecto = rfcValido(rfc);   // ⬅️ Acá se comprueba

    if (rfcCorrecto) {
        valido = "";
        resultado.classList.add("ok");
        $("#validCurp").removeClass("has-error");
        $(".save").removeAttr("disabled","disabled");
    } else {
        $("#validCurp").addClass("has-error");
        valido = "El RFC ingresado no es  válido"
        $(".save").attr("disabled","disabled");
        resultado.classList.remove("ok");
    }
       
    resultado.innerText = valido;
}


function validarInputRfc_2(input) {
    var rfc         = input.value.trim().toUpperCase(),
        resultado   = document.getElementById("resultado_2"),
        valido;
    
    var rfcCorrecto = rfcValido(rfc);   // ⬅️ Acá se comprueba
        
    if (rfcCorrecto) {
        valido = "";
        resultado.classList.add("ok");
        $("#validCurp_2").removeClass("has-error");
        $(".save2").removeAttr("disabled","disabled");
    } else {
        $("#validCurp_2").addClass("has-error");
        valido = "El RFC ingresado no es  válido"
        $(".save2").attr("disabled","disabled");
        resultado.classList.remove("ok");
    }
       
    resultado.innerText = valido;
}

/* ------------------------------------------------------------------------------- */




$("#send").click(function() {
    var comprobar_reg = false;
    var comprobar_reg_string = "";
    var comprobar_reg_tab = "";
    var comprobar_reg_campo = "";
    var obj1 = ["0", "1"];
    for (index = 0; index < obj1.length; ++index) {
      item1 = obj1[index];
      $('.tab_content'+item1+' div.valid-required').each(function(){
        if($(this).find(".form-control").prop('required')){
          if($(this).find(".form-control").val() == ""){
            if(!comprobar_reg){
              comprobar_reg = true;
            //  comprobar_reg_string = $(this).find(".control-label-left").html().replace('<span class="required"></span>', "").replace('<span class="required">*</span>', "");
              comprobar_reg_tab = item1;
              comprobar_reg_campo = $(this).find(".form-control").prop("id");
            }
          }
        }
      });
    }
    var obj2 = ["11", "2", "3", "4", "5", "6"];
    for (index = 0; index < obj2.length; ++index) {
      item1 = obj2[index];
      var contador_req = 0;
      var temp_comprobar_reg_string = "";
      var temp_comprobar_reg_campo = "";
      $('.tab_content'+item1+' div.valid-required').each(function(){
        if($(this).find(".form-control").prop('required')){
          if($(this).find(".form-control").val() == "" || $(this).find(".form-control").val() == "0" || $(this).find(".form-control").val() == "accio"){
            if(!comprobar_reg){
              if(comprobar_reg_campo == ""){
               // temp_comprobar_reg_string = $(this).find(".control-label-left").html().replace('<span class="required"></span>', "").replace('<span class="required">*</span>', "");
                comprobar_reg_campo = $(this).find(".form-control").prop("id");
              }
            }
          }
          else{
            contador_req++;
          }
        }
      });

      if(!comprobar_reg){
        if(contador_req > 0){
          if(comprobar_reg_campo != ""){
            comprobar_reg = true;
            comprobar_reg_string = temp_comprobar_reg_string;
            comprobar_reg_tab = item1;
            comprobar_reg_campo = comprobar_reg_campo;
          }
        }
        else{
          $('.tab_content'+item1+' div.valid-required').each(function(){
            $(this).find(".form-control").removeAttr("required");
          });
        }
      }
    }
    

    if(comprobar_reg){
      $('.tab_content0').removeClass("active in");$('#tab0').removeClass("active");
      $('.tab_content1').removeClass("active in");$('#tab1').removeClass("active");
      $('.tab_content11').removeClass("active in");$('#tab11').removeClass("active");
      $('.tab_content2').removeClass("active in");$('#tab2').removeClass("active");
      $('.tab_content3').removeClass("active in");$('#tab3').removeClass("active");
      $('.tab_content4').removeClass("active in");$('#tab4').removeClass("active");
      $('.tab_content5').removeClass("active in");$('#tab5').removeClass("active");
      $('.tab_content6').removeClass("active in");$('#tab6').removeClass("active");
      $('.tab_content'+comprobar_reg_tab).addClass("active in");$('#tab'+comprobar_reg_tab).addClass("active");
      //$('#'+comprobar_reg_campo).focus();
      //alert("El campo "+comprobar_reg_string+" es obligatorio.");
    }
  });

function valida(e){
    tecla = (document.all) ? e.keyCode : e.which;      
    if(tecla == 8){
        return;
    }else{
        patron =/^([0-9])*[.]?[0-9]*$/;
        tecla_final = String.fromCharCode(tecla);
        return patron.test(tecla_final);
    }
}

var isNumeric = function(obj){
  return !Array.isArray( obj ) && (obj - parseFloat( obj ) + 1) >= 0;
}
//--------------------------------------------------
//Funcion para la busqueda de codigo
function buscarCodigos(codigo, estado="estado", ciudad="ciudad",municipio="municipio",colonia="colonia"){
        if (!busqueda){
            busqueda = true;
            eliminarOptions2(estado);
            eliminarOptions2(ciudad);
            eliminarOptions2(municipio);
            eliminarOptions2(colonia);
            if(codigo.length>4){
                var url=document.getElementById('ruta').value;
                $.ajax({
                    url:url+'Usuarios/buscar_codigos',
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
                        console.log(respuesta);
                        if(respuesta){
                            $("#alertas").html('');
                            respuesta.estados.forEach(function(campo, index){
                                agregarOptions('#'+estado, campo.d_estado, campo.d_estado);
                            });
                            respuesta.ciudades.forEach(function(campo, index){
                                if(campo.d_ciudad!=""){
                                    agregarOptions('#'+ciudad, campo.d_ciudad, campo.d_ciudad);
                                    //$("#"+ciudad).css('border-color', '#ccc');
                                }else{
                                    agregarOptions('#'+ciudad, "N/A", "NO APLICA");
                                    $("#"+ciudad+" option[value='N/A']").attr("selected","selected");
                                    //$("#"+ciudad).css('border-color', '#a94442');
                                }
                            });
                            respuesta.municipios.forEach(function(campo, index){
                                agregarOptions('#'+municipio, campo.d_mnpio, campo.d_mnpio);
                            });
                            respuesta.colonias.forEach(function(campo, index){
                                agregarOptions('#'+colonia, campo.id_codigo_postal, campo.d_asenta);
                            });
                        }else{
                            mensajes('danger', '<span>No se encontraron datos para este código!</span>');         
                        }
                        //---
                    }
                });
            }else{
                warning('Debe colocar al menos 5 caracteres para continuar.');
            }
        }
    }
//--------------------------------------------------
function inNum(monto) {
    var cantidad           = monto;
    var myNumeral          = numeral(cantidad);
    return myNumeral.value();
}

function validPhone(input) {
    var regex  = $(input).val();

    var output = inNum(regex);
    $("#len_num").val(output);
    var count = $("#len_num").val().length;

    if (count < 13) {
        $(input).siblings('.emailError').html('Debe tener 12 caracteres');
        $(".save-cliente").attr("disabled","disabled");
    }else{
        $(input).siblings('.emailError').html('');
        $(".save-cliente").removeAttr("disabled","disabled");
    }

    if (count == 0) {
        $(input).siblings('.emailError').html('');
        $(".save-cliente").removeAttr("disabled","disabled");
    }
    
}
function validEmail(input) {
    var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;

    if (regex.test($(input).val().trim())) {
        $(input).siblings('.emailError').html('');
        $(".save-cliente").removeAttr("disabled","disabled");
    } else {
        $(input).siblings('.emailError').html('Email Invalido');
        $(".save-cliente").attr("disabled","disabled");
    }
    //--
    if($(input).val()==""){
       $(input).siblings('.emailError').html(''); 
       $(".save-cliente").removeAttr("disabled","disabled"); 
    }
}


function number_format(amount, decimals) {   
 amount += ''; // por si pasan un numero en vez de un string
 amount = parseFloat(amount.replace(/[^0-9\.]/g, ''));
 // elimino cualquier cosa que no sea numero o punto 
  decimals = decimals || 0; // por si la variable no fue fue pasada  
  // si no es un numero o es igual a cero retorno el mismo cero 
  if (isNaN(amount) || amount === 0)      
     return parseFloat(0).toFixed(decimals);     
      // si es mayor o menor que cero retorno el valor formateado como numero   
    amount = '' + amount.toFixed(decimals);   
    var amount_parts = amount.split('.'),    
    regexp = /(\d+)(\d{3})/;       
      while (regexp.test(amount_parts[0]))  
      amount_parts[0] = amount_parts[0].replace(regexp, '$1' + ',' + '$2'); 
       return amount_parts.join('.');  
}