
CREATE TABLE `recargas` (
  `id_recarga` int(11) NOT NULL,
  `tipo_plazo` int(11) NOT NULL,
  `recarga` double NOT NULL,
  `cod_esquema` int(11) NOT NULL,
  `tipo_vendedor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



ALTER TABLE `recargas`
  ADD PRIMARY KEY (`id_recarga`),
  ADD KEY `tipo_plazo` (`tipo_plazo`),
  ADD KEY `cod_esquema` (`cod_esquema`),
  ADD KEY `tipo_vendedor` (`tipo_vendedor`);


ALTER TABLE `recargas`
  MODIFY `id_recarga` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;


 --
ALTER TABLE `recargas`
  ADD CONSTRAINT `recargas_ibfk_1` FOREIGN KEY (`tipo_plazo`) REFERENCES `lval` (`codlval`),
  ADD CONSTRAINT `recargas_ibfk_2` FOREIGN KEY (`cod_esquema`) REFERENCES `esquemas` (`id_esquema`),
  ADD CONSTRAINT `recargas_ibfk_3` FOREIGN KEY (`tipo_vendedor`) REFERENCES `lval` (`codlval`);
