<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios_model extends Default_model {

    var $table = 'usuarios';
    var $column = array('usuarios.id', 'usuarios.nombre', 'usuarios.apellido', 'usuarios.usuario', 'usuarios.email', 'roles.nombre'/* ,'sistemas,nombre', 'planes.nombre' */);
    var $order = array('id' => 'desc');

    public function __construct() {
        parent::__construct();
        $this->apply_filter_system = FALSE;
    }

    public function _get_datatables_query() {
        $this->db->select('usuarios.id, usuarios.nombre, usuarios.apellido, usuarios.usuario, usuarios.email, roles.nombre AS Rol', FALSE);
        $this->db->from($this->table);
        $this->db->join('roles', 'roles.id = usuarios.roles_id');
        if ($this->apply_filter_system)
            $this->db->join('sistemas', 'sistemas.id = usuarios.sistema_id');
       
        $this->db->where('(usuarios.borrado="no")');
        
        if($this->rol_id !=4)
            $this->db->where('(roles.mostrar=1)');
        
        $i = 0;
        $str = '';
        foreach ($this->column as $item) {
            if ($_POST['search']['value']) {

                //($i === 0) ? $this->db->like($item, $_POST['search']['value']) : $this->db->or_like($item, $_POST['search']['value']);
                $str .= ($i === 0) ? " (" . $item . " LIKE '%" . $_POST['search']['value'] . "%'" : " OR " . $item . " LIKE '%" . $_POST['search']['value'] . "%' ESCAPE '!'";
            }
            $column[$i] = $item;
            $i++;
        }
        if ($_POST['search']['value'])
            $this->db->where($str . ")");

        unset($column[0]);

        $column = array_values($column);

        if (isset($_POST['order'])) {
            $this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables() {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered() {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all() {
        $this->db->from($this->table);
        $this->db->where('borrado', 'no');
        return $this->db->count_all_results();
    }

    public function get_by_id($id) {
        $this->db->from($this->table);
        $this->db->where('id', $id);
        $query = $this->db->get();

        return $query->row();
    }

    function login($username, $password) {

        $this->db->select('usuarios.id, usuarios.nombre, usuarios.apellido, usuarios.usuario, usuarios.email, roles.nombre AS rol, usuarios.roles_id, roles.controller_default, usuarios.sistema_id, usuarios.file', FALSE);
        $this->db->from($this->table);
        $this->db->join('roles', 'roles.id = usuarios.roles_id');
        $this->db->where('usuarios.usuario = ' . "'" . $username . "'");
        $this->db->where('usuarios.password = ' . "'" . $password . "'");
        $this->db->where('usuarios.borrado = "no" ');
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
