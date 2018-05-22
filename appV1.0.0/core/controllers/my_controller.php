<?php

class MY_Controller extends CI_Controller {

    var $model = '';
    var $folder_view = '';
    var $data_view = array();
    var $campos_excluidos = array('id', '');
    var $controller = '';
    var $mostrar_add = TRUE;
    var $mostrar_delete = TRUE;
    var $otra_accion = '';
    var $default_view = 'view';
    var $files_urlpath;
    var $files_urlpath_s;

    function MY_Controller() {
        parent::__construct();

        $this->load->model($this->model, 'obj_model');
        if (isset($_SESSION['logged_in']))
            $this->files_urlpath = 'uploads/' . sha1($_SESSION['logged_in']['username']);
        if (isset($_SESSION['logged_in']))
            $this->files_urlpath_s = base_url() . 'uploads/' . sha1($_SESSION['logged_in']['username']) . '/';
    }

    public function ajax_list() {

        $list = $this->obj_model->get_datatables();

        $data = array();
        $no = $_POST['start'];

        foreach ($list as $obj) {
            $acciones = '';
            $no++;
            $row = array();

            foreach ($obj as $key => $value) { //itero sobre las properties del objeto
                if ($key != 'id')
                    $row[] = $value;
            }
            
            if (isset($this->permisos_rol[$this->controller])) {

                if (in_array("M", $this->permisos_rol[$this->controller])) {

                    if ($this->mostrar_add)
                        $acciones .='<a class="btn btn-sm btn-primary" href="javascript:void()" title="Editar" onclick="edit_obj(' . "'" . $obj->id . "'" . ')"><i class="glyphicon glyphicon-pencil"></i></a>';
                }

                if (in_array("B", $this->permisos_rol[$this->controller])) {
                    if ($this->mostrar_delete)
                        $acciones .='<a class="btn btn-sm btn-danger" href="javascript:void()" title="Eliminar" onclick="delete_obj(' . "'" . $obj->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i></a>';
                }
                if (in_array("O", $this->permisos_rol[$this->controller])) {
                    $acciones .= str_replace("#IDENTIFICADOR", $obj->id, $this->otra_accion);
                }
            }

            $row[] = $acciones;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->obj_model->count_all(),
            "recordsFiltered" => $this->obj_model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function ajax_edit($id) {
        $data = $this->obj_model->get_by_id($id);
        echo json_encode($data);
    }

    public function ajax_delete($id) {
        $this->obj_model->delete_by_id($id);
        echo json_encode(array("status" => TRUE));
    }

    public function load_view() {

        $this->data_view['controller'] = $this->controller;

        $this->data_view['otros_acciones'] = $this->get_otras_acciones();

        $msg['output'] = $this->load->view($this->folder_view . '/' . $this->default_view, $this->data_view, true);

        $msg['url_images'] = $this->files_urlpath_s;

        $this->load->view('dashboard', $msg);
    }

    public function get_otras_acciones() { 
       
        $acciones = '';
        
        if (isset($this->permisos_rol[$this->controller])) {

            if (in_array("A", $this->permisos_rol[$this->controller])) {

                $acciones .= '<button class="btn btn-success" onclick="add_obj()"><i class="glyphicon glyphicon-plus"></i>AGREGAR ' . strtoupper($this->controller) . '</button>';
            }

            if (in_array("E", $this->permisos_rol[$this->controller])) {

                $acciones .='<button class="btn btn-danger" onclick="document.location =\'' . site_url($this->controller . "/descargar_excel") . '\'"><i class="glyphicon glyphicon-download"></i>EXPORTAR ' . strtoupper($this->controller) . '</button>';
            }
        }
        return $acciones;
    }

    public function descargar_excel() {

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $this->controller . '_' . date("Y-m-d") . '.xls"');
        header('Cache-Control: max-age=0');

        $_POST['start'] = 0;

        $_POST['length'] = 5000000;

        $_POST['search']['value'] = '';

        $list = $this->obj_model->get_datatables();

        $table = '<table style=""><tr style="background:#5193d6; text-align:center;font-size:14px;font-family:Arial;font-weight:bold;color:#fff;">';

        $data = '';

        $i0 = 0;

        foreach ($list as $obj) {

            $data .='<tr style="">';

            foreach ($obj as $key => $value) {

                if (($i0 == 0) && (($key != 'id')))
                    $table .='<td>' . strtoupper($key) . '</td>';

                if ($key != 'id') {

                    $data .= '<td style="vertical-align:middle;	border:1px solid #000000;border-width:0px 1px 1px 0px;text-align:left;padding:7px;font-size:13px;font-family:Arial;font-weight:normal;color:#000000;">' . $value . '</td>';
                }
            }
            $i0 = 1;
            $table .='</tr>';
            $data .= '</tr>';
        }
        $table .=$data . '</table>';
        echo $table;
    }

    function generate_data_dropdown($table, $campo, $apply_filters = TRUE, $filter_filed = '', $filter_value = '') {

        $query = $this->obj_model->get_data_dropdown($table, $campo, $apply_filters, $filter_filed, $filter_value);

        $options = array();

        $options[] = "";

        foreach ($query->result() as $row) {
            $options[$row->id] = $row->$campo;
        }
        return $options;
    }

    function delete_from_table($table, $field, $value) {

        $this->obj_model->delete_from_table($table, $field, $value);
    }

    public function get_data_multiple($table, $campo, $id, $campo2) {

        $query = $this->obj_model->get_data_multiple($table, $campo, $id);

        $string = '';

        foreach ($query->result() as $row) {
            $string .= $row->$campo2 . ',';
        }
        return $string;
    }

    function check_default_combox($post_string) {
        return $post_string == '0' ? FALSE : TRUE;
    }

    function encrypt_decrypt($action, $string) {
        $output = false;

        $encrypt_method = "AES-256-CBC";
        $secret_key = 'This is my secret key';
        $secret_iv = 'This is my secret iv';

        // hash
        $key = hash('sha256', $secret_key);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        if ($action == 'encrypt') {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } else if ($action == 'decrypt') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }

        return $output;
    }
    
}
