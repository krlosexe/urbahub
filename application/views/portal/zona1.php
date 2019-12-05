


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  
<meta name="google-site-verification" content="mLr-d0AbO3ZgT3itKi8VY_xEVTNCuDlQXTuhk8kab0s" />

<meta name="google-site-verificationsssssssss" content="Jccsyxlh5P8qAhvf7Id7ZvrHD3E-jkWvZJFz1HUM1i0" />
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-129800976-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-129800976-1');
</script>

<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-PCNZNBG');</script>
<!-- End Google Tag Manager -->

<?php

if (isset($meta_data))

{
foreach($meta_data as $meta){


  print_r('<title>'.$meta['titulo'].'</title>');
  print_r('<meta name="keywords" content="'.$meta['keywords'].'">');
  print_r('<meta name="description" content="'.$meta['descripcion'].'">
    ');




}}?>




  
	


	

<link rel="stylesheet" href="assets/css/bootstrap.css">


<link rel="stylesheet" href="assets/css/owl.carousel.css">
<link rel="stylesheet" href="assets/css/owl.theme.default.css">

<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css"  integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
<style>

@import "color-schemer";
@import "compass";
@import "compass/css3/animation";

@import url('https://fonts.googleapis.com/css?family=Raleway');


@font-face {
  font-family: "montserrat";
  src: url("assets/font/Montserrat-ExtraLight.ttf");

}

@font-face {
  font-family: "montserrat_t_seccion";
  src: url("assets/font/Montserrat-Thin.ttf");

} 

@font-face {
  font-family: "montserrat_banner";
  src: url("assets/font/Montserrat-Light.ttf");

} 

@font-face {
  font-family: "montserrat_servicios_titulo";
  src: url("assets/font/Montserrat-Bold.ttf");

} 

@font-face {
  font-family: "montserrat_servicios_parrafo";
  src: url("assets/font/Montserrat-Regular.ttf");

} 

/*



  src: url("assets/font/Montserrat-Black.ttf");
  src: url("assets/font/Montserrat-BlackItalic.ttf");
  src: url("assets/font/Montserrat-Bold.ttf");
  src: url("assets/font/Montserrat-BoldItalic.ttf");
  src: url("assets/font/Montserrat-ExtraBold.ttf");
  src: url("assets/font/Montserrat-ExtraBoldItalic.ttf");
  src: url("assets/font/Montserrat-ExtraLight.ttf");
  src: url("assets/font/Montserrat-ExtraLightItalic.ttf");
  src: url("assets/font/Montserrat-Italic.ttf");

  src: url("assets/font/Montserrat-Light.ttf");
  src: url("assets/font/Montserrat-LightItalic.ttf");
  src: url("assets/font/Montserrat-Medium.ttf");
  src: url("assets/font/Montserrat-MediumItalic.ttf");
  src: url("assets/font/Montserrat-Regular.ttf");
  src: url("assets/font/Montserrat-SemiBold.ttf");
  src: url("assets/font/Montserrat-SemiBoldItalic.ttf");
  src: url("assets/font/Montserrat-Thin.ttf");
  src: url("assets/font/Montserrat-ThinItalic.ttf");


*/



$defaultSeconds: 3s;


h1 h2 h3 h4 h5 h6 p a {
	font-family: "montserrat" !important ;  
}


body {
	margin: 0;
	padding: 0;
/*	font-family: 'Raleway', sans-serif;*/
	font-family: "montserrat" !important;
}
.flex-container {
	position: absolute;
	height: 70%;
	width: 100%;
	display: -webkit-flex; /* Safari */
	display: flex;
	overflow: hidden;
	font-family: "montserrat" !important;
	@media screen and (max-width: 768px) {
		flex-direction: column;
	}
}



.letra_azul{
	color: rgb(29, 33, 53) !important
}

.flex-title {
	letter-spacing: 1rem;
	color: #f1f1f1;
	position: relative;
	font-size: 4vw;
	margin: auto;
	text-align: center;
	transform: rotate(-90deg);
    top: 69.2%;
	left: 40%;
	-webkit-transition: all 500ms ease;
	-moz-transition: all 500ms ease;
	-ms-transition: all 500ms ease;
	-o-transition: all 500ms ease;
	transition: all 500ms ease;
	@media screen and (max-width: 768px) {
		transform: rotate(-90deg); !important;
	}
}
.flex-about {
	opacity: 0;
	color: #f1f1f1;
	position: relative;
	width: 70%;
	font-size: 2vw;
	padding: 5%;
	top: 20%;
	border: 2px solid #f1f1f1;
	border-radius: 10px;
	line-height: 1.3;
	margin: auto;
	text-align: left;
	transform: rotate(0deg);
	-webkit-transition: all 500ms ease;
	-moz-transition: all 500ms ease;
	-ms-transition: all 500ms ease;
	-o-transition: all 500ms ease;
	transition: all 500ms ease;
	@media screen and (max-width: 768px) {
		padding: 0%;
		border: 0px solid #f1f1f1;
	}
}


.flex-slide:hover {
	width: 75%;
}

.flex-slide {
	width: 16.6%;
	cursor: pointer;
	-webkit-transition: all 500ms ease;
	-moz-transition: all 500ms ease;
	-ms-transition: all 500ms ease;
	-o-transition: all 500ms ease;
	transition: all 500ms ease;
	@media screen and (max-width: 768px) {
		overflow: auto;
		overflow-x: hidden;
	}
}
.flex-slide p {
	@media screen and (max-width: 768px) {
		font-size: 2em;
	}
} 
.flex-slide ul li {
	@media screen and (max-width: 768px) {
		font-size: 2em;
	}
} 

.home {
	background: linear-gradient(rgba(0, 0, 0, 0), rgba(0, 0, 0, 0)), url('assets/img/biblioteca_imagenes/fondo_servicios.png');
	background-size: cover;
	background-position: center center;
	background-attachment: fixed;
	@media screen and (min-width: 768px) {
		@include animation-properties((animation: aboutFlexSlide, animation-duration: $defaultSeconds, animation-iteration-count: 1, animation-delay: 0s));
	}
}

.fondo_1 {
	background: linear-gradient(rgba(0, 0, 0, 0), rgba(0, 0, 0, 0)), url('assets/img/biblioteca_imagenes/fondo_servicios.png');
	background-size: cover;
	background-position: left;
	@media screen and (min-width: 768px) {
		@include animation-properties((animation: aboutFlexSlide, animation-duration: $defaultSeconds, animation-iteration-count: 1, animation-delay: 0s));
	}
}

.fondo_2 {
	background: linear-gradient(rgba(255, 255, 255, 0.5), rgba(255, 255, 255, 0.5)), url('assets/img/biblioteca_imagenes/fondo_planes.png');
	background-size: cover;
	background-position: left;
	@media screen and (min-width: 768px) {
		@include animation-properties((animation: aboutFlexSlide, animation-duration: $defaultSeconds, animation-iteration-count: 1, animation-delay: 0s));
	}
}

.fondo_3 {
	background: url('assets/img/biblioteca_imagenes/fondo_galeria.png');
	background-size: cover;
	background-position: left;
	@media screen and (min-width: 768px) {
		@include animation-properties((animation: aboutFlexSlide, animation-duration: $defaultSeconds, animation-iteration-count: 1, animation-delay: 0s));
	}
}

.fondo_4 {
	background: linear-gradient(rgba(255, 255, 255, 0.5), rgba(255, 255, 255, 0.5)), url('assets/img/biblioteca_imagenes/fondo_noticias.png');
	background-size: cover;
	background-position: center;
	@media screen and (min-width: 768px) {
		@include animation-properties((animation: aboutFlexSlide, animation-duration: $defaultSeconds, animation-iteration-count: 1, animation-delay: 0s));
	}
}

.fondo_5 {
	background: url('assets/img/biblioteca_imagenes/fondo_beneficios.png');
	background-size: cover;
	background-position: 82% 0;
	@media screen and (min-width: 768px) {
		@include animation-properties((animation: aboutFlexSlide, animation-duration: $defaultSeconds, animation-iteration-count: 1, animation-delay: 0s));
	}
}

.fondo_6 {
	background: linear-gradient(rgba(255, 255, 255, 0.5), rgba(255, 255, 255, 0.5)),url('assets/img/biblioteca_imagenes/fondo_contacto.png');
	background-size: cover;
	background-position: left;
	@media screen and (min-width: 768px) {
		@include animation-properties((animation: aboutFlexSlide, animation-duration: $defaultSeconds, animation-iteration-count: 1, animation-delay: 0s));
	}
}


.banner_1 {
	background: linear-gradient(rgba(0, 0, 0, 0.79), rgba(0, 0, 0, 0.79)),url('assets/img/nuevo/banner_1.jpg');
  background-size: cover;
	background-size: 100% 297%;
	background-position: 100% 56%;
	position: absolute;
  height: 30%;
	bottom: 0;
	
	@media screen and (min-width: 768px) {
		@include animation-properties((animation: aboutFlexSlide, animation-duration: $defaultSeconds, animation-iteration-count: 1, animation-delay: 0s));
	}
}


@keyframes aboutFlexSlide {
	0% {
		-webkit-flex-grow: 1;
		flex-grow: 1;
	}
	50% {
		-webkit-flex-grow: 3;
		flex-grow: 3;
	}
	100% {
		-webkit-flex-grow: 1;
		flex-grow: 1;
	}
}

.flex-title-home {
	@media screen and (min-width: 768px) {
		transform: rotate(90deg);
		top: 15%;
		@include animation-properties((animation: homeFlextitle, animation-duration: $defaultSeconds, animation-iteration-count: 1, animation-delay: 0s));
	}
}



@keyframes homeFlextitle {
	0% {
		transform: rotate(90deg);
		top: 15%;
	}
	50% {
		transform: rotate(0deg);
		top: 15%;
	}
	100% {
		transform: rotate(90deg);
		top: 15%;
	}
}

.flex-about-home {
	opacity: 0;
	@media screen and (min-width: 768px) {
		@include animation-properties((animation: flexAboutHome, animation-duration: $defaultSeconds, animation-iteration-count: 1, animation-delay: 0s));
	}
}

@keyframes flexAboutHome {
	0% {
		opacity: 0;
	}
	50% {
		opacity: 1;
	}
	100% {
		opacity: 0;
	}
}



.about {
	background: linear-gradient(rgba(0, 0, 0, 0), rgba(0, 0, 0, 0)), url('assets/img/biblioteca_imagenes/fondo_planes.png');
	background-size: cover;
	background-position: center center;
	background-attachment: fixed;	
}
.contact-form {
	width: 100%;
}
input {
	width: 100%;
}
textarea {
	width: 100%;	
}
.contact {
	background: linear-gradient(rgba(0, 0, 0, 0), rgba(0, 0, 0, 0)), url('assets/img/biblioteca_imagenes/fondo_noticias.png');
	background-size: cover;
	background-position: center center;
	background-attachment: fixed;
}
.work {
	background: linear-gradient(rgba(0, 0, 0, 0), rgba(0, 0, 0, 0)), url('assets/img/biblioteca_imagenes/fondo_galeria.png');
	background-size: cover;
	background-position: center center;
	background-attachment: fixed;
}


.contact {
	background: linear-gradient(rgba(0, 0, 0, 0), rgba(0, 0, 0, 0)), url('assets/img/biblioteca_imagenes/fondo_noticias.png');
	background-size: cover;
	background-position: center center;
	background-attachment: fixed;
}



// Preloader
.spinner {
	position: fixed;
	top: 0;
	left: 0;
	background: #222;
	height: 100%;
	width: 100%;
	z-index: 11;
	margin-top: 0;
	color: #fff;
	font-size: 1em;
}

.cube1, .cube2 {
  background-color: #fff;
  width: 15px;
  height: 15px;
  position: absolute;
  top: 0;
  left: 0;
  
  -webkit-animation: sk-cubemove 1.8s infinite ease-in-out;
  animation: sk-cubemove 1.8s infinite ease-in-out;
}

.cube2 {
  -webkit-animation-delay: -0.9s;
  animation-delay: -0.9s;
}

@-webkit-keyframes sk-cubemove {
  25% { -webkit-transform: translateX(42px) rotate(-90deg) scale(0.5) }
  50% { -webkit-transform: translateX(42px) translateY(42px) rotate(-180deg) }
  75% { -webkit-transform: translateX(0px) translateY(42px) rotate(-270deg) scale(0.5) }
  100% { -webkit-transform: rotate(-360deg) }
}

@keyframes sk-cubemove {
  25% { 
    transform: translateX(42px) rotate(-90deg) scale(0.5);
    -webkit-transform: translateX(42px) rotate(-90deg) scale(0.5);
  } 50% { 
    transform: translateX(42px) translateY(42px) rotate(-179deg);
    -webkit-transform: translateX(42px) translateY(42px) rotate(-179deg);
  } 50.1% { 
    transform: translateX(42px) translateY(42px) rotate(-180deg);
    -webkit-transform: translateX(42px) translateY(42px) rotate(-180deg);
  } 75% { 
    transform: translateX(0px) translateY(42px) rotate(-270deg) scale(0.5);
    -webkit-transform: translateX(0px) translateY(42px) rotate(-270deg) scale(0.5);
  } 100% { 
    transform: rotate(-360deg);
    -webkit-transform: rotate(-360deg);
  }
}


.p_banner{
	letter-spacing: 3px;
    line-height: 1.5rem;
}

.logo-blanco{
			position: absolute;
			top: 1rem;
			left: 5rem;
			width: 10rem;
			z-index: 100
}


.carrusel_servicios{
		background: #00263b;
    
    position: absolute;
    bottom: 0;
}

.img-servicios{
	-webkit-transform: scale(1.12);
	transform: scale(1.12);
}

.img-franja-amarilla{
	height: 30rem;
    position: fixed;
    right: 0rem;
    top: 1rem;
    width: 82rem;
}

.fondo_imagen_amarilla{
		background: linear-gradient(rgba(0, 0, 0, 0), rgba(0, 0, 0, 0)), url(assets/img/nuevo/FRANJA-SERVICIOS.svg);
    background-size: contain;
    background-position: left;
    background-repeat: no-repeat;
    height: 30rem;
    width: 100%;
    position: absolute;}



.titulo-seccion{
	position: absolute;
    color: #fff;
    bottom: 51%;
    right: -10%;
    transform: rotate(-90deg);
    letter-spacing: 0.8rem;
    font-size: 4rem;
}

.contenido-servicios {
    position: absolute;
    top: 5rem;
    left: 20rem;
    z-index: 1;
    transform: scale(0.8);
    background: #f8ed3800;
}


.img-franja-amarilla {
    height: 20rem;
    position: fixed;
    right: -4rem;
    top: 6rem;
    -webkit-transform: scaleX(1.8);
    transform: scaleX(1.8);
    width: 60rem;
}


.contenedor_opciones_planes{
    height: 60%;
    position: absolute;
    top: 20%;
    width: 90%;
    left: 0%;
}



.btn-op-planes{
  width: 90%;
  -webkit-transition: 0.5s;
  transition: 0.5s;
}


.btn-op-planes:hover{
  width: 100%;
  -webkit-transition: 0.5s;
  transition: 0.5s;
}

.titulo-plan, .costo-plan{
    font-size: 5rem;
    font-weight: bolder
}

.descripcion-plan{
  font-weight: bolder
}

.lista_bondades_plan{
    font-size: 1.3rem;
    color: #ffeb3b;
    font-weight: 700;
}

.lista_bondades_plan span{
  color: black;
}


.letra-pequeña-planes{
  background: #00263b !important;
    border-radius: 3px;
    padding-top: 15px;
    padding-bottom: 0px;
    padding-left: 5%;
}

.letra-pequeña-planes p{
  line-height: 10px;
  
}


.btn-op-planes {
    width: 90%;
    -webkit-transition: 0.5s;
    transition: 0.5s;
    font-size: 1.3rem;
}


.titulo-seccion-2 {
    position: absolute;
    color: #00263b;
    bottom: 29% !important;
    right: -10%;
    transform: rotate(-90deg);
    letter-spacing: 0.8rem;
    font-size: 4rem;
}





.contenedor_carrusel_galeria{
            height: 60%;
            position: absolute;
            top: 20%;
            width: 90%;
            left: 5%;
        }

				.titulo{
    color: #00324a;
    font-weight: bold;
    font-size: 1.4rem;
    line-height: 25px;
    padding-bottom: 1rem;
}



.contenedor_noticia{
   background: #00324a;
   padding: 3%;
   color: #fff;
 }   


 .contenedor_imagen {
    overflow: hidden;
    width: 100%;
    height: 20rem;
}    


 .contenedor_imagen img{
  -webkit-transform: scale(1.5);
  transform: scale(1.5);
  -webkit-transition: 0.5s;
  transition: 0.5s;
  margin-left: auto;
  margin-right: auto;
 }

 .contenedor_imagen img:hover{
  -webkit-transform: scale(1);
  transform: scale(1);
  -webkit-transition: 0.5s;
  transition: 0.5s
 }

 
#opciones_blog button{
  font-size: 1.5rem;
}


