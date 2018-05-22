<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/controllers/my_controller.php';

class descuento extends MY_Controller {

    var $model = 'descuento_model';
    var $folder_view = 'descuento';
    var $controller = 'descuento';
    var $data_view = array();

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->load_view();
    }

    public function ajax_add() {

         $this->form_validation->set_rules("codigo", "Codigo", "required|xss_clean|trim");
 $this->form_validation->set_rules("porcentaje", "Porcentaje", "required|xss_clean|trim|numeric");


        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array("status" => FALSE, "errores" => validation_errors()));
        } else {
            $data = array(
                "codigo" => $this->input->post("codigo"),
"porcentaje" => $this->input->post("porcentaje"),

            );
            $insert = $this->obj_model->save($data);
            echo json_encode(array("status" => $insert));
        }
    }

    public function ajax_update() {
        
         $this->form_validation->set_rules("codigo", "Codigo", "required|xss_clean|trim");
 $this->form_validation->set_rules("porcentaje", "Porcentaje", "required|xss_clean|trim");

        
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array("status" => FALSE, "errores" => validation_errors()));
        } else {

            $data = array(
                "codigo" => $this->input->post("codigo"),
"porcentaje" => $this->input->post("porcentaje"),

            );
            $res = $this->obj_model->update(array('id' => $this->input->post('id')), $data);
            echo json_encode(array("status" => $res));
        }
    }

}
