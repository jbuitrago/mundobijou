<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class medidas_model extends Default_model {

    var $table = 'medidas';
    var $column = array('id','nombre');
    var $order = array('id' => 'desc');

    public function __construct() {
        parent::__construct();
    }
   
}
