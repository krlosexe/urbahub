<div class="container">
<div class="row el-element-overlay">
    <div class="col-md-12">
        <h4 class="card-title">Imagenes del carrusel inicial</h4>
    </div>
</div>


<div class="row" id="carga_imagenes"></div>

<div class="modal fade bs-example-modal-lg" id="modal_ver" tabindex="-1" role="dialog" aria-labelledby="Titulo" style="display: none;"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-center" id="nombre"></h4>

                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <img class="img-fluid mr-auto ml-auto d-block" src="" id="img" alt="">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect text-left" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade bs-example-modal-lg" id="modal_editar" tabindex="-1" role="dialog" aria-labelledby="Titulo" style="display: none;"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-center" id="nombre"></h4>

                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <div class="dropzone" id="contenido_imagen"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success waves-effect text-left" onclick="actualizar()">Guardar</button>
                <button type="button" class="btn btn-danger waves-effect text-left" onclick="dz.removeAllFiles()" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<img src="" alt="">
</div>


<script>




function actualizar(){
    if (dz.files[0] == undefined) {
        console.log("no califica 3")

        swal("Actualizacion cancelada", "Estimado usuario por favor seleccione una imagen.", "error");
        return;}
    if(dz.files[0].height != 552){
        console.log("no califica 1")
        dz.removeAllFiles()
        swal("Actualizacion cancelada", "Estimado la imagen debe tener una altura igual a 552px.", "error");
        return;
    }
    if(dz.files[0].width != 1920){
        console.log("no califica 2")
        dz.removeAllFiles()
        swal("Actualizacion cancelada", "Estimado la imagen debe tener un largo igual a 1920px.", "error");

        return
    }
    dz.processQueue()
}



Dropzone.autoDiscover = false;



var dz = new Dropzone(".dropzone", {




//url: "http://localhost/pruebas/lux/test.php", /*url de prueba*/
url: "actualizar_carrusel", /*define url*/
method: "post", /*define metodo*/
maxFiles: 1, /*solo un archivo por formulario*/
dictDefaultMessage: "Arrastre <STRONG>aquí</STRONG> la nueva imagen.", /*mensaje del formulario*/
dictMaxFilesExceeded: "Solo se puede subir una imagen", /*mensaje si intenta subir varios*/
renameFile: function (file) {
let newName = nombre_imagen;
return newName;},
paramName: "filenames", //nombre del elemento en $_FILES
acceptedFiles: "image/jpg, image/png, image/jpeg", /*formatos aceptados*/
dictInvalidFileType: "No puedes subir este tipo de archivos", /*mensaje de formato erroneo*/
autoProcessQueue: false, /*evita que se suba automaticamente*/
previewsContainer: ".dropzone",
/* headers: {
     "accept": "application/json",
     "authorization": end_token,
     "cache-control": "no-cache"
     },*/
init: function () {
    /*sustituye la imagen anterior para cargar una sola*/
    this.on('addedfile', function (file) {
        if (this.files.length > 1) {
            this.removeFile(this.files[0]);
        }
    });

    //llamada cuando finaliza la subida
    this.on('success', function (file, response) {
        //$("#entrada").val(response)
        console.log(response)
       //cambiar_entrada();
if(response == 0){
 console.log("error")
}else{
 swal("Cambios guardados con exito!", "", "success");
        $("#modal_editar").modal('hide')
        $(".modal-backdrop").removeClass('show');
        $(".modal-backdrop").removeClass('fade');
        $(".modal-backdrop").removeClass('modal-backdrop');
        cambio_modulo('carrusel-principal', '')
}
    });

    //muestra un error
    this.on('error', function (file, response) {
        //console.log(response);
        //.dz-error-message lo agregue yo manualmente
        $(file.previewElement).find('.dz-error-message').text(response);
    });
}
});





cargar_imagenes()
function cargar_imagenes(){
url = "../assets/img/controlador_imagenes.php"
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
    a.forEach(function (valor, indice, array) {
        //console.log(valor)
        var cadena = valor,
            separador = ".", // un espacio en blanco
            arregloDeSubCadenas = cadena.split(separador);
            
        img_1 = "'" + arregloDeSubCadenas[0] + "'"
        img_2 = "'"+arregloDeSubCadenas[1]+"'" 
     
       
            
            console.log(arregloDeSubCadenas);
       $("#carga_imagenes").append('<div class="col-lg-4 col-md-6"><div class="card"><div class="el-card-item">' +
            '<div class="el-card-avatar el-overlay-1"> <img class="img-fluid" src="../assets/img/slider/img-com/' + valor + '?' +Date()+'" alt="user"></div>' +
            '<div class="el-card-content text-center pt-2 pb-1"><h3 class="box-title">' + valor + '</h3>'+
            '<button type="button" title="Ver" class="btn btn-secondary" data-toggle="modal" data-target="#modal_ver" onclick="mostrar_imagen('+img_1+','+img_2+')"><i class="mdi mdi-eye"></i></button>'+
            '<button type="button" title="Editar" class="btn btn-secondary ml-1" data-toggle="modal" data-target="#modal_editar" onclick="cambiar_imagen(' + img_1 + ',' + img_2 +')"><i class="mdi mdi-tooltip-edit"></i></button>' +
            '</div></div></div ></div >')
    })



})
console.clear();
}




function mostrar_imagen(nombre, ext) {
    console.log(nombre+" - "+ext)
    $("#nombre").html(nombre+"."+ext);
    $("#img").attr('src', '../assets/img/slider/img-com/'+ nombre + "." + ext+'?' +Date() )
}

    function cambiar_imagen(nombre, ext) {
            console.log(nombre + " - " + ext)

            nombre_imagen = nombre+"."+ext;
           // $("#nombre").html(nombre + "." + ext);
           // $("#img").attr('src', '../assets/img/slider/img-com/' + nombre + "." + ext)
        }


</script>