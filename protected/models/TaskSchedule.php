<?php 

class TaskSchedule extends CActiveRecord  
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function tableName()
	{
		return '{{task_schedule}}';
	}
	
	public function relations()
	{
		return array(
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'task' => array(self::BELONGS_TO, 'Task', 'task_id'),
		);
	}
	
	public function addTask($task_id, $user_id, $date_start, $hours)
	{
		$working_day_length = $this->getWorkingDayLength();
		
		$today = MysqlDateHelper::currentDate();
		if (MysqlDateHelper::gt($today, $date_start)) {
			$date_start = $today;
		}
		$datetime = new DateTime($date_start);
		$start_day = $datetime->format('j');
		$start_month = $month = $datetime->format('n');
		$start_year = $year = $datetime->format('Y');
		while ($hours > 0) {
			$working_days = $this->getWorkingDays($month, $year, $start_day, $start_month, $start_year);
			$user_loading = $this->getUserLoading($user_id, $month, $year, $date_start);
			foreach ($working_days as $date) {
				if (isset($user_loading[$date])) {
					$free_hours = $working_day_length - $user_loading[$date];
				} else {
					$free_hours = $working_day_length;
				}
				if ($free_hours > 0) {
					$entry = new self();
					$entry->task_id = $task_id;
					$entry->user_id = $user_id;
					$entry->date = $date;
					$entry->hours = min($free_hours, $hours);
					$entry->save();
					$hours -= $free_hours;
					if ($hours <= 0) {
						break;
					}
				}
			}
			if ($month == 12) {
				$month = 1;
				$year++;
			} else {
				$month++;
			}
		}
	}
	
	protected function getWorkingDays($month, $year, $start_day, $start_month, $start_year)
	{
		if ($month == $start_month && $year == $start_year) {
			$day = $start_day;
		} else {
			$day = 1;
		}
		$last_day = date('t', mktime(0,0,0, $month, 1, $year));
		$result = array();
		for (; $day <= $last_day; $day++) {
			if ($this->isWorkingDay($day, $month, $year)) {
				$result[] = MysqlDateHelper::mkdate($day, $month, $year);
			}
		}
		return $result;
	}
	
	protected function getUserLoading($user_id, $month, $year, $date_start)
	{
		$result = array();
		$criteria = new CDbCriteria();
		$criteria->select = '`date`, SUM(`hours`) AS `hours`';
		$criteria->compare('user_id', $user_id);
		$criteria->compare('YEAR(`date`)', $year);
		$criteria->compare('MONTH(`date`)', $month);
		$criteria->addCondition('`date` >= :today');
		$criteria->group = '`date`';
		$criteria->params[':today'] = $date_start;
		foreach ($this->findAll($criteria) as $entry) {
			$result[$entry->date] = $entry->hours;
		}
		return $result;
	}
	
	public function isWorkingDay($day, $month, $year)
	{
		return  !$this->isHoliday($day, $month, $year) && 
				!$this->isWeekend($day, $month, $year);
	}
	
	public function isHoliday($day, $month, $year)
	{
		 // TODO to config
		return false;
	}
	
	public function isWeekend($day, $month, $year)
	{
		$holidays = array('6', '7'); // TODO to config
		
		return in_array(date('N', mktime(0,0,0, $month, $day, $year)), $holidays);
	}
	
	public function scopes()
	{
		return array(
			'my' => array(
				'condition' => 'taskSchedule.user_id = :current_user_id',
				'params' => array(
					':current_user_id' => Yii::app()->user->id,
				),
			),
		);
	}
	
	public function getWeek($start, $params=array())
	{
		$grid = array();
		$hr = array();
		$criteria = new CDbCriteria($params);
		$criteria->alias = 'taskSchedule';
		$criteria->with = array('user', 'task');
		$criteria->addCondition('taskSchedule.date BETWEEN :start AND DATE_ADD(:start, INTERVAL 6 DAY)');
		$criteria->order = 'task.priority DESC';
		$criteria->params[':start'] = $start;
		foreach ($this->findAll($criteria) as $entry) {
			$grid[$entry->user_id][$entry->date][] = $entry;
			if (!isset($hr[$entry->user_id])) {
				$hr[$entry->user_id] = $entry->user;
			}
		}
		return array(
			'grid' => $grid,
			'hr' => $hr,
		);
	}
	
	public function getWorkingDayLength()
	{
		return 8;  // TODO to config
	}
}
