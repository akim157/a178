<?php
/*класс отвечающий за модули */
abstract class Module extends AbstractModule {
	
	public function __construct() {
		//обращаемся к конструктуру родителя а именно классу AbstractModule
		//параметры передаются это объект класса view шаблонизатор
		//с параметром путь к директории где находиться tpl файлы
		parent::__construct(new View(Config::DIR_TMPL));
	}
	
}

?>