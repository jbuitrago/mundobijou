<?php

class Web extends CI_Controller {

    var $data = array();

    function __construct() {

        parent::__construct();

        $this->load->library("pagination");

        $this->load->model("Banners_model", "banners");
        $this->load->model("Header_model", "header");
 
        $this->load->model("Producto_model", "producto");

        $this->load->model("Contacto_model", "contacto");

        $this->load->helper('captcha');
    }

    public function index() {

        $data['opcion'] = "index";

        $data['title'] = "Home";
        
        //$menu = $this->Menu->get_all();
        $data['categorias'] = $this->header->get_all_categorias();
        $data['subcategorias'] = $this->header->get_all_subcategorias();
       // $data['menu'] = $this->dynamic_menu->build_menu('1');
       // $data['banners'] = $this->banners->get_all();

        //$data['noticias'] = $this->noticias->get_all(3);
        $data['banner'] = $this->banners->get_banners();
        $data_index['slider_categorias'] = $this->load->view('web/slider_categorias', $data, true);

        $data_index['slider_home'] = $this->load->view('web/slider_home', $data, true);
        
        $data_final['header'] = $this->load->view('web/header', $data ,true);
        
        $data_index['last_prod'] = $this->producto->last_prod();
        $data_index['ofertas'] = $this->producto->get_ofertas();
        $data_final['section'] = $this->load->view('web/index', $data_index, true);

        $data_final['modal_login'] = $this->load->view('web/modal_login', $data, true);

        $data_final['modal_vistarapida'] = $this->load->view('web/modal_vistarapida', $data, true);

        $data_final['footer'] = $this->load->view('web/footer', $data, true);

        $this->load->view('web/index_marco', $data_final);
    }

    public function productos() {

        $data['opcion'] = "index";

        $data['title'] = "Productos";
 $data['categorias'] = $this->header->get_all_categorias();
        $data['subcategorias'] = $this->header->get_all_subcategorias();
        //$data['banners'] = $this->banners->get_all();

        $data['productos'] = $this->producto->get_all();
        
        $data_index['slider_categorias'] = $this->load->view('web/slider_categorias', $data, true);

        $data_index['slider_home'] = $this->load->view('web/slider_home', $data, true);
        
        $data_final['header'] = $this->load->view('web/header', $data, true);

        $data_final['section'] = $this->load->view('web/productos', $data_index, true);

        $data_final['modal_login'] = $this->load->view('web/modal_login', $data, true);

        $data_final['modal_vistarapida'] = $this->load->view('web/modal_vistarapida', $data, true);

        $data_final['footer'] = $this->load->view('web/footer', $data, true);

        $this->load->view('web/index_marco', $data_final);
    }
    
    public function registrarse() {

        $data['opcion'] = "index";

        $data['title'] = "Productos";
 $data['categorias'] = $this->header->get_all_categorias();
        $data['subcategorias'] = $this->header->get_all_subcategorias();
        //$data['banners'] = $this->banners->get_all();

        //$data['noticias'] = $this->noticias->get_all(3);
        
        $data_index['slider_categorias'] = $this->load->view('web/slider_categorias', $data, true);

        $data_index['slider_home'] = $this->load->view('web/slider_home', $data, true);
        
        $data_final['header'] = $this->load->view('web/header', $data, true);

        $data_final['section'] = $this->load->view('web/registro2', $data_index, true);

        $data_final['modal_login'] = $this->load->view('web/modal_login', $data, true);

        $data_final['modal_vistarapida'] = $this->load->view('web/modal_vistarapida', $data, true);

        $data_final['footer'] = $this->load->view('web/footer', $data, true);

        $this->load->view('web/index_marco', $data_final);
    }
    
