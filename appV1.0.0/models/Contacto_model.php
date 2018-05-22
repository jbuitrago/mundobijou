<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class contacto_model extends Default_model {

    var $table = 'contacto';
    var $column = array('id','fecha_insert','nombre','apellido','email','codarea','telefono','mensaje');
    var $order = array('id' => 'desc');

    public function __construct() {
        parent::__construct();
    }
}
