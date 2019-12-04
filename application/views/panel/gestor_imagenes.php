<div class="container">
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Gestor de Imagenes</h4>
                <form class="row" id="cambio_pass">
                    <!-- select de selecion de imagenes-->
                    <div class="form-group col-md-4 form-material">
                        <div class="form-group">
                            <label>Sección</label> 
                            <select name="name_seccion" onchange="cargar_imagenes(this.value)"  class="form-control form-control-line" id="name_seccion">
                                <option value="1">Logos Banner</option>
                                <option value="2">Iconos servicios</option>
                                <option value="3">Galeria</option>
                                <option value="4">Logos Beneficios</option>
                                <option value="5">Logos Principales</option>
                                <option value="7">Banner</option>
                                <option value="8">Fondos</option>
                            </select>
                        
                        </div>
                    </div>
                    <!-- select de selecion de imagenes-->

                    <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th colspan=1>Nombre</th>
                                        <th colspan=1>Etiqueta</th>
                                        <th colspan=1>Acciones</th>
                                        
                                    </tr>
                                </thead>
                                <tbody id="tabla-imagenes">
                                    <tr>
                                        <th></th>
                                    </tr>
                                </tbody>
                            </table>
                    </div>
                    
              
                </form>
            </div>
        </div>
      
    </div>
</div>
</div>


<!-- MODAL DE ACTULIZAR IMAGEN -->
<div class="modal fade" id="actualizar_imagen" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="">Actualizar imagen</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                    <form id="actualizar_etiqueta">
                            <div class="row">
                              <div class="col-lg-12">
                                <input type="text" name="etiqueta" id="etiqueta" class="form-control" placeholder="Etiqueta">
                                <input type="hidden" name="id_imagen" id="id_imagen" class="form-control" placeholder="id">
                              </div>

                              <div class="col-lg-12 pt-2">
                                  
                                    <div class="dropzone" id="contenido_imagen"></div>
                              </div>

                            </div>
                          
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-secondary" >Actualizar</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </form>
          </div>
        </div>
      </div>

<!-- MODAL DE ACTULIZAR IMAGEN -->



<!-- MODAL DE VISTA DE IMAGEN -->
<div class="modal fade" id="ver_imagen" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="Gestor_de_imagen"></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <img class="img-fluid bg-secondary" id="imagen_seleccionda" src="" alt="">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
              
            </div>
          </div>
        </div>
      </div>

<!-- MODAL DE VISTA DE IMAGEN -->

<script>

$('#actualizar_imagen').on('submit', function (e) {

console.log("actualizar_imagen");
e.preventDefault();
//$("#modal_editar").modal('hide');
if(dz.files[0] == undefined){
    console.log("NO existe")
    swal({   
title: "Desea mantener la imagen actual?",   
text: "",   
type: "warning",   
showCancelButton: true,   
confirmButtonColor: "#DD6B55",   
confirmButtonText: "Si!",   
cancelButtonText: "No,",   
closeOnConfirm: false,   
closeOnCancel: false 
}, function(isConfirm){   
if (isConfirm) {     
    actualizar_etiqueta()
} else {     
    swal("Actualizacion cancelada", "Estimado usuario por favor seleccione una imagen", "error");  

} 
});
   return;
}else{
    console.log("existe");
     dz.processQueue()
}
})

    

Dropzone.autoDiscover = false;



var dz = new Dropzone(".dropzone", {
        //url: "http://localhost/pruebas/lux/test.php", /*url de prueba*/
        url: "../Gestor_imagenes/actualizar_imagen", /*define url*/
        method: "post", /*define metodo*/
        maxFiles: 1, /*solo un archivo por formulario*/
        dictDefaultMessage: "Arrastre <STRONG>aquí</STRONG> la imagen del contenido.", /*mensaje del formulario*/
        dictMaxFilesExceeded: "Solo se puede subir una imagen", /*mensaje si intenta subir varios*/
        renameFile: function (file) {
    let newName = nombre_imagen;
    return newName;},
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
        });

        //llamada cuando finaliza la subida
        this.on('success', function (file, response) {
           // $("#entrada").val(response)
           actualizar_etiqueta();
        });

        //muestra un error
        this.on('error', function (file, response) {
            //console.log(response);
            //.dz-error-message lo agregue yo manualmente
            $(file.previewElement).find('.dz-error-message').text(response);
        });
    }
});

    function cargar_imagenes(id_seccion){
        url = "../Gestor_imagenes/buscar_imagenes";
            var settings = {
       "async": true,
       "crossDomain": true,
       "url": url,
       "method": "POST",
       "headers": {
           "cache-control": "no-cache"
       },
       "data": {"id_seccion": id_seccion},
       "beforeSend": function () {
           //showLoader()
       }
   };

   // llena el select
   $.ajax(settings).done(function (response) {
       console.log(response);
        a = JSON.parse(response);

        if(a.mensaje == "error"){

        }else{

            $("#tabla-imagenes").html("")
            a.forEach(function (valor, indice, array) {
               nombre = String("barra")
             $("#tabla-imagenes").append('<tr><td>'+valor.nombre_imagen+'</td>'+
                '<td>'+valor.etiqueta+'</td>'+
                '<td><button type="button" title="Editar" class="btn btn-secondary" data-toggle="modal"  data-object="'+valor._id.$id+'"  data-target="#modal_editar" onclick="actualizar_imagen($(this).data(\'object\'))"><i class="mdi mdi-tooltip-edit"></i></button>'+
                '<button type="button" title="Ver" class="btn btn-secondary" data-toggle="modal" data-target="#modal_ver" id="'+valor.nombre_imagen+'" onclick="mostrar_imagen(this.id)"><i class="mdi mdi-eye"></i></button></td>'+'</tr>');
})
        }


        


   
   })
    }


