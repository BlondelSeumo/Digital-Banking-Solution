-- table structure update query start
ALTER TABLE `deposits` ADD `success_url` VARCHAR(255) NULL DEFAULT NULL AFTER `admin_feedback`,
ADD `failed_url` VARCHAR(255) NULL DEFAULT NULL AFTER `success_url`,
ADD `last_cron` INT NOT NULL DEFAULT '0' AFTER `failed_url`;

ALTER TABLE `frontends` ADD `seo_content` LONGTEXT NULL DEFAULT NULL AFTER `data_values`;
ALTER TABLE `frontends` ADD `slug` VARCHAR(255) NULL DEFAULT NULL AFTER `tempname`;

ALTER TABLE `gateways` ADD `image` VARCHAR(255) NULL DEFAULT NULL AFTER `alias`;

ALTER TABLE `gateway_currencies` DROP `image`;

ALTER TABLE `general_settings` DROP `system_info`;
ALTER TABLE `general_settings` CHANGE `push_configuration` `push_template` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `general_settings` ADD `email_from_name` VARCHAR(255) NULL DEFAULT NULL AFTER `email_from`;
ALTER TABLE `general_settings` CHANGE `push_template` `push_template` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `sms_from`;
ALTER TABLE `general_settings` ADD `push_title` VARCHAR(255) NULL DEFAULT NULL AFTER `sms_from`;
ALTER TABLE `general_settings` ADD `firebase_config` TEXT NULL DEFAULT NULL AFTER `sms_config`;
ALTER TABLE `general_settings` ADD `in_app_payment` TINYINT(1) NOT NULL DEFAULT '1' AFTER `force_ssl`;
ALTER TABLE `general_settings` ADD `socialite_credentials` TEXT NULL DEFAULT NULL AFTER `active_template`;
ALTER TABLE `general_settings` ADD `available_version` VARCHAR(40) NULL DEFAULT NULL AFTER `socialite_credentials`;
ALTER TABLE `general_settings` ADD `paginate_number` INT UNSIGNED NOT NULL DEFAULT '0' AFTER `system_customized`, ADD `currency_format` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '1 => Both, 2 => Text Only, 3 = Symbol Only' AFTER `paginate_number`;
ALTER TABLE `general_settings` CHANGE `idle_time_threshold` `idle_time_threshold` INT NULL DEFAULT '0' COMMENT 'value in seconds' AFTER `referral_commission_count`;

ALTER TABLE `languages` ADD `image` VARCHAR(255) NULL DEFAULT NULL AFTER `is_default`;

ALTER TABLE `notification_logs` ADD `image` VARCHAR(255) NULL DEFAULT NULL AFTER `notification_type`;

ALTER TABLE `notification_templates` CHANGE `subj` `subject` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `notification_templates` ADD `push_title` VARCHAR(255) NULL DEFAULT NULL AFTER `subject`;
ALTER TABLE `notification_templates` CHANGE `push_notification_body` `push_body` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `notification_templates` ADD `email_sent_from_name` VARCHAR(40) NULL DEFAULT NULL AFTER `email_status`, ADD `email_sent_from_address` VARCHAR(40) NULL DEFAULT NULL AFTER `email_sent_from_name`;
ALTER TABLE `notification_templates` ADD `sms_sent_from` VARCHAR(40) NULL DEFAULT NULL AFTER `sms_status`;
ALTER TABLE `notification_templates` CHANGE `push_notification_status` `push_status` TINYINT(1) NOT NULL DEFAULT '1';
UPDATE `general_settings` SET `available_version` = '3.0' WHERE `general_settings`.`id` = 1;


ALTER TABLE `operators` DROP `supports_local_amount`;
ALTER TABLE `operators` DROP `supports_geographical_recharge_plans`;
ALTER TABLE `operators` DROP `sender_currency_code`;
ALTER TABLE `operators` DROP `sender_currency_symbol`;
ALTER TABLE `operators` DROP `commission`;
ALTER TABLE `operators` DROP `international_discount`;
ALTER TABLE `operators` DROP `local_discount`;
ALTER TABLE `operators` DROP `most_popular_local_amount`;
ALTER TABLE `operators` DROP `local_min_amount`;
ALTER TABLE `operators` DROP `local_max_amount`;
ALTER TABLE `operators` DROP `suggested_amounts_map`;
ALTER TABLE `operators` DROP `fees`;
ALTER TABLE `operators` DROP `geographical_recharge_plans`;
ALTER TABLE `operators` DROP `reloadly_status`;
ALTER TABLE `operators` DROP `fx`;

ALTER TABLE `pages` ADD `seo_content` TEXT NULL DEFAULT NULL AFTER `secs`;

ALTER TABLE `users` ADD `dial_code` VARCHAR(40) NULL DEFAULT NULL AFTER `email`;
ALTER TABLE `users` ADD `country_name` VARCHAR(255) NULL DEFAULT NULL AFTER `image`;
ALTER TABLE `users` ADD `city` VARCHAR(255) NULL DEFAULT NULL AFTER `country_code`, ADD `state` VARCHAR(255) NULL DEFAULT NULL AFTER `city`, ADD `zip` VARCHAR(255) NULL DEFAULT NULL AFTER `state`;
ALTER TABLE `users` ADD `kyc_rejection_reason` VARCHAR(255) NULL DEFAULT NULL AFTER `kyc_data`;
ALTER TABLE `users` ADD `provider` VARCHAR(255) NULL DEFAULT NULL AFTER `remember_token`;
ALTER TABLE `users` ADD `provider_id` VARCHAR(255) NULL DEFAULT NULL AFTER `provider`;

