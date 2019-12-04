<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Base_service {

	public $CI;

	function __construct(){
        //parent::__construct();
        $this->CI =& get_instance();
    }
}