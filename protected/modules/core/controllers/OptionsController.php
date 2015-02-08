<?php

class OptionsController extends AdminController 
{

	public function filters()
	{
		return array(
			'accessControl',
		);
	}
	
	public function accessRules()
	{
		return array(
			array('deny'),
		);
	}
}
