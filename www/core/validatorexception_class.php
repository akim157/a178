<?php
/*класс служил для хранения ошибок в виде исключений
он будет дочерним классом в строенный php класс исключений exception*/
class ValidatorException extends Exception {
	//поле для хранение ошибок
	private $errors;
	
	public function __construct($errors) {
		//вызываем родительский конструктор у класса Exception 
		parent::__construct();
		$this->errors = $errors;
	}
	
	//получаем ошибки, служит для отладки и вывода сообщений пользователю
	public function getErrors() {
		return $this->errors;
	}
	
}

?>