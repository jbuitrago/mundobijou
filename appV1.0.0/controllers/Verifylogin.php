<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class VerifyLogin extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('usuarios_model');

        $this->load->helper('url');
    }

    function index() {
        //This method will have the credentials validation
        $this->load->library('form_validation');

        $this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');

        $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|md5|callback_check_database');

        if ($this->form_validation->run() == FALSE) {
            //Field validation failed.  User redirected to login page
            $this->load->view('login_view');
        } else {
            //Go to private area
            $session_data = $this->session->userdata('logged_in');
            redirect("welcome", 'refresh');
        }
    }

    function logout() {
        $user_data = $this->session->all_userdata();
        foreach ($user_data as $key => $value) {
            if ($key != 'session_id' && $key != 'ip_address' && $key != 'user_agent' && $key != 'last_activity') {
                $this->session->unset_userdata($key);
            }
        }
       
        $this->usuarios_model->log(array("tabla" => "session", "accion" => "LOGUOT", "id_registro" => 0));
        
        $this->session->sess_destroy();
        redirect('login');
    }

    function check_database($password) {
        //Field validation succeeded.  Validate against database
        $username = $this->input->post('username');

        //query the database
        $result = $this->usuarios_model->login($username, $password);

        if ($result) {
            $sess_array = array();
            foreach ($result as $row) {

                $sess_array = array('id' => $row->id, 'username' => $row->usuario, 'rol'=>$row->roles_id, 'rol_nombre'=>$row->rol, 'controller_default' => $row->controller_default, 'sistema_actual' => $row->sistema_id, 'imagen' => $row->file );

                $this->session->set_userdata('logged_in', $sess_array);
            }
            $this->usuarios_model->log(array("tabla" => "session", "accion" => "LOGIN", "id_registro" => 0, "usuario"=>$sess_array["id"])); 
            return TRUE;
        } else {
            $this->form_validation->set_message('check_database', 'Invalid username or password');
            return false;
        }
    }

}

?>