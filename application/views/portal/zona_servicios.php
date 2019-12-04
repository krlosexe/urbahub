<style>

@media (max-width : 1600px){
    .titulo-seccion {
    position: absolute;
    color: #fff;
    bottom: 51%;
    right: -13%;
    transform: rotate(-90deg);
    letter-spacing: 0.8rem;
    font-size: 4rem;
}

}

@media (max-width : 1500px){
    .titulo-seccion {
    position: absolute;
    color: #fff;
    bottom: 51%;
    right: -13%;
    transform: rotate(-90deg);
    letter-spacing: 0.8rem;
    font-size: 4rem;
}

}

@media (max-width: 1370px){
.titulo-seccion {
    position: absolute;
    color: #fff;
    bottom: 48%;
    right: -10%;
    transform: rotate(-90deg);
    letter-spacing: 0.35rem;
    font-size: 3rem;
}
}


@media (max-width: 1024px){
    .titulo-seccion {
    position: absolute;
    color: #fff;
    bottom: 48%;
    right: -14%;
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
.titulo-seccion {
    position: absolute;
    color: #fff;
    bottom: 50%;
    right: -32%;
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

.concepto_servicio h1{
    font-family: montserrat_servicios_titulo
}

.concepto_servicio p{
    font-family: montserrat_servicios_parrafo !important
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
    



<div class="flex-container fondo_1">



    <div class="container-fluid">
        <div class="row ">
                    <div class="col-lg-4 col-md-4 col-sm-5 col-xs-6">

                    </div>

                    <div class="col-lg-8  col-md-8 col-sm-5 col-xs-6  font-weight-bold">
                            <div class="row fondo_imagen_amarillas contenido-servicios">
                                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-6 d-none d-md-block">
                                        <H1 class="text-center pt-5 " style="font-family: montserrat_servicios_titulo" ><strong>
                <?php
                if (isset($textos))
                {
                   // print_r($textos);die();
                print_r($textos[0]->titulo_texto."<br>");
                }
                ?></strong></H1>
                                        <p class="text-justify pt-4 mr-auto ml-auto d-block" style="width: 90%; font-family:montserrat_servicios_parrafo" >
                                                                <?php
                                        if (isset($textos))
                                        {
                                        print_r($textos[0]->contenido."<br>");
                                        }
                                        ?>
                                        </p>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-5 col-xs-6 pt-4 pb-5 d-none d-md-block">
                                            <img class="img-fluid" id="img_servico_activo" src="" alt="Servicio activo">
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-6 concepto_servicio pl-4" id="concepto_servicio_1">
                                            <H1 class="text-center pt-5 text-capitalize"><strong>
                                            <?php
                if (isset($textos))
                {
                print_r($textos[1]->titulo_texto."<br>");
                }
                ?>
                                            </strong></H1>
                                            <p class="text-justify pt-4 mr-auto ml-auto d-block" style="width: 90%; font-family:montserrat_servicios_parrafo">
                                            <?php
                if (isset($textos))
                {
                print_r($textos[1]->contenido."<br>");
                }
                ?>
                                            </p>
                                    </div>

                                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-6 concepto_servicio pl-4" id="concepto_servicio_2">
                                          <H1 class="text-center pt-5 text-capitalize"><strong>
                                          <?php
                if (isset($textos))
                {
                print_r($textos[2]->titulo_texto."<br>");
                }
                ?>
                                            </strong></H1>
                                            <p class="text-justify pt-4 mr-auto ml-auto d-block" style="width: 90%; font-family:montserrat_servicios_parrafo">
                                            
                                            <?php
                                        if (isset($textos))
                                        {
                                        print_r($textos[2]->contenido."<br>");
                                        }
                                        ?>
                                            </p>
                                    </div>

                                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-6 concepto_servicio pl-4" id="concepto_servicio_3">
                                            <H1 class="text-center pt-5 text-capitalize"><strong>
                                            <?php
                if (isset($textos))
                {
                print_r($textos[3]->titulo_texto."<br>");
                }
                ?></strong></H1>
                                            <p class="text-justify pt-4 mr-auto ml-auto d-block" style="width: 90%; font-family:montserrat_servicios_parrafo">
                                            <?php
                                        if (isset($textos))
                                        {
                                        print_r($textos[3]->contenido."<br>");
                                        }
                                        ?>
                                            </p>
                                    </div>

                                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-6 concepto_servicio pl-4" id="concepto_servicio_4">
                                            <H1 class="text-center pt-5 text-capitalize"><strong>
                                            <?php
                if (isset($textos))
                {
                print_r($textos[4]->titulo_texto."<br>");
                }
                ?>
                                            </strong></H1>
                                            <p class="text-justify pt-4 mr-auto ml-auto d-block" style="width: 90%; font-family:montserrat_servicios_parrafo">
                                            <?php
                                        if (isset($textos))
                                        {
                                        print_r($textos[4]->contenido."<br>");
                                        }
                                        ?>
                                            </p>
                                    </div>

                                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-6 concepto_servicio pl-4" id="concepto_servicio_5">
                                            <H1 class="text-center pt-5 text-capitalize"><strong>
                                            <?php
                if (isset($textos))
                {
                print_r($textos[5]->titulo_texto."<br>");
                }
                ?>
                                            </strong></H1>
                                            <p class="text-justify pt-4 mr-auto ml-auto d-block" style="width: 90%; font-family:montserrat_servicios_parrafo">
                                            <?php
                                        if (isset($textos))
                                        {
                                        print_r($textos[5]->contenido."<br>");
                                        }
                                        ?>
                                            </p>
                                    </div>

                                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-6 concepto_servicio pl-4" id="concepto_servicio_6">
                                            <H1 class="text-center pt-5 text-capitalize"><strong>
                                            <?php
                if (isset($textos))
                {
                print_r($textos[6]->titulo_texto."<br>");
                }
                ?>
                                            </strong></H1>
                                            <p class="text-justify pt-4 mr-auto ml-auto d-block" style="width: 90%; font-family:montserrat_servicios_parrafo">
                                            <?php
                                        if (isset($textos))
                                        {
                                        print_r($textos[6]->contenido."<br>");
                                        }
                                        ?>
                                            </p>
                                    </div>

                                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-6 concepto_servicio pl-4" id="concepto_servicio_7">
                                            <H1 class="text-center pt-5 text-capitalize"><strong>
                                            <?php
                if (isset($textos))
                {
                print_r($textos[7]->titulo_texto."<br>");
                }
                ?>
                                            </strong></H1>
                                            <p class="text-justify pt-4 mr-auto ml-auto d-block" style="width: 90%; font-family:montserrat_servicios_parrafo">
                                            <?php
                                        if (isset($textos))
                                        {
                                        print_r($textos[7]->contenido."<br>");
                                        }
                                        ?>
                                            </p>
                                    </div>

                                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-6 concepto_servicio pl-4" id="concepto_servicio_8">
                                            <H1 class="text-center pt-5 text-capitalize"><strong>
                                            <?php
                if (isset($textos))
                {
                print_r($textos[8]->titulo_texto."<br>");
                }
                ?>
                                            </strong></H1>
                                            <p class="text-justify pt-4 mr-auto ml-auto d-block" style="width: 90%; font-family:montserrat_servicios_parrafo">
                                            <?php
                                        if (isset($textos))
                                        {
                                        print_r($textos[8]->contenido."<br>");
                                        }
                                        ?>
                                            </p>
                                    </div>

                                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-6 concepto_servicio pl-4" id="concepto_servicio_9">
                                            <H1 class="text-center pt-5 text-capitalize"><strong>
                                            <?php
                if (isset($textos))
                {
                print_r($textos[9]->titulo_texto."<br>");
                }
                ?>
                                            </strong></H1>
                                            <p class="text-justify pt-4 mr-auto ml-auto d-block" style="width: 90%; font-family:montserrat_servicios_parrafo">
                                            <?php
                                        if (isset($textos))
                                        {
                                        print_r($textos[9]->contenido."<br>");
                                        }
                                        ?>
                                            </p>
                                    </div>

                                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-6 concepto_servicio pl-4" id="concepto_servicio_10">
                                            <H1 class="text-center pt-5 text-capitalize"><strong>
                                            <?php
                if (isset($textos))
                {
                print_r($textos[10]->titulo_texto."<br>");
                }
                ?>
                                            </strong></H1>
                                            <p class="text-justify pt-4 mr-auto ml-auto d-block" style="width: 90%; font-family:montserrat_servicios_parrafo">
                                            <?php
                                        if (isset($textos))
                                        {
                                        print_r($textos[10]->contenido."<br>");
                                        }
                                        ?>
                                            </p>
                                    </div>


                                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-6 concepto_servicio pl-4" id="concepto_servicio_11">
                                            <H1 class="text-center pt-5 text-capitalize"><strong>
                                            <?php
                if (isset($textos))
                {
                print_r($textos[11]->titulo_texto."<br>");
                }
                ?>
                                            </strong></H1>
                                            <p class="text-justify pt-4 mr-auto ml-auto d-block" style="width: 90%; font-family:montserrat_servicios_parrafo">
                                            <?php
                                        if (isset($textos))
                                        {
                                        print_r($textos[11]->contenido."<br>");
                                        }
                                        ?>
                                            </p>
                                    </div>

                                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-6 concepto_servicio pl-4" id="concepto_servicio_12">
                                            <H1 class="text-center pt-5 text-capitalize"><strong>
                                            <?php
                if (isset($textos))
                {
                print_r($textos[12]->titulo_texto."<br>");
                }
                ?>
                                            </strong></H1>
                                            <p class="text-justify pt-4 mr-auto ml-auto d-block" style="width: 90%; font-family:montserrat_servicios_parrafo">
                                            <?php
                                        if (isset($textos))
                                        {
                                        print_r($textos[12]->contenido."<br>");
                                        }
                                        ?>
                                            </p>
                                    </div>

                                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-6 concepto_servicio pl-4" id="concepto_servicio_13">
                                            <H1 class="text-center pt-5 text-capitalize"><strong>
                                            <?php
                if (isset($textos))
                {
                print_r($textos[13]->titulo_texto."<br>");
                }
                ?>
                                            </strong></H1>
                                            <p class="text-justify pt-4 mr-auto ml-auto d-block" style="width: 90%; font-family:montserrat_servicios_parrafo">
                                            <?php
                                        if (isset($textos))
                                        {
                                        print_r($textos[13]->contenido."<br>");
                                        }
                                        ?>
                                            </p>
                                    </div>

                                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-6 concepto_servicio pl-4" id="concepto_servicio_14">
                                            <H1 class="text-center pt-5 text-capitalize"><strong>
                                            <?php
                if (isset($textos))
                {
                print_r($textos[14]->titulo_texto."<br>");
                }
                ?>
                                            </strong></H1>
                                            <p class="text-justify pt-4 mr-auto ml-auto d-block" style="width: 90%; font-family:montserrat_servicios_parrafo">
                                            <?php
                                        if (isset($textos))
                                        {
                                        print_r($textos[14]->contenido."<br>");
                                        }
                                        ?>
                                            </p>
                                    </div>

                                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-6 concepto_servicio pl-4" id="concepto_servicio_15">
                                            <H1 class="text-center pt-5 text-capitalize"><strong>
                                            <?php
                if (isset($textos))
                {
                print_r($textos[15]->titulo_texto."<br>");
                }
                ?>
                                            </strong></H1>
                                            <p class="text-justify pt-4 mr-auto ml-auto d-block" style="width: 90%; font-family:montserrat_servicios_parrafo">
                                            <?php
                                        if (isset($textos))
                                        {
                                        print_r($textos[15]->contenido."<br>");
                                        }
                                        ?>
                                            </p>
                                    </div>

                                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-6 concepto_servicio pl-4" id="concepto_servicio_16">
                                            <H1 class="text-center pt-5 text-capitalize"><strong>
                                            <?php
                if (isset($textos))
                {
                print_r($textos[16]->titulo_texto."<br>");
                }
                ?>
                                            </strong></H1>
                                            <p class="text-justify pt-4 mr-auto ml-auto d-block" style="width: 90%; font-family:montserrat_servicios_parrafo">
                                            <?php
                                        if (isset($textos))
                                        {
                                        print_r($textos[16]->contenido."<br>");
                                        }
                                        ?>
                                            </p>
                                    </div>

                                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-6 concepto_servicio pl-4" id="concepto_servicio_17">
                                            <H1 class="text-center pt-5 text-capitalize"><strong>
                                            <?php
                if (isset($textos))
                {
                print_r($textos[17]->titulo_texto."<br>");
                }
                ?>
                                            </strong></H1>
                                            <p class="text-justify pt-4 mr-auto ml-auto d-block" style="width: 90%; font-family:montserrat_servicios_parrafo">
                                            <?php
                                        if (isset($textos))
                                        {
                                        print_r($textos[17]->contenido."<br>");
                                        }
                                        ?>
                                            </p>
                                    </div>

                                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-6 concepto_servicio pl-4" id="concepto_servicio_18">
                                            <H1 class="text-center pt-5 text-capitalize"><strong>
                                            <?php
                if (isset($textos))
                {
                print_r($textos[18]->titulo_texto."<br>");
                }
                ?>
                                            </strong></H1>
                                            <p class="text-justify pt-4 mr-auto ml-auto d-block" style="width: 90%; font-family:montserrat_servicios_parrafo">
                                            <?php
                                        if (isset($textos))
                                        {
                                        print_r($textos[18]->contenido."<br>");
                                        }
                                        ?>
                                            </p>
                                    </div>
                            </div>
                    </div>
        </div>
    </div>

            <img class="img-franja-amarilla" src="assets/img/nuevo/FRANJA-SERVICIOS.svg" alt="">
    


        <div class="owl-carousel owl-reponsive carrusel_servicios">


        
        <?php 
                if (isset($imagenes))
                {
                    $id = 1;
              foreach($imagenes as $bl_img){
                if($bl_img['seccion'] == 2){
                  print_r('<div class="col-sm-12 col-md-12 col-lg-12 col-xl-12"><div class="row justify-content-center text-light pt-3  pb-3">
                  <div class="col-lg-12"><img onmouseover=(mostrar_leyenda('.$id.')) class="w-100 mr-auto ml-auto d-block img-servicios" src="assets/img/biblioteca_imagenes/'.$bl_img['nombre_imagen'].'" alt="'.$bl_img['etiqueta'].'">
                  </div></div></div>');
                }

                $id = $id +1;
              }}else{
                  print_r("no llego");
              }?>


        </div>


        <H1 class="titulo-seccion text-uppercase" >Servicios<H1>


</div>




<script>


$(function(){
           $(".banner_1").removeClass('d-lg-block');
           $(".banner_1").hide();
           $(".flex-container").css("height", "100%")
        });


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


charge_view_services()

re = 0

$(".concepto_servicio").hide();
mostrar_leyenda(1)
function mostrar_leyenda(indice){
    console.log(indice);
    $(".concepto_servicio").hide();    
    $("#concepto_servicio_"+indice).fadeIn(1000)

    $("#img_servico_activo").attr("src", "assets/img/nuevo/leyenda_servicios/"+indice+".svg")
}



 
 var owl = $('.carrusel_servicios');
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
                items:8,
                nav:true
            },
            1000:{
                items:13,
                nav:true,
                loop:true
            }
        }
    });
    $(".owl-prev").html('<span class="carousel-control-prev-icon" aria-hidden="true"></span>');
    $(".owl-next").html('<span class="carousel-control-next-icon" aria-hidden="true"></span>');
    












    /*})*/





        
        </script>
    