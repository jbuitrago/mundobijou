<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/controllers/my_controller.php';

class Clientes extends MY_Controller {

    var $model = 'clientes_model';
    var $folder_view = 'clientes';
    var $controller = 'clientes';
    var $data_view = array();

    public function __construct() {
        parent::__construct();
    }

    public function index() {

        $this->obj_model->delete_edificios_sin_cliente();
        
        $this->data_view['vendedores'] = $this->generate_data_dropdown('usuarios', 'nombre', FALSE, 'roles_id', 2);
        
        $this->load_view();
    }

    public function ajax_add() {

        $this->form_validation->set_rules('nombre', 'Nombre', 'required|xss_clean|trim');
        $this->form_validation->set_rules('cuit', 'CUIT', 'required|trim|numeric|xss_clean');
        $this->form_validation->set_rules('direccion', 'Direccion', 'required|xss_clean|trim');
        // $this->form_validation->set_rules('email', 'Email', 'required|xss_clean|trim|valid_email');
        $this->form_validation->set_rules('telefono', 'Telefono', 'required|xss_clean|trim');
        $this->form_validation->set_rules('vendedor_id', 'Ejecutivo', 'required|xss_clean|trim|callback_check_default_combox');
        $this->form_validation->set_message('check_default_combox', 'Debe seleccionar un ejecutivo');
       
        $this->form_validation->set_rules('provincia', 'Provincia', 'xss_clean|trim');
        $this->form_validation->set_rules('localidad', 'Localidad', 'xss_clean|trim');
        $this->form_validation->set_rules('observaciones', 'Observaciones', 'xss_clean|trim');


        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array("status" => FALSE, "errores" => validation_errors()));
        } else {
            $data = array(
                'nombre' => $this->input->post('nombre'),
                'cuit' => $this->input->post('cuit'),
                'localidad' => $this->input->post('localidad'),
                'provincia' => $this->input->post('provincia'),
                'cp' => $this->input->post('cp'),
                'direccion' => $this->input->post('direccion'),
                'email' => $this->input->post('email'),
                'telefono' => $this->input->post('telefono'),
                'vendedor_id' => $this->input->post('vendedor_id'),
                'observaciones' => $this->input->post('observaciones'),
                'fecha_insert' => date('Y-m-d H:i:s')
            );
            $insert = $this->obj_model->save($data);
            
            $this->obj_model->actualizar_edificios($insert);
            
            echo json_encode(array("status" => TRUE));
        }
    }

    public function ajax_update() {

        $this->form_validation->set_rules('nombre', 'Nombre', 'required|xss_clean|trim');
        $this->form_validation->set_rules('cuit', 'CUIT', 'required|numeric|trim');
        $this->form_validation->set_rules('direccion', 'Direccion', 'required|xss_clean|trim');
        // $this->form_validation->set_rules('email', 'Email', 'required|xss_clean|trim|valid_email');
        $this->form_validation->set_rules('telefono', 'Telefono', 'required|xss_clean|trim');
        $this->form_validation->set_rules('vendedor_id', 'Ejecutivo', 'required|xss_clean|trim|callback_check_default_combox');
        $this->form_validation->set_message('check_default_combox', 'Debe seleccionar un ejecutivo');
        
        $this->form_validation->set_rules('provincia', 'Provincia', 'xss_clean|trim');
        $this->form_validation->set_rules('localidad', 'Localidad', 'xss_clean|trim');
        $this->form_validation->set_rules('observaciones', 'Observaciones', 'xss_clean|trim');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array("status" => FALSE, "errores" => validation_errors()));
        } else {

            $data = array(
                'nombre' => $this->input->post('nombre'),
                'cuit' => $this->input->post('cuit'),
                'localidad' => $this->input->post('localidad'),
                'provincia' => $this->input->post('provincia'),
                'cp' => $this->input->post('cp'),
                'direccion' => $this->input->post('direccion'),
                'email' => $this->input->post('email'),
                'telefono' => $this->input->post('telefono'),
                'vendedor_id' => $this->input->post('vendedor_id'),
                'observaciones' => $this->input->post('observaciones'),
                'fecha_update' => date('Y-m-d H:i:s')
            );
            $this->obj_model->update(array('id' => $this->input->post('id')), $data);
            echo json_encode(array("status" => TRUE));
        }
    }

}
