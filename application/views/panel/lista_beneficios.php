<div class="container-fluid">
    <div class="row">
        <!-- Column -->
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Lista de Beneficios</h4>
                <div class="col-lg-6 d-none">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" id="buscador"  name="buscador" onkeyup="buscar(this.value)" placeholder="Titulo de Evento O Autor!">
                                                        <span class="input-group-btn">
                                                            <button class="btn btn-info" onclick="cargar_banner(); $('#buscador').val('')" type="button">Reiniciar!</button>
                                                        </span>
                                                    </div>
                                                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th width="" colspan=1>Titulo</th>
                                <th colspan=1>Acciones</th>
                                
                            </tr>
                        </thead>
                        <tbody id="tabla-entradas">
                            <tr>
                                <th></th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
        <!-- Column -->
    </div>
</div>


<div class="modal fade bs-example-modal-lg" id="modal_ver" tabindex="-1" role="dialog" aria-labelledby="Titulo" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-center" id="Titulo"></h4>
                
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
        <!-- CARGA DE VISTA BANNER -->
                   <div class="contenedor_imagen mr-auto ml-auto d-block" style="background: #70809054;">
                   <img class="w-100" src="" id="img_beneficio" alt="">
                   </div>
                   <p id="contenido_beneficio">
                   </p>
                   <p id="url_beneficios"></p>
        <!-- CARGA DE VISTA BANNER -->

           </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect text-left" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


<div class="modal fade bs-example-modal-lg" id="modal_editar" tabindex="-1" role="dialog" aria-labelledby="Titulo" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg" >
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-center" id="">Editar Banner</h4>
                
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <!--  FORM  -->
                <form class="form-material  row pl-2 pr-2" id="cargar_contenido">
                <!-- EDITAR BANNER -->
                <div class="form-group col-md-6 form-material">
                                            <div class="form-group">
                                                    <label>Titulo del Beneficio</label>
                                                    <input class="form-control" type="text" id="titulo" name="" >
                                                <label class="pt-4">Contenido del Beneficio  <span id="cantidad_c"></span></label>
                                                <textarea class="textarea_editor2 form-control" onkeyup="carga_texto_pre()" maxlength="100" placeholder="carapteres limitados (100)" spellcheck="true" required rows="8" id="contenido" name="contenido"></textarea>    
                                            </div>
                                        </div>
                               
                                            <div class="form-group col-md-6 form-material">
                                                <div class="form-group">
                                                    <label>Cargar logo</label>
                                                        <div class="dropzone" id="contenido_imagen_2"></div>
                                                        <input type="text" id="img-logo-nombre" name="img-logo-nombre"  hidden>
                                                        <input type="text" id="beneficio"  hidden >
                                                </div>
                                            </div>


                                            <div class="form-group col-md-12 form-material">
                                                <div class="form-group">
                                                    <label>Url</label>
                                                        <input class="form-control" type="text" id="url" >
                                                </div>
                                            </div>

                                    
                <!-- ESITAR BANNER -->   





                    <div class="mr-auto ml-auto mt-4">
                        <button type="submit" id="" class="btn waves-effect waves-light btn-block btn-info">Guardar</button>
                    </div>
              
                </form>
                <!--  FORM  -->
           </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect text-left" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


<script>



