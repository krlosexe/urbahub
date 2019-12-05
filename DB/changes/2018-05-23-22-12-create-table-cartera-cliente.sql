CREATE TABLE `cartera_clientes` (
  `id` int(11) NOT NULL,
  `id_vendedor` int(11) NOT NULL,
  `id_proyecto` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;




ALTER TABLE `cartera_clientes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cartera_cliente_id_vendedor_idx` (`id_vendedor`),
  ADD KEY `cartera_cliente_id_proyecto_idx` (`id_proyecto`),
  ADD KEY `cartela.cliente_id_cliente_idx` (`id_cliente`);


 --
-- AUTO_INCREMENT de la tabla `cartera_clientes`
--
ALTER TABLE `cartera_clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;



--
-- Filtros para la tabla `cartera_clientes`
--
ALTER TABLE `cartera_clientes`
  ADD CONSTRAINT `fk_cartera_clientes_id_cliente` FOREIGN KEY (`id_cliente`) REFERENCES `cliente_pagador` (`id_cliente`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cartera_clientes_id_proyecto` FOREIGN KEY (`id_proyecto`) REFERENCES `proyectos` (`id_proyecto`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cartera_clientes_id_vendedor` FOREIGN KEY (`id_vendedor`) REFERENCES `vendedores` (`id_vendedor`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;


