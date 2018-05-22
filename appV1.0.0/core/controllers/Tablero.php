<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/controllers/my_controller.php';

class Tablero extends MY_Controller {

    var $model = 'roles_model';
    var $folder_view = 'tableros';
    var $controller = 'tablero';
    var $data_view = array();
    var $extra_filter;
    var $id_rol;

    public function __construct() {
        parent::__construct();

        $session_data = $this->session->userdata('logged_in');

        $this->get_rol_usuario();
    }

    public function index() {
        $this->data_view['controller'] = $this->controller;

        $this->data_view['error'] = $this->get_total_error();

        $this->data_view['enviados'] = $this->get_total_enviados();

        $this->data_view['mes'] = $this->get_name_month();

        $this->data_view["leidos_mes"] = $this->get_total_leido_por_mes();

        $this->data_view["error_mes"] = $this->get_total_error_por_mes();

        $this->data_view["enviados_mes"] = $this->get_total_enviados_por_mes();

        $this->data_view["servicios_mes"] = $this->get_grupos_por_mes();

        $this->data_view["envios_stats"] = $this->get_envios_stats();

        $this->data_view["logs"] = $this->get_logs();

        $this->data_view["leidos"] = $this->get_total_leidos();

        $this->data_view['latitudes'] = $this->get_locations();

        $this->data_view['envios'] = $this->get_envios();

        $this->data_view['rebote'] = $this->get_porcentaje_rebote();

        $this->data_view['clicks'] = $this->get_porcentaje_clicks();

        $this->data_view['lecturas'] = $this->get_porcentaje_lecturas();

        $this->data_view['lecturas_unicas'] = $this->get_porcentaje_lecturas_unicas();

        $this->data_view['enviadosp'] = $this->get_porcentaje_envios();

        $this->data_view['total_suscriptores'] = $this->get_total_suscriptores();
        
        $this->data_view['plan'] = $this->get_plan();
        
        $msg['output'] = $this->load->view($this->folder_view . '/' . $this->get_tablero(), $this->data_view, true);

        $this->load->view('dashboard', $msg);
    }

    public function get_extra_filter() {

        switch ($this->id_rol) {
            case 1:

                return '1=1';

                break;
            case 4:
                return '1=1';
                break;
            case 9:
                return '1=1';
                break;
            case 2:
                return "ventas.vendedor_id=" . $this->obj_model->user_id;
                break;
            default:
                break;
        }
    }

    private function get_rol_usuario() {
        $this->id_rol = $this->get_rol($this->obj_model->user_id);
    }

    private function get_tablero() {

        switch ($this->id_rol) {
            case 1:

                return "tablero_admin";

                break;
            case 4:

                return "tablero_admin";

                break;
            case 9:

                return "tablero_admin";

                break;
            case 2:

                return "tablero_admin";

                break;
            default:
                break;
        }
    }

    private function get_rol($id_usuario) {

        $this->db->from("usuarios");

        $this->db->where('id', $id_usuario);

        $query = $this->db->get();

        $row = $query->row();

        return $row->roles_id;
    }

    private function get_data_user($id_usuario) {

        $this->db->from("usuarios");

        $this->db->where('id', $id_usuario);

        $query = $this->db->get();

        $row = $query->row();

        return $row;
    }
    
    private function get_plan() {

        $user = $this->get_data_user($this->obj_model->user_id);
        
        $this->db->from("planes");

        $this->db->where('id', $user->plan_id);

        $query = $this->db->get();

        $row = $query->row();

        return $row->limite;
    }
    
    private function get_name_month() {
        setlocale(LC_ALL, "es_ES");
        $string = date("d/m/Y");
        $date = DateTime::createFromFormat("d/m/Y", $string);
        return strftime("%B", $date->getTimestamp());
    }

    private function get_total_error() {

        $mes_actual = date('m');

        $year_actual = date('Y');

        $this->db->select('SUM(envios.total_rebotados) as monto');
        $this->db->from('envios');
        //$this->db->join('mailinglist', 'envios.id=mailinglist.envio_id');
        $this->db->where('YEAR(envios.fecha_insert)', $year_actual);
        $this->db->where('MONTH(envios.fecha_insert)', $mes_actual);
        $this->db->where('envios.sistema_id', $this->obj_model->sistema_actual);
        //$this->db->where('mailinglist.enviado', 'si');
        //$this->db->where('mailinglist.estado', MAILING_ERROR);
        $this->db->where('envios.borrado', 'no');

        $query = $this->db->get();
        $row = $query->row();

        return $row->monto;
    }

