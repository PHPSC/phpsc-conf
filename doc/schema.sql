SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `phpsc` DEFAULT CHARACTER SET utf8 ;
USE `phpsc` ;

-- -----------------------------------------------------
-- Table `phpsc`.`location`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `phpsc`.`location` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(60) NOT NULL ,
  `description` TEXT NOT NULL ,
  `longitude` DECIMAL(13,2) NULL ,
  `latitude` DECIMAL(13,2) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `phpsc`.`event`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `phpsc`.`event` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `location_id` INT NOT NULL ,
  `name` VARCHAR(80) NOT NULL ,
  `start` DATE NOT NULL ,
  `end` DATE NOT NULL ,
  `submissions_start` DATETIME NULL ,
  `submissions_end` DATETIME NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `event_index0` (`start` ASC) ,
  INDEX `evento_index1` (`submissions_start` ASC, `submissions_end` ASC) ,
  INDEX `fk_event_location1` (`location_id` ASC) ,
  CONSTRAINT `fk_event_location1`
    FOREIGN KEY (`location_id` )
    REFERENCES `phpsc`.`location` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `phpsc`.`user`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `phpsc`.`user` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(80) NOT NULL ,
  `twitter_user` VARCHAR(60) NOT NULL ,
  `email` VARCHAR(160) NOT NULL ,
  `bio` TEXT NULL ,
  `github_user` VARCHAR(60) NULL ,
  `creation_time` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `screen_name_UNIQUE` (`twitter_user` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `phpsc`.`talk_type`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `phpsc`.`talk_type` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `description` VARCHAR(45) NOT NULL ,
  `length` TIME NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `phpsc`.`talk`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `phpsc`.`talk` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `event_id` INT NOT NULL ,
  `type_id` INT NOT NULL ,
  `title` VARCHAR(120) NOT NULL ,
  `short_description` VARCHAR(255) NOT NULL ,
  `long_description` TEXT NOT NULL ,
  `complexity` ENUM('L', 'M', 'H') NOT NULL DEFAULT 'L' ,
  `tags` VARCHAR(120) NULL ,
  `start_time` DATETIME NULL ,
  `approved` TINYINT(1) NOT NULL DEFAULT 0 ,
  `creation_time` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_talk_event` (`event_id` ASC) ,
  INDEX `fk_talk_type` (`type_id` ASC) ,
  CONSTRAINT `fk_talk_event`
    FOREIGN KEY (`event_id` )
    REFERENCES `phpsc`.`event` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_talk_type1`
    FOREIGN KEY (`type_id` )
    REFERENCES `phpsc`.`talk_type` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `phpsc`.`speaker`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `phpsc`.`speaker` (
  `talk_id` INT NOT NULL ,
  `user_id` INT NOT NULL ,
  PRIMARY KEY (`talk_id`, `user_id`) ,
  INDEX `fk_speaker_user1` (`user_id` ASC) ,
  INDEX `fk_speaker_talk1` (`talk_id` ASC) ,
  CONSTRAINT `fk_talk_has_user_talk1`
    FOREIGN KEY (`talk_id` )
    REFERENCES `phpsc`.`talk` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_talk_has_user_user1`
    FOREIGN KEY (`user_id` )
    REFERENCES `phpsc`.`user` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `phpsc`.`interest`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `phpsc`.`interest` (
  `talk_id` INT NOT NULL ,
  `user_id` INT NOT NULL ,
  PRIMARY KEY (`talk_id`, `user_id`) ,
  INDEX `fk_talk_has_user_user2` (`user_id` ASC) ,
  INDEX `fk_talk_has_user_talk2` (`talk_id` ASC) ,
  CONSTRAINT `fk_talk_has_user_talk2`
    FOREIGN KEY (`talk_id` )
    REFERENCES `phpsc`.`talk` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_talk_has_user_user2`
    FOREIGN KEY (`user_id` )
    REFERENCES `phpsc`.`user` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `phpsc`.`location`
-- -----------------------------------------------------
START TRANSACTION;
USE `phpsc`;
INSERT INTO `phpsc`.`location` (`id`, `name`, `description`, `longitude`, `latitude`) VALUES (NULL, 'UNIVALI São José', 'Campus São José da Universidade do Vale do Itajaí', NULL, NULL);

COMMIT;

-- -----------------------------------------------------
-- Data for table `phpsc`.`event`
-- -----------------------------------------------------
START TRANSACTION;
USE `phpsc`;
INSERT INTO `phpsc`.`event` (`id`, `location_id`, `name`, `start`, `end`, `submissions_start`, `submissions_end`) VALUES (NULL, 1, 'PHPSC Conference 2012', '2012-10-27', '2012-10-27', NULL, NULL);

COMMIT;

-- -----------------------------------------------------
-- Data for table `phpsc`.`talk_type`
-- -----------------------------------------------------
START TRANSACTION;
USE `phpsc`;
INSERT INTO `phpsc`.`talk_type` (`id`, `description`, `length`) VALUES (NULL, 'Minicurso', '04:00');
INSERT INTO `phpsc`.`talk_type` (`id`, `description`, `length`) VALUES (NULL, 'Palestra', '01:00');
INSERT INTO `phpsc`.`talk_type` (`id`, `description`, `length`) VALUES (NULL, 'Palestra Curta', '0:20');
INSERT INTO `phpsc`.`talk_type` (`id`, `description`, `length`) VALUES (NULL, 'Mesa Redonda', '01:00');

COMMIT;
