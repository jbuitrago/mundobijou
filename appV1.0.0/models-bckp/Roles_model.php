<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Roles_model extends Default_model {

    var $table = 'roles';
    var $column = array('id','nombre');
    var $order = array('id' => 'desc');

    public function __construct() {
        parent::__construct();
        $this->apply_filter_system = FALSE;
    }
    
    public function get_acciones_by_rol($rol_id) {
        
        $this->db->select('menu.nombre AS menu, acciones.descripcion AS accion');

        $this->db->from('menu_rol_accion');

        $this->db->join('menu', 'menu.id = menu_rol_accion.menu_id');
        
        $this->db->join('acciones', 'acciones.id = menu_rol_accion.accion_id');

        $this->db->where('menu_rol_accion.rol_id', $rol_id);

        $this->db->where('menu.borrado', 'no');
        
        $this->db->order_by('menu', 'asc');

        return $this->db->get()->result();
        
    }
}