    private function get_total_enviados() {

        $mes_actual = date('m');

        $year_actual = date('Y');

        $this->db->select('SUM(envios.total_enviados) as monto');
        $this->db->from('envios');
        //$this->db->join('mailinglist', 'envios.id=mailinglist.envio_id');
        $this->db->where('YEAR(envios.fecha_insert)', $year_actual);
        $this->db->where('MONTH(envios.fecha_insert)', $mes_actual);
        $this->db->where('envios.sistema_id', $this->obj_model->sistema_actual);
        // $this->db->where('mailinglist.enviado', 'si');
        $this->db->where('envios.borrado', 'no');

        $query = $this->db->get();
        $row = $query->row();

        return $row->monto;
    }

    private function get_total_leidos() {

        $mes_actual = date('m');

        $year_actual = date('Y');

        $this->db->select('SUM(envios.total_leidos) as monto');
        $this->db->from('envios');
        //$this->db->join('mailinglist', 'envios.id=mailinglist.envio_id');
        $this->db->where('YEAR(envios.fecha_insert)', $year_actual);
        $this->db->where('MONTH(envios.fecha_insert)', $mes_actual);
        $this->db->where('envios.sistema_id', $this->obj_model->sistema_actual);
        //$this->db->where('mailinglist.leido', 'si');
        $this->db->where('envios.borrado', 'no');

        $query = $this->db->get();
        $row = $query->row();

        return $row->monto;
    }

    private function get_total_leido_por_mes() {

        $mes_actual = date('m');

        $year_actual = date('Y');

        $this->db->select('MONTH(envios.fecha_insert) AS mes, SUM(envios.total_leidos) AS monto');
        $this->db->from('envios');
        //$this->db->join('mailinglist', 'envios.id=mailinglist.envio_id');
        $this->db->where('YEAR(envios.fecha_insert)', $year_actual);
        $this->db->where('MONTH(envios.fecha_insert)', $mes_actual);
        $this->db->where('envios.sistema_id', $this->obj_model->sistema_actual);
        //$this->db->where('mailinglist.leido', 'si');
        $this->db->where('envios.borrado', 'no');

        $query = $this->db->get();
        return $query->result();
    }

    private function get_total_error_por_mes() {

        $year_actual = date('Y');

        $this->db->select('MONTH(envios.fecha_insert) AS mes, SUM(envios.total_rebotados) AS monto');
        $this->db->from('envios');
        //$this->db->join('mailinglist', 'envios.id=mailinglist.envio_id');
        $this->db->where('YEAR(envios.fecha_insert)', $year_actual);
        $this->db->where('envios.sistema_id', $this->obj_model->sistema_actual);
        //$this->db->where('mailinglist.estado', MAILING_ERROR);
        $this->db->where('envios.borrado', 'no');


        $this->db->group_by("MONTH(envios.fecha_insert)");

        $query = $this->db->get();
        return $query->result();
    }

    private function get_total_enviados_por_mes() {

        $year_actual = date('Y');

        $this->db->select('MONTH(envios.fecha_insert) AS mes, SUM(envios.total_enviados) AS monto');
        $this->db->from('envios');
        //$this->db->join('mailinglist', 'envios.id=mailinglist.envio_id');
        $this->db->where('YEAR(envios.fecha_insert)', $year_actual);
        $this->db->where('envios.sistema_id', $this->obj_model->sistema_actual);
        //$this->db->where('mailinglist.enviado', 'si');
        $this->db->where('envios.borrado', 'no');

        $this->db->group_by("MONTH(envios.fecha_insert)");
        $query = $this->db->get();
        return $query->result();
    }

    private function get_grupos_por_mes() {
        $year_actual = date('Y');

        $this->db->select('grupos.nombre, MONTH(mailinglist.fecha_insert) AS mes, COUNT(*) AS cantidad ');
        $this->db->from('grupos');
        $this->db->join('mailinglist', 'grupos.id=mailinglist.grupo_id');
        $this->db->join('envios', 'envios.id=mailinglist.envio_id');
        $this->db->where('YEAR(mailinglist.fecha_insert)', $year_actual);
        $this->db->where('grupos.borrado', 'no');
        $this->db->where('envios.borrado', 'no');
        $this->db->where('envios.sistema_id', $this->obj_model->sistema_actual);
        $this->db->where('grupos.sistema_id', $this->obj_model->sistema_actual);
        $this->db->where('mailinglist.leido', 'si');

        $this->db->group_by("MONTH(mailinglist.fecha_insert), grupos.id");
        $query = $this->db->get();
        return $query->result();
    }

    private function get_envios_stats() {

        $query = $this->db->query("SELECT e.titulo, (((select count(*) from mailinglist where enviado='si' and envio_id=e.id)*100)/(select count(*) from mailinglist where envio_id=e.id)) as perce "
                . "FROM envios e "
                . "WHERE e.borrado='no' AND e.sistema_id = " . $this->obj_model->sistema_actual . " ORDER BY e.id DESC LIMIT 10");

        return $query->result();
    }

