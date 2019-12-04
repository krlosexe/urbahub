ALTER TABLE `comisiones` ADD `ind_ventas_mes` VARCHAR(1) NOT NULL DEFAULT 'N' AFTER `tipo_vendedor`;

ALTER TABLE `comisiones` ADD `cantidad_min_ventas_mes` INT NOT NULL DEFAULT '0' AFTER `num_ventas_mes`;

ALTER TABLE `comisiones` ADD `cantidad_max_ventas_mes` INT(11) NOT NULL DEFAULT '0' AFTER `cantidad_min_ventas_mes`;
