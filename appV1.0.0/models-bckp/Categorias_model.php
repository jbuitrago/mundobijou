<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class categorias_model extends Default_model {

    var $table = 'categorias';
    var $column = array('id','nombre','destacada');
    var $order = array('id' => 'desc');

    public function __construct() {
        parent::__construct();
    }
}
