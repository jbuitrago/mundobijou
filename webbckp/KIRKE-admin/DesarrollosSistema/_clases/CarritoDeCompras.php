<?php

class CarritoDeCompras {

    // session de compra
    private $_productos;
    // insercion tabla ok venta
    private $_confirmacion_tabla;
    private $_confirmacion_id_registro;
    private $_confirmacion_campo;
    // datos de pago
    private $_metodo_pago;
    private $_id_cliente;
    private $_codigo_de_seguridad;
    private $_id_operacion;
    private $_producto_nombre;
    private $_cantidad = 1;
    private $_tipo_de_moneda = 1;
    private $_precio;
    private $_otros_costos = 0;
    private $_id_del_item;
    private $_pagina_pago_exito;
    private $_pagina_pago_fracaso;
    private $_medios_de_pagos;
    private $_referencia;
    private $_url_imagen;
    private $_comprador_nombre;
    private $_comprador_apellido;
    private $_comprador_email;
    private $_cantidad_de_cuotas = 1;
    private $imprimir_form = false;

    function __construct($nombre) {
        $this->_productos = $nombre;
    }

    public function agregarModificarProducto($id, $cantidad = null, $precio = 0, $nombre = '', $tabla = '') {

        $key_producto = $this->_encontrarProducto($id, $tabla);

        if ($key_producto !== false) {

            if ($cantidad !== null) {
                $_SESSION['kk_sistema'][$this->_productos][$key_producto]['cantidad'] = $cantidad;
            }
            if ($precio != 0) {
                $_SESSION['kk_sistema'][$this->_productos][$key_producto]['precio'] = $precio;
            }
            if ($nombre != '') {
                $_SESSION['kk_sistema'][$this->_productos][$key_producto]['nombre'] = $nombre;
            }
            if ($tabla != '') {
                $_SESSION['kk_sistema'][$this->_productos][$key_producto]['tabla'] = $tabla;
            }
            return true;
        } elseif ($id != '') {

            $key_producto = $this->_keyInsercion();

            $_SESSION['kk_sistema'][$this->_productos][$key_producto]['id'] = $id;
            $_SESSION['kk_sistema'][$this->_productos][$key_producto]['cantidad'] = $cantidad;
            $_SESSION['kk_sistema'][$this->_productos][$key_producto]['precio'] = $precio;
            $_SESSION['kk_sistema'][$this->_productos][$key_producto]['nombre'] = $nombre;
            $_SESSION['kk_sistema'][$this->_productos][$key_producto]['tabla'] = $tabla;
            return true;
        }
        return false;
    }

    public function obtenerCantidad($id, $tabla = '') {

        $key_producto = $this->_encontrarProducto($id, $tabla);

        if ($key_producto !== false) {
            return $_SESSION['kk_sistema'][$this->_productos][$key_producto]['cantidad'];
        } else {
            return false;
        }
    }

    public function obtenerProducto($id, $tabla = '') {

        $key_producto = $this->_encontrarProducto($id, $tabla);

        if ($key_producto !== false) {
            $producto['id'] = $_SESSION['kk_sistema'][$this->_productos][$key_producto]['id'] = $id;
            $producto['cantidad'] = $_SESSION['kk_sistema'][$this->_productos][$key_producto]['cantidad'];
            $producto['precio'] = $_SESSION['kk_sistema'][$this->_productos][$key_producto]['precio'];
            $producto['nombre'] = $_SESSION['kk_sistema'][$this->_productos][$key_producto]['nombre'];
            $producto['tabla'] = $_SESSION['kk_sistema'][$this->_productos][$key_producto]['tabla'];
            $producto['subtotal'] = $producto['cantidad'] * $producto['precio'];
            return $producto;
        } else {
            return false;
        }
    }

    public function eliminarProducto($id, $tabla = '') {
        if (isset($_SESSION['kk_sistema'][$this->_productos])) {

            $key_producto = $this->_encontrarProducto($id, $tabla);

            unset($_SESSION['kk_sistema'][$this->_productos][$key_producto]);
            return true;
        }
    }

