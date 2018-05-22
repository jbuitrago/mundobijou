<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/controllers/my_controller.php';

class Menu extends MY_Controller {

    var $model = 'menu_model';
    var $folder_view = 'menu';
    var $controller = 'menu';
    var $data_view = array();
    var $otra_accion = '<a class="btn btn-sm btn-default" href="javascript:void()" title="Agregar permisos" onclick="cargar_permisos(#IDENTIFICADOR)"><i class="glyphicon glyphicon-cog"></i></a>';

    public function __construct() {
        parent::__construct();
    }

    public function index() {

        $this->data_view['roles'] = $this->generate_data_dropdown('roles', 'nombre', FALSE);

        $this->data_view['acciones'] = $this->generate_data_dropdown('acciones', 'nombre', TRUE);

        $this->data_view['menus'] = $this->generate_data_dropdown('menu', 'nombre', FALSE);

        $this->data_view['acciones'] = $this->generate_data_dropdown('acciones', 'nombre', TRUE);

        $this->load_view();
    }

    public function ajax_edit($id) {

        $data = $this->obj_model->get_by_id($id);

        $data->grupos = $this->get_data_multiple('menu_usuario', 'idmenu', $id, 'rol_usuario');

        echo json_encode($data);
    }

    public function ajax_add() {

        $this->form_validation->set_rules('nombre', 'Nombre', 'required|xss_clean|trim');
        $this->form_validation->set_rules('add', 'Add', 'required|xss_clean|trim');
        $this->form_validation->set_rules('list', 'List', 'xss_clean|trim');
        $this->form_validation->set_rules('orden', 'Orden', 'required|xss_clean|trim');
        $this->form_validation->set_rules('icono', 'Icono', 'required|xss_clean|trim');
        $this->form_validation->set_rules('controller', 'Controller', 'required|xss_clean|trim');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array("status" => FALSE, "errores" => validation_errors()));
        } else {

            $data = array(
                'nombre' => $this->input->post('nombre'),
                'add' => $this->input->post('add'),
                'list' => $this->input->post('list'),
                'orden' => $this->input->post('orden'),
                'icono' => $this->input->post('icono'),
                'controller' => $this->input->post('controller'),
                'padre_id' => $this->input->post('padre_id'),
                    //'fecha_insert' => date('Y-m-d H:i:s')
            );
            $insert = $this->obj_model->save($data);

            if($insert){
                
                $text = trim($this->input->post('campos'));
                $textAr = explode("\n", $text);
                $textAr = array_filter($textAr, 'trim'); // remove any extra \r characters left behind

                $cant = count($textAr);
                
                for($i=0;$i<$cant;$i++){
                    
                    $textAr[$i] = trim(str_replace(array("\n", "\r"), '', $textAr[$i]));
                    
                }
                
                $this->create($this->input->post('controller'), $textAr);               
            }
            
            echo json_encode(array("status" => $insert));
        }
    }

    
    
    public function ajax_update() {

        $this->form_validation->set_rules('nombre', 'Nombre', 'required|xss_clean|trim');
        $this->form_validation->set_rules('add', 'Add', 'required|xss_clean|trim');
        $this->form_validation->set_rules('list', 'List', 'xss_clean|trim');
        $this->form_validation->set_rules('orden', 'Orden', 'required|xss_clean|trim');
        $this->form_validation->set_rules('icono', 'Icono', 'required|xss_clean|trim');
        $this->form_validation->set_rules('controller', 'Controller', 'required|xss_clean|trim');


        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array("status" => FALSE, "errores" => validation_errors()));
        } else {

            $data = array(
                'nombre' => $this->input->post('nombre'),
                'add' => $this->input->post('add'),
                'list' => $this->input->post('list'),
                'orden' => $this->input->post('orden'),
                'icono' => $this->input->post('icono'),
                'controller' => $this->input->post('controller'),
                'padre_id' => $this->input->post('padre_id'),
                    //'fecha_insert' => date('Y-m-d H:i:s')
            );
            $insert = $this->obj_model->update(array('id' => $this->input->post('id')), $data);

            echo json_encode(array("status" => $insert));
        }
    }

    public function ajax_add_permisos() {

        $this->form_validation->set_rules('rol_id', 'Rol', 'required|xss_clean|trim');
        $this->form_validation->set_rules('acciones_id[]', 'Accion', 'required|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array("status" => FALSE, "errores" => validation_errors()));
        } else {

            $this->obj_model->delete_intermedia("menu_rol_accion", "menu_id", "rol_id", $this->input->post('menu_id'), $this->input->post('rol_id'));

            $acciones = $this->input->post('acciones_id');

            $rol_id = $this->input->post('rol_id');

            $data_acciones = array();

            foreach ($acciones as $accion) {
                $data_acciones[] = array("menu_id" => $this->input->post('menu_id'), "accion_id" => $accion, "rol_id" => $rol_id);
            }

            $this->obj_model->insert_multiple("menu_rol_accion", $data_acciones);
            
            echo json_encode(array("status" => TRUE));
        }
    }

    public function create($name_controller, $fields) {

        $this->load->helper('file');

        $this->create_model($name_controller, $fields);

        $this->create_views($name_controller, $fields);

        $this->create_controller($name_controller, $fields);
    }
    
    private function create_model($name_controller, $fields) {

        $path = 'application/models/' . ucfirst($name_controller) . '_model.php';

        $data = file_get_contents('application/crud/Model.mtk');
        
        $fields_model = "'".implode("','", $fields)."'";
        
        $data = str_replace("#FIELDSMODEL", $fields_model, $data); //set campos
        
        $data = str_replace("#NAME", $name_controller, $data);//set name
        
        if (!write_file($path, $data)) {
            echo json_encode(array("status" => FALSE, "errores" => '<p style="color:red;">Unable to write the model file </p>'));
        } else {
            return TRUE;
        }
    }

    private function create_controller($name_controller, $fields) {
        
        $path = 'application/controllers/' . ucfirst($name_controller) . '.php';
       
        $fieldsinsert = '';

        $validations = '';
        
        foreach ($fields as $line) {
           
            $validations .= ' $this->form_validation->set_rules("'.$line.'", "'.  ucfirst($line).'", "required|xss_clean|trim");'."\r\n";
           
            $fieldsinsert .= '"'.$line.'" => $this->input->post("'.$line.'"),'."\r\n";;
        } 
        
        $data = file_get_contents('application/crud/Controller.mtk');

        $data = str_replace("#NAME", $name_controller, $data);//set name
        
        $data = str_replace("#VALIDATIONS", $validations, $data);//set name
        
        $data = str_replace("#FIELDS", $fieldsinsert, $data);//set name
        
        if (!write_file($path, $data)) {
            echo json_encode(array("status" => FALSE, "errores" => '<p style="color:red;">Unable to write the CONTROLLER file </p>'));
        } else {
           return TRUE;
        }
    }

    private function create_views($name_controller, $fields) {

        $path = 'application/views/' . $name_controller;

        if (!is_dir($path)) { //create the folder if it's not already exists
            mkdir($path, 0777, TRUE);
        }
        
        $fieldstable = '';
        
        $jsfields = '';
        
        $formfiels = '';
        
        foreach ($fields as $line) {
           
            $formfiels .= ' <div class="form-group">
                            <label class="control-label col-md-3">'.ucfirst($line).'</label>
                            <div class="col-md-9">
                                <input name="'.$line.'" placeholder="'.$line.'" class="form-control" type="text" required="">
                            </div>
                        </div>'."\r\n";
           
            $fieldstable .= '<th>'.ucfirst($line).'</th>'."\r\n";;
            
            $jsfields .= '$(\'[name="'.$line.'"]\').val(data.'.$line.');'."\r\n";;
        } 
        
        $data = file_get_contents('application/crud/View.mtk');

        $data = str_replace("#TABLEFIELDS", $fieldstable, $data);//set name
        
        $data = str_replace("#JSFIELDSUPDATE", $jsfields, $data);//set name
        
        $data = str_replace("#FORMFIELDS", $formfiels, $data);//set name
        

        if (!write_file($path . '/view.php', $data)) {
           echo json_encode(array("status" => FALSE, "errores" => '<p style="color:red;">Unable to write the VIEW file </p>'));
        } else {
            return TRUE;
        }
    }
}
