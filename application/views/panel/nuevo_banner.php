<div class="container">
<div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Nuevo Banner</h4>
                                <form class="row" id="cargar_contenido">
                                        <div class="form-group col-md-6 form-material">
                                            <div class="form-group">
                                                    <label>Titulo del Banner</label>
                                                    <input class="form-control" type="text" id="titulo" name="" >
                                                <label class="pt-4">Contenido del Banner  <span id="cantidad_c"></span></label>
                                                <textarea class="textarea_editor2 form-control" onkeyup="carga_texto_pre()" maxlength="440" placeholder="carapteres limitados (440)" spellcheck="true" required rows="8" id="contenido" name="contenido"></textarea>    
                                            </div>
                                        </div>
                               
                                            <div class="form-group col-md-3 form-material">
                                            <div class="form-group">
                                                <label>Cargar de Fondo</label>
                                                    <div class="dropzone" id="contenido_imagen"></div>
                                                    <input type="text" id="img-fondo-nombre" name="#img-fondo-nombre" hidden>
                                            </div>
                                            </div>

                                            <div class="form-group col-md-3 form-material">
                                                <div class="form-group">
                                                    <label>Cargar logo</label>
                                                        <div class="dropzone" id="contenido_imagen_2"></div>
                                                        <input type="text" id="img-logo-nombre" name="#img-logo-nombre" hidden >
                                                </div>
                                                </div>

                                        <div class="form-group col-md-3 form-material">
                                            <div class="form-group">
                                                <label>Color</label>
                                                <input type="color" id="color_f" class="form-control">
                                                <label>Transparencia</label>
                                                <input type="range" class="form-control" id="rango" min="0" max="1" step="0.01">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-3 form-material">
                                                <div class="form-group">
                                                    <label>Color</label>
                                                    <input type="color"  id="color_fl" class="form-control">
                                                    <label>Transparencia</label>
                                                    <input type="range" class="form-control" id="rangol" min="0" max="1" step="0.01">
                                                </div>
                                            </div>

                                        <div class="form-group col-md-3 form-material">
                                            <div class="form-group">
                                                <label>Color de Fondo</label>
                                                <div class="contendor_color" style="width: 100%; min-height: 2rem">
                                                </div>
                                        </div>
                                        </div>


                                        <div class="form-group col-md-3 form-material">
                                                <div class="form-group">
                                                    <label>Color del Texto</label>
                                                    <div class="contendor_color_letra" style="width: 100%; min-height: 2rem">
                                                    </div>
                                        </div>
                                        </div>

                                        <div class="form-group col-md-12 mr-auto ml-auto d-block form-material">
                                                <label>Pre-Visualisacíon</label>
                                            <div class="form-group border">
                                               
                                               
                                               
                                               <!-- PRE -->
                                                <div class="" id="pre-banner" style="width: 100%; min-height: 2rem; background-size: cover; overflow: hidden">
                                                    <div class="row">
                                                        <div class="col-lg-6 p-2">
                                                            
                                                            <img src="" id="img-logo" style="width: 13rem; padding-top: 4.5%;"  class="mr-auto ml-auto d-block" alt="imagen">

                                                        </div>
                                                        <div class="col-lg-6 p-2"> 
                                                            <p class="color_letra text-justify" style="padding-top: 4.5%; padding-right: 5%;"></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- PRE -->


                                        </div>
                                    <div class="mr-auto ml-auto mt-4">
                                        <button type="submit" id="" class="btn waves-effect waves-light btn-block btn-info">Guardar</button>
                                    </div>
                              
                                </form>
                            </div>
                        </div>

                      
                    </div>
</div>
</div>



<script>