    public function obtenerArray() {
        if (isset($_SESSION['kk_sistema'][$this->_productos])) {
            return $_SESSION['kk_sistema'][$this->_productos];
        } else {

            return false;
        }
    }

    public function obtenerArraySubtotales() {
        if (isset($_SESSION['kk_sistema'][$this->_productos])) {
            $total = 0;
            $productos = $_SESSION['kk_sistema'][$this->_productos];
            foreach ($_SESSION['kk_sistema'][$this->_productos] as $id => $producto) {
                $productos[$id]['subtotal'] = $_SESSION['kk_sistema'][$this->_productos][$id]['cantidad'] * $_SESSION['kk_sistema'][$this->_productos][$id]['precio'];
                $total += $productos[$id]['subtotal'];
            }
            return $productos;
        } else {

            return false;
        }
    }

    public function obtenerTotal() {
        if (isset($_SESSION['kk_sistema'][$this->_productos])) {
            $total = 0;
            $productos = $_SESSION['kk_sistema'][$this->_productos];
            foreach ($_SESSION['kk_sistema'][$this->_productos] as $id => $producto) {
                $total += ($_SESSION['kk_sistema'][$this->_productos][$id]['cantidad'] * $_SESSION['kk_sistema'][$this->_productos][$id]['precio']);
            }
            return $total;
        } else {

            return false;
        }
    }

    public function eliminarTodos() {
        if (isset($_SESSION['kk_sistema'][$this->_productos])) {
            unset($_SESSION['kk_sistema'][$this->_productos]);
            return true;
            echo ('es verdadero');
        } else {
            return false;
        }
    }

    public function verificarCarrito() {

        if (
                isset($_SESSION['kk_sistema'][$this->_productos]) && (count($_SESSION['kk_sistema'][$this->_productos]) > 0)
        ) {
            return true;
        } else {
            return false;
        }
    }

    public function imprimirForm() {

        $this->imprimir_form = true;
    }

    private function _encontrarProducto($id, $tabla) {
        if (isset($_SESSION['kk_sistema'][$this->_productos]) && ($id != '')
        ) {
            foreach ($_SESSION['kk_sistema'][$this->_productos] as $key => $value) {
                if (
                        ($_SESSION['kk_sistema'][$this->_productos][$key]['id'] == $id) && ($_SESSION['kk_sistema'][$this->_productos][$key]['tabla'] == $tabla)
                ) {
                    return $key;
                }
            }
            return false;
        } else {
            return false;
        }
    }

    private function _keyInsercion() {

        $ultimo_key = 0;

        if (isset($_SESSION['kk_sistema'][$this->_productos])) {
            foreach ($_SESSION['kk_sistema'][$this->_productos] as $key => $value) {
                $ultimo_key = $key;
            }
            if (isset($ultimo_key)) {
                $ultimo_key++;
            }
        }

        return $ultimo_key;
    }

//=== PAGO ===

    public function metodo_pago($metodo_pago = null) {
        if (isset($metodo_pago)) {
            $this->_metodo_pago = $metodo_pago;
        } else {
            die("Falta definir la variable de metodo_pago | metodo_pago('variable')\n");
        }
    }

    public function id_cliente($id_cliente = null) {
        if (isset($id_cliente)) {
            $this->_id_cliente = $id_cliente;
        } else {
            die("Falta definir la variable de id_cliente | id_cliente('variable')\n");
        }
    }

    public function codigo_de_seguridad($codigo_de_seguridad = null) {
        if (isset($codigo_de_seguridad)) {
            $this->_codigo_de_seguridad = $codigo_de_seguridad;
        } else {
            die("Falta definir la variable de codigo_de_seguridad | codigo_de_seguridad('variable')\n");
        }
    }

