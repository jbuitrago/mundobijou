<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class cotizacion_model extends Default_model {

    var $table = 'cotizacion';
    var $column = array('id', 'valor');
    var $order = array('id' => 'desc');

    public function __construct() {
        parent::__construct();
    }

    public function get_valor_dolar() {

        $this->db->select('valor');

        $this->db->where('borrado', 'no');

        $query = $this->db->get('cotizacion');

        if ($query->num_rows() > 0) {

            $row = $query->row_array();

            return $row['valor'];
            
        }
        return 1;
    }

}
