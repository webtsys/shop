<?php

class indexSwitchController extends ControllerSwitchClass {

	public function index()
	{
	
		echo View::load_theme(I18n::lang('shop', 'home_shop', 'Shop'), '');
	
	}

}

?>