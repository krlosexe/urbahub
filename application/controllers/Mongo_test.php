<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mongo_test extends CI_Controller{
    //put your code here
    public function index() {
        /*$mongo_parms = ['host' => "localhost",
            'port' => 27017,
            'db' => "alumnos",
            'collection' => "alumnos"];
        $this->load->library('mongodb',$mongo_parms);
        // If you do not want to pass the parameters at run time and use the ones from config file, you can use below 
        // statement instead. Comment the above one and do  not define the param values.
        // $this->load->library('mongodb');
        
        //Below are some test values to insert and verify the installation
        $biz[] = ['name' => 'biz name',
            'address' => 'test address'] ;
        
        //Access the collection from the library 
        $collection = $this->mongodb->connection()['collection'];
        
        // If you want to modify any of the connection parameters, you can directly access the connection using 
        // below $connection statement.
        $connection = $this->mongodb->connection()['connection'];
        //var_dump($connection);
        
        //Insert the above values to the collection
        $insert = $collection->insertOne($biz) ;
        
        // Retrieve the values from collection
        $result = $collection->find();
        foreach ($result as $k=>$v) {
            var_dump($v) ;
        }*/
        // Manager Class
        $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");

        // Query Class
        $query = new MongoDB\Driver\Query(array('edad' => 20));

        // Output of the executeQuery will be object of MongoDB\Driver\Cursor class
        $cursor = $manager->executeQuery('testDb.testColl', $query);

        // Convert cursor to Array and print result
        print_r($cursor->toArray());
    }
}