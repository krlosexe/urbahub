-- CREATE FIELD "etapas" ---------------------------------------
ALTER TABLE `productos` ADD COLUMN `etapas` VarChar( 200 ) NOT NULL;
-- -------------------------------------------------------------

-- CREATE FIELD "obervacion" -----------------------------------
ALTER TABLE `productos` ADD COLUMN `obervacion` VarChar( 200 ) NOT NULL;
-- -------------------------------------------------------------

-- CREATE FIELD "plan" -----------------------------------------
ALTER TABLE `productos` ADD COLUMN `plan` Text NOT NULL;
-- -------------------------------------------------------------

