<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class pedido_model extends Default_model {

    var $table = 'pedido';
    var $column = array('pedido.id', 'LPAD(pedido.id,4,0) AS numero', 'pedido.fecha_insert', 'FORMAT(pedido.total,2)', 'estado_pedido.nombre AS estado', 'pedido.forma_pago', 'cliente.email AS cliente');
    var $order = array('id' => 'desc');

    public function __construct() {
        parent::__construct();
    }

    public function query_list_get_select() {

        $this->db->select(implode(',', $this->column));

        $this->db->from($this->table);

        $this->db->join('estado_pedido', 'estado_pedido.id = ' . $this->table . '.estado');

        $this->db->join('cliente', 'cliente.id = ' . $this->table . '.cliente_id');

        $this->db->where($this->table . '.borrado', 'no');
    }

    public function get_last_direccion($cliente_id) {

        $this->db->select($this->table . '.*');

        $this->db->from($this->table);

        $this->db->where('cliente_id', $cliente_id);

        $this->db->where('borrado = "no" ');

        $this->db->order_by('id', 'desc');

        $this->db->limit(1);

        $query = $this->db->get();

        if ($query->num_rows() == 1) {

            return $query->row();
        } else {
           
            return FALSE;
        }
    }

    public function save_web() {

        $data = array_merge($this->session->userdata('cliente_logged_in'), $this->session->userdata('direccion_pedido'), $this->session->userdata('formapago_pedido'), (is_array($this->session->userdata('descuento_aplicado'))) ? $this->session->userdata('descuento_aplicado') : array());

        $data['forma_pago'] = $data['payment'];

        $data["fecha_insert"] = date('Y-m-d H:i:s');

        unset($data['cliente_email']);
        unset($data['cliente_usuario']);
        unset($data['tipo_cliente']);
        unset($data['codigo_descuento']);
        unset($data['payment']);
        unset($data['logged_in']);

        $cart_items = $this->cart->contents();

        $grand_total = 0;

        $subtotal = 0;
        $i = 1;

        $items = array();

        foreach ($cart_items as $item) {

            $di = array('producto_id' => $item['id'],
                'pedido_id' => 0,
                'tipo_producto' => $this->cart->product_options($item['rowid'])['tipo_producto'],
                'cantidad' => $item['qty'],
                'precio_unitario' => $item['price'],
                'subtotal' => $item['price'] * $item['qty'],
                'nombre' => $item['name'],
                'imagen' => $this->cart->product_options($item['rowid'])['imagen'],
                'talle_id' => $this->cart->product_options($item['rowid'])['talle_id'],
                'color_id' => $this->cart->product_options($item['rowid'])['color_id'],
                'medida_id' => $this->cart->product_options($item['rowid'])['medida_id'],
                'cantidad_a_descontar' => $this->cart->product_options($item['rowid'])['cantidad_a_descontar'],
                'my_url' => $this->cart->product_options($item['rowid'])['my_url'],
                'fecha_insert' => date('Y-m-d H:i:s'));
            

            $items[] = $di;

            $grand_total = $grand_total + $item['subtotal'];

            $d = (!empty($_SESSION['descuento_aplicado']['porcentaje'])) ? $_SESSION['descuento_aplicado']['porcentaje'] : 0;

            $descuento = number_format(($grand_total * $d) / 100, 2);

            $subtotal = $subtotal + $item['subtotal'];

            $grand_total = $grand_total - $descuento;
        }

        $data['valor_descuento'] = $descuento;
        
        $data['subtotal'] = $subtotal;

        $data['total'] = $grand_total;

        $data['estado'] = 1;

        $this->db->trans_start();

        try {

            $this->db->insert($this->table, $data); //inserto compra

            $id = $this->db->insert_id();

            $this->last_id = $id;

            $cantidad_items = count($items);

            $items_sin_stock = array();

            for ($i = 0; $i < $cantidad_items; $i++) {

                $items[$i]['pedido_id'] = $this->last_id;

                $this->db->insert('pedido_items', $items[$i]);

                $res = $this->update_stock($items[$i]['tipo_producto'], $items[$i]['producto_id'], $items[$i]['cantidad_a_descontar'], $items[$i]['cantidad']);

                if (!$res['status']) {
                    $items_sin_stock[] = array('articulo' => $items[$i]['imagen'], 'titulo' => $items[$i]['nombre'], 'imagen'=>$items[$i]['imagen'], 'color_id'=>$items[$i]['color_id'], 'talle_id'=>$items[$i]['talle_id'], 'medida_id'=>$items[$i]['medida_id']);
                }
            }
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

            return array("status" => TRUE, "id" => $id, 'productos_sin_stock' => $items_sin_stock, 'items' => $items);
        }
    }

    public function update_stock($tipo_producto, $producto_id, $cantidad_a_descontar, $cantidad) {

        if (!$this->hay_stock($tipo_producto, $producto_id, $cantidad_a_descontar, $cantidad)) {

            $status = array('producto_id' => $producto_id, 'tipo_producto' => $tipo_producto, 'status' => FALSE);
        } else {
            $status = array('producto_id' => $producto_id, 'status' => TRUE, 'tipo_producto' => $tipo_producto,);
        }

        $this->db->query('UPDATE ' . $tipo_producto . ' SET stock = stock-' . $cantidad * $cantidad_a_descontar . ', fecha_update = "' . date('Y-m-d H:i:s') . '" WHERE id = ' . $producto_id . ' LIMIT 1');

        return $status;
    }

    public function hay_stock($tipo_producto, $producto_id, $cantidad_a_descontar, $cantidad) {

        $this->db->select('stock');

        $this->db->where('borrado', 'no');

        $this->db->where('id', $producto_id);

        $query = $this->db->get($tipo_producto);

        if ($query->num_rows() > 0) {

            $row = $query->row_array();

            if (($row['stock'] - ($cantidad * $cantidad_a_descontar)) > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
        return FALSE;
    }

    public function hay_stockb($tipo_producto, $producto_id) {

        $this->db->select('stock');

        $this->db->where('stock <= 0');

        $this->db->where('borrado', 'no');

        $this->db->where('id', $producto_id);

        $query = $this->db->get($tipo_producto);

        //echo $this->db->last_query(); die;
        
        if ($query->num_rows() > 0) {

            return FALSE;
        } else {
            return TRUE;
        }

        return FALSE;
    }

    public function get_pedidos_by_cliente($cliente_id) {

        $this->db->select($this->table . '.*');

        $this->db->from($this->table);

        $this->db->where($this->table . '.borrado', 'no');

        $this->db->where($this->table . '.cliente_id', $cliente_id);

        $this->db->order_by($this->table . '.id', 'desc');

        $query = $this->db->get();

        return $query->result();
    }

    public function get_items($pedido_id) {

        $this->db->select('pedido_items.*, producto.slug');

        $this->db->from('pedido_items');

        $this->db->join('producto', 'producto.id = pedido_items.producto_id', 'left');

        $this->db->where('pedido_id', $pedido_id);

        $this->db->where('tipo_producto', 'producto');
        
        $this->db->order_by('id', 'desc');

        $query1 = $this->db->get()->result();

        // Query #2

        $this->db->select('pedido_items.*, combo.slug');

        $this->db->from('pedido_items');

        $this->db->join('combo', 'combo.id = pedido_items.producto_id', 'left');

        $this->db->where('pedido_id', $pedido_id);

        $this->db->where('tipo_producto', 'combo');
        
        $this->db->order_by('id', 'desc');

        $query2 = $this->db->get()->result();

        // Merge both query results

        $query = array_merge($query1, $query2);

        return $query;
    }

    public function get_by_id_and_cliente($id, $cliente_id) {

        $this->db->from($this->table);

        $this->db->where('id', $id);

        $this->db->where('cliente_id', $cliente_id);

        if ($this->apply_filter_system)
            $this->db->where($this->table . '.' . $this->campo_system, $this->sistema_actual);

        if ($this->apply_filter_user)
            $this->db->where($this->table . '.' . $this->campo_user, $this->user_id);

        $query = $this->db->get();

        return $query->row();
    }

    public function delete_by_id($id) {

        $this->db->trans_start();

        try {

            $resultado = parent::delete_by_id($id);

            if ($resultado) {

                $items = $this->get_items($id);

                foreach ($items as $item) {

                    $this->db->set('stock', 'stock+' . $item->cantidad * $item->cantidad_a_descontar, FALSE);

                    $this->db->where('id', $item->producto_id);

                    $this->db->update($item->tipo_producto);
                }
            }
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

            return array("status" => TRUE, "id" => $id);
        }
    }

    public function aumentar_stock_pedido_cancelado($id) {

        $items = $this->get_items($id);

        foreach ($items as $item) {

            $this->db->set('stock', 'stock+' . $item->cantidad * $item->cantidad_a_descontar, FALSE);

            $this->db->where('id', $item->producto_id);

            $this->db->update($item->tipo_producto);
        }
    }

}
