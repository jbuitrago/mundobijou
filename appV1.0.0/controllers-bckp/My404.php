<?php
class my404 extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {

        $this->output->set_status_header('404');

        $data['content'] = 'error_404';
        
        $data['opcion'] = 'error_404';

        $this->load->view('web/header', $data);

        $this->load->view('web/menu', $data);

        $this->load->view('web/error_page', $data);

        $this->load->view('web/footer');
    }

}
?>