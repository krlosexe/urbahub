ALTER TABLE `proyectos` ADD `indicador_mora` VARCHAR(1) NOT NULL AFTER `plano`, ADD `can_dias_vencidos` INT NOT NULL AFTER `indicador_mora`, ADD `porcentaje_mora` DOUBLE NOT NULL AFTER `can_dias_vencidos`;


ALTER TABLE `proyectos` CHANGE `indicador_mora` `indicador_mora` VARCHAR(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'N';
