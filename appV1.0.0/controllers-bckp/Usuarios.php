<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/controllers/my_controller.php';

class Usuarios extends MY_Controller {

    var $model = 'usuarios_model';
    var $folder_view = 'usuarios';
    var $controller = 'usuarios';
    var $data_view = array();
    var $files_urlpath;

    public function __construct() {
        parent::__construct();
    }

    public function index() {

        $this->data_view['roles'] = $this->generate_data_dropdown('roles', 'nombre', FALSE);

        $this->data_view['sistemas'] = $this->generate_data_dropdown('sistemas', 'nombre', FALSE);

        $this->load_view();
    }

    public function ajax_add() {

        $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim');
        $this->form_validation->set_rules('apellido', 'Apellido', 'required|trim');
        $this->form_validation->set_rules('usuario', 'Usuario', 'trim|required|is_unique[usuarios.usuario]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|md5');
        $this->form_validation->set_rules('roles_id', 'Rol', 'required|trim|greater_than[0]', array('greater_than' => 'Debe seleccionar un rol'));
        $this->form_validation->set_rules('sistema_id', 'Sistema', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array("status" => FALSE, "errores" => validation_errors()));
        } else {
            $data = array(
                'nombre' => $this->input->post('nombre'),
                'apellido' => $this->input->post('apellido'),
                'usuario' => $this->input->post('usuario'),
                'password' => $this->input->post('password'),
                'roles_id' => $this->input->post('roles_id'),
                'email' => $this->input->post('email'),
                'file' => $this->files_urlpath . '/' . $this->input->post('file'),
                'sistema_id' => $this->input->post('sistema_id'),
                'fecha_insert' => date('Y-m-d H:i:s')
            );
            $insert = $this->obj_model->save($data);

            if ($insert) {
                mkdir("./uploads/" . sha1($data['usuario']), 0777);
            }

            echo json_encode(array("status" => $insert));
        }
    }

    public function ajax_update() {

        $original_value = $this->db->query("SELECT usuario FROM usuarios WHERE id = " . $this->input->post('id')." AND borrado='no'")->row()->usuario;
        if ($this->input->post('usuario') != $original_value) {
            $is_unique = '|is_unique[usuarios.usuario]';
        } else {
            $is_unique = '';
        }

        $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim');
        $this->form_validation->set_rules('apellido', 'Apellido', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('sistema_id', 'Sistema', 'required|trim');


        if ($this->input->post('password') != '')
            $this->form_validation->set_rules('password', 'Email', 'trim|required|md5');

        $this->form_validation->set_rules('roles_id', 'Rol', 'required|xss_clean|trim');
        $this->form_validation->set_rules('usuario', 'Usuario', 'trim|required' . $is_unique);

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array("status" => FALSE, "errores" => validation_errors()));
        } else {

            $data = array(
                'nombre' => $this->input->post('nombre'),
                'apellido' => $this->input->post('apellido'),
                'usuario' => $this->input->post('usuario'),
                'roles_id' => $this->input->post('roles_id'),
                'email' => $this->input->post('email'),
                'sistema_id' => $this->input->post('sistema_id'),
                'fecha_update' => date('Y-m-d H:i:s')
            );

            if ($this->input->post('password') != '')
                $data['password'] = $this->input->post('password');

            if ($this->input->post('file') != '')
                $data['file'] = $this->files_urlpath . '/' . $this->input->post('file');

            $update = $this->obj_model->update(array('id' => $this->input->post('id')), $data);

            @mkdir("./uploads/" . sha1($data['usuario']), 0777);

            echo json_encode(array("status" => TRUE));
        }
    }

}
