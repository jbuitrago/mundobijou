<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class descuento_model extends Default_model {

    var $table = 'descuento';
    var $column = array('id','codigo','porcentaje');
    var $order = array('id' => 'desc');

    public function __construct() {
        parent::__construct();
    }
    
    function get_by_codigo($codigo) {

        $this->db->select($this->table . '.*');

        $this->db->from($this->table);

        $this->db->where('codigo', $codigo);

        $this->db->where('borrado = "no" ');

        $this->db->limit(1);

        $query = $this->db->get();

        if ($query->num_rows() == 1) {
        
            return $query->row();
        } else {
            return FALSE;
        }
    }

}
