<?php

class ObtenerMime {

    public static function obtener($nombre) {

        if (function_exists('mime_content_type')) {

            return mime_content_type($nombre);
        } else {

            $extension = strtolower(array_pop(explode(".", $nombre)));

            switch ($extension) {

                // sccripts
                case "txt" :
                    return "text/plain";
                case "css" :
                    return "text/css";
                case "xml" :
                    return "application/xml";
                case "js" :
                    return "application/x-javascript";
                case "json" :
                    return "application/json";
                case "html" :
                case "htm" :
                case "php" :
                    return "text/html";

                // imagenes
                case "jpg" :
                case "jpeg" :
                case "jpe" :
                    return "image/jpg";
                case "png" :
                case "gif" :
                case "bmp" :
                case "tif" :
                case "tiff" :
                    return "image/" . $extension;
                case "ico" :
                    return "image/vnd.microsoft.icon";
                case "svg" :
                case "svgz" :
                    return "image/svg+xml";

                // ms office
                case "doc" :
                case "docx" :
                    return "application/msword";
                case "xls" :
                case "xlt" :
                case "xlm" :
                case "xld" :
                case "xla" :
                case "xlc" :
                case "xlw" :
                case "xll" :
                    return "application/vnd.ms-excel";
                case "ppt" :
                case "pps" :
                    return "application/vnd.ms-powerpoint";
                case "rtf" :
                    return "application/rtf";

                // open office
                case "odt" :
                    return "application/vnd.oasis.opendocument.text";
                case "ods" :
                    return "application/vnd.oasis.opendocument.spreadsheet";

                // adobe
                case "pdf" :
                    return "application/pdf";
                case "psd" :
                    return "vnd.adobe.photoshop";
                case "ai" :
                case "eps" :
                case "ps" :
                    return "application/postscript";

                // video
                case "mpeg" :
                case "mpg" :
                case "mpe" :
                    return "video/mpeg";
                case "at" :
                case "mov" :
                    return "video/quicktime";
                case "avi" :
                    return "video/msvideo";
                case "wmv" :
                    return "video/x-ms-wmv";
                case "flv" :
                    return "video/x-flv";
                case "swf" :
                    return "application/x-shockwave-flash";

                // sonido
                case "mp3" :
                    return "audio/mpeg3";
                case "wav" :
                    return "audio/wav";
                case "aiff" :
                case "aif" :
                    return "audio/aiff";

                // compresion y archivos
                case "zip" :
                    return "application/zip";
                case "tar" :
                    return "application/x-tar";
                case "rar" :
                    return "application/x-rar-compressed";
                case "msi" :
                    return "application/x-msdownload";
                case "cab" :
                    return "application/vnd.ms-cab-compressed";
                case "exe" :
                    return "application/x-msdownload";
            }
        }
    }

}
