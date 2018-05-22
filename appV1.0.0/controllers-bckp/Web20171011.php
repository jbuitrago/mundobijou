<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/controllers/my_controller.php';

class Web extends MY_Controller {

    var $data = array();
    var $cliente_logueado = FALSE;
    var $cliente_id_logueado = 0;
    var $model = 'web_model';
    var $email_host;
    var $email_from = 'no-reply@c0770195.ferozo.com';
    var $email_name = 'Mundo Bijou';
    var $email_pass = 'aqAAV8KDMH';
    var $email_pedido;
    var $email_quiebre;
    var $valor_dolar = 1;
    var $campo_precio = 'precio_mayorista';
    var $campo_precio_oferta = 'precio_oferta_mayorista';

    function __construct() {

        parent::__construct();

        $this->load->library("pagination");

        $this->load->library('cart');

        $this->load->library('session');

        $this->load->model("Banners_model", "banners");

        $this->load->model("Header_model", "header");

        $this->load->model("Producto_model", "producto");

        $this->load->model("Combo_model", "combo");

        $this->load->model("Contacto_model", "contacto");

        $this->load->helper('captcha');

        $this->load->model('Cliente_model', 'cliente');

        $this->load->model('Descuento_model', 'descuento');

        $this->load->model('Pedido_model', 'pedido');

        $this->load->model('Cotizacion_model', 'cotizacion');

        $this->load->model('Config_emails_model', 'config_email');

        $this->valor_dolar = $this->cotizacion->get_valor_dolar();

        $this->set_emails();

        if ($this->session->userdata('cliente_logged_in')) {

            $session_data = $this->session->userdata('cliente_logged_in');

            $this->cliente_logueado = $session_data['cliente_email'];

            $this->cliente_id_logueado = $session_data['cliente_id'];

            if ($session_data['tipo_cliente'] == 1) {

                $this->campo_precio = 'precio_mayorista';

                $this->campo_precio_oferta = 'precio_oferta_mayorista';
            } elseif ($session_data['tipo_cliente'] == 2) {

                $this->campo_precio = 'precio_revendedor';

                $this->campo_precio_oferta = 'precio_oferta_revendedor';
            }
        }
    }

    private function set_emails() {

        $email_data = $this->config_email->get_last();

        $this->email_from = $email_data->smtp_email_from;

        $this->email_name = $email_data->smtp_email_name;

        $this->email_pass = $email_data->smtp_email_pass;

        $this->email_pedido = $email_data->email_envio_pedido;

        $this->email_quiebre = $email_data->email_envio_quiebre;

        $this->email_host = $email_data->smtp_host;
    }

    public function index() {

        $data['opcion'] = "index";

        $data['title'] = "Home";

        $data['categorias'] = $this->header->get_all_categorias();

        $data['subcategorias'] = $this->header->get_all_subcategorias();

        $data['cliente_logged'] = $this->cliente_logueado;

        $data['banner'] = $this->banners->get_banners();
        
        $data_index['tipo_cliente'] = $this->get_tipo_cliente();

        $data_index['valor_dolar'] = $this->valor_dolar;

        $data_index['campo_precio'] = $this->campo_precio;

        $data_index['campo_precio_oferta'] = $this->campo_precio_oferta;

        $data_index['slider_categorias'] = $this->load->view('web/slider_categorias', $data, true);

        $data_index['slider_home'] = $this->load->view('web/slider_home', $data, true);

        $data_final['header'] = $this->load->view('web/header', $data, true);

        $data_index['last_prod'] = $this->producto->last_prod();

        $data_index['last_combos'] = $this->combo->last_combos();

        $data_index['ofertas'] = $this->producto->get_ofertas();

        $data_index['ofertas_combos'] = $this->combo->get_ofertas_combos();

        $data_final['section'] = $this->load->view('web/index', $data_index, true);

        $data_final['modal_login'] = $this->load->view('web/modal_login', $data, true);

        $data_final['modal_vistarapida'] = $this->load->view('web/modal_vistarapida', $data, true);

        $data_final['footer'] = $this->load->view('web/footer', $data, true);

        $this->load->view('web/index_marco', $data_final);
    }

    public function productos() {

        $categoria = (!empty($this->uri->segment(2))) ? $this->uri->segment(2) : 0;

        $sub_categoria = (!empty($this->uri->segment(3))) ? $this->uri->segment(3) : 0;

        $sort = (!empty($this->uri->segment(4))) ? $this->uri->segment(4) : 'precio_mayorista';

        if ($this->uri->segment(4) == 'precio_mayorista_desc') {
            $order = 'desc';
            $sort = 'precio_mayorista';
        } else {
            $order = 'asc';
        }


        $data['opcion'] = "index";

        $data['title'] = "Productos";

        $data['categorias'] = $this->header->get_all_categorias();

        $data['subcategorias'] = $this->header->get_all_subcategorias();

        $data['cliente_logged'] = $this->cliente_logueado;

        $data_index['tipo_cliente'] = $this->get_tipo_cliente();

        $data_index['campo_precio'] = $this->campo_precio;

        $data_index['campo_precio_oferta'] = $this->campo_precio_oferta;

        $data_index['categoria'] = $categoria;

        $data_index['subcategoria'] = $sub_categoria;

        $data_index['sort'] = $sort;
        
        $data_index['order'] = $order;

        //numero de pagina actual, por defecto es la 1
        $page_number = ($this->uri->segment(6)) ? $this->uri->segment(6) : 1;

        //preparo la configuracion para la paginacion.
        $config = $this->get_config_pagination($categoria, $sub_categoria);

        $offset = ($page_number - 1) * $config['per_page'];

        $data_index['productos'] = $this->producto->get_productos($config['per_page'], $offset, $sort, $order, $categoria, $sub_categoria);

        $data_index['cantidad_productos'] = count($data_index['productos']);

        $data_index['total_productos'] = $config["total_rows"];

        /* inicializo la paginacion y creo los links */
        $this->pagination->initialize($config);

        $this->pagination->cur_page = $page_number;

        $data_index["links"] = $this->pagination->create_links();

        $data_index['page'] = $offset;

        $data_index['per_page'] = $config['per_page'];

        $data_index['valor_dolar'] = $this->valor_dolar;

        $data_index['slider_categorias'] = $this->load->view('web/slider_categorias', $data, true);

        $data_index['slider_home'] = $this->load->view('web/slider_home', $data, true);

        $data_final['header'] = $this->load->view('web/header', $data, true);

        $data_final['section'] = $this->load->view('web/productos', $data_index, true);

        $data_final['modal_login'] = $this->load->view('web/modal_login', $data, true);

        $data_final['modal_vistarapida'] = $this->load->view('web/modal_vistarapida', $data, true);

        $data_final['footer'] = $this->load->view('web/footer', $data, true);

        $this->load->view('web/index_marco', $data_final);
    }

    public function registro() {

        if (!$this->session->userdata('cliente_logged_in')) {

            $resultado_registrar['status'] = FALSE;

            if (!empty($_POST)) {

                $resultado_registrar = $this->registrar_cliente();
            }

            if ($resultado_registrar['status']) {
                //enviar mail
                $datos['nombre'] = $this->input->post('nombre_apellido');
                $datos['email'] = $this->input->post('email');
                $datos['usuario'] = $this->input->post('usuario');

                $this->mail_registro($datos);

                $sess_array = array('cliente_id' => $resultado_registrar['id'], 'cliente_email' => $this->input->post('email'), 'cliente_usuario' => $this->input->post('usuario'), 'tipo_cliente' => $this->input->post('tipo_cliente'), 'logged_in' => TRUE);

                $this->session->set_userdata('cliente_logged_in', $sess_array);

                if (!empty($this->cart->contents())) {

                    redirect('checkout2', 'refresh');
                } else {
                    redirect('mi_cuenta', 'refresh');
                }
            } else {

                $data['opcion'] = "index";

                $data['title'] = "Productos";

                $data['categorias'] = $this->header->get_all_categorias();

                $data['subcategorias'] = $this->header->get_all_subcategorias();

                $data['condicion_iva'] = $this->generate_data_dropdown('condicion_iva', 'nombre', FALSE);

                $data['tipo_doc'] = $this->generate_data_dropdown('tipo_documentos', 'nombre', FALSE);

                $data['tipo_cliente'] = $this->generate_data_dropdown('tipo_cliente', 'nombre', FALSE);

                $data['provincias'] = $this->generate_data_dropdown('provincia', 'nombre', FALSE);

                unset($data['tipo_cliente'][0]);

                $data['cliente_logged'] = $this->cliente_logueado;

                $data_index['campo_precio'] = $this->campo_precio;

                $data_index['campo_precio_oferta'] = $this->campo_precio_oferta;

                $data_index['slider_categorias'] = $this->load->view('web/slider_categorias', $data, true);

                $data_index['slider_home'] = $this->load->view('web/slider_home', $data, true);

                $data_final['header'] = $this->load->view('web/header', $data, true);

                $data_final['section'] = $this->load->view('web/registro2', $data_index, true);

                $data_final['modal_login'] = $this->load->view('web/modal_login', $data, true);

                $data_final['modal_vistarapida'] = $this->load->view('web/modal_vistarapida', $data, true);

                $data_final['footer'] = $this->load->view('web/footer', $data, true);

                $this->load->view('web/index_marco', $data_final);
            }
        } else {
            redirect('/login_cliente/', 'refresh');
        }
    }

