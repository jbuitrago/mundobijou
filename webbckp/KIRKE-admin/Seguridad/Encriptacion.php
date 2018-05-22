<?php

/* vim: set expandtab shiftwidth=4 softtabstop=4 tabstop=4: */

/**
 * RC4Crypt 3.2
 *
 * RC4Crypt is a petite library that allows you to use RC4
 * encryption easily in PHP. It's OO and can produce outputs
 * in binary and hex.
 *
 * (C) Copyright 2006 Mukul Sabharwal [http://mjsabby.com]
 *     All Rights Reserved
 *
 * @link http://rc4crypt.devhome.org
 * @author Mukul Sabharwal <mjsabby@gmail.com>
 * @version $Id: class.rc4crypt.php,v 3.2 2006/03/10 05:47:24 mukul Exp $
 * @copyright Copyright &copy; 2006 Mukul Sabharwal
 * @license http://www.gnu.org/copyleft/gpl.html
 * @package Seguridad
 * @subpackage Seguridad
 */
class Seguridad_Encriptacion {

    /**
     * The symmetric encryption function
     *
     * @param string $pwd Key to encrypt with (can be binary of hex)
     * @param string $data Content to be encrypted
     * @param bool $ispwdHex Key passed is in hexadecimal or not
     * @access public
     * @return string
     */
    static private function _encrypt($data) {

        $ispwdHex = 1;
        $pwd = session_id();

        if ($ispwdHex)
            $pwd = @pack('H*', $pwd); // valid input, please!

        $key[] = '';
        $box[] = '';
        $cipher = '';

        $pwd_length = strlen($pwd);
        $data_length = strlen($data);

        for ($i = 0; $i < 256; $i++) {

            $key[$i] = ord($pwd[$i % $pwd_length]);
            $box[$i] = $i;
        }
        for ($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $key[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }
        for ($a = $j = $i = 0; $i < $data_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $k = $box[(($box[$a] + $box[$j]) % 256)];
            $cipher .= chr(ord($data[$i]) ^ $k);
        }
        return $cipher;
    }

    /**
     * Decryption, recall encryption
     *
     * @param string $pwd Key to decrypt with (can be binary of hex)
     * @param string $data Content to be decrypted
     * @param bool $ispwdHex Key passed is in hexadecimal or not
     * @access public
     * @return string
     */
    static private function _decrypt($data) {
        return self::_encrypt($data);
    }

    static public function encriptar($data) {
        $encriptado = self::_encrypt($data);
        $encriptado_hexadecimal = unpack('H*', $encriptado);
        // devuelvo el valor en hexadecimal
        return $encriptado_hexadecimal[1];
    }

    static public function desencriptar($data) {
        $encriptado_binario = pack('H*', $data);
        $desencriptado = self::_decrypt($encriptado_binario);
        // devuelvo el valor original
        return $desencriptado;
    }

}
