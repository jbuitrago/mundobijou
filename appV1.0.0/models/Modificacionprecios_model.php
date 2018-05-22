<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class modificacionprecios_model extends Default_model {

    var $table = 'modificacion';
    var $column = array('id', 'valor');
    var $order = array('id' => 'desc');

    public function __construct() {
        parent::__construct();
    }

    private function base_query_actualizacion($valor) {

        $this->db->set('precio_mayorista', 'TRUNCATE(precio_mayorista+((precio_mayorista*' . $valor . ')/100),2)', FALSE);

        $this->db->set('precio_oferta_mayorista', 'TRUNCATE(precio_oferta_mayorista+((precio_oferta_mayorista*' . $valor . ')/100),2)', FALSE);

        $this->db->set('precio_revendedor', 'TRUNCATE(precio_revendedor+((precio_revendedor*' . $valor . ')/100),2)', FALSE);

        $this->db->set('precio_oferta_revendedor', 'TRUNCATE(precio_oferta_revendedor+((precio_oferta_revendedor*' . $valor . ')/100),2)', FALSE);

        $this->db->set('precio_por_diez_mayorista', 'TRUNCATE(precio_por_diez_mayorista+((precio_por_diez_mayorista*' . $valor . ')/100),2)', FALSE);

        $this->db->set('precio_por_diez_revendedor', 'TRUNCATE(precio_mayorista+((precio_por_diez_revendedor*' . $valor . ')/100),2)', FALSE);

        $this->db->set('precio_por_diez_oferta_mayorista', 'TRUNCATE(precio_por_diez_oferta_mayorista+((precio_por_diez_oferta_mayorista*' . $valor . ')/100),2)', FALSE);
    }

    public function actualizar_precios_productos($valor, $productos = '') {

        $this->base_query_actualizacion($valor);

        if ($productos != '') {
            $this->db->where_in('id', $productos);
        }

        return $this->db->update('producto'); // gives UPDATE mytable SET field = field+1 WHERE id = 2
    }

    public function actualizar_precios_combos($valor, $combos = '') {

        $this->base_query_actualizacion($valor);

        if ($combos != '') {
            $this->db->where_in('id', $combos);
        }

        return $this->db->update('combo'); // 
    }

    public function actualizar_precios_productos_por_categorias($valor, $categoria, $subcategorias) {

        $this->base_query_actualizacion($valor);

        $this->db->where('categoria_id', $categoria);

        if ($subcategorias != '') {
            $this->db->where_in('sub_categoria_id', $subcategorias);
        }

        return $this->db->update('producto'); // 
    }

    public function actualizar_precios_combos_por_categorias($valor, $categoria, $subcategorias) {

        $this->base_query_actualizacion($valor);

        $this->db->where('categoria_id', $categoria);

        if ($subcategorias != '') {
            $this->db->where_in('sub_categoria_id', $subcategorias);
        }

        return $this->db->update('combo'); // 
    }

}
