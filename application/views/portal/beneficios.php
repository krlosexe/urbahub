
<style>


.bg-beneficios{
    background: #FFEB3B;
    font-weight: bold;
    text-align: center;
    padding: 0.5rem;
    font-family: montserrat_servicios_titulo;
    width: 75%;
    margin-left: auto;
    margin-right: auto;
    display: block;
    margin-top: -3.5rem;
}       

.borde-ben{
    border-left: #ffffff solid 4px;
    height: 16rem;
    position: absolute;
    top: 4rem;
    left: 0;
 
}


.contenedor_carrusel_bene {
    height: 30%;
    position: absolute;
    top: 15%;
    width: 70%;
    left: 15%;
}        

.contenedor-logo-beneficios{

width: 15rem;
display: block;
margin-left: auto;
margin-right: auto;
}

.contenedor-logo-beneficios img{
    transform: scale(0.6);
    -webkit-transform: scale(0.6);
}

.leyenda_beneficio_blanco{
    background: #fff;
    transform: scaleY(0.9);
    -webkit-transform: scaleY(0.9);
    padding-left: 8rem;
    position: absolute;
    top: 0rem;
    left: -1rem;
    z-index: 45;
}

.contenedor_leyendas_beneficios{
    position: absolute;
    top: 30%
}

.img-promo{
            position: absolute;
            top: 0.5rem;
            transform: scale(1.7);
            z-index: 50;
        }


