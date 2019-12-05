INSERT INTO `tipolval` (`tipolval`, `descriplval`) VALUES ('STSPRODUCTO', 'ESTATUS PRODUCTO');



ALTER TABLE `productos` ADD `STSPRODUCTO` INT NOT NULL AFTER `precio_m2`;

ALTER TABLE `productos` ADD `FECSTSTPROD` DATE NOT NULL AFTER `STSPRODUCTO`;

ALTER TABLE `productos` ADD INDEX `productos_stsproducto_idx` (`STSPRODUCTO`);

ALTER TABLE `productos` ADD INDEX `productos_id_idx` (`id_producto`);

ALTER TABLE `productos` ADD CONSTRAINT `fk_productos_sts_producto` FOREIGN KEY (`STSPRODUCTO`) REFERENCES `lval`(`codlval`) ON DELETE CASCADE ON UPDATE CASCADE;

