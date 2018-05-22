<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/controllers/my_controller.php';

class config_emails extends MY_Controller {

    var $model = 'config_emails_model';
    var $folder_view = 'config_emails';
    var $controller = 'config_emails';
    var $data_view = array();

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->load_view();
    }

    public function ajax_add() {

        $this->form_validation->set_rules("smtp_host", "Smtp host", "required|xss_clean|trim");
        $this->form_validation->set_rules("smtp_email_from", "Smtp email from", "required|xss_clean|trim|valid_email");
        $this->form_validation->set_rules("smtp_email_name", "Smtp email name", "required|xss_clean|trim");
        $this->form_validation->set_rules("smtp_email_pass", "Smtp email pass", "required|xss_clean|trim");
        $this->form_validation->set_rules("email_envio_quiebre", "Email envio quiebre", "required|xss_clean|trim|valid_email");
        $this->form_validation->set_rules("email_envio_pedido", "Email envio pedido", "required|xss_clean|trim|valid_email");

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array("status" => FALSE, "errores" => validation_errors()));
        } else {
            $data = array(
                "smtp_host" => $this->input->post("smtp_host"),
                "smtp_email_from" => $this->input->post("smtp_email_from"),
                "smtp_email_name" => $this->input->post("smtp_email_name"),
                "smtp_email_pass" => $this->input->post("smtp_email_pass"),
                "email_envio_quiebre" => $this->input->post("email_envio_quiebre"),
                "email_envio_pedido" => $this->input->post("email_envio_pedido"),
            );
            $insert = $this->obj_model->save($data);
            
            echo json_encode(array("status" => $insert));
        }
    }

    public function ajax_update() {
        
        $this->form_validation->set_rules("smtp_host", "Smtp host", "required|xss_clean|trim");
        $this->form_validation->set_rules("smtp_email_from", "Smtp email from", "required|xss_clean|trim|valid_email");
        $this->form_validation->set_rules("smtp_email_name", "Smtp email name", "required|xss_clean|trim");
        $this->form_validation->set_rules("smtp_email_pass", "Smtp email pass", "required|xss_clean|trim");
        $this->form_validation->set_rules("email_envio_quiebre", "Email envio quiebre", "required|xss_clean|trim|valid_email");
        $this->form_validation->set_rules("email_envio_pedido", "Email envio pedido", "required|xss_clean|trim|valid_email");

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array("status" => FALSE, "errores" => validation_errors()));
        } else {

            $data = array(
                "smtp_host" => $this->input->post("smtp_host"),
                "smtp_email_from" => $this->input->post("smtp_email_from"),
                "smtp_email_name" => $this->input->post("smtp_email_name"),
                "smtp_email_pass" => $this->input->post("smtp_email_pass"),
                "email_envio_quiebre" => $this->input->post("email_envio_quiebre"),
                "email_envio_pedido" => $this->input->post("email_envio_pedido"),
            );
            
            $res = $this->obj_model->update(array('id' => $this->input->post('id')), $data);
            
            echo json_encode(array("status" => $res));
        }
    }

}
