<?php
/*модуль отвечающий за верхнее меню*/
class TopMenu extends Module {
	
	public function __construct() {
		//вызываем родительский конструктор
		parent::__construct();
		//добавляем свойства
		//активная ссылка
		$this->add("uri");
		//все пункты меню
		$this->add("items", null, true);
		//ссылка на регистрация
		$this->add("link_register");
	}
	//получаем tpl файл отвечающий за верхнее меню
	public function getTmplFile() {
		return "topmenu";
	}
	
}

?>