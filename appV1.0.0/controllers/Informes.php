<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/controllers/my_controller.php';

class Informes extends MY_Controller {

    var $model = 'informes_model';
    var $folder_view = 'informes';
    var $controller = 'informes';
    var $data_view = array();

    public function __construct() {
        parent::__construct();
    }

    public function index() {

        $this->load_view();

    }

    public function ventas() {

        $this->default_view = 'ventas';

        $this->data_view['ventas'] = $this->obj_model->get_ventas();
    
        $this->load_view();
    }

    public function ventas_por_articulo() {

        $this->default_view = 'ventas_por_articulo';

        $this->data_view['ventas'] = $this->obj_model->get_ventas_por_articulo();
        
        $this->load_view();
    }

    public function ventas_por_categoria() {

        $this->default_view = 'ventas_por_categoria';

        $this->data_view['ventas'] = $this->obj_model->get_ventas_por_categoria();
        
        $this->load_view();
    }


}
