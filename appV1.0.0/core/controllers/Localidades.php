<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/controllers/my_controller.php';

class localidades extends MY_Controller {

    var $model = 'localidades_model';
    var $folder_view = 'localidades';
    var $controller = 'localidades';
    var $data_view = array();

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->load_view();
    }

    public function ajax_add() {

        $this->form_validation->set_rules("nombre", "Nombre", "required|xss_clean|trim");
        $this->form_validation->set_rules("codigopostal", "Codigopostal", "required|xss_clean|trim");
        $this->form_validation->set_rules("latitud", "Latitud", "xss_clean|trim");
        $this->form_validation->set_rules("longitud", "Longitud", "xss_clean|trim");


        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array("status" => FALSE, "errores" => validation_errors()));
        } else {
            $data = array(
                "nombre" => $this->input->post("nombre"),
                "codigopostal" => $this->input->post("codigopostal"),
                "latitud" => $this->input->post("latitud"),
                "longitud" => $this->input->post("longitud"),
            );
            $insert = $this->obj_model->save($data);
            echo json_encode(array("status" => $insert));
        }
    }

    public function ajax_update() {

        $this->form_validation->set_rules("nombre", "Nombre", "xss_clean|trim");
        $this->form_validation->set_rules("codigopostal", "Codigopostal", "xss_clean|trim");
        $this->form_validation->set_rules("latitud", "Latitud", "xss_clean|trim");
        $this->form_validation->set_rules("longitud", "Longitud", "xss_clean|trim");


        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array("status" => FALSE, "errores" => validation_errors()));
        } else {

            $data = array(
                "nombre" => $this->input->post("nombre"),
                "codigopostal" => $this->input->post("codigopostal"),
                "latitud" => $this->input->post("latitud"),
                "longitud" => $this->input->post("longitud"),
            );
            $res = $this->obj_model->update(array('id' => $this->input->post('id')), $data);
            echo json_encode(array("status" => $res));
        }
    }

}