#img_servico_activo{
    margin-top: 5rem;
    transform: scale(1.6);
    -webkit-transform: scale(1.6);
    
}


@media (max-width: 1600px) {
.contenido-servicios {
  position: absolute;
  top: 5rem;
  left: 8rem;
  z-index: 1;
  transform: scale(0.8);
}

#img_servico_activo{
    margin-top: 5rem;
    transform: scale(1.6);
    -webkit-transform: scale(1.6);
    
}

.contenedor_opciones_planes {
    height: 60%;
    position: absolute;
    top: 15%;
    width: 90%;
    left: 0%;
}

.titulo-plan, .costo-plan {
    font-size: 4rem;
    font-weight: bolder;
}

.contenedor_imagen {
    overflow: hidden;
    width: 100%;
    height: 19rem;
}

.contenedor_carrusel_galeria {
    height: 60%;
    position: absolute;
    top: 22%;
    width: 90%;
    left: 5%;
}

}

@media (max-width: 1500px) {
.contenido-servicios {
  position: absolute;
  top: 5rem;
  left: 4rem;
  z-index: 1;
  transform: scale(0.8);
}

#img_servico_activo{
    margin-top: 5rem;
    transform: scale(1.8);
    -webkit-transform: scale(1.8);
    
}

.contenedor_opciones_planes {
    height: 60%;
    position: absolute;
    top: 15%;
    width: 90%;
    left: 0%;
}

