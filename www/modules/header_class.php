<?php
/*модуль отвечающий за шапку сайта*/
class Header extends Module {
	
	public function __construct() {
		//обращаемся к родительскому уонструктору
		parent::__construct();
		//добавляем свойство в объект модуля
		$this->add("title");
		$this->add("favicon");
		$this->add("meta", null, true);
		$this->add("css", null, true);
		$this->add("js", null, true);
	}
	//для добавления массива в свойства
	public function meta($name, $content, $http_equiv) {
		/*название массива
		его содержимое
		тип*/
		//создаем объект
		$class = new stdClass();
		//присваем свойства
		$class->name = $name;
		$class->content = $content;
		$class->http_equiv = $http_equiv;
		$this->meta = $class;
	}
	//получаем название tpl файла отвечающий за данный модуль
	public function getTmplFile() {
		return "header";
	}
	
}

?>