$(document).ready(function () {
/*

sessionStorage.setItem("imagen_banner", "");
sessionStorage.setItem("color-letra", "rgba(0, 0, 0, 0.0)");
sessionStorage.setItem("color-rgb", "rgba(0, 0, 0, 0.0)");
//control de colores

$("#color_f").change(function() {


    var opacity = $("#rango").val();
    var color = $("#color_f").val();
    var rgbaCol = 'rgba(' + parseInt(color.slice(-6, -4), 16) + ',' + parseInt(color.slice(-4, -2), 16) + ',' + parseInt(color.slice(-2), 16) + ',' + opacity + ')';
    sessionStorage.setItem("color-rgb", rgbaCol);
    $('.contendor_color').css('background-color', rgbaCol)
    $('#pre-banner1').css('background', 'linear-gradient('+sessionStorage.getItem("color-rgb")+', '+sessionStorage.getItem("color-rgb")+'), url('+sessionStorage.getItem("imagen_banner")+')');
    $('#pre-banner1').css('background-size', 'cover')
    $('#pre-banner1').css('height', '17rem')
})

$("#rango").change(function() {

    var opacity = $("#rango").val();
    var color = $("#color_f").val();
    var rgbaCol = 'rgba(' + parseInt(color.slice(-6, -4), 16) + ',' + parseInt(color.slice(-4, -2), 16) + ',' + parseInt(color.slice(-2), 16) + ',' + opacity + ')';
    sessionStorage.setItem("color-rgb", rgbaCol);
    $('.contendor_color').css('background-color', rgbaCol)
    $('#pre-banner1').css('background', 'linear-gradient('+sessionStorage.getItem("color-rgb")+', '+sessionStorage.getItem("color-rgb")+'), url('+sessionStorage.getItem("imagen_banner")+')');
    $('#pre-banner1').css('background-size', 'cover')
    $('#pre-banner1').css('height', '17rem')
})


        $("#color_fl").change(function() {

        var opacity = $("#rangol").val();
        var color = $("#color_fl").val();

        var rgbaCol = 'rgba(' + parseInt(color.slice(-6, -4), 16) + ',' + parseInt(color.slice(-4, -2), 16) + ',' + parseInt(color.slice(-2), 16) + ',' + opacity + ')';
        // COLOR FONDO
        $('.contendor_color_letra').css('background-color', rgbaCol)
        $('.color_letra').css('color', rgbaCol)
        sessionStorage.setItem("color-letra", rgbaCol);    
    })

        $("#rangol").change(function() {

        var opacity = $("#rangol").val();
        var color = $("#color_fl").val();

        var rgbaCol = 'rgba(' + parseInt(color.slice(-6, -4), 16) + ',' + parseInt(color.slice(-4, -2), 16) + ',' + parseInt(color.slice(-2), 16) + ',' + opacity + ')';
        console.log(rgbaCol);
        //COLOR LETRA
        $('.contendor_color_letra').css('background-color', rgbaCol)
        $('.color_letra').css('color', rgbaCol)

        sessionStorage.setItem("color-letra", rgbaCol);
        })


//control de colores

*/

});





// cargar lista
/*
function buscar(){
    $("#tabla-entradas").html("");
    url = "../Panel/listar_banner"
            var settings = {
       "async": true,
       "crossDomain": true,
       "url": url,
       "method": "POST",
       "headers": {
           "cache-control": "no-cache"
       },
       "data": {"buscador": $("#buscador").val()},
       "beforeSend": function () {
           //showLoader()
       }
   };

   // llena el select
   $.ajax(settings).done(function (response) {
    a = JSON.parse(response);

    if(a == 0){
    $("#tabla-entradas").append('<tr><td>No hay resultados</td></tr>');
}else{
     
     
  var datalista = a;
  console.log(datalista);

            //$("#tabla-entradas").html("No hay contenido de este tipo.")
            datalista.forEach(function (valor, indice, array) {
                
                var estado = valor.estado_visible;

if(estado == 1){
  boton_estado = "checked";
  accion = 0
}


if(estado == 0){
    boton_estado = "";
    accion = 1
}


                    
    if (valor.tipo == "Evento") {
                        $("#tabla-entradas").append('<tr>'+
                        '<td>' + valor.titulo + '</td>'+
                        '<td><button type="button" title="Editar" class="btn btn-secondary" data-toggle="modal" data-target="#modal_editar" data-object="'+valor._id.$id+'" onclick="editar_entrada($(this).data(\'object\'))"><i class="mdi mdi-tooltip-edit"></i></button>'+
                        '<button type="button" title="Ver" class="btn btn-secondary" data-toggle="modal" data-target="#modal_ver"  data-object="'+valor._id.$id+'" onclick="mostrar_banner($(this).data(\'object\'))"><i class="mdi mdi-eye"></i></button>'+
                        '<div class="switch" title="Contenido activo" style="display:inline-block;"><label><input type="checkbox" data-object="'+valor._id.$id+'" '+boton_estado+' onclick="cambiar_estado_beneficio($(this).data(\'object\'), '+accion+')" ><span class="lever switch-col-light-green"></span></label></td>'+'</tr>');
                    }
       })
}
        })
}

*/



    cargar_banner()
    function cargar_banner() {
    $("#tabla-entradas").html("");
        url = "../Panel/listar_b"
        var settings = {
            "async": true,
            "crossDomain": true,
            "url": url,
            "method": "GET",
            "headers": {
                "cache-control": "no-cache"
            },
            //"data": $('#cargar_contenido').serialize(),
            "beforeSend": function () {
                //showLoader()
            }
        };

        $.ajax(settings).done(function (response) {
            a = JSON.parse(response);
        var datalista = a;
            datalista.forEach(function (valor, indice, array) {
                var estado = valor.visible;
                 var id_objeto = ('"'+valor._id.$id+'"')
            if(estado == 1){
            boton_estado = "checked";
            accion = 0
            }
            if(estado == 0){
                boton_estado = "";
                accion = 1
            }
                    $("#tabla-entradas").append('<tr>'+
                    '<td>' + valor.titulo + '</td>'+
                    '<td><button type="button" title="Editar" class="btn btn-secondary" data-toggle="modal" data-target="#modal_editar" data-object="'+valor._id.$id+'" onclick="editar_banner($(this).data(\'object\'))"><i class="mdi mdi-tooltip-edit"></i></button>'+
                    '<button type="button" title="Ver" class="btn btn-secondary" data-toggle="modal" data-target="#modal_ver"  data-object="'+valor._id.$id+'" onclick="mostrar_banner($(this).data(\'object\'))"><i class="mdi mdi-eye"></i></button>'+
                    '<div class="switch" title="Contenido activo" style="display:inline-block;"><label><input type="checkbox" data-object="'+valor._id.$id+'" '+boton_estado+' onclick="cambiar_estado_beneficio($(this).data(\'object\'), '+accion+')" ><span class="lever switch-col-light-green"></span></label></td>'+'</tr>');
         
       })

        })
    }

