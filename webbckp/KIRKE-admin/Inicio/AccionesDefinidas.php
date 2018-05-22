<?php

class Inicio_AccionesDefinidas {

    public static function obtener($variable = null) {

        switch ($variable) {
// Registro
            case 45:
                return 'RegistroVer';
                break;
            case 41:
                return 'RegistroListado';
                break;
            case 37:
                return 'RegistroAlta';
                break;
            case 42:
                return 'RegistroModificacion';
                break;
            case 39:
                return 'RegistroAltaPrevia';
                break;
            case 44:
                return 'RegistroModificacionPrevia';
                break;
            case 38:
                return 'RegistroAltaInsercion';
                break;
            case 43:
                return 'RegistroModificacionInsercion';
                break;
            case 40:
                return 'RegistroBaja';
                break;
            case 67:
                return 'RegistroMenuInsercion';
                break;
            case 70:
                return 'RegistroTabuladoresInsercion';
                break;
            case 71:
                return 'RegistroTabuladoresBaja';
                break;
// DatosPersonales
            case 11:
                return 'DatosPersonalesModificacion';
                break;
            case 12:
                return 'DatosPersonalesModificacionInsercion';
                break;
            case 13:
                return 'DatosPersonalesVer';
                break;
// Usuario
            case 54:
                return 'UsuarioAlta';
                break;
            case 55:
                return 'UsuarioAltaInsercion';
                break;
            case 56:
                return 'UsuarioBaja';
                break;
            case 57:
                return 'UsuarioListado';
                break;
            case 58:
                return 'UsuarioModificacion';
                break;
            case 59:
                return 'UsuarioModificacionInsercion';
                break;
            case 60:
                return 'UsuarioVer';
                break;
            case 61:
                return 'UsuarioYClaveModificacion';
                break;
            case 62:
                return 'UsuarioYClaveModificacionInsercion';
                break;
            case 63:
                return 'UsuarioYClaveVer';
                break;
// Rol
            case 46:
                return 'RolAlta';
                break;
            case 47:
                return 'RolAltaInsercion';
                break;
            case 48:
                return 'RolBaja';
                break;
            case 49:
                return 'RolListado';
                break;
            case 50:
                return 'RolModificacion';
                break;
            case 51:
                return 'RolModificacionInsercion';
                break;
            case 52:
                return 'RolVer';
                break;
// Pagina
            case 27:
                return 'PaginaAlta';
                break;
            case 28:
                return 'PaginaAltaInsercion';
                break;
            case 29:
                return 'PaginaBaja';
                break;
            case 30:
                return 'PaginaListado';
                break;
            case 31:
                return 'PaginaModificacion';
                break;
            case 32:
                return 'PaginaModificacionInsercion';
                break;
            case 65:
                return 'PaginaMenu';
                break;  
            case 66:
                return 'PaginaMenuInsercion';
                break;  
            case 68:
                return 'PaginaTabuladores';
                break;  
            case 69:
                return 'PaginaTabuladoresInsercion';
                break;  
            
            
            
            
// Menu
            case 15:
                return 'Menu';
                break;
            case 16:
                return 'MenuInsercion';
                break;
            case 18:
                return 'MenuLinkAlta';
                break;
            case 19:
                return 'MenuLinkAltaInsercion';
                break;
            case 20:
                return 'MenuLinkBaja';
                break;
            case 21:
                return 'MenuLinkListado';
                break;
            case 22:
                return 'MenuLinkParametroAlta';
                break;
            case 23:
                return 'MenuLinkParametroAltaInsercion';
                break;
// Componente
            case 2:
                return 'ComponenteAlta';
                break;
            case 3:
                return 'ComponenteAltaInsercion';
                break;
            case 4:
                return 'ComponenteBaja';
                break;
            case 5:
                return 'ComponenteListado';
                break;
            case 6:
                return 'ComponenteModificacion';
                break;
            case 7:
                return 'ComponenteModificacionInsercion';
                break;
            case 8:
                return 'ConfiguracionPersonalModificacion';
                break;
            case 9:
                return 'ConfiguracionPersonalModificacionInsercion';
                break;
            case 10:
                return 'ConfiguracionPersonalVer';
                break;
// Prefijo
            case 33:
                return 'PrefijoAlta';
                break;
            case 34:
                return 'PrefijoAltaInsercion';
                break;
            case 35:
                return 'PrefijoListado';
                break;
            case 36:
                return 'PrefijoVer';
                break;
// Varios
            case 1:
                return 'Bienvenida';
                break;
            case 14:
                return 'Inicio';
                break;
            case 53:
                return 'Salir';
                break;
            case 64:
                return 'RegistroExportacion';
                break;
            default:
                return 'Inicio';
                
        }
    }

}
