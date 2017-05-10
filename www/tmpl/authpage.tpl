<?php
//модуль для статей
class Auth extends Module {
	
	public function __construct() {
		parent::__construct();		
	}	
	
	public function getTmplFile() {
		return "authpage";
	}
}

?>