.titulo-plan, .costo-plan {
    font-size: 4rem;
    font-weight: bolder;
}

.titulo-seccion {
    position: absolute;
    color: #fff;
    bottom: 51%;
    right: -13%;
    transform: rotate(-90deg);
    letter-spacing: 0.8rem;
    font-size: 4rem;
}

.contenedor_imagen {
    overflow: hidden;
    width: 100%;
    height: 19rem;
}

.contenedor_carrusel_galeria {
    height: 60%;
    position: absolute;
    top: 22%;
    width: 90%;
    left: 5%;
}


}


@media (max-width: 1370px) {

body{
  /*filter: invert(100)*/
}


.p_banner {
		letter-spacing: 0px;
    line-height: 1rem;
    font-family: montserrat_banner;
}

.flex-title {
  font-family: montserrat_t_seccion;
    letter-spacing: 1rem;
    color: #f1f1f1;
    position: relative;
    font-size: 3.3vw;
    margin: auto;
    text-align: center;
    transform: rotate(-90deg);
    top: 68.2%;
    left: 40%;
    -webkit-transition: all 500ms ease;
    -moz-transition: all 500ms ease;
    -ms-transition: all 500ms ease;
    -o-transition: all 500ms ease;
    transition: all 500ms ease;
    @media screen and (: ;
    max-width: 768px) {;
    transform: rotate(-90deg);
    !important: ;
}}

.img-franja-amarilla {
    height: 19rem;
    position: fixed;
    right: 1rem;
    top: 3.5rem;
    -webkit-transform: scaleX(1.2);
    transform: scaleX(1.2);
    width: 60rem;
}


.titulo-seccion {
    position: absolute;
    color: #fff;
    bottom: 47%;
    right: -11%;
    transform: rotate(-90deg);
    letter-spacing: 0.35rem;
    font-size: 3.5rem;
}

.titulo-seccion {
    position: absolute;
    color: #fff;
    bottom: 27%;
    right: -10%;
    transform: rotate(-90deg);
    letter-spacing: 0.35rem;
    font-size: 3.5rem;
}


.contenido-servicios {
    position: absolute;
    top: 1.5rem;
    left: -2rem;
    z-index: 1;
    transform: scale(0.85);
}

.titulo-plan, .costo-plan {
    font-size: 2.3rem;
    font-weight: bolder;
}

.lista_bondades_plan {
    font-size: 1.1rem;
    color: #ffeb3b;
    font-weight: 700;
}

.letra-pequeña-planes p {
    line-height: 5px;
}

.btn-op-planes {
    width: 90%;
    -webkit-transition: 0.5s;
    transition: 0.5s;
    font-size: 0.75rem;
}

.contenedor_imagen {
    overflow: hidden;
    width: 100%;
    height: 15rem;
}

.contenedor_imagen img {
    transform: scale(1.5);
    -webkit-transform: scale(1.7);
}

.contenedor_carrusel_galeria {
    height: 60%;
    position: absolute;
    top: 30%;
    width: 90%;
    left: 5%;
}


.titulo {
    color: #fff;
    font-weight: bold;
    font-size: 1rem;
    line-height: 15px;
    padding-bottom: 1rem;
}

.contenedor_imagen {
    width: 100%;
    height: 15rem;
    margin-left: auto;
    margin-right: auto;
    display: block;
    overflow: hidden;
}


#opciones_blog button{
  font-size: 1.05rem;
}

