<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/controllers/my_controller.php';

class banners_internos extends MY_Controller {

    var $model = 'banners_internos_model';
    var $folder_view = 'banners_internos';
    var $controller = 'banners_internos';
    var $data_view = array();

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->load_view();
    }

    public function ajax_add() {

         $this->form_validation->set_rules("file", "File", "required|xss_clean|trim");
         $this->form_validation->set_rules("link", "Link", "required|xss_clean|trim");
         $this->form_validation->set_rules("target", "Target", "required|xss_clean|trim");
         $this->form_validation->set_rules("ubicacion", "Ubicacion", "required|xss_clean|trim");

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array("status" => FALSE, "errores" => validation_errors()));
        } else {
            $data = array(
                "file" => $this->input->post("file"),
                "link" => $this->input->post("link"),
                "target" => $this->input->post("target"),
                "ubicacion" => $this->input->post("ubicacion"),
            );

            $insert = $this->obj_model->save($data);

            echo json_encode(array("status" => $insert));
        }
    }

    public function ajax_update() {

         $this->form_validation->set_rules("link", "Link", "required|xss_clean|trim");
         $this->form_validation->set_rules("target", "Target", "required|xss_clean|trim");
         $this->form_validation->set_rules("ubicacion", "Ubicacion", "required|xss_clean|trim");

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array("status" => FALSE, "errores" => validation_errors()));
        } else {

            $data = array(
                "link" => $this->input->post("link"),
                "target" => $this->input->post("target"),
                "ubicacion" => $this->input->post("ubicacion"),
            );

            if ($this->input->post('file') != '') {
                $data['file'] = $this->input->post('file');
            }

            $res = $this->obj_model->update(array('id' => $this->input->post('id')), $data);

            echo json_encode(array("status" => $res));
        }
    }

}
