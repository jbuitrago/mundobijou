<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class banners_internos_model extends Default_model {

    var $table = 'banners_internos';
    var $column = array('id','file','target','ubicacion');
    var $order = array('id' => 'desc');

    public function __construct() {
        parent::__construct();
    }

    public function get_banners() {

        $this->db->select('banners_internos.*');

        $this->db->from('banners_internos');

        $this->db->where('banners_internos.borrado', 'no');

        //$this->db->where('banners_internos.ubicacion');

        $this->db->order_by('ubicacion', 'asc');

        $this->db->limit(10);

        $query = $this->db->get();

        return $query->result();
    }
}
