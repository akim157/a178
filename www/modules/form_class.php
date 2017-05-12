<?php
/*модуль для формы*/
class Form extends ModuleHornav {
	
	public function __construct() {
		parent::__construct();
		$this->add("name");
		$this->add("action");
		//метод
		$this->add("method", "post");
		//заголовок
		$this->add("header");
		//сообщение
		$this->add("message");
		//проверка
		$this->add("check", true);
		//файлы отправка
		$this->add("enctype");
		//массив элементов, которая будет содержать поля
		$this->add("inputs", null, true);
		//массив для проверок
		$this->add("jsv", null, true);
	}
	//проверка формы
	public function addJSV($field, $jsv) {
		$this->addObj("jsv", $field, $jsv);
	}
	//текстовое поле
	public function text($name, $label = "", $value = "", $default_v = "") {
		$this->input($name, "text", $label, $value, $default_v);
	}
	//текстовое поле для пароля
	public function password($name, $label = "", $default_v = "") {
		$this->input($name, "password", $label, "", $default_v);
	}
	//текстовое поля для капчи
	public function captcha($name, $label) {
		$this->input($name, "captcha", $label);
	}
	//поля для добавления файла
	public function file($name, $label) {
		$this->input($name, "file", $label);
	}
	//скрытое поле
	public function hidden($name, $value) {
		$this->input($name, "hidden", "", $value);
	}
	//кнопка submit
	public function submit($value, $name = false) {
		$this->input($name, "submit", "", $value);
	}
	//поле выборки
	public function select($name, $label = "", $value = array(), $default_v= "", $selected = "") {
		$this->input($name, "select", $label, $value, $default_v, $selected);
	}
	//создаем объект для построение input
	private function input($name, $type, $label, $value = false, $default_v = false) {
		$cl = new stdClass();
		$cl->name = $name;
		$cl->type = $type;
		$cl->label = $label;
		$cl->value = $value;
		$cl->default_v = $default_v;
		$this->inputs = $cl;
	}
	//tpl файл
	public function getTmplFile() {
		return "form";
	}
	
}

?>