-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u2
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 02-06-2017 a las 12:17:40
-- Versión del servidor: 5.5.55-0+deb8u1
-- Versión de PHP: 5.6.30-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `mundobijou`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kirke_componente`
--

CREATE TABLE IF NOT EXISTS `kirke_componente` (
`id_componente` int(6) NOT NULL,
  `id_tabla` int(6) NOT NULL DEFAULT '0',
  `orden` int(6) NOT NULL DEFAULT '0',
  `componente` varchar(30) DEFAULT NULL,
  `tabla_campo` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kirke_componente_parametro`
--

CREATE TABLE IF NOT EXISTS `kirke_componente_parametro` (
`id_componente_parametro` int(6) NOT NULL,
  `id_componente` int(6) NOT NULL DEFAULT '0',
  `parametro` varchar(30) DEFAULT NULL,
  `valor` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kirke_ip_control`
--

CREATE TABLE IF NOT EXISTS `kirke_ip_control` (
`id_ip_control` int(6) NOT NULL,
  `id_usuario` int(6) NOT NULL DEFAULT '0',
  `ip` varchar(20) DEFAULT NULL,
  `condicion` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kirke_log`
--

CREATE TABLE IF NOT EXISTS `kirke_log` (
`id_log` int(6) NOT NULL,
  `id_usuario` int(6) NOT NULL DEFAULT '0',
  `elemento` varchar(100) NOT NULL,
  `pagina` varchar(200) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `hora` int(30) NOT NULL DEFAULT '0',
  `navegador` varchar(100) DEFAULT NULL,
  `ip` varchar(20) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kirke_menu`
--

CREATE TABLE IF NOT EXISTS `kirke_menu` (
`id_menu` int(6) NOT NULL,
  `nivel1_orden` int(3) DEFAULT NULL,
  `nivel2_orden` int(6) DEFAULT NULL,
  `nivel3_orden` int(3) DEFAULT NULL,
  `nivel4_orden` int(3) DEFAULT NULL,
  `habilitado` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kirke_menu_link`
--

CREATE TABLE IF NOT EXISTS `kirke_menu_link` (
`id_menu_link` int(6) NOT NULL,
  `id_menu` int(6) NOT NULL,
  `id_elemento` varchar(200) NOT NULL,
  `elemento` varchar(100) NOT NULL,
  `orden` int(6) NOT NULL,
  `habilitado` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kirke_menu_link_nombre`
--

CREATE TABLE IF NOT EXISTS `kirke_menu_link_nombre` (
`id_menu_link_nombre` int(6) NOT NULL,
  `id_menu_link` int(6) NOT NULL,
  `menu_link_nombre` varchar(30) DEFAULT NULL,
  `idioma_codigo` char(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kirke_menu_link_parametro`
--

CREATE TABLE IF NOT EXISTS `kirke_menu_link_parametro` (
`id_menu_link_parametro` int(6) NOT NULL,
  `id_menu_link` int(6) NOT NULL,
  `tipo` varchar(30) DEFAULT NULL,
  `id` int(6) DEFAULT NULL,
  `parametro` varchar(30) DEFAULT NULL,
  `valor` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kirke_menu_nombre`
--

CREATE TABLE IF NOT EXISTS `kirke_menu_nombre` (
`id_menu_nombre` int(6) NOT NULL,
  `id_menu` int(6) NOT NULL DEFAULT '0',
  `menu_nombre` varchar(30) DEFAULT NULL,
  `idioma_codigo` char(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kirke_rol`
--

CREATE TABLE IF NOT EXISTS `kirke_rol` (
`id_rol` int(6) NOT NULL,
  `orden` int(6) NOT NULL,
  `rol` varchar(100) DEFAULT NULL,
  `descripcion` varchar(100) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

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
`id_rol_detalle` int(6) NOT NULL,
  `id_rol` int(6) NOT NULL,
  `elemento` varchar(100) DEFAULT NULL,
  `id_elemento` varchar(200) DEFAULT NULL,
  `permiso` varchar(50) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

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
`id_tabla` int(6) NOT NULL,
  `id_tabla_prefijo` int(6) NOT NULL DEFAULT '0',
  `orden` int(6) NOT NULL,
  `tabla_nombre` varchar(30) DEFAULT NULL,
  `habilitado` char(1) DEFAULT NULL,
  `tipo` varchar(30) DEFAULT NULL,
  `plantilla` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kirke_tabla_nombre_idioma`
--

CREATE TABLE IF NOT EXISTS `kirke_tabla_nombre_idioma` (
`id_tabla_nombre_idioma` int(6) NOT NULL,
  `id_tabla` int(6) NOT NULL DEFAULT '0',
  `idioma_codigo` char(2) DEFAULT NULL,
  `tabla_nombre_idioma` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kirke_tabla_parametro`
--

CREATE TABLE IF NOT EXISTS `kirke_tabla_parametro` (
`id_tabla_parametro` int(6) NOT NULL,
  `id_tabla` int(6) NOT NULL,
  `tipo` varchar(30) DEFAULT NULL,
  `parametro` varchar(30) DEFAULT NULL,
  `valor` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kirke_tabla_prefijo`
--

CREATE TABLE IF NOT EXISTS `kirke_tabla_prefijo` (
`id_tabla_prefijo` int(6) NOT NULL,
  `orden` int(6) NOT NULL,
  `prefijo` varchar(6) DEFAULT NULL,
  `descripcion` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kirke_tmp`
--

CREATE TABLE IF NOT EXISTS `kirke_tmp` (
`id_tmp` int(6) NOT NULL,
  `fecha` int(20) NOT NULL,
  `id_componente` int(6) NOT NULL,
  `contenido` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kirke_usuario`
--

CREATE TABLE IF NOT EXISTS `kirke_usuario` (
`id_usuario` int(6) NOT NULL,
  `orden` int(6) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `apellido` varchar(50) DEFAULT NULL,
  `usuario` varchar(20) DEFAULT NULL,
  `clave` varchar(32) DEFAULT NULL,
  `mail` varchar(50) DEFAULT NULL,
  `telefono` varchar(30) DEFAULT NULL,
  `habilitado` char(1) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `kirke_usuario`
--

INSERT INTO `kirke_usuario` (`id_usuario`, `orden`, `nombre`, `apellido`, `usuario`, `clave`, `mail`, `telefono`, `habilitado`) VALUES
(1, 1, 'kirke', 'kirke', 'mundobijou_dev', '3cebeb8760b86404c3bae36d118158fa', 'kirke@kirke.ws', '', 's');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kirke_usuario_atributo`
--

CREATE TABLE IF NOT EXISTS `kirke_usuario_atributo` (
`id_usuario_atributo` int(6) NOT NULL,
  `id_usuario` int(6) NOT NULL,
  `atributo_nombre` varchar(50) DEFAULT NULL,
  `atributo_valor` varchar(50) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

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
`id_usuario_rol` int(6) NOT NULL,
  `id_usuario` int(6) NOT NULL DEFAULT '0',
  `id_rol` int(6) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `kirke_usuario_rol`
--

INSERT INTO `kirke_usuario_rol` (`id_usuario_rol`, `id_usuario`, `id_rol`) VALUES
(1, 1, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `kirke_componente`
--
ALTER TABLE `kirke_componente`
 ADD PRIMARY KEY (`id_componente`), ADD KEY `id_pagina` (`id_tabla`);

--
-- Indices de la tabla `kirke_componente_parametro`
--
ALTER TABLE `kirke_componente_parametro`
 ADD PRIMARY KEY (`id_componente_parametro`), ADD KEY `id_componente` (`id_componente`);

--
-- Indices de la tabla `kirke_ip_control`
--
ALTER TABLE `kirke_ip_control`
 ADD PRIMARY KEY (`id_ip_control`), ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `kirke_log`
--
ALTER TABLE `kirke_log`
 ADD PRIMARY KEY (`id_log`), ADD KEY `usuario` (`id_usuario`);

--
-- Indices de la tabla `kirke_menu`
--
ALTER TABLE `kirke_menu`
 ADD PRIMARY KEY (`id_menu`);

--
-- Indices de la tabla `kirke_menu_link`
--
ALTER TABLE `kirke_menu_link`
 ADD PRIMARY KEY (`id_menu_link`), ADD KEY `id_menu` (`id_menu`,`id_elemento`);

--
-- Indices de la tabla `kirke_menu_link_nombre`
--
ALTER TABLE `kirke_menu_link_nombre`
 ADD PRIMARY KEY (`id_menu_link_nombre`), ADD KEY `id_menu_link` (`id_menu_link`);

--
-- Indices de la tabla `kirke_menu_link_parametro`
--
ALTER TABLE `kirke_menu_link_parametro`
 ADD PRIMARY KEY (`id_menu_link_parametro`), ADD KEY `id_menu_link` (`id_menu_link`);

--
-- Indices de la tabla `kirke_menu_nombre`
--
ALTER TABLE `kirke_menu_nombre`
 ADD PRIMARY KEY (`id_menu_nombre`);

--
-- Indices de la tabla `kirke_rol`
--
ALTER TABLE `kirke_rol`
 ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `kirke_rol_detalle`
--
ALTER TABLE `kirke_rol_detalle`
 ADD PRIMARY KEY (`id_rol_detalle`), ADD KEY `id_rol` (`id_rol`);

--
-- Indices de la tabla `kirke_tabla`
--
ALTER TABLE `kirke_tabla`
 ADD PRIMARY KEY (`id_tabla`);

--
-- Indices de la tabla `kirke_tabla_nombre_idioma`
--
ALTER TABLE `kirke_tabla_nombre_idioma`
 ADD PRIMARY KEY (`id_tabla_nombre_idioma`), ADD KEY `id_idioma` (`idioma_codigo`);

--
-- Indices de la tabla `kirke_tabla_parametro`
--
ALTER TABLE `kirke_tabla_parametro`
 ADD PRIMARY KEY (`id_tabla_parametro`), ADD KEY `id_tabla` (`id_tabla`);

--
-- Indices de la tabla `kirke_tabla_prefijo`
--
ALTER TABLE `kirke_tabla_prefijo`
 ADD PRIMARY KEY (`id_tabla_prefijo`);

--
-- Indices de la tabla `kirke_tmp`
--
ALTER TABLE `kirke_tmp`
 ADD PRIMARY KEY (`id_tmp`), ADD KEY `id_componente` (`id_componente`);

--
-- Indices de la tabla `kirke_usuario`
--
ALTER TABLE `kirke_usuario`
 ADD PRIMARY KEY (`id_usuario`);

--
-- Indices de la tabla `kirke_usuario_atributo`
--
ALTER TABLE `kirke_usuario_atributo`
 ADD PRIMARY KEY (`id_usuario_atributo`), ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `kirke_usuario_rol`
--
ALTER TABLE `kirke_usuario_rol`
 ADD PRIMARY KEY (`id_usuario_rol`), ADD KEY `id_usuario` (`id_usuario`,`id_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `kirke_componente`
--
ALTER TABLE `kirke_componente`
MODIFY `id_componente` int(6) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `kirke_componente_parametro`
--
ALTER TABLE `kirke_componente_parametro`
MODIFY `id_componente_parametro` int(6) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `kirke_ip_control`
--
ALTER TABLE `kirke_ip_control`
MODIFY `id_ip_control` int(6) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `kirke_log`
--
ALTER TABLE `kirke_log`
MODIFY `id_log` int(6) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `kirke_menu`
--
ALTER TABLE `kirke_menu`
MODIFY `id_menu` int(6) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `kirke_menu_link`
--
ALTER TABLE `kirke_menu_link`
MODIFY `id_menu_link` int(6) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `kirke_menu_link_nombre`
--
ALTER TABLE `kirke_menu_link_nombre`
MODIFY `id_menu_link_nombre` int(6) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `kirke_menu_link_parametro`
--
ALTER TABLE `kirke_menu_link_parametro`
MODIFY `id_menu_link_parametro` int(6) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `kirke_menu_nombre`
--
ALTER TABLE `kirke_menu_nombre`
MODIFY `id_menu_nombre` int(6) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `kirke_rol`
--
ALTER TABLE `kirke_rol`
MODIFY `id_rol` int(6) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `kirke_rol_detalle`
--
ALTER TABLE `kirke_rol_detalle`
MODIFY `id_rol_detalle` int(6) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `kirke_tabla`
--
ALTER TABLE `kirke_tabla`
MODIFY `id_tabla` int(6) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `kirke_tabla_nombre_idioma`
--
ALTER TABLE `kirke_tabla_nombre_idioma`
MODIFY `id_tabla_nombre_idioma` int(6) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `kirke_tabla_parametro`
--
ALTER TABLE `kirke_tabla_parametro`
MODIFY `id_tabla_parametro` int(6) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `kirke_tabla_prefijo`
--
ALTER TABLE `kirke_tabla_prefijo`
MODIFY `id_tabla_prefijo` int(6) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `kirke_tmp`
--
ALTER TABLE `kirke_tmp`
MODIFY `id_tmp` int(6) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `kirke_usuario`
--
ALTER TABLE `kirke_usuario`
MODIFY `id_usuario` int(6) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `kirke_usuario_atributo`
--
ALTER TABLE `kirke_usuario_atributo`
MODIFY `id_usuario_atributo` int(6) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT de la tabla `kirke_usuario_rol`
--
ALTER TABLE `kirke_usuario_rol`
MODIFY `id_usuario_rol` int(6) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
