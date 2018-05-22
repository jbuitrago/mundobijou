<?php

class CarritoDeCompras {

    // session de compra
    private $_carrito = 0;
    // datos de pago
    private $_metodo_pago;
    private $_pago_envio = false;
    private $_codigo_postal = false;
    private $_id_cliente;
    private $_id_cuenta;
    private $_codigo_de_seguridad;
    private $_id_operacion = '';
    private $_producto_nombre;
    private $_producto_nombre_total;
    private $_cantidad = 1;
    private $_tipo_de_moneda = 1;
    private $_precio;
    private $_precio_total;
    private $_otros_costos = 0;
    private $_id_del_item;
    private $_id_del_item_total;
    private $_pagina_pago_pendiente;
    private $_medios_de_pagos;
    private $_comprador_nombre = '';
    private $_comprador_apellido = '';
    private $_comprador_email;
    private $_cantidad_de_cuotas = 1;
    private $_url_control_pago;
    private $_imprimir_datos;
    private $_codigo_control;

    function __construct($nombre) {

        $control_nombre = substr($nombre, 0, 3);

        if ($control_nombre != 'kk_') {
            $this->_carrito = $nombre;
        }
    }

    public function agregarModificarProducto($id, $cantidad = null, $precio = 0, $nombre = '', $tabla = '', $otros_valores = '', $alto = '', $ancho = '', $largo = '', $peso = '') {

        $key_producto = $this->_encontrarProducto($id, $tabla);

        if ($key_producto !== false) {

            if ($cantidad !== null) {
                $_SESSION['kk_sistema'][$this->_carrito][$key_producto]['cantidad'] = $cantidad;
            }
            if ($precio != 0) {
                $_SESSION['kk_sistema'][$this->_carrito][$key_producto]['precio'] = $precio;
            }
            if ($nombre != '') {
                $_SESSION['kk_sistema'][$this->_carrito][$key_producto]['nombre'] = $nombre;
            }
            if ($tabla != '') {
                $_SESSION['kk_sistema'][$this->_carrito][$key_producto]['tabla'] = $tabla;
            }
            if ($otros_valores != '') {
                $_SESSION['kk_sistema'][$this->_carrito][$key_producto]['otros_valores'] = $otros_valores;
            }
            if ($alto != '') {
                $_SESSION['kk_sistema'][$this->_carrito][$key_producto]['alto'] = $alto;
            }
            if ($ancho != '') {
                $_SESSION['kk_sistema'][$this->_carrito][$key_producto]['ancho'] = $ancho;
            }
            if ($largo != '') {
                $_SESSION['kk_sistema'][$this->_carrito][$key_producto]['largo'] = $largo;
            }
            if ($peso != '') {
                $_SESSION['kk_sistema'][$this->_carrito][$key_producto]['peso'] = $peso;
            }
            return true;
        } elseif ($id != '') {

            $key_producto = $this->_keyInsercion();

            $_SESSION['kk_sistema'][$this->_carrito][$key_producto]['id'] = $id;
            $_SESSION['kk_sistema'][$this->_carrito][$key_producto]['cantidad'] = $cantidad;
            $_SESSION['kk_sistema'][$this->_carrito][$key_producto]['precio'] = $precio;
            $_SESSION['kk_sistema'][$this->_carrito][$key_producto]['nombre'] = $nombre;
            $_SESSION['kk_sistema'][$this->_carrito][$key_producto]['tabla'] = $tabla;
            $_SESSION['kk_sistema'][$this->_carrito][$key_producto]['otros_valores'] = $otros_valores;
            $_SESSION['kk_sistema'][$this->_carrito][$key_producto]['alto'] = $alto;
            $_SESSION['kk_sistema'][$this->_carrito][$key_producto]['ancho'] = $ancho;
            $_SESSION['kk_sistema'][$this->_carrito][$key_producto]['largo'] = $largo;
            $_SESSION['kk_sistema'][$this->_carrito][$key_producto]['peso'] = $peso;
            return true;
        }
        return false;
    }

    public function obtenerCantidad($id, $tabla = '') {
        $key_producto = $this->_encontrarProducto($id, $tabla);
        if ($key_producto !== false) {
            return $_SESSION['kk_sistema'][$this->_carrito][$key_producto]['cantidad'];
        } else {
            return false;
        }
    }

    public function obtenerProducto($id, $tabla = '') {

        $key_producto = $this->_encontrarProducto($id, $tabla);

        if ($key_producto !== false) {
            $producto['id'] = $_SESSION['kk_sistema'][$this->_carrito][$key_producto]['id'] = $id;
            $producto['cantidad'] = $_SESSION['kk_sistema'][$this->_carrito][$key_producto]['cantidad'];
            $producto['precio'] = $_SESSION['kk_sistema'][$this->_carrito][$key_producto]['precio'];
            $producto['nombre'] = $_SESSION['kk_sistema'][$this->_carrito][$key_producto]['nombre'];
            $producto['tabla'] = $_SESSION['kk_sistema'][$this->_carrito][$key_producto]['tabla'];
            $producto['otros_valores'] = $_SESSION['kk_sistema'][$this->_carrito][$key_producto]['otros_valores'];
            $producto['alto'] = $_SESSION['kk_sistema'][$this->_carrito][$key_producto]['alto'];
            $producto['ancho'] = $_SESSION['kk_sistema'][$this->_carrito][$key_producto]['ancho'];
            $producto['volumen'] = $_SESSION['kk_sistema'][$this->_carrito][$key_producto]['largo'];
            $producto['largo'] = $_SESSION['kk_sistema'][$this->_carrito][$key_producto]['peso'];
            $producto['subtotal'] = $producto['cantidad'] * $producto['precio'];
            return $producto;
        } else {
            return false;
        }
    }