    public function olvide_pass() {

        $data['opcion'] = "index";

        $data['title'] = "Productos";

        //$data['banners'] = $this->banners->get_all();

        //$data['noticias'] = $this->noticias->get_all(3);
        
        $data_index['slider_categorias'] = $this->load->view('web/slider_categorias', $data, true);

        $data_index['slider_home'] = $this->load->view('web/slider_home', $data, true);
        
        $data_final['header'] = $this->load->view('web/header', $data, true);

        $data_final['section'] = $this->load->view('web/olvide_pass', $data_index, true);

        $data_final['modal_login'] = $this->load->view('web/modal_login', $data, true);

        $data_final['modal_vistarapida'] = $this->load->view('web/modal_vistarapida', $data, true);

        $data_final['footer'] = $this->load->view('web/footer', $data, true);

        $this->load->view('web/index_marco', $data_final);
    }
    
    public function detalle($id='') {

        $data['opcion'] = "index";

        $data['title'] = "Productos";

        //$data['banners'] = $this->banners->get_all();

        //$data['noticias'] = $this->noticias->get_all(3);
        
        $data_index['slider_categorias'] = $this->load->view('web/slider_categorias', $data, true);

        $data_index['slider_home'] = $this->load->view('web/slider_home', $data, true);
        
        $data_final['header'] = $this->load->view('web/header', $data, true);

        $data_final['section'] = $this->load->view('web/detalle', $data_index, true);

        $data_final['modal_login'] = $this->load->view('web/modal_login', $data, true);

        $data_final['modal_vistarapida'] = $this->load->view('web/modal_vistarapida', $data, true);

        $data_final['footer'] = $this->load->view('web/footer', $data, true);

        $this->load->view('web/index_marco', $data_final);
    }
    
    public function detalle_combo() {

        $data['opcion'] = "index";

        $data['title'] = "Productos";

        //$data['banners'] = $this->banners->get_all();

        //$data['noticias'] = $this->noticias->get_all(3);
        
        $data_index['slider_categorias'] = $this->load->view('web/slider_categorias', $data, true);

        $data_index['slider_home'] = $this->load->view('web/slider_home', $data, true);
        
        $data_final['header'] = $this->load->view('web/header', $data, true);

        $data_final['section'] = $this->load->view('web/detalle_combo', $data_index, true);

        $data_final['modal_login'] = $this->load->view('web/modal_login', $data, true);

        $data_final['modal_vistarapida'] = $this->load->view('web/modal_vistarapida', $data, true);

        $data_final['footer'] = $this->load->view('web/footer', $data, true);

        $this->load->view('web/index_marco', $data_final);
    }
    
    public function carro() {

        $data['opcion'] = "index";

        $data['title'] = "Productos";

        //$data['banners'] = $this->banners->get_all();

        //$data['noticias'] = $this->noticias->get_all(3);
        
        $data_index['slider_categorias'] = $this->load->view('web/slider_categorias', $data, true);

        $data_index['slider_home'] = $this->load->view('web/slider_home', $data, true);
        
        $data_final['header'] = $this->load->view('web/header', $data, true);

        $data_final['section'] = $this->load->view('web/carro', $data_index, true);

        $data_final['modal_login'] = $this->load->view('web/modal_login', $data, true);

        $data_final['modal_vistarapida'] = $this->load->view('web/modal_vistarapida', $data, true);

        $data_final['footer'] = $this->load->view('web/footer', $data, true);

        $this->load->view('web/index_marco', $data_final);
    }
    
    public function contacto() {

        $data['opcion'] = "index";

        $data['title'] = "Productos";
         $data['categorias'] = $this->header->get_all_categorias();
        $data['subcategorias'] = $this->header->get_all_subcategorias();
        //$data['banners'] = $this->banners->get_all();

        //$data['noticias'] = $this->noticias->get_all(3);
        
        $data_index['slider_categorias'] = $this->load->view('web/slider_categorias', $data, true);

        $data_index['slider_home'] = $this->load->view('web/slider_home', $data, true);
        
        $data_final['header'] = $this->load->view('web/header', $data, true);

        $data_final['section'] = $this->load->view('web/contacto', $data_index, true);

        $data_final['modal_login'] = $this->load->view('web/modal_login', $data, true);

        $data_final['modal_vistarapida'] = $this->load->view('web/modal_vistarapida', $data, true);

        $data_final['footer'] = $this->load->view('web/footer', $data, true);

        $this->load->view('web/index_marco', $data_final);
    }
    
