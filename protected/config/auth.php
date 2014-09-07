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
							 ($params["project"] === "*" ? false : $params["project"]->isUserAssigned($params["userId"], array(Assignment::ROLE_OWNER, Assignment::ROLE_MANAGER)));',
		'data' => null,
		'children' => array(
			'update_project',
		),
	),
	'delete_shared_project' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Delete shared project',
		'bizRule' => 'return (!isset($params["project"])) || 
							 ($params["project"] === "*" ? false : $params["project"]->isUserAssigned($params["userId"], Assignment::ROLE_OWNER));',
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
	/**
	 * Task 
	 */
	'view_task' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'View task',
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
	'delete_task' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Delete task',
		'bizRule' => null,
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
							 (isset($params["task"]) && ($params["task"] === "*" ? false : $params["task"]->project->isUserAssigned($params["userId"], array(Assignment::ROLE_OWNER, Assignment::ROLE_MANAGER))));',
		'data' => null,
		'children' => array(
			'update_task',
		),
	),
	'delete_shared_task' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Delete shared task',
		'bizRule' => 'return (!isset($params["task"])) || 
							 (isset($params["task"]) && ($params["task"] === "*" ? false : $params["task"]->project->isUserAssigned($params["userId"], array(Assignment::ROLE_OWNER, Assignment::ROLE_MANAGER))));',
		'data' => null,
		'children' => array(
			'delete_task',
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
	'daily_time_report' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Dialy time report',
		'bizRule' => null,
		'data' => null,
	),
	'create_time_entry' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Create time entry',
		'bizRule' => null,
		'data' => null,
	),
	'report_time_entry' => array(
		'type' => CAuthItem::TYPE_OPERATION,
		'description' => 'Report time entry',
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
			'create_shared_milestone',
			'update_shared_milestone',
			'delete_shared_milestone',
			'view_shared_task',
			'create_shared_task',
			'update_shared_task',
			'delete_shared_task',
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
	'developer' => array(
		'type' => CAuthItem::TYPE_ROLE,
		'description' => 'Разработчик',
		'children' => array(
			'user',
		),
		'bizRule' => null,
		'data' => null
	),
	'tester' => array(
		'type' => CAuthItem::TYPE_ROLE,
		'description' => 'Тестер',
		'children' => array(
			'user',
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
			'view_time_entry',
			'daily_time_report',
			'create_time_entry',
			'report_time_entry',
			'update_time_entry',
			'delete_time_entry',
			'view_milestone',
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
			'create_tag',
			'update_tag',
			'delete_tag',
			'manager',
		),
		'bizRule' => null,
		'data' => null
	),
);
