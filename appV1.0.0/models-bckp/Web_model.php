<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class web_model extends Default_model {

    var $table = 'contacto';
    var $column = array('id','nombre','apellido','empresa','email','telefono','mensaje');
    var $order = array('id' => 'desc');

    public function __construct() {
        parent::__construct();
    }
}
