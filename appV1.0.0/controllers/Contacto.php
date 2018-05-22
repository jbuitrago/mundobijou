<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/controllers/my_controller.php';

class contacto extends MY_Controller {

    var $model = 'contacto_model';
    var $folder_view = 'contacto';
    var $controller = 'contacto';
    var $data_view = array();

    public function __construct() {
        parent::__construct();
       
    }

    public function index() {
        $this->load_view();
        
    }

   
}