function mostrar_banner(id){


            $.ajax({
                type: 'GET',
                url: 'cargar_beneficio/'+id,
                success: function (data) {
                    //location.reload(true);
                    a = JSON.parse(data);
                    datalista = a;
                    $("#img_beneficio").attr("src", '../assets/img/img-banner/'+datalista.imglogo)
                    $("#contenido_beneficio").html(datalista.parrafo)
                    $("#url_beneficios").text(datalista.url)
                  
                }
            });


}
      //  $(document).ready(function () {
           
            
            
     $('#contenido').wysihtml5( {
        "font-styles": false, //Font styling, e.g. h1, h2, etc. Default true
        "emphasis": true, //Italics, bold, etc. Default true
        "lists": false, //(Un)ordered lists, e.g. Bullets, Numbers. Default true
        "html": false, //Button which allows you to edit the generated HTML. Default false
        "link": false, //Button to insert a link. Default true
        "image": false, //Button to insert an image. Default true,
        "color": false, //Button to change color of font  
        "events": { 
            "change": function () { 
                carga_texto_pre()
                }}
    });

   //     });

// editar entrada


function editar_banner(id){


$.ajax({
    type: 'GET',
    url: 'cargar_beneficio/'+id,
    success: function (data) {
        //location.reload(true);
        a = JSON.parse(data);
        datalista = a;
        $('#contenido').data("wysihtml5").editor.setValue(datalista.parrafo);    
        $("#titulo").val(datalista.titulo);
        $("#contenido").val(datalista.parrafo)
        $("#img-logo-nombre").val(datalista.imglogo);
        $("#url").val(datalista.url);
        $("#beneficio").val(datalista._id.$id);
        }
});


}
/*
function cargar_imagen_logo(){
    console.log("se carga la imagen");
            $('#img-logo1').attr('src', dz2.files[0].dataURL)

            clearInterval(cargar_logo);
}


function cargar_imagen_fondo(){
    console.log("se carga la imagen de fondo");
            sessionStorage.setItem("imagen_banner", dz1.files[0].dataURL);
            $('#pre-banner1').css('height', '17rem')
            $('#pre-banner1').css('background', 'linear-gradient('+sessionStorage.getItem("color-rgb")+', '+sessionStorage.getItem("color-rgb")+'), url('+dz1.files[0].dataURL+')');
            $('#pre-banner1').css('background-size', 'cover')
            clearInterval(cargar_fondo);
}
*/
/*
function editar_entrada(id){

                $.ajax({
                type: 'GET',
                url: 'cargar_entrada/'+id,
                success: function (data) {
                    //location.reload(true);
                    a = JSON.parse(data);
                    datalista = a;
                    console.log(datalista._id.$id);
                    var lista_etiquetas = [];

                    if(a.etiquetas_id == undefined){
                        console.log("no tiene etiquetas")
                    }else{
                        console.log(a.etiquetas_id)
                        a.etiquetas_id.forEach(function (valor, indice, array) {
                         lista_etiquetas.push(valor);  
                    });
                    }
                    
                    $('#lista').val(lista_etiquetas);
                    $('#lista').trigger('change');
                    $(".select2").select2();
       
                    $("#contenido").html("")
                    $("#fecha_editar").val(datalista.fecha)
                    $("#titulo_editar").val(datalista.titulo)
                     $('#p1').data("wysihtml5").editor.setValue(datalista.contenido);             
                    $("#tipo_contenido").val(datalista.tipo);
                    $("#descripcion_editar").val(datalista.descripcion);
                    $("#autor_editar").val(datalista.autor);
                    $("#etiquetas_editar").val(datalista.etiquetas_id);
                    $("#entrada").val(datalista._id.$id);
                    $(".modal-dialog").css("max-width","70%");
                    nombre_imagen = datalista.imagen;

                       
                }
            });


}
*/



 


    Dropzone.autoDiscover = false;