function actualizar_etiqueta(){
    url = "../Gestor_imagenes/actualizar_etiqueta_imagen";
            var settings = {
       "async": true,
       "crossDomain": true,
       "url": url,
       "method": "POST",
       "headers": {
           "cache-control": "no-cache"
       },
       "data": $("#actualizar_etiqueta").serialize(),
       "beforeSend": function () {
           //showLoader()
       }
   };

   $.ajax(settings).done(function (response) {
       console.log(response);
        a = JSON.parse(response);

        if(a.mensaje == "SUCCESS"){
            $(".modal").modal('hide');
            cargar_imagenes($("#name_seccion").val())
    swal({
    title: "Datos cambiados con exito",
    text: "",
    type: "success",
    confirmButtonColor: "#DD6B55", confirmButtonText: "Aceptar",
    closeOnConfirm: false
})  
}
   })

}

function actualizar_imagen(id_imagen){
    url = "../Gestor_imagenes/buscar_imagen";
            var settings = {
       "async": true,
       "crossDomain": true,
       "url": url,
       "method": "POST",
       "headers": {
           "cache-control": "no-cache"
       },
       "data": {"id_imagen": id_imagen},
       "beforeSend": function () {
           //showLoader()
       }
   };

   $.ajax(settings).done(function (response) {
       console.log(response);
        a = JSON.parse(response);
        nombre_imagen = a[0].nombre_imagen
    $("#actualizar_imagen").modal("show");
    $("#etiqueta").val(a[0].etiqueta);
    $("#id_imagen").val(a[0]._id.$id)



   });

}


    function mostrar_imagen(nombre_imagen){

        $("#ver_imagen").modal("show");
        $("#imagen_seleccionda").attr("src", "../assets/img/biblioteca_imagenes/"+nombre_imagen);
        $("#Gestor_de_imagen").text("Gestor de imagen : "+nombre_imagen);

}

$(document).ready(function () {


console.log("llego")
});







$('#cambio_pass').on('submit', function (e) {
    e.preventDefault();
pass1 = $("#pass_1").val()
pass2 = $("#pass_2").val()
    
console.log("paso")
url = "cambio_contra_bd"
var settings = {
"async": true,
"crossDomain": true,
"url": url,
"method": "POST",
"headers": {
"cache-control": "no-cache"
},
"data": $('#cambio_pass').serialize(),
"beforeSend": function () {
//showLoader()
}
};

// llena el select
$.ajax(settings).done(function (response) {
console.log(response);

if(response == 1){
    swal({
    title: "La contraseña ha sido cambiada",
    text: "",
    type: "success",
    confirmButtonColor: "#DD6B55", confirmButtonText: "Aceptar",
    closeOnConfirm: false
})  
}


if(response == 2){
    swal({
    title: "Error al actualizar contraseña",
    text: "Por favor intentelo mas tarde.",
    type: "warning",
    confirmButtonColor: "#DD6B55", confirmButtonText: "Aceptar",
    closeOnConfirm: false
})
}


if(response == 3){
    swal({
    title: "Error al actualizar contraseña",
    text: "La contraseña es incorrecta",
    type: "warning",
    confirmButtonColor: "#DD6B55", confirmButtonText: "Aceptar",
    closeOnConfirm: false
})
}



if(response == 4){  
    swal({
    title: "Las contraseñas no son iguales",
    text: "",
    type: "warning",
    confirmButtonColor: "#DD6B55", confirmButtonText: "Aceptar",
    closeOnConfirm: false
})
}


});



})

</script>