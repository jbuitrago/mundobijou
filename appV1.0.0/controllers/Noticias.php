<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/controllers/my_controller.php';

class noticias extends MY_Controller {

    var $model = 'noticias_model';
    var $folder_view = 'noticias';
    var $controller = 'noticias';
    var $data_view = array();

    public function __construct() {

        parent::__construct();
    }

    public function index() {
        $this->load_view();
    }

    public function ajax_add() {

        $this->form_validation->set_rules("titulo", "Titulo", "required|xss_clean|trim");
        $this->form_validation->set_rules("descripcion", "Descripcion", "required|trim");
        //$this->form_validation->set_rules("video", "Video", "xss_clean|trim");
        $this->form_validation->set_rules("file", "Image", "xss_clean|trim");

        if ($this->form_validation->run() == FALSE) {
           
            echo json_encode(array("status" => FALSE, "errores" => validation_errors()));
            
        } else {
            
            $data = array(
                "titulo" => $this->input->post("titulo"),
                "descripcion" => $this->input->post("descripcion"),
                'file' => $this->input->post('file'),
            );

            $config = array(
                'field' => 'slug',
                'title' => 'title',
                'table' => 'noticias',
                'id' => 'id',
            );
            $this->load->library('slug', $config);

            $data['slug'] = $this->slug->create_uri(array('title'=>$this->input->post("titulo")));
            
            $insert = $this->obj_model->save($data);

            echo json_encode(array("status" => $insert));
        }
    }

    public function ajax_update() {

        $this->form_validation->set_rules("titulo", "Titulo", "required|xss_clean|trim");
        $this->form_validation->set_rules("descripcion", "Descripcion", "required|trim");
        $this->form_validation->set_rules("file", "Image", "xss_clean|trim");

        if ($this->form_validation->run() == FALSE) {

            echo json_encode(array("status" => FALSE, "errores" => validation_errors()));
        } else {

            $data = array(
                "titulo" => $this->input->post("titulo"),
                "descripcion" => $this->input->post("descripcion"),
            );

            if ($this->input->post('file') != '')
                $data['file'] = $this->input->post('file');

            $config = array(
                'field' => 'slug',
                'title' => 'title',
                'table' => 'noticias',
                'id' => 'id',
            );
            $this->load->library('slug', $config);

            $data['slug'] = $this->slug->create_uri(array('title'=>$this->input->post("titulo")), $this->input->post('id'));
            
            $res = $this->obj_model->update(array('id' => $this->input->post('id')), $data);
            
            echo json_encode(array("status" => $res));
        }
    }

    public function get_images($id) {

        $images = $this->obj_model->get_images($id);

        echo json_encode($images);
    }

    public function ajax_delete_image($id) {

        $res = $this->obj_model->delete_image($id);

        echo json_encode($res);
    }

    public function upload_image() {

        $targetDir = './uploads/';

        $fileName = strtolower($_FILES['file']['name']);

        $File_Ext = substr($fileName, strrpos($fileName, '.')); //get file extention

        $Random_Number = rand(0, 9999999999); //Random number to be added to name.

        $NewFileName = $Random_Number . $File_Ext; //new file name

        $targetFile = $targetDir . $NewFileName;

        if (move_uploaded_file($_FILES['file']['tmp_name'], $targetFile)) {

            //$this->thumbnail($this->files_urlpath . '/' . $NewFileName);

            $nombre_img = explode('.', $NewFileName);

            $nombre = $nombre_img[0] . "_thumb";

            $nombre_completo = $nombre . '.' . $nombre_img[1];

            //$this->make_watermark($nombre_completo);

            /*$data = array(
                'file' => $this->files_urlpath . '/' . $nombre_completo,
                'noticia_id' => $_POST['noticia_id'],
                'fecha_insert' => date('Y-m-d H:i:s')
            );

            $insert = $this->obj_model->insert_image($data);*/
            echo $NewFileName;
        }
    }

}
