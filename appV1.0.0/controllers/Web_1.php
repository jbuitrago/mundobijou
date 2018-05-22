<?php

class Web extends CI_Controller {

    var $data = array();

    function __construct() {

        parent::__construct();

        $this->load->library("pagination");

        $this->load->model("Banners_model", "banners");

        $this->load->model("Noticias_model", "noticias");

        $this->load->model("Contacto_model", "contacto");

        $this->load->helper('captcha');
    }

    public function index() {

        $data['opcion'] = "index";

        $data['title'] = "Home";

        $data['banners'] = $this->banners->get_all();

        $data['noticias'] = $this->noticias->get_all(3);

        $data['catpcha'] = $this->captcha_setting();

        $this->load->view('web/header', $data);

        $this->load->view('web/menu', $data);

        $this->load->view('web/index', $data);

        $this->load->view('web/footer');
    }

    public function noticias_interna($id = 0) {

        $m = $this->noticias->get_by_slug($id);

        if (!isset($m->id)) {

            show_404('appV1.0.0/views/web/error_page.php');
        }


        $data['noticia'] = $m;

        $data['opcion'] = "noticias_contables";

        $data['title'] = "Noticias contables";

        $this->load->view('web/header', $data);

        $this->load->view('web/menu', $data);

        $this->load->view('web/noticias_interna', $data);

        $this->load->view('web/footer');
    }

    public function noticias_contables($id = '0') {
        $view = '';
  
        if ($id != '0') {
           
            $m = $this->noticias->get_by_slug($id);

            if (!isset($m->id)) {

                show_404('appV1.0.0/views/web/error_page.php');
            }

            $data['noticia'] = $m;
            
            $view = 'noticias_interna';
            
        } else {
            
            $view = 'noticias_contables';

            $data['noticias'] = $this->noticias->get_all();
        }
        
        $data['opcion'] = "noticias_contables";

        $data['title'] = "Noticias contables";

        $this->load->view('web/header', $data);

        $this->load->view('web/menu', $data);

        $this->load->view('web/'.$view, $data);

        $this->load->view('web/footer');
    }

    public function contacto() {

        $this->load->helper('captcha');

        $data['opcion'] = "contacto";

        $data['title'] = "Contacto";

        $data['catpcha'] = $this->captcha_setting();

        $this->load->view('web/header', $data);

        $this->load->view('web/menu', $data);

        $this->load->view('web/contacto', $data);

        $this->load->view('web/footer');
    }

    public function quienes_somos() {

        $data['opcion'] = "quienes_somos";

        $data['title'] = "Quienes somos";

        $this->load->view('web/header', $data);

        $this->load->view('web/menu', $data);

        $this->load->view('web/quienes_somos');

        $this->load->view('web/footer');
    }

    /*
     * funcion que guarda y envia por mail el formulario de contacto 
     * 
     * */

    public function send_contacto() {

       $this->load->library('form_validation');

        $this->form_validation->set_error_delimiters('<p class="bg-danger text-left font-weight-bold" style="color:#fff">', '</p>');

        $this->form_validation->set_rules('nombre', 'Nombre', 'required|xss_clean|trim');

        $this->form_validation->set_rules('email', 'Email', 'required|xss_clean|trim|valid_email');

        $this->form_validation->set_rules('mensaje', 'Mensaje', 'required|xss_clean|trim');

        //$this->form_validation->set_rules('captcha', 'Captcha', 'required|xss_clean|trim');

        $this->form_validation->set_rules('telefono', 'Telefono', 'xss_clean|trim');

        $this->form_validation->set_rules('empresa', 'Empresa', 'xss_clean|trim');

        if ($this->form_validation->run() == FALSE) {

            echo validation_errors();
            die;
        } else {


            $recaptcha = $this->input->post('g-recaptcha-response');
            if (!empty($recaptcha)) {
                $response = $this->recaptcha->verifyResponse($recaptcha);
                if (isset($response['success']) and $response['success'] === true) {
                   //echo "You got it!";
                }
            }else {
                echo '<p class="bg-danger text-left font-weight-bold" style="color:#fff">El catpcha ingresado no es correcto</p>';

                die;
            }

        }


        $data = array(
            "user_agent" => $_SERVER['HTTP_USER_AGENT'],
            "subject" => $this->input->post("asunto"),
            "nombre" => $this->input->post("nombre"),
            "email" => $this->input->post("email"),
            "mensaje" => $this->input->post("mensaje"),
            "telefono" => $this->input->post("telefono"),
            "apellido" => $this->input->post("apellido"),
            "empresa" => $this->input->post("empresa"),
            "ip" => $_SERVER['REMOTE_ADDR'],
        );

        $insert_log = $this->contacto->save($data);

        $this->load->library('email');

        $config['charset'] = 'utf-8';

        $config['mailtype'] = 'html';

        $this->email->initialize($config);

        $this->email->from($this->input->post('email'), $this->input->post('nombre'));

        $this->email->to('info@estudiofleisman.com');

        $this->email->bcc('negocifacundo@hotmail.com');

        $this->email->subject('NUEVA CONSULTA DESDE LA WEB');

        $this->email->message('<html><head></head><body>
                            <u>NOMBRE: </u>' . $this->input->post('nombre') . '<br>'
                . '<u>APELLIDO: </u>' . $this->input->post('apellido') . '<br>'
                . '<u>EMPRESA: </u>' . $this->input->post('empresa') . '<br>'
                . '<u>EMAIL: </u>' . $this->input->post('email') . '<br>'
                . '<u>TELEFONO: </u>' . $this->input->post('telefono') . '<br>'
                . '<u>COMENTARIO: </u>' . nl2br($this->input->post('mensaje')) . '</body></html>');

        if (!$this->email->send(FALSE)) {

            echo "<p class='bg-danger text-white' style='color:#fff'>Ocurrio un error al enviar el mensaje, intentelo nuevamente mas tarde.</p>|1";
            exit;
        } else {
            echo "<p class='bg-success text-white' style='color:#fff'>Mensaje enviado correctamente, gracias por contactarse, pronto nos estaremos comunicando</p>|0";
            exit;
        }
    }

