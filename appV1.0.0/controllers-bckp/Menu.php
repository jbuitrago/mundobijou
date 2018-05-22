<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/controllers/my_controller.php';

class Menu extends MY_Controller {

    var $model = 'menu_model';
    var $folder_view = 'menu';
    var $controller = 'menu';
    var $data_view = array();
    var $otra_accion = '<a class="btn btn-sm btn-default" href="javascript:void()" title="Agregar permisos" onclick="cargar_permisos(#IDENTIFICADOR)"><i class="glyphicon glyphicon-cog"></i></a>';

    public function __construct() {
        parent::__construct();
    }

    public function index() {

        $this->data_view['roles'] = $this->generate_data_dropdown('roles', 'nombre', FALSE);

        //$this->data_view['acciones'] = $this->generate_data_dropdown('acciones', 'nombre', TRUE);

        $this->data_view['menus'] = $this->generate_data_dropdown('menu', 'nombre', FALSE);

        $this->data_view['acciones'] = $this->generate_data_dropdown('acciones', 'descripcion', TRUE);

        $this->load_view();
    }

    public function ajax_edit($id) {

        $data = $this->obj_model->get_by_id($id);

        $data->grupos = $this->get_data_multiple('menu_usuario', 'idmenu', $id, 'rol_usuario');

        echo json_encode($data);
    }

    public function ajax_add() {

        $this->form_validation->set_rules('nombre', 'Nombre', 'required|xss_clean|trim');
        $this->form_validation->set_rules('add', 'Add', 'required|xss_clean|trim');
        $this->form_validation->set_rules('list', 'List', 'xss_clean|trim');
        $this->form_validation->set_rules('orden', 'Orden', 'required|xss_clean|trim');
        $this->form_validation->set_rules('icono', 'Icono', 'required|xss_clean|trim');
        $this->form_validation->set_rules('controller', 'Controller', 'required|xss_clean|trim');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array("status" => FALSE, "errores" => validation_errors()));
        } else {

            $data = array(
                'nombre' => $this->input->post('nombre'),
                'add' => $this->input->post('add'),
                'list' => $this->input->post('list'),
                'orden' => $this->input->post('orden'),
                'icono' => $this->input->post('icono'),
                'controller' => $this->input->post('controller'),
                'padre_id' => $this->input->post('padre_id'),
                    //'fecha_insert' => date('Y-m-d H:i:s')
            );
            $insert = $this->obj_model->save($data);

            echo json_encode(array("status" => $insert));
        }
    }

    public function ajax_update() {

        $this->form_validation->set_rules('nombre', 'Nombre', 'required|xss_clean|trim');
        $this->form_validation->set_rules('add', 'Add', 'required|xss_clean|trim');
        $this->form_validation->set_rules('list', 'List', 'xss_clean|trim');
        $this->form_validation->set_rules('orden', 'Orden', 'required|xss_clean|trim');
        $this->form_validation->set_rules('icono', 'Icono', 'required|xss_clean|trim');
        $this->form_validation->set_rules('controller', 'Controller', 'required|xss_clean|trim');


        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array("status" => FALSE, "errores" => validation_errors()));
        } else {

            $data = array(
                'nombre' => $this->input->post('nombre'),
                'add' => $this->input->post('add'),
                'list' => $this->input->post('list'),
                'orden' => $this->input->post('orden'),
                'icono' => $this->input->post('icono'),
                'controller' => $this->input->post('controller'),
                'padre_id' => $this->input->post('padre_id'),
                    //'fecha_insert' => date('Y-m-d H:i:s')
            );
            $insert = $this->obj_model->update(array('id' => $this->input->post('id')), $data);

            echo json_encode(array("status" => $insert));
        }
    }

    public function ajax_add_permisos() {

        $this->form_validation->set_rules('rol_id', 'Rol', 'required|xss_clean|trim');
        
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array("status" => FALSE, "errores" => validation_errors()));
        } else {

            $this->obj_model->delete_intermedia("menu_rol_accion", "menu_id", "rol_id", $this->input->post('menu_id'), $this->input->post('rol_id'));

            $acciones = $this->input->post('acciones_id');

            $rol_id = $this->input->post('rol_id');

            $data_acciones = array();

            if (count($acciones) > 0) {
                foreach ($acciones as $accion) {
                    if ($accion != 0)
                        $data_acciones[] = array("menu_id" => $this->input->post('menu_id'), "accion_id" => $accion, "rol_id" => $rol_id);
                }

                if (count($data_acciones) > 0)
                    $this->obj_model->insert_multiple("menu_rol_accion", $data_acciones);
            }

            echo json_encode(array("status" => TRUE));
        }
    }

}
