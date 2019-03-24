<?php

class m190320_155631_upgrade_to_1_5 extends CDbMigration
{
	public function up()
	{
		$this->createTable('question_category', array(
			'id' => 'Int( 10 ) UNSIGNED AUTO_INCREMENT NOT NULL',
			'created_by_id' => 'Int( 10 ) UNSIGNED NOT NULL',
			'time_created' => 'DateTime NOT NULL',
			'name' => 'VarChar( 200 ) NOT NULL',
			'PRIMARY KEY (`id`)',
		), 'ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_unicode_ci');
		
		$this->createTable('question', array(
			'id' => 'Int( 10 ) UNSIGNED AUTO_INCREMENT NOT NULL',
			'created_by_id' => 'Int( 10 ) UNSIGNED NOT NULL',
			'time_created' => 'DateTime NOT NULL',
			'text' => 'Text NOT NULL',
			'category_id' => 'Int( 10 ) UNSIGNED NOT NULL',
			'level' => 'Int( 10 ) UNSIGNED NOT NULL',
			'time' => 'Int( 10 ) UNSIGNED NOT NULL',
			'PRIMARY KEY (`id`)',
		), 'ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_unicode_ci');
		
		$this->createTable('answer', array(
			'id' => 'Int( 10 ) UNSIGNED AUTO_INCREMENT NOT NULL',
			'text' => 'Text NOT NULL',
			'question_id' => 'Int( 10 ) UNSIGNED NOT NULL',
			'score' => 'Int( 10 ) UNSIGNED NOT NULL',
			'PRIMARY KEY (`id`)',
			'KEY `idx_question_id` (`question_id`)',
		), 'ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_unicode_ci');
		
		$this->createTable('candidate', array(
			'id' => 'Int( 10 ) UNSIGNED AUTO_INCREMENT NOT NULL',
			'created_by_id' => 'Int( 10 ) UNSIGNED NOT NULL',
			'time_created' => 'DateTime NOT NULL',
			'name' => 'VarChar( 200 ) NOT NULL',
			'token' => 'VarChar( 200 ) NOT NULL',
			'notes' => 'Text NULL',
			'level' => 'Int( 10 ) UNSIGNED NULL',
			'lang' => 'VarChar( 10 ) NOT NULL DEFAULT \'en\'',
			'questions_limit' => 'Int( 10 ) UNSIGNED NOT NULL',
			'time_examine_started' => 'DateTime NULL',
			'time_examine_ended' => 'DateTime NULL',
			'abandoned' => 'TinyInt( 3 ) UNSIGNED NOT NULL DEFAULT \'0\'',
			'examine_score' => 'Int( 10 ) UNSIGNED NULL',
			'examine_score_max' => 'Int( 10 ) UNSIGNED NULL',
			'PRIMARY KEY (`id`)',
		), 'ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_unicode_ci');
		
		$this->createTable('candidate_categories', array(
			'candidate_id' => 'Int( 10 ) UNSIGNED NOT NULL',
			'category_id' => 'Int( 10 ) UNSIGNED NOT NULL',
			'PRIMARY KEY (`candidate_id`, `category_id`)',
		), 'ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_unicode_ci');
		
		$this->createTable('candidate_answer', array(
			'id' => 'Int( 10 ) UNSIGNED AUTO_INCREMENT NOT NULL',
			'candidate_id' => 'Int( 10 ) UNSIGNED NOT NULL',
			'question_id' => 'Int( 10 ) UNSIGNED NOT NULL',
			'answer_id' => 'Int( 10 ) UNSIGNED NULL',
			'time_questioned' => 'DateTime NULL',
			'time_answered' => 'DateTime NULL',
			'PRIMARY KEY (`id`)',
		), 'ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_unicode_ci');
	}

	public function down()
	{
		$this->dropTable('candidate_answer');
		$this->dropTable('candidate_categories');
		$this->dropTable('candidate');
		$this->dropTable('answer');
		$this->dropTable('question');
		$this->dropTable('question_category');
	}
}