    public function eliminarProducto($id, $tabla = '') {
        if (isset($_SESSION['kk_sistema'][$this->_carrito])) {

            $key_producto = $this->_encontrarProducto($id, $tabla);

            if ($key_producto !== false) {
                unset($_SESSION['kk_sistema'][$this->_carrito][$key_producto]);
            }

            return true;
        } else {
            return false;
        }
    }

    public function obtenerArray() {
        if (isset($_SESSION['kk_sistema'][$this->_carrito])) {
            return $_SESSION['kk_sistema'][$this->_carrito];
        } else {
            return false;
        }
    }

    public function obtenerArraySubtotales() {
        if (isset($_SESSION['kk_sistema'][$this->_carrito])) {
            $total = 0;
            $productos = $_SESSION['kk_sistema'][$this->_carrito];
            foreach ($_SESSION['kk_sistema'][$this->_carrito] as $id => $producto) {
                $productos[$id]['subtotal'] = $_SESSION['kk_sistema'][$this->_carrito][$id]['cantidad'] * $_SESSION['kk_sistema'][$this->_carrito][$id]['precio'];
                $total += $productos[$id]['subtotal'];
            }
            return $productos;
        } else {

            return false;
        }
    }

    public function obtenerTotal() {
        if (isset($_SESSION['kk_sistema'][$this->_carrito])) {
            $total = 0;
            $productos = $_SESSION['kk_sistema'][$this->_carrito];
            foreach ($_SESSION['kk_sistema'][$this->_carrito] as $id => $producto) {
                $total += ($_SESSION['kk_sistema'][$this->_carrito][$id]['cantidad'] * $_SESSION['kk_sistema'][$this->_carrito][$id]['precio']);
            }
            return $total;
        } else {

            return false;
        }
    }

    public function obtenerCostoEnvio($codigo_postal) {

        switch ($this->_metodo_pago) {
            case 'mp':
                $this->_codigo_postal = $codigo_postal;
                return $this->_mercadoPagoCostoEnvio();
                break;
            case 'dm':
                die("Este método de pago no permite obtener un costo de envío\n");
                break;
            case 'de':
                die("Este método de pago no permite obtener un costo de envío\n");
                break;
            default:
                die("Falta definir o esta mal definido el metodo de pago | _metodo_pago()\n");
        }
        exit;
    }

    public function eliminarTodos() {
        if (isset($_SESSION['kk_sistema'][$this->_carrito])) {
            unset($_SESSION['kk_sistema'][$this->_carrito]);
            return true;
            echo ('es verdadero');
        } else {
            return false;
        }
    }

    public function verificarCarrito() {

        if (
                isset($_SESSION['kk_sistema'][$this->_carrito]) && (count($_SESSION['kk_sistema'][$this->_carrito]) > 0)
        ) {
            return true;
        } else {
            return false;
        }
    }

    public function imprimirDatos() {

        $this->_imprimir_datos = true;
    }

