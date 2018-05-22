<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class colores_model extends Default_model {

    var $table = 'colores';
    var $column = array('id','nombre','codigo');
    var $order = array('id' => 'desc');

    public function __construct() {
        parent::__construct();
    }
}
