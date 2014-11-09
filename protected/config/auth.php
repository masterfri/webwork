<?php

return array(
	/**
	 * User
	 */
	'view_user' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'View users',
		'bizRule' => null,
		'data' => null,
	),
	'create_user' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Create users',
		'bizRule' => null,
		'data' => null,
	),
	'update_user' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Update users',
		'bizRule' => null,
		'data' => null,
	),
	'delete_user' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Delete users',
		'bizRule' => null,
		'data' => null,
	),
	/**
	 * Activity 
	 */
	'view_activity' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'View activity',
		'bizRule' => null,
		'data' => null,
	),
	'create_activity' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Create activity',
		'bizRule' => null,
		'data' => null,
	),
	'update_activity' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Edit activity',
		'bizRule' => null,
		'data' => null,
	),
	'delete_activity' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Delete activity',
		'bizRule' => null,
		'data' => null,
	),
	/**
	 * Assignment 
	 */
	'view_assignment' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'View assignment',
		'bizRule' => null,
		'data' => null,
	),
	'create_assignment' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Create assignment',
		'bizRule' => null,
		'data' => null,
	),
	'update_assignment' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Edit assignment',
		'bizRule' => null,
		'data' => null,
	),
	'delete_assignment' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Delete assignment',
		'bizRule' => null,
		'data' => null,
	),
	'view_shared_assignment' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'View shared assignment',
		'bizRule' => 'return (!isset($params["project"]) && !isset($params["assignment"])) || 
							 (isset($params["project"]) && $params["project"]->isUserAssigned($params["userId"])) ||
							 (isset($params["assignment"]) && $params["assignment"]->project->isUserAssigned($params["userId"]));',
		'data' => null,
		'children' => array(
			'view_assignment',
		),
	),
	'create_shared_assignment' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Create shared assignment',
		'bizRule' => 'return (!isset($params["project"])) || 
							 ($params["project"]->isUserAssigned($params["userId"], array(Assignment::ROLE_OWNER, Assignment::ROLE_MANAGER)));',
		'data' => null,
		'children' => array(
			'create_assignment',
		),
	),
	'update_shared_assignment' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Edit shared assignment',
		'bizRule' => 'return (!isset($params["assignment"])) || 
							 ($params["assignment"]->project->isUserAssigned($params["userId"], array(Assignment::ROLE_OWNER, Assignment::ROLE_MANAGER)));',
		'data' => null,
		'children' => array(
			'update_assignment',
		),
	),
	'delete_shared_assignment' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Delete shared assignment',
		'bizRule' => 'return (!isset($params["assignment"])) || 
							 ($params["assignment"]->project->isUserAssigned($params["userId"], array(Assignment::ROLE_OWNER, Assignment::ROLE_MANAGER)));',
		'data' => null,
		'children' => array(
			'delete_assignment',
		),
	),
	/**
	 * File
	 */
	'view_file' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'View files',
		'bizRule' => null,
		'data' => null,
	),
	'create_file' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Upload files',
		'bizRule' => null,
		'data' => null,
	),
	'update_file' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Edit files',
		'bizRule' => null,
		'data' => null,
	),
	'delete_file' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Delete files',
		'bizRule' => null,
		'data' => null,
	),
	/**
	 * FileCategory
	 */
	'view_file_category' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'View file categories',
		'bizRule' => null,
		'data' => null,
	),
	'create_file_category' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Create file categories',
		'bizRule' => null,
		'data' => null,
	),
	'update_file_category' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Edit file categories',
		'bizRule' => null,
		'data' => null,
	),
	'delete_file_category' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Delete file categories',
		'bizRule' => null,
		'data' => null,
	),
	/**
	 * Invoice 
	 */
	'view_invoice' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'View invoice',
		'bizRule' => null,
		'data' => null,
	),
	'create_invoice' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Create invoice',
		'bizRule' => null,
		'data' => null,
	),
	'update_invoice' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Edit invoice',
		'bizRule' => null,
		'data' => null,
	),
	'delete_invoice' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Delete invoice',
		'bizRule' => null,
		'data' => null,
	),
	/**
	 * Milestone 
	 */
	'view_milestone' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'View milestone',
		'bizRule' => null,
		'data' => null,
	),
	'query_milestone' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Query milestone',
		'bizRule' => null,
		'data' => null,
	),
	'create_milestone' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Create milestone',
		'bizRule' => null,
		'data' => null,
	),
	'update_milestone' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Edit milestone',
		'bizRule' => null,
		'data' => null,
	),
	'delete_milestone' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Delete milestone',
		'bizRule' => null,
		'data' => null,
	),
	'view_shared_milestone' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'View shared milestone',
		'bizRule' => 'return (!isset($params["project"]) && !isset($params["milestone"])) || 
							 (isset($params["project"]) && $params["project"]->isUserAssigned($params["userId"])) ||
							 (isset($params["milestone"]) && $params["milestone"]->project->isUserAssigned($params["userId"]));',
		'data' => null,
		'children' => array(
			'view_milestone',
		),
	),
	'query_shared_milestone' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Query shared milestone',
		'bizRule' => 'return (!isset($params["project"])) || 
							 (isset($params["project"]) && $params["project"] === "*" ? false : $params["project"]->isUserAssigned($params["userId"]));',
		'data' => null,
		'children' => array(
			'query_milestone',
		),
	),
	'create_shared_milestone' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Create shared milestone',
		'bizRule' => 'return (!isset($params["project"])) || 
							 ($params["project"]->isUserAssigned($params["userId"], array(Assignment::ROLE_OWNER, Assignment::ROLE_MANAGER)));',
		'data' => null,
		'children' => array(
			'create_milestone',
		),
	),
	'update_shared_milestone' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Edit shared milestone',
		'bizRule' => 'return (!isset($params["milestone"])) || 
							 ($params["milestone"]->project->isUserAssigned($params["userId"], array(Assignment::ROLE_OWNER, Assignment::ROLE_MANAGER)));',
		'data' => null,
		'children' => array(
			'update_milestone',
		),
	),
	'delete_shared_milestone' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Delete shared milestone',
		'bizRule' => 'return (!isset($params["milestone"])) || 
							 ($params["milestone"]->project->isUserAssigned($params["userId"], array(Assignment::ROLE_OWNER, Assignment::ROLE_MANAGER)));',
		'data' => null,
		'children' => array(
			'delete_milestone',
		),
	),
	/**
	 * Payment 
	 */
	'view_payment' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'View payment',
		'bizRule' => null,
		'data' => null,
	),
	'create_payment' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Create payment',
		'bizRule' => null,
		'data' => null,
	),
	'update_payment' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Edit payment',
		'bizRule' => null,
		'data' => null,
	),
	'delete_payment' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Delete payment',
		'bizRule' => null,
		'data' => null,
	),
	/**
	 * Project 
	 */
	'view_project' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'View project',
		'bizRule' => null,
		'data' => null,
	),
	'create_project' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Create project',
		'bizRule' => null,
		'data' => null,
	),
	'update_project' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Edit project',
		'bizRule' => null,
		'data' => null,
	),
	'delete_project' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Delete project',
		'bizRule' => null,
		'data' => null,
	),
	'view_shared_project' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'View shared project',
		'bizRule' => 'return (!isset($params["project"])) || 
							 ($params["project"] === "*" ? false : $params["project"]->isUserAssigned($params["userId"]));',
		'data' => null,
		'children' => array(
			'view_project',
		),
	),
	'update_shared_project' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Update shared project',
		'bizRule' => 'return (!isset($params["project"])) || 
							 ($params["project"]->isUserAssigned($params["userId"], array(Assignment::ROLE_OWNER, Assignment::ROLE_MANAGER)));',
		'data' => null,
		'children' => array(
			'update_project',
		),
	),
	'delete_shared_project' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Delete shared project',
		'bizRule' => 'return (!isset($params["project"])) || 
							 ($params["project"]->isUserAssigned($params["userId"], Assignment::ROLE_OWNER));',
		'data' => null,
		'children' => array(
			'delete_project',
		),
	),
	/**
	 * Rate 
	 */
	'view_rate' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'View rate',
		'bizRule' => null,
		'data' => null,
	),
	'create_rate' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Create rate',
		'bizRule' => null,
		'data' => null,
	),
	'update_rate' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Edit rate',
		'bizRule' => null,
		'data' => null,
	),
	'delete_rate' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Delete rate',
		'bizRule' => null,
		'data' => null,
	),
	/**
	 * Tag 
	 */
	'view_tag' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'View tag',
		'bizRule' => null,
		'data' => null,
	),
	'query_tag' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Query tag',
		'bizRule' => null,
		'data' => null,
	),
	'create_tag' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Create tag',
		'bizRule' => null,
		'data' => null,
	),
	'update_tag' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Edit tag',
		'bizRule' => null,
		'data' => null,
	),
	'delete_tag' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Delete tag',
		'bizRule' => null,
		'data' => null,
	),
	'query_shared_tag' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Query shared tag',
		'bizRule' => 'return (!isset($params["project"])) || 
							 ($params["project"] === "*" ? false : $params["project"]->isUserAssigned($params["userId"]));',
		'data' => null,
		'children' => array(
			'query_tag',
		),
	),
	/**
	 * Task 
	 */
	'view_task' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'View task',
		'bizRule' => null,
		'data' => null,
	),
	'query_task' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Query task',
		'bizRule' => null,
		'data' => null,
	),
	'create_task' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Create task',
		'bizRule' => null,
		'data' => null,
	),
	'update_task' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Edit task',
		'bizRule' => null,
		'data' => null,
	),
	'update_task_tags' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Update task tags',
		'bizRule' => null,
		'data' => null,
	),
	'update_task_priority' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Update task priority',
		'bizRule' => null,
		'data' => null,
	),
	'update_task_assignment' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Update task assignment',
		'bizRule' => null,
		'data' => null,
	),
	'estimate_task' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Estimate task',
		'bizRule' => null,
		'data' => null,
	),
	'delete_task' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Delete task',
		'bizRule' => null,
		'data' => null,
	),
	'comment_task' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Comment on task',
		'bizRule' => 'return (!isset($params["task"])) || 
							 ($params["task"]->getIsActionAvailable(Task::ACTION_COMMENT));',
		'data' => null,
	),
	'start_task' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Start work on task',
		'bizRule' => 'return (!isset($params["task"])) || 
							 ($params["task"]->getIsActionAvailable(Task::ACTION_START_WORK));',
		'data' => null,
	),
	'complete_task' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Complete work on task',
		'bizRule' => 'return (!isset($params["task"])) || 
							 ($params["task"]->getIsActionAvailable(Task::ACTION_COMPLETE_WORK));',
		'data' => null,
	),
	'return_task' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Return task',
		'bizRule' => 'return (!isset($params["task"])) || 
							 ($params["task"]->getIsActionAvailable(Task::ACTION_RETURN));',
		'data' => null,
	),
	'close_task' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Close task',
		'bizRule' => 'return (!isset($params["task"])) || 
							 ($params["task"]->getIsActionAvailable(Task::ACTION_CLOSE));',
		'data' => null,
	),
	'hold_task' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Put task on hold',
		'bizRule' => 'return (!isset($params["task"])) || 
							 ($params["task"]->getIsActionAvailable(Task::ACTION_PUT_ON_HOLD));',
		'data' => null,
	),
	'reopen_task' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Reopen task',
		'bizRule' => 'return (!isset($params["task"])) || 
							 ($params["task"]->getIsActionAvailable(Task::ACTION_REOPEN));',
		'data' => null,
	),
	'resume_task' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Resume task',
		'bizRule' => 'return (!isset($params["task"])) || 
							 ($params["task"]->getIsActionAvailable(Task::ACTION_RESUME));',
		'data' => null,
	),
	'view_shared_task' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'View shared task',
		'bizRule' => 'return (!isset($params["project"]) && !isset($params["task"])) || 
							 (isset($params["project"]) && $params["project"]->isUserAssigned($params["userId"])) ||
							 (isset($params["task"]) && ($params["task"] === "*" ? false : $params["task"]->project->isUserAssigned($params["userId"])));',
		'data' => null,
		'children' => array(
			'view_task',
		),
	),
	'query_shared_task' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Query shared task',
		'bizRule' => 'return (!isset($params["project"])) || 
							 (isset($params["project"]) && $params["project"] === "*" ? false : $params["project"]->isUserAssigned($params["userId"]));',
		'data' => null,
		'children' => array(
			'view_task',
		),
	),
	'create_shared_task' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Create shared task',
		'bizRule' => 'return (!isset($params["project"])) || 
							 ($params["project"]->isUserAssigned($params["userId"], array(Assignment::ROLE_OWNER, Assignment::ROLE_MANAGER)));',
		'data' => null,
		'children' => array(
			'create_task',
		),
	),
	'update_shared_task' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Edit shared task',
		'bizRule' => 'return (!isset($params["task"])) || 
							 ($params["task"]->project->isUserAssigned($params["userId"], array(Assignment::ROLE_OWNER, Assignment::ROLE_MANAGER)));',
		'data' => null,
		'children' => array(
			'update_task',
		),
	),
	'update_shared_task_tags' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Update shared task tags',
		'bizRule' => 'return (!isset($params["task"])) || 
							 ($params["task"]->project->isUserAssigned($params["userId"]));',
		'data' => null,
		'children' => array(
			'update_task_tags',
		),
	),
	'update_shared_task_priority' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Update shared task priority',
		'bizRule' => 'return (!isset($params["task"])) || 
							 ($params["task"]->project->isUserAssigned($params["userId"], array(Assignment::ROLE_OWNER, Assignment::ROLE_MANAGER)));',
		'data' => null,
		'children' => array(
			'update_task_priority',
		),
	),
	'update_shared_task_assignment' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Update shared task assignment',
		'bizRule' => 'return (!isset($params["task"])) || 
							 ($params["task"]->project->isUserAssigned($params["userId"], Assignment::ROLE_MANAGER));',
		'data' => null,
		'children' => array(
			'update_task_assignment',
		),
	),
	'estimate_shared_task' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Estimate shared task',
		'bizRule' => 'return (!isset($params["task"])) || 
							 ($params["task"]->project->isUserAssigned($params["userId"]));',
		'data' => null,
		'children' => array(
			'estimate_task',
		),
	),
	'delete_shared_task' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Delete shared task',
		'bizRule' => 'return (!isset($params["task"])) || 
							 ($params["task"]->project->isUserAssigned($params["userId"], array(Assignment::ROLE_OWNER, Assignment::ROLE_MANAGER)));',
		'data' => null,
		'children' => array(
			'delete_task',
		),
	),
	'comment_shared_task' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Comment on shared task',
		'bizRule' => 'return (!isset($params["task"])) || 
							 ($params["task"]->project->isUserAssigned($params["userId"]));',
		'data' => null,
		'children' => array(
			'comment_task',
		),
	),
	'start_shared_task' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Start work on shared task',
		'bizRule' => 'return (!isset($params["task"])) || 
							 ((!$params["task"]->assigned_id || $params["task"]->assigned_id == $params["userId"]) && $params["task"]->project->isUserAssigned($params["userId"]));',
		'data' => null,
		'children' => array(
			'start_task',
		),
	),
	'complete_shared_task' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Complete work on shared task',
		'bizRule' => 'return (!isset($params["task"])) || 
							 ($params["task"]->assigned_id == $params["userId"] && $params["task"]->project->isUserAssigned($params["userId"]));',
		'data' => null,
		'children' => array(
			'complete_task',
		),
	),
	'return_shared_task' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Return shared task',
		'bizRule' => 'return (!isset($params["task"])) || 
							 ($params["task"]->project->isUserAssigned($params["userId"], array(Assignment::ROLE_OWNER, Assignment::ROLE_MANAGER)));',
		'data' => null,
		'children' => array(
			'return_task',
		),
	),
	'close_shared_task' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Close shared task',
		'bizRule' => 'return (!isset($params["task"])) || 
							 ($params["task"]->project->isUserAssigned($params["userId"], array(Assignment::ROLE_OWNER, Assignment::ROLE_MANAGER)));',
		'data' => null,
		'children' => array(
			'close_task',
		),
	),
	'hold_shared_task' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Put shared task on hold',
		'bizRule' => 'return (!isset($params["task"])) || 
							 ($params["task"]->project->isUserAssigned($params["userId"], array(Assignment::ROLE_OWNER, Assignment::ROLE_MANAGER)));',
		'data' => null,
		'children' => array(
			'hold_task',
		),
	),
	'reopen_shared_task' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Reopen shared task',
		'bizRule' => 'return (!isset($params["task"])) || 
							 ($params["task"]->project->isUserAssigned($params["userId"], array(Assignment::ROLE_OWNER, Assignment::ROLE_MANAGER)));',
		'data' => null,
		'children' => array(
			'reopen_task',
		),
	),
	'resume_shared_task' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Resume shared task',
		'bizRule' => 'return (!isset($params["task"])) || 
							 ($params["task"]->project->isUserAssigned($params["userId"], array(Assignment::ROLE_OWNER, Assignment::ROLE_MANAGER)));',
		'data' => null,
		'children' => array(
			'resume_task',
		),
	),
	/**
	 * TimeEntry 
	 */
	'view_time_entry' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'View time entry',
		'bizRule' => null,
		'data' => null,
	),
	'create_time_entry' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Create time entry',
		'bizRule' => null,
		'data' => null,
	),
	'update_time_entry' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Edit time entry',
		'bizRule' => null,
		'data' => null,
	),
	'delete_time_entry' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Delete time entry',
		'bizRule' => null,
		'data' => null,
	),
	'report_time_entry' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Report time entry',
		'bizRule' => 'return (!isset($params["task"])) || 
							 ($params["task"]->project->isUserAssigned($params["userId"]));',
		'data' => null,
	),
	'daily_time_report' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Dialy time report',
		'bizRule' => null,
		'data' => null,
	),
	'view_my_time_entry' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'View my time entry',
		'bizRule' => 'return (!isset($params["entry"])) || 
							 ($params["entry"] === "*" ? false : $params["entry"]->user_id == $params["userId"]);',
		'data' => null,
		'children' => array(
			'view_time_entry',
		),
	),
	'update_my_time_entry' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Edit my time entry',
		'bizRule' => 'return (!isset($params["entry"])) || 
							 ($params["entry"] === "*" ? false : $params["entry"]->user_id == $params["userId"]);',
		'data' => null,
		'children' => array(
			'update_time_entry',
		),
	),
	'delete_my_time_entry' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Delete my time entry',
		'bizRule' => 'return (!isset($params["entry"])) || 
							 ($params["entry"] === "*" ? false : $params["entry"]->user_id == $params["userId"]);',
		'data' => null,
		'children' => array(
			'delete_time_entry',
		),
	),
	/**
	 * Basic roles
	 */
	'guest' => array(
		'type' => CAuthItem::TYPE_ROLE,
		'description' => 'Гость',
		'bizRule' => null,
		'data' => null
	),
	'user' => array(
		'type' => CAuthItem::TYPE_ROLE,
		'description' => 'Участник',
		'children' => array(
			'guest',
			'view_shared_project',
			'update_shared_project',
			'view_shared_milestone',
			'query_shared_milestone',
			'create_shared_milestone',
			'update_shared_milestone',
			'delete_shared_milestone',
			'view_shared_task',
			'create_shared_task',
			'update_shared_task',
			'update_shared_task_tags',
			'update_shared_task_priority',
			'update_shared_task_assignment',
			'delete_shared_task',
			'comment_shared_task',
			'return_shared_task',
			'close_shared_task',
			'hold_shared_task',
			'reopen_shared_task',
			'resume_shared_task',
			'query_shared_tag',
		),
		'bizRule' => null,
		'data' => null
	),
	'client' => array(
		'type' => CAuthItem::TYPE_ROLE,
		'description' => 'Клиент',
		'children' => array(
			'create_project',
			'user',
		),
		'bizRule' => null,
		'data' => null
	),
	'worker' => array(
		'type' => CAuthItem::TYPE_ROLE,
		'description' => 'Cотрудник',
		'children' => array(
			'user',
			'start_shared_task',
			'complete_shared_task',
			'estimate_shared_task',
			'daily_time_report',
			'report_time_entry',
			'view_my_time_entry',
			'update_my_time_entry',
			'delete_my_time_entry',
		),
		'bizRule' => null,
		'data' => null
	),
	'developer' => array(
		'type' => CAuthItem::TYPE_ROLE,
		'description' => 'Разработчик',
		'children' => array(
			'worker',
		),
		'bizRule' => null,
		'data' => null
	),
	'tester' => array(
		'type' => CAuthItem::TYPE_ROLE,
		'description' => 'Тестер',
		'children' => array(
			'worker',
		),
		'bizRule' => null,
		'data' => null
	),
	'teamlead' => array(
		'type' => CAuthItem::TYPE_ROLE,
		'description' => 'Тимлид',
		'children' => array(
			'developer',
			'tester',
			'view_shared_assignment',
			'create_shared_assignment',
			'update_shared_assignment',
			'delete_shared_assignment',
		),
		'bizRule' => null,
		'data' => null
	),
	'manager' => array(
		'type' => CAuthItem::TYPE_ROLE,
		'description' => 'Менеджер',
		'children' => array(
			'view_project',
			'create_project',
			'update_project',
			'delete_project',
			'view_task',
			'create_task',
			'update_task',
			'delete_task',
			'update_task_tags',
			'update_task_priority',
			'update_task_assignment',
			'estimate_task',
			'comment_task',
			'start_task',
			'complete_task',
			'return_task',
			'close_task',
			'hold_task',
			'reopen_task',
			'resume_task',
			'view_time_entry',
			'create_time_entry',
			'update_time_entry',
			'delete_time_entry',
			'view_milestone',
			'query_milestone',
			'create_milestone',
			'update_milestone',
			'delete_milestone',
			'view_assignment',
			'create_assignment',
			'update_assignment',
			'delete_assignment',
			'view_invoice',
			'create_invoice',
			'update_invoice',
			'delete_invoice',
			'view_payment',
			'create_payment',
			'update_payment',
			'delete_payment',
			'view_rate',
			'create_rate',
			'update_rate',
			'delete_rate',
			'client',
			'teamlead',
		),
		'bizRule' => null,
		'data' => null
	),
	'admin' => array(
		'type' => CAuthItem::TYPE_ROLE,
		'description' => 'Администратор',
		'children' => array(
			'view_user',
			'create_user',
			'update_user',
			'delete_user',
			'view_activity',
			'create_activity',
			'update_activity',
			'delete_activity',
			'view_file',
			'create_file',
			'update_file',
			'delete_file',
			'view_file_category',
			'create_file_category',
			'update_file_category',
			'delete_file_category',
			'view_tag',
			'query_tag',
			'create_tag',
			'update_tag',
			'delete_tag',
			'manager',
		),
		'bizRule' => null,
		'data' => null
	),
);