    public function contacto_gracias() {

        $data['opcion'] = "index";

        $data['title'] = "Productos";
$data['categorias'] = $this->header->get_all_categorias();
        $data['subcategorias'] = $this->header->get_all_subcategorias();
        //$data['banners'] = $this->banners->get_all();

        //$data['noticias'] = $this->noticias->get_all(3);
        
        $data_index['slider_categorias'] = $this->load->view('web/slider_categorias', $data, true);

        $data_index['slider_home'] = $this->load->view('web/slider_home', $data, true);
        
        $data_final['header'] = $this->load->view('web/header', $data, true);

        $data_final['section'] = $this->load->view('web/contacto_gracias', $data_index, true);

        $data_final['modal_login'] = $this->load->view('web/modal_login', $data, true);

        $data_final['modal_vistarapida'] = $this->load->view('web/modal_vistarapida', $data, true);

        $data_final['footer'] = $this->load->view('web/footer', $data, true);

        $this->load->view('web/index_marco', $data_final);
    }
    
    public function checkout1() {

        $data['opcion'] = "index";

        $data['title'] = "Productos";

        //$data['banners'] = $this->banners->get_all();

        //$data['noticias'] = $this->noticias->get_all(3);
        
        $data_index['slider_categorias'] = $this->load->view('web/slider_categorias', $data, true);

        $data_index['slider_home'] = $this->load->view('web/slider_home', $data, true);
        
        $data_final['header'] = $this->load->view('web/header', $data, true);

        $data_final['section'] = $this->load->view('web/checkout1', $data_index, true);

        $data_final['modal_login'] = $this->load->view('web/modal_login', $data, true);

        $data_final['modal_vistarapida'] = $this->load->view('web/modal_vistarapida', $data, true);

        $data_final['footer'] = $this->load->view('web/footer', $data, true);

        $this->load->view('web/index_marco', $data_final);
    }
    
    public function checkout2() {

        $data['opcion'] = "index";

        $data['title'] = "Productos";

        //$data['banners'] = $this->banners->get_all();

        //$data['noticias'] = $this->noticias->get_all(3);
        
        $data_index['slider_categorias'] = $this->load->view('web/slider_categorias', $data, true);

        $data_index['slider_home'] = $this->load->view('web/slider_home', $data, true);
        
        $data_final['header'] = $this->load->view('web/header', $data, true);

        $data_final['section'] = $this->load->view('web/checkout2', $data_index, true);

        $data_final['modal_login'] = $this->load->view('web/modal_login', $data, true);

        $data_final['modal_vistarapida'] = $this->load->view('web/modal_vistarapida', $data, true);

        $data_final['footer'] = $this->load->view('web/footer', $data, true);

        $this->load->view('web/index_marco', $data_final);
    }
    
    public function checkout3() {

        $data['opcion'] = "index";

        $data['title'] = "Productos";

        //$data['banners'] = $this->banners->get_all();

        //$data['noticias'] = $this->noticias->get_all(3);
        
        $data_index['slider_categorias'] = $this->load->view('web/slider_categorias', $data, true);

        $data_index['slider_home'] = $this->load->view('web/slider_home', $data, true);
        
        $data_final['header'] = $this->load->view('web/header', $data, true);

        $data_final['section'] = $this->load->view('web/checkout3', $data_index, true);

        $data_final['modal_login'] = $this->load->view('web/modal_login', $data, true);

        $data_final['modal_vistarapida'] = $this->load->view('web/modal_vistarapida', $data, true);

        $data_final['footer'] = $this->load->view('web/footer', $data, true);

        $this->load->view('web/index_marco', $data_final);
    }
    