    public function blanqueo_de_capitales_y_moratoria_fiscal_y_previsional_2016() {

        $data['opcion'] = "servicios";

        $data['title'] = "Blanqueo de capitales y moratoria_fiscal y previsional 2016";

        $this->load->view('web/header', $data);

        $this->load->view('web/menu', $data);

        $this->load->view('web/blanqueo_de_capitales_y_moratoria_fiscal_y_previsional_2016', $data);

        $this->load->view('web/footer');
    }

    public function asesoramiento_servicios_impositivos() {

        $data['opcion'] = "servicios";

        $data['title'] = "Asesoramiento servicios impositivos";

        $this->load->view('web/header', $data);

        $this->load->view('web/menu', $data);

        $this->load->view('web/asesoramiento_servicios_impositivos', $data);

        $this->load->view('web/footer');
    }

    public function auditoria_contable() {

        $data['opcion'] = "servicios";

        $data['title'] = "Auditoria contable";

        $this->load->view('web/header', $data);

        $this->load->view('web/menu', $data);

        $this->load->view('web/auditoria_contable', $data);

        $this->load->view('web/footer');
    }

    public function asesoramiento_servicios_societarios() {

        $data['opcion'] = "servicios";

        $data['title'] = "Asesoramiento servicios societarios";

        $this->load->view('web/header', $data);

        $this->load->view('web/menu', $data);

        $this->load->view('web/asesoramiento_servicios_societarios', $data);

        $this->load->view('web/footer');
    }

    public function asesoramiento_servicios_contables() {

        $data['opcion'] = "servicios";

        $data['title'] = "Asesoramiento servicios contables";

        $this->load->view('web/header', $data);

        $this->load->view('web/menu', $data);

        $this->load->view('web/asesoramiento_servicios_contables', $data);

        $this->load->view('web/footer');
    }

    public function asesoramiento_servicios_laborales_previsionales() {

        $data['opcion'] = "servicios";

        $data['title'] = "Asesoramiento servicios laborales previsionales";

        $this->load->view('web/header', $data);

        $this->load->view('web/menu', $data);

        $this->load->view('web/asesoramiento_servicios_laborales_previsionales', $data);

        $this->load->view('web/footer');
    }

    public function servicios_contables() {

        $data['opcion'] = "servicios";

        $data['title'] = "Servicios contables";

        $this->load->view('web/header', $data);

        $this->load->view('web/menu', $data);

        $this->load->view('web/servicios_contables', $data);

        $this->load->view('web/footer');
    }

    public function busqueda_y_seleccion_de_personal() {

        $data['opcion'] = "servicios";

        $data['title'] = "Busqueda y seleccion de personal";

        $this->load->view('web/header', $data);

        $this->load->view('web/menu', $data);

        $this->load->view('web/busqueda_y_seleccion_de_personal', $data);

        $this->load->view('web/footer');
    }

    public function captcha_setting() {
        $this->load->library('recaptcha');

        $data = array(
            'widget' => $this->recaptcha->getWidget(),
            'script' => $this->recaptcha->getScriptTag(array('hl' => 'es-AR')),
        );

        return $data;
    }

}

?>
