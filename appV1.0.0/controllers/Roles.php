<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/controllers/my_controller.php';

class Roles extends MY_Controller {

    var $model = 'roles_model';
    var $folder_view = 'roles';
    var $controller = 'roles';
    var $data_view = array();
    var $otra_accion = '<a class="btn btn-sm btn-default" href="javascript:void()" title="Ver permisos" onclick="ver_permisos(#IDENTIFICADOR)"><i class="fa fa-eye"></i></a>';

    public function __construct() {
        parent::__construct();
    }

    public function index() {

        $this->load_view();
    }

    public function ajax_add() {

        $this->form_validation->set_rules('nombre', 'Nombre', 'required|xss_clean|trim');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array("status" => FALSE, "errores" => validation_errors()));
        } else {
            $data = array(
                'nombre' => $this->input->post('nombre'),
                'fecha_insert' => date('Y-m-d H:i:s')
            );
            $insert = $this->obj_model->save($data);
            echo json_encode(array("status" => TRUE));
        }
    }

    public function ajax_update() {
        $this->form_validation->set_rules('nombre', 'Nombre', 'required|xss_clean|trim');
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array("status" => FALSE, "errores" => validation_errors()));
        } else {

            $data = array(
                'nombre' => $this->input->post('nombre'),
                'fecha_update' => date('Y-m-d H:i:s')
            );
            $this->obj_model->update(array('id' => $this->input->post('id')), $data);
            echo json_encode(array("status" => TRUE));
        }
    }

    public function get_permisos($id) {

        $acciones_por_rol = $this->obj_model->get_acciones_by_rol($id);

        $table = '
            
        <table class="table table-hover" style="font-size:12px;">
                            <thead>
                                <tr>
                                    <th>Menu/Permiso</th>
                                    <th>Accion</th>
                                   
                                </tr>
                            </thead>
                            <tbody>';
        $menu_actual = '';

        $menu_anterior = '';

        $indice = 0;

        $string_acciones = '';

        foreach ($acciones_por_rol as $accion_rol) {

            $menu_actual = $accion_rol->menu;

            if ($menu_actual != $menu_anterior) {
                if($indice != 0){
                $table .= '<tr>
                        <td>' . $menu_anterior . '</td>
                        <td>' . $string_acciones . '</td>
                    </tr>';
                
                }
                $menu_anterior = $menu_actual;
                $indice = $indice+1;
                $string_acciones = '';
            }
            
            $string_acciones = $string_acciones.$accion_rol->accion.',';
        }

        $table .= '</tbody></table>';

        echo $table;
    }

}