ALTER TABLE `withdraw_methods` ADD `image` VARCHAR(255) NULL DEFAULT NULL AFTER `name`;

ALTER TABLE `personal_access_tokens` ADD `expires_at` TIMESTAMP NULL DEFAULT NULL AFTER `last_used_at`;

DROP TABLE `user_notifications`;
-- table structure update query end

-- data change query start
UPDATE `general_settings` SET `socialite_credentials` = '{\"google\":{\"client_id\":\"------------\",\"client_secret\":\"-------------\",\"status\":1},\"facebook\":{\"client_id\":\"------\",\"client_secret\":\"------\",\"status\":1},\"linkedin\":{\"client_id\":\"-----\",\"client_secret\":\"-----\",\"status\":1}}' WHERE `general_settings`.`id` = 1;

ALTER TABLE `general_settings` CHANGE `sms_body` `sms_template` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;

ALTER TABLE `notification_templates` CHANGE `shortcodes` `shortcodes` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `push_body`, CHANGE `email_status` `email_status` TINYINT(1) NOT NULL DEFAULT '1' AFTER `shortcodes`, CHANGE `sms_status` `sms_status` TINYINT(1) NOT NULL DEFAULT '1' AFTER `email_status`;


UPDATE `extensions` SET `script` = '<script async src=\"https://www.googletagmanager.com/gtag/js?id={{measurement_id}}\"></script>\n                <script>\n                  window.dataLayer = window.dataLayer || [];\n                  function gtag(){dataLayer.push(arguments);}\n                  gtag(\"js\", new Date());\n                \n                  gtag(\"config\", \"{{measurement_id}}\");\n                </script>' WHERE `extensions`.`act` = 'google-analytics';

UPDATE `extensions` SET `shortcode` = '{\"measurement_id\":{\"title\":\"Measurement ID\",\"value\":\"------\"}}' WHERE `extensions`.`act` = 'google-analytics';


INSERT INTO `gateways` (`id`, `form_id`, `code`, `name`, `alias`, `status`, `gateway_parameters`, `supported_currencies`, `crypto`, `extra`, `description`, `created_at`, `updated_at`) VALUES (NULL, '0', '510', 'Binance', 'Binance', '1', '{\"api_key\":{\"title\":\"API Key\",\"global\":true,\"value\":\"tsu3tjiq0oqfbtmlbevoeraxhfbp3brejnm9txhjxcp4to29ujvakvfl1ibsn3ja\"},\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"jzngq4t04ltw8d4iqpi7admfl8tvnpehxnmi34id1zvfaenbwwvsvw7llw3zdko8\"},\"merchant_id\":{\"title\":\"Merchant ID\",\"global\":true,\"value\":\"231129033\"}}', '{\"BTC\":\"Bitcoin\",\"USD\":\"USD\",\"BNB\":\"BNB\"}', '1', '', NULL, NULL, '2023-02-14 11:08:04');

INSERT INTO `gateways` (`id`, `form_id`, `code`, `name`, `alias`, `status`, `gateway_parameters`, `supported_currencies`, `crypto`, `extra`, `description`, `created_at`, `updated_at`) VALUES (NULL, '0', '123', 'SslCommerz', 'SslCommerz', '1', '{\"store_id\": {\"title\": \"Store ID\",\"global\": true,\"value\": \"---------\"},\"store_password\": {\"title\": \"Store Password\",\"global\": true,\"value\": \"----------\"}}', '{\"BDT\":\"BDT\",\"USD\":\"USD\",\"EUR\":\"EUR\",\"SGD\":\"SGD\",\"INR\":\"INR\",\"MYR\":\"MYR\"}', '0', NULL, NULL, NULL, '2023-05-06 13:43:01');

INSERT INTO `gateways` (`id`, `form_id`, `code`, `name`, `alias`, `status`, `gateway_parameters`, `supported_currencies`, `crypto`, `extra`, `description`, `created_at`, `updated_at`) VALUES (NULL, '0', '126', 'Aamarpay', 'Aamarpay', '1', '{\"store_id\": {\"title\": \"Store ID\",\"global\": true,\"value\": \"---------\"},\"signature_key\": {\"title\": \"Signature Key\",\"global\": true,\"value\": \"----------\"}}', '{\"BDT\":\"BDT\"}', '0', NULL, NULL, NULL, '2023-05-06 13:43:01');