    public function id_operacion($id_operacion = null) {
        if (isset($id_operacion)) {
            $this->_id_operacion = $id_operacion;
        } else {
            die("Falta definir la variable de id_operacion | id_operacion('variable')\n");
        }
    }

    public function producto_nombre($producto_nombre = null) {
        if (isset($producto_nombre)) {
            $this->_producto_nombre = $producto_nombre;
        } else {
            die("Falta definir la variable de producto_nombre | producto_nombre('variable')\n");
        }
    }

    public function id_del_item($id_del_item = null) {
        if (isset($id_del_item)) {
            $this->_id_del_item = $id_del_item;
        } else {
            die("Falta definir la variable de id_del_item | id_del_item('variable')\n");
        }
    }

    public function cantidad($cantidad = null) {
        if (isset($cantidad)) {
            $this->_cantidad = $cantidad;
        } else {
            die("Falta definir la variable de cantidad | cantidad('variable')\n");
        }
    }

    public function tipo_de_moneda($tipo_de_moneda = null) {
        if (isset($tipo_de_moneda)) {
            $this->_tipo_de_moneda = $tipo_de_moneda;
        } else {
            die("Falta definir la variable de tipo_de_moneda | tipo_de_moneda('variable')\n");
        }
    }

    public function precio($precio = null) {
        if (isset($precio)) {
            $this->_precio = $precio;
        } else {
            die("Falta definir la variable de precio | precio('variable')\n");
        }
    }

    public function otros_costos($costo = null) {
        if (isset($costo)) {
            $this->_otros_costos += $costo;
        } else {
            die("Falta definir la variable de otros costos | otros_costos('variable')\n");
        }
    }

    public function cantidad_de_cuotas($_cantidad_de_cuotas = null) {
        if (isset($_cantidad_de_cuotas)) {
            $this->_cantidad_de_cuotas = $_cantidad_de_cuotas;
        } else {
            die("Falta definir la variable de cantidad_de_cuotas | cantidad_de_cuotas('variable')\n");
        }
    }

    public function pagina_pago_exito($pagina_pago_exito = null) {
        if (isset($pagina_pago_exito)) {
            $this->_pagina_pago_exito = $pagina_pago_exito;
        } else {
            die("Falta definir la variable de pagina_pago_exito | pagina_pago_exito('variable')\n");
        }
    }

    public function pagina_pago_fracaso($pagina_pago_fracaso = null) {
        if (isset($pagina_pago_fracaso)) {
            $this->_pagina_pago_fracaso = $pagina_pago_fracaso;
        } else {
            die("Falta definir la variable de pagina_pago_fracaso | pagina_pago_fracaso('variable')\n");
        }
    }

    public function medios_de_pagos($medios_de_pagos = null) {
        if (isset($medios_de_pagos)) {
            $this->_medios_de_pagos = $medios_de_pagos;
        } else {
            die("Falta definir la variable de medios_de_pagos | medios_de_pagos('variable')\n");
        }
    }

    public function referencia($referencia = null) {
        if (isset($referencia)) {
            $this->_referencia = $referencia;
        } else {
            die("Falta definir la variable de referencia | referencia('variable')\n");
        }
    }

    public function url_imagen($url_imagen = null) {
        if (isset($url_imagen)) {
            $this->_url_imagen = $url_imagen;
        } else {
            die("Falta definir la variable de url_imagen | url_imagen('variable')\n");
        }
    }

    public function comprador_nombre($comprador_nombre = null) {
        if (isset($comprador_nombre)) {
            $this->_comprador_nombre = $comprador_nombre;
        } else {
            die("Falta definir la variable de comprador_nombre | comprador_nombre('variable')\n");
        }
    }

    public function comprador_apellido($comprador_apellido = null) {
        if (isset($comprador_apellido)) {
            $this->_comprador_apellido = $comprador_apellido;
        } else {
            die("Falta definir la variable de comprador_apellido | comprador_apellido('variable')\n");
        }
    }

