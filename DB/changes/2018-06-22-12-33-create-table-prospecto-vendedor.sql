-- CREATE TABLE "prospecto_vendedor" ---------------------------
CREATE TABLE `prospecto_vendedor` ( 
	`id_prospecto_vendedor` Int( 11 ) NOT NULL,
	`id_vendedor` Int( 11 ) NOT NULL,
	`id_proyecto` Int( 11 ) NOT NULL,
	`tipo_cliente` VarChar( 100 ) NOT NULL,
	`id_cliente` Int( 11 ) NOT NULL,
	PRIMARY KEY ( `id_proyecto`, `id_cliente` ) )
ENGINE = InnoDB;
