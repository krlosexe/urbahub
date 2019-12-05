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

#opciones_blog{
  height: 12%;
  position: absolute;
  bottom: 0;
  padding: 1%;
  width: 100%
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


}



@media (max-width: 800px){
.titulo-seccion {
    position: absolute;
    color: #fff;
    bottom: 40%;
    right: -20%;
    transform: rotate(-90deg);
    letter-spacing: 0.35rem;
    font-size: 3.5rem;
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
	bottom: -70%;
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
    height: 110%;
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


.opciones_blog{
  height: 12%;
  position: absolute;
  bottom: 13rem;
  padding: 1%;
  
}
.contenedor_imagen {
    width: 100%;
    height: 20rem;
    margin-left: auto;
    margin-right: auto;
    display: block;
    overflow: hidden;
}

.contenedor_carrusel_noticias {
    height: 60%;
    position: absolute;
    top: 9%;
    width: 90%;
    left: 5%;
}

}

.titulo {
    color: #fff;
    font-weight: bold;
    font-size: 1rem;
    line-height: 15px;
    padding-bottom: 1rem;
}






 
.contenedor_redes a i {
  color: #00263b;
}



@media (max-width: 480px){
  .contenedor_carrusel_noticias {
    height: 60%;
    position: absolute;
    top: 16%;
    width: 90%;
    left: 5%;
}
.flex-container {
  height: 55rem;


}


.banner_1 {
	background: linear-gradient(rgba(0, 0, 0, 0.79), rgba(0, 0, 0, 0.79)),url('assets/img/nuevo/banner_1.jpg');
	position: absolute;
	height: 60%;
	bottom: -110%;
	background-size: cover;
	background-size: 100% 297%;
	background-position: 100% 56%;
	@media screen and (
	min-width: 768px) {
		@include animation-properties((animation: aboutFlexSlide, animation-duration: $defaultSeconds, animation-iteration-count: 1, animation-delay: 0s));
	}
}

.p_banner {
    letter-spacing: 0px;
    line-height: 1rem;
    font-size: 0.7rem;
    margin-top: -2.5rem;
}
.titulo-seccion {
    position: absolute;
    color: #00263b;
    bottom: 67%;
    right: -21%;
    transform: rotate(-90deg);
    letter-spacing: 0.35rem;
    font-size: 2.1rem;
}

}


</style>
	<!-- logo -->
	<a href="./"><img src="assets/img/nuevo/logo-oscuro.svg" alt="" class="logo-blanco"></a>
	<!-- logo -->




<div class="flex-container fondo_4">





<div class="contenedor_carrusel_noticias">
<div class="owl-carousel owl-reponsive carrusel-blog">
</div>
</div>




        


<H1 class="titulo-seccion text-uppercase" >NOTICIAS&nbsp&nbsp<H1>


  <div class="container-fluid opciones_blog" style="" >
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

// variable de menu  
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

charge_view_news() 

    cargar_carruel_blog()
function cargar_carruel_blog() {

url = "Blog/listar_entradas"
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


if(window.visualViewport.width < 500){
  resumen = 100;
}


if((window.visualViewport.width < 800) &&  (window.visualViewport.width > 500) ){
  resumen = 200;
}

if((window.visualViewport.width > 800) &&  (window.visualViewport.width < 1025) ){
  resumen = 200;
}

if((window.visualViewport.width < 1025) && (window.visualViewport.width > 1370)){
  resumen = 120;
}


if((window.visualViewport.width < 1370) &&  (window.visualViewport.width > 1024) ){
  resumen = 55;
}

		
if((window.visualViewport.width < 1500) && (window.visualViewport.width > 1370)){
  resumen = 80;
}

if(window.visualViewport.width > 1500){
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
        '<a href="Blog/articulo/'+valor._id.$id+'"><img src="assets/img/img-blog/' + valor.imagen +'" alt=""></a>'+
        '</div>'+
        '<div class="titulo text-center text-light pt-3 "><a class="text-light" href="Blog/articulo/'+valor._id.$id+'"><strong>'+valor.titulo+'</strong></a>'+
        '</div>'+
        '<p>'+(valor.contenido.substr(0, resumen))+'...</p></div></div>')


          if(valor.tipo == 'Evento'){
            
          $("#lista_eventos").append('<li><a href="Blog/articulo/'+valor._id.$id+'"><strong>'+valor.titulo+'</strong></a></li>')

          }

          if(valor.tipo == 'Noticia'){
  
            $("#lista_noticias").append('<li><a href="Blog/articulo/'+valor._id.$id+'"><strong>'+valor.titulo+'</strong></a></li>')
            
          }


          $("#lista_recientes").append('<li><a href="Blog/articulo/'+valor._id.$id+'"><strong>'+valor.titulo+'</strong></a></li>')
            





   }
   
    })

    var owl = $('.carrusel-blog');
    owl.owlCarousel({
        items:4,
        loop:true,
        margin:10,
        autoplay:true,
        autoplayTimeout:5000,
        autoplayHoverPause:true,
        responsiveClass:true,
        responsive:{
            0:{
                items:1,
                nav:true
            },
            600:{
                items:3,
                nav:false
            },
            768:{
                items:2,
                nav:true
            },
            1024:{
                items:3,
                nav:true,
                loop:true
            },
            1200:{
                items:5,
                nav:true,
                loop:true
            },
            1440:{
                items:4,
                nav:true,
                loop:true
            }
            ,
            1640:{
                items:5,
                nav:true,
                loop:true
            }
        }
    });
    $(".owl-prev").html('<span class="carousel-control-prev-icon" aria-hidden="true"></span>');
    $(".owl-next").html('<span class="carousel-control-next-icon" aria-hidden="true"></span>');



})
}





function buscar($titulo){
  $("#lista").html("");
    url = "blog/buscar_titulo"
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
                  
        $("#lista").append('<p><a href="Blog/articulo/'+valor._id.$id+'"><strong>'+valor.titulo+'</strong></a></p>');
            
     })
}})


}
 



$(function(){


if(window.screen.availWidth > 500){
  var tp = "100%"
}else{
  var tp = "150%"
}


           $(".banner_1").removeClass('d-lg-block');
           $(".banner_1").hide();
           $(".flex-container").css("height", tp)
        });



        </script>
    