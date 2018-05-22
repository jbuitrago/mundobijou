<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class subcategorias_model extends Default_model {

    var $table = 'subcategorias';
    var $column = array('subcategorias.id', 'categorias.nombre AS categoria', 'subcategorias.nombre', 'subcategorias.destacada');
    var $order = array('id' => 'desc');

    public function __construct() {
        parent::__construct();
    }

    public function query_list_get_select() {

        $this->db->select(implode(',', $this->column));

        $this->db->from($this->table);

        $this->db->join('categorias', 'categorias.id = ' . $this->table . '.categoria_padre');

        //  $this->db->join('subcategorias', 'subcategorias.id = ' . $this->table . '.sub_categoria_id');

        $this->db->where($this->table . '.borrado', 'no');
    }

    public function get_subcategorias($id) {
        
        $this->db->from($this->table);

        $this->db->where('categoria_padre', $id);

        $query = $this->db->get();

        return $query->result();
        
    }
}