    public function checkout4() {

        $data['opcion'] = "index";

        $data['title'] = "Productos";

        //$data['banners'] = $this->banners->get_all();

        //$data['noticias'] = $this->noticias->get_all(3);
        
        $data_index['slider_categorias'] = $this->load->view('web/slider_categorias', $data, true);

        $data_index['slider_home'] = $this->load->view('web/slider_home', $data, true);
        
        $data_final['header'] = $this->load->view('web/header', $data, true);

        $data_final['section'] = $this->load->view('web/checkout4', $data_index, true);

        $data_final['modal_login'] = $this->load->view('web/modal_login', $data, true);

        $data_final['modal_vistarapida'] = $this->load->view('web/modal_vistarapida', $data, true);

        $data_final['footer'] = $this->load->view('web/footer', $data, true);

        $this->load->view('web/index_marco', $data_final);
    }
    
    public function checkout5() {

        $data['opcion'] = "index";

        $data['title'] = "Productos";

        //$data['banners'] = $this->banners->get_all();

        //$data['noticias'] = $this->noticias->get_all(3);
        
        $data_index['slider_categorias'] = $this->load->view('web/slider_categorias', $data, true);

        $data_index['slider_home'] = $this->load->view('web/slider_home', $data, true);
        
        $data_final['header'] = $this->load->view('web/header', $data, true);

        $data_final['section'] = $this->load->view('web/checkout5', $data_index, true);

        $data_final['modal_login'] = $this->load->view('web/modal_login', $data, true);

        $data_final['modal_vistarapida'] = $this->load->view('web/modal_vistarapida', $data, true);

        $data_final['footer'] = $this->load->view('web/footer', $data, true);

        $this->load->view('web/index_marco', $data_final);
    }
    
    public function combos() {

        $data['opcion'] = "index";

        $data['title'] = "Productos";

        //$data['banners'] = $this->banners->get_all();

        //$data['noticias'] = $this->noticias->get_all(3);
        
        $data_index['slider_categorias'] = $this->load->view('web/slider_categorias', $data, true);

        $data_index['slider_home'] = $this->load->view('web/slider_home', $data, true);
        
        $data_final['header'] = $this->load->view('web/header', $data, true);

        $data_final['section'] = $this->load->view('web/combos', $data_index, true);

        $data_final['modal_login'] = $this->load->view('web/modal_login', $data, true);

        $data_final['modal_vistarapida'] = $this->load->view('web/modal_vistarapida', $data, true);

        $data_final['footer'] = $this->load->view('web/footer', $data, true);

        $this->load->view('web/index_marco', $data_final);
    }
    
    public function mi_cuenta() {

        $data['opcion'] = "index";

        $data['title'] = "Productos";

        //$data['banners'] = $this->banners->get_all();

        //$data['noticias'] = $this->noticias->get_all(3);
        
        $data_index['slider_categorias'] = $this->load->view('web/slider_categorias', $data, true);

        $data_index['slider_home'] = $this->load->view('web/slider_home', $data, true);
        
        $data_final['header'] = $this->load->view('web/header', $data, true);

        $data_final['section'] = $this->load->view('web/mi_cuenta', $data_index, true);

        $data_final['modal_login'] = $this->load->view('web/modal_login', $data, true);

        $data_final['modal_vistarapida'] = $this->load->view('web/modal_vistarapida', $data, true);

        $data_final['footer'] = $this->load->view('web/footer', $data, true);

        $this->load->view('web/index_marco', $data_final);
    }
    
    public function mis_pedidos() {

        $data['opcion'] = "index";

        $data['title'] = "Productos";

        //$data['banners'] = $this->banners->get_all();

        //$data['noticias'] = $this->noticias->get_all(3);
        
        $data_index['slider_categorias'] = $this->load->view('web/slider_categorias', $data, true);

        $data_index['slider_home'] = $this->load->view('web/slider_home', $data, true);
        
        $data_final['header'] = $this->load->view('web/header', $data, true);

        $data_final['section'] = $this->load->view('web/mis_pedidos', $data_index, true);

        $data_final['modal_login'] = $this->load->view('web/modal_login', $data, true);

        $data_final['modal_vistarapida'] = $this->load->view('web/modal_vistarapida', $data, true);

        $data_final['footer'] = $this->load->view('web/footer', $data, true);

        $this->load->view('web/index_marco', $data_final);
    }
    
