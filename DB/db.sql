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
CREATE SCHEMA IF NOT EXISTS `papeleria` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci ;
USE `papeleria` ;

-- -----------------------------------------------------
-- Table `papeleria`.`productos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `papeleria`.`productos` (
  `id_prod` INT NOT NULL AUTO_INCREMENT,
  `marca` VARCHAR(20) CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_0900_ai_ci' NOT NULL,
  `precio` DECIMAL(4,0) NOT NULL,
  `presentacion` VARCHAR(100) CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_0900_ai_ci' NULL DEFAULT NULL,
  `cod_bar` VARCHAR(12) CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_0900_ai_ci' NOT NULL,
  `descripcion` VARCHAR(100) NOT NULL,
  `modify_date` TIMESTAMP NULL DEFAULT NULL,
  `created_by` VARCHAR(50) NOT NULL,
  `created_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` INT NOT NULL DEFAULT '0',
  `modify_by` VARCHAR(60) NOT NULL DEFAULT 'No se ha modificado',
  `nombre_prod` VARCHAR(50) NOT NULL,
  `foto` VARCHAR(60) NULL DEFAULT NULL,
  PRIMARY KEY (`id_prod`),
  UNIQUE INDEX `idproducto` (`id_prod` ASC) VISIBLE,
  INDEX `user3_idx` (`created_by` ASC) VISIBLE)
ENGINE = InnoDB
AUTO_INCREMENT = 10
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `papeleria`.`inventario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `papeleria`.`inventario` (
  `id_inventario` INT NOT NULL AUTO_INCREMENT,
  `cantidad` INT NOT NULL,
  `id_prod` INT NOT NULL,
  `modify_by` VARCHAR(50) NOT NULL DEFAULT 'No se ha modificado',
  `modify_date` TIMESTAMP NULL DEFAULT NULL,
  `created_by` VARCHAR(50) NOT NULL,
  `created_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` INT NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_inventario`),
  INDEX `catidad` (`cantidad` ASC) VISIBLE,
  INDEX `prod_idx` (`id_prod` ASC) VISIBLE,
  CONSTRAINT `prod`
    FOREIGN KEY (`id_prod`)
    REFERENCES `papeleria`.`productos` (`id_prod`))
ENGINE = InnoDB
AUTO_INCREMENT = 9
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `papeleria`.`movimientos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `papeleria`.`movimientos` (
  `id_movimientos` INT NOT NULL AUTO_INCREMENT,
  `cant_mov` INT NOT NULL,
  `fecha` DATETIME NOT NULL,
  `tipo` ENUM('Venta', 'Ingreso', 'Perdida') NOT NULL,
  `motivo` VARCHAR(100) NOT NULL,
  `id_inventario` INT NOT NULL,
  `modify_by` VARCHAR(50) NOT NULL DEFAULT 'No se ha modificado',
  `modify_date` TIMESTAMP NULL DEFAULT NULL,
  `created_by` VARCHAR(50) NOT NULL,
  `created_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` INT NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_movimientos`),
  INDEX `can_idx` (`id_inventario` ASC) VISIBLE,
  CONSTRAINT `can`
    FOREIGN KEY (`id_inventario`)
    REFERENCES `papeleria`.`inventario` (`id_inventario`)
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 40
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


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
AUTO_INCREMENT = 26
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
