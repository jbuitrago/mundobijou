<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Informes_model extends Default_model {

    var $table = 'pedido';
    var $column = array('id', 'nombre');
    var $order = array('id' => 'desc');

    public function __construct() {
        
        parent::__construct();
        
        $this->cantidad_a_sumar_para_order_by = 1;
    }

    public function get_ventas() {

        $this->db->select('EXTRACT(MONTH FROM fecha_insert) as mes, SUM(total) as monto', FALSE);

        $this->db->where('estado<>3');

        $this->db->where('borrado', 'no');

        if (!empty($_POST['start'])) {
            $this->db->where('fecha_insert>="' . date("Y-m-d", strtotime($_POST['start'])) . '"');
        }

        if (!empty($_POST['end'])) {
            $this->db->where('fecha_insert<="' . date("Y-m-d", strtotime($_POST['end'])) . '"');
        }

        $this->db->group_by('EXTRACT(MONTH FROM fecha_insert)');

        $this->db->group_by('EXTRACT(YEAR FROM fecha_insert)');

        $query = $this->db->get('pedido');

        return $query->result();
    }

    public function get_ventas_por_articulo() {

        $this->db->select('pedido_items.tipo_producto, pedido_items.nombre AS articulo, categorias.nombre AS categoria, subcategorias.nombre AS subcategoria, SUM(pedido_items.cantidad*pedido_items.cantidad_a_descontar) AS cantidad, SUM(pedido_items.subtotal) as monto', FALSE);

        $this->db->from('pedido_items');

        $this->db->join('pedido', 'pedido.id = pedido_items.pedido_id');

        $this->db->join('producto', 'producto.id = pedido_items.producto_id', 'left');

        $this->db->join('combo', 'combo.id = pedido_items.producto_id', 'left');

        $this->db->join('categorias', 'categorias.id = producto.categoria_id', 'left');

        $this->db->join('subcategorias', 'subcategorias.id = producto.sub_categoria_id', 'left');

        $this->db->where('pedido.estado<>3');

        $this->db->where('pedido.borrado', 'no');

        if (!empty($_POST['start'])) {
            $this->db->where('pedido.fecha_insert>="' . date("Y-m-d", strtotime($_POST['start'])) . '"');
        }

        if (!empty($_POST['end'])) {
            $this->db->where('pedido.fecha_insert<="' . date("Y-m-d", strtotime($_POST['end'])) . '"');
        }

        $this->db->group_by('pedido_items.producto_id');

        $query = $this->db->get();
     
        return $query->result();
    }

    public function get_ventas_por_categoria() {

        $this->db->select('categorias.nombre AS categoria, SUM(pedido_items.cantidad*pedido_items.cantidad_a_descontar) AS cantidad, SUM(pedido_items.subtotal) as monto', FALSE);

        $this->db->from('pedido_items');

        $this->db->join('pedido', 'pedido.id = pedido_items.pedido_id');

        $this->db->join('producto', 'producto.id = pedido_items.producto_id');

        $this->db->join('categorias', 'categorias.id = producto.categoria_id');

        $this->db->where('pedido.estado<>3');

        $this->db->where('pedido.borrado', 'no');

        if (!empty($_POST['start'])) {
            $this->db->where('pedido.fecha_insert>="' . date("Y-m-d", strtotime($_POST['start'])) . '"');
        }

        if (!empty($_POST['end'])) {
            $this->db->where('pedido.fecha_insert<="' . date("Y-m-d", strtotime($_POST['end'])) . '"');
        }

        $this->db->group_by('pedido_items.producto_id');

        $query = $this->db->get();

        return $query->result();
    }
    
    public function query_list_get_select() {

        $input_stock = 'CONCAT("<div class=\'text-right\'><input type=\'number\' value=\'",stock,"\' class=\'text-right\' onchange=\'actualizar_stock(this.value, ",producto.id,")\'></div>") AS s';

        $this->column = array('producto.id', 'articulo', 'categorias.nombre AS categoria', 'subcategorias.nombre AS subcategoria', 'titulo', $input_stock);

        $this->db->select(implode(',', $this->column));

        $this->db->from('producto');

        $this->db->join('categorias', 'categorias.id = producto.categoria_id');

        $this->db->join('subcategorias', 'subcategorias.id = producto.sub_categoria_id', 'left');

        $this->db->where('producto.borrado', 'no');
    }

    public function query_list_apply_filter_search_and_orderby() {

        $i = 0;

        $str = '';

        foreach ($this->column as $item) {
            if (isset($_POST['search']['value']) && $_POST['search']['value']) {

                $str .= ($i === 0) ? " (" . explode('AS', $item)[0] . " LIKE '%" . $_POST['search']['value'] . "%'" : " OR " . explode('AS', $item)[0] . " LIKE '%" . $_POST['search']['value'] . "%' ESCAPE '!'";
            }
            $column[$i] = $item;
            $i++;
        }
        if (isset($_POST['search']['value']) && $_POST['search']['value']) {
            $this->db->where($str . ")");
        }
        $column = array_values($column);

        unset($column[0]);

        if (isset($_POST['order'])) {

            if (($_POST['order']['0']['column'] + $this->cantidad_a_sumar_para_order_by == 5)) {

                $this->db->order_by('stock', $_POST['order']['0']['dir']);
            } else {

                $this->db->order_by($_POST['order']['0']['column'] + $this->cantidad_a_sumar_para_order_by, $_POST['order']['0']['dir']);
            }
        } else if (isset($this->order)) {

            $order = $this->order;

            $this->db->order_by(key($order), $order[key($order)]);
        }
        
        //echo $this->db->last_query();
    }

}
