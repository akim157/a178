<?php
/*модуль для введения в разделах*/
class Intro extends ModuleHornav {
	
	public function __construct() {
		parent::__construct();
		$this->add("obj");
	}
	
	public function getTmplFile() {
		return "intro";
	}
	
}

?>