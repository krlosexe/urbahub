<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
  |--------------------------------------------------------------------------
  | Display Debug backtrace
  |--------------------------------------------------------------------------
  |
  | If set to TRUE, a backtrace will be displayed along with php errors. If
  | error_reporting is disabled, the backtrace will not display, regardless
  | of this setting
  |
 */
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
  |--------------------------------------------------------------------------
  | File and Directory Modes
  |--------------------------------------------------------------------------
  |
  | These prefs are used when checking and setting modes when working
  | with the file system.  The defaults are fine on servers with proper
  | security, but you may wish (or even need) to change the values in
  | certain environments (Apache running a separate process for each
  | user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
  | always be used to set the mode correctly.
  |
 */
defined('FILE_READ_MODE') OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE') OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE') OR define('DIR_WRITE_MODE', 0755);

/*
  |--------------------------------------------------------------------------
  | File Stream Modes
  |--------------------------------------------------------------------------
  |
  | These modes are used when working with fopen()/popen()
  |
 */
defined('FOPEN_READ') OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE') OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE') OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE') OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE') OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE') OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT') OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT') OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
  |--------------------------------------------------------------------------
  | Exit Status Codes
  |--------------------------------------------------------------------------
  |
  | Used to indicate the conditions under which the script is exit()ing.
  | While there is no universal standard for error codes, there are some
  | broad conventions.  Three such conventions are mentioned below, for
  | those who wish to make use of them.  The CodeIgniter defaults were
  | chosen for the least overlap with these conventions, while still
  | leaving room for others to be defined in future versions and user
  | applications.
  |
  | The three main conventions used for determining exit status codes
  | are as follows:
  |
  |    Standard C/C++ Library (stdlibc):
  |       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
  |       (This link also contains other GNU-specific conventions)
  |    BSD sysexits.h:
  |       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
  |    Bash scripting:
  |       http://tldp.org/LDP/abs/html/exitcodes.html
  |
 */
defined('EXIT_SUCCESS') OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR') OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG') OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE') OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS') OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

function return_bytes($val) {
    $val = trim($val);
    $last = strtolower($val[strlen($val)-1]);
    switch($last) {
        // El modificador 'G' está disponble desde PHP 5.1.0
        case 'g':
            $val *= 1024;
        case 'm':
            $val *= 1024;
        case 'k':
            $val *= 1024;
    }

    return $val;
}
define('POST_MAX_SIZE', return_bytes(ini_get('post_max_size')));
// //constante URL
if (isset($_SERVER['HTTP_HOST'])) {
    $base_url = 'https'
            . '://' . $_SERVER['HTTP_HOST']
            . str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
    // Base URI (It's different to base URL!)
    $base_uri = parse_url($base_url, PHP_URL_PATH);
    if (substr($base_uri, 0, 1) != '/')
        $base_uri = '/' . $base_uri;
    if (substr($base_uri, -1, 1) != '/')
        $base_uri .= '/';
}
else {
    $base_url = 'localhost/urbahub';
    $base_uri = '/';
}
// Define these values to be used later on
define('BASE_DIRECCION', $base_url);
define('BASE_URI', $base_uri);
define('APPPATH_URI', BASE_URI . APPPATH);
// We dont need these variables any more
unset($base_uri, $base_url);


//Variables Globales
define('MONEDA_LOCAL', "5c8c7f4ee31dd91bdd6a518a");
define('GASTO_CUPON', "5c8c7f4ee31dd91bdd6a4f02");
define('CONCEPTO_CUPON', "5c8c7f4ee31dd91bdd6a4f02");


///Notificaciones
define('NOTIFICACIONES_CORREO',true);


