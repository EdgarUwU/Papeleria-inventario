CREATE database IF NOT EXISTS `maquillaje_inventario` DEFAULT CHARACTER SET utf8 ;
USE `maquillaje_inventario` ;

CREATE TABLE IF NOT EXISTS `maquillaje_inventario`.`PRODUCTO` (
  `id_producto` SMALLINT AUTO_INCREMENT,
  `nombre_prod` VARCHAR(45) NOT NULL,
  `marca` VARCHAR(30) NOT NULL,
  `presentacion` VARCHAR(45) NOT NULL,
  `precio` DECIMAL(5,2) NOT NULL,
  `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` INT NOT NULL,
  `modified` TIMESTAMP NULL,
  `modified_by` INT NULL,
  `delete` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_producto`),
  INDEX `idx_nombreProd` (`nombre_prod` ASC),
  INDEX `idx_marca` (`marca` ASC))
ENGINE = InnoDB;


CREATE TABLE IF NOT EXISTS `maquillaje_inventario`.`INVENTARIO` (
  `id_inventario` SMALLINT AUTO_INCREMENT,
  `stock` INT NOT NULL,
  `id_producto` SMALLINT NOT NULL,
  `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_by` INT NOT NULL,
  `modified` TIMESTAMP NULL,
  `modified_by` INT NULL,
  `deleted` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_inventario`),
  INDEX `idx_stock` (`stock`),
  INDEX `fk_producto_id_producto_idx` (`id_producto` ASC),
  CONSTRAINT `fk_producto_id_producto`
    FOREIGN KEY (`id_producto`)
    REFERENCES `maquillaje_inventario`.`PRODUCTO` (`id_producto`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT)
ENGINE = InnoDB;


CREATE TABLE IF NOT EXISTS `maquillaje_inventario`.`MOVIMIENTO` (
  `id_movimiento` SMALLINT AUTO_INCREMENT,
  `tipo` ENUM('venta', 'compra', 'merma') NOT NULL,
  `cantidad` INT NOT NULL,
  `fecha` DATETIME NOT NULL,
	`descripcion` VARCHAR(100) NOT NULL,
  `id_inventario` SMALLINT NOT NULL,
  `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` INT NOT NULL,
  `modified` TIMESTAMP NULL,
  `modified_by` INT NULL,
  `deleted` INT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_movimiento`),
  INDEX `idx_tipo` (`tipo`),
  INDEX `fk_inventario_id_inventario_idx` (`id_inventario` ASC),
  INDEX `idx_fecha` (`fecha` ASC),
  CONSTRAINT `fk_inventario_id_inventario`
    FOREIGN KEY (`id_inventario`)
    REFERENCES `maquillaje_inventario`.`INVENTARIO` (`id_inventario`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT)
ENGINE = InnoDB;


CREATE TABLE IF NOT EXISTS `maquillaje_inventario`.`USUARIOS` (
  `id_usuario` INT AUTO_INCREMENT,
  `nombre` VARCHAR(45) NOT NULL,
  `apellido_pat` VARCHAR(30) NULL,
  `apellido_mat` VARCHAR(30) NULL,
  `contrasena` VARBINARY(40) NOT NULL,
  `created` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `create_by` INT NOT NULL,
  `modified` TIMESTAMP NULL,
  `modified_by` INT NULL,
  `ip` VARCHAR(15) NOT NULL,
  `login_count` SMALLINT NULL DEFAULT 0,
  `privilegios` ENUM('administrador', 'auditoria', 'normal') NULL,
  PRIMARY KEY (`id_usuario`),
  INDEX `idx_nombre` (`nombre` ASC, `apellido_pat` ASC, `apellido_mat` ASC))
ENGINE = InnoDB;

USE maquillaje_inventario;

SELECT * FROM maquillaje_inventario.USUARIOS;
INSERT INTO `USUARIOS` (`username`,nombre, `apellido_pat`, `apellido_mat`, `contrasena`, `create_by`,ip,privilegios) VALUES
('Administrador', 'Edgar', 'Escobedo','Nevárez', '$2y$10$EPY9LSLOFLDDBriuJICmFOqmZdnDXxLJG8YFbog5LcExp77DBQvgC', '1','192.168.127.66','1');
