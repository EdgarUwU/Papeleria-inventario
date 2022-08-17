-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Schema papeleria
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema papeleria
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `papeleria` DEFAULT CHARACTER SET utf8mb4 ;
USE `papeleria` ;

-- -----------------------------------------------------
-- Table `papeleria`.`usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `papeleria`.`usuario` (
  `id_usuario` INT NOT NULL AUTO_INCREMENT,
  `nombre_usuario` VARCHAR(60) NOT NULL,
  `contrasena` VARCHAR(200) NOT NULL,
  `last_login` TIMESTAMP NULL DEFAULT NULL,
  `login_count` INT NOT NULL DEFAULT '0',
  `created_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` VARCHAR(25) NOT NULL,
  `modify_date` TIMESTAMP NULL DEFAULT NULL,
  `modify_by` VARCHAR(60) NOT NULL DEFAULT 'No se ha modificado',
  `deleted` INT NOT NULL DEFAULT '0',
  `nombre` VARCHAR(45) NOT NULL,
  `apellido_pat` VARCHAR(45) NULL DEFAULT NULL,
  `apellido_mat` VARCHAR(45) NULL DEFAULT NULL,
  `ip` VARCHAR(45) NOT NULL,
  `privilegios` ENUM('admin', 'user', 'auditor') NOT NULL,
  PRIMARY KEY (`id_usuario`),
  UNIQUE INDEX `nombre_usuario_UNIQUE` (`nombre_usuario` ASC) VISIBLE)
ENGINE = InnoDB
AUTO_INCREMENT = 15
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `papeleria`.`productos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `papeleria`.`productos` (
  `id_prod` INT NOT NULL AUTO_INCREMENT,
  `marca` VARCHAR(20) CHARACTER SET 'utf8mb4' NOT NULL,
  `precio` DECIMAL(4,0) NOT NULL,
  `presentacion` VARCHAR(100) CHARACTER SET 'utf8mb4' NULL DEFAULT NULL,
  `cod_bar` VARCHAR(12) CHARACTER SET 'utf8mb4' NOT NULL,
  `descripcion` VARCHAR(100) NOT NULL,
  `modify_date` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` INT NOT NULL,
  `created_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` INT NOT NULL DEFAULT '0',
  `modify_by` INT NULL DEFAULT NULL,
  PRIMARY KEY (`id_prod`),
  UNIQUE INDEX `idproducto` (`id_prod` ASC) VISIBLE,
  INDEX `user3_idx` (`created_by` ASC) VISIBLE,
  CONSTRAINT `user3`
    FOREIGN KEY (`created_by`)
    REFERENCES `papeleria`.`usuario` (`id_usuario`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `papeleria`.`inventario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `papeleria`.`inventario` (
  `id_inventario` INT NOT NULL AUTO_INCREMENT,
  `cantidad` INT NOT NULL,
  `id_prod` INT NOT NULL DEFAULT '1',
  `modify_by` INT NULL DEFAULT NULL,
  `modify_date` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` INT NOT NULL,
  `created_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` INT NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_inventario`, `id_prod`),
  UNIQUE INDEX `inventario` (`id_inventario` ASC) VISIBLE,
  INDEX `catidad` (`cantidad` ASC) VISIBLE,
  INDEX `prod_idx` (`id_prod` ASC) VISIBLE,
  INDEX `user2_idx` (`created_by` ASC) VISIBLE,
  CONSTRAINT `prod`
    FOREIGN KEY (`id_prod`)
    REFERENCES `papeleria`.`productos` (`id_prod`),
  CONSTRAINT `user2`
    FOREIGN KEY (`created_by`)
    REFERENCES `papeleria`.`usuario` (`id_usuario`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `papeleria`.`movimientos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `papeleria`.`movimientos` (
  `Id_cantidad` INT NOT NULL AUTO_INCREMENT,
  `cantidad` INT NOT NULL,
  `fecha` DATETIME NOT NULL,
  `tipo` ENUM('Tienda', 'Delivery', 'Paquetería') NOT NULL,
  `motivo` VARCHAR(100) NOT NULL,
  `factura` VARCHAR(100) NULL DEFAULT NULL,
  `id_inventario` INT NOT NULL,
  `modify_by` INT NULL DEFAULT NULL,
  `modify_date` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` INT NOT NULL,
  `created_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` INT NOT NULL DEFAULT '0',
  PRIMARY KEY (`Id_cantidad`),
  UNIQUE INDEX `idacan` (`Id_cantidad` ASC) VISIBLE,
  UNIQUE INDEX `factura_UNIQUE` (`factura` ASC) VISIBLE,
  INDEX `can_idx` (`id_inventario` ASC) VISIBLE,
  INDEX `user_idx` (`created_by` ASC) VISIBLE,
  CONSTRAINT `can`
    FOREIGN KEY (`id_inventario`)
    REFERENCES `papeleria`.`inventario` (`id_inventario`)
    ON UPDATE CASCADE,
  CONSTRAINT `user`
    FOREIGN KEY (`created_by`)
    REFERENCES `papeleria`.`usuario` (`id_usuario`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
