<?php
/*класс будет служить для всех модулей как родительский*/
abstract class AbstractModule {
	
	//массив где будет храниться название полей в таблицах БД
	private $properties = array();
	//шаблонизатор объект
	private $view;
	
	//присваем объект шаблонизатора
	public function __construct($view) {
		$this->view = $view;
	}
	
	//добавление нового свйоства для модуля не нужно его переприаделять в классах наследниках
	final protected function add($name, $default = null, $is_array = false) {
		/*
			name - название свойства
			default - значение по умолчинаю
			is_array - является ли данное свойство массивом
		*/
		//доабвляем новый элемент в свойства properties подстваляем значения
		$this->properties[$name]["is_array"] = $is_array;
		//проверка если true и null то в свойства помещаим пустой массив
		if ($is_array && $default == null) $this->properties[$name]["value"] = array();
		//если нет то значения по умолчанию
		else $this->properties[$name]["value"] = $default;		
	}
	
	//метод доступа и установка значений
	final public function __get($name) {
		//если в properties существуе такое свойство то его возвращаем если нет то просто null
		if (array_key_exists($name, $this->properties)) return $this->properties[$name]["value"];
		return null;
	}
	
	/*настройка свойст*/
	final public function __set($name, $value) {
		/*
			name - свойство
			value - значение данного свойства
		*/
		//если свойства есть
		if (array_key_exists($name, $this->properties)) {
			//если оно является массивом
			if (is_array($this->properties[$name]["value"])) {
				//если само значение массива является массивом изменям
				if (is_array($value)) $this->properties[$name]["value"] = $value;
				//если не является массиов создаем еще ступень и тогда приравниваем
				else $this->properties[$name]["value"][] = $value;
			}
			//если свойство не является массивом то просто привсваеваем
			else $this->properties[$name]["value"] = $value;
		}//если не найдено возвращаем false
		else return false;
	}
	
	//возвращаем массив свойст с ключами и значениями
	final protected function getProperties() {
		$ret = array();		
		foreach ($this->properties as $name => $value) {
			$ret[$name] = $value["value"];
		}
		return $ret;
	}
	//проверка формы
	final protected function addObj($name, $field, $obj) {
		if (array_key_exists($name, $this->properties)) $this->properties[$name]["value"][$field] = $obj;
	}
	//работа с компелксными даннымии user->login распарсить
	final protected function getComplexValue($obj, $field) {
		if (strpos($field, "->") !== false) $field = explode("->", $field);
		if (is_array($field)) {
			$value = $obj;
			foreach ($field as $f) $value = $value->{$f};
		}
		else $value = $obj->$field;
		return $value;
	}
	//преобразование модуля в строку для вывода(преобразование объекта в строку) 
	final public function __toString() {
		$this->preRender();
		//обращаеся к шаблонизатору предаем tpl файл, настройки
		//возвращаем а не выводим
		return $this->view->render($this->getTmplFile(), $this->getProperties(), true);
	}
	//пустая реализация
	protected function preRender() {}
	
	//скланения слов связанных с числителями (один человек, два человака)
	final protected function numberOf($number, $suffix) {
		$keys = array(2, 0, 1, 1, 1, 2);
		$mod = $number % 100;
		$suffix_key = ($mod > 7 && $mod < 20) ? 2: $keys[min($mod % 10, 5)];
		return $suffix[$suffix_key];
	}
	
	//получам tpl файл
	abstract public function getTmplFile();
	
}

?>