
<style>

        

.modal-dialog {
    max-width: 60% !important;
    margin: 1.75rem auto;
}

.modal-header {
    display: -ms-flexbox;
    display: flex;
    -ms-flex-align: start;
    align-items: flex-start;
    -ms-flex-pack: justify;
    justify-content: space-between;
     padding: 0rem !important;
    border-bottom: 1px solid #e9ecef;
    border-top-left-radius: 0.3rem;
    border-top-right-radius: 0.3rem;
}

.modal-body {
    position: relative;
    -ms-flex: 1 1 auto;
    flex: 1 1 auto;
    padding: 0.5rem !important;
}



@media (max-width: 1600){
  .titulo-seccion {
    position: absolute;
    color: #fff;
    bottom: 51%;
    right: -15%;
    transform: rotate(-90deg);
    letter-spacing: 0.8rem;
    font-size: 4rem;
}
}


@media (max-width: 1500){
 
}

@media (max-width: 1370px) {

.modal-dialog {
max-width: 64.5% !important;
margin: 1.75rem auto;
}


}  


@media (max-width: 500px){
  .titulo-seccion {
    position: absolute;
    color: #fff;
    bottom: 35%;
    right: -29%;
    transform: rotate(-90deg);
    letter-spacing: 0.35rem;
    font-size: 3rem;
}

.logo-blanco {
    position: absolute;
    top: 1rem;
    left: 4.5rem !important;
    width: 6rem;
    z-index: 100;
}
}

.contenedor_redes a i {
    color: #fff;
}

@media (max-width: 500px){
  .flex-container {
  height: 100%;

}

}

        
        </style>


	<!-- logo -->

  <a href="./"><img src="assets/img/nuevo/logo-blanco.svg" alt="" class="logo-blanco"></a>
	
	<!-- logo -->




<div class="flex-container fondo_3">





        
<div class="contenedor_carrusel_galeria">

<div class="owl-carousel owl-reponsive carrusel_imagenes">
            



<?php 
                if (isset($imagenes))
                {
              foreach($imagenes as $bl_img){

                if($bl_img['seccion'] == 3){
                  print_r('<div class="col-lg-12">
                  <div class="contenedor_imagen border">
                  <img class="" onclick="ampliar(this.src)" src="assets/img/biblioteca_imagenes/'.$bl_img['nombre_imagen'].'" alt="'.$bl_img['etiqueta'].'">
                  </div></div>');
                }
              }}?>



        </div>




</div>




<H1 class="titulo-seccion text-uppercase" >GALERIA&nbsp&nbsp<H1>

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
    }



re = 0
charge_view_galery()
    function ampliar(ruta){
        $("#imagen_ampliada").attr('src', ruta)
        $("#modal_ampliar_imagen").modal('show');
    }


cargar_imagenes()
  function cargar_imagenes() {
var owl = $('.carrusel_imagenes');
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
            1000:{
                items:3,
                nav:true,
                loop:true
            }
        }
    });
    $(".owl-prev").html('<span class="carousel-control-prev-icon" aria-hidden="true"></span>');
    $(".owl-next").html('<span class="carousel-control-next-icon" aria-hidden="true"></span>');






  }






  
$(function(){
           $(".banner_1").removeClass('d-lg-block');
           $(".banner_1").hide();
           $(".flex-container").css("height", "100%")
        });

 





        </script>
    