/////LISTAS DE VALORES ESTATICAS
define('LISTA_VALORES', 
				serialize(array(
                                                "ROLES_PRINCIPALES" => array(
                                                                
												"5c8c7fc7e31dd91ca20619d2" => "Administrador", 
												"5c8c7fc7e31dd91ca20619d4" => "director",
												"5c8c7fc7e31dd91ca20619d5" => "Presidente"
												),
                                                "PAISES_PRINCIPALES" => array(
                                                                
												"5c8c7f4ee31dd91bdd6a5171" => "mexico"
												),

						'TIPO_AUTORIZACION' => array(
												"pago" => "Por Pago", 
												"credito" => "Por Credito",
												),
					

						'TIPO_GARANTIA' => array(
												/*"Aval" => "Aval",*/ 
												"Hipoteca" => "Hipoteca", 
												/*"Inmueble" => "Inmueble",*/ 
												/*"Pagare" => "Pagaré",*/ 
												"Prenda" => "Prenda", 
												"Prenda_Acciones" => "Prenda Sobre Acciones",
												),
					
						'TIPO_ACCIONIS_GARAN' => array(
												"accio"=>"Accionista", 
												"garan"=>"Garante", 
												"ambos"=>"Ambos",
												),
					
						'TIPO_PERTENENCIA_BENEF' => array(
												"benef"=>"Cliente/Pagador", 
												"prove"=>"Proveedor/Beneficiario", 
												"ambos"=>"Ambos",
												),
					
						'TIPO_BENEFICIARIO' => array(
												"simple"=>"Directo", 
												"credito"=>"Credito-Mutuo", 
												"inmueble"=>"Inmueble",
												),

						'ESTADO_DOC' => array(
												216 => "Borrador",
												224 => "En Proceso",
												218 => "Autorizado",
												225 => "Aprobado",
												226 => "Pagada",
												227 => "Cancelada",
											),
						'DOCUMENTOS' => array(
												'5c8c7f4ee31dd91bdd6a5185' => "contrato",
											),                                    

						'ESTADO_DOC_AUTORIZACION' => array(
												225 => "Pendiente Por Pagar",
												226 => "Pagada",
											),
						'ESTATUS_GEN' => array(
												2 => "inactivo",
												1 => "activo",
											),

						'ESTADO_DOC_AUTORIZAR' => array(
												224 => "En Proceso",
												218 => "Autorizado",
												225 => "Aprobado",
											),

						'SI_NO' => array(
												'si' => "Si",
												'no' => "No",
											),

						'SI_NO_NOAPLICA' => array(
												'si' => "Si",
												'no' => "No",
												'no_aplica' => "No Aplica",
											),

						'TIPO_CONCEPTOS' => array(
												'arrend' => "Arrendamiento",
												'cupon' => "Cupon",
												'factura' => "Factura",
												'honorari' => "Honorarios",
												'pagoext' => "Pago al Extranjero",
												'pres_per' => "Préstamo Personal",
												'reembols' => "Reembolso",
												'autobien' => "Serv. Autotrans Bienes",
											),

						'TIPO_PAGO' => array(
												'transferencia' => "Transferencia",
												'efectivo' => "Efectivo",
												'cheque' => "Cheque",
												'convenio' => "Convenio",
											),

						'TIPO_CATEGORIA' => array(
												'gastos_fijos' => "Gastos Fijos",
												'gastos_variables' => "Gastos Variables",
												'cupon' => "Cupón",
											),
						'TIPO_IMPUESTO' => array(
												'1' => "Traslado",
												'2' => "Impuesto"
											),
						'TIPO_MONEDA' => array(
												array('_id'=>"5c8c7f4ee31dd91bdd6a518b", 'ID'=>"USD", 'NOMBRE'=>"USD", 'ID_SERIAL'=>"SF43718",),
												array('_id'=>"5c8c7f4ee31dd91bdd6a518d", 'ID'=>"EUR", 'NOMBRE'=>"EUR", 'ID_SERIAL'=>"SF46410",),
											),
					))
		);
define('TOKEN_BCM', "1bfd21c5de74e10874f6f016e33a240fecc02a3f2509b78c2496b310271fb71b");
define('CAMBIOS_BAAXAL', false);

if((strpos( $_SERVER['HTTP_HOST'], '127.0.0.1' ) !== false) OR(strpos( $_SERVER['HTTP_HOST'], 'localhost' ) !== false))  {
   	define('desarrollo', true);
}
define("NOMBRE_USUARIO", "desarrollo");
define("CLAVE_USUARIO", "ag28520");
define("URL_TOKEN","https://admin.ag2.com.mx/service");
define("URL_SOLICITAR_METODO","https://admin.ag2.com.mx/service");
define("SISTEMA_EXPIRA_TOKEN", 24);
