-- CHANGE "NULLABLE" OF "FIELD "tipo_persona_cliente" ----------
ALTER TABLE `cliente_pagador` MODIFY `tipo_persona_cliente` VarChar( 6 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
-- -------------------------------------------------------------

-- CREATE FIELD "giro_mercantil" -------------------------------
ALTER TABLE `cliente_pagador` ADD COLUMN `giro_mercantil` Int( 11 ) NULL;
-- -------------------------------------------------------------

-- CREATE FIELD "acta_constitutiva" ----------------------------
ALTER TABLE `cliente_pagador` ADD COLUMN `acta_constitutiva` VarChar( 100 ) NULL;
-- -------------------------------------------------------------

-- CREATE FIELD "acta_img" -------------------------------------
ALTER TABLE `cliente_pagador` ADD COLUMN `acta_img` VarChar( 100 ) NULL