.logo-blanco {
    position: absolute;
    top: 1rem;
    left: 5rem;
    width: 8rem !important;
    z-index: 100;
}
}




@media (max-width: 1024px){
.img-franja-amarilla {
    height: 19rem;
    position: fixed;
    right: 1rem;
    top: 9.5rem;
    -webkit-transform: scaleX(1.2);
    transform: scaleX(1.1);
    width: 60rem;
}

.contenido-servicios {
    position: absolute;
    top: 6.5rem;
    left: -8rem;
    z-index: 1;
    transform: scale(0.85);
}

.titulo-seccion {
    position: absolute;
    color: #fff;
    bottom: 35%;
    right: -14%;
    transform: rotate(-90deg);
    letter-spacing: 0.35rem;
    font-size: 3.5rem;
}

.contenedor_opciones_planes {
    height: 60%;
    position: absolute;
    top: 22%;
    width: 90%;
    left: 0%;
}

}


@media (max-width: 800px){
  .logo-blanco {
    position: absolute;
    top: 1rem;
    left: 1.5rem;
    width: 6rem;
    z-index: 100;
}

.img-franja-amarilla {
    height: 27rem;
    position: fixed;
    right: 1rem;
    top: 2.5rem;
    -webkit-transform: scaleX(0);
    transform: scaleX(0);
    width: 43rem;
}
.contenido-servicios {
    position: absolute;
    top: 0.5rem;
    left: -16rem;
    z-index: 1;
    transform: scale(0.7);
    background: #f8ed38;
}


.titulo-plan, .costo-plan {
    font-size: 2.3rem;
    font-weight: bolder;
}


.descripcion-plan {
    font-weight: bolder;
    font-size: 1.5rem;
}

.lista_bondades_plan {
    font-size: 1rem;
    color: #ffeb3b;
    font-weight: 700;
}

.titulo-seccion {
    position: absolute;
    color: #fff;
    bottom: 27%;
    right: -19%;
    transform: rotate(-90deg);
    letter-spacing: 0.35rem;
    font-size: 3.5rem;
}

.letra-pequeña-planes p {
    line-height: 20px;
}
}