UPDATE `gateways` SET `extra` = '{\"cron\":{\"title\": \"Cron Job URL\",\"value\":\"ipn.Binance\"}}' WHERE `gateways`.`alias` = 'Binance';
ALTER TABLE `cron_jobs` CHANGE `is_default` `is_default` TINYINT(1) NOT NULL DEFAULT '1';
ALTER TABLE `cron_job_logs` CHANGE `cron_job_id` `cron_job_id` INT UNSIGNED NOT NULL DEFAULT '0';
UPDATE `notification_templates` SET `name` = 'KYC Rejected' WHERE `notification_templates`.`act` = 'KYC_REJECT';
ALTER TABLE `support_attachments` CHANGE `support_message_id` `support_message_id` INT UNSIGNED NOT NULL DEFAULT '0';
UPDATE `notification_templates` SET `shortcodes` = '{\"reason\":\"Rejection Reason\"}' WHERE `notification_templates`.`act` = 'KYC_REJECT';
DELETE FROM `gateway_currencies` WHERE `gateway_currencies`.`method_code` = 108;
DELETE FROM `gateway_currencies` WHERE `gateway_currencies`.`gateway_alias` = 'NowPaymentsCheckout';
UPDATE `gateways` SET `supported_currencies` = '{\"USD\":\"USD\",\"EUR\":\"EUR\"}' WHERE `gateways`.`alias` = 'NowPaymentsCheckout';
UPDATE `gateways` SET `crypto` = '0' WHERE `gateways`.`alias` = 'TwoCheckout';

UPDATE `frontends` SET `tempname` = 'indigo_fusion' WHERE `frontends`.`tempname` = 'templates.indigo_fusion.';
UPDATE `frontends` SET `tempname` = 'crystal_sky' WHERE `frontends`.`tempname` = 'templates.crystal_sky.';

-- add new table
CREATE TABLE `table_configurations` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `admin_id` int UNSIGNED NOT NULL DEFAULT '0',
  `table_name` varchar(40) NOT NULL,
  `visible_columns` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Update slugs for blog elements
INSERT INTO `cron_jobs` (`name`, `alias`, `action`, `url`, `cron_schedule_id`, `next_run`, `last_run`, `is_running`, `is_default`, `created_at`, `updated_at`) VALUES ('Update Reloadly Operators', 'update_operator', '[\"App\\\\Http\\\\Controllers\\\\CronController\", \"updateOperators\"]', NULL, 1, '2024-06-06 12:19:14', '2024-06-06 12:14:14', 1, 1, '2023-06-21 17:29:14', '2024-06-06 06:14:14');


UPDATE frontends
SET slug = LOWER(
  REPLACE(
        REPLACE(
            REPLACE(
                REPLACE(
                    JSON_UNQUOTE(JSON_EXTRACT(data_values, '$.title')), 
                    ' ', '-'
                ), 
                ',', ''
            ), 
            '.', ''
        ),
        '?', ''
    )
)
WHERE data_keys = 'blog.element';

-- Update slugs for policy pages
UPDATE frontends
SET slug = LOWER(
    REPLACE(
        REPLACE(
            REPLACE(
                REPLACE(
                    JSON_UNQUOTE(JSON_EXTRACT(data_values, '$.title')), 
                    ' ', '-'
                ), 
                ',', ''
            ), 
            '.', ''
        ),
        '?', ''
    )
)
WHERE data_keys = 'policy_pages.element';

UPDATE `users`
SET `country_name` = NULLIF(NULLIF(JSON_UNQUOTE(JSON_EXTRACT(`address`, '$.country')), ''), 'null'),
    `state` = NULLIF(NULLIF(JSON_UNQUOTE(JSON_EXTRACT(`address`, '$.state')), ''), 'null'),
    `city` = NULLIF(NULLIF(JSON_UNQUOTE(JSON_EXTRACT(`address`, '$.city')), ''), 'null'),
    `zip` = NULLIF(NULLIF(JSON_UNQUOTE(JSON_EXTRACT(`address`, '$.zip')), ''), 'null');
UPDATE `users` SET `address` = NULLIF(NULLIF(JSON_UNQUOTE(JSON_EXTRACT(`address`, '$.address')), ''), 'null');
-- data change query end



UPDATE transactions SET remark = "withdraw_refund" WHERE remark = "withdraw_reject";

ALTER TABLE `operators` ADD COLUMN `operator_group_id` INT UNSIGNED DEFAULT '0' NOT NULL AFTER `unique_id`;


ALTER table `countries` convert to character set utf8mb4 collate utf8mb4_unicode_ci;
ALTER table `operators` convert to character set utf8mb4 collate utf8mb4_unicode_ci;

--
-- Table structure for table `operator_groups`
--
CREATE TABLE `operator_groups` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `country_id` INT UNSIGNED NOT NULL DEFAULT 0,
  `name` VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE migrations;

CREATE TABLE permissions_backup AS SELECT * FROM permissions;
TRUNCATE TABLE permissions;
ALTER TABLE permissions MODIFY COLUMN name VARCHAR(255);

