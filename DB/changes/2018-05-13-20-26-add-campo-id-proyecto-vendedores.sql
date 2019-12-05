ALTER TABLE `vendedores_inmobiliarias` ADD `id_proyecto` INT NOT NULL AFTER `id_vendedor`;


ALTER TABLE `vendedores_inmobiliarias` ADD INDEX `id_proyecto_vendedor_ix` (`id_proyecto`);

ALTER TABLE `vendedores_inmobiliarias` ADD CONSTRAINT `fk_id_proyecto_vendedor` FOREIGN KEY (`id_proyecto`) REFERENCES `proyectos`(`id_proyecto`) ON DELETE CASCADE ON UPDATE CASCADE;
