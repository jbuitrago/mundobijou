<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/controllers/my_controller.php';

class talles extends MY_Controller {

    var $model = 'talles_model';
    var $folder_view = 'talles';
    var $controller = 'talles';
    var $data_view = array();

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->load_view();
    }

    public function ajax_add() {
         //$this->form_validation->set_rules("nombre", "Nombre", "required|xss_clean|trim|is_unique[talles.nombre]");
         /*$original_value = $this->db->query("SELECT * FROM talles WHERE nombre = '". $this->input->post('nombre')."' AND borrado='no'")->num_rows();
        if ($original_value != 0) {
            $is_unique = '|is_unique[talles.nombre]';
        } else {
            $is_unique = '';
        }
         $this->form_validation->set_rules('nombre', 'Nombre', 'trim|required' . $is_unique);
         */
         $this->form_validation->set_rules("nombre", "Nombre", "required|xss_clean|trim|callback_validate");
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array("status" => FALSE, "errores" => validation_errors()));
        } else {
            $data = array(
                "nombre" => $this->input->post("nombre"),

            );
            $insert = $this->obj_model->save($data);
            echo json_encode(array("status" => $insert));
        }
    }

    public function ajax_update() {
       /* $original_value = $this->db->query("SELECT * FROM talles WHERE nombre = '". $this->input->post('nombre')."' AND borrado='no'")->num_rows();
        if ($original_value != 0) {
            $is_unique = '|is_unique[talles.nombre]';
        } else {
            $is_unique = '';
        }
        $this->form_validation->set_rules('nombre', 'Nombre', 'trim|required' . $is_unique);
          
        */
        $original_value = $this->db->query("SELECT nombre FROM talles WHERE id = " . $this->input->post('id')." AND borrado='no'")->row()->nombre;
        if ($this->input->post('nombre') != $original_value) {
            $is_unique = '|callback_validate';
        } else {
            $is_unique = '';
        }

        
        $this->form_validation->set_rules('nombre', 'Nombre', 'trim|required' . $is_unique);
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array("status" => FALSE, "errores" => validation_errors()));
        } else {

            $data = array(
                "nombre" => $this->input->post("nombre"),
            );
            $res = $this->obj_model->update(array('id' => $this->input->post('id')), $data);
            echo json_encode(array("status" => $res));
        }
    }
     public function validate($var)
    {
        $original_value = $this->db->query("SELECT * FROM talles WHERE nombre = '". $this->input->post('nombre')."' AND borrado='no'")->num_rows();
        if ($original_value != 0) {
            // existe
            RETURN FALSE;
        } else {
            return TRUE;
        }
    }

}
