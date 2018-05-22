<?php

class DesarrollosSistema_Modificadores {

    public function modificadores($valor, $modificadores = null, $exportacion = false) {

        if ($modificadores != '') {

            if (is_array($modificadores)) {

                foreach ($modificadores as $modificador) {
                    if (($modificador[0] == 'truncate') && isset($modificador[1]) && ($exportacion == false)) {
                        $largo = $modificador[1];
                        if (strlen($valor) > $largo) {
                            if (isset($modificador[2])) {
                                $final = $modificador[2];
                            } else {
                                $final = '';
                            }
                            $valor = mb_substr($valor, 0, $largo);
                            if (isset($modificador[3]) && ($modificador[3] == 'true')) {
                                $valor = mb_substr($valor, 0, -(mb_strlen($valor) - mb_strrpos($valor, ' ')));
                            }
                            $valor .= $final;
                        }
                    } elseif ($modificador[0] == 'nl2br') {
                        $valor = nl2br($valor);
                    } elseif (($modificador[0] == 'date_format') && isset($modificador[1])) {
                        $valor = date($modificador[1], $valor);
                    } elseif ($modificador[0] == 'number_format') {
                        if (!isset($modificador[1])) {
                            $modificador[1] = 0;
                        }
                        if (!isset($modificador[2])) {
                            $modificador[2] = '.';
                        }
                        if (!isset($modificador[3])) {
                            $modificador[3] = ',';
                        }
                        $valor = number_format($valor, $modificador[1], $modificador[2], $modificador[3]);
                    } elseif ($modificador[0] == 'upper') {
                        $valor = strtoupper($valor);
                    } elseif ($modificador[0] == 'lower') {
                        $valor = strtolower($valor);
                    } elseif ($modificador[0] == 'capitalize') {
                        $valor = ucfirst($valor);
                    } elseif (($modificador[0] == 'truncate_p') && isset($modificador[1]) && ($exportacion == false)) {
                        $parrafos = preg_split("/(<\/[p|P]\s*\/?>)|(<\s*br\s*\/?>\s*<\s*br\s*\/?>)/", $valor);
                        $parrafos = preg_replace("/<[p|P](.*?)>/", "", $parrafos);
                        $parrafos = array_slice($parrafos, 0, $modificador[1]);
                        $valor = implode('<br /><br />', $parrafos);
                    } elseif ($modificador[0] == 'strip_tags') {
                        if (!isset($modificador[1])) {
                            $valor = strip_tags($valor);
                        } else {
                            $valor = strip_tags($valor, $modificador[1]);
                        }
                    } elseif (($modificador[0] == 'default') && isset($modificador[1])) {
                        if (!isset($valor) || (trim($valor) == '')) {
                            $valor = $modificador[1];
                        }
                    } elseif (($modificador[0] == 'regex_replace') && isset($modificador[1]) && isset($modificador[2])) {
                        $valor = preg_replace($modificador[1], $modificador[2], $valor);
                    } elseif (($modificador[0] == 'replace') && isset($modificador[1]) && isset($modificador[2])) {
                        $valor = str_replace($modificador[1], $modificador[2], $valor);
                    } elseif (($modificador[0] == 'string_format') && isset($modificador[1])) {
                        $valor = sprintf($modificador[1], $valor);
                    } elseif (($modificador[0] == 'escape') && isset($modificador[1])) {
                        switch ($modificador[1]) {
                            case 'html':
                                $valor = html_entity_decode($valor);
                                break;
                            case 'htmlall':
                                $valor = htmlspecialchars($valor);
                                break;
                            case 'url':
                                $valor = urlencode($valor);
                                break;
                            case 'quotes':
                                $valor = addslashes($valor);
                                break;
                        }
                    }
                }
            }
        }

        return $valor;
    }

}
