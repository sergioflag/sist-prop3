-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema db_biblioteca
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema db_biblioteca
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `db_biblioteca` DEFAULT CHARACTER SET utf8 ;
USE `db_biblioteca` ;

-- -----------------------------------------------------
-- Table `db_biblioteca`.`perfiles`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `db_biblioteca`.`perfiles` (
  `id_perfil` INT NOT NULL AUTO_INCREMENT,
  `perfil` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id_perfil`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `db_biblioteca`.`personas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `db_biblioteca`.`personas` (
  `id_persona` INT NOT NULL AUTO_INCREMENT,
  `nombres` VARCHAR(100) NOT NULL,
  `a_paterno` VARCHAR(100) NOT NULL,
  `a_materno` VARCHAR(100) NULL,
  `telefono` VARCHAR(20) NULL,
  `f_nacimiento` DATE NULL,
  `status` TINYINT(1) NULL DEFAULT 1,
  `created` DATETIME NULL DEFAULT CURRENT_TIMESTAMP(),
  PRIMARY KEY (`id_persona`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `db_biblioteca`.`usuarios`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `db_biblioteca`.`usuarios` (
  `id_usuario` INT NOT NULL AUTO_INCREMENT,
  `id_persona` INT NOT NULL,
  `id_perfil` INT NOT NULL,
  `usuario` VARCHAR(55) NULL,
  `email` VARCHAR(100) NULL,
  `contrasena` VARCHAR(255) NULL,
  `created` DATETIME NULL DEFAULT CURRENT_TIMESTAMP(),
  `status` TINYINT(1) NULL DEFAULT 1,
  PRIMARY KEY (`id_usuario`),
  INDEX `fk_perfiles_cat_perfil_idx` (`id_perfil` ASC),
  INDEX `fk_usuarios_personas1_idx` (`id_persona` ASC),
  CONSTRAINT `fk_perfiles_cat_perfil`
    FOREIGN KEY (`id_perfil`)
    REFERENCES `db_biblioteca`.`perfiles` (`id_perfil`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_usuarios_personas1`
    FOREIGN KEY (`id_persona`)
    REFERENCES `db_biblioteca`.`personas` (`id_persona`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `db_biblioteca`.`menu`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `db_biblioteca`.`menu` (
  `id_menu` INT NOT NULL AUTO_INCREMENT,
  `recurso` VARCHAR(45) NOT NULL,
  `url` VARCHAR(45) NOT NULL,
  `icono` VARCHAR(45) NULL,
  PRIMARY KEY (`id_menu`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `db_biblioteca`.`perfiles_menu`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `db_biblioteca`.`perfiles_menu` (
  `id_perfil` INT NOT NULL,
  `id_menu` INT NOT NULL,
  PRIMARY KEY (`id_perfil`, `id_menu`),
  INDEX `fk_perfiles_has_menu_menu1_idx` (`id_menu` ASC),
  INDEX `fk_perfiles_has_menu_perfiles1_idx` (`id_perfil` ASC),
  CONSTRAINT `fk_perfiles_has_menu_perfiles1`
    FOREIGN KEY (`id_perfil`)
    REFERENCES `db_biblioteca`.`perfiles` (`id_perfil`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_perfiles_has_menu_menu1`
    FOREIGN KEY (`id_menu`)
    REFERENCES `db_biblioteca`.`menu` (`id_menu`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `db_biblioteca`.`autores`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `db_biblioteca`.`autores` (
  `id_autor` INT NOT NULL AUTO_INCREMENT,
  `autor` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id_autor`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `db_biblioteca`.`editoriales`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `db_biblioteca`.`editoriales` (
  `id_editorial` INT NOT NULL AUTO_INCREMENT,
  `editorial` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id_editorial`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `db_biblioteca`.`generos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `db_biblioteca`.`generos` (
  `id_genero` INT NOT NULL,
  `genero` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id_genero`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `db_biblioteca`.`libros`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `db_biblioteca`.`libros` (
  `id_libro` INT NOT NULL,
  `id_autor` INT NOT NULL,
  `id_editorial` INT NOT NULL,
  `id_genero` INT NOT NULL,
  `titulo` VARCHAR(45) NOT NULL,
  `fecha_publicacion` VARCHAR(45) NOT NULL,
  `paginas` VARCHAR(45) NOT NULL,
  `isbn` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id_libro`),
  INDEX `fk_libros_autores1_idx` (`id_autor` ASC),
  INDEX `fk_libros_editoriales1_idx` (`id_editorial` ASC),
  INDEX `fk_libros_generos1_idx` (`id_genero` ASC),
  CONSTRAINT `fk_libros_autores1`
    FOREIGN KEY (`id_autor`)
    REFERENCES `db_biblioteca`.`autores` (`id_autor`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_libros_editoriales1`
    FOREIGN KEY (`id_editorial`)
    REFERENCES `db_biblioteca`.`editoriales` (`id_editorial`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_libros_generos1`
    FOREIGN KEY (`id_genero`)
    REFERENCES `db_biblioteca`.`generos` (`id_genero`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `db_biblioteca`.`lectores`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `db_biblioteca`.`lectores` (
  `id_lector` INT NOT NULL AUTO_INCREMENT,
  `id_persona` INT NOT NULL,
  `direccion` VARCHAR(100) NOT NULL,
  `f_registro` DATETIME NULL DEFAULT CURRENT_TIMESTAMP(),
  `estado` TINYINT(1) NULL DEFAULT 1,
  PRIMARY KEY (`id_lector`),
  INDEX `fk_lectores_personas1_idx` (`id_persona` ASC),
  CONSTRAINT `fk_lectores_personas1`
    FOREIGN KEY (`id_persona`)
    REFERENCES `db_biblioteca`.`personas` (`id_persona`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `db_biblioteca`.`stock`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `db_biblioteca`.`stock` (
  `id_stock` INT NOT NULL,
  `cantidad` INT NOT NULL,
  `id_libro` INT NOT NULL,
  PRIMARY KEY (`id_stock`),
  INDEX `fk_stock_libros1_idx` (`id_libro` ASC),
  CONSTRAINT `fk_stock_libros1`
    FOREIGN KEY (`id_libro`)
    REFERENCES `db_biblioteca`.`libros` (`id_libro`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `db_biblioteca`.`prestamos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `db_biblioteca`.`prestamos` (
  `id_prestamo` INT NOT NULL AUTO_INCREMENT,
  `id_usuario` INT NOT NULL,
  `id_lector` INT NOT NULL,
  `id_libro` INT NOT NULL,
  `fecha_prestamo` DATETIME NULL DEFAULT CURRENT_TIMESTAMP(),
  `fecha_devolucion` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id_prestamo`),
  INDEX `fk_prestamos_usuarios1_idx` (`id_usuario` ASC),
  INDEX `fk_prestamos_lectores1_idx` (`id_lector` ASC),
  INDEX `fk_prestamos_stock1_idx` (`id_libro` ASC),
  CONSTRAINT `fk_prestamos_usuarios1`
    FOREIGN KEY (`id_usuario`)
    REFERENCES `db_biblioteca`.`usuarios` (`id_usuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_prestamos_lectores1`
    FOREIGN KEY (`id_lector`)
    REFERENCES `db_biblioteca`.`lectores` (`id_lector`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_prestamos_stock1`
    FOREIGN KEY (`id_libro`)
    REFERENCES `db_biblioteca`.`stock` (`id_stock`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
