ALTER TABLE `employee_indemnity` ADD `today_date` DATE NULL DEFAULT NULL AFTER `joined_on`;

ALTER TABLE `employee_indemnity` ADD `months_earned` VARCHAR(100) NOT NULL AFTER `years_diff`, ADD `max_months_eligible` VARCHAR(100) NOT NULL AFTER `months_earned`, ADD `months_taken` VARCHAR(100) NOT NULL AFTER `max_months_eligible`, ADD `indemnity_amount` VARCHAR(100) NOT NULL AFTER `months_taken`;
ALTER TABLE `employee_indemnity` ADD `current_salary` VARCHAR(100) NOT NULL AFTER `total_months`, ADD `month_days` VARCHAR(100) NOT NULL AFTER `current_salary`;
ALTER TABLE `employee_indemnity` CHANGE `years_diff` `years_diff` VARCHAR(11) NOT NULL;

ALTER TABLE `employees` CHANGE `status` `status` ENUM('active','inactive','deleted') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `employees` ADD `opening_leave_days` VARCHAR(100) NOT NULL DEFAULT '0' AFTER `designation`, ADD `opening_leave_amount` VARCHAR(100) NOT NULL DEFAULT '0' AFTER `opening_leave_days`;

ALTER TABLE `employee_leaves` CHANGE `leave_status` `leave_status` ENUM('new','pending','approved','rejected','cancelled','paid','hold') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `employee_leaves` ADD `amount` VARCHAR(100) NOT NULL DEFAULT '0' AFTER `leave_status`;


26-07-2023
ALTER TABLE `overtime` ADD `off_day` INT NOT NULL DEFAULT '5' AFTER `working_hours`;
ALTER TABLE `employee_monthly_salary` ADD `off_day` INT NOT NULL DEFAULT '0' AFTER `hourly_salary`, ADD `off_days_no` INT NOT NULL DEFAULT '0' AFTER `off_day`, ADD `ph_days_no` INT NOT NULL DEFAULT '0' AFTER `off_days_no`, ADD `ph_dates` TEXT NULL AFTER `ph_days_no`, ADD `day_hours` VARCHAR(100) NOT NULL DEFAULT '0' AFTER `ph_dates`, ADD `dates_between` VARCHAR(255) NOT NULL AFTER `day_hours`, ADD `excluded_dates` TEXT NULL AFTER `dates_between`;

ALTER TABLE `scheduling` CHANGE `start_time` `start_time` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL, CHANGE `end_time` `end_time` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;