INSERT INTO `permissions` (`id`, `name`, `group`, `code`) VALUES
(1, 'View Dashboard', 'AdminController', 'admin.dashboard'),
(2, 'View Notifications', 'AdminController', 'admin.notifications'),
(3, 'Mark Single Notification As Read', 'AdminController', 'admin.notification.read'),
(4, 'Mark All Notification As Read', 'AdminController', 'admin.notifications.read.all'),
(5, 'Delete Single Notification', 'AdminController', 'admin.notifications.delete.single'),
(6, 'Delete All Notifications', 'AdminController', 'admin.notifications.delete.all'),
(7, 'Download Files Submitted by Users', 'AdminController', 'admin.download.attachment'),
(8, 'View All Accounts List', 'ManageAccountsController', 'admin.users.all'),
(9, 'View Active Accounts List', 'ManageAccountsController', 'admin.users.active'),
(10, 'View Banned Accounts List', 'ManageAccountsController', 'admin.users.banned'),
(11, 'View Email Verified Accounts List', 'ManageAccountsController', 'admin.users.email.verified'),
(12, 'View Email Unverified Accounts List', 'ManageAccountsController', 'admin.users.email.unverified'),
(13, 'View Mobile Verified Accounts List', 'ManageAccountsController', 'admin.users.mobile.verified'),
(14, 'View Mobile Unverified Accounts List', 'ManageAccountsController', 'admin.users.mobile.unverified'),
(15, 'View KYC Verified Users List', 'ManageAccountsController', 'admin.users.kyc.verified'),
(16, 'View KYC Unverified Users List', 'ManageAccountsController', 'admin.users.kyc.unverified'),
(17, 'View KYC Pending Users List', 'ManageAccountsController', 'admin.users.kyc.pending'),
(18, 'View Profile Incomplete Accounts List', 'ManageAccountsController', 'admin.users.profile.incomplete'),
(19, 'View Profile Completed Accounts List', 'ManageAccountsController', 'admin.users.profile.completed'),
(20, 'View Account\'s Details', 'ManageAccountsController', 'admin.users.detail'),
(21, 'Update Account\'s Details', 'ManageAccountsController', 'admin.users.update'),
(22, 'View Account\'s KYC Details', 'ManageAccountsController', 'admin.users.kyc.details'),
(23, 'Approve Account\'s KYC Data', 'ManageAccountsController', 'admin.users.kyc.approve'),
(24, 'Reject Account\'s KYC Data', 'ManageAccountsController', 'admin.users.kyc.reject'),
(25, 'Add/Sub Account\'s Balance', 'ManageAccountsController', 'admin.users.add.sub.balance'),
(26, 'Send Notification to a Single Account', 'ManageAccountsController', 'admin.users.notification.single'),
(27, 'View the Send Notification to All Accounts Page', 'ManageAccountsController', 'admin.users.notification.all'),
(28, 'Submit the Send Notification to All Accounts Form', 'ManageAccountsController', 'admin.users.notification.all.send'),
(29, 'View Account\'s Notification Log', 'ManageAccountsController', 'admin.users.notification.log'),
(30, 'View Account\'s Beneficiaries', 'ManageAccountsController', 'admin.users.beneficiaries'),
(31, 'View Beneficiary Details', 'ManageAccountsController', 'admin.users.beneficiary.details'),
(32, 'Login As Account', 'ManageAccountsController', 'admin.users.login'),
(33, 'Ban/Unban a Account', 'ManageAccountsController', 'admin.users.status'),
(34, 'View All Deposits List', 'DepositController', 'admin.deposit.list'),
(35, 'View Pending Deposits List', 'DepositController', 'admin.deposit.pending'),
(36, 'View Rejected Deposits List', 'DepositController', 'admin.deposit.rejected'),
(37, 'View Approved Deposits List', 'DepositController', 'admin.deposit.approved'),
(38, 'View Successful Deposits List', 'DepositController', 'admin.deposit.successful'),
(39, 'View Initiated Deposits List', 'DepositController', 'admin.deposit.initiated'),
(40, 'View Deposit\'s Details', 'DepositController', 'admin.deposit.details'),
(41, 'Approve a Manual Deposit', 'DepositController', 'admin.deposit.approve'),
(42, 'Reject a Manual Deposit', 'DepositController', 'admin.deposit.reject'),
(43, 'View All Withdrawals List', 'WithdrawalController', 'admin.withdraw.data.all'),
(44, 'View Pending Withdrawals List', 'WithdrawalController', 'admin.withdraw.data.pending'),
(45, 'View Approved Withdrawals List', 'WithdrawalController', 'admin.withdraw.data.approved'),
(46, 'View Rejected Withdrawals List', 'WithdrawalController', 'admin.withdraw.data.rejected'),
(47, 'View Withdrawal Details', 'WithdrawalController', 'admin.withdraw.data.details'),
(48, 'Approve a Withdrawal Request', 'WithdrawalController', 'admin.withdraw.data.approve'),
(49, 'Reject a Withdrawal Request', 'WithdrawalController', 'admin.withdraw.data.reject'),
(50, 'View All FDRs List', 'FdrController', 'admin.fdr.index'),
(51, 'View Running FDRs List', 'FdrController', 'admin.fdr.running'),
(52, 'View Closed FDRs List', 'FdrController', 'admin.fdr.closed'),
(53, 'View FDRs List Having Due Installments', 'FdrController', 'admin.fdr.due'),
(54, 'Pay Due Installments of FDR', 'FdrController', 'admin.fdr.due.pay'),
(55, 'View FDR Installments', 'FdrController', 'admin.fdr.installments'),
(56, 'View All DPS List', 'DpsController', 'admin.dps.index'),
(57, 'View Running DPS List', 'DpsController', 'admin.dps.running'),
(58, 'View Matured DPS List', 'DpsController', 'admin.dps.matured'),
(59, 'View Closed DPS List', 'DpsController', 'admin.dps.closed'),
(60, 'View DPS List Having Due Installment', 'DpsController', 'admin.dps.due'),
(61, 'View DPS Installments', 'DpsController', 'admin.dps.installments'),
(62, 'View All Loans List', 'LoanController', 'admin.loan.index'),
(63, 'View Running Loans List', 'LoanController', 'admin.loan.running'),
(64, 'View Pending Loans List', 'LoanController', 'admin.loan.pending'),
(65, 'View Rejected Loans List', 'LoanController', 'admin.loan.rejected'),
(66, 'View Paid Loans List', 'LoanController', 'admin.loan.paid'),
(67, 'View Loans List Having Due Installment', 'LoanController', 'admin.loan.due'),
(68, 'View Loan Details Page', 'LoanController', 'admin.loan.details'),
(69, 'Approve a Loan Request', 'LoanController', 'admin.loan.approve'),
(70, 'Reject a Loan Request', 'LoanController', 'admin.loan.reject'),
(71, 'View Loan Installments', 'LoanController', 'admin.loan.installments'),
(72, 'View All Transfers List', 'MoneyTransferController', 'admin.transfers.index'),
(73, 'View Pending Transfers List', 'MoneyTransferController', 'admin.transfers.pending'),
(74, 'View Rejected Transfers List', 'MoneyTransferController', 'admin.transfers.rejected'),
(75, 'View Own Bank Transfers List', 'MoneyTransferController', 'admin.transfers.own'),
(76, 'View Other Bank Transfers List', 'MoneyTransferController', 'admin.transfers.other'),
(77, 'View Wire Transfers List', 'MoneyTransferController', 'admin.transfers.wire'),
(78, 'View Transfers Details Page', 'MoneyTransferController', 'admin.transfers.details'),
(79, 'Complete a Transfer Request', 'MoneyTransferController', 'admin.transfers.complete'),
(80, 'Reject a Transfer Request', 'MoneyTransferController', 'admin.transfers.reject'),
(81, 'View Login History', 'ReportController', 'admin.report.login.history'),
(82, 'View Notification History', 'ReportController', 'admin.report.notification.history'),
(83, 'View Notification Details', 'ReportController', 'admin.report.email.details'),
(84, 'View Transaction History', 'ReportController', 'admin.report.transaction'),
(85, 'View FDR Plans', 'FdrPlanController', 'admin.plans.fdr.index'),
(86, 'Add/Update FDR Plan', 'FdrPlanController', 'admin.plans.fdr.save'),
(87, 'Enable/Disable an FDR Plan', 'FdrPlanController', 'admin.plans.fdr.status'),
(88, 'View DPS Plans', 'DpsPlanController', 'admin.plans.dps.index'),
(89, 'View Add New DPS Plan Page', 'DpsPlanController', 'admin.plans.dps.add'),
(90, 'View Edit DPS Plan Page', 'DpsPlanController', 'admin.plans.dps.edit'),
(91, 'Add/Update DPS a Plan', 'DpsPlanController', 'admin.plans.dps.save'),
(92, 'Enable/Disable a DPS Plan', 'DpsPlanController', 'admin.plans.dps.status'),
(93, 'View Loan Plans', 'LoanPlanController', 'admin.plans.loan.index'),
(94, 'View Add New Loan Plan Page', 'LoanPlanController', 'admin.plans.loan.create'),
(95, 'View Edit Loan Plan Page', 'LoanPlanController', 'admin.plans.loan.edit'),
(96, 'Add/Update Loan Plan', 'LoanPlanController', 'admin.plans.loan.save'),
(97, 'Enable/Disable a Loan Plan', 'LoanPlanController', 'admin.plans.loan.status'),
(98, 'View Other Banks List', 'OtherBankController', 'admin.bank.index'),
(99, 'View Add New Bank Page', 'OtherBankController', 'admin.bank.create'),
(100, 'View Edit Bank Page', 'OtherBankController', 'admin.bank.edit'),
(101, 'Add/Update Other Bank Details', 'OtherBankController', 'admin.bank.store'),
(102, 'Enable/Disable Other Bank', 'OtherBankController', 'admin.bank.change.status'),
(103, 'View Wire Transfer Setting', 'WireTransferSettingController', 'admin.wire.transfer.setting'),
(104, 'Update Wire Transfer Setting', 'WireTransferSettingController', 'admin.wire.transfer.setting.save'),
(105, 'View Wire Transfer Form', 'WireTransferSettingController', 'admin.wire.transfer.form'),
(106, 'Update Wire Transfer Form', 'WireTransferSettingController', 'admin.wire.transfer.form.save'),
(107, 'View Countries List', 'AirtimeController', 'admin.airtime.countries'),
(108, 'Fetch Countries from API', 'AirtimeController', 'admin.airtime.countries.fetch'),
(109, 'Save the Fetched Countries', 'AirtimeController', 'admin.airtime.countries.save'),
(110, 'Enable/Disable a Country', 'AirtimeController', 'admin.airtime.country.status'),
(111, 'View All Operators List', 'AirtimeController', 'admin.airtime.operators'),
(112, 'Fetch Operators from API', 'AirtimeController', 'admin.airtime.operators.fetch'),
(113, 'Save Fetched Operators', 'AirtimeController', 'admin.airtime.operators.save'),
(114, 'Enable/Disable an Operator', 'AirtimeController', 'admin.airtime.operator.status'),
(115, 'View All Branches', 'BranchController', 'admin.branch.index'),
(116, 'View Add New Branch Page', 'BranchController', 'admin.branch.add'),
(117, 'Add/Update Branch ', 'BranchController', 'admin.branch.save'),
(118, 'View Branch Details Page', 'BranchController', 'admin.branch.details'),
(119, 'Enable/Disable a Branch', 'BranchController', 'admin.branch.status'),
(120, 'View Branch Staff List', 'BranchStaffController', 'admin.branch.staff.index'),
(121, 'Add New Branch Staff', 'BranchStaffController', 'admin.branch.staff.add'),
(122, 'Add/Update Branch Staff', 'BranchStaffController', 'admin.branch.staff.save'),
(123, 'View Branch Staff Details', 'BranchStaffController', 'admin.branch.staff.details'),
(124, 'Enable/Disable Branch Staff', 'BranchStaffController', 'admin.branch.staff.status'),
(125, 'Login As Another Branch Staff', 'BranchStaffController', 'admin.branch.staff.login'),
(126, 'View Frontend Content Management Options', 'FrontendController', 'admin.frontend.index'),
(127, 'View Frontend Sections Content', 'FrontendController', 'admin.frontend.sections'),
(128, 'Update Frontend Sections Content', 'FrontendController', 'admin.frontend.sections.content'),
(129, 'View Frontend Sections Single Element', 'FrontendController', 'admin.frontend.sections.element'),
(130, 'View Frontend Sections Element SEO', 'FrontendController', 'admin.frontend.sections.element.seo'),
(131, 'Update Frontend Sections Element SEO', 'FrontendController', 'admin.frontend.sections.element.seo.update'),
(132, 'Remove Frontend Section Element', 'FrontendController', 'admin.frontend.remove'),
(133, 'Manage Pages', 'FrontendController', 'admin.frontend.manage.pages'),
(134, 'Add New Page', 'FrontendController', 'admin.frontend.manage.pages.save'),
(135, 'Update Page Info', 'FrontendController', 'admin.frontend.manage.pages.update'),
(136, 'Delete a Page', 'FrontendController', 'admin.frontend.manage.pages.delete'),
(137, 'View Sections of a Page', 'FrontendController', 'admin.frontend.manage.section'),
(138, 'Update Sections of a Page', 'FrontendController', 'admin.frontend.manage.section.update'),
(139, 'View the Manage SEO for Specific Page', 'FrontendController', 'admin.frontend.manage.pages.seo'),
(140, 'Submit the Manage SEO Form', 'FrontendController', 'admin.frontend.manage.pages.seo.store'),
(141, 'View All Frontend Templates', 'FrontendController', 'admin.frontend.templates'),
(142, 'Change Active Template', 'FrontendController', 'admin.frontend.templates.active'),
(143, 'View Cookie Policy', 'FrontendController', 'admin.setting.cookie'),
(144, 'Update Cookie Policy', 'FrontendController', 'admin.setting.cookie.submit'),
(145, 'View Global SEO Manager', 'FrontendController', 'admin.frontend.seo'),
(146, 'Update Global SEO Manager', 'FrontendController', 'admin.frontend.seo.update'),
(147, 'View Sitemap Setting Page', 'FrontendController', 'admin.setting.sitemap'),
(148, 'Update Setting Sitemap', 'FrontendController', 'admin.setting.sitemap.submit'),
(149, 'View the Robots.txt Setting', 'FrontendController', 'admin.setting.robot'),
(150, 'Update the Robots.txt Setting', 'FrontendController', 'admin.setting.robot.submit'),
(151, 'View Custom CSS', 'FrontendController', 'admin.setting.custom.css'),
(152, 'Update Custom CSS', 'FrontendController', 'admin.setting.custom.css.submit'),
(153, 'View Maintenance Mode Content', 'FrontendController', 'admin.maintenance.mode'),
(154, 'Update Maintenance Mode Content', 'FrontendController', 'admin.maintenance.mode.submit'),
(155, 'View Languages List', 'LanguageController', 'admin.language.manage'),
(156, 'Add a New Language', 'LanguageController', 'admin.language.manage.store'),
(157, 'Update a Language', 'LanguageController', 'admin.language.manage.update'),
(158, 'Delete a Language', 'LanguageController', 'admin.language.manage.delete'),
(159, 'Fetch Keywords from System', 'LanguageController', 'admin.language.get.key'),
(160, 'View Keywords List', 'LanguageController', 'admin.language.key'),
(161, 'Import Keywords', 'LanguageController', 'admin.language.import.lang'),
(162, 'Add a New Keyword', 'LanguageController', 'admin.language.store.key'),
(163, 'Delete a Keyword', 'LanguageController', 'admin.language.delete.key'),
(164, 'Update a Keyword', 'LanguageController', 'admin.language.update.key'),
(165, 'View Automatic Gateways List', 'AutomaticGatewayController', 'admin.gateway.automatic.index'),
(166, 'View the Update Automatic Gateway Page', 'AutomaticGatewayController', 'admin.gateway.automatic.edit'),
(167, 'Submit the Update Automatic Gateway Form', 'AutomaticGatewayController', 'admin.gateway.automatic.update'),
(168, 'Remove a Gateway Currency', 'AutomaticGatewayController', 'admin.gateway.automatic.remove'),
(169, 'Enable/Disable a Gateway', 'AutomaticGatewayController', 'admin.gateway.automatic.status'),
(170, 'View Manual Gateway List', 'ManualGatewayController', 'admin.gateway.manual.index'),
(171, 'View the Add New Gateway Page', 'ManualGatewayController', 'admin.gateway.manual.create'),
(172, 'Submit the Add New Gateway Form', 'ManualGatewayController', 'admin.gateway.manual.store'),
(173, 'View the Update Gateway Page', 'ManualGatewayController', 'admin.gateway.manual.edit'),
(174, 'Submit the Update Gateway Form', 'ManualGatewayController', 'admin.gateway.manual.update'),
(175, 'Enable/Disable a Gateway', 'ManualGatewayController', 'admin.gateway.manual.status'),
(176, 'View Withdrawal Methods List', 'WithdrawMethodController', 'admin.withdraw.method.index'),
(177, 'View the Add New Method Page', 'WithdrawMethodController', 'admin.withdraw.method.create'),
(178, 'Submit the Add New Method Form', 'WithdrawMethodController', 'admin.withdraw.method.store'),
(179, 'View the Update Method Page', 'WithdrawMethodController', 'admin.withdraw.method.edit'),
(180, 'Submit the Update Method Form', 'WithdrawMethodController', 'admin.withdraw.method.update'),
(181, 'Enable/Disable a Method', 'WithdrawMethodController', 'admin.withdraw.method.status'),
(182, 'View Extensions List', 'ExtensionController', 'admin.extensions.index'),
(183, 'Configure an Extension', 'ExtensionController', 'admin.extensions.update'),
(184, 'Enable/Disable an Extension', 'ExtensionController', 'admin.extensions.status'),
(185, 'View Global Email Template', 'NotificationSettingController', 'admin.setting.notification.global.email'),
(186, 'Update Global Email Template Form', 'NotificationSettingController', 'admin.setting.notification.global.email.update'),
(187, 'View Email Setting', 'NotificationSettingController', 'admin.setting.notification.email'),
(188, 'Submit Email Setting Form', 'NotificationSettingController', 'admin.setting.notification.email.update'),
(189, 'Send Test Email', 'NotificationSettingController', 'admin.setting.notification.email.test'),
(190, 'View Global SMS Template', 'NotificationSettingController', 'admin.setting.notification.global.sms'),
(191, 'Update Global SMS Template Form', 'NotificationSettingController', 'admin.setting.notification.global.sms.update'),
(192, 'View SMS Setting', 'NotificationSettingController', 'admin.setting.notification.sms'),
(193, 'Update SMS Setting', 'NotificationSettingController', 'admin.setting.notification.sms.update'),
(194, 'Send Test SMS', 'NotificationSettingController', 'admin.setting.notification.sms.test'),
(195, 'View Global Push Notification Template', 'NotificationSettingController', 'admin.setting.notification.global.push'),
(196, 'Update Global Push Notification Template', 'NotificationSettingController', 'admin.setting.notification.global.push.update'),
(197, 'View Push Notification Setting', 'NotificationSettingController', 'admin.setting.notification.push'),
(198, 'Download Push Notification Config File', 'NotificationSettingController', 'admin.setting.notification.push.download'),
(199, 'Upload Push Notification Config File', 'NotificationSettingController', 'admin.setting.notification.push.upload'),
(200, 'Update Push Notification Setting', 'NotificationSettingController', 'admin.setting.notification.push.update'),
(201, 'View All Notification Templates', 'NotificationSettingController', 'admin.setting.notification.templates'),
(202, 'View Notification Update Page', 'NotificationSettingController', 'admin.setting.notification.template.edit'),
(203, 'Update Single Notification Template', 'NotificationSettingController', 'admin.setting.notification.template.update'),
(204, 'View Setting System Page', 'SystemSettingsController', 'admin.setting.system'),
(205, 'View General Settings', 'SystemSettingsController', 'admin.setting.general'),
(206, 'Update General Settings', 'SystemSettingsController', 'admin.setting.update'),
(207, 'View System Configuration', 'SystemSettingsController', 'admin.setting.system.configuration'),
(208, 'Update System Configuration', 'SystemSettingsController', 'admin.setting.system.configuration.submit'),
(209, 'View Logo & Favicon', 'SystemSettingsController', 'admin.setting.logo.icon'),
(210, 'Update Logo & Favicon', 'SystemSettingsController', 'admin.setting.logo.icon.update'),
(211, 'View Referral Setting', 'SystemSettingsController', 'admin.referral.setting'),
(212, 'Submit Referral Setting Form', 'SystemSettingsController', 'admin.referral.setting.save'),
(213, 'View API Configuration Page', 'SystemSettingsController', 'admin.api.config.index'),
(214, 'Update Airtime API Credentials', 'SystemSettingsController', 'admin.api.config.reloadly.save'),
(215, 'View KYC Setting', 'SystemSettingsController', 'admin.kyc.setting'),
(216, 'Submit KYC Setting Form', 'SystemSettingsController', 'admin.kyc.setting.submit'),
(217, 'View Social Login Setting', 'SystemSettingsController', 'admin.setting.socialite.credentials'),
(218, 'Update Social Login Credentials', 'SystemSettingsController', 'admin.setting.socialite.credentials.update'),
(219, 'Enable/Disable Social Login Options', 'SystemSettingsController', 'admin.setting.socialite.credentials.status.update'),
(220, 'View In App Purchase Page', 'SystemSettingsController', 'admin.setting.app.purchase'),
(221, 'Download Google Pay JSON File', 'SystemSettingsController', 'admin.setting.app.purchase.file.download'),
(222, 'Update/Upload Google Pay JSON File', 'SystemSettingsController', 'admin.setting.app.purchase.submit'),
(223, 'View All Tickets List', 'SupportTicketController', 'admin.ticket.index'),
(224, 'View Pending Tickets List', 'SupportTicketController', 'admin.ticket.pending'),
(225, 'View Closed Tickets List', 'SupportTicketController', 'admin.ticket.closed'),
(226, 'View Answered Tickets List', 'SupportTicketController', 'admin.ticket.answered'),
(227, 'View Ticket Page', 'SupportTicketController', 'admin.ticket.view'),
(228, 'Reply a Ticket', 'SupportTicketController', 'admin.ticket.reply'),
(229, 'Close a Ticket', 'SupportTicketController', 'admin.ticket.close'),
(230, 'Delete a Ticket', 'SupportTicketController', 'admin.ticket.delete'),
(231, 'Download Ticket Attachments', 'SupportTicketController', 'admin.ticket.download'),
(232, 'View Roles List', 'RolesController', 'admin.roles.index'),
(233, 'View Add New Role Page', 'RolesController', 'admin.roles.add'),
(234, 'Edit Role - Page', 'RolesController', 'admin.roles.edit'),
(235, 'Submit Add/Update Role Form', 'RolesController', 'admin.roles.save'),
(236, 'View All Staff List', 'AdminStaffController', 'admin.staff.index'),
(237, 'Add New Staff', 'AdminStaffController', 'admin.staff.save'),
(238, 'Enable / Disable Staff', 'AdminStaffController', 'admin.staff.status'),
(239, 'Login as Another Staff', 'AdminStaffController', 'admin.staff.login'),
(240, 'View Subscribers List', 'SubscriberController', 'admin.subscriber.index'),
(241, 'View the Send Email to All Subscribers Page', 'SubscriberController', 'admin.subscriber.send.email'),
(242, 'Remove a Subscriber', 'SubscriberController', 'admin.subscriber.remove'),
(243, 'View Application Information', 'SystemController', 'admin.system.info'),
(244, 'View Server Information', 'SystemController', 'admin.system.server.info'),
(245, 'View Clear Cache Page', 'SystemController', 'admin.system.optimize'),
(246, 'Clear Cache', 'SystemController', 'admin.system.optimize.clear'),
(247, 'View Update Page', 'SystemController', 'admin.system.update'),
(248, 'Update the System', 'SystemController', 'admin.system.update.process'),
(249, 'View Update Log', 'SystemController', 'admin.system.update.log');

