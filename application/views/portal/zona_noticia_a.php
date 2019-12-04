<style>

.contenedor_carrusel_noticias{
            height: 60%;
            position: absolute;
            top: 20%;
            width: 90%;
            left: 5%;
        }


        .contenedor_imagen {
    width: 100%;
    height: 10rem;
    margin-left: auto;
    margin-right: auto;
    display: block;
    overflow: hidden;
}


.relacionados{
  height: 20rem;
  overflow-y: scroll;
  overflow-x:hidden;
}


@media (max-width: 2000px){



#opciones_blog{
height: 12%;
position: absolute;
bottom: 1rem;
padding: 1%;
width: 100%
}


.flex-container {
    position: absolute;
    height: 100%;
    width: 100%;
    display: -webkit-flex;
    display: flex;
    overflow: hidden;
    font-family: "montserrat" !important;
    @media screen and (max-width: 768px) {;
    flex-direction: column;
    } ;
}

.relacionados{
  height: 30rem;
  overflow-y: scroll !important;
  overflow-x:hidden;
}

.parrafo_articulo{

  height: 35rem;
  overflow-y: scroll;
}

.titulo-seccion {
    position: absolute;
    color: #00263b;
    bottom: 53% !important;
    right: -10% !important;
    transform: rotate(-90deg);
    letter-spacing: 0.35rem;
    font-size: 3rem;
}


}


@media (max-width: 1500px){
.titulo-seccion {
    position: absolute;
    color: #00263b;
    bottom: 53% !important;
    right: -10% !important;
    transform: rotate(-90deg);
    letter-spacing: 0.35rem;
    font-size: 3rem;
}
}

@media (max-width: 1370px) {
        
    .contenedor_imagen {
    width: 100%;
    height: 8rem;
    margin-left: auto;
    margin-right: auto;
    display: block;
    overflow: hidden;
}

.parrafo_articulo{

height: 21.5rem !important;
overflow-y: scroll;
}

.relacionados{
  height: 20rem;
  overflow-y: scroll !important;
  overflow-x:hidden;
}


}

@media (max-width: 800px){
.titulo-seccion {
    position: absolute;
    color: rgb(255, 255, 255);
    bottom: 40%;
    right: -20%;
    transform: rotate(-90deg);
    letter-spacing: 0.35rem;
    font-size: 3.5rem;
}

}






@media (max-width: 1500px){

  .titulo-seccion {
    position: absolute;
    color: #3f5b6b;
    bottom: 51%;
    right: -13%;
    transform: rotate(-90deg);
    letter-spacing: 0.8rem;
    font-size: 4rem;
}

  #opciones_blog{
  height: 12%;
  position: absolute;
  bottom: 1rem;
  padding: 1%;
  width: 100%
}

.titulo-seccion {
    position: absolute;
    color: #00263b;
    bottom: 80%;
    right: -32%;
    transform: rotate(-90deg);
    letter-spacing: 0.35rem;
    font-size: 3rem;
}

}


@media (max-width: 1367px){
  .banner_1 {
	background: linear-gradient(rgba(0, 0, 0, 0.79), rgba(0, 0, 0, 0.79)),url('../../assets/img/nuevo/banner_1.jpg');
	position: absolute;
	height: 30%;
	bottom: 0%;
	background-size: cover;
	background-size: 100% 297%;
	background-position: 100% 56%;
	@media screen and (
	min-width: 768px) {
		@include animation-properties((animation: aboutFlexSlide, animation-duration: $defaultSeconds, animation-iteration-count: 1, animation-delay: 0s));
	}
}

.flex-container {
    position: absolute;
    height: 100%;
    width: 100%;
    display: -webkit-flex;
    display: flex;
    overflow: hidden;
    font-family: "montserrat" !important;
    @media screen and (max-width: 768px) {;
    flex-direction: column;
    } ;
}

.p_banner {
    letter-spacing: 0px;
    line-height: 1rem;
}


#autor{
  font-size: 0.5rem
}

}



