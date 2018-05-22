<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/controllers/my_controller.php';

class pedidos extends MY_Controller {

    var $model = 'pedido_model';
    var $folder_view = 'pedido';
    var $controller = 'pedidos';
    var $data_view = array();

    public function __construct() {

        parent::__construct();

        $this->load->model('Cliente_model', 'cliente');

        $this->load->model('Producto_model', 'producto');

        $this->load->model('Combo_model', 'combo');

        $this->load->model('Cotizacion_model', 'cotizacion');
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
        $this->form_validation->set_rules("porcentaje", "Descuento", "xss_clean|trim");
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
                "porcentaje" => $this->input->post("porcentaje"),
                "descuento_id" => $this->input->post("descuento_id"),
                "subtotal" => $this->input->post("subtotal"),
                "total" => $this->input->post("total"),
                "estado" => $this->input->post("estado"),
                "forma_pago" => $this->input->post("forma_pago"),
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
                "expreso" => $this->input->post("expreso"),
                "direccion_transporte" => $this->input->post("direccion_transporte"),
                "comentario" => $this->input->post("comentario"),
                "cliente_id" => $this->input->post("cliente_id"),
            );

            $original_value = $this->db->query("SELECT estado FROM pedido WHERE id = " . $this->input->post('id') . " AND borrado='no'")->row()->estado;

            if ($this->input->post('estado') != $original_value && ($this->input->post('estado') == 3)) {

                $aumentar = TRUE;
            } else {

                $aumentar = FALSE;
            }

            $this->db->trans_start();

            try {

                $res = $this->obj_model->update(array('id' => $this->input->post('id')), $data);

                $this->actualizar_items($this->input->post('id'));

                if ($aumentar) {
                    $this->obj_model->aumentar_stock_pedido_cancelado($this->input->post('id'));
                }

                echo json_encode(array("status" => $res));
                
            } catch (Exception $ex) {

                $this->db->trans_rollback();

                return array("status" => FALSE, "error" => 'error bug');
            }

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {

                $this->db->trans_rollback();

                return array("status" => FALSE, "error" => 'Error transaccion');
            } else {

                $this->db->trans_commit();

                return array("status" => TRUE);
            }
        }
    }

    public function add($id = 0) {

        $msg['controller'] = $this->controller;

        $pedido = $this->obj_model->get_by_id($id);

        $msg['provincias'] = $this->generate_data_dropdown('provincia', 'nombre', FALSE);

        $msg['estados_pedido'] = $this->generate_data_dropdown('estado_pedido', 'nombre', FALSE);

        $msg['medidas'] = $this->generate_data_dropdown('medidas', 'nombre', FALSE);

        $msg['talles'] = $this->generate_data_dropdown('talles', 'nombre', FALSE);

        $msg['colores'] = $this->generate_data_dropdown('colores', 'codigo', FALSE);

        $msg['productos'] = $this->generate_data_dropdown('producto', 'titulo', FALSE);

        $msg['combos'] = $this->generate_data_dropdown('combo', 'titulo', FALSE);

        $msg['url_images'] = $this->files_urlpath_s;

        $msg['cliente'] = $this->cliente->get_by_id($pedido->cliente_id);

        $msg['accion'] = 'add';

        $msg['cotizacion'] = $this->cotizacion->get_valor_dolar();

        if ($id != 0) {
            $msg['id'] = $id;

            $msg['items'] = $this->obj_model->get_items($id);
        }
        if ($id != 0) {
            $msg['accion'] = 'update';
        }

        $msg['output'] = $this->load->view($this->folder_view . '/add', $msg, TRUE);

        $this->load->view('dashboard', $msg);
    }

    public function get_localidades($id) {

        echo json_encode($this->obj_model->get_localidades($id));
        //echo $this->db->last_query();
    }

    public function get_informacion_producto($id) {

        $producto = $this->producto->get_by_id($id);

        $producto->colores_selected = $this->producto->web_get_colores($id);

        $producto->talles_selected = $this->producto->web_get_talles($id);

        $producto->medidas_selected = $this->producto->web_get_medidas($id);

        echo json_encode($producto);
    }

    public function get_informacion_combo($id) {

        echo json_encode($this->combo->get_by_id($id));
    }

    private function actualizar_items($pedido_id) {

        $items = $this->obj_model->get_items($pedido_id);

        foreach ($items as $item) {
            /* re actualizo el stock */
            $a_descontar = $item->cantidad * $item->cantidad_a_descontar;

            $this->db->set('stock', 'stock+' . $a_descontar, FALSE);

            $this->db->where('id', $item->producto_id);

            $this->db->update($item->tipo_producto);

            /* elimino el item */
            $this->db->where('id', $item->id);

            $this->db->delete('pedido_items');
        }
        
        $items_save = array();

        foreach ($_POST['cart'] as $p) {

            /* [id] => 4
              [name] => Pulsera 12162
              [price] => 36
              [qty] => 5
              [color_id] => 4
              [medida_id] => 7
              [talle_id] => 9
              [imagen] => 9587541306.jpg
              [tipo_producto] => producto
              [producto_id] => 4
              [cantidad_a_descontar] => 1
              [subtotal] => 180.00 */


            $di = array('producto_id' => $p['id'],
                'pedido_id' => $pedido_id,
                'tipo_producto' => $p['tipo_producto'],
                'cantidad' => $p['qty'],
                'precio_unitario' => $p['price'],
                'subtotal' => $p['price'] * $p['qty'],
                'nombre' => $p['name'],
                'imagen' => $p['imagen'],
                'talle_id' => $p['talle_id'],
                'color_id' => $p['color_id'],
                'medida_id' => $p['medida_id'],
                'cantidad_a_descontar' => $p['cantidad_a_descontar'],
                'fecha_insert' => date('Y-m-d H:i:s'));

            $items_save[] = $di;
        }

        $cantidad_items = count($items_save);

        for ($i = 0; $i < $cantidad_items; $i++) {

            $this->db->insert('pedido_items', $items_save[$i]);

            $res = $this->obj_model->update_stock($items_save[$i]['tipo_producto'], $items_save[$i]['producto_id'], $items_save[$i]['cantidad_a_descontar'], $items_save[$i]['cantidad']);
        }
    }

}
