<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/controllers/my_controller.php';

class Front extends MY_Controller {

    var $model = 'send_model';
    
    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->load_view();
    }

    public function redirect_link($envio_id, $url, $mailing_id=0) {
       
        //$this->obj_model->set_correo_leido($mailing_id, array('click' => 1));
        
        $envio = $this->obj_model->get_envio_by_id($envio_id); //obtengo los datos del envio

        $clicks = $envio->total_clicks + 1; //actualizo la cantidad de correos enviados

        $this->obj_model->update_envio(array('total_clicks' => $clicks), $envio->id);
        
        $decoded = ($this->encrypt_decrypt('decrypt', $url));

        header("Location: " . $decoded);

    }
    
    public function view($id_encriptado){
        
        $id = ($this->encrypt_decrypt('decrypt', $id_encriptado));
        
        $envio = $this->obj_model->get_envio_by_id($id); //obtengo los datos del envio
        
        $total_vistas = $envio->total_vistas_online +1;
        
        $this->obj_model->update_envio(array('total_vistas_online' => $total_vistas), $envio->id);
        
        echo utf8_encode($envio->texto);
        
    }
    
    public function unsuscribe($suscriptor_id){
        
        $this->load->model('Suscriptores_model', 'suscriptores');

        $this->suscriptores->update($suscriptor_id, array('suscripto'=>1, 'fecha_unsuscribe'=>date('Y-m-d H:i:s')));
        
    }
    

}
