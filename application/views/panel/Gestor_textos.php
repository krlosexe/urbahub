<div class="container">
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Gestor de Textos</h4>
                <form class="row" id="">
                    <!-- select de selecion de imagenes-->
                    <div class="form-group col-md-4 form-material">
                        <div class="form-group">
                            <label>Sección</label> 
                            <select name="name_seccion" onchange="cargar_textos(this.value)"  class="form-control form-control-line" id="name_seccion">
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
                                        <th colspan=1>Texto</th>
                                    </tr>
                                </thead>
                                <tbody id="tabla-texto">
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
<div class="modal fade" id="actualizar_texto" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="">Actualizar Texto</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                    <form id="actualizar_contenido_texto">
                            <div class="row">
                              <div class="col-lg-12">
                                <input type="text" name="titulo" id="titulo" class="form-control" placeholder="Etiqueta">
                                <textarea name="contenido" class="form-control pt-2 mt-2" id="contenido" cols="30" rows="10"></textarea>
                                <input type="hidden" name="id_texto" id="id_texto">
                              </div>

                              <div class="col-lg-12 pt-2">
                                  
                            
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



<script>
// cargar seclec 
/*url = "../Gestor_textos/listar_secciones";
            var settings = {
       "async": true,
       "crossDomain": true,
       "url": url,
       "method": "POST",
       "headers": {
           "cache-control": "no-cache"
       },
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
           // console.log(a);
           $("#name_seccion").html("")
            a.forEach(function (valor, indice, array) {
             $("#name_seccion").append('<option value="'+valor.id_seccion+'">'+valor.nombre_seccion+'</option>')})
        }
})*/
// cargar seclec 








$('#actualizar_contenido_texto').on('submit', function (e) {

console.log("actualizar_texto");
e.preventDefault();

url = "../Gestor_textos/actualizar_texto";
            var settings = {
       "async": true,
       "crossDomain": true,
       "url": url,
       "method": "POST",
       "headers": {
           "cache-control": "no-cache"
       },
       "data": $("#actualizar_contenido_texto").serialize(),
       "beforeSend": function () {
           //showLoader()
       }
   };

   $.ajax(settings).done(function (response) {
       console.log(response);
        a = JSON.parse(response);

        if(a.mensaje == "SUCCESS"){
            $(".modal").modal('hide');
            cargar_textos($("#name_seccion").val())
    swal({
    title: "Datos cambiados con exito",
    text: "",
    type: "success",
    confirmButtonColor: "#DD6B55", confirmButtonText: "Aceptar",
    closeOnConfirm: false
})  
}
   })


})

//  TABLA DE TEXTO
    function cargar_textos(id_seccion){
        console.log(id_seccion)
        url = "../Gestor_textos/buscar_texto";
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

            $("#tabla-texto").html("")
            a.forEach(function (valor, indice, array) {
               nombre = String("barra")
             $("#tabla-texto").append('<tr><td>'+valor.titulo_texto+'</td>'+
                '<td>'+valor.contenido+'</td>'+
                '<td><button type="button" title="Editar" class="btn btn-secondary" data-toggle="modal" data-target="#modal_editar" data-object="'+valor._id.$id+'"  data-target="#modal_editar"  onclick="actualizar_texto($(this).data(\'object\'))"><i class="mdi mdi-tooltip-edit"></i></button>'+
                '</td>'+'</tr>');
})
}
   })
    }
//  TABLA DE TEXTO

//  BUSCAR TEXTO
function actualizar_texto(id_texto){
    url = "../Gestor_textos/buscar_texto_id";
            var settings = {
       "async": true,
       "crossDomain": true,
       "url": url,
       "method": "POST",
       "headers": {
           "cache-control": "no-cache"
       },
       "data": {"id_texto": id_texto},
       "beforeSend": function () {
           //showLoader()
       }
   };

   $.ajax(settings).done(function (response) {
       console.log(response);
        a = JSON.parse(response);
       // nombre_imagen = a[0].nombre_imagen
    $("#actualizar_texto").modal("show");
    $("#titulo").val(a.titulo_texto);
    $("#contenido").val(a.contenido);
    $("#id_texto").val(a._id.$id);



   });

}

//  BUSCAR TEXTO


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