    public function mail_registro($datos) {
        require_once APPPATH . '/external_libs/phpmailer/PHPMailerAutoload.php';
        require_once APPPATH . '/external_libs/mailin-api/Mailin.php';

        $mail = new PHPMailer();

        //Luego tenemos que iniciar la validación por SMTP:
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true));
        //$mail->SMTPDebug  = 3;
        $mail->Host = $this->email_host; // SMTP a utilizar. Por ej. smtp.elserver.com
        $mail->Username = $this->email_from; // Correo completo a utilizar
        $mail->Password = $this->email_pass; // Contraseña
        $mail->Port = 587; // Puerto a utilizar
        //Con estas pocas líneas iniciamos una conexión con el SMTP. Lo que ahora deberíamos hacer, es configurar el mensaje a enviar, el //From, etc.
        $mail->From = $this->email_from; // Desde donde enviamos (Para mostrar)
        $mail->FromName = $this->email_name;

        //Estas dos líneas, cumplirían la función de encabezado (En mail() usado de esta forma: “From: Nombre <correo@dominio.com>”) de //correo.
        $mail->AddAddress($datos['email']); // Esta es la dirección a donde enviamos
        $mail->IsHTML(true); // El correo se envía como HTML
        $mail->Subject = "REGISTRO EN MUNDO BIJOU"; // Este es el titulo del email.

        $mail->Body = $this->load->view('email_templates/welcome', $datos, true); // Mensaje a enviar
        $mail->Send(); // Envía el correo.            
    }

    public function olvide_pass() {

        $data['opcion'] = "index";

        $data['title'] = "Productos";

        $data['categorias'] = $this->header->get_all_categorias();

        $data['subcategorias'] = $this->header->get_all_subcategorias();

        $data_index['valor_dolar'] = $this->valor_dolar;

        $data_index['campo_precio'] = $this->campo_precio;

        $data_index['campo_precio_oferta'] = $this->campo_precio_oferta;

        $data_index['slider_categorias'] = $this->load->view('web/slider_categorias', $data, true);

        $data_index['slider_home'] = $this->load->view('web/slider_home', $data, true);

        $data_final['header'] = $this->load->view('web/header', $data, true);

        $data_final['section'] = $this->load->view('web/olvide_pass', $data_index, true);

        $data_final['modal_login'] = $this->load->view('web/modal_login', $data, true);

        $data_final['modal_vistarapida'] = $this->load->view('web/modal_vistarapida', $data, true);

        $data_final['footer'] = $this->load->view('web/footer', $data, true);

        $this->load->view('web/index_marco', $data_final);
    }

    public function detalle($id = '') {
       //die('slug: '.$id);
        $producto = $this->producto->get_by_slug($id);

        if (!isset($producto->id)) {

            show_404('appV1.0.0/views/web/error_page.php');
        }

        $data_index['producto'] = $producto;

        $data_index['valor_dolar'] = $this->valor_dolar;

        $data_index['colores'] = $this->producto->web_get_colores($producto->id);

        $data_index['talles'] = $this->producto->web_get_talles($producto->id);

        $data_index['medidas'] = $this->producto->web_get_medidas($producto->id);

        $data['opcion'] = "index";

        $data['title'] = "Productos";

        $data['categorias'] = $this->header->get_all_categorias();

        $data['subcategorias'] = $this->header->get_all_subcategorias();

        $data['cliente_logged'] = $this->cliente_logueado;

        $data_index['tipo_cliente'] = $this->get_tipo_cliente();

        $data_index['campo_precio'] = $this->campo_precio;

        $data_index['campo_precio_oferta'] = $this->campo_precio_oferta;

        $data_index['slider_categorias'] = $this->load->view('web/slider_categorias', $data, true);

        $data_index['slider_home'] = $this->load->view('web/slider_home', $data, true);

        $data_final['header'] = $this->load->view('web/header', $data, true);

        $data_final['section'] = $this->load->view('web/detalle', $data_index, true);

        $data_final['modal_login'] = $this->load->view('web/modal_login', $data, true);

        $data_final['modal_vistarapida'] = $this->load->view('web/modal_vistarapida', $data, true);

        $data_final['footer'] = $this->load->view('web/footer', $data, true);

        $this->load->view('web/index_marco', $data_final);
    }

    public function modal_detalle($categoria = '', $id='') {

        $producto = $this->producto->get_by_slug($id);

        if (!isset($producto->id)) {

            show_404('appV1.0.0/views/web/error_page.php');
        }

        $data_index['producto'] = $producto;

        $data_index['colores'] = $this->producto->web_get_colores($producto->id);

        $data_index['talles'] = $this->producto->web_get_talles($producto->id);

        $data_index['medidas'] = $this->producto->web_get_medidas($producto->id);

        $data_index['valor_dolar'] = $this->valor_dolar;

        $data['opcion'] = "index";

        $data['title'] = "Productos";

        $data['categorias'] = $this->header->get_all_categorias();

        $data['subcategorias'] = $this->header->get_all_subcategorias();

        $data['cliente_logged'] = $this->cliente_logueado;

        $data_index['campo_precio'] = $this->campo_precio;

        $data_index['campo_precio_oferta'] = $this->campo_precio_oferta;

        $data_index['tipo_cliente'] = $this->get_tipo_cliente();

        $this->load->view('web/modal_producto_detalle', $data_index);

        //$this->load->view('web/index_marco', $data_final);
    }

    public function modal_detalle_combo($id = '') {

        $combo = $this->combo->get_by_slug($id);

        if (!isset($combo->id)) {

            show_404('appV1.0.0/views/web/error_page.php');
        }

        $data_index['combo'] = $combo;

        $data_index['composicion'] = $this->combo->get_items($combo->id);

        $data_index['valor_dolar'] = $this->valor_dolar;

        $data['opcion'] = "index";

        $data['title'] = "Productos";

        $data['categorias'] = $this->header->get_all_categorias();

        $data['subcategorias'] = $this->header->get_all_subcategorias();

        $data['cliente_logged'] = $this->cliente_logueado;

        $data_index['campo_precio'] = $this->campo_precio;

        $data_index['campo_precio_oferta'] = $this->campo_precio_oferta;

        $data_index['tipo_cliente'] = $this->get_tipo_cliente();

        $this->load->view('web/modal_combo_detalle', $data_index);

        //$this->load->view('web/index_marco', $data_final);
    }

    public function detalle_combo($id) {

        $combo = $this->combo->get_by_slug($id);

        if (!isset($combo->id)) {

            show_404('appV1.0.0/views/web/error_page.php');
        }

        $data_index['combo'] = $combo;

        $data_index['composicion'] = $this->combo->get_items($combo->id);

        $data['opcion'] = "index";

        $data['title'] = "Combos";

        $data['categorias'] = $this->header->get_all_categorias();

        $data['subcategorias'] = $this->header->get_all_subcategorias();

        $data['cliente_logged'] = $this->cliente_logueado;

        $data_index['campo_precio'] = $this->campo_precio;

        $data_index['campo_precio_oferta'] = $this->campo_precio_oferta;

        $data_index['tipo_cliente'] = $this->get_tipo_cliente();

        $data_index['valor_dolar'] = $this->valor_dolar;

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

        $data['cliente_logged'] = $this->cliente_logueado;

        $data['categorias'] = $this->header->get_all_categorias();

        $data['subcategorias'] = $this->header->get_all_subcategorias();

        $data_index['campo_precio'] = $this->campo_precio;

        $data_index['campo_precio_oferta'] = $this->campo_precio_oferta;

        $data_index['valor_dolar'] = $this->valor_dolar;

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

        $data['cliente_logged'] = $this->cliente_logueado;

        $data['categorias'] = $this->header->get_all_categorias();

        $data['subcategorias'] = $this->header->get_all_subcategorias();

        $data_index['valor_dolar'] = $this->valor_dolar;

        $data_index['campo_precio'] = $this->campo_precio;

        $data_index['campo_precio_oferta'] = $this->campo_precio_oferta;

        $data_index['slider_categorias'] = $this->load->view('web/slider_categorias', $data, true);

        $data_index['slider_home'] = $this->load->view('web/slider_home', $data, true);

        $data_final['header'] = $this->load->view('web/header', $data, true);

        $data_final['section'] = $this->load->view('web/contacto', $data_index, true);

        $data_final['modal_login'] = $this->load->view('web/modal_login', $data, true);

        $data_final['modal_vistarapida'] = $this->load->view('web/modal_vistarapida', $data, true);

        $data_final['footer'] = $this->load->view('web/footer', $data, true);

        $this->load->view('web/index_marco', $data_final);
    }

    public function registrate() {

        $data['opcion'] = "index";

        $data['title'] = "Productos";

        $data['cliente_logged'] = $this->cliente_logueado;

        $data['categorias'] = $this->header->get_all_categorias();

        $data['subcategorias'] = $this->header->get_all_subcategorias();

        $data_index['valor_dolar'] = $this->valor_dolar;

        $data_index['campo_precio'] = $this->campo_precio;

        $data_index['campo_precio_oferta'] = $this->campo_precio_oferta;

        $data_index['slider_home'] = $this->load->view('web/slider_home', $data, true);

        $data_final['header'] = $this->load->view('web/header', $data, true);

        $data_final['section'] = $this->load->view('web/registro', $data_index, true);

        $data_final['modal_login'] = $this->load->view('web/modal_login', $data, true);

        $data_final['modal_vistarapida'] = $this->load->view('web/modal_vistarapida', $data, true);

        $data_final['footer'] = $this->load->view('web/footer', $data, true);

        $this->load->view('web/index_marco', $data_final);
    }

    public function contacto_gracias() {

        $data['opcion'] = "index";

        $data['title'] = "Productos";

        $data['cliente_logged'] = $this->cliente_logueado;

        $data['categorias'] = $this->header->get_all_categorias();

        $data['subcategorias'] = $this->header->get_all_subcategorias();

        $data_index['valor_dolar'] = $this->valor_dolar;

        $data_index['campo_precio'] = $this->campo_precio;

        $data_index['campo_precio_oferta'] = $this->campo_precio_oferta;

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

        if (!$this->session->userdata('cliente_logged_in')) {

            $data['opcion'] = "index";

            $data['title'] = "Productos";

            $data['cliente_logged'] = $this->cliente_logueado;

            $data['categorias'] = $this->header->get_all_categorias();

            $data['subcategorias'] = $this->header->get_all_subcategorias();

            $data_index['valor_dolar'] = $this->valor_dolar;

            $data_index['campo_precio'] = $this->campo_precio;

            $data_index['campo_precio_oferta'] = $this->campo_precio_oferta;

            $data_index['slider_categorias'] = $this->load->view('web/slider_categorias', $data, true);

            $data_index['slider_home'] = $this->load->view('web/slider_home', $data, true);

            $data_final['header'] = $this->load->view('web/header', $data, true);

            $data_final['section'] = $this->load->view('web/checkout1', $data_index, true);

            $data_final['modal_login'] = $this->load->view('web/modal_login', $data, true);

            $data_final['modal_vistarapida'] = $this->load->view('web/modal_vistarapida', $data, true);

            $data_final['footer'] = $this->load->view('web/footer', $data, true);

            $this->load->view('web/index_marco', $data_final);
        } else {
            redirect('/checkout2/', 'refresh');
        }
    }

    public function checkout2() {

        if ($this->session->userdata('cliente_logged_in') && (!empty($this->cart->contents()) )) {

            if (!empty($_POST)) {
                $resultado_direccion = $this->set_address_pedido();

                if ($resultado_direccion['status']) {
                    redirect('checkout3', 'refresh');
                } else {
                    $data_index['direccion_cargada'] = TRUE;
                }
            }

            $data['opcion'] = "index";

            $data['title'] = "Productos";

            $data['cliente_logged'] = $this->cliente_logueado;

            $data['categorias'] = $this->header->get_all_categorias();

            $data['subcategorias'] = $this->header->get_all_subcategorias();

            $data_index['valor_dolar'] = $this->valor_dolar;

            $data_index['campo_precio'] = $this->campo_precio;

            $data_index['campo_precio_oferta'] = $this->campo_precio_oferta;

            // $data_index['provincias'] = $this->generate_data_dropdown('provincia', 'nombre', FALSE);
            // $data_index['localidades'] = $this->generate_data_dropdown('localidad', 'nombre', FALSE);

            $data['provincias'] = $this->generate_data_dropdown('provincia', 'nombre', FALSE);

            $data_index['cliente'] = (!empty($this->pedido->get_last_direccion($this->session->userdata('cliente_logged_in')['cliente_id']))) ? $this->pedido->get_last_direccion($this->session->userdata('cliente_logged_in')['cliente_id']) : $this->cliente->get_by_id($this->session->userdata('cliente_logged_in')['cliente_id']);

            $data_index['slider_categorias'] = $this->load->view('web/slider_categorias', $data, true);

            $data_index['slider_home'] = $this->load->view('web/slider_home', $data, true);

            $data_final['header'] = $this->load->view('web/header', $data, true);

            $data_final['section'] = $this->load->view('web/checkout2', $data_index, true);

            $data_final['modal_login'] = $this->load->view('web/modal_login', $data, true);

            $data_final['modal_vistarapida'] = $this->load->view('web/modal_vistarapida', $data, true);

            $data_final['footer'] = $this->load->view('web/footer', $data, true);

            $this->load->view('web/index_marco', $data_final);
        } elseif ($this->session->userdata('cliente_logged_in') && empty($this->cart->contents())) {
            redirect('mi_cuenta/', 'refresh');
        } else {
            redirect('checkut1/', 'refresh');
        }
    }

    public function checkout3() {

        if ($this->session->userdata('cliente_logged_in') && !empty($this->cart->contents())) {

            if (!empty($_POST)) {

                $resultado_forma = $this->set_formapago_pedido();

                if ($resultado_forma['status']) {
                    redirect('checkout4', 'refresh');
                }
            }

            $data['opcion'] = "index";

            $data['title'] = "Productos";

            $data['cliente_logged'] = $this->cliente_logueado;

            $data['categorias'] = $this->header->get_all_categorias();

            $data['subcategorias'] = $this->header->get_all_subcategorias();

            $data_index['valor_dolar'] = $this->valor_dolar;

            $data_index['campo_precio'] = $this->campo_precio;

            //  $data_index['provincias'] = $this->generate_data_dropdown('provincia', 'nombre', FALSE);
            // $data_index['localidades'] = $this->generate_data_dropdown('localidad', 'nombre', FALSE);

            $data_index['campo_precio_oferta'] = $this->campo_precio_oferta;

            $data_index['slider_categorias'] = $this->load->view('web/slider_categorias', $data, true);

            $data_index['slider_home'] = $this->load->view('web/slider_home', $data, true);

            $data_final['header'] = $this->load->view('web/header', $data, true);

            $data_final['section'] = $this->load->view('web/checkout3', $data_index, true);

            $data_final['modal_login'] = $this->load->view('web/modal_login', $data, true);

            $data_final['modal_vistarapida'] = $this->load->view('web/modal_vistarapida', $data, true);

            $data_final['footer'] = $this->load->view('web/footer', $data, true);

            $this->load->view('web/index_marco', $data_final);
        } else {
            redirect('/checkout1/', 'refresh');
        }
    }

    public function checkout4() {

        if ($this->session->userdata('cliente_logged_in') && !empty($this->cart->contents())) {

            if (!empty($_POST)) {
                $this->cart_update(FALSE);
            }

            $data['opcion'] = "index";

            $data['title'] = "Productos";

            $data['cliente_logged'] = $this->cliente_logueado;

            $data['categorias'] = $this->header->get_all_categorias();

            $data['subcategorias'] = $this->header->get_all_subcategorias();

            $data_index['valor_dolar'] = $this->valor_dolar;

            $data_index['campo_precio'] = $this->campo_precio;

            $data_index['campo_precio_oferta'] = $this->campo_precio_oferta;

            $data_index['medidas'] = $this->generate_data_dropdown('medidas', 'nombre', FALSE);

            $data_index['talles'] = $this->generate_data_dropdown('talles', 'nombre', FALSE);

            $data_index['colores'] = $this->generate_data_dropdown('colores', 'codigo', FALSE);

            // $data_index['provincias'] = $this->generate_data_dropdown('provincia', 'nombre', FALSE);
            // $data_index['localidades'] = $this->generate_data_dropdown('localidad', 'nombre', FALSE);

            $data_index['slider_categorias'] = $this->load->view('web/slider_categorias', $data, true);

            $data_index['slider_home'] = $this->load->view('web/slider_home', $data, true);

            $data_final['header'] = $this->load->view('web/header', $data, true);

            $data_final['section'] = $this->load->view('web/checkout4', $data_index, true);

            $data_final['modal_login'] = $this->load->view('web/modal_login', $data, true);

            $data_final['modal_vistarapida'] = $this->load->view('web/modal_vistarapida', $data, true);

            $data_final['footer'] = $this->load->view('web/footer', $data, true);

            $this->load->view('web/index_marco', $data_final);
        } else {
            redirect('/checkout1/', 'refresh');
        }
    }

    public function checkout5() {

        if ($this->session->userdata('cliente_logged_in') && !empty($this->cart->contents()) && !empty($this->session->userdata('direccion_pedido')) && !empty($this->session->userdata('formapago_pedido'))) {

            $pedido_grabado = $this->pedido->save_web();

            if ($pedido_grabado['status']) {

                $data_index['pedido'] = $this->pedido->get_by_id_and_cliente($pedido_grabado['id'], $this->cliente_id_logueado);

                $cliente = $this->cliente->get_by_id($this->cliente_id_logueado);

                $data_index['pedido_items'] = $this->pedido->get_items($pedido_grabado['id']); //$pedido_grabado['items'];

                $data_index['valor_dolar'] = $this->valor_dolar;

                $data_index['campo_precio'] = $this->campo_precio;

                $data_index['campo_precio_oferta'] = $this->campo_precio_oferta;

                $dx['medidas'] = $this->generate_data_dropdown('medidas', 'nombre', FALSE);

                $dx['talles'] = $this->generate_data_dropdown('talles', 'nombre', FALSE);

                $dx['colores'] = $this->generate_data_dropdown('colores', 'codigo', FALSE);

                $dx['provincias'] = $this->generate_data_dropdown('provincia', 'nombre', FALSE);

                $dx['localidades'] = $this->generate_data_dropdown('localidad', 'nombre', FALSE);

                $this->enviar_email(array('subject' => 'Se ha realizado un nuevo pedido', 'pedido' => $data_index['pedido'], 'cliente' => $cliente, 'items' => $data_index['pedido_items'], 'email_to' => $this->email_pedido, 'extra' => $dx), 'pedido_admin');

                $d['provincias'] = $this->generate_data_dropdown('provincia', 'nombre', FALSE);

                $d['localidades'] = $this->generate_data_dropdown('localidad', 'nombre', FALSE);

                $d['medidas'] = $this->generate_data_dropdown('medidas', 'nombre', FALSE);

                $d['talles'] = $this->generate_data_dropdown('talles', 'nombre', FALSE);

                $d['colores'] = $this->generate_data_dropdown('colores', 'codigo', FALSE);

                $this->enviar_email(array('subject' => 'Pedido realizado con exito', 'pedido' => $data_index['pedido'], 'cliente' => $cliente, 'items' => $data_index['pedido_items'], 'email_to' => $this->cliente_logueado, 'extra' => $d), 'pedido');

                if (!empty($pedido_grabado['productos_sin_stock'])) {
                    $this->enviar_email(array('email_to' => $this->email_quiebre, 'subject' => 'Hay productos sin stock', 'items' => $pedido_grabado['productos_sin_stock']), 'quiebre');
                }

                $this->cart_remove('all'); //elimino todos los items del carrito

                $this->session->unset_userdata('direccion_pedido');

                $this->session->unset_userdata('formapago_pedido');

                $this->session->unset_userdata('descuento_aplicado');
            } else {

                $data_index['pedido'] = FALSE;

                show_404('appV1.0.0/views/web/error_page.php');

                die;
            }

            $data['opcion'] = "index";

            $data['title'] = "Productos";

            $data['cliente_logged'] = $this->cliente_logueado;

            $data['categorias'] = $this->header->get_all_categorias();

            $data['subcategorias'] = $this->header->get_all_subcategorias();

            $data_index['valor_dolar'] = $this->valor_dolar;

            $data_index['campo_precio'] = $this->campo_precio;

            $data_index['campo_precio_oferta'] = $this->campo_precio_oferta;

            $data_index['medidas'] = $this->generate_data_dropdown('medidas', 'nombre', FALSE);

            $data_index['talles'] = $this->generate_data_dropdown('talles', 'nombre', FALSE);

            $data_index['colores'] = $this->generate_data_dropdown('colores', 'codigo', FALSE);

            //$data_index['localidades'] = $this->generate_data_dropdown('localidad', 'nombre', FALSE);

            $data_index['slider_categorias'] = $this->load->view('web/slider_categorias', $data, true);

            $data_index['slider_home'] = $this->load->view('web/slider_home', $data, true);

            $data_final['header'] = $this->load->view('web/header', $data, true);

            $data_final['section'] = $this->load->view('web/checkout5', $data_index, true);

            $data_final['modal_login'] = $this->load->view('web/modal_login', $data, true);

            $data_final['modal_vistarapida'] = $this->load->view('web/modal_vistarapida', $data, true);

            $data_final['footer'] = $this->load->view('web/footer', $data, true);

            $this->load->view('web/index_marco', $data_final);
        } else {
            redirect('/checkout1/', 'refresh');
        }
    }

    public function combos() {

        $categoria = (!empty($this->uri->segment(2))) ? $this->uri->segment(2) : 0;

        $sub_categoria = (!empty($this->uri->segment(3))) ? $this->uri->segment(3) : 0;

        $sort = (!empty($this->uri->segment(4))) ? $this->uri->segment(4) : 'precio_mayorista';

        if ($this->uri->segment(4) == 'precio_mayorista_desc') {
            $order = 'desc';
            $sort = 'precio_mayorista';
        } else {
            $order = 'asc';
        }

        $data['opcion'] = "index";

        $data['title'] = "Combos";

        $data['categorias'] = $this->header->get_all_categorias();

        $data['subcategorias'] = $this->header->get_all_subcategorias();

        $data['cliente_logged'] = $this->cliente_logueado;

        $data_index['campo_precio'] = $this->campo_precio;

        $data_index['campo_precio_oferta'] = $this->campo_precio_oferta;

        $data_index['tipo_cliente'] = $this->get_tipo_cliente();

        $data_index['categoria'] = $categoria;

        $data_index['subcategoria'] = $sub_categoria;

        $data_index['sort'] = $sort;

        $data_index['order'] = $order;

        //numero de pagina actual, por defecto es la 1
        $page_number = ($this->uri->segment(6)) ? $this->uri->segment(6) : 1;

        //preparo la configuracion para la paginacion.
        $config = $this->get_config_pagination($categoria, $sub_categoria, 'combos');

        $offset = ($page_number - 1) * $config['per_page'];

        $data_index['combos'] = $this->combo->get_combos($config['per_page'], $offset, $sort, $order, 0, 0);

        $cantidad_combos = count($data_index['combos']);

        for ($i = 0; $i < $cantidad_combos; $i++) {

            $data_index['combos'][$i]->composicion = $this->combo->get_items($data_index['combos'][$i]->id);
        }

        $data_index['cantidad_productos'] = count($data_index['combos']);

        $data_index['total_productos'] = $config["total_rows"];

        /* inicializo la paginacion y creo los links */
        $this->pagination->initialize($config);

        $this->pagination->cur_page = $page_number;

        $data_index["links"] = $this->pagination->create_links();

        $data_index['page'] = $offset;

        $data_index['per_page'] = $config['per_page'];

        $data_index['valor_dolar'] = $this->valor_dolar;

        $data_index['slider_categorias'] = $this->load->view('web/slider_categorias', $data, true);

        $data_index['slider_home'] = $this->load->view('web/slider_home', $data, true);

        $data_final['header'] = $this->load->view('web/header', $data, true);

        $data_final['section'] = $this->load->view('web/combos', $data_index, true);

        $data_final['modal_login'] = $this->load->view('web/modal_login', $data, true);

        $data_final['modal_vistarapida'] = $this->load->view('web/modal_vistarapida', $data, true);

        $data_final['footer'] = $this->load->view('web/footer', $data, true);

        $this->load->view('web/index_marco', $data_final);
    }

    public function cambiar_pass() {

        if ($this->session->userdata('cliente_logged_in')) {

            $data_index['actualizar_info'] = '';

            $data_index['actualizar_pass'] = '';

            //if (!empty($_POST['email'])) {
            //      $resultado_registrar = $this->registrar_cliente(TRUE);
            //       $data_index['actualizar_info'] = ($resultado_registrar['status'] == TRUE) ? '<p style="color:green">Datos actualizados correctamente</p>' : 'Ocurrio un error mientras al actualizar los datos';
            if (!empty($_POST['password_old'])) {

                $resultado_registrar = $this->actualizar_password();

                $data_index['actualizar_pass'] = ($resultado_registrar['status'] == TRUE) ? '<p style="color:green">Contraseña actualizada correctamente</p>' : 'Ocurrio un error al actualizar la contraseña';
            }

            $data['opcion'] = "index";

            $data['title'] = "Productos";

            $data['cliente_logged'] = $this->cliente_logueado;

            $data['categorias'] = $this->header->get_all_categorias();

            $data['subcategorias'] = $this->header->get_all_subcategorias();

            $data['condicion_iva'] = $this->generate_data_dropdown('condicion_iva', 'nombre', FALSE);

            $data['tipo_doc'] = $this->generate_data_dropdown('tipo_documentos', 'nombre', FALSE);

            $data['tipo_cliente'] = $this->generate_data_dropdown('tipo_cliente', 'nombre', FALSE);

            $data['provincias'] = $this->generate_data_dropdown('provincia', 'nombre', FALSE);

            unset($data['tipo_cliente'][0]);

            $data_index['valor_dolar'] = $this->valor_dolar;

            $data_index['campo_precio'] = $this->campo_precio;

            $data_index['campo_precio_oferta'] = $this->campo_precio_oferta;

            $data_index['cliente'] = $this->cliente->get_by_id($this->session->userdata('cliente_logged_in')['cliente_id']);

            $data_index['slider_categorias'] = $this->load->view('web/slider_categorias', $data, true);

            $data_index['slider_home'] = $this->load->view('web/slider_home', $data, true);

            $data_final['header'] = $this->load->view('web/header', $data, true);

            $data_final['section'] = $this->load->view('web/cambiar_pass', $data_index, true);

            $data_final['modal_login'] = $this->load->view('web/modal_login', $data, true);

            $data_final['modal_vistarapida'] = $this->load->view('web/modal_vistarapida', $data, true);

            $data_final['footer'] = $this->load->view('web/footer', $data, true);

            $this->load->view('web/index_marco', $data_final);
        } else {
            redirect('registro/', 'refresh');
        }
    }

    public function mi_cuenta() {

        if ($this->session->userdata('cliente_logged_in')) {

            $data_index['actualizar_info'] = '';

            $data_index['actualizar_pass'] = '';

            if (!empty($_POST['email'])) {

                $resultado_registrar = $this->registrar_cliente(TRUE);

                $data_index['actualizar_info'] = ($resultado_registrar['status'] == TRUE) ? '<p style="color:green">Datos actualizados correctamente</p>' : 'Ocurrio un error al actualizar los datos';
            }
            //  elseif (!empty($_POST['update_pass'])) {
            //   $resultado_registrar = $this->actualizar_password();
            //     $data_index['actualizar_pass'] = ($resultado_registrar['status'] == TRUE) ? '<p style="color:green">Contraseña actualizada correctamente</p>' : 'Ocurrio un error mientras al actualizar la contraseña';
            //   }

            $data['opcion'] = "index";

            $data['title'] = "Productos";

            $data['cliente_logged'] = $this->cliente_logueado;

            $data['categorias'] = $this->header->get_all_categorias();

            $data['subcategorias'] = $this->header->get_all_subcategorias();

            $data['condicion_iva'] = $this->generate_data_dropdown('condicion_iva', 'nombre', FALSE);

            $data['tipo_doc'] = $this->generate_data_dropdown('tipo_documentos', 'nombre', FALSE);

            $data['tipo_cliente'] = $this->generate_data_dropdown('tipo_cliente', 'nombre', FALSE);

            $data['provincias'] = $this->generate_data_dropdown('provincia', 'nombre', FALSE);

            unset($data['tipo_cliente'][0]);

            $data_index['valor_dolar'] = $this->valor_dolar;

            $data_index['campo_precio'] = $this->campo_precio;

            $data_index['campo_precio_oferta'] = $this->campo_precio_oferta;

            $data_index['cliente'] = $this->cliente->get_by_id($this->session->userdata('cliente_logged_in')['cliente_id']);

            $data_index['slider_categorias'] = $this->load->view('web/slider_categorias', $data, true);

            $data_index['slider_home'] = $this->load->view('web/slider_home', $data, true);

            $data_final['header'] = $this->load->view('web/header', $data, true);

            $data_final['section'] = $this->load->view('web/mi_cuenta', $data_index, true);

            $data_final['modal_login'] = $this->load->view('web/modal_login', $data, true);

            $data_final['modal_vistarapida'] = $this->load->view('web/modal_vistarapida', $data, true);

            $data_final['footer'] = $this->load->view('web/footer', $data, true);

            $this->load->view('web/index_marco', $data_final);
        } else {
            redirect('registro/', 'refresh');
        }
    }

    public function mis_pedidos() {

        if ($this->session->userdata('cliente_logged_in')) {

            $data['opcion'] = "index";

            $data['title'] = "Productos";

            $data['cliente_logged'] = $this->cliente_logueado;

            $data['categorias'] = $this->header->get_all_categorias();

            $data['subcategorias'] = $this->header->get_all_subcategorias();

            $data_index['valor_dolar'] = $this->valor_dolar;

            $data_index['campo_precio'] = $this->campo_precio;

            $data_index['campo_precio_oferta'] = $this->campo_precio_oferta;

            $data_index['pedidos'] = $this->pedido->get_pedidos_by_cliente($this->cliente_id_logueado);

            $data_index['estados_pedido'] = $this->generate_data_dropdown('estado_pedido', 'nombre', FALSE);

            $data_index['slider_categorias'] = $this->load->view('web/slider_categorias', $data, true);

            $data_index['slider_home'] = $this->load->view('web/slider_home', $data, true);

            $data_final['header'] = $this->load->view('web/header', $data, true);

            $data_final['section'] = $this->load->view('web/mis_pedidos', $data_index, true);

            $data_final['modal_login'] = $this->load->view('web/modal_login', $data, true);

            $data_final['modal_vistarapida'] = $this->load->view('web/modal_vistarapida', $data, true);

            $data_final['footer'] = $this->load->view('web/footer', $data, true);

            $this->load->view('web/index_marco', $data_final);
        } else {
            redirect('/registrarse/', 'refresh');
        }
    }

    public function link() {

        if ($this->session->userdata('cliente_logged_in')) {

            $data['opcion'] = "index";

            $data['title'] = "Productos";

            $data['cliente_logged'] = $this->cliente_logueado;

            $data['categorias'] = $this->header->get_all_categorias();

            $data['subcategorias'] = $this->header->get_all_subcategorias();

            $data_index['valor_dolar'] = $this->valor_dolar;

            $data_index['campo_precio'] = $this->campo_precio;

            $data_index['campo_precio_oferta'] = $this->campo_precio_oferta;

            $data_index['slider_categorias'] = $this->load->view('web/slider_categorias', $data, true);

            $data_index['slider_home'] = $this->load->view('web/slider_home', $data, true);

            $data_final['header'] = $this->load->view('web/header', $data, true);

            $data_final['section'] = $this->load->view('web/link', $data_index, true);

            $data_final['modal_login'] = $this->load->view('web/modal_login', $data, true);

            $data_final['modal_vistarapida'] = $this->load->view('web/modal_vistarapida', $data, true);

            $data_final['footer'] = $this->load->view('web/footer', $data, true);

            $this->load->view('web/index_marco', $data_final);
        } else {
            redirect('registrarse/', 'refresh');
        }
    }

    public function olvide_pass_gracias() {

        $data['opcion'] = "index";

        $data['title'] = "Productos";

        $data['cliente_logged'] = $this->cliente_logueado;

        $data['categorias'] = $this->header->get_all_categorias();

        $data['subcategorias'] = $this->header->get_all_subcategorias();

        $data_index['valor_dolar'] = $this->valor_dolar;

        $data_index['campo_precio'] = $this->campo_precio;

        $data_index['campo_precio_oferta'] = $this->campo_precio_oferta;

        $data_index['slider_categorias'] = $this->load->view('web/slider_categorias', $data, true);

        $data_index['slider_home'] = $this->load->view('web/slider_home', $data, true);

        $data_final['header'] = $this->load->view('web/header', $data, true);

        $data_final['section'] = $this->load->view('web/olvide_pass_gracias', $data_index, true);

        $data_final['modal_login'] = $this->load->view('web/modal_login', $data, true);

        $data_final['modal_vistarapida'] = $this->load->view('web/modal_vistarapida', $data, true);

        $data_final['footer'] = $this->load->view('web/footer', $data, true);

        $this->load->view('web/index_marco', $data_final);
    }

    public function pedido($pedido_id = 0) {

        if (!empty($_POST)) {

            $this->update_pedido();
        }

        $pedido = $this->pedido->get_by_id_and_cliente($pedido_id, $this->cliente_id_logueado);

        $items = (array) $this->pedido->get_items($pedido_id);

        if (!isset($pedido)) {

            show_404('appV1.0.0/views/web/error_page.php');
        }

        $data['opcion'] = "index";

        $data['title'] = "Productos";

        $data['cliente_logged'] = $this->cliente_logueado;

        $data['categorias'] = $this->header->get_all_categorias();

        $data['subcategorias'] = $this->header->get_all_subcategorias();

        $data_index['valor_dolar'] = $this->valor_dolar;

        $data_index['campo_precio'] = $this->campo_precio;

        $data_index['campo_precio_oferta'] = $this->campo_precio_oferta;

        $data_index['medidas'] = $this->generate_data_dropdown('medidas', 'nombre', FALSE);

        $data_index['talles'] = $this->generate_data_dropdown('talles', 'nombre', FALSE);

        $data_index['colores'] = $this->generate_data_dropdown('colores', 'codigo', FALSE);

        $data_index['estados_pedido'] = $this->generate_data_dropdown('estado_pedido', 'nombre', FALSE);

        $data_index['provincias'] = $this->generate_data_dropdown('provincia', 'nombre', FALSE);

        $data_index['localidades'] = $this->generate_data_dropdown('localidad', 'nombre', FALSE);

        $data_index['pedido'] = $pedido;

        $data_index['items'] = $items;

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

        $data['cliente_logged'] = $this->cliente_logueado;

        $data['categorias'] = $this->header->get_all_categorias();

        $data['subcategorias'] = $this->header->get_all_subcategorias();

        $data_index['valor_dolar'] = $this->valor_dolar;

        $data_index['campo_precio'] = $this->campo_precio;

        $data_index['campo_precio_oferta'] = $this->campo_precio_oferta;

        $data_index['slider_categorias'] = $this->load->view('web/slider_categorias', $data, true);

        $data_index['slider_home'] = $this->load->view('web/slider_home', $data, true);

        $data_final['header'] = $this->load->view('web/header', $data, true);

        $data_final['section'] = $this->load->view('web/terminos_y_condiciones', $data_index, true);

        $data_final['modal_login'] = $this->load->view('web/modal_login', $data, true);

        $data_final['modal_vistarapida'] = $this->load->view('web/modal_vistarapida', $data, true);

        $data_final['footer'] = $this->load->view('web/footer', $data, true);

        $this->load->view('web/index_marco', $data_final);
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

    public function captcha_setting() {
        
        $this->load->library('recaptcha');

        $data = array(
            'widget' => $this->recaptcha->getWidget(),
            'script' => $this->recaptcha->getScriptTag(array('hl' => 'es-AR')),
        );

        return $data;
    }

    public function cart_add() {

        $this->form_validation->set_rules('quantity', 'Cantidad', 'required|xss_clean|trim|integer|greater_than[0]');

        $this->form_validation->set_rules('id', 'Producto', 'required|xss_clean|trim');

        $data_index['colores'] = $this->producto->web_get_colores($this->input->post('id'));

        $data_index['talles'] = $this->producto->web_get_talles($this->input->post('id'));

        $data_index['medidas'] = $this->producto->web_get_medidas($this->input->post('id'));

        if (count($data_index['talles']) > 0)
            $this->form_validation->set_rules('size', 'Talle', 'required|xss_clean|trim');

        if (count($data_index['colores']) > 0)
            $this->form_validation->set_rules('color_id', 'Color', 'required|xss_clean|trim');

        if (count($data_index['medidas']) > 0)
            $this->form_validation->set_rules('medida_id', 'Medida', 'required|xss_clean|trim');

        if ($this->form_validation->run() == FALSE) {

            echo json_encode(array('status' => FALSE, "errores" => validation_errors()));
        } else {

            /* Set array for send data. */
            $data = array('id' => $this->input->post('id'),
                'qty' => $this->input->post('quantity'),
                'price' => $this->input->post('price'),
                'name' => addslashes($this->input->post('name')),
                'options' => array('imagen' => $this->input->post('imagen'),
                    'idproducto' => $this->input->post('id'),
                    'table' => $this->input->post('table'),
                    'subtotal' => $this->input->post('quantity') * $this->input->post('price'),
                    'slug' => $this->input->post('slug'),
                    'my_url' => $this->input->post('my_url'),
                    'talle_id' => $this->input->post('size'),
                    'color_id' => $this->input->post('color_id'),
                    'medida_id' => $this->input->post('medida_id'),
                    'tipo_producto' => $this->input->post('tipo_producto'),
                    'cantidad_a_descontar' => $this->input->post('cantidad_a_descontar')));

            /* This function add items into cart. */
            $id = $this->cart->insert($data);

            /* This will show insert data in cart. */
            //redirect('carro');
            echo json_encode(array('status' => TRUE, "errores" => ''));
        }
    }

    function cart_remove($rowid) {
        /* Check rowid value. */
        if ($rowid === "all") {
            /* Destroy data which store in session. */
            $this->cart->destroy();
        } else {
            /* Destroy selected rowid in session. */
            $data = array(
                'rowid' => $rowid,
                'qty' => 0
            );
            /* Update cart data, after cancel. */
            $this->cart->update($data);
            //  }

            /* This will show cancel data in cart. */
            redirect('carro');
        }
    }

    public function cart_update($redirect = TRUE) {

        /* Recieve post values,calcute them and update */
        $cart_info = $_POST['cart'];

        foreach ($cart_info as $id => $cart) {

            $rowid = $cart['rowid'];

            $price = $cart['price'];

            $amount = $price * $cart['qty'];

            $qty = $cart['qty'];

            $data = array(
                'rowid' => $rowid,
                'price' => $price,
                'amount' => $amount,
                'qty' => $qty
            );

            $this->cart->update($data);
        }

        $descuento = $this->descuento->get_by_codigo($this->input->post('descuento'));

        if ($descuento) {

            $newdata = array(
                'codigo_descuento' => $this->input->post('descuento'),
                'descuento_id' => $descuento->id,
                'porcentaje' => $descuento->porcentaje
            );

            $this->session->set_userdata('descuento_aplicado', $newdata);
        }
        if ($redirect) {
            redirect('carro');
        } else {
            return true;
        }
    }

    function process_login() {
        //This method will have the credentials validation
        $this->load->library('form_validation');

        $this->form_validation->set_rules('usuario', 'Usuario', 'trim|required|xss_clean');

        $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|sha1|callback_check_database');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array("status" => FALSE, "errores" => validation_errors()));
        } else {
            echo json_encode(array("status" => TRUE, "errores" => ''));
        }
    }

    function check_user_email() {

        $this->load->library('form_validation');

        $this->form_validation->set_rules('usuario_email', 'usuario_email', 'trim|required|callback_check_user_email_database');

        if ($this->form_validation->run() == FALSE) {

            echo json_encode(array("status" => FALSE, "errores" => "Usuario o Email incorrectos"));
        } else {

            $usuario = $this->cliente->id_user($this->input->post('usuario_email'));

            $pass = $this->generateRandomString();

            $data = array("password" => sha1($pass));

            $insert = $this->cliente->update(array('id' => $usuario->id), $data);
            if ($insert) {

                require_once APPPATH . '/external_libs/phpmailer/PHPMailerAutoload.php';

                require_once APPPATH . '/external_libs/mailin-api/Mailin.php';

                $mail = new PHPMailer();

                $mail->IsSMTP();

                $mail->SMTPAuth = true;

                $mail->SMTPOptions = array(
                    'ssl' => array('verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => true));

                $mail->Host = $this->email_host; // SMTP a utilizar. Por ej. smtp.elserver.com

                $mail->Username = $this->email_from; // Correo completo a utilizar

                $mail->Password = $this->email_pass; // Contraseña

                $mail->Port = 587; // Puerto a utilizar

                $mail->From = $this->email_from; // Desde donde enviamos (Para mostrar)

                $mail->FromName = $this->email_name;

                $datos = array();

                $datos['nombre'] = $usuario->nombre_apellido;

                $datos['contraseña'] = $pass;

                $mail->AddAddress($usuario->email); // Esta es la dirección a donde enviamos

                $mail->IsHTML(true); // El correo se envía como HTML

                $mail->Subject = "CAMBIO DE CONTRASE&Ntilde;A"; // Este es el titulo del email.

                $mail->Body = $this->load->view('email_templates/olvide_pass', $datos, true); // Mensaje a enviar

                $exito = $mail->Send(); // Envía el correo.
                //
                //También podríamos agregar simples verificaciones para saber si se envió:
                if ($exito) {

                    echo json_encode(array("status" => true));
                } else {

                    echo json_encode(array("status" => false, "errores" => $exito));
                }
            } else {

                echo json_encode(array("status" => FALSE, "errores" => 'Error al actualizar la contraseña'));
            }
        }
    }

    function generateRandomString($length = 10) {
        return substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
    }

    function check_user_email_database($value) {
        $result = $this->cliente->olvide_password($value);
        return $result;
        die;
    }

    function check_database($password) {

        //Field validation succeeded.  Validate against database
        $email = $this->input->post('usuario');

        //query the database
        $result = $this->cliente->login($email, $password);

        if ($result) {

            $sess_array = array();

            foreach ($result as $row) {

                $sess_array = array('cliente_id' => $row->id, 'cliente_email' => $row->email, 'cliente_usuario' => $row->usuario, 'tipo_cliente' => $row->tipo_cliente);

                $this->session->set_userdata('cliente_logged_in', $sess_array);
            }
            return TRUE;
        } else {

            $this->form_validation->set_message('check_database', 'Usuario o password incorrectos');

            return FALSE;
        }
    }

    private function get_config_pagination($categoria, $sub_categoria, $page = 'productos') {

        $config = array();

        $config["base_url"] = base_url() . $page . $this->contruct_uri();

        if ($page == 'productos') {
            $config["total_rows"] = $this->producto->record_count($categoria, $sub_categoria);
        } else {
            $config["total_rows"] = $this->combo->record_count($categoria, $sub_categoria);
        }

        $config["per_page"] = (!empty($this->uri->segment(5))) ? $this->uri->segment(5) : '12';

        $config['uri_segment'] = 6;

        $choice = $config["total_rows"] / $config["per_page"];

        $config["num_links"] = round($choice);

        $config['use_page_numbers'] = TRUE;

        $config['num_tag_open'] = '<li>';

        $config['num_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="active"><a href="#">';

        $config['cur_tag_close'] = '</a></li>';

        $config['next_link'] = '&raquo;';

        $config['next_tag_open'] = '<li>';

        $config['next_tag_close'] = '</li>';

        $config['prev_link'] = '&laquo;';

        $config['prev_tag_open'] = '<li>';

        $config['prev_tag_close'] = '</li>';

        return $config;
    }

    private function contruct_uri() {

        $categoria = (!empty($this->uri->segment(2))) ? $this->uri->segment(2) : 0;

        $sub_categoria = (!empty($this->uri->segment(3))) ? $this->uri->segment(3) : 0;

        $sort = (!empty($this->uri->segment(4))) ? $this->uri->segment(4) : 'titulo';

        $page = (!empty($this->uri->segment(6))) ? $this->uri->segment(6) : '1';

        $per_page = (!empty($this->uri->segment(5))) ? $this->uri->segment(5) : '12';

        return '/' . $categoria . '/' . $sub_categoria . '/' . $sort . '/' . $per_page;
    }

    public function get_tipo_cliente() {

        $session_data = $this->session->userdata('cliente_logged_in');

        if (!empty($session_data['tipo_cliente'])) {

            return $session_data['tipo_cliente'];
        } else {
            return '';
        }
    }

    public function registrar_cliente($actualizar = FALSE) {

        if ($actualizar) {

            $original_value = $this->db->query("SELECT usuario FROM cliente WHERE id = " . $this->input->post('id') . " AND borrado='no'")->row()->usuario;

            if ($this->input->post('usuario') != $original_value) {

                $is_unique = '|is_unique[cliente.usuario]';
            } else {

                $is_unique = '';
            }

            $original_value_email = $this->db->query("SELECT email FROM cliente WHERE id = " . $this->input->post('id') . " AND borrado='no'")->row()->email;

            if ($this->input->post('email') != $original_value_email) {

                $is_unique_e = '|is_unique[cliente.email]';
            } else {

                $is_unique_e = '';
            }
        } else {

            $is_unique = '|is_unique[cliente.usuario]';

            $is_unique_e = '|is_unique[cliente.email]';
        }

        $this->form_validation->set_rules("nombre_apellido", "Nombre y apellido", "required|xss_clean|trim");
        //$this->form_validation->set_rules("tipo_doc", "Tipo doc.", "required|xss_clean|trim");
        $this->form_validation->set_rules("numero_doc", "Numero doc.", "required|xss_clean|trim");
        $this->form_validation->set_rules("usuario", "Usuario", "required|xss_clean|trim" . $is_unique);
        $this->form_validation->set_message('is_unique', 'El %s ya se encuentra registrado');
        if (!$actualizar)
            $this->form_validation->set_rules("password", "Password", "required|xss_clean|trim");
        $this->form_validation->set_rules("email", "Email", "required|xss_clean|trim" . $is_unique_e);
        $this->form_validation->set_rules("codigo_de_area", "Codigo de area", "xss_clean|trim");
        $this->form_validation->set_rules("telefono", "Telefono", "required|xss_clean|trim");
        $this->form_validation->set_rules("calle", "Calle", "xss_clean|trim");
        $this->form_validation->set_rules("altura", "Altura", "xss_clean|trim");
        $this->form_validation->set_rules("piso", "Piso", "xss_clean|trim");
        $this->form_validation->set_rules("dpto", "Dpto", "xss_clean|trim");
        $this->form_validation->set_rules("barrio", "Barrio", "xss_clean|trim");
        $this->form_validation->set_rules("manzana", "Manzana", "xss_clean|trim");
        $this->form_validation->set_rules("lote", "Lote", "xss_clean|trim");
        $this->form_validation->set_rules("codigo_postal", "CP", "xss_clean|trim");
        $this->form_validation->set_rules("provincia", "Provincia", "xss_clean|trim");
        $this->form_validation->set_rules("localidad", "Localidad", "xss_clean|trim");
        $this->form_validation->set_rules("tipo_iva", "Condicion IVA", "xss_clean|trim");
        $this->form_validation->set_rules("tipo_cliente", "Tipo cuenta", "required|xss_clean|trim");
        if (!$actualizar)
            $this->form_validation->set_rules("terminos", "Terminos", "required|xss_clean|trim");

        if ($this->form_validation->run() == FALSE) {

            return (array("status" => FALSE, "errores" => validation_errors()));
        } else {

            $data = array(
                "nombre_apellido" => $this->input->post("nombre_apellido"),
                "tipo_doc" => $this->input->post("tipo_doc"),
                "numero_doc" => $this->input->post("numero_doc"),
                "usuario" => $this->input->post("usuario"),
                "email" => $this->input->post("email"),
                "codigo_de_area" => $this->input->post("codigo_de_area"),
                "telefono" => $this->input->post("telefono"),
                "calle" => $this->input->post("calle"),
                "altura" => $this->input->post("altura"),
                "piso" => $this->input->post("piso"),
                "dpto" => $this->input->post("dpto"),
                "barrio" => $this->input->post("barrio"),
                "manzana" => $this->input->post("manzana"),
                "lote" => $this->input->post("lote"),
                "codigo_postal" => $this->input->post("codigo_postal"),
                "provincia" => $this->input->post("provincia"),
                "localidad" => $this->input->post("localidad"),
                "tipo_iva" => $this->input->post("tipo_iva"),
                "tipo_cliente" => $this->input->post("tipo_cliente"),
                    // "fecha_update" => date('Y-m-d H:i:s')
            );

            if ($actualizar) {

                if (!empty($this->input->post("password"))) {

                    $data["password"] = sha1($this->input->post("password"));
                }

                $data["fecha_update"] = date('Y-m-d H:i:s');



                $insert = $this->cliente->update(array('id' => $this->input->post('id')), $data);
            } else {

                $data["password"] = sha1($this->input->post("password"));

                $data["fecha_insert"] = date('Y-m-d H:i:s');

                $insert = $this->cliente->save($data);
            }
            if ($insert) {

                return (array("status" => TRUE, "id" => $insert));
            } else {
                return (array("status" => FALSE, "errores" => 'Ocurrio un error al registrar. Contacte al administrador'));
            }
        }
    }

    public function actualizar_password() {

        $this->form_validation->set_rules('password_old', 'Contraseña actual', 'trim|required|callback_validate_valor_original');

        $this->form_validation->set_message('validate_valor_original', 'La contraseña actual no es correcta');

        $this->form_validation->set_rules("password_1", "Nueva contraseña", "required|xss_clean|trim");

        $this->form_validation->set_rules("password_2", "Repetir constraseña", "required|xss_clean|trim|callback_validate_equal_pass");

        $this->form_validation->set_message('validate_equal_pass', 'Las contraseñas ingresadas no coinciden');

        if ($this->form_validation->run() == FALSE) {
            return (array("status" => FALSE, "errores" => validation_errors()));
        } else {

            $data = array(
                "password" => sha1($this->input->post("password_1")));

            $insert = $this->cliente->update(array('id' => $this->input->post('id')), $data);

            if ($insert) {
                return array("status" => TRUE, "errores" => '');
            } else {
                return array("status" => FALSE, "errores" => 'Error al actualizar la contraseña');
            }
        }
    }

    public function get_localidades($id) {

        echo json_encode($this->cliente->get_localidades($id));
        //echo $this->db->last_query();
    }

    public function validate_equal_pass() {

        if ($this->input->post('password_1') != $this->input->post('password_2')) {

            return FALSE;
        }
        return TRUE;
    }

    public function validate_valor_original($param) {

        $original_value = $this->db->query("SELECT password FROM cliente WHERE id = " . $this->input->post('id') . " AND borrado='no'")->row()->password;

        if (sha1($this->input->post('password_old')) != $original_value) {

            return FALSE;
        }
        return TRUE;
    }

    public function logout() {

        // Destruir todas las variables de sesión.
        $_SESSION = array();

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]
            );
        }

        session_destroy();

        redirect('index', 'refresh');
    }

    public function send() {

        $this->form_validation->set_rules("firstname", "Nombre", "required|xss_clean|trim");

        $this->form_validation->set_rules("lastname", "Apellido", "required|xss_clean|trim");

        $this->form_validation->set_rules("email", "Email", "required|xss_clean|valid_email|trim");

        $this->form_validation->set_rules("codarea", "Codigo de area", "required|numeric");

        $this->form_validation->set_rules("tel", "Telefono", "required|numeric");

        $this->form_validation->set_rules("message", "Consulta", "required|xss_clean|trim");

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array("status" => FALSE, "errores" => validation_errors()));
        } else {

            $data = array(
                "nombre" => $this->input->post("firstname"),
                "apellido" => $this->input->post("lastname"),
                "email" => $this->input->post("email"),
                "mensaje" => $this->input->post("message"),
                "telefono" => $this->input->post("tel"),
                "codarea" => $this->input->post("codarea")
            );

            $insert_log = $this->contacto->save($data);

            require_once APPPATH . '/external_libs/phpmailer/PHPMailerAutoload.php';

            require_once APPPATH . '/external_libs/mailin-api/Mailin.php';

            $mail = new PHPMailer();

            //Luego tenemos que iniciar la validación por SMTP:
            $mail->IsSMTP();
            $mail->SMTPAuth = true;
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true));
            //$mail->SMTPDebug  = 3;
            $mail->Host = $this->email_host; // SMTP a utilizar. Por ej. smtp.elserver.com
            $mail->Username = $this->email_from; // Correo completo a utilizar
            $mail->Password = $this->email_pass; // Contraseña
            $mail->Port = 587; // Puerto a utilizar
            //Con estas pocas líneas iniciamos una conexión con el SMTP. Lo que ahora deberíamos hacer, es configurar el mensaje a enviar, el //From, etc.
            $mail->From = $this->email_from; // Desde donde enviamos (Para mostrar)
            $mail->FromName = $this->email_name;

            //Estas dos líneas, cumplirían la función de encabezado (En mail() usado de esta forma: “From: Nombre <correo@dominio.com>”) de //correo.
            $mail->AddAddress('contacto@mundobijou.com.ar'); // Esta es la dirección a donde enviamos
            $mail->IsHTML(true); // El correo se envía como HTML
            $mail->Subject = "NUEVA CONSULTA"; // Este es el titulo del email.

            $mail->Body = $this->load->view('email_templates/consulta', $data, true); // Mensaje a enviar
            $exito = $mail->Send(); // Envía el correo.
            //También podríamos agregar simples verificaciones para saber si se envió:
            if ($exito) {
                echo json_encode(array("status" => true));
            } else {
                echo json_encode(array("status" => false, "errores" => $exito));
            }
        }
    }

    public function validate_cart_producto() {

        $cart_check = $this->cart->contents();

        foreach ($cart_check as $item) {
            if ($item['id'] == $this->input->post('id')) {
                return FALSE;
            }
        }
        return TRUE;
    }

    public function set_address_pedido() {

        $this->form_validation->set_rules("calle", "Calle", "required|xss_clean|trim");
        $this->form_validation->set_rules("altura", "Altura", "required|xss_clean|trim");
        $this->form_validation->set_rules("piso", "Piso", "xss_clean|trim");
        $this->form_validation->set_rules("dpto", "Dpto", "xss_clean|trim");
        $this->form_validation->set_rules("barrio", "Barrio", "xss_clean|trim");
        $this->form_validation->set_rules("manzana", "Manzana", "xss_clean|trim");
        $this->form_validation->set_rules("lote", "Lote", "xss_clean|trim");
        $this->form_validation->set_rules("codigo_postal", "CP", "required|xss_clean|trim");
        $this->form_validation->set_rules("provincia", "Provincia", "required|xss_clean|trim");
        $this->form_validation->set_rules("localidad", "Localidad", "required|xss_clean|trim");

        $this->form_validation->set_error_delimiters('<p style="padding: 0;margin: 0;float: left;">', '</p>');

        if ($this->form_validation->run() == FALSE) {
            return (array("status" => FALSE, "errores" => validation_errors()));
        } else {
            $data = array(
                "calle" => $this->input->post("calle"),
                "altura" => $this->input->post("altura"),
                "piso" => $this->input->post("piso"),
                "dpto" => $this->input->post("dpto"),
                "barrio" => $this->input->post("barrio"),
                "manzana" => $this->input->post("manzana"),
                "lote" => $this->input->post("lote"),
                "codigo_postal" => $this->input->post("codigo_postal"),
                "provincia" => $this->input->post("provincia"),
                "localidad" => $this->input->post("localidad"),
                "expreso" => $this->input->post("expreso"),
                "direccion_transporte" => $this->input->post("direccion_transporte"),
                "comentario" => $this->input->post("comentario"),
            );

            $this->session->set_userdata('direccion_pedido', $data);

            return array('status' => TRUE, 'errores');
        }
    }

    public function set_address_edit_pedido() {

        $this->form_validation->set_rules("calle", "Calle", "required|xss_clean|trim");
        $this->form_validation->set_rules("altura", "Altura", "required|xss_clean|trim");
        $this->form_validation->set_rules("piso", "Piso", "xss_clean|trim");
        $this->form_validation->set_rules("dpto", "Dpto", "xss_clean|trim");
        $this->form_validation->set_rules("barrio", "Barrio", "xss_clean|trim");
        $this->form_validation->set_rules("manzana", "Manzana", "xss_clean|trim");
        $this->form_validation->set_rules("lote", "Lote", "xss_clean|trim");
        $this->form_validation->set_rules("codigo_postal", "CP", "required|xss_clean|trim");
        $this->form_validation->set_rules("provincia", "Provincia", "required|xss_clean|trim");
        $this->form_validation->set_rules("localidad", "Localidad", "required|xss_clean|trim");

        if ($this->form_validation->run() == FALSE) {
            return (array("status" => FALSE, "errores" => validation_errors()));
        } else {
            $data = array(
                "calle" => $this->input->post("calle"),
                "altura" => $this->input->post("altura"),
                "piso" => $this->input->post("piso"),
                "dpto" => $this->input->post("dpto"),
                "barrio" => $this->input->post("barrio"),
                "manzana" => $this->input->post("manzana"),
                "lote" => $this->input->post("lote"),
                "codigo_postal" => $this->input->post("codigo_postal"),
                "provincia" => $this->input->post("provincia"),
                "localidad" => $this->input->post("localidad"),
                "expreso" => $this->input->post("expreso"),
                "direccion_transporte" => $this->input->post("direccion_transporte"),
                "comentario" => $this->input->post("comentario"),
            );

            return array('status' => TRUE, 'data' => $data);
        }
    }

    public function set_formapago_pedido() {

        $this->form_validation->set_rules("payment", "Forma de pago", "required|xss_clean|trim");

        if ($this->form_validation->run() == FALSE) {
            return (array("status" => FALSE, "errores" => validation_errors()));
        } else {
            $data = array(
                "payment" => $this->input->post("payment"),
            );

            $this->session->set_userdata('formapago_pedido', $data);

            return array('status' => TRUE, 'errores');
        }
    }

    private function enviar_email($data = array(), $template) {

        require_once APPPATH . '/external_libs/phpmailer/PHPMailerAutoload.php';

        require_once APPPATH . '/external_libs/mailin-api/Mailin.php';
        //require("class.phpmailer.php");
        $mail = new PHPMailer();

//Luego tenemos que iniciar la validación por SMTP:
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true));

        $mail->Host = $this->email_host; // SMTP a utilizar. Por ej. smtp.elserver.com
        $mail->Username = $this->email_from; // Correo completo a utilizar
        $mail->Password = $this->email_pass; // Contraseña
        $mail->Port = 587; // Puerto a utilizar
