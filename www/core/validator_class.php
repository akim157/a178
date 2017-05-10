<?php

/*класс который отвечат за все validator для провераки корректности данных (лоигн, пароль и т.д.)*/
abstract class Validator {
	/*это явлется паттерном*/
	//неизвестная ошибка
	const CODE_UNKNOWN = "UNKNOWN_ERROR";
	
	//данные
	protected $data;
	//массив ошибок
	private $errors = array();
	
	//AbstractObjectDB метод validate() в качестве параметра передается data
	public function __construct($data) {
		$this->data = $data;
		$this->validate();
	}
	
	//будет абязан реализован в каждом дочернем классе от валидатаора
	abstract protected function validate();
	
	//возвращает массив ошибки
	public function getErrors() {
		return $this->errors;
	}
	
	//возвращает количество ошибок если ошибок нет то true если есть то false
	public function isValid() {
		return count($this->errors) == 0;
	}
	
	//запись ошибок в массив ошибок
	protected function setError($code) {
		$this->errors[] = $code;
	}
	
	protected function isContainQuotes($string) {
		$array = array("\"", "'", "`", "&quot;", "&apos;");
		foreach ($array as $value) {
			if (strpos($string, $value) !== false) return true;
		}
		return false;
	}
	
}

?>