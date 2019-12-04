-- CREATE FIELD "tipo_cliente" ---------------------------------
ALTER TABLE `cliente_pagador` ADD COLUMN `tipo_cliente` VarChar( 255 ) NOT NULL DEFAULT 'cliente';
