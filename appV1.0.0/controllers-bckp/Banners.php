<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/controllers/my_controller.php';

class Banners extends MY_Controller {

    var $model = 'banners_model';
    var $folder_view = 'banners';
    var $controller = 'banners';
    var $data_view = array();
    var $files_urlpath;

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->load_view();
    }

    public function ajax_add() {

        $this->form_validation->set_rules('titulo', 'Titulo', 'required|xss_clean|trim');
        $this->form_validation->set_rules('descripcion', 'Descripcion', 'required|xss_clean|trim');
        $this->form_validation->set_rules('link', 'Link', 'required|xss_clean|trim');

        $this->form_validation->set_rules('file', 'Foto', 'required|xss_clean|trim');

        $this->form_validation->set_rules('orden', 'Orden', 'required|xss_clean|trim');

        $this->form_validation->set_rules('link_accion', 'link accion', 'xss_clean|trim');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array("status" => FALSE, "errores" => validation_errors()));
        } else {
            $data = array(
                'link' => $this->input->post('link'),
                'file' => $this->input->post('file'),
                'titulo' => $this->input->post('titulo'),
                'descripcion' => $this->input->post('descripcion'),
                'orden' => $this->input->post('orden'),
                'link_accion' => $this->input->post('link_accion'),
                'fecha_insert' => date('Y-m-d H:i:s')
            );

            $insert = $this->obj_model->save($data);

            echo json_encode(array("status" => $insert));
        }
    }

    public function ajax_update() {
        
        $this->form_validation->set_rules('titulo', 'Titulo', 'required|xss_clean|trim');
        
        $this->form_validation->set_rules('descripcion', 'Descripcion', 'required|xss_clean|trim');
        
        $this->form_validation->set_rules('link', 'Link', 'required|xss_clean|trim');

        $this->form_validation->set_rules('orden', 'Orden', 'required|xss_clean|trim');

        $this->form_validation->set_rules('link_accion', 'link accion', 'required|xss_clean|trim');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array("status" => FALSE, "errores" => validation_errors()));
        } else {

            $data = array(
                'titulo' => $this->input->post('titulo'),
                'descripcion' => $this->input->post('descripcion'),
                'link' => $this->input->post('link'),
                
                'orden' => $this->input->post('orden'),
                'link_accion' => $this->input->post('link_accion'),
                'fecha_update' => date('Y-m-d H:i:s')
            );

            if ($this->input->post('file') != '') {
                $data['file'] = $this->input->post('file');
            }
            $this->obj_model->update(array('id' => $this->input->post('id')), $data);

            echo json_encode(array("status" => TRUE));
        }
    }

}
