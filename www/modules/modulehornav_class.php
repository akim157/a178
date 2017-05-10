<?php
/*отдельный модуль для хлебных крошек*/
abstract class ModuleHornav extends Module {
	
	public function __construct() {
		parent::__construct();
		$this->add("hornav");
	}
	
}

?>