@media (max-width: 1024px){

  
.banner_1 {
	background: linear-gradient(rgba(0, 0, 0, 0.79), rgba(0, 0, 0, 0.79)),url('assets/img/nuevo/banner_1.jpg');
	position: absolute;
	height: 30%;
	bottom: -90%;
	background-size: cover;
	background-size: 100% 297%;
	background-position: 100% 56%;
	@media screen and (
	min-width: 768px) {
		@include animation-properties((animation: aboutFlexSlide, animation-duration: $defaultSeconds, animation-iteration-count: 1, animation-delay: 0s));
	}
}


.flex-container {
    position: absolute;
    height: 160%;
    width: 100%;
    display: -webkit-flex;
    display: flex;
    overflow: hidden;
    font-family: "montserrat" !important;
    @media screen and (max-width: 768px) {;
    flex-direction: column;
    }: ;
}


#opciones_blog{
  height: 12%;
  position: absolute;
  bottom: -6rem;
  padding: 1%;
  width: 100%
}

}


@media (max-width: 800px){

  
.banner_1 {
	background: linear-gradient(rgba(0, 0, 0, 0.79), rgba(0, 0, 0, 0.79)),url('assets/img/nuevo/banner_1.jpg');
	position: absolute;
	height: 30%;
	bottom: -130%;
	background-size: cover;
	background-size: 100% 297%;
	background-position: 100% 56%;
	@media screen and (
	min-width: 768px) {
		@include animation-properties((animation: aboutFlexSlide, animation-duration: $defaultSeconds, animation-iteration-count: 1, animation-delay: 0s));
	}
}


.flex-container {
    position: absolute;
    height: 200%;
    width: 100%;
    display: -webkit-flex;
    display: flex;
    overflow: hidden;
    font-family: "montserrat" !important;
    @media screen and (max-width: 768px) {;
    flex-direction: column;
    }: ;
}


#opciones_blog{
  height: 12%;
  position: absolute;
  bottom: -8.5rem;
  padding: 1%;
  width: 100%
}

}


@media (max-width: 500px){
.contenido-servicios {
    position: absolute;
    top: 3rem;
    left: -2rem;
    z-index: 1;
    transform: scale(0.7);
    background: #f8ed38;
}
.logo-blanco {
    position: absolute;
    top: 1rem;
    left: 4.5rem !important;
    width: 6rem;
    z-index: 100;
}


.titulo-seccion {
    position: absolute;
    color: #00263b;
    bottom: 80%;
    right: -32%;
    transform: rotate(-90deg);
    letter-spacing: 0.35rem;
    font-size: 3rem;
}


.banner_1 {
	background: linear-gradient(rgba(0, 0, 0, 0.79), rgba(0, 0, 0, 0.79)),url('assets/img/nuevo/banner_1.jpg');
	position: absolute;
	height: 60%;
	bottom: -180%;
	background-size: cover;
	background-size: 100% 297%;
	background-position: 100% 56%;
	@media screen and (
	min-width: 768px) {
		@include animation-properties((animation: aboutFlexSlide, animation-duration: $defaultSeconds, animation-iteration-count: 1, animation-delay: 0s));
	}
}


.flex-container {
    position: absolute;
    height: 221%;
    width: 100%;
    display: -webkit-flex;
    display: flex;
    overflow: hidden;
    font-family: "montserrat" !important;
    @media screen and (max-width: 768px) {;
    flex-direction: column;
    }: ;
}

.contenedor_opciones_planes {
    height: 60%;
    position: absolute;
    top: 7%;
    width: 90%;
    left: 0%;
}


#opciones_blog button {
  margin-top:5px;
}


}




 



</style>
	<!-- logo -->
	
	<a href="../../"><img src="../../assets/img/nuevo/logo-oscuro.svg" alt="" class="logo-blanco"></a>
	<!-- logo -->




<div class="flex-container fondo_4">



<div class="container-fluid" style="position: absolute;top: 13%; ">
<div class="row justify-content-center" style="background: #fff;">
<div class="col-lg-12 col-xl-4 col-md-12 pt-5">
<img class="mr-auto ml-auto d-block img-blog-a mt-3 w-100"  onclick="ampliar(this.src)"   src="../../assets/img/img-blog/<?php echo $imagen ?>" alt="">
<p id="autor"><strong> Autor: <?php echo $autor ?>. <?php echo $fecha ?></strong></p>
</div>
<div class="col-lg-6 col-xl-5 col-lg-6 col-md-12">

                 <strong>
                 <p id="titulo"><h1><?php echo $titulo ?></h1></p>
                 </strong>
                 <p class="text-left parrafo_articulo" id="contenido" style="text-decoration: none !important;">
                 <?php echo $parrafos ?>
                  <br>
                 <?php 
                   if (isset($etiquetas))
                   {
                 foreach($etiquetas as $etiqueta){
                  print_r("<i class='fas fa-tag etiquetas_frank pt-2'><a href='../articulos_relacionados/".json_decode(json_encode($etiqueta['_id']), True)['$id']."'>#".$etiqueta['etiqueta']."</a></i> ");
                 }}?>
                </p>

