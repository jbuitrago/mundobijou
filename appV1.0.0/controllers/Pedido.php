<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/controllers/my_controller.php';

class pedido extends MY_Controller {

    var $model = 'pedido_model';
    var $folder_view = 'pedido';
    var $controller = 'pedido';
    var $data_view = array();

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->load_view();
    }

    public function ajax_add() {

        $this->form_validation->set_rules("fecha", "Fecha", "required|xss_clean|trim");
        $this->form_validation->set_rules("descuento", "Descuento", "xss_clean|trim");
        $this->form_validation->set_rules("descuento_id", "Descuento_id", "xss_clean|trim");
        $this->form_validation->set_rules("subtotal", "Subtotal", "required|xss_clean|trim");
        $this->form_validation->set_rules("total", "Total", "required|xss_clean|trim");
        $this->form_validation->set_rules("estado", "Estado", "required|xss_clean|trim");
        $this->form_validation->set_rules("forma_pago", "Forma_pago", "required|xss_clean|trim");
        $this->form_validation->set_rules("calle", "Calle", "required|xss_clean|trim");
        $this->form_validation->set_rules("altura", "Altura", "required|xss_clean|trim");
        $this->form_validation->set_rules("piso", "Piso", "xss_clean|trim");
        $this->form_validation->set_rules("depto", "Depto", "xss_clean|trim");
        $this->form_validation->set_rules("barrio", "Barrio", "xss_clean|trim");
        $this->form_validation->set_rules("manzana", "Manzana", "xss_clean|trim");
        $this->form_validation->set_rules("lote", "Lote", "xss_clean|trim");
        $this->form_validation->set_rules("codigo_postal", "Codigo_postal", "required|xss_clean|trim");
        $this->form_validation->set_rules("provincia", "Provincia", "required|xss_clean|trim");
        $this->form_validation->set_rules("localidad", "Localidad", "required|xss_clean|trim");
        $this->form_validation->set_rules("expreso", "Expreso", "xss_clean|trim");
        $this->form_validation->set_rules("direccion_transporte", "Direccion_transporte", "xss_clean|trim");
        $this->form_validation->set_rules("comentario", "Comentario", "xss_clean|trim");
        $this->form_validation->set_rules("cliente_id", "Cliente_id", "required|xss_clean|trim");


        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array("status" => FALSE, "errores" => validation_errors()));
        } else {
            $data = array(
                "fecha" => $this->input->post("fecha"),
                "descuento" => $this->input->post("descuento"),
                "descuento_id" => $this->input->post("descuento_id"),
                "subtotal" => $this->input->post("subtotal"),
                "total" => $this->input->post("total"),
                "estado" => $this->input->post("estado"),
                "forma_pago" => $this->input->post("forma_pago"),
                "calle" => $this->input->post("calle"),
                "altura" => $this->input->post("altura"),
                "piso" => $this->input->post("piso"),
                "depto" => $this->input->post("depto"),
                "barrio" => $this->input->post("barrio"),
                "manzana" => $this->input->post("manzana"),
                "lote" => $this->input->post("lote"),
                "codigo_postal" => $this->input->post("codigo_postal"),
                "provincia" => $this->input->post("provincia"),
                "localidad" => $this->input->post("localidad"),
                "expreso" => $this->input->post("expreso"),
                "direccion_transporte" => $this->input->post("direccion_transporte"),
                "comentario" => $this->input->post("comentario"),
                "cliente_id" => $this->input->post("cliente_id"),
            );
            $insert = $this->obj_model->save($data);
            echo json_encode(array("status" => $insert));
        }
    }

    public function ajax_update() {

        $this->form_validation->set_rules("fecha", "Fecha", "required|xss_clean|trim");
        $this->form_validation->set_rules("descuento", "Descuento", "xss_clean|trim");
        $this->form_validation->set_rules("descuento_id", "Descuento_id", "xss_clean|trim");
        $this->form_validation->set_rules("subtotal", "Subtotal", "required|xss_clean|trim");
        $this->form_validation->set_rules("total", "Total", "required|xss_clean|trim");
        $this->form_validation->set_rules("estado", "Estado", "required|xss_clean|trim");
        $this->form_validation->set_rules("forma_pago", "Forma_pago", "required|xss_clean|trim");
        $this->form_validation->set_rules("calle", "Calle", "required|xss_clean|trim");
        $this->form_validation->set_rules("altura", "Altura", "required|xss_clean|trim");
        $this->form_validation->set_rules("piso", "Piso", "xss_clean|trim");
        $this->form_validation->set_rules("depto", "Depto", "xss_clean|trim");
        $this->form_validation->set_rules("barrio", "Barrio", "xss_clean|trim");
        $this->form_validation->set_rules("manzana", "Manzana", "xss_clean|trim");
        $this->form_validation->set_rules("lote", "Lote", "xss_clean|trim");
        $this->form_validation->set_rules("codigo_postal", "Codigo_postal", "required|xss_clean|trim");
        $this->form_validation->set_rules("provincia", "Provincia", "required|xss_clean|trim");
        $this->form_validation->set_rules("localidad", "Localidad", "required|xss_clean|trim");
        $this->form_validation->set_rules("expreso", "Expreso", "xss_clean|trim");
        $this->form_validation->set_rules("direccion_transporte", "Direccion_transporte", "xss_clean|trim");
        $this->form_validation->set_rules("comentario", "Comentario", "xss_clean|trim");
        $this->form_validation->set_rules("cliente_id", "Cliente_id", "required|xss_clean|trim");


        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array("status" => FALSE, "errores" => validation_errors()));
        } else {

            $data = array(
                "fecha" => $this->input->post("fecha"),
                "descuento" => $this->input->post("descuento"),
                "descuento_id" => $this->input->post("descuento_id"),
                "subtotal" => $this->input->post("subtotal"),
                "total" => $this->input->post("total"),
                "estado" => $this->input->post("estado"),
                "forma_pago" => $this->input->post("forma_pago"),
                "calle" => $this->input->post("calle"),
                "altura" => $this->input->post("altura"),
                "piso" => $this->input->post("piso"),
                "depto" => $this->input->post("depto"),
                "barrio" => $this->input->post("barrio"),
                "manzana" => $this->input->post("manzana"),
                "lote" => $this->input->post("lote"),
                "codigo_postal" => $this->input->post("codigo_postal"),
                "provincia" => $this->input->post("provincia"),
                "localidad" => $this->input->post("localidad"),
                "expreso" => $this->input->post("expreso"),
                "direccion_transporte" => $this->input->post("direccion_transporte"),
                "comentario" => $this->input->post("comentario"),
                "cliente_id" => $this->input->post("cliente_id"),
            );
            $res = $this->obj_model->update(array('id' => $this->input->post('id')), $data);
            echo json_encode(array("status" => $res));
        }
    }

}
