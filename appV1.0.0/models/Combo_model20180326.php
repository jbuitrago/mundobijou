<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class combo_model extends Default_model {

    var $table = 'combo';
    var $column = array('combo.id', 'titulo', 'articulo', 'precio_mayorista', 'precio_revendedor', 'stock');
    var $order = array('id' => 'desc');

    public function __construct() {
        parent::__construct();
    }

    public function query_list_get_select() {

        $this->db->select(implode(',', $this->column));

        $this->db->from($this->table);

        //$this->db->join('categorias', 'categorias.id = ' . $this->table . '.categoria_id');
        //$this->db->join('subcategorias', 'subcategorias.id = ' . $this->table . '.sub_categoria_id');

        $this->db->where($this->table . '.borrado', 'no');
    }

    public function get_colores($id) {

        $this->db->select('color_id');

        $this->db->from('combo_colores');

        $this->db->where('combo_id', $id);

        $query = $this->db->get();

        $data = array();

        foreach ($query->result() as $row) {
            $data[] = $row->color_id;
        }

        return $data;
    }

    public function get_talles($id) {

        $this->db->select('talle_id');

        $this->db->from('combo_talles');

        $this->db->where('combo_id', $id);

        $query = $this->db->get();

        $data = array();

        foreach ($query->result() as $row) {
            $data[] = $row->talle_id;
        }

        return $data;
    }

    public function get_medidas($id) {

        $this->db->select('medida_id');

        $this->db->from('combo_medidas');

        $this->db->where('combo_id', $id);

        $query = $this->db->get();

        $data = array();

        foreach ($query->result() as $row) {
            $data[] = $row->medida_id;
        }

        return $data;
    }

    public function delete_all_from($table, $id) {

        $this->db->where('combo_id', $id);

        $this->db->delete($table);
    }

    public function save($data, $items = '', $extra = '') {

        $this->db->trans_start();

        try {

            $this->db->insert($this->table, $data);

            $id = $this->db->insert_id();

            $this->last_id = $id;

            $this->insert_items($id, $items); //inserto items
        } catch (Exception $ex) {

            $this->db->trans_rollback();

            return FALSE;
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {

            $this->db->trans_rollback();

            return FALSE;
        } else {

            $this->db->trans_commit();

            return $id;
        }
    }

    private function insert_items($combo_id, $items) {

        $data2 = array();

        $cant = count($items);

        for ($i = 0; $i < $cant; $i++) {

            $data2[] = array(
                "nombre" => $items[$i][1],
                "numero_articulo" => $items[$i][2],
                "cantidad" => $items[$i][3],
                "imagen" => $items[$i][5],
                "combo_id" => $combo_id,
                "fecha_insert" => date('Y-m-d H:i:s'));
        }

        $this->db->insert_batch('composicion_combo', $data2);
    }

    public function get_items($combo_id) {

        $this->db->select('nombre, numero_articulo, cantidad, imagen');

        $this->db->from('composicion_combo');

        $this->db->where('composicion_combo.combo_id', $combo_id);

        $query = $this->db->get();

        return $query->result();
    }

    public function update($where, $data, $items = '') {

        $this->db->trans_start();

        $actualizado = parent::update($where, $data, $items);

        if ($actualizado > 0) {

            $combo_id = $where['id'];

            if ($this->delete_items($combo_id) > 0) {

                $this->insert_items($combo_id, $items);

                $this->db->trans_complete();

                if ($this->db->trans_status() === FALSE) {
                    return array("status" => FALSE, "items" => $items);
                } else {
                    //return TRUE;
                    return array("status" => TRUE, "items" => $items);
                }
            } else {
                return array("status" => FALSE, "items" => $items);
            }
        }
    }

    public function delete_items($combo_id) {

        $this->db->delete('composicion_combo', array('combo_id' => $combo_id));

        return $this->db->affected_rows();
    }

    function get_combos($limit, $start, $sort, $order, $categoria, $subcategoria, $filtro='') {

        $this->db->select('*, (SELECT COUNT(*) FROM pedido_items WHERE tipo_producto="combo" AND producto_id=combo.id GROUP BY combo.id) AS moresell', FALSE);

        if ($categoria != 0) {
            $this->db->where('categoria_id', $categoria);
        }
        if ($subcategoria != 0) {
            $this->db->where('sub_categoria_id', $subcategoria);
        }

        if ($filtro == 'ofertas') {
            $this->db->where('oferta', 'SI');
        }

        if ($filtro == 'nuevos') {
            $this->db->where('nuevo', 'SI');
        }
        
        $this->db->where('borrado', 'no');

        if (!empty($_POST)) {

            $this->db->where("(titulo LIKE '%" . $_POST['search_ter'] . "%' OR articulo LIKE '%" . $_POST['search_ter'] . "%' OR descripcion LIKE '%" . $_POST['search_ter'] . "%' OR descripcion_corta LIKE '%" . $_POST['search_ter'] . "%')", NULL, FALSE);
        }

        $this->db->order_by($sort, $order);

        $this->db->limit($limit, $start);

        $query = $this->db->get('combo');

        return $query->result();
    }

    public function record_count($categoria, $subcategoria, $filtro='') {

        if ($categoria != 0) {
            $this->db->where('categoria_id', $categoria);
        }
        if ($subcategoria != 0) {
            $this->db->where('sub_categoria_id', $subcategoria);
        }

        if ($filtro == 'ofertas') {
            $this->db->where('oferta', 'SI');
        }

        if ($filtro == 'nuevos') {
            $this->db->where('nuevo', 'SI');
        }
        
        $this->db->where('borrado', 'no');

        $this->db->from('combo');

        return $this->db->count_all_results();
    }

    public function get_by_slug($id) {

        $this->db->select($this->table . '.*', FALSE);

        $this->db->from($this->table);

        $this->db->where('slug', $id);

        $this->db->where('borrado', 'no');

        $query = $this->db->get();

        return $query->row();
    }

    public function last_combos() {

        $this->db->select('*');

        $this->db->from($this->table);

        $this->db->where($this->table . '.borrado', 'no');

        $this->db->where($this->table . '.nuevo', 'SI');

        $this->db->order_by('id', 'desc');

        $this->db->limit(2);

        $query = $this->db->get();

        return $query->result();
    }

    public function get_ofertas_combos() {

        $this->db->select('*');

        $this->db->from($this->table);

        $this->db->where($this->table . '.borrado', 'no');

        $this->db->where($this->table . '.oferta', 'SI');

        $this->db->order_by('id', 'desc');

        $this->db->limit(2);

        $query = $this->db->get();

        return $query->result();
    }

    public function get_cantidad_combos() {

        $this->db->select('id');

        $this->db->from($this->table);

        $this->db->where($this->table . '.borrado', 'no');

        $this->db->order_by('id', 'desc');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            
            return $query->num_rows();
            
        }else{
            return 0;
        }
    }

}
