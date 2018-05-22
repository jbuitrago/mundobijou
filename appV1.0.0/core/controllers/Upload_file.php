<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Upload_file extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function upload_image() {

        $this->load->helper('upload_images');

        $res = upload_file_ajax();

        echo $res;
    }

}

?>