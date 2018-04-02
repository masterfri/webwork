<?php

class InvoiceCommand extends CConsoleCommand
{
	protected static $_employeeRoles = array(
		'worker',
		'developer',
		'tester',
		'teamlead',
		'manager',
		'admin',
	);
	
	protected static $_customerRoles = array(
		'client',
	);
	
	protected $_silent = false;
	
	public function actionGenerate($month=null, $silent=null, $forUser=null)
	{
		if ($silent == '1') {
			$this->_silent = true;
		}
		if (null == $month) {
			$m = date('m', mktime(0,0,0, date('m') - 1));
			$y = date('Y', mktime(0,0,0, date('m') - 1));
		} else {
			$month = explode('-', $month);
			$m = array_shift($month);
			$y = count($month) ? array_shift($month) : date('Y');
		}
		
		$this->say(sprintf("Generating invoices for %s", date('F Y', mktime(0,0,0, $m, 1, $y))));
		
		$builder = Yii::app()->db->commandBuilder;
		$criteria = new CDbCriteria();
		$criteria->compare('status', User::STATUS_ENABLED);
		if ($forUser !== null) {
			$criteria->addCondition('id = :userID OR username = :userID');
			$criteria->params[':userID'] = $forUser;
		}
		$usermodel = User::model();
		$reader = $builder->createFindCommand($usermodel->tableName(), $criteria)->query();
		while ($row = $reader->read()) {
			$user = $usermodel->populateRecord($row);
			$this->generateInvoiceForUser($m, $y, $user, $builder);
		}
		$reader->close();
	}
	
	protected function generateInvoiceForUser($m, $y, $user, $builder)
	{
		if ($user->rate === null) {
			$this->say(sprintf("Rate is not set for user: %s", $user->getDisplayName()));
		} else {
			$monthName = Yii::app()->dateFormatter->format('LLLL', mktime(0,0,0, $m, 1, $y));
			if (in_array($user->role, self::$_employeeRoles)) {
				$reader = $this->getEmployeeInvoiceItems($builder, $user, $y, $m);
				if ($reader->getRowCount() > 0) {
					$this->say(sprintf("Generating salary invoice for employee: %s", $user->getDisplayName()));
					$comments = Yii::t('invoice', 'Salary for {month}, {year}', array(
						'{month}' => $monthName,
						'{year}' => $y,
					));
					$this->createInvoice($user->id, null, $comments, 0, $reader, $user->rate->getCompleteMatrix(), true);
				}
				$reader->close();
			}
			if (in_array($user->role, self::$_customerRoles)) {
				$reader = $this->getCustomerInvoiceItems($builder, $user, $y, $m);
				if ($reader->getRowCount() > 0) {
					$this->say(sprintf("Generating invoice for client: %s", $user->getDisplayName()));
					$comments = Yii::t('invoice', 'Monthly invoice for {month}, {year}', array(
						'{month}' => $monthName,
						'{year}' => $y,
					), null, $user->locale);
					$this->createInvoice(null, $user->id, $comments, 1, $reader, $user->rate->getCompleteMatrix(), false);
				}
				$reader->close();
			}
		}
	}
	
	protected function getEmployeeInvoiceItems($builder, $user, $y, $m)
	{
		$criteria = new CDbCriteria();
		$criteria->alias = 'timeentry';
		$criteria->compare('timeentry.user_id', $user->id);
		$criteria->select = 'timeentry.activity_id, SUM(timeentry.amount) AS amount, '.
							'GROUP_CONCAT(DISTINCT timeentry.description SEPARATOR ";") AS description, '.
							'timeentry.task_id, task.name AS task_name, activity.name AS activity_name, '.
							'project.bonus, project.bonus_type';
		$criteria->group = 'timeentry.task_id, timeentry.activity_id';
		$criteria->join = 'LEFT JOIN {{task}} task ON task.id = timeentry.task_id '.
						  'LEFT JOIN {{project}} project ON project.id = task.project_id '.
						  'LEFT JOIN {{activity}} activity ON activity.id = timeentry.activity_id';
		$criteria->compare('YEAR(timeentry.date_created)', $y);
		$criteria->compare('MONTH(timeentry.date_created)', $m);
		return $builder->createFindCommand(TimeEntry::model()->tableName(), $criteria)->query();
	}
	
	protected function getCustomerInvoiceItems($builder, $user, $y, $m)
	{
		$criteria = new CDbCriteria();
		$criteria->alias = 'timeentry';
		$criteria->select = 'timeentry.activity_id, SUM(timeentry.amount) AS amount, '.
							'GROUP_CONCAT(DISTINCT timeentry.description SEPARATOR ";") AS description, '.
							'timeentry.task_id, task.name AS task_name, activity.name AS activity_name';
		$criteria->group = 'timeentry.task_id, timeentry.activity_id';
		$criteria->join = 'INNER JOIN {{task}} task ON task.id = timeentry.task_id '.
						  'INNER JOIN {{assignment}} assignment ON assignment.project_id = task.project_id '.
								'AND assignment.user_id = :user AND assignment.role = :owner '.
						  'LEFT JOIN {{activity}} activity ON activity.id = timeentry.activity_id';
		$criteria->compare('YEAR(timeentry.date_created)', $y);
		$criteria->compare('MONTH(timeentry.date_created)', $m);
		$criteria->params[':user'] = $user->id;
		$criteria->params[':owner'] = Assignment::ROLE_OWNER;
		return $builder->createFindCommand(TimeEntry::model()->tableName(), $criteria)->query();
	}
	
	protected function createInvoice($from, $to, $comments, $draft, $items, $matrix, $apply_bonus) 
	{
		$invoice = new Invoice('create');
		$invoice->from_id = $from;
		$invoice->to_id = $to;
		$invoice->comments = $comments;
		$invoice->draft = $draft;
		$invoice->save();
		while ($row = $items->read()) {
			$label = !empty($row['task_name']) ? $row['task_name'] : $row['description'];
			$label = !empty($label) ? sprintf('%s (%s)', $label, $row['activity_name']) : $row['activity_name'];
			$label = mb_substr($label, 0, 200);
			$item = new InvoiceItem('create');
			$item->invoice = $invoice;
			$item->task_id = $row['task_id'];
			$item->hours = $row['amount'];
			$item->value = isset($matrix[$row['activity_id']]) ? $matrix[$row['activity_id']]->hour_rate * $row['amount'] : 0;
			if ($apply_bonus && $row['bonus'] != 0) {
				if ($row['bonus_type'] == Project::BONUS_ABSOLUTE) {
					$item->bonus = $row['amount'] * $row['bonus'];
					$item->value += $item->bonus;
				} elseif ($row['bonus_type'] == Project::BONUS_PERCENT) {
					$item->bonus = $item->value * ($row['bonus'] / 100);
					$item->value += $item->bonus;
				}
			}
			$item->name = $label;
			$item->save(false);
		}
	}
	
	protected function say($message)
	{
		if (!$this->_silent) {
			echo sprintf("[%s] %s \n", date('r'), $message);
		}
	}
}
