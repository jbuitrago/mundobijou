<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class suscriptores_model extends Default_model {

    var $table = 'suscriptores';
    var $column = array('id','email','nombre');
    var $order = array('id' => 'desc');

    public function __construct() {
        parent::__construct();
    }
}
