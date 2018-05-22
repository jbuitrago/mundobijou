<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sistemas_model extends Default_model {

    var $table = 'sistemas';
    var $column = array('id','nombre');
    var $order = array('id' => 'desc');

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

  
}
