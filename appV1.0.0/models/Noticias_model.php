<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class noticias_model extends Default_model {

    var $table = 'noticias';
    var $column = array('id', 'titulo', 'SUBSTRING(descripcion,1,20) AS descripcion');
    var $order = array('id' => 'desc');

    public function __construct() {
        parent::__construct();
    }

    public function insert_image($data) {

        $this->table = "noticias_images";

        return parent::save($data);
    }

    public function get_images($id) {

        $this->db->select('noticias_images.id, noticias_images.file');

        $this->db->from('noticias_images');

        $this->db->join('noticias', 'noticias.id = noticias_images.noticia_id');

        $this->db->where('noticias.borrado', 'no');

        $this->db->where('noticias_images.noticia_id', $id);

        $query = $this->db->get();

        return $query->result();
    }

    public function delete_image($id) {

        $this->db->where('id', $id);
        return $this->db->delete('noticias_images');
    }

    public function get_all($limit='') {

        $this->db->select($this->table . '.*');

        $this->db->from($this->table);

        $this->db->where($this->table . '.borrado', 'no');

        $this->db->order_by($this->table . '.fecha_insert', 'desc');
        
        if($limit!=''){
            $this->db->limit($limit);
        }

        $query = $this->db->get();

        return $query->result();
    }

    public function get_images_front($noticia_id) {

        $this->db->select('noticias_images.file');

        $this->db->from('noticias_images');

        $this->db->where('noticias_images.noticia_id', $noticia_id);

        $query = $this->db->get();

        return $query->result();
    }
	
	public function get_by_slug($id) {

        $this->db->from($this->table);

        $this->db->where('slug', $id);

        if ($this->apply_filter_system)
            $this->db->where($this->campo_system, $this->sistema_actual);

        if ($this->apply_filter_user)
            $this->db->where($this->campo_user, $this->user_id);

        $query = $this->db->get();

        return $query->row();
    }

}
