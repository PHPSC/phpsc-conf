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
  `longitude` DECIMAL(13,7) NULL ,
  `latitude` DECIMAL(13,7) NULL ,
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
  `short_description` TEXT NOT NULL ,
  `long_description` TEXT NOT NULL ,
  `complexity` ENUM('L', 'M', 'H') NOT NULL DEFAULT 'L' ,
  `tags` VARCHAR(120) NULL ,
  `start_time` DATETIME NULL ,
  `approved` TINYINT(1) NULL ,
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
-- Table `phpsc`.`opinion`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `phpsc`.`opinion` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `user_id` INT(11) NOT NULL ,
  `talk_id` INT(11) NOT NULL ,
  `likes` TINYINT(1) NOT NULL DEFAULT 1 ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_opinion_user1` (`user_id` ASC) ,
  INDEX `fk_opinion_talk1` (`talk_id` ASC) ,
  UNIQUE INDEX `opinion_index0` (`user_id` ASC, `talk_id` ASC) ,
  INDEX `opinion_index1` (`likes` ASC) ,
  CONSTRAINT `fk_opinion_user1`
    FOREIGN KEY (`user_id` )
    REFERENCES `phpsc`.`user` (`id` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `fk_opinion_talk1`
    FOREIGN KEY (`talk_id` )
    REFERENCES `phpsc`.`talk` (`id` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `phpsc`.`attendee`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `phpsc`.`attendee` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `event_id` INT NOT NULL ,
  `user_id` INT NOT NULL ,
  `cost` DECIMAL(13,2) NOT NULL ,
  `status` ENUM('0', '1', '2', '3', '4') NOT NULL ,
  `creation_time` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_attendee_event1` (`event_id` ASC) ,
  INDEX `fk_attendee_user1` (`user_id` ASC) ,
  CONSTRAINT `fk_attendee_event1`
    FOREIGN KEY (`event_id` )
    REFERENCES `phpsc`.`event` (`id` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `fk_attendee_user1`
    FOREIGN KEY (`user_id` )
    REFERENCES `phpsc`.`user` (`id` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `phpsc`.`registration_info`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `phpsc`.`registration_info` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `event_id` INT NOT NULL ,
  `start` DATETIME NOT NULL ,
  `end` DATETIME NOT NULL ,
  `regular_price` DECIMAL(13,2) NOT NULL ,
  `early_price` DECIMAL(13,2) NULL ,
  `student_label` VARCHAR(45) NULL ,
  `student_rules` TEXT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `fk_registration_info_event1` (`event_id` ASC) ,
  CONSTRAINT `fk_registration_info_event1`
    FOREIGN KEY (`event_id` )
    REFERENCES `phpsc`.`event` (`id` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
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
INSERT INTO `phpsc`.`event` (`id`, `location_id`, `name`, `start`, `end`, `submissions_start`, `submissions_end`) VALUES (NULL, 1, 'PHPSC Conference 2012', '2012-10-27', '2012-10-27', '2012-08-06', '2012-09-16');

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

-- -----------------------------------------------------
-- Data for table `phpsc`.`registration_info`
-- -----------------------------------------------------
START TRANSACTION;
USE `phpsc`;
INSERT INTO `phpsc`.`registration_info` (`id`, `event_id`, `start`, `end`, `regular_price`, `early_price`, `student_label`, `student_rules`) VALUES (NULL, 1, '2012-08-06 00:00:00', '2012-10-26 23:59:59', 15, 10, 'Sou estudante da UNIVALI', '<p>Estudantes da UNIVALI (Universidade do Vale do Itajaí) serão contemplados com <strong>100%</strong> de desconto <strong>no valor da inscrição do evento</strong>.</p>\n<p>Será <strong>obrigatória</strong> a apresentação do comprovante de matrícula no credenciamento, no início do evento.</p>\n<p><strong>Importante:</strong> as pessoas que se inscreverem como alunos da UNIVALI e não apresentarem o comprovante de matrícula deverão pagar, no credenciamento, o valor de <strong>R$ 20,00</strong> (correspondente à inscrições realizadas no dia do evento).</p>');

COMMIT;

ALTER TABLE `phpsc`.`user` CHANGE COLUMN `twitter_user` `twitter_user` VARCHAR(60) NULL DEFAULT NULL  ;

CREATE  TABLE IF NOT EXISTS `phpsc`.`sponsor` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `user_id` INT(11) NOT NULL ,
  `name` VARCHAR(45) NOT NULL ,
  `creation_time` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_sponsor_user1` (`user_id` ASC) ,
  CONSTRAINT `fk_sponsor_user1`
    FOREIGN KEY (`user_id` )
    REFERENCES `phpsc`.`user` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE  TABLE IF NOT EXISTS `phpsc`.`event_sponsor` (
  `event_id` INT(11) NOT NULL ,
  `sponsor_id` INT(11) NOT NULL ,
  PRIMARY KEY (`event_id`, `sponsor_id`) ,
  INDEX `fk_event_has_sponsor_sponsor1` (`sponsor_id` ASC) ,
  INDEX `fk_event_has_sponsor_event1` (`event_id` ASC) ,
  CONSTRAINT `fk_event_has_sponsor_event1`
    FOREIGN KEY (`event_id` )
    REFERENCES `phpsc`.`event` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_event_has_sponsor_sponsor1`
    FOREIGN KEY (`sponsor_id` )
    REFERENCES `phpsc`.`sponsor` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

ALTER TABLE `phpsc`.`attendee` ADD COLUMN `arrived` TINYINT(1) NOT NULL DEFAULT 0  AFTER `status` ;

CREATE  TABLE IF NOT EXISTS `phpsc`.`event_organizer` (
  `event_id` INT(11) NOT NULL ,
  `user_id` INT(11) NOT NULL ,
  PRIMARY KEY (`event_id`, `user_id`) ,
  INDEX `fk_event_has_user_user1` (`user_id` ASC) ,
  INDEX `fk_event_has_user_event1` (`event_id` ASC) ,
  CONSTRAINT `fk_event_has_user_event1`
    FOREIGN KEY (`event_id` )
    REFERENCES `phpsc`.`event` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_event_has_user_user1`
    FOREIGN KEY (`user_id` )
    REFERENCES `phpsc`.`user` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;