    public function comprador_email($comprador_email = null) {
        if (isset($comprador_email)) {
            $this->_comprador_email = $comprador_email;
        } else {
            die("Falta definir la variable de comprador_email | comprador_email('variable')\n");
        }
    }

    // datos para actualizar en la tabla
    public function tabla_registro_venta_ok($confirmacion_tabla, $confirmacion_id_registro, $confirmacion_campo) {
        $this->_confirmacion_tabla = $confirmacion_tabla;
        $this->_confirmacion_id_registro = $confirmacion_id_registro;
        $this->_confirmacion_campo = $confirmacion_campo;
    }

    public function pagar() {

        $this->_controlPagadoRedireccion();

        if ($this->_id_del_item == '') {
            foreach ($this->obtenerArray() as $producto) {
                if (isset($producto['id']) && $producto['id'] != '') {
                    $this->_id_del_item .= $producto['id'];
                }
            }
        }

        if ($this->_producto_nombre == '') {
            $separador = '';
            foreach ($this->obtenerArray() as $producto) {
                if (isset($producto['nombre']) && $producto['nombre'] != '') {
                    $this->_producto_nombre .= $producto['nombre'] . $separador;
                    $separador = ', ';
                }
            }
        }

        if ($this->_precio == '') {
            foreach ($this->obtenerArray() as $producto) {
                if (isset($producto['precio']) && $producto['precio'] != 0) {
                    $this->_precio = $this->_precio + ($producto['precio'] * $producto['cantidad']);
                }
            }
        }

        $this->_precio += $this->_otros_costos;

        switch ($this->_metodo_pago) {
            case 'mp':
                $formulario = $this->_mercadoPago();
                break;
            case 'dm':
                $formulario = $this->_dineroMail();
                break;
            case 'de':
                $formulario = $this->_decidir();
                break;
            default:
                die("Falta definir o esta mal definido el metodo de pago | _metodo_pago()\n");
                exit;
        }

        // armado del formulario
        $form = '<!DOCTYPE html>' . "\n";
        $form .= '<html>' . "\n";
        $form .= '   <head>' . "\n";
        $form .= '       <meta charset="UTF-8">' . "\n";
        $form .= '   </head>' . "\n";
        $form .= '   <body>' . "\n";
        $form .= $formulario;
        $form .= '      <script language="JavaScript">document.forms[0].submit();</script>' . "\n";
        $form .= '   </body>' . "\n";
        $form .= '</html>' . "\n";

        echo $form;
        exit;
    }

    private function _controlPagadoRedireccion() {

        $resultado_pago = VariableControl::getParam('cc');

        $ingresar_tabla = false;

        if ($resultado_pago == 'exito') {

            $redirigir = $this->_pagina_pago_exito;
            $ingresar_tabla = true;
        } elseif ($resultado_pago == 'pendiente') {

            $redirigir = $this->_pagina_pago_exito;
            $ingresar_tabla = true;
        } elseif ($resultado_pago == 'fracaso') {

            $redirigir = $this->_pagina_pago_fracaso;
        } elseif ($resultado_pago == 'informacion') {

            switch ($this->_metodo_pago) {
                case 'de':
                    $ingresar_tabla = $this->_decidir_informacion();
                    break;
            }
        }

        if (
                ($ingresar_tabla === true) && ($this->_confirmacion_tabla != '') && ($this->_confirmacion_id_registro != '') && ($this->_confirmacion_campo != '')
        ) {

            if (VariableGet::sistema('mostrar_errores') === false) {
                $mostrar_errores = 's';
            } else {
                $mostrar_errores = 'n';
            }

            switch (ucfirst(VariableGet::sistema('tipo_base'))) {
                case 'mysql':
                    $query = "UPDATE `" . $this->_confirmacion_tabla . "` SET `" . $this->_confirmacion_campo . "` = '1' WHERE `" . $this->_confirmacion_tabla . "` ='" . $this->_confirmacion_id_registro . "';";
                    $bd = BDMysqlConsulta::consulta($obtengo_query, $mostrar_errores);
                    break;
            }
        }

        if (isset($redirigir)) {
            header('Location: ' . $redirigir);
            exit;
        }
    }

