SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';


-- -----------------------------------------------------
-- Table `auth_session`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `auth_session` ;

CREATE TABLE IF NOT EXISTS `auth_session` (
  `id` VARCHAR(64) NOT NULL,
  `method` VARCHAR(45) NOT NULL,
  `create_time` DATETIME NOT NULL,
  `expiration_time` DATETIME NOT NULL,
  `user_id` VARCHAR(45) NOT NULL,
  `user_data` TEXT NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `session`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `session` ;

CREATE TABLE IF NOT EXISTS `session` (
  `id` VARCHAR(64) NOT NULL,
  `auth_session_id` VARCHAR(64) NOT NULL,
  `create_time` DATETIME NOT NULL,
  `modify_time` DATETIME NOT NULL,
  `expiration_time` DATETIME NOT NULL,
  `nonce` VARCHAR(64) NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_session_auth_session1_idx` (`auth_session_id` ASC),
  CONSTRAINT `fk_session_auth_session1`
    FOREIGN KEY (`auth_session_id`)
    REFERENCES `auth_session` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `authorization_code`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `authorization_code` ;

CREATE TABLE IF NOT EXISTS `authorization_code` (
  `code` VARCHAR(64) NOT NULL,
  `session_id` VARCHAR(64) NOT NULL,
  `create_time` DATETIME NOT NULL,
  `expiration_time` DATETIME NOT NULL,
  `client_id` VARCHAR(255) NOT NULL,
  `scope` VARCHAR(255) NULL,
  PRIMARY KEY (`code`),
  INDEX `fk_authorization_code_session_idx` (`session_id` ASC),
  UNIQUE INDEX `unique_session_client_scope` (`session_id` ASC, `client_id` ASC, `scope` ASC),
  CONSTRAINT `fk_authorization_code_session`
    FOREIGN KEY (`session_id`)
    REFERENCES `session` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `access_token`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `access_token` ;

CREATE TABLE IF NOT EXISTS `access_token` (
  `token` VARCHAR(64) NOT NULL,
  `session_id` VARCHAR(64) NOT NULL,
  `create_time` DATETIME NOT NULL,
  `expiration_time` DATETIME NOT NULL,
  `client_id` VARCHAR(255) NOT NULL,
  `type` VARCHAR(32) NOT NULL DEFAULT 'bearer',
  `scope` VARCHAR(255) NULL,
  PRIMARY KEY (`token`),
  INDEX `fk_access_token_session1_idx` (`session_id` ASC),
  CONSTRAINT `fk_access_token_session1`
    FOREIGN KEY (`session_id`)
    REFERENCES `session` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `refresh_token`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `refresh_token` ;

CREATE TABLE IF NOT EXISTS `refresh_token` (
  `token` VARCHAR(64) NOT NULL,
  `access_token` VARCHAR(64) NOT NULL,
  `create_time` DATETIME NOT NULL,
  `expiration_time` DATETIME NOT NULL,
  `client_id` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`token`),
  INDEX `fk_refresh_token_access_token1_idx` (`access_token` ASC),
  CONSTRAINT `fk_refresh_token_access_token1`
    FOREIGN KEY (`access_token`)
    REFERENCES `access_token` (`token`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
