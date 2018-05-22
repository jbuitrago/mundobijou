<?php

/**
 * Description of default_model
 *
 * @author facundo
 */
class Default_model extends CI_Model {

    var $sistema_actual = '';
    var $campo_system = 'sistema_id';
    var $campo_user = 'user_id';
    var $user_id;
    var $apply_filter_user = FALSE;
    var $apply_filter_system = TRUE;
    public $table = '';
    public $column;
    public $order;

    function __construct() {
        parent::__construct();
        $this->load->database();

        $session_data = $this->session->userdata('logged_in');

        $this->sistema_actual = $session_data['sistema_actual'];

        $this->user_id = $session_data['id'];
    }

    private function _get_datatables_query() {

        $this->db->select(implode(',', $this->column));

        $this->db->from($this->table);

        $this->db->where('borrado', 'no');

        if ($this->apply_filter_system)
            $this->db->where($this->campo_system, $this->sistema_actual);

        if ($this->apply_filter_user)
            $this->db->where($this->campo_user, $this->user_id);

        $i = 0;

        foreach ($this->column as $item) {
            if ($_POST['search']['value'])
                ($i === 0) ? $this->db->like($item, $_POST['search']['value']) : $this->db->or_like($item, $_POST['search']['value']);
            $column[$i] = $item;
            $i++;
        }

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

        if ($this->apply_filter_system)
            $this->db->where($this->campo_system, $this->sistema_actual);

        if ($this->apply_filter_user)
            $this->db->where($this->campo_user, $this->user_id);

        return $this->db->count_all_results();
    }

    public function get_by_id($id) {

        $this->db->from($this->table);

        $this->db->where('id', $id);

        if ($this->apply_filter_system)
            $this->db->where($this->campo_system, $this->sistema_actual);

        if ($this->apply_filter_user)
            $this->db->where($this->campo_user, $this->user_id);

        $query = $this->db->get();

        return $query->row();
    }

    public function save($data) {

        if ($this->apply_filter_system)
            $data[$this->campo_system] = $this->sistema_actual;

        if ($this->apply_filter_user)
            $data[$this->campo_user] = $this->user_id;

        $this->db->insert($this->table, $data);

        return $this->db->insert_id();
    }

    public function update($where, $data) {

        if ($this->apply_filter_system)
            $data[$this->campo_system] = $this->sistema_actual;

        if ($this->apply_filter_user)
            $data[$this->campo_user] = $this->user_id;

        $this->db->update($this->table, $data, $where);

        return $this->db->affected_rows();
    }

    public function delete_by_id($id) {

        $data = array('borrado' => 'si', 'fecha_delete' => date('Y-m-d H:i:s'));

        $this->db->where('id', $id);

        if ($this->apply_filter_system)
            $this->db->where($this->campo_system, $this->sistema_actual);

        if ($this->apply_filter_user)
            $this->db->where($this->campo_user, $this->user_id);

        $this->db->update($this->table, $data);
    }

    public function get_data_dropdown($table, $campo, $apply_filters = TRUE) {

        $this->db->from($table);

        $this->db->where('borrado', 'no');
        
        $this->db->order_by($campo, 'asc');

        if ($apply_filters) {

            if ($this->apply_filter_system)
                $this->db->where($this->campo_system, $this->sistema_actual);

           /* if ($this->apply_filter_user)
                $this->db->where($this->campo_user, $this->user_id);*/
        }
        return $this->db->get();
    }

    public function delete_from_table($table, $field, $value) {

        $this->db->where($field, $value);

        $this->db->delete($table);
    }
    public function insert_multiple($table, $data){
        
        $this->db->insert_batch($table, $data); 
        
    }
    public function get_data_multiple($table,$campo, $id){
        
        $this->db->from($table);

        $this->db->where($campo, $id);
        
        return $this->db->get();
        
    }

}
