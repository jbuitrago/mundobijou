<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/controllers/my_controller.php';

class Roles extends MY_Controller {

    var $model = 'roles_model';
    var $folder_view = 'roles';
    var $controller = 'roles';
    var $data_view = array();

    public function __construct() {
        parent::__construct();
    }

    public function index() {

        $this->load_view();
    }

    public function ajax_add() {

        $this->form_validation->set_rules('nombre', 'Nombre', 'required|xss_clean|trim');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array("status" => FALSE, "errores" => validation_errors()));
        } else {
            $data = array(
                'nombre' => $this->input->post('nombre'),
                'fecha_insert' => date('Y-m-d H:i:s')
            );
            $insert = $this->obj_model->save($data);
            echo json_encode(array("status" => TRUE));
        }
    }

    public function ajax_update() {
        $this->form_validation->set_rules('nombre', 'Nombre', 'required|xss_clean|trim');
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array("status" => FALSE, "errores" => validation_errors()));
        } else {

            $data = array(
                'nombre' => $this->input->post('nombre'),
                'fecha_update' => date('Y-m-d H:i:s')
            );
            $this->obj_model->update(array('id' => $this->input->post('id')), $data);
            echo json_encode(array("status" => TRUE));
        }
    }

}
