<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class metadescripciones_config_model extends Default_model {

    var $table = 'metadescripciones_config';
    var $column = array('id', 'fecha_insert', 'fecha_update', 'metadescripcion_inicio');
    var $order = array('id' => 'desc');

    public function __construct() {
        parent::__construct();
    }

    public function get_last() {

        $this->db->select($this->table . '.*');

        $this->db->from($this->table);

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

}