    private function _controlDatos($datos_obligatorios) {

        if (VariableGet::sistema('mostrar_errores') === false) {
            return true;
        }

        $mensaje = '';

        foreach ($datos_obligatorios as $dato) {
            if (($dato == 'id_cliente') && ($this->_id_cliente == '')) {
                $mensaje .= "Falta definir id_cliente | id_cliente()\n";
            }
            if (($dato == 'codigo_de_seguridad') && ($this->_codigo_de_seguridad == '')) {
                $mensaje .= "Falta definir codigo_de_seguridad | codigo_de_seguridad()\n";
            }
            if (($dato == 'id_operacion') && ($this->_id_operacion == '')) {
                $mensaje .= "Falta definir codigo_de_seguridad | id_operacion()\n";
            }
            if (($dato == 'producto_nombre') && ($this->_producto_nombre == '')) {
                $mensaje .= "Falta definir producto_nombre | producto_nombre()\n";
            }
            if (($dato == 'id_del_item') && ($this->_id_del_item == '')) {
                $mensaje .= "Falta definir id_del_item | id_del_item()\n";
            }
            if (($dato == 'cantidad') && ($this->_cantidad == '')) {
                $mensaje .= "Falta definir cantidad | cantidad()\n";
            }
            if (($dato == 'tipo_de_moneda') && ($this->_tipo_de_moneda == '')) {
                $mensaje .= "Falta definir tipo_de_moneda | tipo_de_moneda()\n";
            }
            if (($dato == 'precio') && ($this->_precio == '')) {
                $mensaje .= "Falta definir precio | precio()\n";
            }
            if (($dato == 'cantidad_de_cuotas') && ($this->_cantidad_de_cuotas == '')) {
                $mensaje .= "Falta definir cantidad_de_cuotas | cantidad_de_cuotas()\n";
            }
            if (($dato == 'pagina_pago_exito') && ($this->_pagina_pago_exito == '')) {
                $mensaje .= "Falta definir pagina de pago realizado exitosamente | pagina_pago_exito()\n";
            }
            if (($dato == 'pagina_pago_fracaso') && ($this->_pagina_pago_fracaso == '')) {
                $mensaje .= "Falta definir pagina de pago NO realizado exitosamente | pagina_pago_fracaso()\n";
            }
            if (($dato == 'medios_de_pagos') && ($this->_medios_de_pagos == '')) {
                $mensaje .= "Falta definir medios_de_pagos | medios_de_pagos()\n";
            }
            if (($dato == 'referencia') && ($this->_referencia == '')) {
                $mensaje .= "Falta definir referencia | referencia()\n";
            }
            if (($dato == 'url_imagen') && ($this->_url_imagen == '')) {
                $mensaje .= "Falta definir url_imagen | url_imagen()\n";
            }
            if (($dato == 'comprador_nombre') && ($this->_comprador_nombre == '')) {
                $mensaje .= "Falta definir comprador_nombre | comprador_nombre()\n";
            }
            if (($dato == 'comprador_apellido') && ($this->_comprador_apellido == '')) {
                $mensaje .= "Falta definir comprador_apellido | comprador_apellido()\n";
            }
            if (($dato == 'comprador_email') && ($this->_comprador_email == '')) {
                $mensaje .= "Falta definir comprador_email | comprador_email()\n";
            }
        }

        if ($mensaje != '') {
            echo $mensaje;
            exit;
        }
    }

//=== FORMAS DE PAGO ===