</div>
<div class="col-lg-6 col-xl-2 pt-2 col-md-12">
      
      <h5>
            <strong id="cantidad_vistas"><?php echo $nvistas ?></strong> Vistas
          </h5>

          <div class="fb-share-button" id="compartir_face" data-href="" data-layout="button" data-size="large" data-mobile-iframe="true"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fdevelopers.facebook.com%2Fdocs%2Fplugins%2F&amp;src=sdkpreparse" class="fb-xfbml-parse-ignore">Compartir</a></div>

          
               
              <?php  
              if (isset($relacionados))
              {
               print_r('<div class="relacionados pt-1 row">
                  <h6 class="text-center">Articulos relacionados</h6><br><div class="row">');
              foreach($relacionados as $articulos){

               // print_r(json_decode(json_encode($articulos['_id']), True)['$id']);
      
              print_r('<HR><div class="col-lg-6 col-md-6 col-sm-12 pt-2" title="VER"><a href="'.json_decode(json_encode($articulos['_id']), True)['$id'].'"><img class="img-fluid mr-auto ml-auto d-block" src="../../assets/img/img-blog/'.$articulos['imagen'].'"></a></div><div class="col-lg-6 col-md-6 pt-2 col-sm-12"><p><a href="'.json_decode(json_encode($articulos['_id']), True)['$id'].'">'.$articulos['titulo'].'</a></p><p><a href="'.json_decode(json_encode($articulos['_id']), True)['$id'].'"><i class="fas fa-eye etiquetas_frank"> '.$articulos['visitas'].'</i></a></p></div>');
                 
              }
              print_r('</div></div>');
              
             }
              ?>              
              <button class="btn btn-warning w-100 mr-auto ml-auto d-block  mt-3 pt-2 pb-2 text-uppercase" data-toggle="modal" data-target="#modal_blog_comentarios" ><strong>Comentarios</strong></button>
    
          

</div>
</div>
</div>




        


<H1 class="titulo-seccion text-uppercase" >NOTICIAS&nbsp&nbsp<H1>


  <div class="container-fluid" ;
}" >
      <div class="row justify-content-center"  id="opciones_blog" >
          <div class="col-lg-3 col-md-3">
            <button class="btn btn-warning w-100 mr-auto ml-auto d-block pt-2 pb-2 text-uppercase" data-toggle="modal" data-target="#modal_blog_recientes" ><strong>Recientes</strong></button>
          </div>

          <div class="col-lg-3 col-md-3">
            <button class="btn btn-warning w-100 mr-auto ml-auto d-block pb-2 pb-2 text-uppercase" data-toggle="modal" data-target="#modal_blog_noticias"><strong>noticias</strong></button>
          </div>

          <div class="col-lg-3 col-md-3">
            <button class="btn btn-warning w-100 mr-auto ml-auto d-block pb-2 pb-2 text-uppercase" data-toggle="modal" data-target="#modal_blog_eventos"><strong>eventos</strong></button>
          </div>


          <div class="col-lg-3 col-md-3">
            <button class="btn btn-warning w-100 mr-auto ml-auto d-block pb-2 pb-2 text-uppercase"  data-toggle="modal" data-target="#modal_blog_busqueda"><strong>buscar <i class="fas fa-search ml-2"></i></strong></button>
          </div>

      </div>
  </div>




</div>


<div class="modal fade" id="modal_blog_comentarios" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          Comentarios!
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="fb-comments" data-href="https://stfranksanchez.com.ve/blog<?php echo $code_face ?>" data-mobile="true"  data-numposts="5"></div>
                           
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary mr-auto ml-auto d-block" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>



