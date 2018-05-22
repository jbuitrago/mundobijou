<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Menu_model extends Default_model {

    var $table = 'menu';
    var $column = array('id','nombre', 'orden');
    var $order = array('id' => 'desc');

    public function __construct() {
        parent::__construct();
        $this->apply_filter_system = FALSE;
    }
    
    public function delete_intermedia($table, $field1, $field2, $val1, $val2){
        
        $this->db->where($field1, $val1);
        
        $this->db->where($field2, $val2);

        $this->db->delete($table);
        
        
    }
    
    
}