//Con estas pocas líneas iniciamos una conexión con el SMTP. Lo que ahora deberíamos hacer, es configurar el mensaje a enviar, el //From, etc.
        $mail->From = $this->email_from; // Desde donde enviamos (Para mostrar)
        $mail->FromName = $this->email_name;

//Estas dos líneas, cumplirían la función de encabezado (En mail() usado de esta forma: “From: Nombre <correo@dominio.com>”) de //correo.
        $mail->AddAddress($data['email_to']); // Esta es la dirección a donde enviamos
        $mail->IsHTML(true); // El correo se envía como HTML
        $mail->Subject = $data['subject']; // Este es el titulo del email.

        $mail->Body = $this->load->view('email_templates/' . $template, $data, true); // Mensaje a enviar
        $exito = $mail->Send(); // Envía el correo.
//También podríamos agregar simples verificaciones para saber si se envió:
        if ($exito) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function test_mail() {

        $this->enviar_email(array('email_to' => 'negocifacundo@hotmail.com', 'subject' => 'test'), 'olvide_pass');
    }

    public function update_pedido() {

        $pedido_id = $_POST['pedido_id'];

        $porcentaje = $_POST['porcentaje'];

        $total = 0;

        $items_sin_stock = array();

        $pedido = array();

        foreach ($_POST['cart'] as $p) {

            if (isset($p['delete']) && $p['delete'] == 1) {

                $a_descontar = $p['cantidad'] * $p['cantidad_a_descontar'];

                $total = $total - ($p['cantidad'] * $p['price']);
            } else {

                $a_descontar = 0;

                $total = $total + ($p['cantidad'] * $p['price']);

                $item = array('cantidad' => $p['cantidad'], 'subtotal' => $p['cantidad'] * $p['price']);

                $this->db->where('id', $p['id']);

                $this->db->update('pedido_items', $item);
                
                $item['imagen'] = $p['imagen'];

                $item['talle'] = $p['talle_id'];
                
                $item['color'] = $p['color_id'];
                
                $item['medida'] = $p['medida_id'];
                
                $item['titulo'] = $p['name'];
                
                $a_descontar = ($p['qty'] - $p['cantidad']) * $p['cantidad_a_descontar'];

                if ($a_descontar < 0) {

                    $this->db->set('stock', 'stock-' . $a_descontar * -1, FALSE);
                } else {

                    $this->db->set('stock', 'stock+' . $a_descontar, FALSE);
                }
            }
            if (isset($p['delete']) && $p['delete'] == 1) {

                $this->db->where('id', $p['id']);

                $this->db->delete('pedido_items');

                $this->db->set('stock', 'stock+' . $a_descontar, FALSE);
            }

            $this->db->where('id', $p['producto_id']);

            $this->db->update($p['tipo_producto']);

            if (!$this->pedido->hay_stockb($p['tipo_producto'], $p['producto_id'])) {
                $items_sin_stock[] = $item;
            }
        }

        $pedido['subtotal'] = $total;

        $pedido['subtotal'] = $total;

        $this->db->set('subtotal', $total);

        $this->db->set('fecha_update', date('Y-m-d H:i:s'));

        $this->db->set('total', $total - (($total * $porcentaje) / 100), FALSE);

        $this->db->where('id', $pedido_id);

        $this->db->update('pedido');

        if (count($items_sin_stock) > 0) {
            $this->enviar_email(array('email_to' => $this->email_quiebre, 'subject' => 'Hay productos sin stock', 'items' => $items_sin_stock), 'quiebre');
        }
    }

    public function busqueda() {

        $categoria = (!empty($this->uri->segment(2))) ? $this->uri->segment(2) : 0;

        $sub_categoria = (!empty($this->uri->segment(3))) ? $this->uri->segment(3) : 0;

        $sort = (!empty($this->uri->segment(4))) ? $this->uri->segment(4) : 'precio_mayorista';

        if ($this->uri->segment(4) == 'precio_mayorista_desc') {
            $order = 'desc';
            $sort = 'precio_mayorista';
        } else {
            $order = 'asc';
        }


        $data['opcion'] = "index";

        $data['title'] = "Productos";

        $data['categorias'] = $this->header->get_all_categorias();

        $data['subcategorias'] = $this->header->get_all_subcategorias();

        $data['cliente_logged'] = $this->cliente_logueado;

        $data_index['tipo_cliente'] = $this->get_tipo_cliente();

        $data_index['campo_precio'] = $this->campo_precio;

        $data_index['campo_precio_oferta'] = $this->campo_precio_oferta;

        $data_index['categoria'] = $categoria;

        $data_index['subcategoria'] = $sub_categoria;

        $data_index['sort'] = $sort;

        $data_index['order'] = $order;

        //numero de pagina actual, por defecto es la 1
        $page_number = ($this->uri->segment(6)) ? $this->uri->segment(6) : 1;

        //preparo la configuracion para la paginacion.
        $config = $this->get_config_pagination($categoria, $sub_categoria);

        $offset = ($page_number - 1) * $config['per_page'];

        $data_index['productos'] = $this->producto->get_productos(10000, $offset, $sort, $order, $categoria, $sub_categoria);

        $data_index['combos'] = $this->combo->get_combos(10000, $offset, $sort, $order, 0, 0);

        $cantidad_combos = count($data_index['combos']);

        for ($i = 0; $i < $cantidad_combos; $i++) {

            $data_index['combos'][$i]->composicion = $this->combo->get_items($data_index['combos'][$i]->id);
        }

        $data_index['cantidad_productos'] = count($data_index['productos']) + $cantidad_combos;

        //$data_index['total_productos'] = $config["total_rows"];


        /* inicializo la paginacion y creo los links */
        $this->pagination->initialize($config);

        $this->pagination->cur_page = $page_number;

        $data_index["links"] = $this->pagination->create_links();

        $data_index['productos'] = array_merge($data_index['productos'], $data_index['combos']);

        $data_index['page'] = $offset;

        $data_index['per_page'] = $config['per_page'];

        $data_index['valor_dolar'] = $this->valor_dolar;

        $data_index['termino'] = $_POST['search_ter'];

        $data_index['slider_categorias'] = $this->load->view('web/slider_categorias', $data, true);

        $data_index['slider_home'] = $this->load->view('web/slider_home', $data, true);

        $data_final['header'] = $this->load->view('web/header', $data, true);

        $data_final['section'] = $this->load->view('web/busqueda', $data_index, true);

        $data_final['modal_login'] = $this->load->view('web/modal_login', $data, true);

        $data_final['modal_vistarapida'] = $this->load->view('web/modal_vistarapida', $data, true);

        $data_final['footer'] = $this->load->view('web/footer', $data, true);

        $this->load->view('web/index_marco', $data_final);
    }

    public function blogs() {
        die('entro bien');
    }
    
}

?>
