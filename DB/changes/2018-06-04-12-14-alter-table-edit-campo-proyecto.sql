-- CHANGE "NULLABLE" OF "FIELD "can_dias_vencidos" -------------
ALTER TABLE `proyectos` MODIFY `n_dias_vencidos` Int( 11 ) NULL;
-- -------------------------------------------------------------

-- CHANGE "NAME" OF "FIELD "can_dias_vencidos" -----------------
ALTER TABLE `proyectos` CHANGE `n_dias_vencidos` `can_dias_vencidos` Int( 11 ) NULL;
-- -------------------------------------------------------------

