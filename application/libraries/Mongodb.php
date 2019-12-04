<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mongodb {
    protected $CI;

        // We'll use a constructor, as you can't directly call a function
        // from a property definition.
        public function __construct($param=[])
        {
                // Assign the CodeIgniter super-object
                $this->CI = &get_instance();
                
                // if the values from param exist, then assign them to variables for later usage
                $this->p_host = isset($param['host']) ? $param['host'] : \NULL;
                $this->p_port = isset($param['port']) ? $param['port'] : \NULL;
                $this->p_db = isset($param['db']) ? $param['db'] : \NULL;
                $this->p_collection = isset($param['collection']) ? $param['collection'] : \NULL;
                
                //test the values from param by using the echo statement below, if needed
                //echo "$p_host,$p_port, $p_db, $p_collection <br>" ;
 
        }
        public function connection() {
            // load the config file config_mongo
                $this->CI->config->load('config_mongo', TRUE);
            //Check param values. If param values are present, ignore the config file values for connection paramenters   
                $host = is_null($this->p_host) ? $this->CI->config->item('host','mongodb','config_mongo') : $this->p_host;
                $port = is_null($this->p_port) ? $this->CI->config->item('port','mongodb','config_mongo') : $this->p_port ;
                $db = is_null($this->p_db) ? $this->CI->config->item('db','mongodb','config_mongo') : $this->p_db ;
                $coll = is_null($this->p_collection) ? $this->CI->config->item('collection','mongodb','config_mongo') : $this->p_collection ;
            // username and password for connection being loaded from config file only for security reasons
                $username = $this->CI->config->item('username','mongodb','config_mongo') ;
                $password = $this->CI->config->item('password','mongodb','config_mongo') ;
                
                //verify the final values by using the echo statemet below if needed
                //echo "$host,$port, $db, $coll,$username,$password <br>" ;
                
                // $mongo['connection'] is the connection URI as per mongodb 
                // docs: https://docs.mongodb.com/manual/reference/connection-string/
                // This statement will establish actual connection to your mongodb using all the parameters provided.
                //$mongo['connection'] = new MongoDB\Client("mongodb://$username:$password@$host:$port/$db");

                //$mongo['connection'] = new MongoDB\Driver\Manager("mongodb://$username:$password@$host:$port/$db");
                $mongo['connection'] = new MongoDB\Client("mongodb://localhost:27017");
                //If you are just testing MongoDB and do not have authentication enabled, you can remove 
                // username and password parameters. In that case, you can use below statement for connection
                // ,comment the above one and also remove username and password from config file.
                
                //$mongo['connection'] = new MongoDB\Client("mongodb://$host:$port");
                
                // Getting the collection using the param or config values. This can be overridden by directly
                // using the above connection directly from application code.
                $mongo['collection'] = $mongo['connection']->$db->$coll ; 
                //Return the CONNECTION and COLLECTION values back
                return $mongo ;
        }
}