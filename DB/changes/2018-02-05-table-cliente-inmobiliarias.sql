-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vendedores_inmobiliarias`
--

CREATE TABLE `vendedores_inmobiliarias` (
  `id` int(11) NOT NULL,
  `id_vendedor` int(11) NOT NULL,
  `id_inmobiliaria` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;



--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `vendedores_inmobiliarias`
--
ALTER TABLE `vendedores_inmobiliarias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_vendedor_inmobiliaria_idx` (`id_vendedor`),
  ADD KEY `id_inmobiliaria_vendedor_idx` (`id_inmobiliaria`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `vendedores_inmobiliarias`
--
ALTER TABLE `vendedores_inmobiliarias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `vendedores_inmobiliarias`
--
ALTER TABLE `vendedores_inmobiliarias`
  ADD CONSTRAINT `fk_id_inmobiliaria_vendedor` FOREIGN KEY (`id_inmobiliaria`) REFERENCES `inmobiliarias` (`id_inmobiliaria`) ON DELETE NO ACTION,
  ADD CONSTRAINT `fk_id_vendedor_inmobiliaria` FOREIGN KEY (`id_vendedor`) REFERENCES `vendedores` (`id_vendedor`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
