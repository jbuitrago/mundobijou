<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/controllers/my_controller.php';

class contactos extends MY_Controller {

    var $model = 'contacto_model';
    var $folder_view = 'contacto';
    var $controller = 'contactos';
    var $data_view = array();

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->load_view();
    }

    public function ajax_update() {

        $this->form_validation->set_rules("nombre", "Nombre", "required|xss_clean|trim");
        $this->form_validation->set_rules("apellido", "Apellido", "required|xss_clean|trim");
        $this->form_validation->set_rules("email", "email", "required|xss_clean|trim");
        $this->form_validation->set_rules("telefono", "Telefono", "required|xss_clean|trim");
        $this->form_validation->set_rules("mensaje", "Mensaje", "required|xss_clean|trim");
        $this->form_validation->set_rules("codarea", "Cod. area", "required|xss_clean|trim");
        
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array("status" => FALSE, "errores" => validation_errors()));
        } else {

            $data = array(
                "nombre" => $this->input->post("nombre"),
                "apellido" => $this->input->post("apellido"),
                "email" => $this->input->post("email"),
                "telefono" => $this->input->post("telefono"),
                "mensaje" => $this->input->post("mensaje"),
                "codarea" => $this->input->post("codarea"),
            );
            $res = $this->obj_model->update(array('id' => $this->input->post('id')), $data);
            
            echo json_encode(array("status" => $res));
        }
    }

}