@media (max-width: 500px){

  .banner_1 {
    background: linear-gradient(rgba(0, 0, 0, 0.79), rgba(0, 0, 0, 0.79)),url(assets/img/nuevo/banner_1.jpg);
    position: absolute;
    height: 65%;
    bottom: -35%;
    background-size: cover;
    background-size: 100% 297%;
    background-position: 100% 56%;
    @media screen and (: ;
    min-width: 768px) {;
    @include animation-properties((animation: aboutFlexSlide, animation-duration: $defaultSeconds, animation-iteration-count: 1, animation-delay: 0s));
    }: ;
}

.p_banner {
    letter-spacing: 0px;
    line-height: 1.4rem;
}

}


.contenedor_redes{
  position: absolute;
    right: 1rem;
    top: 1rem;
}


#opciones_menu_cotainer{

  position: absolute;
    z-index: 5;
    height: 100%;
    background: #2f2f2fc4;
    display: none
}

.contenedor_opciones_menu{
  padding-top: 6rem;
}

.whatsapp-container {
    background: none;
    border-radius: 50%;
    bottom: 80pt;
    display: inline;
    height: 45pt;
    padding: 0px;
    position: fixed;
    right: 10pt;
    top: auto;
    width: 25pt;
    z-index: 2147483646;
}

@media (min-width: 2048px){
  #container_log img{
      width: 7.6rem;
    }


  .logo-blanco_login {
    position: absolute;
    top: -5rem;
    left: 2.1rem;
    width: 8rem !important;
    z-index: 100;
}

    #container_log{
    position: absolute;
    z-index: 5;
    height: 100%;
    background: #2f2f2fc4; z-index: 10; 
    width: 100%;
    }

    #container_log .botones{
    position: absolute;
    z-index: 11;
    width: 13rem;
    top: 9rem;
    transform: scale(1.2);
    left: -8.5rem;
    }

    #container_log .botones button { 
    margin-top : 0.5rem;
    padding: 0.5rem;

    }

    #container_log .text{
    width: 10rem;
      position: absolute;
      z-index: 11;
      left: 5.1rem;
      transform: scale(1.1);
      top: 9rem;;
    }

    #container_log .text span{
    letter-spacing: 0.3rem;
    }


    #container_log .form{
    width: 12rem;
      position: absolute;
      z-index: 11;
      right: 13rem;
      top: 9.5rem;
    }

    #container_log .redes{
      position: absolute;
      z-index: 11;
      left: -4.5rem;
      top: 18.5rem;
    }

    #container_log .row{
    margin-top: 5rem;
    }

    #container_log .foto_form{
      position: absolute;
    z-index: 11;
    right: 14.5rem;
    width: 9rem;
    border: #fff solid;
    top: 2rem;
    border-radius: 100%;
    }

    #container_log .mensaje{
    position: absolute;
      z-index: 11;
      right: 2rem;
      top: 10.2rem;
      font-weight: 600;
      line-height: 10px;
    }


    #container_log .btn_salir{
      position: absolute;
    right: 53rem;
    top: 5rem;
    z-index: 11;
    transform: scale(1.7);
    }


}

