
CREATE TABLE `vendedores` (
  `id_vendedor` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `tipo_vendedor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indices de la tabla `vendedores`
--
ALTER TABLE `vendedores`
  ADD PRIMARY KEY (`id_vendedor`),
  ADD UNIQUE KEY `id_usuario` (`id_usuario`) USING BTREE,
  ADD KEY `tipo_vendedor_idx` (`tipo_vendedor`) USING BTREE,
  ADD KEY `id_vendedor_inmobiliaria_idx` (`id_vendedor`),
  ADD KEY `id_id_vendedor_idx` (`id_vendedor`);

ALTER TABLE `vendedores`
  MODIFY `id_vendedor` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `vendedores`
  ADD CONSTRAINT `fk_num_id` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tipo_id` FOREIGN KEY (`tipo_vendedor`) REFERENCES `lval` (`codlval`) ON DELETE CASCADE ON UPDATE CASCADE;