DELETE pr FROM permission_role pr LEFT JOIN permissions p ON pr.permission_id = p.id WHERE p.id IS NULL;
DROP TABLE `permissions_backup`;


ALTER TABLE operators ADD COLUMN modified_group_name VARCHAR(255);

-- Step 2: Update the modified group name by truncating at the first occurrence of 'RTR', 'PIN', country name, or country ISO name
UPDATE operators o
JOIN countries c ON o.country_id = c.id
SET o.modified_group_name = TRIM(
    CASE
        WHEN LOCATE('RTR', o.name) > 0 THEN LEFT(o.name, LOCATE('RTR', o.name) - 1)
        WHEN LOCATE('PIN', o.name) > 0 THEN LEFT(o.name, LOCATE('PIN', o.name) - 1)
        WHEN LOCATE(CONCAT(' ', c.name), o.name) > 0 THEN LEFT(o.name, LOCATE(CONCAT(' ', c.name), o.name) - 1)
        WHEN LOCATE(CONCAT(' ', c.iso_name), o.name) > 0 THEN LEFT(o.name, LOCATE(CONCAT(' ', c.iso_name), o.name) - 1)
        ELSE o.name
    END
)
WHERE o.operator_group_id = 0;

-- Step 3: Insert new operator groups based on the modified names and country ID
INSERT INTO operator_groups (name, country_id)
SELECT DISTINCT o.modified_group_name, o.country_id
FROM operators o
WHERE o.modified_group_name IS NOT NULL
  AND o.modified_group_name != ''
  AND o.operator_group_id = 0
  AND NOT EXISTS (
      SELECT 1
      FROM operator_groups og
      WHERE og.name = o.modified_group_name
        AND og.country_id = o.country_id
  );

-- Step 4: Update the operator_group_id for each operator based on the created groups
UPDATE operators o
JOIN operator_groups og
    ON o.modified_group_name = og.name
    AND o.country_id = og.country_id
SET o.operator_group_id = og.id
WHERE o.operator_group_id = 0;

-- Step 5: Clean up by removing the temporary column
ALTER TABLE operators DROP COLUMN modified_group_name;

UPDATE `notification_templates` SET `push_body` = '{{message}}' WHERE `notification_templates`.`act` = "DEFAULT";