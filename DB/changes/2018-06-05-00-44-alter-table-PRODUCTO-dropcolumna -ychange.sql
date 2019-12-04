-- DROP FIELD "zona" -------------------------------------------
ALTER TABLE `productos` DROP COLUMN `zona`;
-- -------------------------------------------------------------

-- DROP FIELD "plan" -------------------------------------------
ALTER TABLE `productos` DROP COLUMN `plan`;
-- -------------------------------------------------------------

-- CHANGE "NULLABLE" OF "FIELD "etapas" ------------------------
ALTER TABLE `productos` MODIFY `etapas` VarChar( 200 ) CHARACTER SET utf8 COLLATE utf8_spanish_ci NULL;
