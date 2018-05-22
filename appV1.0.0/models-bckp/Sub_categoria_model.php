<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class sub_categoria_model extends Default_model {

    var $table = 'sub_categoria';
    var $column = array('id','categoria_padre','nombre','foto');
    var $order = array('id' => 'desc');

    public function __construct() {
        parent::__construct();
    }
}
