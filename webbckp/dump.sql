-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 14-10-2014 a las 12:16:23
-- Versión del servidor: 5.5.38-0ubuntu0.14.04.1
-- Versión de PHP: 5.5.9-1ubuntu4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `kirke`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kirke_componente`
--

CREATE TABLE IF NOT EXISTS `kirke_componente` (
  `id_componente` int(6) NOT NULL AUTO_INCREMENT,
  `id_tabla` int(6) NOT NULL DEFAULT '0',
  `orden` int(6) NOT NULL DEFAULT '0',
  `componente` varchar(30) DEFAULT NULL,
  `tabla_campo` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`id_componente`),
  KEY `id_pagina` (`id_tabla`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kirke_componente_parametro`
--

CREATE TABLE IF NOT EXISTS `kirke_componente_parametro` (
  `id_componente_parametro` int(6) NOT NULL AUTO_INCREMENT,
  `id_componente` int(6) NOT NULL DEFAULT '0',
  `parametro` varchar(30) DEFAULT NULL,
  `valor` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id_componente_parametro`),
  KEY `id_componente` (`id_componente`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kirke_ip_control`
--

CREATE TABLE IF NOT EXISTS `kirke_ip_control` (
  `id_ip_control` int(6) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(6) NOT NULL DEFAULT '0',
  `ip` varchar(20) DEFAULT NULL,
  `condicion` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id_ip_control`),
  KEY `id_usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kirke_log`
--

CREATE TABLE IF NOT EXISTS `kirke_log` (
  `id_log` int(6) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(6) NOT NULL DEFAULT '0',
  `elemento` varchar(100) NOT NULL,
  `pagina` varchar(200) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `hora` int(30) NOT NULL DEFAULT '0',
  `navegador` varchar(100) DEFAULT NULL,
  `ip` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id_log`),
  KEY `usuario` (`id_usuario`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kirke_menu`
--

CREATE TABLE IF NOT EXISTS `kirke_menu` (
  `id_menu` int(6) NOT NULL AUTO_INCREMENT,
  `nivel1_orden` int(3) DEFAULT NULL,
  `nivel2_orden` int(6) DEFAULT NULL,
  `nivel3_orden` int(3) DEFAULT NULL,
  `nivel4_orden` int(3) DEFAULT NULL,
  `habilitado` char(1) DEFAULT NULL,
  PRIMARY KEY (`id_menu`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kirke_menu_link`
--

CREATE TABLE IF NOT EXISTS `kirke_menu_link` (
  `id_menu_link` int(6) NOT NULL AUTO_INCREMENT,
  `id_menu` int(6) NOT NULL,
  `id_elemento` varchar(200) NOT NULL,
  `elemento` varchar(100) NOT NULL,
  `orden` int(6) NOT NULL,
  `habilitado` char(1) DEFAULT NULL,
  PRIMARY KEY (`id_menu_link`),
  KEY `id_menu` (`id_menu`,`id_elemento`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kirke_menu_link_nombre`
--

CREATE TABLE IF NOT EXISTS `kirke_menu_link_nombre` (
  `id_menu_link_nombre` int(6) NOT NULL AUTO_INCREMENT,
  `id_menu_link` int(6) NOT NULL,
  `menu_link_nombre` varchar(30) DEFAULT NULL,
  `idioma_codigo` char(2) DEFAULT NULL,
  PRIMARY KEY (`id_menu_link_nombre`),
  KEY `id_menu_link` (`id_menu_link`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kirke_menu_link_parametro`
--

CREATE TABLE IF NOT EXISTS `kirke_menu_link_parametro` (
  `id_menu_link_parametro` int(6) NOT NULL AUTO_INCREMENT,
  `id_menu_link` int(6) NOT NULL,
  `tipo` varchar(30) DEFAULT NULL,
  `id` int(6) DEFAULT NULL,
  `parametro` varchar(30) DEFAULT NULL,
  `valor` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id_menu_link_parametro`),
  KEY `id_menu_link` (`id_menu_link`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kirke_menu_nombre`
--

CREATE TABLE IF NOT EXISTS `kirke_menu_nombre` (
  `id_menu_nombre` int(6) NOT NULL AUTO_INCREMENT,
  `id_menu` int(6) NOT NULL DEFAULT '0',
  `menu_nombre` varchar(30) DEFAULT NULL,
  `idioma_codigo` char(2) DEFAULT NULL,
  PRIMARY KEY (`id_menu_nombre`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kirke_rol`
--

CREATE TABLE IF NOT EXISTS `kirke_rol` (
  `id_rol` int(6) NOT NULL AUTO_INCREMENT,
  `orden` int(6) NOT NULL,
  `rol` varchar(100) DEFAULT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_rol`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000 ;

--
-- Volcado de datos para la tabla `kirke_rol`
--

INSERT INTO `kirke_rol` (`id_rol`, `orden`, `rol`, `descripcion`) VALUES
(1, 0, '{TR|o_administrador_general}', '{TR|o_administrador_general_descripcion}'),
(2, 0, '{TR|o_administrador_usuarios}', '{TR|o_administrador_usuarios_descripcion}');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kirke_rol_detalle`
--

CREATE TABLE IF NOT EXISTS `kirke_rol_detalle` (
  `id_rol_detalle` int(6) NOT NULL AUTO_INCREMENT,
  `id_rol` int(6) NOT NULL,
  `elemento` varchar(100) DEFAULT NULL,
  `id_elemento` varchar(200) DEFAULT NULL,
  `permiso` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_rol_detalle`),
  KEY `id_rol` (`id_rol`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000 ;

--
-- Volcado de datos para la tabla `kirke_rol_detalle`
--

INSERT INTO `kirke_rol_detalle` (`id_rol_detalle`, `id_rol`, `elemento`, `id_elemento`, `permiso`) VALUES
(1, 1, NULL, NULL, NULL),
(2, 2, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kirke_tabla`
--

CREATE TABLE IF NOT EXISTS `kirke_tabla` (
  `id_tabla` int(6) NOT NULL AUTO_INCREMENT,
  `id_tabla_prefijo` int(6) NOT NULL DEFAULT '0',
  `orden` int(6) NOT NULL,
  `tabla_nombre` varchar(30) DEFAULT NULL,
  `habilitado` char(1) DEFAULT NULL,
  `tipo` varchar(30) DEFAULT NULL,
  `plantilla` longtext,
  PRIMARY KEY (`id_tabla`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kirke_tabla_nombre_idioma`
--

CREATE TABLE IF NOT EXISTS `kirke_tabla_nombre_idioma` (
  `id_tabla_nombre_idioma` int(6) NOT NULL AUTO_INCREMENT,
  `id_tabla` int(6) NOT NULL DEFAULT '0',
  `idioma_codigo` char(2) DEFAULT NULL,
  `tabla_nombre_idioma` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id_tabla_nombre_idioma`),
  KEY `id_idioma` (`idioma_codigo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kirke_tabla_parametro`
--

CREATE TABLE IF NOT EXISTS `kirke_tabla_parametro` (
  `id_tabla_parametro` int(6) NOT NULL AUTO_INCREMENT,
  `id_tabla` int(6) NOT NULL,
  `tipo` varchar(30) DEFAULT NULL,
  `parametro` varchar(30) DEFAULT NULL,
  `valor` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id_tabla_parametro`),
  KEY `id_tabla` (`id_tabla`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kirke_tabla_prefijo`
--

CREATE TABLE IF NOT EXISTS `kirke_tabla_prefijo` (
  `id_tabla_prefijo` int(6) NOT NULL AUTO_INCREMENT,
  `orden` int(6) NOT NULL,
  `prefijo` varchar(6) DEFAULT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_tabla_prefijo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kirke_tmp`
--

CREATE TABLE IF NOT EXISTS `kirke_tmp` (
  `id_tmp` int(6) NOT NULL AUTO_INCREMENT,
  `fecha` int(20) NOT NULL,
  `id_componente` int(6) NOT NULL,
  `contenido` longtext,
  PRIMARY KEY (`id_tmp`),
  KEY `id_componente` (`id_componente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kirke_usuario`
--

CREATE TABLE IF NOT EXISTS `kirke_usuario` (
  `id_usuario` int(6) NOT NULL AUTO_INCREMENT,
  `orden` int(6) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `apellido` varchar(50) DEFAULT NULL,
  `usuario` varchar(20) DEFAULT NULL,
  `clave` varchar(32) DEFAULT NULL,
  `mail` varchar(50) DEFAULT NULL,
  `telefono` varchar(30) DEFAULT NULL,
  `habilitado` char(1) DEFAULT NULL,
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000 ;

--
-- Volcado de datos para la tabla `kirke_usuario`
--

INSERT INTO `kirke_usuario` (`id_usuario`, `orden`, `nombre`, `apellido`, `usuario`, `clave`, `mail`, `telefono`, `habilitado`) VALUES
(1, 1, 'kirke', 'kirke', 'kirke', 'e4afca97a13bd913ad0627a5ffc48d01', 'kirke@kirke.ws', '', 's');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kirke_usuario_atributo`
--

CREATE TABLE IF NOT EXISTS `kirke_usuario_atributo` (
  `id_usuario_atributo` int(6) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(6) NOT NULL,
  `atributo_nombre` varchar(50) DEFAULT NULL,
  `atributo_valor` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_usuario_atributo`),
  KEY `id_usuario` (`id_usuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000 ;

--
-- Volcado de datos para la tabla `kirke_usuario_atributo`
--

INSERT INTO `kirke_usuario_atributo` (`id_usuario_atributo`, `id_usuario`, `atributo_nombre`, `atributo_valor`) VALUES
(5, 1, 'idioma', 'idioma'),
(6, 1, 'plantilla', 'kirke');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kirke_usuario_rol`
--

CREATE TABLE IF NOT EXISTS `kirke_usuario_rol` (
  `id_usuario_rol` int(6) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(6) NOT NULL DEFAULT '0',
  `id_rol` int(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_usuario_rol`),
  KEY `id_usuario` (`id_usuario`,`id_rol`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000 ;

--
-- Volcado de datos para la tabla `kirke_usuario_rol`
--

INSERT INTO `kirke_usuario_rol` (`id_usuario_rol`, `id_usuario`, `id_rol`) VALUES
(1, 1, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
