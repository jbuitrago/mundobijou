<?php

class Estadisticas extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {

        $mes_actual = date("m");

        $year = date("Y");

        $msg['ventas_mes_actual'] = $this->get_ventas_mes_actual($mes_actual, $year);

        $msg['ventas_por_mes'] = $this->get_ventas_por_mes($year);

        $msg['productos_mas_venidos'] = $this->get_productos_mas_vendidos();

        $msg['productos_con_stock_bajo'] = $this->productos_con_stock_bajo();

        $msg['total_productos'] = $this->total_productos();

        $msg['participacion_vendedores_vendido'] = $this->get_participacion_vendedores_vendido();

        $msg['participacion_vendedores_cobrado'] = $this->get_participacion_vendedores_cobrado();

        $msg['a_cobrar'] = $this->get_proximos_cobros();

        $msg['output'] = $this->load->view('estadisticas/view', $msg, true);

        $this->load->view('dashboard', $msg);
    }

    private function get_ventas_mes_actual($mes, $year) {

        $this->db->select('DAY(fecha) as dia, sum(monto_total) as monto');
        $this->db->from('ventas');
        $this->db->where('YEAR(fecha)', $year);
        $this->db->where('MONTH(fecha)', $mes);
        $this->db->where('ventas.borrado', 'no');
        
        if (!empty(isset($_POST['fechaInicio'])) && ($_POST['fechaInicio'] != '')) {
            $this->db->where('ventas.fecha>="' . $_POST['fechaInicio'] . '"');
        }
        if (!empty(isset($_POST['fechaFin'])) && ($_POST['fechaFin'] != '')) {
            $this->db->where('ventas.fecha<="' . $_POST['fechaFin'] . '"');
        }
        
        $this->db->group_by("DAY(fecha)");
        $query = $this->db->get();
        return $query->result();
    }

    private function get_ventas_por_mes($year) {

        $this->db->select('MONTH(fecha) as mes, sum(monto_total) as monto');
        $this->db->from('ventas');
        $this->db->where('YEAR(fecha)', $year);
        $this->db->where('ventas.borrado', 'no');
        
        if (!empty(isset($_POST['fechaInicio'])) && ($_POST['fechaInicio'] != '')) {
            $this->db->where('ventas.fecha>="' . $_POST['fechaInicio'] . '"');
        }
        if (!empty(isset($_POST['fechaFin'])) && ($_POST['fechaFin'] != '')) {
            $this->db->where('ventas.fecha<="' . $_POST['fechaFin'] . '"');
        }
        
        $this->db->group_by("MONTH(fecha)");
        $query = $this->db->get();
        return $query->result();
    }

    private function get_productos_mas_vendidos() {

        $this->db->select('productos.nombre, COUNT(*) as cant,');
        $this->db->from('ventas');
        $this->db->join('productos', 'productos.id = ventas.producto_id');

        $this->db->where('ventas.borrado', 'no');
        $this->db->where('productos.borrado', 'no');
        
        if (!empty(isset($_POST['fechaInicio'])) && ($_POST['fechaInicio'] != '')) {
            $this->db->where('ventas.fecha>="' . $_POST['fechaInicio'] . '"');
        }
        if (!empty(isset($_POST['fechaFin'])) && ($_POST['fechaFin'] != '')) {
            $this->db->where('ventas.fecha<="' . $_POST['fechaFin'] . '"');
        }
        
        $this->db->group_by("ventas.producto_id");
        $query = $this->db->get();
        return $query->result();
    }

    private function productos_con_stock_bajo() {

        $this->db->select('productos.id');
        $this->db->from('productos');
        $this->db->where('productos.stock <', 'productos.stock_minimo');
        $this->db->where('productos.borrado', 'no');

        $query = $this->db->get();
        return $query->num_rows();
    }

    private function total_productos() {

        $this->db->select('productos.id');
        $this->db->from('productos');
        $this->db->where('productos.borrado', 'no');
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function get_participacion_vendedores_vendido() {

        $this->db->select('usuarios.usuario, SUM(ventas.monto_total) AS total');
        $this->db->from('ventas');
        $this->db->join('usuarios', 'usuarios.id = ventas.vendedor_id');

        $this->db->where('ventas.borrado', 'no');
        $this->db->where('usuarios.borrado', 'no');
        $this->db->group_by("usuarios.usuario");
        
        if (!empty(isset($_POST['fechaInicio'])) && ($_POST['fechaInicio'] != '')) {
            $this->db->where('ventas.fecha>="' . $_POST['fechaInicio'] . '"');
        }
        if (!empty(isset($_POST['fechaFin'])) && ($_POST['fechaFin'] != '')) {
            $this->db->where('ventas.fecha<="' . $_POST['fechaFin'] . '"');
        }

        $query = $this->db->get();

        return $query->result();
    }

    public function get_participacion_vendedores_cobrado() {

        $this->db->select('usuarios.usuario, SUM(pagos.monto_pagado) AS total');
        $this->db->from('pagos');
        $this->db->join('ventas', 'ventas.id = pagos.venta_id');
        $this->db->join('usuarios', 'usuarios.id = ventas.vendedor_id');
        $this->db->where('pagos.borrado', 'no');
        $this->db->where('ventas.borrado', 'no');
        $this->db->where('usuarios.borrado', 'no');
        $this->db->group_by("usuarios.usuario");

        if (!empty(isset($_POST['fechaInicio'])) && ($_POST['fechaInicio'] != '')) {
            $this->db->where('ventas.fecha>="' . $_POST['fechaInicio'] . '"');
        }
        if (!empty(isset($_POST['fechaFin'])) && ($_POST['fechaFin'] != '')) {
            $this->db->where('ventas.fecha<="' . $_POST['fechaFin'] . '"');
        }

        $query = $this->db->get();

        return $query->result();
    }

    public function get_proximos_cobros() {
        $this->db->select('EXTRACT(MONTH FROM agenda_pagos.fecha) as fecha, sum( agenda_pagos.monto_a_pagar ) AS m', FALSE);
        $this->db->from('agenda_pagos');
        $this->db->join('ventas', 'ventas.id = agenda_pagos.venta_id');

        if (!empty(isset($_POST['fechaInicio'])) && ($_POST['fechaInicio'] != '')) {
            $this->db->where('agenda_pagos.fecha>="' . $_POST['fechaInicio'] . '"');
        }
        if (!empty(isset($_POST['fechaFin'])) && ($_POST['fechaFin'] != '')) {
            $this->db->where('agenda_pagos.fecha<="' . $_POST['fechaFin'] . '"');
        }

        $this->db->where('ventas.borrado', 'no');
        $this->db->where('agenda_pagos.monto_a_pagar - agenda_pagos.monto_pagado>', 0, FAlSE);
        $this->db->group_by('EXTRACT(YEAR_MONTH FROM agenda_pagos.fecha)');

        $query = $this->db->get();
        return $query->result();
    }

}

?>