@media (max-width: 1500px){
         
         .contenedor_carrusel_bene {
             height: 30%;
             position: absolute;
             top: 65%;
             width: 70%;
             left: 15%;
         }       

         .titulo-seccion {
            position: absolute;
            color: #fff;
            bottom: 51%;
            right: -15%;
            transform: rotate(-90deg);
            letter-spacing: 0.8rem;
            font-size: 3.5rem !important;
                 }


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
        
        
        
        
        @media (max-width: 1370px) {

            .borde-ben{
                border-left: #ffffff solid 4px;
                height: 11rem;
                position: absolute;
                top: 3rem;
                left: 0;
            }

            .bg-beneficios {
            background: #FFEB3B;
            font-weight: bold;
            text-align: center;
            padding: 0.5rem;
            font-family: montserrat_servicios_titulo;
            width: 60%;
            margin-left: auto;
            margin-right: auto;
            display: block;
            margin-top: -2.5rem;
            font-size: 0.6rem;
        }
        
        
        .contenedor_carrusel_bene {
            height: 30%;
            position: absolute;
            top: 25%;
            width: 70%;
            left: 11%;
         }      

         .contenedor-logo-beneficios img{
           transform: scale(0.3);
           -webkit-transform: scale(0.3);
        }

        .titulo-seccion {
            position: absolute;
            color: #fff;
            bottom: 40%;
            right: -10%;
            transform: rotate(-90deg);
            letter-spacing: 0.35rem;
            font-size: 3.5rem;
        } 

        .contenedor_leyendas_beneficios {
            position: absolute;
            top: 20%;
            left: -6rem;
            transform: scale(0.9);
           -webkit-transform: scale(0.9);
            }
        
            .img-promo {
                position: absolute;
                top: 9rem;
                transform: scale(2.1);
                z-index: 50;
}

        
.leyenda_beneficio_blanco{
    background: #fff;
    transform: scaleY(1);
    -webkit-transform: scaleY(1);
    padding-left: 5rem;
    position: absolute;
    top: 4.5rem;
    left: -1.3rem;
    z-index: 45;
}



        }  
        
        
        @media (max-width: 1024px){

            .contenedor_definicion_beneficio img {
                transform: scale(1.5);
                -webkit-transform: scale(1.5);
                padding-top: 2.5rem !important; 
            }

            .contenedor_carrusel_bene {
                height: 30%;
                position: absolute;
                top: 65%;
                width: 70%;
                left: 15%;
            }

            .flex-container {
            position: absolute;
            height: 100% !important;
            width: 100%;
            display: -webkit-flex;
            display: flex;
            overflow: hidden;
            font-family: "montserrat" !important;
            @media screen and (: ;
            max-width: 768px) {;
            flex-direction: column;
            }
            }

            .banner_1 {
                background: linear-gradient(rgba(0, 0, 0, 0.79), rgba(0, 0, 0, 0.79)),url(assets/img/nuevo/banner_1.jpg);
                position: absolute;
                height: 30%;
                bottom: -20% !important;
                background-size: cover;
                background-size: 100% 297%;
                background-position: 100% 56%;
                @media screen and (: ;
                min-width: 768px) {;
                @include animation-properties((animation: aboutFlexSlide, animation-duration: $defaultSeconds, animation-iteration-count: 1, animation-delay: 0s));
                }: ;
                }
        }


        @media (max-width : 800px){
            .contenedor_leyendas_beneficios {
            position: absolute;
            top: 30%;
            left: -2rem;
            transform: scale(0.9);
            -webkit-transform: scale(0.9);
        }

        .titulo-seccion {
    position: absolute;
    color: #fff;
    bottom: 37%;
    right: -22%;
    transform: rotate(-90deg);
    letter-spacing: 0.35rem;
    font-size: 3.5rem;
}

.banner_1 {
                background: linear-gradient(rgba(0, 0, 0, 0.79), rgba(0, 0, 0, 0.79)),url(assets/img/nuevo/banner_1.jpg);
                position: absolute;
                height: 35%;
                bottom: -20% !important;
                background-size: cover;
                background-size: 100% 297%;
                background-position: 100% 56%;
                @media screen and (: ;
                min-width: 768px) {;
                @include animation-properties((animation: aboutFlexSlide, animation-duration: $defaultSeconds, animation-iteration-count: 1, animation-delay: 0s));
                }
                }
        }
        
        @media (max-width: 500px){
            .contenedor_carrusel_bene {
    height: 30%;
    position: absolute;
    top: 65%;
    width: 100%;
    left: 0;
}

.titulo-seccion {
    position: absolute;
    color: #fff;
    bottom: 50% !important;
    right: -40%;
    transform: rotate(-90deg);
    letter-spacing: 0.35rem;
    font-size: 3rem;
    z-index: 0;
}
        
        .logo-blanco {
            position: absolute;
            top: 1rem;
            left: 4.5rem !important;
            width: 6rem;
            z-index: 100;
        }

 

.banner_1 {
    background: linear-gradient(rgba(0, 0, 0, 0.79), rgba(0, 0, 0, 0.79)),url(assets/img/nuevo/banner_1.jpg);
    position: absolute;
    height: 66%;
    bottom: -56% !important;
    background-size: cover;
    background-size: 100% 297%;
    background-position: 100% 56%;
    @media screen and (: ;
    min-width: 768px) {;
    @include animation-properties((animation: aboutFlexSlide, animation-duration: $defaultSeconds, animation-iteration-count: 1, animation-delay: 0s));
    }
}
        }






        .contenedor-logo-beneficios img{
        filter: brightness(100);
        -webkit-transition: 0.3s;
        transition: 0.3s;
    }

    .contenedor-logo-beneficios img:hover{
        filter: brightness(1);
        -webkit-transition: 0.3s;
        transition: 0.3s;
    }
    


    .contenedor_redes a i {
    color: #fff;
}


.leyenda_beneficio_blanco h1{
    font-family: montserrat_servicios_titulo;
    font-size: 2rem;
}

.leyenda_beneficio_blanco p{
    font-family: montserrat_servicios_parrafo !important
}

    
.owl-frank{
        /* position: absolute; */
    /* margin-top: 6rem; */
    padding-top: 10rem;
    top: 0;
    z-index: 0;*/
    }


                
                </style>
        
        
            <!-- logo -->
            
            <a href="./"><img src="assets/img/nuevo/logo-blanco.svg" alt="" class="logo-blanco"></a>
            <!-- logo -->
        

        
        
        <div class="flex-container fondo_5">
        


            <div class="container-fluid contenedor_leyendas_beneficios">
                <div class="row" >
                    
                </div>
            </div>
        
        
        <!--
        
                
        <div class="contenedor_carrusel_bene d-lg-block d-none">
            <div class="row">
                <div class="col-lg-12">
                   <a href="https://www.hermanmiller.com/es_mx/"> <img class="img-fluid d-block mr-auto ml-auto " src="assets/img/nuevo/beneficios/blanco/HERMAN-MILLER.svg" alt=""></a>
                    <p class="bg-beneficios"><?php print_r($textos[0]->contenido)?></p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12" style="">
                    <div class="borde-ben"></div>
                   <a href="https://sushiclub.com.mx/"> <img class="img-fluid d-block mr-auto ml-auto " src="assets/img/nuevo/beneficios/blanco/LOGO-SUSHICLUB.svg" alt=""></a>
                   <p class="bg-beneficios"><?php print_r($textos[1]->contenido)?></p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12" style="">
                    <div class="borde-ben"></div>
                   <a href="https://www.marista.edu.mx/"> <img class="img-fluid d-block mr-auto ml-auto " src="assets/img/nuevo/beneficios/blanco/LOGO-UNIVERSIDAD.svg" alt=""></a>
                   <p class="bg-beneficios"><?php print_r($textos[2]->contenido)?></p>
                </div>
            </div>
        
        
        
        
        </div>-->

           
        <div class="owl-carousel owl-frank">

                <?php 

                foreach($beneficios as $benefio){
                    print_r('<div class="col-lg-10"> <a href="https://www.hermanmiller.com/es_mx/">
                                        <img class="w-75 d-block mr-auto ml-auto pb-5" src="assets/img/img-banner/'.$benefio['imglogo'].'" alt=""></a>
                                        <p class="bg-beneficios mt-2">'.$benefio['parrafo'].'</p></div>
                                        ');
                };


                ?>


            </div>
        


        



        <H1 class="titulo-seccion text-uppercase" >BENEFICIOS<H1>
        
        </div>
        
        
        
        
        <script>


var owl = $('.owl-carousel');
            owl.owlCarousel({
                items:1,
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
                        nav:true
                    },
                    768:{
                        items:3,
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
        

 
var owl = $('.contenedor_carrusel_bene');
            owl.owlCarousel({
                items:1,
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
                        nav:true
                    },
                    768:{
                        items:3,
                        nav:true
                    },
                    1000:{
                        items:4,
                        nav:true,
                        loop:true
                    }
                }
            });
            $(".owl-prev").html('<span class="carousel-control-prev-icon" aria-hidden="true"></span>');
            $(".owl-next").html('<span class="carousel-control-next-icon" aria-hidden="true"></span>');




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

        $(".leyenda_beneficio").hide();

    
        captar_data(14)
        mostrar_leyenda(82);
function mostrar_leyenda(indice){
    console.log(indice);
    
    $(".leyenda_beneficio").hide(500);
        
if(indice == 82){
    $(".img-promo").attr("src", "assets/img/nuevo/beneficios/IMG-HM.png")
}

if(indice == 83){
    $(".img-promo").attr("src", "assets/img/nuevo/beneficios/IMG-SC.png")
}

if(indice == 84){
    $(".img-promo").attr("src", "assets/img/nuevo/beneficios/IMG-UM.png")
}

if(indice == 85){
    $(".img-promo").attr("src", "assets/img/nuevo/beneficios/IMG-UC.png")
}


    $(".leyenda_beneficio_"+indice).show(1000)
}
        
        cargar_imagenes()
          function cargar_imagenes() {
          
        
          var owl = $('.carrusel_imagenes_beneficios');
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
                        items:3,
                        nav:true
                    },
                    600:{
                        items:3,
                        nav:true
                    },
                    768:{
                        items:3,
                        nav:true
                    },
                    1000:{
                        items:4,
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
            