@media (max-width: 1920px){
  .logo-blanco_login {
    position: absolute;
    top: -6rem;
    left: 2.1rem;
    width: 8rem !important;
    z-index: 100;
}

    #container_log{
    position: absolute;
    z-index: 5;
    height: 100%;
    background: #2f2f2fc4; z-index: 10; 
    width: 100%;
    }

    #container_log .botones{
    position: absolute;
    z-index: 11;
    width: 13rem;
    top: 9rem;
    transform: scale(1.2);
    left: -8.5rem;
    }

    #container_log .botones button { 
    margin-top : 0.5rem;
    padding: 0.5rem;

    }

    #container_log .text{
    width: 10rem;
      position: absolute;
      z-index: 11;
      left: 5.1rem;
      transform: scale(1.1);
      top: 9rem;;
    }


    #container_log .text img{
      width: 7.6rem;
    }

    #container_log .text span{
    letter-spacing: 0.3rem;
    }


    #container_log .form{
    width: 12rem;
      position: absolute;
      z-index: 11;
      right: 11rem;
      top: 9.5rem;
    }

    #container_log .redes{
      position: absolute;
      z-index: 11;
      left: -4.5rem;
      top: 18.5rem;
    }

    #container_log .row{
    margin-top: 5rem;
    }

    #container_log .foto_form{
      position: absolute;
    z-index: 11;
    right: 12.5rem;
    width: 9rem;
    border: #fff solid;
    top: 2rem;
    border-radius: 100%;
    }

    #container_log .mensaje{
    position: absolute;
      z-index: 11;
      right: 2rem;
      top: 10.2rem;
      font-weight: 600;
      line-height: 10px;
    }


    #container_log .btn_salir{
    position: absolute;
      right: 48rem;
      top: 5rem;
      z-index: 11;
      transform: scale(1.7);
    }


}

@media (max-width: 1600px){

  .logo-blanco_login {
    position: absolute;
    top: -5rem;
    left: 2.1rem;
    width: 8rem !important;
    z-index: 100;
}

        #container_log{
        position: absolute;
        z-index: 5;
        height: 100%;
        background: #2f2f2fc4; z-index: 10; 
        width: 100%;
        }

        #container_log .botones{
        position: absolute;
        z-index: 11;
        width: 13rem;
        top: 9rem;
        transform: scale(1.2);
        left: -8.5rem;
        }

        #container_log .botones button { 
        margin-top : 0.5rem;
        padding: 0.5rem;

        }

        #container_log .text{
        width: 10rem;
          position: absolute;
          z-index: 11;
          left: 5.1rem;
          transform: scale(1.1);
          top: 9rem;;
        }

        #container_log .text span{
        letter-spacing: 0.3rem;
        }


        #container_log .form{
        width: 12rem;
          position: absolute;
          z-index: 11;
          right: 4rem;
          top: 9.5rem;
        }

        #container_log .redes{
          position: absolute;
          z-index: 11;
          left: -4.5rem;
          top: 18.5rem;
        }

        #container_log .row{
        margin-top: 5rem;
        }

        #container_log .foto_form{
          position: absolute;
          z-index: 11;
          right: 5.5rem;
          width: 9rem;
          border: #fff solid;
          top: 2rem;
          border-radius: 100%;
        }

        #container_log .mensaje{
        position: absolute;
          z-index: 11;
          right: 2rem;
          top: 10.2rem;
          font-weight: 600;
          line-height: 10px;
        }


        #container_log .btn_salir{
        position: absolute;
          right: 35rem;
          top: 5rem;
          z-index: 11;
          transform: scale(1.7);
        }


}


@media (max-width: 1440px){

  .logo-blanco_login {
    position: absolute;
    top: -5rem;
    left: 2.1rem;
    width: 8rem !important;
    z-index: 100;
}

    #container_log{
    position: absolute;
    z-index: 5;
    height: 100%;
    background: #2f2f2fc4; z-index: 10; 
    width: 100%;
    }

    #container_log .botones{
    position: absolute;
    z-index: 11;
    width: 13rem;
    top: 9rem;
    transform: scale(1.2);
    left: -8.5rem;
    }

    #container_log .botones button { 
    margin-top : 0.5rem;
    padding: 0.5rem;

    }

    #container_log .text{
    width: 10rem;
      position: absolute;
      z-index: 11;
      left: 5.1rem;
      transform: scale(1.1);
      top: 9rem;;
    }

    #container_log .text span{
    letter-spacing: 0.3rem;
    }


    #container_log .form{
      width: 12rem;
        position: absolute;
        z-index: 11;
        right: 0rem;
        top: 9.5rem;
    }

    #container_log .redes{
      position: absolute;
      z-index: 11;
      left: -4.5rem;
      top: 18.5rem;
    }

    #container_log .row{
    margin-top: 5rem;
    }

    #container_log .foto_form{
      position: absolute;
        z-index: 11;
        right: 1.5rem;
        width: 9rem;
        border: #fff solid;
        top: 2rem;
        border-radius: 100%;
    }

    #container_log .mensaje{
    position: absolute;
      z-index: 11;
      right: 2rem;
      top: 10.2rem;
      font-weight: 600;
      line-height: 10px;
    }


    #container_log .btn_salir{
      position: absolute;
        right: 27rem;
        top: 5rem;
        z-index: 11;
        transform: scale(1.7);
    }


}

@media (max-width: 1380px){

  
  .logo-blanco_login {
    position: absolute;
    top: -5rem;
    left: 2.1rem;
    width: 8rem !important;
    z-index: 100;
}


  .contenedor_login{
  transform: scale(1);
}

      #container_log{
    position: absolute;
    z-index: 5;
    height: 100%;
    background: #2f2f2fc4; z-index: 10; 
    width: 100%;
    }

    #container_log .botones{
      position: absolute;
    z-index: 11;
    width: 13rem;
    top: 9rem;
    transform: scale(1.2);
    left: -8.5rem;
    }

    #container_log .botones button { 
    margin-top : 0.5rem;
    padding: 0.5rem;

    }

    #container_log .text{
      width: 10rem;
        position: absolute;
        z-index: 11;
        left: 5.1rem;
        transform: scale(1.1);
        top: 9rem;;
    }

    #container_log .text span{
      letter-spacing: 0.3rem;
    }


    #container_log .form{
      width: 12rem;
        position: absolute;
        z-index: 11;
        right: -1rem;
        top: 9.5rem;
    }

    #container_log .redes{
        position: absolute;
        z-index: 11;
        left: -4.5rem;
        top: 18.5rem;
    }

    #container_log .row{
      margin-top: 5rem;
    }

    #container_log .foto_form{
      position: absolute;
        z-index: 11;
        right: 0.5rem;
        width: 9rem;
        border: #fff solid;
        top: 2rem;
        border-radius: 100%;
    }

    #container_log .mensaje{
      position: absolute;
        z-index: 11;
        right: 2rem;
        top: 10.2rem;
        font-weight: 600;
        line-height: 10px;
    }


    #container_log .btn_salir{
      position: absolute;
        right: 25rem;
        top: 5rem;
        z-index: 11;
        transform: scale(1.7);
    }


}


