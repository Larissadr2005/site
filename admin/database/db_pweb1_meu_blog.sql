SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema meu_blog
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema meu_blog
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `meu_blog` DEFAULT CHARACTER SET utf8 ;
USE `meu_blog` ;

-- -----------------------------------------------------
-- Table `meu_blog`.`usuarios`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `meu_blog`.`usuarios` (
  `id` INT AUTO_INCREMENT NOT NULL, 
  `nome` VARCHAR(255) NOT NULL,
  `sobrenome` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `password` VARCHAR(127) NOT NULL,
  `phone1` VARCHAR(45) NULL,
  `tipo_phone1` VARCHAR(16) NULL,
  `phone2` VARCHAR(45) NULL,
  `tipo_phone2` VARCHAR(16) NULL,
  `created_at` TIMESTAMP NOT NULL,
  `updated_at` TIMESTAMP NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `meu_blog`.`categorias`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `meu_blog`.`categorias` (
  `id` INT AUTO_INCREMENT NOT NULL,
  `titulo` VARCHAR(45) NOT NULL,
  `descricao` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NOT NULL,
  `updated_at` TIMESTAMP NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `meu_blog`.`posts`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `meu_blog`.`posts` (
  `id` INT AUTO_INCREMENT NOT NULL,
  `user_id` INT NOT NULL,
  `categoria_id` INT NOT NULL,
  `titulo` VARCHAR(45) NOT NULL,
  `descricao` VARCHAR(255) NOT NULL,
  `post_path` TEXT NOT NULL,
  `created_at` TIMESTAMP NOT NULL,
  `updated_at` TIMESTAMP NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_posts_categorias_idx` (`categoria_id` ASC) VISIBLE,
  INDEX `fk_posts_usuarios1_idx` (`user_id` ASC) VISIBLE,
  CONSTRAINT `fk_posts_categorias`
    FOREIGN KEY (`categoria_id`)
    REFERENCES `meu_blog`.`categorias` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_posts_usuarios1`
    FOREIGN KEY (`user_id`)
    REFERENCES `meu_blog`.`usuarios` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `meu_blog`.`comentarios`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `meu_blog`.`comentarios` (
  `id` INT AUTO_INCREMENT NOT NULL,
  `post_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  `comentario` VARCHAR(255) NOT NULL,
  `likes` INT NOT NULL,
  `dislikes` INT NOT NULL,
  `created_at` TIMESTAMP NOT NULL,
  `updated_at` TIMESTAMP NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_comentarios_posts1_idx` (`post_id` ASC) VISIBLE,
  INDEX `fk_comentarios_usuarios1_idx` (`user_id` ASC) VISIBLE,
  CONSTRAINT `fk_comentarios_posts1`
    FOREIGN KEY (`post_id`)
    REFERENCES `meu_blog`.`posts` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_comentarios_usuarios1`
    FOREIGN KEY (`user_id`)
    REFERENCES `meu_blog`.`usuarios` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `meu_blog`.`enderecos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `meu_blog`.`enderecos` (
  `id` INT AUTO_INCREMENT NOT NULL,
  `user_id` INT NOT NULL,
  `nome` VARCHAR(255) NOT NULL,
  `rua` VARCHAR(255) NOT NULL,
  `numero` VARCHAR(16) NOT NULL,
  `cidade` VARCHAR(255) NOT NULL,
  `bairro` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NOT NULL,
  `updated_at` TIMESTAMP NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_enderecos_usuarios1_idx` (`user_id` ASC) VISIBLE,
  CONSTRAINT `fk_enderecos_usuarios1`
    FOREIGN KEY (`user_id`)
    REFERENCES `meu_blog`.`usuarios` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- DADOS DE TESTE ROBUSTOS
-- -----------------------------------------------------

-- Inserir usuários diversos
-- Senha padrão para todos: 123456 (hash gerado pelo PHP)
INSERT INTO usuarios (nome, sobrenome, email, password, phone1, tipo_phone1, phone2, tipo_phone2, created_at, updated_at) VALUES 
('Admin', 'Sistema', 'admin@test.com', '$2y$10$cJ0lXJaPOV0DBsZK8rGiCeZgtqyerr6STUf46KD5IzoXFJvvOVNWe', '(11) 98765-4321', 'Celular', '(11) 3456-7890', 'Comercial', '2024-01-15 10:00:00', NOW());
