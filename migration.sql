ALTER TABLE `bg_order` ADD `unique_code` VARCHAR(255) NOT NULL , ADD `company_id` INT(11) NOT NULL , ADD `com_user_id` INT(11) NOT NULL ;
ALTER TABLE `bg_user_address` CHANGE `uadd_company` `uadd_company` VARCHAR(75) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;
ALTER TABLE `bg_order` CHANGE `unique_code` `unique_code` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
ALTER TABLE `bg_user_profiles` ADD `unique_url` INT(11) NULL DEFAULT NULL ;