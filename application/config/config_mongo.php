<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
$config['mongodb']['host'] = 'localhost'; // your host url or ip address
$config['mongodb']['port'] = 27017; // MongoDB port. You can leave it blank for default port
$config['mongodb']['db'] = 'alumnos'; // The Database you want to connect to
//username for the DB authentication. Make sure DB user with sufficient authorization has been created. 
//Read https://docs.mongodb.com/manual/reference/command/createUser/#dbcmd.createUser
$config['mongodb']['username'] = ''; 
$config['mongodb']['password'] = ''; //password for the above user
$config['mongodb']['collection'] = 'alumnos'; // collection you want to connect to. 
*/


// INTEGRACION


$config['mongo_db']['active'] = 'default';

$config['mongo_db']['default']['no_auth'] = true;
$config['mongo_db']['default']['hostname'] = 'localhost';
$config['mongo_db']['default']['port'] = '27017';
$config['mongo_db']['default']['username'] = '';
$config['mongo_db']['default']['password'] = '';
$config['mongo_db']['default']['database'] = 'Urbanhubweb';
$config['mongo_db']['default']['db_debug'] = TRUE;
$config['mongo_db']['default']['return_as'] = 'array';
$config['mongo_db']['default']['write_concerns'] = (int)1;
$config['mongo_db']['default']['journal'] = TRUE;
$config['mongo_db']['default']['read_preference'] = 'primary'; 
$config['mongo_db']['default']['read_concern'] = 'local'; //'local', 'majority' or 'linearizable'
$config['mongo_db']['default']['legacy_support'] = TRUE;
