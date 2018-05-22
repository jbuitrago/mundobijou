<?php

class Consultas_EliminarRegistroTmpId {

    static public function armado($id_tmp) {

        Consultas_RegistroEliminar::armado(__FILE__, __LINE__, 'kirke_tmp', 'id_tmp', $id_tmp);

        return true;
    }

}

