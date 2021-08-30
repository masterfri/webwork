<?php

class m210826_155631_upgrade_to_1_6 extends CDbMigration
{
	public function up()
	{
        $this->addColumn('user', 'legal_signer_name', 'VarChar( 200 ) NULL');
		$this->addColumn('user', 'legal_name', 'VarChar( 200 ) NULL');
        $this->addColumn('user', 'legal_type', 'Int( 10 ) UNSIGNED NULL');
        $this->addColumn('user', 'legal_number', 'VarChar( 50 ) NULL');
        $this->addColumn('user', 'legal_address', 'Text NULL');
		$this->addColumn('user', 'document_locale', 'VarChar( 5 ) NULL DEFAULT \'en\'');
		
		$this->createTable('completion_reports', array(
			'id' => 'Int( 10 ) UNSIGNED AUTO_INCREMENT NOT NULL',
            'date' => 'Date NOT NULL',
			'number' => 'Int( 10 ) UNSIGNED NULL',
            'contract_number' => 'VarChar( 50 ) NULL',
            'contract_date' => 'Date NOT NULL',
			'draft' => 'TinyInt( 3 ) UNSIGNED NOT NULL DEFAULT \'1\'',
			'performer_id' => 'Int( 10 ) UNSIGNED NULL',
            'contragent_id' => 'Int( 10 ) UNSIGNED NULL',
			'time_created' => 'DateTime NULL',
            'created_by_id' => 'Int( 10 ) UNSIGNED NULL',
			'PRIMARY KEY (`id`)',
			'KEY `idx_contract_number` (`contract_number`)',
			'KEY `idx_performer_id` (`performer_id`)',
			'KEY `idx_contragent_id` (`contragent_id`)',
		), 'ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_unicode_ci');

        $this->createTable('completed_jobs', array(
			'id' => 'Int( 10 ) UNSIGNED AUTO_INCREMENT NOT NULL',
			'report_id' => 'Int( 10 ) UNSIGNED NULL',
			'name' => 'VarChar( 200 ) NOT NULL',
            'qty' => 'Int( 10 ) UNSIGNED NOT NULL DEFAULT \'1\'',
			'price' => 'Decimal( 10, 2 ) NOT NULL',
			'PRIMARY KEY (`id`)',
			'KEY `idx_report_id` (`report_id`)',
		), 'ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_unicode_ci');
	}

	public function down()
	{
        $this->dropTable('completed_jobs');
        $this->dropTable('completion_reports');
        $this->dropColumn('user', 'legal_signer_name');
        $this->dropColumn('user', 'legal_name');
        $this->dropColumn('user', 'legal_type');
        $this->dropColumn('user', 'legal_number');
        $this->dropColumn('user', 'legal_address');
		$this->dropColumn('user', 'document_locale');
	}
}