    private function _encontrarProducto($id, $tabla) {
        if (isset($_SESSION['kk_sistema'][$this->_carrito]) && ($id != '')
        ) {
            foreach ($_SESSION['kk_sistema'][$this->_carrito] as $key => $value) {
                if (
                        ($_SESSION['kk_sistema'][$this->_carrito][$key]['id'] == $id) && ($_SESSION['kk_sistema'][$this->_carrito][$key]['tabla'] == $tabla)
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

        if (isset($_SESSION['kk_sistema'][$this->_carrito])) {
            foreach ($_SESSION['kk_sistema'][$this->_carrito] as $key => $value) {
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
        if ($metodo_pago !== null) {
            $this->_metodo_pago = $metodo_pago;
        } else {
            die("Falta definir la variable de metodo_pago | metodo_pago('variable')\n");
        }
    }

    public function pago_envio($codigo_postal) {

        switch ($this->_metodo_pago) {
            case 'mp':
                $this->_pago_envio = true;
                $this->_codigo_postal = $codigo_postal;
                break;
            case 'dm':
                die("Este método de pago no permite obtener un costo de envío\n");
                break;
            case 'de':
                die("Este método de pago no permite obtener un costo de envío\n");
                break;
            default:
                die("Falta definir o esta mal definido el metodo de pago | _metodo_pago()\n");
        }
    }

    public function id_cliente($id_cliente = null) {
        if ($id_cliente !== null) {
            $this->_id_cliente = $id_cliente;
        } else {
            die("Falta definir la variable de id_cliente | id_cliente('variable')\n");
        }
    }

    public function id_cuenta($id_cuenta = null) {
        if ($id_cuenta !== null) {
            $this->_id_cuenta = $id_cuenta;
        } else {
            die("Falta definir la variable de id_cuenta | id_cuenta('variable')\n");
        }
    }

    public function codigo_de_seguridad($codigo_de_seguridad = null) {
        if ($codigo_de_seguridad !== null) {
            $this->_codigo_de_seguridad = $codigo_de_seguridad;
        } else {
            die("Falta definir la variable de codigo_de_seguridad | codigo_de_seguridad('variable')\n");
        }
    }

    public function producto_nombre($producto_nombre = null) {
        if ($producto_nombre !== null) {
            $this->_producto_nombre = $producto_nombre;
        } else {
            die("Falta definir la variable de producto_nombre | producto_nombre('variable')\n");
        }
    }

    public function id_del_item($id_del_item = null) {
        if ($id_del_item !== null) {
            $this->_id_del_item = $id_del_item;
        } else {
            die("Falta definir la variable de id_del_item | id_del_item('variable')\n");
        }
    }

    public function cantidad($cantidad = null) {
        if ($cantidad !== null) {
            $this->_cantidad = $cantidad;
        } else {
            die("Falta definir la variable de cantidad | cantidad('variable')\n");
        }
    }

    public function tipo_de_moneda($tipo_de_moneda = null) {
        if ($tipo_de_moneda !== null) {
            $this->_tipo_de_moneda = $tipo_de_moneda;
        } else {
            die("Falta definir la variable de tipo_de_moneda | tipo_de_moneda('variable')\n");
        }
    }

    public function precio($precio = null) {
        if ($precio !== null) {
            $this->_precio = $precio;
        } else {
            die("Falta definir la variable de precio | precio('variable')\n");
        }
    }

    public function otros_costos($costo = null) {
        if ($costo !== null) {
            $this->_otros_costos += $costo;
        } else {
            die("Falta definir la variable de otros costos | otros_costos('variable')\n");
        }
    }

    public function cantidad_de_cuotas($_cantidad_de_cuotas = null) {
        if ($_cantidad_de_cuotas !== null) {
            $this->_cantidad_de_cuotas = $_cantidad_de_cuotas;
        } else {
            die("Falta definir la variable de cantidad_de_cuotas | cantidad_de_cuotas('variable')\n");
        }
    }

    public function url_control_pago($url_control_pago = null) {
        if ($url_control_pago !== null) {
            if (substr($url_control_pago, 0, 1) == '/') {
                $this->_url_control_pago = $url_control_pago;
            } elseif (substr($url_control_pago, 0, 4) == 'http') {
                $this->_url_control_pago = $url_control_pago;
            } else {
                $this->_url_control_pago = '/' . $url_control_pago;
            }
        } else {
            die("Falta definir la variable de url_control_pago | url_control_pago('variable')\n");
        }
    }

    public function medios_de_pagos($medios_de_pagos = null) {
        if ($medios_de_pagos !== null) {
            $this->_medios_de_pagos = $medios_de_pagos;
        } else {
            die("Falta definir la variable de medios_de_pagos | medios_de_pagos('variable')\n");
        }
    }

    public function comprador_nombre($comprador_nombre = null) {
        if ($comprador_nombre !== null) {
            $this->_comprador_nombre = $comprador_nombre;
        } else {
            die("Falta definir la variable de comprador_nombre | comprador_nombre('variable')\n");
        }
    }

    public function comprador_apellido($comprador_apellido = null) {
        if ($comprador_apellido !== null) {
            $this->_comprador_apellido = $comprador_apellido;
        } else {
            die("Falta definir la variable de comprador_apellido | comprador_apellido('variable')\n");
        }
    }

    public function comprador_email($comprador_email = null) {
        if ($comprador_email !== null) {
            $this->_comprador_email = $comprador_email;
        } else {
            die("Falta definir la variable de comprador_email | comprador_email('variable')\n");
        }
    }

    public function controlPago() {

        $estado = null;
        $codigo = false;

        // id registro del carrito
        $codigo_control = VariableControl::getParam('codigo_control');

        if ($codigo_control != '') {
            $archivo = $this->_obtenerDirectorio(VariableGet::sistema('directorio_cache_sistema')) . '/CarritoDeCompras-' . $codigo_control . '.cache';

            if (file_exists($archivo)) {
                $codigo = file_get_contents($this->_obtenerDirectorio(VariableGet::sistema('directorio_cache_sistema')) . '/CarritoDeCompras-' . $codigo_control . '.cache');
                unlink($this->_obtenerDirectorio(VariableGet::sistema('directorio_cache_sistema')) . '/CarritoDeCompras-' . $codigo_control . '.cache');
            } else {
                return null;
            }

            $resultado_pago = VariableControl::getParam('cc');

            switch ($resultado_pago) {
                case 'exito':
                    $this->eliminarTodos();
                    $estado = true;
                    break;
                case 'fracaso':
                    $estado = false;
                    break;
                case 'pendiente':
                    $estado = 'pendiente';
                    break;
                case 'informacion':
                    $estado = $this->_decidir_informacion();
                    break;
            }
        } else {
            return false;
        }

        foreach (glob($this->_obtenerDirectorio(VariableGet::sistema('directorio_cache_sistema')) . '/CarritoDeCompras-*') as $nombre_archivo) {
            if (file_exists($nombre_archivo) && (filemtime($nombre_archivo) < (time() - (60 * 60 * 24)))) {
                unlink($nombre_archivo);
            }
        }

        return array($estado, $codigo);
    }

    public function controlPagoMP() {

        $topic = VariableControl::getParam('topic');
        $id = VariableControl::getParam('id');

        if (($topic == 'payment') && ($id != '')) {

            include_once(VariableGet::sistema('directorio_clases') . '/CarritoDeCompras/mercadopago.php');

            $mp = new MP($this->_id_cliente, $this->_codigo_de_seguridad);

            if (!isset($id, $topic) || !ctype_digit($id)) {
                http_response_code(400);
                return;
            }

            $merchant_order_info = null;

            switch ($topic) {
                case 'payment':
                    $payment_info = $mp->get("/collections/notifications/" . $id);
                    $merchant_order_info = $mp->get("/merchant_orders/" . $payment_info["response"]["collection"]["merchant_order_id"]);
                    break;
                case 'merchant_order':
                    $merchant_order_info = $mp->get("/merchant_orders/" . $id);
                    break;
                default:
                    $merchant_order_info = null;
            }

            if ($merchant_order_info == null) {
                return false;
            }

            if ($merchant_order_info["status"] == 200) {

                $datos['id'] = $merchant_order_info["response"]['external_reference'];

                // ISO 3166-1 alfa-2
                switch ($merchant_order_info["response"]['site_id']) {
                    case 'MLA':
                        $datos['pais'] = 'Argentina';
                        $datos['pais_iso'] = 'AR';
                        break;
                    case 'MLB':
                        $datos['pais'] = 'Brasil';
                        $datos['pais_iso'] = 'BR';
                        break;
                    case 'MLM':
                        $datos['pais'] = 'México';
                        $datos['pais_iso'] = 'MX';
                        break;
                    case 'MLV':
                        $datos['pais'] = 'Venezuela';
                        $datos['pais_iso'] = 'VE';
                        break;
                    case 'MLC':
                        $datos['pais'] = 'Chile';
                        $datos['pais_iso'] = 'CL';
                        break;
                    case 'MPE':
                        $datos['pais'] = 'Perú';
                        $datos['pais_iso'] = 'PE';
                        break;
                    case 'MCO':
                        $datos['pais'] = 'Colombia';
                        $datos['pais_iso'] = 'CO';
                        break;
                    default:
                        break;
                }

                // ISO 4217
                switch ($merchant_order_info["response"]['payments'][0]['currency_id']) {
                    case 'ARS':
                        $datos['moneda'] = 'Peso argentino';
                        $datos['moneda_iso'] = 'ARS';
                        break;
                    case 'BRL':
                        $datos['moneda'] = 'Real';
                        $datos['moneda_iso'] = 'BRL';
                        break;
                    case 'MXN':
                        $datos['moneda'] = 'Peso mexicano';
                        $datos['moneda_iso'] = 'MXN';
                        break;
                    case 'VEF':
                        $datos['moneda'] = 'Bolívar';
                        $datos['moneda_iso'] = 'VEF';
                        break;
                    case 'CLP':
                        $datos['moneda'] = 'Peso chileno';
                        $datos['moneda_iso'] = 'CLP';
                        break;
                    case 'PEN':
                        $datos['moneda'] = 'Sol';
                        $datos['moneda_iso'] = 'MCO';
                        break;
                    case 'COP':
                        $datos['moneda'] = 'Peso colombiano';
                        $datos['moneda_iso'] = 'MCO';
                        break;
                    default:
                        break;
                }

                if ($merchant_order_info["response"]['payments'][0]['status'] == 'approved') {
                    $datos['cobrado'] = true;
                    $datos['estado'] = 'aprobado';
                    $datos['estado_detalle'] = 'acreditado';
                } elseif ($merchant_order_info["response"]['payments'][0]['status'] == 'rejected') {
                    $datos['cobrado'] = false;
                    $datos['estado'] = 'rechazado';
                    switch ($merchant_order_info["response"]['payments'][0]['status_detail']) {
                        case 'cc_rejected_bad_filled_date':
                            $datos['estado_detalle'] = 'mal llenado de la fecha de';
                            break;
                        case 'cc_rejected_bad_filled_security_code':
                            $datos['estado_detalle'] = 'mal llenado código de seguridad';
                            break;
                        case 'cc_rejected_other_reason':
                            $datos['estado_detalle'] = 'por alguna razón';
                            break;
                        case 'cc_rejected_insufficient_amount':
                            $datos['estado_detalle'] = 'cantidad insuficiente';
                            break;
                        case 'cc_rejected_call_for_authorize':
                            $datos['estado_detalle'] = 'pedido no autorizado';
                            break;
                        case 'cc_rejected_bad_filled_other':
                            $datos['estado_detalle'] = 'campos mas ingresados';
                            break;
                        default:
                            break;
                    }
                } elseif ($merchant_order_info["response"]['payments'][0]['status'] == 'pending') {
                    $datos['cobrado'] = false;
                    $datos['estado'] = 'pendiente';
                    $datos['estado_detalle'] = 'a la espera de pago';
                }

                return $datos;
            }
        } else {
            return false;
        }
    }

    public function pagar($id_operacion = null) {

        if ($id_operacion == null) {
            die("Falta definir el identificador de la operacion | pagar('variable')\n");
        } else {
            $this->_id_operacion = $id_operacion;
        }

        $cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
        $codigo_control = "";
        for ($i = 0; $i < 12; $i++) {
            $codigo_control .= substr($cadena, rand(0, 62), 1);
        }
        $this->_codigo_control = $codigo_control;
        file_put_contents($this->_obtenerDirectorio(VariableGet::sistema('directorio_cache_sistema')) . '/CarritoDeCompras-' . $codigo_control . '.cache', $this->_id_operacion, LOCK_EX);

        if ($this->_id_del_item == '') {
            if (is_array($this->obtenerArray())) {
                foreach ($this->obtenerArray() as $producto) {
                    if (isset($producto['id']) && $producto['id'] != '') {
                        $this->_id_del_item_total .= $producto['id'];
                    }
                }
            }
        }

        if ($this->_producto_nombre == '') {
            $separador = '';
            if (is_array($this->obtenerArray())) {
                foreach ($this->obtenerArray() as $producto) {
                    if (isset($producto['nombre']) && $producto['nombre'] != '') {
                        $this->_producto_nombre_total .= $producto['nombre'] . $separador;
                        $separador = ', ';
                    }
                }
            }
        }

        if ($this->_precio == 0) {
            if (is_array($this->obtenerArray())) {
                foreach ($this->obtenerArray() as $producto) {
                    if (isset($producto['precio']) && $producto['precio'] != 0) {
                        $this->_precio_total = $this->_precio + ($producto['precio'] * $producto['cantidad']);
                    }
                }
            }
        } else {
            $this->_precio_total = $this->_precio;
        }

        $this->_precio_total += $this->_otros_costos;

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
            if (($dato == 'id_cuenta') && ($this->_id_cuenta == '')) {
                $mensaje .= "Falta definir id_cuenta | id_cuenta()\n";
            }
            if (($dato == 'codigo_de_seguridad') && ($this->_codigo_de_seguridad == '')) {
                $mensaje .= "Falta definir codigo_de_seguridad | codigo_de_seguridad()\n";
            }
            if (($dato == 'id_operacion') && ($this->_id_operacion == '')) {
                $mensaje .= "Falta definir codigo_de_seguridad | id_operacion()\n";
            }
            if (($dato == 'producto_nombre') && ($this->_producto_nombre_total != '')) {
                $error = false;
                foreach ($this->obtenerArray() as $producto) {
                    if (!isset($producto['nombre']) || $producto['nombre'] == '') {
                        $error = true;
                    }
                }
                if ($error == true) {
                    $mensaje .= "Falta definir algun producto_nombre | producto_nombre()\n";
                }
            } elseif (($dato == 'producto_nombre') && ($this->_producto_nombre_total == '')) {
                $mensaje .= "Falta definir producto_nombre | producto_nombre()\n";
            }
            if (($dato == 'producto_nombre_total') && ($this->_producto_nombre_total == '')) {
                $mensaje .= "Falta definir producto_nombre | producto_nombre()\n";
            }
            if (($dato == 'id_del_item') && ($this->_id_del_item_total != '')) {
                $error = false;
                foreach ($this->obtenerArray() as $producto) {
                    if (!isset($producto['id']) || $producto['id'] == '') {
                        $error = true;
                    }
                }
                if ($error == true) {
                    $mensaje .= "Falta definir algun id_del_item | id_del_item()\n";
                }
            } elseif (($dato == 'id_del_item') && ($this->_id_del_item == '')) {
                $mensaje .= "Falta definir id_del_item | id_del_item()\n";
            }
            if (($dato == 'id_del_item_total') && ($this->_id_del_item_total == '')) {
                $mensaje .= "Falta definir id_del_item | id_del_item()\n";
            }
            if (($dato == 'cantidad') && ($this->_cantidad == '')) {
                $mensaje .= "Falta definir cantidad | cantidad()\n";
            }
            if (($dato == 'tipo_de_moneda') && ($this->_tipo_de_moneda == '')) {
                $mensaje .= "Falta definir tipo_de_moneda | tipo_de_moneda()\n";
            }
            if (($dato == 'precio') && ($this->_precio_total != '')) {
                $error = false;
                foreach ($this->obtenerArray() as $producto) {
                    if (!isset($producto['precio']) || $producto['precio'] == '') {
                        $error = true;
                    }
                }
                if ($error == true) {
                    $mensaje .= "Falta definir algun precio | precio()\n";
                }
            } elseif (($dato == 'precio') && ($this->_precio == '')) {
                $mensaje .= "Falta definir precio | precio()\n";
            }
            if (($dato == 'precio_total') && ($this->_precio_total == '')) {
                $mensaje .= "Falta definir precio | precio()\n";
            }
            if (($dato == 'cantidad') && ($this->_cantidad == '')) {
                $error = false;
                foreach ($this->obtenerArray() as $producto) {
                    if (!isset($producto['cantidad']) || $producto['cantidad'] == '') {
                        $error = true;
                    }
                }
                if ($error == true) {
                    $mensaje .= "Falta definir alguna cantidad | cantidad()\n";
                }
            } elseif (($dato == 'cantidad') && ($this->_cantidad == '')) {
                $mensaje .= "Falta definir cantidad | cantidad()\n";
            }
            if (($dato == 'cantidad_de_cuotas') && ($this->_cantidad_de_cuotas == '')) {
                $mensaje .= "Falta definir cantidad_de_cuotas | cantidad_de_cuotas()\n";
            }
            if (($dato == 'url_control_pago') && ($this->_url_control_pago == '')) {
                $mensaje .= "Falta definir la url de control | url_control_pago()\n";
            }
            if (($dato == 'medios_de_pagos') && ($this->_medios_de_pagos == '')) {
                $mensaje .= "Falta definir medios_de_pagos | medios_de_pagos()\n";
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
    // MercadoPago

    private function _mercadoPago() { // _metodo_pago = mp
        $datos_obligatorios = array(
            'id_cliente',
            'codigo_de_seguridad',
            'producto_nombre',
            'id_del_item',
            'cantidad',
            'tipo_de_moneda',
            'precio',
            'url_control_pago'
        );
        $this->_controlDatos($datos_obligatorios);

        $mensaje = '';

        if ($this->_tipo_de_moneda == '') {
            $this->_tipo_de_moneda = 1;
        }
        /*
          ISO_4217
          1	ARS (pesos argentinos)
          2	USD (dolares EEUU)
          3	BRL (reales)
          4	MXN (meso mexicano)
          5	VEF (bolivar fuerte)
          6     CLP (Peso Chileno)
          7     COP (Peso Colombiano)
         */
        $tipo_de_moneda = array(
            1 => 'ARS',
            2 => 'USD',
            3 => 'BRL',
            4 => 'MXN',
            5 => 'VEF',
            6 => 'CLP',
            7 => 'COP',
        );
        $tipo_de_moneda = $tipo_de_moneda[$this->_tipo_de_moneda];

        if ($mensaje != '') {
            echo $mensaje;
            exit;
        }

        include_once(VariableGet::sistema('directorio_clases') . '/CarritoDeCompras/mercadopago.php');

        $mp = new MP($this->_id_cliente, $this->_codigo_de_seguridad);

        $items = array();
        foreach ($this->obtenerArray() as $producto) {
            $items[] = array(
                "id" => $producto['id'],
                "title" => $producto['nombre'],
                "currency_id" => $tipo_de_moneda,
                "quantity" => (int) $producto['cantidad'],
                "unit_price" => (int) $producto['precio']
            );
        }

        if ($this->_otros_costos != 0) {
            $items[] = array(
                "title" => 'Otros costos',
                "currency_id" => $tipo_de_moneda,
                "quantity" => 1,
                "unit_price" => $this->_otros_costos
            );
        }

        $envio = array();
        if ($this->_pago_envio) {
            foreach ($this->obtenerArray() as $producto) {
                $envio[] = array(
                    "mode" => 'me2',
                    "dimensions" => '"' . (int) $producto['alto'] . 'x' . (int) $producto['ancho'] . 'x' . (int) $producto['largo'] . ',' . (int) $producto['peso'] . '"',
                    "local_pickup" => true,
                    "free_methods" => array(
                        array(
                            "id" => 73328
                        )
                    ),
                    "default_shipping_method" => 73328,
                    "zip_code" => '"' . $this->_codigo_postal . '"',
                );
            }
        }

        $preference_data = array(
            "items" => $items,
            "shipments" => $envio,
            "payer" => array(
                "name" => $this->_comprador_nombre,
                "surname" => $this->_comprador_apellido
            ),
            "back_urls" => array(
                "success" => $this->_control_http_https() . '://' . $_SERVER['HTTP_HOST'] . $this->_url_control_pago . '?codigo_control=' . $this->_codigo_control . '&cc=exito',
                "failure" => $this->_control_http_https() . '://' . $_SERVER['HTTP_HOST'] . $this->_url_control_pago . '?codigo_control=' . $this->_codigo_control . '&cc=fracaso',
                "pending" => $this->_control_http_https() . '://' . $_SERVER['HTTP_HOST'] . $this->_url_control_pago . '?codigo_control=' . $this->_codigo_control . '&cc=pendiente'
            ),
            "notification_url" => $this->_control_http_https() . '://' . $_SERVER['HTTP_HOST'] . $this->_url_control_pago,
            "external_reference" => $this->_id_operacion
        );

        $preference = $mp->create_preference($preference_data);

        if ($this->_imprimir_datos === true) {
            print_r($preference);
            exit;
        }

        header('Location: ' . $preference["response"]["init_point"]);
        exit;
    }

    // DineroMail

    private function _dineroMail() { // _metodo_pago = dm (Ahora PayU)
        // https://ar.dineromail.com/biblioteca
        $datos_obligatorios = array(
            'id_cliente',
            'id_cuenta',
            'codigo_de_seguridad',
            'comprador_email',
            'id_del_item_total',
            'tipo_de_moneda',
            'precio_total',
            'url_control_pago'
        );
        $this->_controlDatos($datos_obligatorios);

        $mensaje = '';

        if ($this->_tipo_de_moneda == '') {
            $this->_tipo_de_moneda = 1;
        }
        /*
          ISO_4217
          1	ARS (pesos argentinos)
          2	USD (dolares EEUU)
          3	BRL (reales)
          4	MXN (meso mexicano)
          5	VEF (bolivar fuerte)
          6     CLP (Peso Chileno)
          7     COP (Peso Colombiano)
         */
        $tipo_de_moneda = array(
            1 => 'ARS',
            2 => 'USD',
            3 => 'BRL',
            4 => 'MXN',
            5 => 'VEF',
            6 => 'CLP',
            7 => 'COP',
        );
        $tipo_de_moneda = $tipo_de_moneda[$this->_tipo_de_moneda];

        if ($mensaje != '') {
            echo $mensaje;
            exit;
        }

        $descripcion = '';
        foreach ($this->obtenerArray() as $producto) {
            $descripcion .= "\n" . $producto['nombre'];
        }

        if ($this->_id_operacion == '') {
            $this->_id_operacion = $this->_codigo_control;
        }

        $firma = sha1($this->_codigo_de_seguridad . '-' . $this->_id_cliente . '-' . $this->_id_operacion . '-' . $this->_precio_total . '-' . $tipo_de_moneda);

        $form = '
            <form method="post" action="https://sandbox.gateway.payulatam.com/ppp-web-gateway/">
            <input name="merchantId"    type="hidden"  value="' . $this->_id_cliente . '">
            <input name="accountId"     type="hidden"  value="' . $this->_id_cuenta . '">
            <input name="description"   type="hidden"  value="' . $descripcion . '">
            <input name="referenceCode" type="hidden"  value="' . $this->_id_operacion . '">
            <input name="amount"        type="hidden"  value="' . $this->_precio_total . '">
            <input name="tax"           type="hidden"  value="0">
            <input name="taxReturnBase" type="hidden"  value="0">
            <input name="currency"      type="hidden"  value="' . $tipo_de_moneda . '">
            <input name="signature"     type="hidden"  value="' . $firma . '">
            <input name="buyerEmail"    type="hidden"  value="' . $this->_comprador_email . '">
            <input name="responseUrl"    type="hidden"  value="' . $this->_control_http_https() . '://' . $_SERVER['HTTP_HOST'] . $this->_url_control_pago . '?codigo_control=' . $this->_codigo_control . '&cc=fracaso">
            <input name="confirmationUrl"    type="hidden"  value="' . $this->_control_http_https() . '://' . $_SERVER['HTTP_HOST'] . $this->_url_control_pago . '?codigo_control=' . $this->_codigo_control . '&cc=exito">
            <input name="Submit"        type="submit"  value="Enviar" >
            </form>
        ';

        if ($this->_imprimir_datos === true) {

            exit($form);
        }

        // armado del formulario
        $formulario = '<!DOCTYPE html>' . "\n";
        $formulario .= '<html>' . "\n";
        $formulario .= '   <head>' . "\n";
        $formulario .= '       <meta charset="UTF-8">' . "\n";
        $formulario .= '   </head>' . "\n";
        $formulario .= '   <body>' . "\n";
        $formulario .= $form;
        $formulario .= '      <script language="JavaScript">document.forms[0].submit();</script>' . "\n";
        $formulario .= '   </body>' . "\n";
        $formulario .= '</html>' . "\n";

        echo $formulario;

        return $form;
    }

    // Decidir

    private function _decidir() { // _metodo_pago = de
        // https://ar.dineromail.com/biblioteca
        $datos_obligatorios = array(
            'id_cliente',
            'precio_total',
            'cantidad_de_cuotas',
            'medios_de_pagos',
            'url_control_pago'
        );
        $this->_controlDatos($datos_obligatorios);

        // $this->_id_cliente; // Alfanumérico de 8 caracteres.
        // $this->_id_operacion; // Alfanumérico de 1 a 20 caracteres.
        // $this->_medios_de_pagos; // (*) 1, seleccionando solo uno
        // $this->_precio_total; // (*), ejemplo $125,38 -> 12538
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
        if (!isset($medios_de_pagos)) {
            $mensaje .= "El siguiente valor se encuentra mal definido en Decidir | medios_de_pagos()\n";
        }

        if ($mensaje != '') {
            echo $mensaje;
            exit;
        }

        $precio_total = round($this->_precio_total * 100);

        if ($this->_id_operacion == '') {
            $this->_id_operacion = $this->_codigo_control;
        }

        $form = '
            <form action="https://sps.decidir.com/sps-ar/Validar" method="post">
            <input type="hidden" name="NROCOMERCIO" value="' . $this->_id_cliente . '"/>
            <input type="hidden" name="NROOPERACION" value="' . $this->_id_operacion . '"/>
            <input type="hidden" name="MEDIODEPAGO" value="' . $medios_de_pagos . '"/>
            <input type="hidden" name="MONTO" value="' . $precio_total . '"/>
            <input type="hidden" name="CUOTAS" value="' . $this->_cantidad_de_cuotas . '"/>
            <input type="hidden" name="URLDINAMICA" value="' . $this->_control_http_https() . '://' . $_SERVER['HTTP_HOST'] . $this->_url_control_pago . '?codigo_control=' . $this->_codigo_control . '&cc=informacion"/>
            <input type="hidden" name="EMAILCLIENTE" value="' . $this->_comprador_email . '"/>
            <input type="hidden" name="PARAMSITIO" value=""/>
            </form>
        ';

        if ($this->_imprimir_datos === true) {

            exit($form);
        }

        // armado del formulario
        $formulario = '<!DOCTYPE html>' . "\n";
        $formulario .= '<html>' . "\n";
        $formulario .= '   <head>' . "\n";
        $formulario .= '       <meta charset="UTF-8">' . "\n";
        $formulario .= '   </head>' . "\n";
        $formulario .= '   <body>' . "\n";
        $formulario .= $form;
        $formulario .= '      <script language="JavaScript">document.forms[0].submit();</script>' . "\n";
        $formulario .= '   </body>' . "\n";
        $formulario .= '</html>' . "\n";

        echo $formulario;
    }

    private function _decidir_informacion() {

        if (isset($_POST) && ($_POST['resultado'] == 'APROBADA')) {
            return true;
        } elseif (isset($_POST) && ($_POST['resultado'] == 'APROBADA')) {
            return false;
        }

        return false;
    }

//=== COSTO ENVIO ===
    // MercadoPago

    private function _mercadoPagoCostoEnvio() {

        /* valores límites */

        $limite_alto = 70;
        $limite_ancho = 70;
        $limite_largo = 70;
        $limite_peso = 25000;

        /* valores límites */

        $array_productos = $this->obtenerArray();

        if (is_array($array_productos)) {

            $datos_obligatorios = array(
                'id_cliente',
                'codigo_de_seguridad'
            );
            $this->_controlDatos($datos_obligatorios);

            include_once(VariableGet::sistema('directorio_clases') . '/CarritoDeCompras/mercadopago.php');

            $mp = new MP($this->_id_cliente, $this->_codigo_de_seguridad);

            $alto = 0;
            $ancho = 0;
            $largo = 0;
            $peso = 0;
            $precio = 0;

            foreach ($this->obtenerArray() as $producto) {
                $alto += round((int) $producto['alto'], 0);
                $ancho += round((int) $producto['ancho'], 0);
                $largo += round((int) $producto['largo'], 0);
                $peso += round((int) $producto['peso'], 0);
                $precio += ((int) $producto['cantidad'] * (int) $producto['precio']);
            }

            if (
                    ($alto > $limite_alto) ||
                    ($ancho > $limite_ancho) ||
                    ($largo > $limite_largo) ||
                    ($peso > $limite_peso)
            ) {
                return array('200', '');
            }

            $params = array(
                "dimensions" => (string) $alto . 'x' . $ancho . 'x' . $largo . ',' . $peso,
                "zip_code" => (string) $this->_codigo_postal,
                "item_price" => number_format($precio, 2, '.', ''),
                "free_method" => "73328"
            );

            $response = $mp->get("/shipping_options", $params);

            if ($response == '') {
                return array('400', '');
            }

            $respuesta = false;

            if (is_array($response) && isset($response['response']['options'])) {
                $shipping_options = $response['response']['options'];

                foreach ($shipping_options as $shipping_option) {

                    $value = $shipping_option['shipping_method_id'];
                    $name = $shipping_option['name'];
                    $checked = $shipping_option['display'] == "recommended" ? "checked='checked'" : "";

                    $shipping_speed = $shipping_option['estimated_delivery_time']['shipping'];
                    $estimated_delivery = $shipping_speed < 24 ? 1 : ceil($shipping_speed / 24); //from departure, estimated delivery time

                    $cost = $shipping_option['cost'];
                    $cost = $cost == 0 ? "sin costo" : "$ $cost";

                    $respuesta[] = array(
                        'tipo_envio' => $name,
                        'tiempo_entrega_dias' => $estimated_delivery,
                        'costo' => $cost
                    );
                }
            }

            return array('100', $respuesta);
        } else {

            return array('300', '');
        }
    }

    private static function _control_http_https() {

        $http = 'http';
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
            $http = 'https';
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
            $http = 'https';
        }

        return $http;
    }

    private function _obtenerDirectorio($directorio) {

        $url_actual = getcwd();
        chdir($directorio);
        $directorio = getcwd();
        chdir($url_actual);

        return $directorio;
    }

}
