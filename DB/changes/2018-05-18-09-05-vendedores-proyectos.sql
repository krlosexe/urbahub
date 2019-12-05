CREATE TABLE `vendedores_proyectos` (
  `id` int(11) NOT NULL,
  `id_vendedor` int(11) NOT NULL,
  `id_proyecto` int(11) NOT NULL,
  `estatus` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

--
ALTER TABLE `vendedores_proyectos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendedores_proyectos_id_proyecto_idx` (`id_proyecto`),
  ADD KEY `vendedores_proyecto_id_vendedor_idx` (`id_vendedor`);

--
ALTER TABLE `vendedores_proyectos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


--
ALTER TABLE `vendedores_proyectos`
  ADD CONSTRAINT `fk_vendedores_id_proyectos` FOREIGN KEY (`id_proyecto`) REFERENCES `proyectos` (`id_proyecto`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_vendedores_id_vendedor` FOREIGN KEY (`id_vendedor`) REFERENCES `vendedores` (`id_vendedor`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;