@media (max-width: 1280px){
  
  .logo-blanco_login {
    position: absolute;
    top: -5rem;
    left: 2.1rem;
    width: 8rem !important;
    z-index: 100;
}

  .contenedor_login{
  transform: scale(1);
}

      #container_log{
    position: absolute;
    z-index: 5;
    height: 100%;
    background: #2f2f2fc4; z-index: 10; 
    width: 100%;
    }

    #container_log .botones{
      position: absolute;
    z-index: 11;
    width: 13rem;
    top: 9rem;
    transform: scale(1.2);
    left: -8.5rem;
    }

    #container_log .botones button { 
    margin-top : 0.5rem;
    padding: 0.5rem;

    }

    #container_log .text{
      width: 10rem;
        position: absolute;
        z-index: 11;
        left: 5.1rem;
        transform: scale(1.1);
        top: 9rem;;
    }

    #container_log .text span{
      letter-spacing: 0.3rem;
    }


    #container_log .form{
      width: 12rem;
    position: absolute;
    z-index: 11;
    right: -2.5rem;
    top: 11.5rem;
    }

    #container_log .redes{
        position: absolute;
        z-index: 11;
        left: -4.5rem;
        top: 18.5rem;
    }

    #container_log .row{
      margin-top: 5rem;
    }

    #container_log .foto_form{
      position: absolute;
    z-index: 11;
    right: -0.5rem;
    width: 9rem;
    border: #fff solid;
    top: 4rem;
    border-radius: 100%;
    }

    #container_log .mensaje{
      position: absolute;
        z-index: 11;
        right: 2rem;
        top: 10.2rem;
        font-weight: 600;
        line-height: 10px;
    }


    #container_log .btn_salir{
      position: absolute;
    right: 21rem;
    top: 0rem;
    z-index: 11;
    transform: scale(1.7);
    }


}


@media (max-width: 448px){
  
  .logo-blanco_login {
    position: absolute;
    top: -5rem;
    left: 2.1rem;
    width: 8rem !important;
    z-index: 100;
}
.contenedor_login{
  transform: scale(0.50);
}

#container_log{
position: absolute;
z-index: 5;
height: 100%;
background: #2f2f2fc4; z-index: 10; 
width: 100%;
}

#container_log .botones{
position: absolute;
z-index: 11;
width: 13rem;
top: 9rem;
transform: scale(1.2);
left: -8.5rem;
}

#container_log .botones button { 
margin-top : 0.5rem;
padding: 0.5rem;

}

#container_log .text{
width: 10rem;
  position: absolute;
  z-index: 11;
  left: 5.1rem;
  transform: scale(1.1);
  top: 9rem;;
}

#container_log .text span{
letter-spacing: 0.3rem;
}


#container_log .form{
  width: 12rem;
    position: absolute;
    z-index: 11;
    right: -8rem;
    top: 12.5rem;
}

#container_log .redes{
  position: absolute;
  z-index: 11;
  left: -4.5rem;
  top: 15.5rem;
}

#container_log .row{
margin-top: 5rem;
}

#container_log .foto_form{
  position: absolute;
    z-index: 11;
    right: -6.5rem;
    width: 9rem;
    border: #fff solid;
    top: 4rem;
    border-radius: 100%;
}

#container_log .mensaje{
position: absolute;
  z-index: 11;
  right: 2rem;
  top: 10.2rem;
  font-weight: 600;
  line-height: 10px;
}


#container_log .btn_salir{
  position: absolute;
    right: -9rem;
    top: 2rem;
    z-index: 11;
    transform: scale(1.7);
}


}
</style>


