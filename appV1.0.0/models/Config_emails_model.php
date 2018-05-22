<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class config_emails_model extends Default_model {

    var $table = 'config_emails';
    var $column = array('id','smtp_host','smtp_email_from','smtp_email_name','email_envio_quiebre','email_envio_pedido', 'email_contacto');
    var $order = array('id' => 'desc');

    public function __construct() {
        parent::__construct();
    }
    
    public function get_last() {
        
        $this->db->select($this->table . '.*', FALSE);

        $this->db->from($this->table);

        $this->db->where($this->table . '.borrado', 'no');

        $this->db->order_by('id', 'desc');
        
        $this->db->limit(1);
        
        $query = $this->db->get();

        return $query->row();
        
    }
}
