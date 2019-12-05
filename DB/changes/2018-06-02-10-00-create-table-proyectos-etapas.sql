- CREATE TABLE "proyectos_etapas" -----------------------------
CREATE TABLE `proyectos_etapas` ( 
	`id_proyectos_etapas` Int( 11 ) NOT NULL,
	`id_proyecto` Int( 11 ) NOT NULL,
	`etapas_proyectos` VarChar( 255 ) NOT NULL,
	PRIMARY KEY ( `id_proyectos_etapas` ) )
ENGINE = InnoDB;
