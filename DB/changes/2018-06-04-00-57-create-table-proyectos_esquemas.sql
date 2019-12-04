-- CREATE TABLE "proyectos_esquemas" ---------------------------
CREATE TABLE `proyectos_esquemas` ( 
	`id_proyectos_esquemas` Int( 11 ) NOT NULL,
	`id_esquema` Int( 11 ) NOT NULL,
	`id_proyecto` Int( 11 ) NOT NULL,
	CONSTRAINT `unique_id_proyectos_esquemas` UNIQUE( `id_proyectos_esquemas` ) )
ENGINE = InnoDB;
-- -----------------
