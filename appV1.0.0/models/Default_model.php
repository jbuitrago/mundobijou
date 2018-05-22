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
    var $apply_filter_system = FALSE;
    var $filtro_por_rol = null;
    var $cantidad_a_sumar_para_order_by = 2;
    public $table = '';
    public $column;
    public $order;

    function __construct() {

        parent::__construct();

        $this->load->database();

        $session_data = $this->session->userdata('logged_in');

        $this->sistema_actual = $session_data['sistema_actual'];

        $this->user_id = $session_data['id'];
        
        $this->rol_id = $session_data['rol'];
    }

    public function query_list_get_select() {

        $this->db->select(implode(',', $this->column));

        $this->db->from($this->table);

        $this->db->where($this->table . '.borrado', 'no');
    }

    public function query_list_apply_filters() {

        if ($this->apply_filter_system)
            $this->db->where($this->table . '.' . $this->campo_system, $this->sistema_actual);

        if ($this->apply_filter_user)
            $this->db->where($this->table . '.' . $this->campo_user, $this->user_id);

        if ($this->filtro_por_rol != null)
            $this->db->where($this->table . '.' . $this->filtro_por_rol[0], $this->filtro_por_rol[1]);
    }

    public function query_list_apply_filter_search_and_orderby() {

        $i = 0;

        $str = '';

        foreach ($this->column as $item) {
            if (isset($_POST['search']['value']) && $_POST['search']['value']) {

                $str .= ($i === 0) ? " (" . explode('AS', $item)[0] . " LIKE '%" . $_POST['search']['value'] . "%'" : " OR " . explode('AS', $item)[0] . " LIKE '%" . $_POST['search']['value'] . "%' ESCAPE '!'";
            }
            $column[$i] = $item;
            $i++;
        }
        if (isset($_POST['search']['value']) && $_POST['search']['value']) {
            $this->db->where($str . ")");
        }
        $column = array_values($column);

        unset($column[0]);

        if (isset($_POST['order'])) {

            $this->db->order_by($_POST['order']['0']['column'] + $this->cantidad_a_sumar_para_order_by, $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {

            $order = $this->order;

            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function _get_datatables_query() {

        $this->query_list_get_select();

        $this->query_list_apply_filters();

        $this->query_list_apply_filter_search_and_orderby();
    }

    function get_datatables() {

        $this->_get_datatables_query();

        if (isset($_POST['length']) && $_POST['length'] != -1) {

            $this->db->limit($_POST['length'], $_POST['start']);
        }

        $query = $this->db->get();

        //echo $this->db->last_query();

        return $query->result();
    }

    function count_filtered() {

        $this->_get_datatables_query();

        $query = $this->db->get();

        return $query->num_rows();
    }

    public function count_all() {

        $this->db->from($this->table);

        $this->db->where($this->table . '.borrado', 'no');

        if ($this->apply_filter_system)
            $this->db->where($this->table . '.' . $this->campo_system, $this->sistema_actual);

        if ($this->apply_filter_user)
            $this->db->where($this->table . '.' . $this->campo_user, $this->user_id);

        return $this->db->count_all_results();
    }

    public function get_by_id($id) {

        $this->db->from($this->table);

        $this->db->where('id', $id);

        if ($this->apply_filter_system)
            $this->db->where($this->table . '.' . $this->campo_system, $this->sistema_actual);

        if ($this->apply_filter_user)
            $this->db->where($this->table . '.' . $this->campo_user, $this->user_id);

        $query = $this->db->get();

        return $query->row();
    }

    public function save($data, $extra = '', $extra2 = '') {

        $data = $this->add_fields_core($data);

        $this->db->insert($this->table, $data);

        $id_insert = $this->db->insert_id();

        $this->log(array("tabla" => $this->table, "accion" => "INSERT", "id_registro" => $id_insert));

        return $id_insert;
    }

    public function add_fields_core($data) {
        if ($this->apply_filter_system)
            if (!isset($data[$this->campo_system]))
                $data[$this->campo_system] = $this->sistema_actual;

        // if ($this->apply_filter_user)
        $data[$this->campo_user] = $this->user_id;

        return $data;
    }

    public function update($where, $data, $extra = '') {

        if ($this->apply_filter_system)
            if (!isset($data[$this->campo_system]))
                $data[$this->campo_system] = $this->sistema_actual;

        //if ($this->apply_filter_user)
        $data[$this->campo_user] = $this->user_id;

        $this->db->update($this->table, $data, $where);

        $this->log(array("tabla" => $this->table, "accion" => "UPDATE", "id_registro" => $where["id"], "detalle"=> json_encode($data)));

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

        $this->log(array("tabla" => $this->table, "accion" => "DELETE", "id_registro" => $id));

        return $this->db->affected_rows();
    }

    public function get_data_dropdown($table, $campo, $apply_filters = TRUE, $filter_filed, $filter_value) {

        $this->db->from($table);

        $this->db->where('borrado', 'no');

        if (!empty($filter_filed) && (!empty($filter_value)))
            $this->db->where($filter_filed, $filter_value);

        $this->db->order_by($campo, 'asc');

        if ($apply_filters) {

            if ($this->apply_filter_system)
                $this->db->where($this->campo_system, $this->sistema_actual);

            /* if ($this->apply_filter_user)
              $this->db->where($this->campo_user, $this->user_id); */
        }
        
        return $this->db->get();
    }

    public function delete_from_table($table, $field, $value) {

        $this->db->where($field, $value);

        $this->db->delete($table);

        $this->log(array("tabla" => $table, "accion" => "DELETE", "id_registro" => $value));
    }

    public function insert_multiple($table, $data) {

        $this->log(array("tabla" => $table, "accion" => "INSERT", "id_registro" => 0));

        return $this->db->insert_batch($table, $data);
    }

    public function get_data_multiple($table, $campo, $id) {

        $this->db->from($table);

        $this->db->where($campo, $id);

        return $this->db->get();
    }

    public function log($data = '') {

        /* $data["tabla"]
         * $data["id"]
         * $data["accion"]==> INSERT, UPDATE, DELETE, LOGIN, LOGOUT
         *          */
        if (!isset($data["usuario"]) && (empty($data["usuario"])))
            $data["usuario"] = $this->user_id;

        $data["fecha_insert"] = date("Y-m-d H:i:s");

        $data['ip'] = $this->input->ip_address();

        $data['browser'] = $this->input->user_agent();

        $this->db->insert("logs", $data);
    }

    public function get_all() {

        $this->db->select($this->table . '.*');

        $this->db->from($this->table);

        $this->db->where($this->table . '.borrado', 'no');

        $this->db->order_by($this->table . '.id', 'desc');

        $query = $this->db->get();

        return $query->result();
    }

}
