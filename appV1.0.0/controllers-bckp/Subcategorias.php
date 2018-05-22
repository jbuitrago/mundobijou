<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/controllers/my_controller.php';

class subcategorias extends MY_Controller {

    var $model = 'subcategorias_model';
    var $folder_view = 'subcategorias';
    var $controller = 'subcategorias';
    var $data_view = array();

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->data_view['categorias'] = $this->generate_data_dropdown('categorias', 'nombre', FALSE);
        $this->load_view();
    }
     public function ExcelDataAdd()	{ 
      
        $this->load->library('excel');//load PHPExcel library 
        $configUpload['upload_path'] = FCPATH.'uploads/excel/';
        $configUpload['allowed_types'] = 'xls|xlsx|csv';
        $configUpload['max_size'] = '5000';
        $this->load->library('upload', $configUpload);
        $this->upload->do_upload('excelfile');	
        $upload_data = $this->upload->data(); //Returns array of containing all of the data related to the file you uploaded.
        $file_name = $upload_data['file_name']; //uploded file name
        $extension=$upload_data['file_ext'];    // uploded file extension
        //$objReader =PHPExcel_IOFactory::createReader('Excel5');     //For excel 2003 
        $objReader= PHPExcel_IOFactory::createReader('Excel2007');	// For excel 2007 	  
        $objReader->setReadDataOnly(true); 		  
        $objPHPExcel=$objReader->load(FCPATH.'uploads/excel/'.$file_name);		 
        $totalrows=$objPHPExcel->setActiveSheetIndex(0)->getHighestRow();   //Count Numbe of rows avalable in excel      	 
        $objWorksheet=$objPHPExcel->setActiveSheetIndex(0);
         if(($objWorksheet->getCellByColumnAndRow(0,1)->getValue()!="CATEGORIA PADRE")
          or($objWorksheet->getCellByColumnAndRow(1,1)->getValue()!="NOMBRE")
          or($objWorksheet->getCellByColumnAndRow(2,1)->getValue()!="IMAGEN")
          or($objWorksheet->getCellByColumnAndRow(3,1)->getValue()!="DESTACADA"))
        {
            echo '<link href="'.  base_url()  .'assets/web/css/bootstrap.min.css" rel="stylesheet"><body><div class="modal-backdrop fade in" style="z-index:-1;"></div><div class="modalx fade"  role="dialog" style="display:block;opacity:1;"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h3 class="modal-title"> Error al importar desde Excel</h3></div><div class="modal-body form" style="margin-bottom:15px;">
                 <label class="control-label col-md-3" style="width:100%;">Formato de importación incorrecto!</label></div><div class="modal-footer"><button type="button" onClick="history.back()" class="btn btn-danger" data-dismiss="modal">Atras</button></div></div></div></div></div></body>';
            die;
        }
          $this->db->trans_begin();
        for($i=2;$i<=$totalrows;$i++)
        {
            $Cat_padre= $objWorksheet->getCellByColumnAndRow(0,$i)->getValue();
            $Nombre= $objWorksheet->getCellByColumnAndRow(1,$i)->getValue();
            $Imagen= $objWorksheet->getCellByColumnAndRow(2,$i)->getValue(); //Excel Column 1
            $Destacada= $objWorksheet->getCellByColumnAndRow(3,$i)->getValue(); //Excel Column 2
            if($Cat_padre!='')
            {
                $id_padre = $this->db->query("SELECT * FROM categorias WHERE nombre = '" .$Cat_padre. "' AND borrado='no'");
                $cant=$id_padre->num_rows();
                if($cant!=0)
                {
                    $id=$id_padre->row();
                    
                        $cant_value = $this->db->query("SELECT * FROM subcategorias WHERE nombre = '" .$Nombre. "' AND borrado='no' AND categoria_padre = '" .$id->id. "' ")->num_rows();
                        if ($cant_value == 0)
                       {
                           $data=array('categoria_padre'=>$id->id, 'nombre'=>$Nombre, 'foto'=>$Imagen ,'destacada'=>$Destacada);
                           $this->obj_model->save($data);
                       }
                       else 
                        {
                            // error nombre subcategoria 
                            $this->db->trans_rollback();
                            echo '<link href="'.  base_url()  .'assets/web/css/bootstrap.min.css" rel="stylesheet"><body><div class="modal-backdrop fade in" style="z-index:-1;"></div><div class="modalx fade"  role="dialog" style="display:block;opacity:1;"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h3 class="modal-title"> Error al importar desde Excel</h3></div><div class="modal-body form" style="margin-bottom:15px;">
                   <label class="control-label col-md-3" style="width:100%;">La subcategoria '.$Nombre.' ya existe!</label></div><div class="modal-footer"><button type="button" onClick="history.back()" class="btn btn-danger" data-dismiss="modal">Atras</button></div></div></div></div></div></body>';
               die;
                        }
                    
                }
                else 
                {
                    // error categoria padre 
                    $this->db->trans_rollback();
                    echo '<link href="'.  base_url()  .'assets/web/css/bootstrap.min.css" rel="stylesheet"><body><div class="modal-backdrop fade in" style="z-index:-1;"></div><div class="modalx fade"  role="dialog" style="display:block;opacity:1;"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h3 class="modal-title"> Error al importar desde Excel</h3></div><div class="modal-body form" style="margin-bottom:15px;">
                   <label class="control-label col-md-3" style="width:100%;">La categoria '.$Cat_padre.' no existe!</label></div><div class="modal-footer"><button type="button" onClick="history.back()" class="btn btn-danger" data-dismiss="modal">Atras</button></div></div></div></div></div></body>';
               die;
                }
            }
            
        }
        if ($this->db->trans_status() === FALSE)
        {
            //error al guardar
            $this->db->trans_rollback();
            echo '<link href="'.  base_url()  .'assets/web/css/bootstrap.min.css" rel="stylesheet"><body><div class="modal-backdrop fade in" style="z-index:-1;"></div><div class="modalx fade"  role="dialog" style="display:block;opacity:1;"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h3 class="modal-title"> Error al importar desde Excel</h3></div><div class="modal-body form" style="margin-bottom:15px;">
                   <label class="control-label col-md-3" style="width:100%;"> Error al guardar!</label></div><div class="modal-footer"><button type="button" onClick="history.back()" class="btn btn-danger" data-dismiss="modal">Atras</button></div></div></div></div></div></body>';
        }
        else
        {
            $this->db->trans_commit();
        }
        unlink(FCPATH.'/uploads/excel/'.$file_name); //File Deleted After uploading in database .			 
        redirect(base_url() . "subcategorias");
    }
    public function ajax_add() {

        $this->form_validation->set_rules("categoria_padre", "Categoria padre", "callback_check_select");
        $this->form_validation->set_rules("nombre", "Nombre", "required|xss_clean|trim|callback_validate");
        $this->form_validation->set_rules("file", "Imagen", "callback_check_file[file]");
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array("status" => FALSE, "errores" => validation_errors()));
        } else {
            $data = array(
                "categoria_padre" => $this->input->post("categoria_padre"),
                "nombre" => $this->input->post("nombre"),
                "destacada" => $this->input->post("destacada"),
                "foto" => $this->input->post("file")
            );
            $this->obj_model->save($data);
            echo json_encode(array("status" => TRUE));
        }
    }

    public function ajax_update() {

        $this->form_validation->set_rules("categoria_padre", "Categoria padre", "callback_check_select");

        $original_value = $this->db->query("SELECT nombre,categoria_padre FROM subcategorias WHERE id = " . $this->input->post('id') . " AND borrado='no'")->row();

        $nombre = $original_value->nombre;

        $padre = $original_value->categoria_padre;

        if ((strtoupper($this->input->post("nombre")) == strtoupper($nombre)) && ( $this->input->post("categoria_padre") == $padre)) {

            $is_unique = '';
        } else {

            $is_unique = '|callback_validate';
        }

        $this->form_validation->set_rules('nombre', 'Nombre', 'trim|required' . $is_unique);

        $this->form_validation->set_rules("file", "Imagen", "callback_check_file[file]");

        if ($this->form_validation->run() == FALSE) {

            echo json_encode(array("status" => FALSE, "errores" => validation_errors()));
        } else {
            $data = array(
                "categoria_padre" => $this->input->post("categoria_padre"),
                "nombre" => $this->input->post("nombre"),
                "foto" => $this->input->post("file"),
                "destacada" => $this->input->post("destacada"),
            );

            $res = $this->obj_model->update(array('id' => $this->input->post('id')), $data);

            echo json_encode(array("status" => $res));
        }
    }

    public function validate() {
        $original_value = $this->db->query("SELECT * FROM subcategorias WHERE nombre = '" . $this->input->post('nombre') . "' AND borrado='no'  AND categoria_padre = '" . $this->input->post('categoria_padre') . "'")->num_rows();
        if ($original_value != 0) {
            RETURN FALSE;
        } else {
            return TRUE;
        }
    }

    public function check_select($value) {
        if ($value == '0') {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function get_subcategorias($id) {

        echo json_encode($this->obj_model->get_subcategorias($id));
    }

}
