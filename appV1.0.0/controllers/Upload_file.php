<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once APPPATH . '/controllers/my_controller.php';

class Upload_file extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function upload_image() {

        $this->load->helper('upload_images');

        $res = upload_file_ajax();
           
        if ($res == 'Unsupported') 
        {
            echo 'Unsupported';
            return false;
        }
        //$this->thumbnail('uploads/' . $res);

        $nombre_img = explode('.', $res);

        $nombre = $nombre_img[0] . "_thumb";

        $nombre_completo = $nombre . '.' . $nombre_img[1];

        $ext = strrchr($res, '.');
        $name = ($ext === FALSE) ? $res : substr($res, 0, -strlen($ext));

        echo $name . '' . $ext;

    }

    public function resize_image($file) {

        parent::resize_image($file);

    }

}

?>