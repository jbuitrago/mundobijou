<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/controllers/my_controller.php';

class categorias extends MY_Controller {

    var $model = 'categorias_model';
    var $folder_view = 'categorias';
    var $controller = 'categorias';
    var $data_view = array();

    public function __construct() {
        parent::__construct();
    }

    public function index() {
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
        // comprobacion de formato
        if(($objWorksheet->getCellByColumnAndRow(0,1)->getValue()!="NOMBRE")
                or($objWorksheet->getCellByColumnAndRow(1,1)->getValue()!="IMAGEN")
                        or($objWorksheet->getCellByColumnAndRow(2,1)->getValue()!="DESTACADA"))
        {
             echo '<link href="'.  base_url()  .'assets/web/css/bootstrap.min.css" rel="stylesheet"><body><div class="modal-backdrop fade in" style="z-index:-1;"></div><div class="modalx fade"  role="dialog" style="display:block;opacity:1;"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h3 class="modal-title"> Error al importar desde Excel</h3></div><div class="modal-body form" style="margin-bottom:15px;">
                   <label class="control-label col-md-3" style="width:100%;">Formato de importación incorrecto!</label></div><div class="modal-footer"><button type="button" onClick="history.back()" class="btn btn-danger" data-dismiss="modal">Atras</button></div></div></div></div></div></body>';
               die;
        }
        
        
         $this->db->trans_begin();
        for($i=2;$i<=$totalrows;$i++)
        {
            $Nombre= $objWorksheet->getCellByColumnAndRow(0,$i)->getValue();			
            $Imagen= $objWorksheet->getCellByColumnAndRow(1,$i)->getValue(); //Excel Column 1
            $Destacada= $objWorksheet->getCellByColumnAndRow(2,$i)->getValue(); //Excel Column 2
            $cant_value = $this->db->query("SELECT * FROM categorias WHERE nombre = '" .$Nombre. "' AND borrado='no'")->num_rows();
            if (($Nombre!='')&($cant_value == 0))
            {
                $data=array('nombre'=>$Nombre, 'foto'=>$Imagen ,'destacada'=>$Destacada);
                $this->obj_model->save($data);
            }
            elseif ($cant_value != 0)
            {
              // error de duplicado
               
                $this->db->trans_rollback();
              // echo '<script type="text/javascript">alert("Data has been submitted to ' . $Nombre . '");</script>';
              echo '<link href="'.  base_url()  .'assets/web/css/bootstrap.min.css" rel="stylesheet"><body><div class="modal-backdrop fade in" style="z-index:-1;"></div><div class="modalx fade"  role="dialog" style="display:block;opacity:1;"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h3 class="modal-title"> Error al importar desde Excel</h3></div><div class="modal-body form" style="margin-bottom:15px;">
                   <label class="control-label col-md-3" style="width:100%;"> La categoria '.$Nombre.' ya existe!</label></div><div class="modal-footer"><button type="button" onClick="history.back()" class="btn btn-danger" data-dismiss="modal">Atras</button></div></div></div></div></div></body>';
               die;
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
        unlink(FCPATH.'uploads/excel/'.$file_name); //File Deleted After uploading in database .			 
        redirect(base_url() . "categorias");
    }
   
    public function ajax_add() {

        $this->form_validation->set_rules("nombre", "Nombre", "required|xss_clean|trim|callback_validate");

        $this->form_validation->set_rules("file", "Imagen", "callback_check_file[file]");

        if ($this->form_validation->run() == FALSE) {

            echo json_encode(array("status" => FALSE, "errores" => validation_errors()));
        } else {
            $data = array(
                'nombre' => $this->input->post('nombre'),
                'destacada' => $this->input->post('destacada'),
                'foto' => $this->input->post('file')
            );

            $insert = $this->obj_model->save($data);

            echo json_encode(array("status" => $insert));
        }
    }

    public function ajax_update() {

        $original_value = $this->db->query("SELECT nombre FROM categorias WHERE id = " . $this->input->post('id') . " AND borrado='no'")->row()->nombre;

        if ($this->input->post('nombre') != $original_value) {

            $is_unique = '|callback_validate';
        } else {
            $is_unique = '';
        }
        $this->form_validation->set_rules('nombre', 'Nombre', 'trim|required' . $is_unique);

        $this->form_validation->set_rules("file", "Imagen", "callback_check_file[file]");

        if ($this->form_validation->run() == FALSE) {

            echo json_encode(array("status" => FALSE, "errores" => validation_errors()));
        } else {

            $data = array(
                'nombre' => $this->input->post('nombre'),
                'destacada' => $this->input->post('destacada'));

            if ($this->input->post('file') != '') {

                $data['foto'] = $this->input->post('file');
            }
            $res = $this->obj_model->update(array('id' => $this->input->post('id')), $data);

            echo json_encode(array("status" => $res));
        }
    }

    public function validate() {

        $original_value = $this->db->query("SELECT * FROM categorias WHERE nombre = '" . $this->input->post('nombre') . "' AND borrado='no'")->num_rows();

        if ($original_value != 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

}
