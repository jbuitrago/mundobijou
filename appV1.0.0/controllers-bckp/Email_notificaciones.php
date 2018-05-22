<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/controllers/my_controller.php';

class Email_notificaciones extends MY_Controller {

    var $model = 'categorias_productos_model';
    var $folder_view = 'categorias_productos';
    var $controller = 'categorias_productos';
    var $data_view = array();

    public function __construct() {
        parent::__construct();
    }

    public function index() {

        $this->load->library('email');

        $this->email->from('sistemagrupomap@ik000225.ferozo.com', 'Sistema grupomap');
        
        $this->email->to('negocifacundo@hotmail.com');

        $this->email->subject('Reporte Diario');
        
        $this->email->set_mailtype("html");
        
        $data['logs'] = $this->get_logs();
        
        $msg = $this->load->view('services/email_notificaciones', $data, TRUE);
        
        $this->email->message($msg);

        if (!$this->email->send()) {
            die("Generate error");
        }
    }
    
     
}
