<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/controllers/my_controller.php';

class cliente extends MY_Controller {

    var $model = 'cliente_model';
    var $folder_view = 'cliente';
    var $controller = 'cliente';
    var $data_view = array();
    var $otra_accion = '<a class="btn btn-sm btn-default" href="javascript:void()" title="Ver pedidos" onclick="ver_pedidos(#IDENTIFICADOR)"><i class="fa fa-shopping-cart"></i></a>';

    public function __construct() {
        parent::__construct();
        $this->load->model('Pedido_model', 'pedido');
    }

    public function index() {

        $this->data_view['condicion_iva'] = $this->generate_data_dropdown('condicion_iva', 'nombre', FALSE);

        $this->data_view['tipo_doc'] = $this->generate_data_dropdown('tipo_documentos', 'nombre', FALSE);

        $this->data_view['tipo_cliente'] = $this->generate_data_dropdown('tipo_cliente', 'nombre', FALSE);

        $this->data_view['provincias'] = $this->generate_data_dropdown('provincia', 'nombre', FALSE);

        $this->load_view();
    }

    public function ajax_add() {

        $this->form_validation->set_rules("nombre_apellido", "Nombre_apellido", "required|xss_clean|trim");
        $this->form_validation->set_rules("tipo_doc", "Tipo_doc", "xss_clean|trim");
        $this->form_validation->set_rules("numero_doc", "Numero_doc", "xss_clean|trim");
        $this->form_validation->set_rules("usuario", "Usuario", "required|xss_clean|trim|is_unique[cliente.usuario]");
        $this->form_validation->set_rules("password", "Password", "required|xss_clean|trim");
        $this->form_validation->set_rules("email", "Email", "required|xss_clean|trim");
        $this->form_validation->set_rules("codigo_de_area", "Codigo_de_area", "xss_clean|trim");
        $this->form_validation->set_rules("telefono", "Telefono", "xss_clean|trim");
        $this->form_validation->set_rules("calle", "Calle", "xss_clean|trim");
        $this->form_validation->set_rules("altura", "Altura", "xss_clean|trim");
        $this->form_validation->set_rules("piso", "Piso", "xss_clean|trim");
        $this->form_validation->set_rules("dpto", "Dpto", "xss_clean|trim");
        $this->form_validation->set_rules("barrio", "Barrio", "xss_clean|trim");
        $this->form_validation->set_rules("manzana", "Manzana", "xss_clean|trim");
        $this->form_validation->set_rules("lote", "Lote", "xss_clean|trim");
        $this->form_validation->set_rules("codigo_postal", "Codigo_postal", "xss_clean|trim");
        $this->form_validation->set_rules("provincia", "Provincia", "xss_clean|trim");
        $this->form_validation->set_rules("localidad", "Localidad", "xss_clean|trim");
        $this->form_validation->set_rules("tipo_iva", "Tipo_iva", "xss_clean|trim");
        $this->form_validation->set_rules("tipo_cliente", "Tipo_cliente", "required|xss_clean|trim");

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array("status" => FALSE, "errores" => validation_errors()));
        } else {
            $data = array(
                "nombre_apellido" => $this->input->post("nombre_apellido"),
                "tipo_doc" => $this->input->post("tipo_doc"),
                "numero_doc" => $this->input->post("numero_doc"),
                "usuario" => $this->input->post("usuario"),
                "password" => sha1($this->input->post("password")),
                "email" => $this->input->post("email"),
                "codigo_de_area" => $this->input->post("codigo_de_area"),
                "telefono" => $this->input->post("telefono"),
                "calle" => $this->input->post("calle"),
                "altura" => $this->input->post("altura"),
                "piso" => $this->input->post("piso"),
                "dpto" => $this->input->post("dpto"),
                "barrio" => $this->input->post("barrio"),
                "manzana" => $this->input->post("manzana"),
                "lote" => $this->input->post("lote"),
                "codigo_postal" => $this->input->post("codigo_postal"),
                "provincia" => $this->input->post("provincia"),
                "localidad" => $this->input->post("localidad"),
                "tipo_iva" => $this->input->post("tipo_iva"),
                "tipo_cliente" => $this->input->post("tipo_cliente"),
                 "fecha_insert" => date('Y-m-d H:i:s')
            );

            $insert = $this->obj_model->save($data);

            echo json_encode(array("status" => $insert));
        }
    }

    public function ajax_update() {

        $original_value = $this->db->query("SELECT usuario FROM cliente WHERE id = " . $this->input->post('id') . " AND borrado='no'")->row()->usuario;

        if ($this->input->post('usuario') != $original_value) {

            $is_unique = '|is_unique[cliente.usuario]';
        } else {

            $is_unique = '';
        }

        $this->form_validation->set_rules("nombre_apellido", "Nombre y apellido", "required|xss_clean|trim");

        $this->form_validation->set_rules("tipo_doc", "Tipo doc", "xss_clean|trim");

        $this->form_validation->set_rules("numero_doc", "N° doc", "xss_clean|trim");

        $this->form_validation->set_rules("usuario", "Usuario", "required|xss_clean|trim" . $is_unique);

        $this->form_validation->set_rules("password", "Password", "xss_clean|trim");

        $this->form_validation->set_rules("email", "Email", "required|xss_clean|trim");

        $this->form_validation->set_rules("codigo_de_area", "Cod. area", "xss_clean|trim");

        $this->form_validation->set_rules("telefono", "Telefono", "xss_clean|trim");

        $this->form_validation->set_rules("calle", "Calle", "xss_clean|trim");

        $this->form_validation->set_rules("altura", "Altura", "xss_clean|trim");

        $this->form_validation->set_rules("piso", "Piso", "xss_clean|trim");

        $this->form_validation->set_rules("dpto", "Dpto", "xss_clean|trim");

        $this->form_validation->set_rules("barrio", "Barrio", "xss_clean|trim");

        $this->form_validation->set_rules("manzana", "Manzana", "xss_clean|trim");

        $this->form_validation->set_rules("lote", "Lote", "xss_clean|trim");

        $this->form_validation->set_rules("codigo_postal", "CP", "xss_clean|trim");

        $this->form_validation->set_rules("provincia", "Provincia", "xss_clean|trim");

        $this->form_validation->set_rules("localidad", "Localidad", "xss_clean|trim");

        $this->form_validation->set_rules("tipo_iva", "Tipo iva", "xss_clean|trim");

        $this->form_validation->set_rules("tipo_cliente", "Tipo cliente", "required|xss_clean|trim");

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array("status" => FALSE, "errores" => validation_errors()));
        } else {

            $data = array(
                "nombre_apellido" => $this->input->post("nombre_apellido"),
                "tipo_doc" => $this->input->post("tipo_doc"),
                "numero_doc" => $this->input->post("numero_doc"),
                "usuario" => $this->input->post("usuario"),
                "email" => $this->input->post("email"),
                "codigo_de_area" => $this->input->post("codigo_de_area"),
                "telefono" => $this->input->post("telefono"),
                "calle" => $this->input->post("calle"),
                "altura" => $this->input->post("altura"),
                "piso" => $this->input->post("piso"),
                "dpto" => $this->input->post("dpto"),
                "barrio" => $this->input->post("barrio"),
                "manzana" => $this->input->post("manzana"),
                "lote" => $this->input->post("lote"),
                "codigo_postal" => $this->input->post("codigo_postal"),
                "provincia" => $this->input->post("provincia"),
                "localidad" => $this->input->post("localidad"),
                "tipo_iva" => $this->input->post("tipo_iva"),
                "tipo_cliente" => $this->input->post("tipo_cliente"),
                 "fecha_update" => date('Y-m-d H:i:s')
            );

            if (!empty(($this->input->post('password')))) {
                $data["password"] = sha1($this->input->post("password"));
            }

            $res = $this->obj_model->update(array('id' => $this->input->post('id')), $data);

            echo json_encode(array("status" => $res));
        }
    }

    public function get_localidades($id) {

        echo json_encode($this->obj_model->get_localidades($id));
        //echo $this->db->last_query();
    }

    public function get_pedidos_cliente($id) {

        $pedidos = $this->pedido->get_pedidos_by_cliente($id);
        
        $estados_pedido = $this->generate_data_dropdown('estado_pedido', 'nombre', FALSE);
        
        $table = '
            
        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#Pedido</th>
                                    <th>Fecha</th>
                                    <th>Total</th>
                                    <th colspan="2">Estado</th>
                                </tr>
                            </thead>
                            <tbody>';

        foreach ($pedidos as $pedido) {


            $table .= '<tr>
                <th>#'.str_pad($pedido->id, 4, 0, STR_PAD_LEFT).'</th>
                <td>'.date("d/m/Y", strtotime($pedido->fecha_insert)).'</td>
                <td>$'.$pedido->total.'</td>
                <td>'.$estados_pedido[$pedido->estado].'</td>
                <td><a href="' . site_url('pedidos/add/' . $pedido->id) . '" class="btn btn-primary btn-sm">ver</a></td>
            </tr>';
        }

        $table .= '</tbody></table>';
        
        echo $table;
    }

}