$(document).ready(function () {

    sessionStorage.setItem("color-rgb", "rgba(0, 0, 0, 0.0)");
    sessionStorage.setItem("imagen_banner", "");
    sessionStorage.setItem("color-letra", "rgba(0, 0, 0, 0.0)");
    //control de colores
    
    $("#color_f").change(function() {


        var opacity = $("#rango").val();
        var color = $("#color_f").val();
        var rgbaCol = 'rgba(' + parseInt(color.slice(-6, -4), 16) + ',' + parseInt(color.slice(-4, -2), 16) + ',' + parseInt(color.slice(-2), 16) + ',' + opacity + ')';
        sessionStorage.setItem("color-rgb", rgbaCol);
        $('.contendor_color').css('background-color', rgbaCol)
        $('#pre-banner').css('background', 'linear-gradient('+sessionStorage.getItem("color-rgb")+', '+sessionStorage.getItem("color-rgb")+'), url('+sessionStorage.getItem("imagen_banner")+')');
        $('#pre-banner').css('background-size', 'cover')
        $('#pre-banner').css('height', '17rem')
    })

    $("#rango").change(function() {

        var opacity = $("#rango").val();
        var color = $("#color_f").val();
        var rgbaCol = 'rgba(' + parseInt(color.slice(-6, -4), 16) + ',' + parseInt(color.slice(-4, -2), 16) + ',' + parseInt(color.slice(-2), 16) + ',' + opacity + ')';
        sessionStorage.setItem("color-rgb", rgbaCol);
        $('.contendor_color').css('background-color', rgbaCol)
        $('#pre-banner').css('background', 'linear-gradient('+sessionStorage.getItem("color-rgb")+', '+sessionStorage.getItem("color-rgb")+'), url('+sessionStorage.getItem("imagen_banner")+')');
        $('#pre-banner').css('background-size', 'cover')
        $('#pre-banner').css('height', '17rem')
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





   
$('#contenido').wysihtml5({
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

        });

    // Date Picker

//cantidad_parrafos(0);

function carga_texto_pre(){
    $('.color_letra').html(($('#contenido').val()));
    // CONTEO DE caracteres
        var cant_caracteres = $('#contenido').val().length;

        if(cant_caracteres > 440){
            $("#cantidad_c").text(cant_caracteres);
            $("#cantidad_c").css("color", "red");
        }else{
            $("#cantidad_c").text(cant_caracteres);
            $("#cantidad_c").css("color", "green");
        }
}

Dropzone.autoDiscover = false;
var dz1 = new Dropzone(".dropzone", {
    //url: "http://localhost/pruebas/lux/test.php", /*url de prueba*/
    url: "../Panel/guardar_imagen_banner", /*define url*/
    method: "post", /*define metodo*/
    maxFiles: 1, /*solo un archivo por formulario*/
    dictDefaultMessage: "Arrastre <STRONG>aquí</STRONG> la imagen del contenido.", /*mensaje del formulario*/
    dictMaxFilesExceeded: "Solo se puede subir una imagen", /*mensaje si intenta subir varios*/
    paramName: "filenames", //nombre del elemento en $_FILES
    acceptedFiles: "image/jpg, image/png, image/jpeg", /*formatos aceptados*/
    dictInvalidFileType: "No puedes subir este tipo de archivos", /*mensaje de formato erroneo*/
    autoProcessQueue: false, /*evita que se suba automaticamente*/
    previewsContainer: ".dropzone",
    init: function () {
        /*sustituye la imagen anterior para cargar una sola*/
        this.on('addedfile', function (file) {
            if (this.files.length > 1) {
                this.removeFile(this.files[0]);
            }
            cargar_fondo  = setInterval("cargar_imagen_fondo()",1000);
        });

        //llamada cuando finaliza la subida
        this.on('success', function (file, response) {
            $("#img-fondo-nombre").val(response)
            dz2.processQueue()
        });

        //muestra un error
        this.on('error', function (file, response) {
            //console.log(response);
            //.dz-error-message lo agregue yo manualmente
            $(file.previewElement).find('.dz-error-message').text(response);
        });
    }
});



var dz2 = new Dropzone("#contenido_imagen_2", {
    //url: "http://localhost/pruebas/lux/test.php", /*url de prueba*/
    url: "../Panel/guardar_imagen_banner", /*define url*/
    method: "post", /*define metodo*/
    maxFiles: 1, /*solo un archivo por formulario*/
    dictDefaultMessage: "Arrastre <STRONG>aquí</STRONG> la imagen del logo.", /*mensaje del formulario*/
    dictMaxFilesExceeded: "Solo se puede subir una imagen", /*mensaje si intenta subir varios*/
    paramName: "filenames", //nombre del elemento en $_FILES
    acceptedFiles: "image/jpg, image/png, image/jpeg, image/svg", /*formatos aceptados*/
    dictInvalidFileType: "No puedes subir este tipo de archivos", /*mensaje de formato erroneo*/
    autoProcessQueue: false, /*evita que se suba automaticamente*/
    previewsContainer: "#contenido_imagen_2",
    init: function () {
        /*sustituye la imagen anterior para cargar una sola*/
        this.on('addedfile', function (file) {
            if (this.files.length > 1) {
                this.removeFile(this.files[0]);
            }
             cargar_logo  = setInterval("cargar_imagen_logo()",1000);
        });

        //llamada cuando finaliza la subida
        this.on('success', function (file, response) {
            $("#img-logo-nombre").val(response)
            crear_banner()
        });

        //muestra un error
        this.on('error', function (file, response) {
            //console.log(response);
            //.dz-error-message lo agregue yo manualmente
            $(file.previewElement).find('.dz-error-message').text(response);
        });
    }
});





function cargar_imagen_logo(){
    console.log("se carga la imagen");
            $('#img-logo').attr('src', dz2.files[0].dataURL)

            clearInterval(cargar_logo);
}


function cargar_imagen_fondo(){
    console.log("se carga la imagen de fondo");
            sessionStorage.setItem("imagen_banner", dz1.files[0].dataURL);
            $('#pre-banner').css('height', '17rem')
            $('#pre-banner').css('background', 'linear-gradient('+sessionStorage.getItem("color-rgb")+', '+sessionStorage.getItem("color-rgb")+'), url('+dz1.files[0].dataURL+')');
            $('#pre-banner').css('background-size', 'cover')
            clearInterval(cargar_fondo);
}



$('#cargar_contenido').on('submit', function (e) {

        console.log("cargar_contenido");
        e.preventDefault();

        if(dz1.files[0] == undefined  || dz2.files[0] == undefined){
              swal({
                title: "No se ha encontrado Una de las imagenes",
                text: "Todo Banner debe tener dos imagenes",
                type: "warning",
                confirmButtonColor: "#DD6B55", confirmButtonText: "Aceptar",
                closeOnConfirm: false
            })
           return;
        }else{
             dz1.processQueue()
        }
})




function crear_banner() {

    console.log("paso a guardar");

    var titulo = $("#titulo").val();
    var parrafo = $("#contenido").val();
    var imgfondo = $("#img-fondo-nombre").val();
    var imglogo = $("#img-logo-nombre").val();
    var transparencia = sessionStorage.getItem("color-rgb");
    var colortexto = sessionStorage.getItem("color-letra");
    url = "../Panel/crear_banner"
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
                "imgfondo": imgfondo,
                "imglogo": imglogo,
                "transparencia": transparencia,
                "colortexto": colortexto,
                "visible": 1},
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

</script>