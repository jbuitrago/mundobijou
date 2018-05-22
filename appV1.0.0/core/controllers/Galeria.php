<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/controllers/my_controller.php';

class Galeria extends MY_Controller {

    var $model = 'galeria_model';
    var $folder_view = 'galeria';
    var $controller = 'galeria';
    var $data_view = array();

    public function __construct() {
        parent::__construct();
        $this->files_urlpath = base_url().'uploads/' . sha1($_SESSION['logged_in']['username']);
    }

    public function index() {
        $this->load_view();
    }

    public function ajax_add() {

        $this->form_validation->set_rules('titulo', 'Titulo', 'required|xss_clean|trim');
        
        $this->form_validation->set_rules('file', 'Imagen', 'required|xss_clean|trim');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array("status" => FALSE, "errores" => validation_errors()));
        } else {
            $data = array(
                'titulo' => $this->input->post('titulo'),
                'file' => $this->files_urlpath.'/'.$this->input->post('file'),
                'descripcion' => $this->input->post('descripcion'),
                'fecha_insert' => date('Y-m-d H:i:s')
            );
            $insert = $this->obj_model->save($data);
            echo json_encode(array("status" => $insert));
        }
    }

    public function ajax_update() {

        $this->form_validation->set_rules('titulo', 'Titulo', 'required|xss_clean|trim');
        $this->form_validation->set_rules('file', 'Imagen', 'required|xss_clean|trim');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array("status" => FALSE, "errores" => validation_errors()));
        } else {

            $data = array(
                'file' => $this->files_urlpath.'/'.$this->input->post('file'),
                'descripcion' => $this->input->post('descripcion'),
                'fecha_update' => date('Y-m-d H:i:s')
            );
            $this->obj_model->update(array('id' => $this->input->post('id')), $data);
            echo json_encode(array("status" => TRUE));
        }
    }

}