</head>
<body>
		<script src="assets/js/jquery.min.js"></script>
		<script src="assets/js/bootstrap.js"></script>
		<script src="assets/js/popper.min.js"></script>
		<script src="assets/js/owl.carousel.js"></script>
		<script src="assets/js/newks.js"></script>


    <script>(function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = 'https://connect.facebook.net/es_ES/sdk/xfbml.customerchat.js#xfbml=1&version=v2.12&autoLogAppEvents=1';
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>


<!-- Your customer chat code  URBAN -->
<div class="fb-customerchat"
  attribution=setup_tool
  theme_color="#ffc300"
  page_id="1296978620384308"
  logged_in_greeting="¡Hola! ¿Te podemos ayudar? "
  logged_out_greeting="¡Hola! ¿Te podemos ayudar? ">
</div>

<div class="whatsapp-container" id="">
<a href="https://api.whatsapp.com/send?phone=529842545636&amp;text=Hola,%20me%20gustaría%20recibir%20información." class="social-icon whatsapp" target="blank" id="">
      <img class="whatsapp-btn w-100 mr-auto ml-auto d-block" src="assets/img/W4.png" id="">
</a>
</div>

    <script>
    
    
    captar_data(1)
function charge_view_initial() {
    captar_data(1)
  clear() 
  $("#OP1").addClass('option-active');
}
    
function charge_view_services() {
    captar_data(3)
          clear() 
        $("#OP2").addClass('option-active');
        }

function charge_view_plains() {
    captar_data(2)
                  clear() 
    $("#OP4").addClass('option-active');
          }

function charge_view_contact() {
    captar_data(7)
            clear() 
          $("#OP6").addClass('option-active');
          }
    
function charge_view_galery() {
    captar_data(6)
            clear() 
          $("#OP3").addClass('option-active');
          }

function charge_view_news() {
    captar_data(4)
            clear() 
          $("#OP5").addClass('option-active');
        
          }


function clear() {
      $(".option-active").removeClass('option-active');
      scroll(0,0);
    }
    

    





function captar_data(tipo) {

if(tipo == 1){
    tipo = "visitas"
}

if(tipo == 2){
    tipo = "planes"
}

if(tipo == 3){
    tipo = "servicios"
}

if(tipo == 4){
    tipo = "blog"
}

if(tipo == 5){
    tipo = "email"
}

if(tipo == 6){
    tipo = "galeria"
}

if(tipo == 7){
    tipo = "contacto"
}

if(tipo == 8){
    tipo = "plan1"
}
if(tipo == 9){
    tipo = "plan2"
}
if(tipo == 10){
    tipo = "plan3"
}
if(tipo == 15){
    tipo = "plan7"
}
if(tipo == 11){
    tipo = "plan4"
}
if(tipo == 12){
    tipo = "plan5"
}
if(tipo == 13){
    tipo = "plan6"
}

if(tipo == 14){
    tipo = "beneficios"
}


url = "panel/actualizar_estadisticas/"+tipo
var settings = {
"async": true,
"crossDomain": true,
"url": url,
"method": "POST",
"headers": {
  "cache-control": "no-cache"
}
};

$.ajax(settings).done(function (response) {
})

}

    
    
    
    </script>


<!--  PRIMERA PARTE DE PAGINA   -->
	
  

<nav class="navbar navbar-dark" style="width: 0%; position: absolute; top: 2rem; z-index: 1;">
    <button class="navbar-toggler mr-auto ml-auto d-block" type="button" onclick="desplegar_menu()" data-toggle="collapse" data-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
  </nav>


<div class="container-fluid" id="opciones_menu_cotainer" onclick="ocultar_menu()" >
  <div class="contenedor_opciones_menu">
    <div class="row">
        <div class="col-xl-2 col-lg-2 col-md-3 col-sm-8 col   pt-2">
        <button class="btn btn-warning btn-op-planes mr-auto d-block pb-2 pb-2 text-uppercase menu_opciones" id="" onclick="location.replace('./Servicios')"><strong>Servicios</strong></button>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-2 col-lg-2 col-md-3 col-sm-8 col   pt-2">
        <button class="btn btn-warning btn-op-planes mr-auto d-block pb-2 pb-2 text-uppercase menu_opciones" id="" onclick="location.replace('./Planes')"><strong>Planes</strong></button>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-2 col-lg-2 col-md-3 col-sm-8 col   pt-2">
        <button class="btn btn-warning btn-op-planes mr-auto d-block pb-2 pb-2 text-uppercase menu_opciones" id="" onclick="location.replace('./Galeria')"><strong>Galeria</strong></button>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-2 col-lg-2 col-md-3 col-sm-8 col   pt-2">
        <button class="btn btn-warning btn-op-planes mr-auto d-block pb-2 pb-2 text-uppercase menu_opciones" id="" onclick="location.replace('./Blog')"><strong>Blog</strong></button>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-2 col-lg-2 col-md-3 col-sm-8 col   pt-2">
        <button class="btn btn-warning btn-op-planes mr-auto d-block pb-2 pb-2 text-uppercase menu_opciones" id="" onclick="location.replace('./Beneficios')"><strong>Beneficios</strong></button>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-2 col-lg-2 col-md-3 col-sm-8 col   pt-2">
        <button class="btn btn-warning btn-op-planes mr-auto d-block pb-2 pb-2 text-uppercase menu_opciones" id="" onclick="location.replace('./Contacto')"><strong>Contacto</strong></button>
        </div>
    </div>
  </div>
  
</div>



  <script>

    
  
  seccion_activa = 0

  
  if(seccion_activa == 0 ){
      // ocultar boton menu
    $(".navbar").hide();
    console.log("No mostrar menu");
    $("#opciones_menu_cotainer").hide();
    $(".menu_opciones").hide();
    }else{
      $(".navbar").show();
    }

   function ocultar_menu(){
    $("#opciones_menu_cotainer").hide();
   } 

  function desplegar_menu() {

     
  if(seccion_activa == 0 ){
      // ocultar boton menu
    $(".navbar").hide();
    console.log("No mostrar menu");
    $("#opciones_menu_cotainer").hide();
    $(".menu_opciones").hide();
    }

    if(seccion_activa == 1 ){
      // ocultar boton menu
    $(".navbar").show();
    console.log("zona 1");
    $("#opciones_menu_cotainer").fadeIn(700);
    $(".menu_opciones").fadeIn(1000);
    }

    if(seccion_activa == 2 ){
      // ocultar boton menu
    $(".navbar").hide();
    console.log("zona 2");
  
    }

    if(seccion_activa == 3 ){
      // ocultar boton menu
    $(".navbar").hide();
    console.log("zona 3");
  
    }

    if(seccion_activa == 4 ){
      // ocultar boton menu
    $(".navbar").hide();
    console.log("zona 4");
  
    }

    if(seccion_activa == 5 ){
      // ocultar boton menu
    $(".navbar").hide();
    console.log("zona 5");
  
    }

    if(seccion_activa == 6 ){
      // ocultar boton menu
    $(".navbar").hide();
    console.log("zona 6");
  
    }


  }
  
  </script>

