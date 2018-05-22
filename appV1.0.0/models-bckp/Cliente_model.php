<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class cliente_model extends Default_model {

    var $table = 'cliente';
    var $column = array('cliente.id','nombre_apellido','usuario','email','provincia.nombre AS provincia','localidad.nombre AS localidad','tipo_cliente.nombre AS tipo_cliente');
    var $order = array('id' => 'desc');

    public function __construct() {
        parent::__construct();
    }
    
    public function query_list_get_select() {

        $this->db->select(implode(',', $this->column));

        $this->db->from($this->table);

        $this->db->join('provincia', 'provincia.id = ' . $this->table . '.provincia','left');

        $this->db->join('localidad', 'localidad.id = ' . $this->table . '.localidad','left');
        
        $this->db->join('tipo_cliente', 'tipo_cliente.id = ' . $this->table . '.tipo_cliente','left');

        $this->db->where($this->table . '.borrado', 'no');
    }
    
    public function get_localidades($id) {
        
        $this->db->from('localidad');

        $this->db->where('provincia_id', $id);
        
        $this->db->order_by('nombre', 'asc');

        $query = $this->db->get();

        return $query->result();
        
    }
     function olvide_password($value) {

        $this->db->select('usuario, email');
        $this->db->from($this->table);
        $this->db->where('usuario = "'.$value .'"');
        $this->db->or_where('email = "'.$value .'"');
        $this->db->where('borrado = "no" ');
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return true;
        }
        else 
        {
            return false;
        }
        die;
    }
    
    function id_user($value) {

        $this->db->select('id,nombre_apellido,email');
        $this->db->from($this->table);
        $this->db->where('usuario = "'.$value .'"');
        $this->db->or_where('email = "'.$value .'"');
        $this->db->where('borrado = "no" ');
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return $query->row();
        }
        else 
        {
            return false;
        }
        die;
    }
    
    function login($username, $password) {
       // $this->db->select(implode(',', $this->column));
        $this->db->select('*', FALSE);
        $this->db->from($this->table);
        //$this->db->join('roles', 'roles.id = usuarios.roles_id');
        $this->db->where('usuario = ' . "'" . $username . "'");
        $this->db->where('password = ' . "'" . $password . "'");
        $this->db->where('borrado = "no" ');
        $this->db->limit(1);

        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
        die;
    }
}
