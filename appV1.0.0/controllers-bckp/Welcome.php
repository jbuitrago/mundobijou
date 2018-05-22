<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/controllers/my_controller.php';

class Welcome extends MY_Controller {

    var $model = '';
    var $folder_view = '';
    var $controller = '';
    var $data_view = array();
    
    var $default_view = 'welcome'; 

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        
         $this->load_view();

    }

}
