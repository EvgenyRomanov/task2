CREATE DATABASE `pets`;
USE `pets`;


/* владельцы питомцев */
CREATE TABLE `owners` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL
);


/* кошки, собаки и т.д. */
CREATE TABLE `type_pets` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL UNIQUE
);


/* порода */
CREATE TABLE `breeds` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL UNIQUE
);


/* м/ж */
CREATE TABLE `genders` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(64) NOT NULL
);
INSERT INTO `genders` (`name`) VALUES ('м'), ('ж');


/* награды */
CREATE TABLE `rewards` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(64) NOT NULL UNIQUE
);


/* питомцы */
CREATE TABLE `pets` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `code` INT NOT NULL UNIQUE, 
    `nickname` VARCHAR(255),
    `breed_id` INT,
    `type_id` INT NOT NULL,
    `gender_id` INT NOT NULL,
    `age` INT,
    `owner_id` INT,
    FOREIGN KEY `type_index` (`type_id`) REFERENCES `type_pets` (`id`),
    FOREIGN KEY `breed_index` (`breed_id`) REFERENCES `breeds` (`id`),
    FOREIGN KEY `owner_index` (`owner_id`) REFERENCES `owners` (`id`),
    FOREIGN KEY `gender_index` (`gender_id`) REFERENCES `genders` (`id`)
);


/* связь «многие-ко-многим» */
CREATE TABLE `pets_rewards` (
    `pet_id` INT NOT NULL,
    `reward_id` INT NOT NULL,
    FOREIGN KEY `pet_index` (`pet_id`) REFERENCES `pets` (`id`),
    FOREIGN KEY `reward_index` (`reward_id`) REFERENCES `rewards` (`id`),
    PRIMARY KEY (`pet_id`, `reward_id`)
);


/* связь «многие-ко-многим» */
-- CREATE TABLE `parents` (
--     `pet_id` INT NOT NULL,
--     `parent_code` INT NOT NULL,
--     FOREIGN KEY `pet_index` (`pet_id`) REFERENCES `pets` (`id`),
--     FOREIGN KEY `pet_code` (`parent_code`) REFERENCES `pets` (`code`),
--     PRIMARY KEY (`pet_id`, `parent_code`)
-- );


/* 
Логичнее была таблица выше, но вероятна ситуация, когда родителей (других питомцев) еще нет в базе,
и тогда не чему будет привязаться
*/
CREATE TABLE `parents` (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `parent_code` INT NOT NULL,
    `pet_id` INT NOT NULL
);


CREATE TABLE `users` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `email` VARCHAR(128) NOT NULL UNIQUE,
    `password` CHAR(64) NOT NULL
);