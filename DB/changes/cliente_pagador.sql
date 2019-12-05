-- Valentina Studio --
-- MySQL dump --
-- ---------------------------------------------------------


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
-- ---------------------------------------------------------


-- CREATE TABLE "cliente_pagador" --------------------------
-- CREATE TABLE "cliente_pagador" ------------------------------
CREATE TABLE `cliente_pagador` ( 
	`id_cliente` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`actividad_e_cliente` Int( 11 ) NOT NULL,
	`pais_cliente` Int( 11 ) NULL,
	`tipo_persona_cliente` VarChar( 6 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`id_datos_personales` Int( 11 ) NOT NULL,
	`id_contacto` Int( 11 ) NOT NULL,
	`rfc_img` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`id_contacto_cliente` VarChar( 11 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`dominio_fiscal_img` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	PRIMARY KEY ( `id_cliente` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = InnoDB
AUTO_INCREMENT = 21;
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "index_id_cliente" -------------------------
-- CREATE INDEX "index_id_cliente" -----------------------------
CREATE INDEX `index_id_cliente` USING BTREE ON `cliente_pagador`( `id_cliente` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "index_pais_cliente" -----------------------
-- CREATE INDEX "index_pais_cliente" ---------------------------
CREATE INDEX `index_pais_cliente` USING BTREE ON `cliente_pagador`( `pais_cliente` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "lnk_contacto_cliente_pagador" -------------
-- CREATE INDEX "lnk_contacto_cliente_pagador" -----------------
CREATE INDEX `lnk_contacto_cliente_pagador` USING BTREE ON `cliente_pagador`( `id_contacto` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "lnk_datos_personales_cliente_pagador" -----
-- CREATE INDEX "lnk_datos_personales_cliente_pagador" ---------
CREATE INDEX `lnk_datos_personales_cliente_pagador` USING BTREE ON `cliente_pagador`( `id_datos_personales` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "lnk_lval_cliente_pagador" -----------------
-- CREATE INDEX "lnk_lval_cliente_pagador" ---------------------
CREATE INDEX `lnk_lval_cliente_pagador` USING BTREE ON `cliente_pagador`( `actividad_e_cliente` );
-- -------------------------------------------------------------
-- ---------------------------------------------------------


/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
-- ---------------------------------------------------------


