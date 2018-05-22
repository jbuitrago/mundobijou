<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/controllers/my_controller.php';

class Modificacionprecios extends MY_Controller {

    var $model = 'modificacionprecios_model';
    var $folder_view = 'modificacionprecios';
    var $controller = 'modificacionprecios';
    var $data_view = array();

    public function __construct() {
        parent::__construct();
    }

    public function index() {

        $this->data_view['categorias'] = $this->generate_data_dropdown('categorias', 'nombre', FALSE);

        $this->data_view['productos'] = $this->generate_data_dropdown('producto', 'titulo', FALSE);

        $this->data_view['combos'] = $this->generate_data_dropdown('combo', 'titulo', FALSE);

        unset($this->data_view['combos'][0]);

        unset($this->data_view['productos'][0]);

        //unset($this->data_view['categorias'][0]);

        $this->load_view();
    }

    public function ajax_add() {

        $this->form_validation->set_rules("valor", "Valor", "required|xss_clean|trim|numeric");

        switch ($this->input->post("modificacion")) {

            case 'categorias':

                $this->form_validation->set_rules("categoria_id", "Categoria", "required|xss_clean|trim");

                break;
            case 'productos':

                $this->form_validation->set_rules("productos[]", "Productos", "required|xss_clean|trim");

                break;
            case 'combos':

                $this->form_validation->set_rules("combos[]", "Combos", "required|xss_clean|trim");

                break;
        }

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array("status" => FALSE, "errores" => validation_errors()));
        } else {

            $data = array('valor' => $this->input->post("valor"), 'operacion' => $this->input->post("modificacion"), 'fecha_insert' => date('Y-m-d H:i:s'));

            switch ($this->input->post("modificacion")) {
                case 'todos':

                    $res = $this->obj_model->actualizar_precios_productos($this->input->post("valor"));

                    $res = $this->obj_model->actualizar_precios_combos($this->input->post("valor"));

                    break;
                case 'categorias':

                    $data['data'] = $this->input->post("categoria_id") .'|'. implode(';', $this->input->post("sub_categoria_id[]"));

                    $res = $this->obj_model->actualizar_precios_productos_por_categorias($this->input->post("valor"), $this->input->post("categoria_id"), $this->input->post("sub_categoria_id[]"));

                    $res = $this->obj_model->actualizar_precios_combos_por_categorias($this->input->post("valor"), $this->input->post("categoria_id"), $this->input->post("sub_categoria_id[]"));

                    break;
                case 'productos':

                    $data['data'] = implode(';', $this->input->post("productos[]"));

                    $res = $this->obj_model->actualizar_precios_productos($this->input->post("valor"), $this->input->post("productos[]"));

                    break;
                case 'combos':

                    $data['data'] = implode(';', $this->input->post("combos[]"));

                    $res = $this->obj_model->actualizar_precios_combos($this->input->post("valor"), $this->input->post("combos[]"));

                    break;
            }

            $insert = $this->obj_model->save($data);

            echo json_encode(array("status" => $res));
        }
    }

}