    public function link() {

        $data['opcion'] = "index";

        $data['title'] = "Productos";

        //$data['banners'] = $this->banners->get_all();

        //$data['noticias'] = $this->noticias->get_all(3);
        
        $data_index['slider_categorias'] = $this->load->view('web/slider_categorias', $data, true);

        $data_index['slider_home'] = $this->load->view('web/slider_home', $data, true);
        
        $data_final['header'] = $this->load->view('web/header', $data, true);

        $data_final['section'] = $this->load->view('web/link', $data_index, true);

        $data_final['modal_login'] = $this->load->view('web/modal_login', $data, true);

        $data_final['modal_vistarapida'] = $this->load->view('web/modal_vistarapida', $data, true);

        $data_final['footer'] = $this->load->view('web/footer', $data, true);

        $this->load->view('web/index_marco', $data_final);
    }
    
    public function olvide_pass_gracias() {

        $data['opcion'] = "index";

        $data['title'] = "Productos";

        //$data['banners'] = $this->banners->get_all();

        //$data['noticias'] = $this->noticias->get_all(3);
        
        $data_index['slider_categorias'] = $this->load->view('web/slider_categorias', $data, true);

        $data_index['slider_home'] = $this->load->view('web/slider_home', $data, true);
        
        $data_final['header'] = $this->load->view('web/header', $data, true);

        $data_final['section'] = $this->load->view('web/olvide_pass_gracias', $data_index, true);

        $data_final['modal_login'] = $this->load->view('web/modal_login', $data, true);

        $data_final['modal_vistarapida'] = $this->load->view('web/modal_vistarapida', $data, true);

        $data_final['footer'] = $this->load->view('web/footer', $data, true);

        $this->load->view('web/index_marco', $data_final);
    }
    
    public function pedido() {

        $data['opcion'] = "index";

        $data['title'] = "Productos";

        //$data['banners'] = $this->banners->get_all();

        //$data['noticias'] = $this->noticias->get_all(3);
        
        $data_index['slider_categorias'] = $this->load->view('web/slider_categorias', $data, true);

        $data_index['slider_home'] = $this->load->view('web/slider_home', $data, true);
        
        $data_final['header'] = $this->load->view('web/header', $data, true);

        $data_final['section'] = $this->load->view('web/pedido', $data_index, true);

        $data_final['modal_login'] = $this->load->view('web/modal_login', $data, true);

        $data_final['modal_vistarapida'] = $this->load->view('web/modal_vistarapida', $data, true);

        $data_final['footer'] = $this->load->view('web/footer', $data, true);

        $this->load->view('web/index_marco', $data_final);
    }
    
    public function terminos_y_condiciones() {

        $data['opcion'] = "index";

        $data['title'] = "Productos";

        //$data['banners'] = $this->banners->get_all();

        //$data['noticias'] = $this->noticias->get_all(3);
        
        $data_index['slider_categorias'] = $this->load->view('web/slider_categorias', $data, true);

        $data_index['slider_home'] = $this->load->view('web/slider_home', $data, true);
        
        $data_final['header'] = $this->load->view('web/header', $data, true);

        $data_final['section'] = $this->load->view('web/terminos_y_condiciones', $data_index, true);

        $data_final['modal_login'] = $this->load->view('web/modal_login', $data, true);

        $data_final['modal_vistarapida'] = $this->load->view('web/modal_vistarapida', $data, true);

        $data_final['footer'] = $this->load->view('web/footer', $data, true);

        $this->load->view('web/index_marco', $data_final);
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

        $this->load->view('web/' . $view, $data);

        $this->load->view('web/footer');
    }

    public function contactoc() {

        $this->load->helper('captcha');

        $data['opcion'] = "contacto";

        $data['title'] = "Contacto";

        $data['catpcha'] = $this->captcha_setting();

        $this->load->view('web/header', $data);

        $this->load->view('web/menu', $data);

        $this->load->view('web/contacto', $data);

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
            } else {
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
