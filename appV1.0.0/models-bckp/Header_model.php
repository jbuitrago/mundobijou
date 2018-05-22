<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Header_model extends Default_model {

    var $table = 'categorias';
    var $table2 = 'subcategorias';
    var $column = array('categorias.id,categorias.nombre,categorias.foto,categorias.destacada,COUNT(subcategorias.nombre) AS total');
    var $column2 = array('id,nombre,categoria_padre,foto');
    var $order = array('id' => 'desc');

    public function __construct() {
        parent::__construct();
        // $this->apply_filter_system = FALSE;
    }

    public function get_all_categorias() {

        $this->db->select(implode(',', $this->column));
        $this->db->from($this->table);
        $this->db->join('subcategorias', 'subcategorias.categoria_padre = ' . $this->table . '.id', 'left');
        $this->db->where($this->table . '.borrado', 'no');
        $this->db->where('(SELECT COUNT(*) FROM producto WHERE borrado = "no" AND producto.categoria_id=categorias.id AND producto.stock>0 ) >0', NULL, FALSE);
        $this->db->group_by('categorias.nombre');
        $this->db->order_by('categorias.nombre', 'asc');
      
        $query = $this->db->get();

        return $query->result();
    }

    public function get_all_subcategorias() {

        $this->db->select(implode(',', $this->column2));
        $this->db->from($this->table2);
        //$this->db->join('subcategorias', 'subcategorias.categoria_padre = ' . $this->table . '.id','left');
        $this->db->where($this->table2 . '.borrado', 'no');
        $this->db->where('(SELECT COUNT(* ) FROM producto WHERE borrado = "no" AND producto.sub_categoria_id=' . $this->table2 . '.id AND producto.stock>0 ) >0', NULL, FALSE);
        // $this->db->group_by('nombre'); 
        $this->db->order_by('nombre', 'asc');
        $query = $this->db->get();

        return $query->result();
    }

}
