SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';


-- -----------------------------------------------------
-- Table `session`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `session` ;

CREATE  TABLE IF NOT EXISTS `session` (
  `id` CHAR(40) NOT NULL ,
  `user_id` VARCHAR(255) NOT NULL ,
  `create_time` DATETIME NOT NULL ,
  `modify_time` DATETIME NOT NULL ,
  `expiration_time` DATETIME NOT NULL ,
  `authentication_time` DATETIME NOT NULL ,
  `authentication_method` VARCHAR(32) NOT NULL ,
  `user_data` TEXT NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `authorization_code`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `authorization_code` ;

CREATE  TABLE IF NOT EXISTS `authorization_code` (
  `code` CHAR(40) NOT NULL ,
  `session_id` CHAR(40) NOT NULL ,
  `issue_time` DATETIME NOT NULL ,
  `expiration_time` DATETIME NOT NULL ,
  `client_id` VARCHAR(255) NOT NULL ,
  `scope` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`code`) ,
  INDEX `fk_authorization_code_session` (`session_id` ASC) ,
  CONSTRAINT `fk_authorization_code_session`
    FOREIGN KEY (`session_id` )
    REFERENCES `session` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `access_token`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `access_token` ;

CREATE  TABLE IF NOT EXISTS `access_token` (
  `token` CHAR(40) NOT NULL ,
  `session_id` CHAR(40) NOT NULL ,
  `issue_time` DATETIME NOT NULL ,
  `expiration_time` DATETIME NOT NULL ,
  `client_id` VARCHAR(255) NOT NULL ,
  `type` VARCHAR(32) NOT NULL DEFAULT 'bearer' ,
  `scope` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`token`) ,
  INDEX `fk_access_token_session1` (`session_id` ASC) ,
  CONSTRAINT `fk_access_token_session1`
    FOREIGN KEY (`session_id` )
    REFERENCES `session` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `refresh_token`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `refresh_token` ;

CREATE  TABLE IF NOT EXISTS `refresh_token` (
  `token` CHAR(40) NOT NULL ,
  `access_token` CHAR(40) NOT NULL ,
  `issue_time` DATETIME NOT NULL ,
  `expiration_time` DATETIME NOT NULL ,
  `client_id` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`token`) ,
  INDEX `fk_refresh_token_access_token1` (`access_token` ASC) ,
  CONSTRAINT `fk_refresh_token_access_token1`
    FOREIGN KEY (`access_token` )
    REFERENCES `access_token` (`token` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
