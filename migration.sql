UPDATE `synergyd_kilima`.`bg_config` SET `editable` = '1' WHERE `bg_config`.`config_key` = 'PAYPAL_API_USERNAME';

UPDATE `synergyd_kilima`.`bg_config` SET `editable` = '1' WHERE `bg_config`.`config_key` = 'PAYPAL_API_SIGNATURE';

UPDATE `synergyd_kilima`.`bg_config` SET `editable` = '1' WHERE `bg_config`.`config_key` = 'PAYPAL_API_PASSWORD';
UPDATE `synergyd_kilima`.`bg_config` SET `config_label` = 'Paypal Email' WHERE `bg_config`.`config_key` = 'PAYPAL_API_USERNAME';

UPDATE `synergyd_kilima`.`bg_config` SET `config_label` = 'Paypal API Signature' WHERE `bg_config`.`config_key` = 'PAYPAL_API_SIGNATURE';
UPDATE `synergyd_kilima`.`bg_config` SET `config_label` = 'Paypal API Password' WHERE `bg_config`.`config_key` = 'PAYPAL_API_PASSWORD';
UPDATE `synergyd_kilima`.`bg_config` SET `editable` = '0' WHERE `bg_config`.`config_key` = 'PAYPAL_MERCHENT_EMAIL';