-- state si state 0: pas encore modere, 1:modere success, 2:encours de moderation, 3:banir
ALTER TABLE `api_challenge` ADD `state` TINYINT NOT NULL DEFAULT '0' AFTER `delayed_result`;
-- moderate_by: user qui a modere le paris
ALTER TABLE `api_challenge` ADD `moderate_by` INT NULL AFTER `state`;
-- moderate_at la date du moderation
ALTER TABLE `api_challenge` ADD `moderate_at` DATETIME NULL AFTER `moderate_by`;
ALTER TABLE `api_challenge` ADD INDEX(`moderate_by`);
ALTER TABLE `api_challenge` ADD FOREIGN KEY (`moderate_by`) REFERENCES `auth_user`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `api_challenge` ADD INDEX(`state`);
ALTER TABLE `api_challenge` ADD INDEX(`moderate_at`);