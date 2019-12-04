--
-- Estructura de tabla para la tabla `conceptos_retencion`
--

CREATE TABLE `conceptos_retencion` (
  `id_concepto_retencion` int(11) NOT NULL,
  `codigo_concepto` int(11) NOT NULL,
  `tipo_tasa` int(11) NOT NULL,
  `porcentaje` double NOT NULL,
  `monto` double NOT NULL,
  `fecha` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `conceptos_retencion`
--
ALTER TABLE `conceptos_retencion`
  ADD PRIMARY KEY (`id_concepto_retencion`),
  ADD KEY `codigo_concepto_retencion_idx` (`codigo_concepto`),
  ADD KEY `tipo_tasa_concepto_retencion_idx` (`tipo_tasa`),
  ADD KEY `id_concepto_retencion_idx` (`id_concepto_retencion`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `conceptos_retencion`
--
ALTER TABLE `conceptos_retencion`
  MODIFY `id_concepto_retencion` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `conceptos_retencion`
--
ALTER TABLE `conceptos_retencion`
  ADD CONSTRAINT `fx_codigo_concepto_retencion` FOREIGN KEY (`codigo_concepto`) REFERENCES `lval` (`codlval`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fx_tipo_tasa_conceto_retencion` FOREIGN KEY (`tipo_tasa`) REFERENCES `lval` (`codlval`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;
