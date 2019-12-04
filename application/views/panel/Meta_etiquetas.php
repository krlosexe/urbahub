<div class="container">
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Gestor de Meta Etiquetas</h4>
                <form class="row" id="cambio_pass">
              
                    <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th colspan=1>Meta:Title</th>
                                        <th colspan=1>Meta:description</th>
                                        <th colspan=1>Meta:Keywords</th>
                                        <th colspan=1>Acciones</th>
                                        
                                    </tr>
                                </thead>
                                <tbody id="tabla-imagenes">

                                                <?php
                                if (isset($lista_meta_etiquetas)){
                                foreach($lista_meta_etiquetas as $data_meta){
                               print_r('<tr><td>'.$data_meta['titulo'].'</td>
                                <td>'.$data_meta['descripcion'].'</td>
                                <td>'.$data_meta['keywords'].'</td>
                                <td><button type="button" title="Editar" class="btn btn-secondary" data-toggle="modal" data-target="#actualizar_data"  data-object="'.json_decode(json_encode($data_meta['_id']), True)['$id'].'"  onclick="actualizar_info($(this).data(\'object\'))"><i class="mdi mdi-tooltip-edit"></i></button>
                                </td></tr>');
                                }}else{
                                    print_r("error");
                                }


                ?>
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
<div class="modal fade" id="actualizar_data" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="">Actualizar Meta Etiqueta</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                    <form id="actualizar_meta_etiqueta">
                            <div class="row">
                            <div class="col-lg-12 pt-2">
                            <h4>Ttiulo:</h4>
                                  <textarea name="titulo" class="form-control" require id="titulo" cols="30" rows="5"></textarea>
                              </div>

                              <div class="col-lg-12 pt-2">
                              <h4>Descripción:</h4>
                                  <textarea name="descripcion" class="form-control" id="descripcion" cols="30" rows="5"></textarea>
                              </div>

                              <div class="col-lg-12 pt-2">
                              <h4>Palabras Clave:</h4>
                                  <textarea name="keywords" class="form-control" id="keywords" cols="30" rows="5"></textarea>
                                  <input type="hidden" name="id_meta" id="id_meta">
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



function actualizar_info(id_meta){
    url = "../Meta_etiquetas/editar";
            var settings = {
       "async": true,
       "crossDomain": true,
       "url": url,
       "method": "POST",
       "headers": {
           "cache-control": "no-cache"
       },
       "data": {"id_meta": id_meta},
       "beforeSend": function () {
           //showLoader()
       }
   }

   // llena el select
   $.ajax(settings).done(function (response) {
       console.log(response);
        a = JSON.parse(response);

        $("#titulo").val(a.titulo);
        $("#descripcion").val(a.descripcion);
        $("#keywords").val(a.keywords)
        $("#id_meta").val(a._id.$id);


})

}


$('#actualizar_meta_etiqueta').on('submit', function (e) {

e.preventDefault();


    url = "../Meta_etiquetas/actualizar";
            var settings = {
       "async": true,
       "crossDomain": true,
       "url": url,
       "method": "POST",
       "headers": {
           "cache-control": "no-cache"
       },
       "data": $("#actualizar_meta_etiqueta").serialize(),
       "beforeSend": function () {
           //showLoader()
       }
   };

   $.ajax(settings).done(function (response) {
       console.log(response);
        a = JSON.parse(response);

        if(a.mensaje == "SUCCESS"){
            location.reload();
           // $(".modal").modal('hide');
          //  cargar_imagenes($("#name_seccion").val())
    /*swal({
    title: "Datos cambiados con exito",
    text: "",
    type: "success",
    confirmButtonColor: "#DD6B55", confirmButtonText: "Aceptar",
    closeOnConfirm: false
})  */
}
   })



})

    


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
    $("#id_imagen").val(a[0].id)



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