    private function _mercadoPago() { // _metodo_pago = mp
        // http://developers.mercadopago.com/
        $datos_obligatorios = array(
            'id_cliente',
            'codigo_de_seguridad',
            'producto_nombre',
            'id_del_item',
            'cantidad',
            'tipo_de_moneda',
            'precio',
            'pagina_pago_exito',
            'pagina_pago_fracaso'
        );
        $this->_controlDatos($datos_obligatorios);

        $mensaje = '';

        if ($this->_tipo_de_moneda == '') {
            $this->_tipo_de_moneda = 1;
        }
        /*
          1	ARS (pesos argentinos)
          2	USD (dolares EEUU)
          3	BRL (reales)
          4	MXN (meso mexicano)
          5	VEF (bolivar fuerte)
         */
        $tipo_de_moneda = array(
            1 => 'ARS',
            2 => 'USD',
            3 => 'BRL',
            4 => 'MXN',
            5 => 'VEF',
        );
        $tipo_de_moneda = $tipo_de_moneda[$this->_tipo_de_moneda];

        if ($mensaje != '') {
            echo $mensaje;
            exit;
        }

        $hash_md5 = md5($this->_id_cliente . $this->_codigo_de_seguridad . $this->_cantidad . $tipo_de_moneda . $this->_precio . $this->_id_del_item . $this->_referencia);

        $form = '
            <form action="https://www.mercadopago.com/checkout/init" method="post" enctype="application/x-www-form-urlencoded">
            <input type="hidden" name="client_id" value="' . $this->_id_cliente . '"/>
            <input type="hidden" name="md5" value="' . $hash_md5 . '"/>
            <input type="hidden" name="item_title" value="' . $this->_producto_nombre . '"/>
            <input type="hidden" name="item_quantity" value="' . $this->_cantidad . '"/>
            <input type="hidden" name="item_currency_id" value="' . $tipo_de_moneda . '"/>
            <input type="hidden" name="item_unit_price" value="' . $this->_precio . '"/>
            <input type="hidden" name="item_id" value="' . $this->_id_del_item . '"/>
            <input type="hidden" name="external_reference" value="' . $this->_referencia . '"/>
            <input type="hidden" name="item_picture_url" value="' . $this->_url_imagen . '"/>
            <input type="hidden" name="payer_name" value="' . $this->_comprador_nombre . '"/>
            <input type="hidden" name="payer_surname" value="' . $this->_comprador_apellido . '"/>
            <input type="hidden" name="payer_email" value="' . $this->_comprador_email . '"/>
            <input type="hidden" name="back_url_success" value="'.$_SERVER['REQUEST_SCHEME'].'://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '?cc=exito"/>
            <input type="hidden" name="back_url_pending" value="'.$_SERVER['REQUEST_SCHEME'].'://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '?cc=pendiente"/>
            </form>
        ';

        if ($this->imprimir_form === true) {

            exit($form);
        }

        return $form;
    }

    private function _dineroMail() { // _metodo_pago = dm
        // https://ar.dineromail.com/biblioteca
        $datos_obligatorios = array(
            'id_cliente',
            'id_del_item',
            'tipo_de_moneda',
            'precio',
            'medios_de_pagos',
            'pagina_pago_exito',
            'pagina_pago_fracaso'
        );
        $this->_controlDatos($datos_obligatorios);


        $mensaje = '';

        // $medios_pagos = $this->_medios_de_pagos; // (*) 4,5,6,2,7,13

        if ($mensaje != '') {
            echo $mensaje;
            exit;
        }

        $form = '
            <form action="https://argentina.dineromail.com/Shop/Shop_Ingreso.asp" method="post">
            <input type="hidden" name="NombreItem" value="Seminario online"/>
            <input type="hidden" name="TipoMoneda" value="' . $this->_tipo_de_moneda . '"/>
            <input type="hidden" name="PrecioItem" value="' . $this->_precio . '"/>
            <input type="hidden" name="E_Comercio" value="' . $this->_id_cliente . '"/>
            <input type="hidden" name="NroItem" value="' . $this->_id_del_item . '"/>
            <input type="hidden" name="image_url" value="http://"/>
            <input type="hidden" name="DireccionExito" value="http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '?cc=exito"/>
            <input type="hidden" name="DireccionFracaso" value="http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '?cc=fracaso"/>
            <input type="hidden" name="DireccionEnvio" value="1"/>
            <input type="hidden" name="Mensaje" value="1"/>
            <input type="hidden" name="MediosPago" value="' . $this->_medios_de_pagos . '"/>
            </form>
        ';

        if ($this->imprimir_form === true) {

            exit($form);
        }

        return $form;
    }

