<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/controllers/my_controller.php';

class metadescripciones_config extends MY_Controller {

    var $model = 'metadescripciones_config_model';
    var $folder_view = 'metadescripciones_config';
    var $controller = 'metadescripciones_config';
    var $data_view = array();

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->load_view();
    }

    public function ajax_add() {

        $this->form_validation->set_rules("metadescripcion_inicio", "Metadesc. inicio", "required|xss_clean|trim");
        $this->form_validation->set_rules("metadescripcion_contacto", "Metadesc. contacto", "required|xss_clean|trim");
        $this->form_validation->set_rules("metadescripcion_red_vendedores", "Metadesc. red vendedores", "required|xss_clean|trim");
        $this->form_validation->set_rules("metadescripcion_combos", "Metadesc. combos", "xss_clean|trim");
        $this->form_validation->set_rules("metadescripcion_busqueda", "Metadesc. busqueda", "required|xss_clean|trim");
        $this->form_validation->set_rules("metadescripcion_registro", "Metadesc. egistro", "required|xss_clean|trim");
        $this->form_validation->set_rules("metadescripcion_terminos_condiciones", "Metadesc. terminos condiciones", "required|xss_clean|trim");
        $this->form_validation->set_rules("metadescripcion_carro", "Metadesc. carro", "required|xss_clean|trim");

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array("status" => FALSE, "errores" => validation_errors()));
        } else {
            $data = array(
                "metadescripcion_inicio" => $this->input->post("metadescripcion_inicio"),
                "metadescripcion_contacto" => $this->input->post("metadescripcion_contacto"),
                "metadescripcion_red_vendedores" => $this->input->post("metadescripcion_red_vendedores"),
                "metadescripcion_combos" => $this->input->post("metadescripcion_combos"),
                "metadescripcion_busqueda" => $this->input->post("metadescripcion_busqueda"),
                "metadescripcion_registro" => $this->input->post("metadescripcion_registro"),
                "metadescripcion_terminos_condiciones" => $this->input->post("metadescripcion_terminos_condiciones"),
                "metadescripcion_carro" => $this->input->post("metadescripcion_carro"),
                "fecha_insert" => date('Y-m-d H:i:s')
            );
            $insert = $this->obj_model->save($data);
            echo json_encode(array("status" => $insert));
        }
    }

    public function ajax_update() {

        $this->form_validation->set_rules("metadescripcion_inicio", "Metadesc. inicio", "required|xss_clean|trim");
        $this->form_validation->set_rules("metadescripcion_contacto", "Metadesc. contacto", "required|xss_clean|trim");
        $this->form_validation->set_rules("metadescripcion_red_vendedores", "Metadesc. red vendedores", "required|xss_clean|trim");
        $this->form_validation->set_rules("metadescripcion_combos", "Metadesc. combos", "xss_clean|trim");
        $this->form_validation->set_rules("metadescripcion_busqueda", "Metadesc. busqueda", "required|xss_clean|trim");
        $this->form_validation->set_rules("metadescripcion_registro", "Metadesc. egistro", "required|xss_clean|trim");
        $this->form_validation->set_rules("metadescripcion_terminos_condiciones", "Metadesc. terminos condiciones", "required|xss_clean|trim");
        $this->form_validation->set_rules("metadescripcion_carro", "Metadesc. carro", "required|xss_clean|trim");

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array("status" => FALSE, "errores" => validation_errors()));
        } else {

            $data = array(
                "metadescripcion_inicio" => $this->input->post("metadescripcion_inicio"),
                "metadescripcion_contacto" => $this->input->post("metadescripcion_contacto"),
                "metadescripcion_red_vendedores" => $this->input->post("metadescripcion_red_vendedores"),
                "metadescripcion_combos" => $this->input->post("metadescripcion_combos"),
                "metadescripcion_busqueda" => $this->input->post("metadescripcion_busqueda"),
                "metadescripcion_registro" => $this->input->post("metadescripcion_registro"),
                "metadescripcion_terminos_condiciones" => $this->input->post("metadescripcion_terminos_condiciones"),
                "metadescripcion_carro" => $this->input->post("metadescripcion_carro"),
                "fecha_update" => date('Y-m-d H:i:s')
            );
            $res = $this->obj_model->update(array('id' => $this->input->post('id')), $data);
            echo json_encode(array("status" => $res));
        }
    }

}