<div class="modal fade" id="modal_blog_busqueda" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          Buscar articulo
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form class="form-inline" id="busqueda">
              
              <input type="text" class="form-control mb-2 mt-2 w-100" id="buscador" onkeyup="buscar(this.value)" id="inlineFormInputName2" placeholder="Buscar">
            </form>
            <div id="lista" class="col-12">

            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary mr-auto ml-auto d-block" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

<div class="modal fade" id="modal_blog_recientes" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          Lista de articulos recientes
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <ul id="lista_recientes">

          </ul>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary mr-auto ml-auto d-block" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

<div class="modal fade" id="modal_blog_eventos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        Lista de Eventos
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <ul id="lista_eventos">
            </ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary mr-auto ml-auto d-block" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="modal_blog_noticias" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        Lista de Noticias
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <ul id="lista_noticias">
        </ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary mr-auto ml-auto d-block" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>




<script>
  seccion_activa = 1

if(seccion_activa == 0 ){
      // ocultar boton menu
    $(".navbar").hide();
    console.log("No mostrar menu");
    $("#opciones_menu_cotainer").hide();
    }else{
      $(".navbar").show();
      $(".navbar").removeClass('navbar-dark')
    $(".navbar").addClass('navbar-light')
    }


re = 0
  $("#compartir_face").attr("data-href", location.href);
  </script>
  
  
   <script> (function (d, s, id) {
         var js, fjs = d.getElementsByTagName(s)[0];
         if (d.getElementById(id)) return;
         js = d.createElement(s); js.id = id;
         js.src = 'https://connect.facebook.net/es_LA/sdk.js#xfbml=1&version=v3.1';
         fjs.parentNode.insertBefore(js, fjs);
     }(document, 'script', 'facebook-jssdk'));
     
  
  
  
     
     </script>
  
  <div id="fb-root"></div>
  <script>(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = 'https://connect.facebook.net/es_ES/sdk.js#xfbml=1&version=v3.2';
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));</script>
                  </div>
            
            <!-- CONTENIDO  -->
          </div>
  </div>
  



<script>


    

    cargar_carruel_blog()
function cargar_carruel_blog() {

url = "../../Blog/listar_entradas"
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


if(window.visualViewport.width < 1025){
  resumen = 120;
}


if((window.visualViewport.width < 1370) &&  (window.visualViewport.width > 1024) ){
  resumen = 55;
}

		
if(window.visualViewport.width > 1370){
  resumen = 140;
}


$.ajax(settings).done(function (response) {
a = JSON.parse(response);
  datalista = a;

// $("#noticias").html("")
      $('#solo_eventos').html("")
      $('#solo_noticias').html("")
    datalista.forEach(function (valor, indice, array) {
//console.log(valor.titulo);

   if(valor.estado_visible == 1){

    $(".carrusel-blog").append('<div class="col-lg-12">'+
      '<div class="contenedor_noticia">'+
        '<div class="contenedor_imagen">'+
        '<a href="blog/articulo/'+valor._id.$id+'"><img src="assets/img/img-blog/' + valor.imagen +'" alt=""></a>'+
        '</div>'+
        '<div class="titulo text-center pt-3"><a href="blog/articulo/'+valor._id.$id+'"><strong>'+valor.titulo+'</strong></a>'+
        '</div>'+
        '<p>'+(valor.contenido.substr(0, resumen))+'...</p></div></div>')


          if(valor.tipo == 'Evento'){
            
          $("#lista_eventos").append('<li><a href="'+valor._id.$id+'"><strong>'+valor.titulo+'</strong></a></li>')

          }

          if(valor.tipo == 'Noticia'){
  
            $("#lista_noticias").append('<li><a href="'+valor._id.$id+'"><strong>'+valor.titulo+'</strong></a></li>')
            
          }


          $("#lista_recientes").append('<li><a href="'+valor._id.$id+'"><strong>'+valor.titulo+'</strong></a></li>')
            





   }
   
    })

    var owl = $('.carrusel-blog');



})
}





function buscar($titulo){
  $("#lista").html("");
    url = "../../blog/buscar_titulo"
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

            
            
var datalista = a[0];
console.log(datalista);

if(a == 0){
    $("#lista").append('<p>No se encontraron resultados</p>');
}else{
    datalista.forEach(function (valor, indice, array) {     
                  
        $("#lista").append('<p><a href="'+valor._id.$id+'"><strong>'+valor.titulo+'</strong></a></p>');
            
     })
}})


}
 





        </script>
    