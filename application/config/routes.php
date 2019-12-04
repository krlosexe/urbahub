<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loBlog/listar_entradas
|
|	$route['404Blog/listar_entradasrride'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes with
| underscores in the controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'Inicio_web';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;




// RUTAS DE SITIO WEB

$route['login_web'] = "Login_web";
$route['r_saldos'] = "Login_web/pd";

$route['Servicios'] = 'home_page/servicios';
$route['Planes'] = 'home_page/planes';
$route['consumir_planes'] = 'ServicioUrbanhub/consumir_planes';
$route['Galeria'] = 'home_page/galeria';
$route['Blog'] = 'home_page/blog';

$route['Blog/listar_entradas'] = 'home_page/Blog/listar_entradas';


$route['Blog/articulo/(:any)'] = 'home_page/Blog/articulo/$1';

$route['Blog/articulos_relacionados/(:any)'] = 'home_page/Blog/articulos_relacionados/$1';


$route['Blog/guardar_imagen'] = 'home_page/Blog/guardar_imagen';

$route['Blog/crear_entrada'] = 'home_page/Blog/crear_entrada';


$route['Blog/listar_noticias'] = 'home_page/Blog/listar_noticias';


$route['Blog/listar_eventos'] = 'home_page/Blog/listar_eventos';

$route['Blog/actualizar_entrada'] = 'home_page/Blog/actualizar_entrada';

$route['Blog/actualizar_imagen'] = 'home_page/Blog/actualizar_imagen';

$route['Blog/buscar_evento'] = 'home_page/Blog/buscar_evento';

$route['Blog/buscar_noticia'] = 'home_page/Blog/buscar_noticia';








$route['Beneficios'] = 'home_page/beneficios';
$route['Contacto'] = 'home_page/contacto';



$route['admin'] = 'auth';



