-- MySQL Script generated by MySQL Workbench
-- 09/05/15 09:02:46
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema zoo
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema zoo
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `zoo` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `zoo` ;

-- -----------------------------------------------------
-- Table `zoo`.`dyreart`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `zoo`.`dyreart` ;

CREATE TABLE IF NOT EXISTS `zoo`.`dyreart` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `navn` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `zoo`.`dyr`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `zoo`.`dyr` ;

CREATE TABLE IF NOT EXISTS `zoo`.`dyr` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `navn` VARCHAR(45) NULL,
  `chipid` VARCHAR(45) NULL,
  `billede` VARCHAR(45) NULL,
  `opretDato` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fk_dyreart_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `chipid_UNIQUE` (`chipid` ASC),
  INDEX `fk_dyr_dyreart_idx` (`fk_dyreart_id` ASC),
  CONSTRAINT `fk_dyr_dyreart`
    FOREIGN KEY (`fk_dyreart_id`)
    REFERENCES `zoo`.`dyreart` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `zoo`.`dyreart`
-- -----------------------------------------------------
START TRANSACTION;
USE `zoo`;
INSERT INTO `zoo`.`dyreart` (`id`, `navn`) VALUES (1, 'Hund');
INSERT INTO `zoo`.`dyreart` (`id`, `navn`) VALUES (2, 'Hest');
INSERT INTO `zoo`.`dyreart` (`id`, `navn`) VALUES (3, 'Gris');
INSERT INTO `zoo`.`dyreart` (`id`, `navn`) VALUES (4, 'Høns');

COMMIT;


-- -----------------------------------------------------
-- Data for table `zoo`.`dyr`
-- -----------------------------------------------------
START TRANSACTION;
USE `zoo`;
INSERT INTO `zoo`.`dyr` (`id`, `navn`, `chipid`, `billede`, `fk_dyreart_id`) VALUES (1, 'Caruso', '65433456', 'Caruso.jpg', 4);

COMMIT;

