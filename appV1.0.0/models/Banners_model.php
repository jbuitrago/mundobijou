<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Banners_model extends Default_model {

    var $table = 'banners';
    var $column = array('id','orden','titulo','descripcion', 'link');
    var $order = array('id' => 'desc');

    public function __construct() {
        parent::__construct();
        $this->apply_filter_system = FALSE;
    }
    
    public function get_all() {
        
        $this->db->select('banners.*');
        
        $this->db->from('banners');
        
        $this->db->where('banners.borrado', 'no');

        $this->db->order_by('banners.orden', 'asc');
        
        $query = $this->db->get();

       return $query->result();
      
    }
    public function get_banners() {
        $this->db->select('banners.*');
        $this->db->from('banners');
        $this->db->where('banners.borrado', 'no');
        $this->db->order_by('banners.orden', 'asc');
        $this->db->limit(5);
        $query = $this->db->get();
        return $query->result();
    }
}
