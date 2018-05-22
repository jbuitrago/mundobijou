<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class slider_model extends Default_model {

    var $table = 'slider';
    var $column = array('id','link','file');
    var $order = array('id' => 'desc');

    public function __construct() {
        parent::__construct();
    }
}