    private function get_logs() {

        $this->db->select('logs.tabla, logs.accion, usuarios.usuario, logs.id_registro, logs.fecha_insert');
        $this->db->from('logs');
        $this->db->join('usuarios', 'usuarios.id = logs.usuario');

        $this->db->where('usuarios.borrado', 'no');

        // $this->extra_filter;

        $this->db->order_by('logs.id', 'desc');

        $this->db->limit(20);

        $query = $this->db->get();
        return $query->result();
    }

    private function get_envios() {

        $this->db->select('envios.fecha_insert, envios.titulo, envios.asunto, envios.fecha_ultimoenvio');
        $this->db->from('envios');
        $this->db->where('envios.borrado', 'no');
        $this->db->where('envios.sistema_id', $this->obj_model->sistema_actual);
        $this->db->order_by('envios.fecha_insert', 'asc');

        $query = $this->db->get();

        return $query->result();
    }

    private function get_locations() {

        $this->db->select('ips.locacion');
        $this->db->from('ips');
        $this->db->join('mailinglist', 'ips.ip = mailinglist.ip');
        $this->db->join('envios', 'envios.id=mailinglist.envio_id');
        $this->db->where('mailinglist.leido', 'si');
        $this->db->where('mailinglist.ip<>""');
        $this->db->where('envios.borrado', 'no');
        $this->db->where('envios.sistema_id', $this->obj_model->sistema_actual);

        $query = $this->db->get();
        return $query->result();
    }

    public function get_porcentaje_rebote() {

        $query = $this->db->query("SELECT TRUNCATE(((SUM(total_rebotados)/SUM(total_enviados))*100),2) AS rebotados FROM envios WHERE sistema_id=" . $this->obj_model->sistema_actual . " AND borrado='no' GROUP BY sistema_id");

        if ($query->num_rows() > 0) {
            $row = $query->row();

            return $row->rebotados;
        }
        return 0;
    }

    public function get_porcentaje_clicks() {

        $query = $this->db->query("SELECT TRUNCATE(((SUM(total_clicks)/SUM(total_enviados))*100),2) AS rebotados FROM envios WHERE sistema_id=" . $this->obj_model->sistema_actual . " AND borrado='no'  GROUP BY sistema_id");

        if ($query->num_rows() > 0) {
            $row = $query->row();

            return $row->rebotados;
        }
        return 0;
    }

    public function get_porcentaje_lecturas() {

        $query = $this->db->query("SELECT TRUNCATE(((SUM(total_leidos)/SUM(total_enviados))*100),2) AS rebotados FROM envios WHERE sistema_id=" . $this->obj_model->sistema_actual . "  AND borrado='no' GROUP BY sistema_id");

        if ($query->num_rows() > 0) {
            $row = $query->row();

            return $row->rebotados;
        }
        return 0;
    }

    public function get_porcentaje_envios() {

        $query = $this->db->query("SELECT TRUNCATE(((SUM(total_envios)/SUM(total_enviados))*100),0) AS rebotados FROM envios WHERE sistema_id=" . $this->obj_model->sistema_actual . " AND borrado='no' GROUP BY sistema_id");

        if ($query->num_rows() > 0) {
            $row = $query->row();

            return $row->rebotados;
        }
        return 0;
    }

    private function get_porcentaje_lecturas_unicas() {

        $q_enviados = "SELECT COUNT(*) as c FROM envios e INNER JOIN mailinglist m ON(e.id=m.envio_id) WHERE m.enviado='si' AND e.sistema_id=" . $this->obj_model->sistema_actual . " AND e.borrado='no' GROUP BY e.sistema_id";

        $q_leidos = "SELECT COUNT(*) as c FROM envios e INNER JOIN mailinglist m ON(e.id=m.envio_id) WHERE m.enviado='si' AND m.leido='si' AND e.sistema_id=" . $this->obj_model->sistema_actual . " AND e.borrado='no' GROUP BY e.sistema_id";


        $query = $this->db->query($q_enviados);

        if ($query->num_rows() > 0) {
            $row = $query->row();

            $total_enviados = $row->c;
        }

        $query2 = $this->db->query($q_leidos);

        if ($query2->num_rows() > 0) {
            $row = $query2->row();

            $total_leidos = $row->c;
        }

        return ($total_leidos / $total_enviados) * 100;
    }

    private function get_total_suscriptores() {
        $q = "SELECT COUNT(*) as c FROM suscriptores WHERE sistema_id=" . $this->obj_model->sistema_actual . " AND borrado='no' AND suscripto=0 GROUP BY sistema_id";


        $query = $this->db->query($q);

        if ($query->num_rows() > 0) {
            $row = $query->row();

            return $row->c;
        }
        return 0;
    }

}
