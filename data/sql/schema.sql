
--
-- Table structure for Activity model
--
CREATE TABLE `activity` (
	`id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	`created_by_id` INTEGER UNSIGNED DEFAULT NULL COMMENT "Created by, many-to-one relation, foreign key refers to User",
	`description` TEXT DEFAULT NULL COMMENT "Description",
	`name` VARCHAR(200) NOT NULL COMMENT "Name",
	`time_created` DATETIME DEFAULT NULL COMMENT "Date Created",
	KEY `idx_created_by_id` (`created_by_id`),
	PRIMARY KEY (`id`)
) AUTO_INCREMENT=1 CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table structure for ActivityRate model
--
CREATE TABLE `activity_rate` (
	`id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	`activity_id` INTEGER UNSIGNED DEFAULT NULL COMMENT "Activity, many-to-one relation, foreign key refers to Activity",
	`hour_rate` DECIMAL(10,2) NOT NULL COMMENT "Hour Rate",
	`rate_id` INTEGER UNSIGNED DEFAULT NULL COMMENT "Rate, many-to-one relation, foreign key refers to Rate",
	KEY `idx_activity_id` (`activity_id`),
	KEY `idx_rate_id` (`rate_id`),
	PRIMARY KEY (`id`)
) AUTO_INCREMENT=1 CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table structure for Assignment model
--
CREATE TABLE `assignment` (
	`id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	`project_id` INTEGER UNSIGNED DEFAULT NULL COMMENT "Project, many-to-one relation, foreign key refers to Project",
	`role` INTEGER NOT NULL COMMENT "Role",
	`task_id` INTEGER UNSIGNED DEFAULT NULL COMMENT "Task, many-to-one relation, foreign key refers to Task",
	`user_id` INTEGER UNSIGNED NOT NULL COMMENT "User, many-to-one relation, foreign key refers to User",
	KEY `idx_project_id` (`project_id`),
	KEY `idx_task_id` (`task_id`),
	KEY `idx_user_id` (`user_id`),
	PRIMARY KEY (`id`)
) AUTO_INCREMENT=1 CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table structure for Attachment model
--
CREATE TABLE `attachment` (
	`id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	`comment_id` INTEGER UNSIGNED DEFAULT NULL COMMENT "Comment, many-to-one relation, foreign key refers to Comment",
	`file_id` INTEGER UNSIGNED DEFAULT NULL COMMENT "File, many-to-one relation, foreign key refers to File",
	KEY `idx_comment_id` (`comment_id`),
	KEY `idx_file_id` (`file_id`),
	PRIMARY KEY (`id`)
) AUTO_INCREMENT=1 CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table structure for Comment model
--
CREATE TABLE `comment` (
	`id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	`content` TEXT NOT NULL COMMENT "Content",
	`created_by_id` INTEGER UNSIGNED DEFAULT NULL COMMENT "Created by, many-to-one relation, foreign key refers to User",
	`task_id` INTEGER UNSIGNED DEFAULT NULL COMMENT "Task, many-to-one relation, foreign key refers to Task",
	`time_created` DATETIME DEFAULT NULL COMMENT "Date Created",
	KEY `idx_created_by_id` (`created_by_id`),
	KEY `idx_task_id` (`task_id`),
	PRIMARY KEY (`id`)
) AUTO_INCREMENT=1 CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE  `file` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(10) unsigned NOT NULL,
  `parent_id` int(10) unsigned NOT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `mime` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `extension` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `size` int(10) unsigned NOT NULL,
  `width` int(10) unsigned NOT NULL,
  `height` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `create_time` int(10) unsigned NOT NULL,
  `update_time` int(10) unsigned NOT NULL,
  `path` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  KEY `parent_id` (`parent_id`),
  KEY `user_id` (`user_id`)
) AUTO_INCREMENT=1 CHARSET=utf8 COLLATE=utf8_unicode_ci;
CREATE TABLE `filecategory` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hidden` tinyint(4) DEFAULT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) AUTO_INCREMENT=1 CHARSET=utf8 COLLATE=utf8_unicode_ci;
--
-- Table structure for Invoice model
--
CREATE TABLE `invoice` (
	`id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	`comments` TEXT DEFAULT NULL COMMENT "Comments",
	`created_by_id` INTEGER UNSIGNED DEFAULT NULL COMMENT "Created by, many-to-one relation, foreign key refers to User",
	`payd` TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT "Payd",
	`project_id` INTEGER UNSIGNED DEFAULT NULL COMMENT "Project, many-to-one relation, foreign key refers to Project",
	`time_created` DATETIME DEFAULT NULL COMMENT "Date Created",
	KEY `idx_created_by_id` (`created_by_id`),
	KEY `idx_project_id` (`project_id`),
	PRIMARY KEY (`id`)
) AUTO_INCREMENT=1 CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table structure for InvoiceItem model
--
CREATE TABLE `invoice_item` (
	`id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	`hours` DECIMAL(10,2) DEFAULT NULL COMMENT "Hours",
	`invoice_id` INTEGER UNSIGNED DEFAULT NULL COMMENT "Invoice, many-to-one relation, foreign key refers to Invoice",
	`name` VARCHAR(200) NOT NULL COMMENT "Name",
	`task_id` INTEGER UNSIGNED DEFAULT NULL COMMENT "Task, many-to-one relation, foreign key refers to Task",
	`value` DECIMAL(10,2) NOT NULL COMMENT "Value",
	KEY `idx_invoice_id` (`invoice_id`),
	KEY `idx_task_id` (`task_id`),
	PRIMARY KEY (`id`)
) AUTO_INCREMENT=1 CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `meta` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci NOT NULL,
  `parent_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) AUTO_INCREMENT=1 CHARSET=utf8 COLLATE=utf8_unicode_ci;
--
-- Table structure for Milestone model
--
CREATE TABLE `milestone` (
	`id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	`created_by_id` INTEGER UNSIGNED DEFAULT NULL COMMENT "Created by, many-to-one relation, foreign key refers to User",
	`description` TEXT DEFAULT NULL COMMENT "Description",
	`due_date` DATE DEFAULT NULL COMMENT "Due Date",
	`name` VARCHAR(200) NOT NULL COMMENT "Name",
	`project_id` INTEGER UNSIGNED DEFAULT NULL COMMENT "Project, many-to-one relation, foreign key refers to Project",
	`time_created` DATETIME DEFAULT NULL COMMENT "Date Created",
	KEY `idx_created_by_id` (`created_by_id`),
	KEY `idx_name` (`name`),
	KEY `idx_project_id` (`project_id`),
	PRIMARY KEY (`id`)
) AUTO_INCREMENT=1 CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `options` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `optname` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) AUTO_INCREMENT=1 CHARSET=utf8 COLLATE=utf8_unicode_ci;
--
-- Table structure for Payment model
--
CREATE TABLE `payment` (
	`id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	`amount` DECIMAL(10,2) NOT NULL COMMENT "Amount",
	`created_by_id` INTEGER UNSIGNED DEFAULT NULL COMMENT "Created by, many-to-one relation, foreign key refers to User",
	`date_created` DATETIME DEFAULT NULL COMMENT "Date Created",
	`description` TEXT DEFAULT NULL COMMENT "Description",
	`project_id` INTEGER UNSIGNED DEFAULT NULL COMMENT "Project, many-to-one relation, foreign key refers to Project",
	`task_id` INTEGER UNSIGNED DEFAULT NULL COMMENT "Task, many-to-one relation, foreign key refers to Task",
	`type` INTEGER NOT NULL COMMENT "Type",
	`user_id` INTEGER UNSIGNED DEFAULT NULL COMMENT "User, many-to-one relation, foreign key refers to User",
	KEY `idx_created_by_id` (`created_by_id`),
	KEY `idx_project_id` (`project_id`),
	KEY `idx_task_id` (`task_id`),
	KEY `idx_type` (`type`),
	KEY `idx_user_id` (`user_id`),
	PRIMARY KEY (`id`)
) AUTO_INCREMENT=1 CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table structure for Project model
--
CREATE TABLE `project` (
	`id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	`created_by_id` INTEGER UNSIGNED DEFAULT NULL COMMENT "Created by, many-to-one relation, foreign key refers to User",
	`date_created` DATETIME DEFAULT NULL COMMENT "Date Created",
	`name` VARCHAR(200) NOT NULL COMMENT "Name",
	`scope` TEXT DEFAULT NULL COMMENT "Scope",
	KEY `idx_created_by_id` (`created_by_id`),
	KEY `idx_name` (`name`),
	PRIMARY KEY (`id`)
) AUTO_INCREMENT=1 CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table structure for Rate model
--
CREATE TABLE `rate` (
	`id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	`created_by_id` INTEGER UNSIGNED DEFAULT NULL COMMENT "Created by, many-to-one relation, foreign key refers to User",
	`description` TEXT DEFAULT NULL COMMENT "Description",
	`name` VARCHAR(200) NOT NULL COMMENT "Name",
	`power` DECIMAL(10,2) NOT NULL DEFAULT "1" COMMENT "Power",
	`time_created` DATETIME DEFAULT NULL COMMENT "Date Created",
	KEY `idx_created_by_id` (`created_by_id`),
	KEY `idx_name` (`name`),
	PRIMARY KEY (`id`)
) AUTO_INCREMENT=1 CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table structure for Subscription model
--
CREATE TABLE `subscription` (
	`id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	`last_view_time` DATETIME DEFAULT NULL COMMENT "Last view time",
	`task_id` INTEGER UNSIGNED DEFAULT NULL COMMENT "Task, many-to-one relation, foreign key refers to Task",
	`user_id` INTEGER UNSIGNED DEFAULT NULL COMMENT "User, many-to-one relation, foreign key refers to User",
	KEY `idx_last_view_time` (`last_view_time`),
	KEY `idx_task_id` (`task_id`),
	KEY `idx_user_id` (`user_id`),
	PRIMARY KEY (`id`)
) AUTO_INCREMENT=1 CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table structure for Tag model
--
CREATE TABLE `tag` (
	`id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	`color` VARCHAR(20) DEFAULT NULL COMMENT "Color",
	`created_by_id` INTEGER UNSIGNED DEFAULT NULL COMMENT "Created by, many-to-one relation, foreign key refers to User",
	`name` VARCHAR(200) NOT NULL COMMENT "Name",
	`time_created` DATETIME DEFAULT NULL COMMENT "Date Created",
	KEY `idx_created_by_id` (`created_by_id`),
	KEY `idx_name` (`name`),
	PRIMARY KEY (`id`)
) AUTO_INCREMENT=1 CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table structure for Task model
--
CREATE TABLE `task` (
	`id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	`assigned_id` INTEGER UNSIGNED DEFAULT NULL COMMENT "Assigned, many-to-one relation, foreign key refers to User",
	`complexity` DECIMAL(10,2) DEFAULT NULL COMMENT "Complexity",
	`created_by_id` INTEGER UNSIGNED DEFAULT NULL COMMENT "Created by, many-to-one relation, foreign key refers to User",
	`date_sheduled` DATE DEFAULT NULL COMMENT "Date Sheduled",
	`description` TEXT DEFAULT NULL COMMENT "Description",
	`due_date` DATE DEFAULT NULL COMMENT "Due Date",
	`estimate` DECIMAL(10,2) DEFAULT NULL COMMENT "Estimate",
	`milestone_id` INTEGER UNSIGNED DEFAULT NULL COMMENT "Milestone, many-to-one relation, foreign key refers to Milestone",
	`name` VARCHAR(200) NOT NULL COMMENT "Name",
	`phase` INTEGER DEFAULT NULL COMMENT "Phase",
	`priority` INTEGER DEFAULT NULL COMMENT "Priority",
	`project_id` INTEGER UNSIGNED DEFAULT NULL COMMENT "Project, many-to-one relation, foreign key refers to Project",
	`regression_risk` INTEGER DEFAULT NULL COMMENT "Risk of Regression",
	`time_created` DATETIME DEFAULT NULL COMMENT "Date Created",
	KEY `idx_assigned_id` (`assigned_id`),
	KEY `idx_created_by_id` (`created_by_id`),
	KEY `idx_milestone_id` (`milestone_id`),
	KEY `idx_name` (`name`),
	KEY `idx_phase` (`phase`),
	KEY `idx_priority` (`priority`),
	KEY `idx_project_id` (`project_id`),
	KEY `idx_regression_risk` (`regression_risk`),
	PRIMARY KEY (`id`)
) AUTO_INCREMENT=1 CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table structure for TimeEntry model
--
CREATE TABLE `time_entry` (
	`id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	`activity_id` INTEGER UNSIGNED NOT NULL COMMENT "Activity, many-to-one relation, foreign key refers to Activity",
	`amount` DECIMAL(10,2) NOT NULL COMMENT "Amount",
	`created_by_id` INTEGER UNSIGNED DEFAULT NULL COMMENT "Created by, many-to-one relation, foreign key refers to User",
	`date_created` DATETIME DEFAULT NULL COMMENT "Date Created",
	`description` TEXT DEFAULT NULL COMMENT "Description",
	`project_id` INTEGER UNSIGNED DEFAULT NULL COMMENT "Project, many-to-one relation, foreign key refers to Project",
	`task_id` INTEGER UNSIGNED DEFAULT NULL COMMENT "Task, many-to-one relation, foreign key refers to Task",
	`user_id` INTEGER UNSIGNED NOT NULL COMMENT "User, many-to-one relation, foreign key refers to User",
	KEY `idx_activity_id` (`activity_id`),
	KEY `idx_created_by_id` (`created_by_id`),
	KEY `idx_project_id` (`project_id`),
	KEY `idx_task_id` (`task_id`),
	KEY `idx_user_id` (`user_id`),
	PRIMARY KEY (`id`)
) AUTO_INCREMENT=1 CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `username` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `salt` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `role` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `date_created` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `email` (`email`(4)),
  KEY `username` (`username`(4))
) AUTO_INCREMENT=1 CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `user` VALUES 
	(1,'admin@example.com','admin','235c3072d3dd58d88ed495cb746b7fe4','lqrDqJ7TCVenHcr','admin',1,NULL);

--
-- Task and Tag linker table
--
CREATE TABLE `task_tag_tags` (
	`task_id` INTEGER UNSIGNED NOT NULL,
	`tag_id` INTEGER UNSIGNED NOT NULL,
	PRIMARY KEY (`task_id`, `tag_id`)
);
ALTER TABLE `task_tag_tags` ADD CONSTRAINT `fk_task_id` FOREIGN KEY (`task_id`) REFERENCES `task` (`id`);
ALTER TABLE `task_tag_tags` ADD CONSTRAINT `fk_tag_id` FOREIGN KEY (`tag_id`) REFERENCES `tag` (`id`);


--
-- Foreign keys definition
--
ALTER TABLE `activity` ADD CONSTRAINT `fk_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `user` (`id`);
ALTER TABLE `activity_rate` ADD CONSTRAINT `fk_activity_id` FOREIGN KEY (`activity_id`) REFERENCES `activity` (`id`);
ALTER TABLE `activity_rate` ADD CONSTRAINT `fk_rate_id` FOREIGN KEY (`rate_id`) REFERENCES `rate` (`id`);
ALTER TABLE `assignment` ADD CONSTRAINT `fk_project_id` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`);
ALTER TABLE `assignment` ADD CONSTRAINT `fk_task_id` FOREIGN KEY (`task_id`) REFERENCES `task` (`id`);
ALTER TABLE `assignment` ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
ALTER TABLE `attachment` ADD CONSTRAINT `fk_comment_id` FOREIGN KEY (`comment_id`) REFERENCES `comment` (`id`);
ALTER TABLE `attachment` ADD CONSTRAINT `fk_file_id` FOREIGN KEY (`file_id`) REFERENCES `file` (`id`);
ALTER TABLE `comment` ADD CONSTRAINT `fk_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `user` (`id`);
ALTER TABLE `comment` ADD CONSTRAINT `fk_task_id` FOREIGN KEY (`task_id`) REFERENCES `task` (`id`);
ALTER TABLE `invoice` ADD CONSTRAINT `fk_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `user` (`id`);
ALTER TABLE `invoice` ADD CONSTRAINT `fk_project_id` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`);
ALTER TABLE `invoice_item` ADD CONSTRAINT `fk_invoice_id` FOREIGN KEY (`invoice_id`) REFERENCES `invoice` (`id`);
ALTER TABLE `invoice_item` ADD CONSTRAINT `fk_task_id` FOREIGN KEY (`task_id`) REFERENCES `task` (`id`);
ALTER TABLE `milestone` ADD CONSTRAINT `fk_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `user` (`id`);
ALTER TABLE `milestone` ADD CONSTRAINT `fk_project_id` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`);
ALTER TABLE `payment` ADD CONSTRAINT `fk_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `user` (`id`);
ALTER TABLE `payment` ADD CONSTRAINT `fk_project_id` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`);
ALTER TABLE `payment` ADD CONSTRAINT `fk_task_id` FOREIGN KEY (`task_id`) REFERENCES `task` (`id`);
ALTER TABLE `payment` ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
ALTER TABLE `project` ADD CONSTRAINT `fk_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `user` (`id`);
ALTER TABLE `rate` ADD CONSTRAINT `fk_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `user` (`id`);
ALTER TABLE `subscription` ADD CONSTRAINT `fk_task_id` FOREIGN KEY (`task_id`) REFERENCES `task` (`id`);
ALTER TABLE `subscription` ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
ALTER TABLE `tag` ADD CONSTRAINT `fk_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `user` (`id`);
ALTER TABLE `task` ADD CONSTRAINT `fk_assigned_id` FOREIGN KEY (`assigned_id`) REFERENCES `user` (`id`);
ALTER TABLE `task` ADD CONSTRAINT `fk_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `user` (`id`);
ALTER TABLE `task` ADD CONSTRAINT `fk_milestone_id` FOREIGN KEY (`milestone_id`) REFERENCES `milestone` (`id`);
ALTER TABLE `task` ADD CONSTRAINT `fk_project_id` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`);
ALTER TABLE `time_entry` ADD CONSTRAINT `fk_activity_id` FOREIGN KEY (`activity_id`) REFERENCES `activity` (`id`);
ALTER TABLE `time_entry` ADD CONSTRAINT `fk_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `user` (`id`);
ALTER TABLE `time_entry` ADD CONSTRAINT `fk_project_id` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`);
ALTER TABLE `time_entry` ADD CONSTRAINT `fk_task_id` FOREIGN KEY (`task_id`) REFERENCES `task` (`id`);
ALTER TABLE `time_entry` ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
