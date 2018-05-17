<?php

class m180427_144529_installation extends CDbMigration
{
	public function up()
	{
		$this->createTable('activity_rate', array(
			'id' => 'Int( 10 ) UNSIGNED AUTO_INCREMENT NOT NULL',
			'activity_id' => 'Int( 10 ) UNSIGNED NULL',
			'hour_rate' => 'Decimal( 10, 2 ) NOT NULL',
			'rate_id' => 'Int( 10 ) UNSIGNED NULL',
			'PRIMARY KEY (`id`)',
			'KEY `idx_activity_id` (`activity_id`)',
			'KEY `idx_rate_id` (`rate_id`)',
		), 'ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_unicode_ci');
		
		$this->createTable('assignment', array(
			'id' => 'Int( 10 ) UNSIGNED AUTO_INCREMENT NOT NULL',
			'project_id' => 'Int( 10 ) UNSIGNED NULL',
			'role' => 'Int( 11 ) NOT NULL',
			'task_id' => 'Int( 10 ) UNSIGNED NULL',
			'user_id' => 'Int( 10 ) UNSIGNED NOT NULL',
			'PRIMARY KEY (`id`)',
			'KEY `idx_project_id` (`project_id`)',
			'KEY `idx_user_id` (`user_id`)',
		), 'ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_unicode_ci');
		
		$this->createTable('comment', array(
			'id' => 'Int( 10 ) UNSIGNED AUTO_INCREMENT NOT NULL',
			'action' => 'VarChar( 45 ) NULL',
			'content' => 'Text NOT NULL',
			'created_by_id' => 'Int( 10 ) UNSIGNED NULL',
			'task_id' => 'Int( 10 ) UNSIGNED NULL',
			'time_created' => 'DateTime NULL',
			'PRIMARY KEY (`id`)',
			'KEY `idx_task_id` (`task_id`)',
		), 'ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_unicode_ci');
		
		$this->createTable('app_entity', array(
			'id' => 'Int( 10 ) UNSIGNED AUTO_INCREMENT NOT NULL',
			'name' => 'VarChar( 100 ) NOT NULL',
			'module' => 'VarChar( 100 ) NULL',
			'label' => 'VarChar( 100 ) NOT NULL',
			'description' => 'Text NULL',
			'application_id' => 'Int( 10 ) UNSIGNED NOT NULL',
			'json_schemes' => 'Text NULL',
			'plain_source' => 'LongText NULL',
			'json_source' => 'LongText NULL',
			'expert_mode' => 'TinyInt( 3 ) UNSIGNED NULL DEFAULT \'0\'',
			'time_created' => 'DateTime NOT NULL',
			'created_by_id' => 'Int( 10 ) UNSIGNED NOT NULL',
			'PRIMARY KEY (`id`)',
			'KEY `idx_application_id` (`application_id`)',
		), 'ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_unicode_ci');
		
		$this->createTable('payment', array(
			'id' => 'Int( 10 ) UNSIGNED AUTO_INCREMENT NOT NULL',
			'amount' => 'Decimal( 10, 2 ) NOT NULL',
			'created_by_id' => 'Int( 10 ) UNSIGNED NULL',
			'date_created' => 'DateTime NULL',
			'description' => 'Text NULL',
			'invoice_id' => 'Int( 10 ) UNSIGNED NOT NULL',
			'project_id' => 'Int( 10 ) UNSIGNED NULL',
			'task_id' => 'Int( 10 ) UNSIGNED NULL',
			'type' => 'Int( 11 ) NOT NULL',
			'user_id' => 'Int( 10 ) UNSIGNED NULL',
			'PRIMARY KEY (`id`)',
			'KEY `idx_project_id` (`project_id`)',
			'KEY `idx_invoice_id` (`invoice_id`)',
			'KEY `idx_type` (`type`)',
			'KEY `idx_user_id` (`user_id`)',
		), 'ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_unicode_ci');
		
		$this->createTable('holiday', array(
			'id' => 'Int( 10 ) UNSIGNED AUTO_INCREMENT NOT NULL',
			'name' => 'VarChar( 200 ) NOT NULL',
			'day' => 'TinyInt( 3 ) UNSIGNED NOT NULL',
			'day2' => 'TinyInt( 3 ) UNSIGNED NULL',
			'month' => 'TinyInt( 3 ) UNSIGNED NOT NULL',
			'month2' => 'TinyInt( 3 ) UNSIGNED NULL',
			'year' => 'Int( 10 ) UNSIGNED NULL',
			'year2' => 'Int( 10 ) UNSIGNED NULL',
			'created_by_id' => 'Int( 10 ) UNSIGNED NULL',
			'time_created' => 'DateTime NULL',
			'PRIMARY KEY (`id`)',
			'KEY `idx_day` (`day`)',
			'KEY `idx_month` (`month`)',
		), 'ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_unicode_ci');
		
		$this->createTable('invoice_item', array(
			'id' => 'Int( 10 ) UNSIGNED AUTO_INCREMENT NOT NULL',
			'bonus' => 'Decimal( 10, 2 ) NOT NULL DEFAULT \'0.00\'',
			'hours' => 'Decimal( 10, 2 ) NULL',
			'invoice_id' => 'Int( 10 ) UNSIGNED NULL',
			'name' => 'VarChar( 200 ) NOT NULL',
			'task_id' => 'Int( 10 ) UNSIGNED NULL',
			'value' => 'Decimal( 10, 2 ) NOT NULL',
			'PRIMARY KEY (`id`)',
			'KEY `idx_invoice_id` (`invoice_id`)',
			'KEY `idx_task_id` (`task_id`)',
		), 'ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_unicode_ci');
		
		$this->createTable('milestone_attachment', array(
			'id' => 'Int( 10 ) UNSIGNED AUTO_INCREMENT NOT NULL',
			'milestone_id' => 'Int( 10 ) UNSIGNED NULL',
			'file_id' => 'Int( 10 ) UNSIGNED NULL',
			'PRIMARY KEY (`id`)',
			'KEY `idx_file_id` (`file_id`)',
			'KEY `idx_milestone_id` (`milestone_id`)',
		), 'ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_unicode_ci');
		
		$this->createTable('activity', array(
			'id' => 'Int( 10 ) UNSIGNED AUTO_INCREMENT NOT NULL',
			'created_by_id' => 'Int( 10 ) UNSIGNED NULL',
			'description' => 'Text NULL',
			'name' => 'VarChar( 200 ) NOT NULL',
			'time_created' => 'DateTime NULL',
			'PRIMARY KEY (`id`)',
		), 'ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_unicode_ci');
		
		$this->createTable('comment_attachment', array(
			'id' => 'Int( 10 ) UNSIGNED AUTO_INCREMENT NOT NULL',
			'comment_id' => 'Int( 10 ) UNSIGNED NULL',
			'file_id' => 'Int( 10 ) UNSIGNED NULL',
			'PRIMARY KEY (`id`)',
			'KEY `idx_comment_id` (`comment_id`)',
			'KEY `idx_file_id` (`file_id`)',
		), 'ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_unicode_ci');
		
		$this->createTable('user_holiday', array(
			'holiday_id' => 'Int( 10 ) UNSIGNED NOT NULL',
			'user_id' => 'Int( 10 ) UNSIGNED NOT NULL',
			'PRIMARY KEY (`holiday_id`, `user_id`)',
		), 'ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_unicode_ci');
		
		$this->createTable('time_entry', array(
			'id' => 'Int( 10 ) UNSIGNED AUTO_INCREMENT NOT NULL',
			'activity_id' => 'Int( 10 ) UNSIGNED NOT NULL',
			'amount' => 'Decimal( 10, 2 ) NOT NULL',
			'created_by_id' => 'Int( 10 ) UNSIGNED NULL',
			'date_created' => 'DateTime NULL',
			'description' => 'Text NULL',
			'project_id' => 'Int( 10 ) UNSIGNED NULL',
			'task_id' => 'Int( 10 ) UNSIGNED NULL',
			'user_id' => 'Int( 10 ) UNSIGNED NOT NULL',
			'PRIMARY KEY (`id`)',
			'KEY `idx_activity_id` (`activity_id`)',
			'KEY `idx_project_id` (`project_id`)',
			'KEY `idx_task_id` (`task_id`)',
			'KEY `idx_user_id` (`user_id`)',
		), 'ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_unicode_ci');
		
		$this->createTable('invoice', array(
			'id' => 'Int( 10 ) UNSIGNED AUTO_INCREMENT NOT NULL',
			'comments' => 'Text NULL',
			'created_by_id' => 'Int( 10 ) UNSIGNED NULL',
			'draft' => 'TinyInt( 3 ) UNSIGNED NOT NULL DEFAULT \'1\'',
			'from_id' => 'Int( 10 ) UNSIGNED NULL',
			'project_id' => 'Int( 10 ) UNSIGNED NULL',
			'time_created' => 'DateTime NULL',
			'to_id' => 'Int( 10 ) UNSIGNED NULL',
			'PRIMARY KEY (`id`)',
			'KEY `idx_from_id` (`from_id`)',
			'KEY `idx_to_id` (`to_id`)',
			'KEY `idx_project_id` (`project_id`)',
		), 'ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_unicode_ci');
		
		$this->createTable('application', array(
			'id' => 'Int( 10 ) UNSIGNED AUTO_INCREMENT NOT NULL',
			'created_by_id' => 'Int( 10 ) UNSIGNED NOT NULL',
			'time_created' => 'DateTime NOT NULL',
			'db_name' => 'VarChar( 100 ) NULL',
			'db_password' => 'VarChar( 100 ) NULL',
			'db_user' => 'VarChar( 100 ) NULL',
			'description' => 'Text NULL',
			'log_directory' => 'VarChar( 100 ) NULL',
			'document_root' => 'VarChar( 100 ) NULL',
			'vhost_options' => 'Text NULL',
			'git' => 'VarChar( 250 ) NULL',
			'git_branch' => 'VarChar( 100 ) NULL',
			'name' => 'VarChar( 30 ) NULL',
			'project_id' => 'Int( 10 ) UNSIGNED NOT NULL',
			'status' => 'Int( 10 ) UNSIGNED NULL DEFAULT \'0\'',
			'last_build_json' => 'Text NULL',
			'PRIMARY KEY (`id`)',
			'KEY `idx_project_id` (`project_id`)',
		), 'ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_unicode_ci');
		
		$this->createTable('project_attachment', array(
			'id' => 'Int( 10 ) UNSIGNED AUTO_INCREMENT NOT NULL',
			'project_id' => 'Int( 10 ) UNSIGNED NULL',
			'file_id' => 'Int( 10 ) UNSIGNED NULL',
			'PRIMARY KEY (`id`)',
			'KEY `idx_file_id` (`file_id`)',
			'KEY `idx_project_id` (`project_id`)',
		), 'ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_unicode_ci');
		
		$this->createTable('note', array(
			'id' => 'Int( 10 ) UNSIGNED AUTO_INCREMENT NOT NULL',
			'created_by_id' => 'Int( 10 ) UNSIGNED NULL',
			'private' => 'TinyInt( 3 ) UNSIGNED NULL',
			'project_id' => 'Int( 10 ) UNSIGNED NULL',
			'text' => 'Text NULL',
			'time_created' => 'DateTime NULL',
			'PRIMARY KEY (`id`)',
			'KEY `idx_created_by_id` (`created_by_id`)',
			'KEY `idx_private` (`private`)',
			'KEY `idx_project_id` (`project_id`)',
		), 'ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_unicode_ci');
		
		$this->createTable('file', array(
			'id' => 'Int( 10 ) UNSIGNED AUTO_INCREMENT NOT NULL',
			'category_id' => 'Int( 10 ) UNSIGNED NOT NULL',
			'parent_id' => 'Int( 10 ) UNSIGNED NOT NULL',
			'title' => 'VarChar( 100 ) NOT NULL',
			'mime' => 'VarChar( 50 ) NOT NULL',
			'extension' => 'VarChar( 10 ) NOT NULL',
			'size' => 'Int( 10 ) UNSIGNED NOT NULL',
			'width' => 'Int( 10 ) UNSIGNED NOT NULL',
			'height' => 'Int( 10 ) UNSIGNED NOT NULL',
			'user_id' => 'Int( 10 ) UNSIGNED NOT NULL',
			'create_time' => 'Int( 10 ) UNSIGNED NOT NULL',
			'update_time' => 'Int( 10 ) UNSIGNED NOT NULL',
			'path' => 'VarChar( 100 ) NOT NULL',
			'PRIMARY KEY (`id`)',
			'KEY `idx_category_id` (`category_id`)',
			'KEY `idx_parent_id` (`parent_id`)',
			'KEY `idx_user_id` (`user_id`)',
		), 'ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_unicode_ci');
		
		$this->createTable('options', array(
			'id' => 'Int( 10 ) UNSIGNED AUTO_INCREMENT NOT NULL',
			'optname' => 'VarChar( 32 ) NOT NULL',
			'PRIMARY KEY (`id`)',
		), 'ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_unicode_ci');
		
		$this->createTable('project', array(
			'id' => 'Int( 10 ) UNSIGNED AUTO_INCREMENT NOT NULL',
			'archived' => 'TinyInt( 3 ) UNSIGNED NULL DEFAULT \'0\'',
			'created_by_id' => 'Int( 10 ) UNSIGNED NULL',
			'bonus' => 'Decimal( 10, 2 ) NULL DEFAULT \'0.00\'',
			'bonus_type' => 'TinyInt( 255 ) UNSIGNED NULL DEFAULT \'0\'',
			'date_created' => 'DateTime NULL',
			'name' => 'VarChar( 200 ) NOT NULL',
			'scope' => 'Text NULL',
			'PRIMARY KEY (`id`)',
			'KEY `idx_archived` (`archived`)',
		), 'ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_unicode_ci');
		
		$this->createTable('rate', array(
			'id' => 'Int( 10 ) UNSIGNED AUTO_INCREMENT NOT NULL',
			'created_by_id' => 'Int( 10 ) UNSIGNED NULL',
			'description' => 'Text NULL',
			'name' => 'VarChar( 200 ) NOT NULL',
			'power' => 'Decimal( 10, 2 ) NOT NULL DEFAULT \'1.00\'',
			'time_created' => 'DateTime NULL',
			'PRIMARY KEY (`id`)',
		), 'ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_unicode_ci');
		
		$this->createTable('meta', array(
			'id' => 'Int( 10 ) UNSIGNED AUTO_INCREMENT NOT NULL',
			'key' => 'VarChar( 32 ) NOT NULL',
			'value' => 'Text NOT NULL',
			'parent_id' => 'Int( 10 ) UNSIGNED NOT NULL',
			'PRIMARY KEY (`id`)',
		), 'ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_unicode_ci');
		
		$this->createTable('filecategory', array(
			'id' => 'Int( 10 ) UNSIGNED AUTO_INCREMENT NOT NULL',
			'hidden' => 'TinyInt( 4 ) NULL',
			'title' => 'VarChar( 100 ) NULL',
			'PRIMARY KEY (`id`)',
		), 'ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_unicode_ci');
		
		$this->createTable('task', array(
			'id' => 'Int( 10 ) UNSIGNED AUTO_INCREMENT NOT NULL',
			'assigned_id' => 'Int( 10 ) UNSIGNED NULL',
			'complexity' => 'Decimal( 10, 2 ) NULL',
			'created_by_id' => 'Int( 10 ) UNSIGNED NULL',
			'date_sheduled' => 'Date NULL',
			'description' => 'Text NULL',
			'due_date' => 'Date NULL',
			'estimate' => 'Decimal( 10, 2 ) NULL',
			'milestone_id' => 'Int( 10 ) UNSIGNED NULL',
			'name' => 'VarChar( 200 ) NOT NULL',
			'phase' => 'Int( 11 ) NULL',
			'priority' => 'Int( 11 ) NULL',
			'project_id' => 'Int( 10 ) UNSIGNED NULL',
			'regression_risk' => 'Int( 11 ) NULL',
			'time_created' => 'DateTime NULL',
			'time_updated' => 'DateTime NULL',
			'PRIMARY KEY (`id`)',
			'KEY `idx_assigned_id` (`assigned_id`)',
			'KEY `idx_created_by_id` (`created_by_id`)',
			'KEY `idx_milestone_id` (`milestone_id`)',
			'KEY `idx_phase` (`phase`)',
			'KEY `idx_priority` (`priority`)',
			'KEY `idx_project_id` (`project_id`)',
			'KEY `idx_time_updated` (`time_updated`)',
		), 'ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_unicode_ci');
		
		$this->createTable('milestone', array(
			'id' => 'Int( 10 ) UNSIGNED AUTO_INCREMENT NOT NULL',
			'created_by_id' => 'Int( 10 ) UNSIGNED NULL',
			'description' => 'Text NULL',
			'date_start' => 'Date NULL',
			'due_date' => 'Date NULL',
			'name' => 'VarChar( 200 ) NOT NULL',
			'project_id' => 'Int( 10 ) UNSIGNED NULL',
			'time_created' => 'DateTime NULL',
			'PRIMARY KEY (`id`)',
			'KEY `idx_project_id` (`project_id`)',
		), 'ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_unicode_ci');
		
		$this->createTable('working_hours', array(
			'id' => 'Int( 10 ) UNSIGNED AUTO_INCREMENT NOT NULL',
			'created_by_id' => 'Int( 10 ) UNSIGNED NOT NULL',
			'name' => 'VarChar( 200 ) NOT NULL',
			'mon' => 'TinyInt( 3 ) UNSIGNED NOT NULL DEFAULT \'0\'',
			'tue' => 'TinyInt( 3 ) UNSIGNED NOT NULL DEFAULT \'0\'',
			'wed' => 'TinyInt( 3 ) UNSIGNED NOT NULL DEFAULT \'0\'',
			'thu' => 'TinyInt( 3 ) UNSIGNED NOT NULL DEFAULT \'0\'',
			'fri' => 'TinyInt( 3 ) UNSIGNED NOT NULL DEFAULT \'0\'',
			'sat' => 'TinyInt( 3 ) UNSIGNED NOT NULL DEFAULT \'0\'',
			'sun' => 'TinyInt( 3 ) UNSIGNED NOT NULL DEFAULT \'0\'',
			'general' => 'TinyInt( 3 ) UNSIGNED NOT NULL DEFAULT \'0\'',
			'time_created' => 'DateTime NOT NULL',
			'PRIMARY KEY (`id`)',
		), 'ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_unicode_ci');
		
		$this->createTable('task_schedule', array(
			'task_id' => 'Int( 11 ) NOT NULL',
			'user_id' => 'Int( 11 ) NOT NULL',
			'date' => 'Date NOT NULL',
			'hours' => 'Decimal( 10, 2 ) NOT NULL',
			'PRIMARY KEY (`task_id`, `user_id`, `date`)',
		), 'ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_unicode_ci');
		
		$this->createTable('task_attachment', array(
			'id' => 'Int( 10 ) UNSIGNED AUTO_INCREMENT NOT NULL',
			'task_id' => 'Int( 10 ) UNSIGNED NULL',
			'file_id' => 'Int( 10 ) UNSIGNED NULL',
			'PRIMARY KEY (`id`)',
			'KEY `idx_file_id` (`file_id`)',
			'KEY `idx_task_id` (`task_id`)',
		), 'ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_unicode_ci');
		
		$this->createTable('tag', array(
			'id' => 'Int( 10 ) UNSIGNED AUTO_INCREMENT NOT NULL',
			'color' => 'VarChar( 20 ) NULL',
			'created_by_id' => 'Int( 10 ) UNSIGNED NULL',
			'name' => 'VarChar( 200 ) NOT NULL',
			'project_id' => 'Int( 10 ) UNSIGNED NULL',
			'time_created' => 'DateTime NULL',
			'PRIMARY KEY (`id`)',
			'KEY `idx_project_id` (`project_id`)',
		), 'ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_unicode_ci');
		
		$this->createTable('subscription', array(
			'id' => 'Int( 10 ) UNSIGNED AUTO_INCREMENT NOT NULL',
			'last_view_time' => 'DateTime NULL',
			'task_id' => 'Int( 10 ) UNSIGNED NULL',
			'user_id' => 'Int( 10 ) UNSIGNED NULL',
			'PRIMARY KEY (`id`)',
			'KEY `idx_last_view_time` (`last_view_time`)',
			'KEY `idx_task_id` (`task_id`)',
			'KEY `idx_user_id` (`user_id`)',
		), 'ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_unicode_ci');
		
		$this->createTable('task_tag_tags', array(
			'task_id' => 'Int( 10 ) UNSIGNED NOT NULL',
			'tag_id' => 'Int( 10 ) UNSIGNED NOT NULL',
			'PRIMARY KEY (`task_id`, `tag_id`)',
		), 'ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_unicode_ci');
		
		$this->createTable('user', array(
			'id' => 'Int( 10 ) UNSIGNED AUTO_INCREMENT NOT NULL',
			'email' => 'VarChar( 100 ) NULL',
			'real_name' => 'VarChar( 100 ) NOT NULL',
			'username' => 'VarChar( 100 ) NULL',
			'password' => 'VarChar( 32 ) NULL',
			'salt' => 'VarChar( 32 ) NULL',
			'role' => 'VarChar( 30 ) NULL',
			'rate_id' => 'Int( 10 ) UNSIGNED NULL',
			'status' => 'Int( 11 ) NULL',
			'date_created' => 'Int( 11 ) NULL',
			'locale' => 'VarChar( 5 ) NULL DEFAULT \'en\'',
			'working_hours_id' => 'Int( 10 ) UNSIGNED NULL',
			'PRIMARY KEY (`id`)',
			'KEY `idx_email` (`email`)',
			'KEY `idx_username` (`username`)',
		), 'ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_unicode_ci');
		
		$user = new User();
		$salt = $user->generateSalt();
		$password = $user->hashPassword('secret', $salt);
		$this->insert('user', array(
			'email' => 'admin@example.com',
			'real_name' => 'Admin',
			'username' => 'admin',
			'password' => $password,
			'salt' => $salt,
			'role' => 'admin',
			'status' => User::STATUS_ENABLED,
			'date_created' => time(),
		));
	}

	public function down()
	{
		echo "m180427_144529_installation does not support migration down.\n";
		return false;
	}
}