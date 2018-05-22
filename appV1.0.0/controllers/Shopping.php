<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Shopping extends CI_Controller {

    public function __construct() {
        parent::__construct();
//Load Library and model.
        $this->load->library('cart');
        $this->load->model('billing_model');
    }

    public function index() {
//Get all data from database
        $data['products'] = $this->billing_model->get_all();
//send all product data to "shopping_view", which fetch from database.
        $this->load->view('shopping_view', $data);
    }

  
}
?>