    private function _decidir() { // _metodo_pago = de
        // https://ar.dineromail.com/biblioteca
        $datos_obligatorios = array(
            'id_cliente',
            'id_operacion',
            'precio',
            'cantidad_de_cuotas',
            'medios_de_pagos'
        );
        $this->_controlDatos($datos_obligatorios);

        // $this->_id_cliente; // Alfanumérico de 8 caracteres.
        // $this->_id_operacion; // Alfanumérico de 1 a 20 caracteres.
        // $this->_medios_de_pagos; // (*) 1, seleccionando solo uno
        // $this->_precio; // (*), ejemplo $125,38 -> 12538
        // $this->_cantidad_de_cuotas; // (*) '01', '03' 2 digitos
        // $this->_comprador_email = ''; // 80 caracteres
        // $this->_referencia = ''; // 256 caracteres, permite es "_-,.;:@|"

        $mensaje = '';

        /*
          SIS     Gateway Tarjeta
          10	6	AMEX
          11	36	ARCASH
          12	27	CABAL
          13	34	COOPEPLUS
          14	38	CREDIMAS
          15	8	DINERS
          16	29	ITALCRED
          17	43	MAS
          18	15	MASTERCARD
          19	20	MASTERCARD TEST, mientras no esta homologado
          20	15	MASTERCARD TEST, si se encuentra homologado
          21	24	NARANJA
          22	42	NATIVA
          23	39	NEVADA
          24	37	NEXO
          25	25	PAGO FACIL
          26	41	PAGO MIS CUENTAS
          27	26	RAPI PAGO
          28	23	SHOPPING
          29	1	VISA
          30	31	VISA DEBITO
         */
        $medios_de_pagos = array(
            10 => 6,
            11 => 36,
            12 => 27,
            13 => 34,
            14 => 38,
            15 => 8,
            16 => 29,
            17 => 43,
            18 => 15,
            19 => 20,
            20 => 15,
            21 => 24,
            22 => 42,
            23 => 39,
            24 => 37,
            25 => 25,
            26 => 41,
            27 => 26,
            28 => 23,
            29 => 1,
            30 => 31
        );
        $medios_de_pagos = $medios_de_pagos[$this->_medios_de_pagos];
        if (isset($medios_de_pagos)) {
            $mensaje .= "El siguiente valor se encuentra mal definido en Decidir | medios_de_pagos()\n";
        }

        if ($mensaje != '') {
            echo $mensaje;
            exit;
        }

        $form = '
            <form action="https://sps.decidir.com/sps-ar/Validar" method="post">
            <input type="hidden" name="NROCOMERCIO" value="' . $this->_id_cliente . '"/>
            <input type="hidden" name="NROOPERACION" value="' . $this->_id_operacion . '"/>
            <input type="hidden" name="MEDIODEPAGO" value="' . $this->_medios_de_pagos . '"/>
            <input type="hidden" name="MONTO" value="' . $this->_precio . '"/>
            <input type="hidden" name="CUOTAS" value="' . $this->_cantidad_de_cuotas . '"/>
            <input type="hidden" name="URLDINAMICA" value="http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '?cc=informacion"/>
            <input type="hidden" name="EMAILCLIENTE" value="' . $this->_comprador_email . '"/>
            <input type="hidden" name="PARAMSITIO" value="' . $this->_referencia . '"/>
            </form>
        ';

        if ($this->imprimir_form === true) {

            exit($form);
        }

        return $form;
    }

    private function _decidir_informacion() {

        /*
         * Desarrollar esto
         */

        return true;
    }

}
