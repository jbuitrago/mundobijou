<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class VerifyLogin_Clientes extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('Cliente_model');
    }

    function index() {
        //This method will have the credentials validation
        $this->load->library('form_validation');

        $this->form_validation->set_rules('usuario', 'Usuario', 'trim|required|xss_clean');

        $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|callback_check_database');

        if ($this->form_validation->run() == FALSE) {
            //Field validation failed.  User redirected to login page
            $this->load->view('login');
        } else {
            //Go to private area
            if(empty($_POST['come_from'])){
                
                redirect('web/mi_cuenta', 'refresh');
                
            } else {
                
                redirect('web/'.$_POST['come_from'], 'refresh');
                
            }
        }
    }

    function check_database($password) {

        //Field validation succeeded.  Validate against database
        $email = $this->input->post('usuario');

        //query the database
        $result = $this->ClientesModel->login($email, $password);

        if ($result) {
            $sess_array = array();
            foreach ($result as $row) {
                $sess_array = array('cliente_id' => $row->id, 'cliente_email' => $row->email, 'cliente_usuario' => $row->usuario);

                $this->session->set_userdata('cliente_logged_in', $sess_array);
            }
            return TRUE;
        } else {
            $this->form_validation->set_message('check_database', 'Usuario o password incorrectos');
            return false;
        }
    }

}

?>