/*
    function carga_texto_pre(){

        
        $(".color_letra").html($("#contenido").val())
    // CONTEO DE caracteres
        var cant_caracteres = $('#contenido').val().length;

        if(cant_caracteres > 440){
            $("#cantidad_c").text(cant_caracteres);
            $("#cantidad_c").css("color", "red");
        }else{
            $("#cantidad_c").text(cant_caracteres);
            $("#cantidad_c").css("color", "green");
        }
    }*/

Dropzone.autoDiscover = false;
var dz1 = new Dropzone(".dropzone", {
    //url: "http://localhost/pruebas/lux/test.php", /*url de prueba*/
    url: "../Panel/guardar_imagen_banner", /*define url*/
    method: "post", /*define metodo*/
    maxFiles: 1, /*solo un archivo por formulario*/
    dictDefaultMessage: "Arrastre <STRONG>aquí</STRONG> la imagen del contenido.", /*mensaje del formulario*/
    dictMaxFilesExceeded: "Solo se puede subir una imagen", /*mensaje si intenta subir varios*/
    paramName: "filenames", //nombre del elemento en $_FILES
    acceptedFiles: "image/jpg, image/png, image/jpeg, image/svg", /*formatos aceptados*/
    dictInvalidFileType: "No puedes subir este tipo de archivos", /*mensaje de formato erroneo*/
    autoProcessQueue: true, /*evita que se suba automaticamente*/
    previewsContainer: ".dropzone",
    init: function () {
        /*sustituye la imagen anterior para cargar una sola*/
        this.on('addedfile', function (file) {
            if (this.files.length > 1) {
                this.removeFile(this.files[0]);
            }
          //  cargar_fondo  = setInterval("cargar_imagen_fondo()",1000);
        });

        //llamada cuando finaliza la subida
        this.on('success', function (file, response) {
            $("#img-logo-nombre").val(response)
        });

        //muestra un error
        this.on('error', function (file, response) {
            //console.log(response);
            //.dz-error-message lo agregue yo manualmente
            $(file.previewElement).find('.dz-error-message').text(response);
        });
    }
});

    $('#cargar_contenido').on('submit', function (e) {

            console.log("cargar_contenido");
            e.preventDefault();
            //$("#modal_editar").modal('hide');
            if(dz1.files[0] == undefined){
                console.log("NO existe")
                swal({   
            title: "Desea mantener la imagen actual?",   
            text: "imagen de fondo o imagen de logo no sera actualizada.",   
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Si!",   
            cancelButtonText: "No,",   
            closeOnConfirm: false,   
            closeOnCancel: false 
        }, function(isConfirm){   
            if (isConfirm) {     
                actualizar_beneficio()
            } else {     
                swal("Actualizacion cancelada", "Estimado usuario por favor seleccione las imagenes", "error");  
            } 
        });
               return;
            }else{
                actualizar_beneficio()
            }
    })


   
 
function actualizar_beneficio() {


    var id = $("#beneficio").val();  
var titulo = $("#titulo").val();
var parrafo = $("#contenido").val();
var imglogo = $("#img-logo-nombre").val();
url = "../Panel/actualizar_beneficio";
var settings = {
   "async": true,
   "crossDomain": true,
   "url": url,
   "method": "POST",
   "headers": {
       "cache-control": "no-cache"
   },
   "data": {"titulo": titulo,
            "parrafo": parrafo,
            "imglogo": imglogo,
            "url": $("#url").val(),
            "visible": 1,
            "id": id},
   "beforeSend": function () {
       //showLoader()
   }
};

// llena el select
$.ajax(settings).done(function (response) {
   console.log(response);
     swal({
       title: "Felicidades,",
       text: "Contendo cargado con exito",
       type: "success",
       confirmButtonText: "Aceptar",
       closeOnConfirm: false
    }, function(isConfirm){   
        if (isConfirm) {     
            location.reload();
        } else {     
        }   
})
})
}


   function cambiar_estado_beneficio(id, accion) {
    
    url = "actualizar_estado_beneficio"
    var settings = {
        "async": true,
        "crossDomain": true,
        "url": url,
        "method": "POST",
        "headers": {
        "cache-control": "no-cache"
                    },
        "data": {"id": id,
                "accion": accion},
        "beforeSend": function () {
        }
       };

    $.ajax(settings).done(function (response) {
        swal("Cambios guardados con exito!", "", "success");
       })

   }












</script>