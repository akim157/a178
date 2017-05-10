<?php

/*класс отвечающий за работу объектов в БД*/
abstract class AbstractObjectDB {
	
	/*константы для проверки данных и их преобразований*/
	//дата
	const TYPE_TIMESTAMP = 1;
	//ip
	const TYPE_IP = 2;
	
	//масси со значениями двух констант для удобства
	private static $types = array(self::TYPE_TIMESTAMP, self::TYPE_IP);
	//объект БД
	protected static $db = null;
	
	//строка отвечающий за формат даты
	private $format_date = "";
	
	//id данной записи из таблицв бд в объекте
	private $id = null;
	//свойства объекта (id, title), которые будут помещаться в данный массив
	private $properties = array();
	
	//имя таблицы, на один объект одна таблица
	protected $table_name = "";
	
	public function __construct($table_name, $format_date){
		/*
			table_name - название таблицы
			format_date - формат даты
		*/
		
		//присваеваем к полям значения параметров
		$this->table_name = $table_name;
		$this->format_date = $format_date;
	}
	
	/*метод присваеват объект БД к статистическому полю*/
	public static function setDB($db){
		self::$db = $db;
	}
	
	/*загрузка объекта по id*/
	public function load($id){
		//переводим id в целый тип для безопасности
		$id = (int) $id;
		//проверка на то, чтобы id не был отрицательным
		if($id < 0) return false;
		//создаем объект запроса select
		$select = new Select(self::$db);
		/*запись по id, строим запрос*/
		//обращаемся к классу AbstractSelect к его методу from
		//параметрами передается имя таблицы и поля
		$select = $select->from($this->table_name, $this->getSelectFields())
			->where("`id` = ".self::$db->getSQ(), array($id));
		//второй метод where того же класса, где передаются также два параметра
		//id = ?, а вторым значения, это для безопасности sql инъекций
		//обращаемся к объекту AbstractDataBase к его методу, который вытаскивает из БД строку
		//параметр метода идет запрос, который мы построили выше
		$row = self::$db->selectRow($select);
		//проверка есть ли данные
		if(!$row) return false;
		//начинаем иницилизацию
		if($this->init($row)) return $this->postLoad();
	}
	
	/*метод init, массив подставляет в свойства конечного объекта
	т.е чтобы мы могли к данным объекта обращаться таким образом: 
	$article->id - это пример*/
	public function init($row){
		/*
			row - объект с БД
		*/
		
		//properties поля (id, title) в таблице
		//key название поля
		foreach($this->properties as $key => $value){
			//значение ячейки в таблице БД
			$val = $row[$key];
			//проверям тип содержимового
			switch ($value["type"]){
				//Тип совподает с датой
				case self::TYPE_TIMESTAMP:
					//преобразовываем с 1231232 в дату
					//если значение val не null, то преобразуем
					if(!is_null($val)) $val = strftime($this->format_date, $val);
					break;
				//Тип совпадает с ip
				case self::TYPE_IP:
					//преобразовываем с 12341241 в ip
					//если занчение не null, то преобразуем
					if(!is_null($val)) $val = long2ip($val);
					break;
			}
			//key название поля в таблице, val его значение
			$this->properties[$key]["value"] = $val;	
		}
		//сохраняем id данной записи
		$this->id = $row["id"];	
		//возвращаем обработчик события postInit()
		return $this->postInit();
	}
	
	/*Проверят сохранянен ли данный объект*/
	public function isSaved(){
		//если в объекте id больше значит он уже существует, его можно обнавить или удалить		
		return $this->getID() > 0;
	}
	
	/*вытаскивает id из объекта для проверки*/
	public function getID(){
		//просто возвращаем id объекта
		return (int) $this->id;
	}
	
	/*сохранить в бд изменения*/
	public function save(){			
		//нужно узнать, что мы будем делать либо добавлять или изменять запись
		//проверям сохранена ли она,есть ли в объекте id
		$update = $this->isSaved();		
		//проверяем если есть id то update(обнавление) иначе добавление(insert)
		if($update) $commit = $this->preUpdate();
		else $commit = $this->preInsert();
		//проверяем если данные false соотвественно возвращаем false
		if(!$commit) return false;
		//перебираем поля таблицы
		foreach($this->properties as $key => $value){
			//проверка
			//преобразовываем данные пример: 12.12.12 то нужно получить 123412
			switch($value["type"]){
				case self::TYPE_TIMESTAMP:
					//проверяем если не null то преобразовываем дату
					if(!is_null($value["value"])) $value["value"] = strtotime($value["value"]);
					break;
				case self::TYPE_IP:
					//перобразуем ip
					if(!is_null($value["value"])) $value["value"] = ip2long($value["value"]);
					break;
			}
			//формируем массив из полученных данных
			$row[$key] = $value["value"];
		}
		//если массив имеет элементы
		if(count($row) > 0) {
			//если обнавление в бд
			if($update) {			
				//обращаемся к объекту AbstractDataBase и его методу update
				//параметерами идут имя таблицы, массив с названием полей и их занчениеями, условие с ?, для безопасности и само значение за место занкак вопроса				
				$success = self::$db->update($this->table_name, $row, "`id` = ".self::$db->getSQ(), array($this->getID()));				
				//если вернулся false выкидываем исключение ошибку
				if(!$success) throw new Exception();
			} 
			//если это добавление записи			
			else {
				//обращаемся к объекту AbstractDataBase, методу insert
				//параметрами идет имя таблицы и массив с названиями таблиц и их значениями
				$this->id = self::$db->insert($this->table_name, $row);
				//если вернулась false выкидываем исключение
				if (!$this->id) throw new Exception();
			}			
		}
		//если обнавления 
			if($update) return $this->postUpdate();
			//если добавление
			return $this->postInsert();
	}
	
	/*метод для удаление*/
	public function delete(){
		//если объект сохранен удалять нечего
		if(!$this->isSaved()) return false;
		//еще проверка перед удалением
		if(!$this->preDelete()) return false;
		//обращаемся к объекту класса AbstractDataBase к его методу delete
		//параметрами идет имя таблицы, условие удаление с ? и значение за место ? для безопасности
		$success = self::$db->delete($this->table_name, "`id` = ".self::$db->getSQ(), array($this->getID()));
		//проверка если вернулся false то выкидываем исключение
		if (!$success) throw new Exception();
		//после удаление обнуляем id в объекте
		$this->id = null;
		//возвращаем метод, что должно происхоодить после удаление записи из таблицы
		return $this->postDelete();
	}
	
	/*$article->title = "asdfasdf" - вызывается этот метод, для записи объект данных*/
	public function __set($name, $value){
		/*
			name - название поле, которое будет изменено
			value - значение на которое будет изменено
		*/
		
		//проверка есть ли ключ в массиве
		//name - ключ, properties - массив
		if (array_key_exists($name, $this->properties)) {
			//если есть то записываем значение
			$this->properties[$name]["value"] = $value;
			//возвращаем true
			return true;
		}
		//новое свойство добавляем в объект
		else $this->$name = $value;
	}
	
	/*получение поля объекта*/
	public function __get($name) {
		/*name - название поля, которое хотим получить*/
		//если это id то возвращаем id объекта
		if ($name == "id") return $this->getID();
		//возвращаем значение поля если оно есть или null если его нету
		return array_key_exists($name, $this->properties)? $this->properties[$name]["value"]: null;
	}
	
	/*создаем массив объектов, чтобы был не одна запись из бд*/
	public static function buildMultiple($class, $data) {
		/*
			class - класс объекта
			data - набор данных
		*/
		
		//создаем пустой массив
		$ret = array();
		
		//проверка на существованния класса, если класс не существует то выбрасываем исключение
		if (!class_exists($class)) throw new Exception();
		
		//создаем объект класса
		$test_obj = new $class();
		//проверяем если созданный объект не является класса AbstractObjectDB опятьже исключение
		if (!$test_obj instanceof AbstractObjectDB) throw new Exception();
		//создаем интерацию для перечисление данных
		foreach ($data as $row) {
			//снова объявляем объект
			$obj = new $class();
			//формируем объект в нужный формат, иницилизируем
			$obj->init($row);
			//создаем массив с ключем в виде id объекта, где сохраняем объект сам целиком
			$ret[$obj->getID()] = $obj;
		}
		return $ret;
	}
	
	/*доступ ко всем записям из одной таблице*/
	public static function getAll($count = false, $offset = false) {
		/*
			count - количества записей
			offset - 
		*/
		
		//вызываем имя класса из которого был вызван данный метод
		$class = get_called_class();
		//обращаемся к методу
		//первый параметр имя таблицы, класс из которого был вызван метод, сортировка по id по умолчанию, по возврастанию, а дальше парраметры.		
		return self::getAllWithOrder($class::$table, $class, "id", true, $count, $offset);
	}
	
	/*получить количество записей в таблице*/
	public static function getCount() {
		//узнаем класс от куда был вызван данный метод
		$class = get_called_class();
		//дальше передаем данные методу
		//имя таблицы полученная из из класса который вызвал данный метод, дальше булевые настройки для сортировки		
		return self::getCountOnWhere($class::$table, false, false);
	}
	/*получить колчество записей по секции*/
	public static function getCountSection($section_id){		
		$class = get_called_class();			
		return self::getCountOnSectionWhere($class::$table, $section_id);
	}
	protected static function getCountOnSectionWhere($table_name, $section_id){
		$select = new Select();
		$select->from($table_name, array("COUNT(id)"))
			->where("`section_id` = ".self::$db->getSQ(), array($section_id));		
		return self::$db->selectCell($select);
	}
	/*получаем все поля*/
	public static function getAllOnField($table_name, $class, $field, $value, $order = false, $ask = true, $count = false, $offset = false) {
		/*имя таблицы, класс, поля таблицы, значения, сортировка, сортировка по ...*/
		return self::getAllOnWhere($table_name, $class, "`$field` = ".self::$db->getSQ(), array($value), $order, $ask, $count, $offset);
	}
	
	protected static function getCountOnField($table_name, $field, $value) {
		return self::getCountOnWhere($table_name, "`$field` = ".self::$db->getSQ(), array($value));
	}
	
	/*метод формирует запрос для полчения количества записей из таблицы*/
	protected static function getCountOnWhere($table_name, $where = false, $values = false) {
		/*
			table_name - название таблицы
			where - условия для запроса
			values - значение для условия
		*/
		
		//создаем объект select для формирования запроса
		$select = new Select();
		//обращаемся к методу from из класса AbstractSelect
		//передаем параметры первый название таблицы, где будут вестись подсчеты и условия для формирования запроса
		$select->from($table_name, array("COUNT(id)"));
		//проверяем есть ли дополнительные условия, если есть до их тоже добовляем в формирования запроса		
		if ($where) $select->where($where, $values);		
		//обращаемся к классу AbstractDataBase, чтобы с помощью сформированного запроса получить одну ячейку
		return self::$db->selectCell($select);
	}
	
	/*метод для формирования запроса для всех записей из таблицы*/
	protected static function getAllWithOrder($table_name, $class, $order = false, $ask = true, $count = false, $offset = false) {
		return self::getAllOnWhere($table_name, $class, false, false, $order, $ask, $count, $offset);
	}
	
	/*метод для формирования запроса для полчения всех полей*/
	protected static function getAllOnWhere($table_name, $class, $where = false, $values = false, $order = false, $ask = true, $count = false, $offset = false) {
		//объявлем объект select класса AbstractSelest
		$select = new Select();
		//формируем запрос по всем полям таблицы БД
		$select->from($table_name, "*");
		//если есть условия добавляем их
		if ($where) $select->where($where, $values);
		//если есть сортировка то также добавляем в запрос
		if ($order) $select->order($order, $ask);
		//если нет то сортируем по умолчанию по id
		else $select->order("id");
		//если есть limit тоже добавляем 
		if ($count) $select->limit($count, $offset);
		//обращаемся к классу AbstractSelect методу select для получения всех записей из таблицы
		$data = self::$db->select($select);
		//обращаемся к методу чтобы создать массив объекта из записей
		return AbstractObjectDB::buildMultiple($class, $data);
	}
	
	/*метод для добавления под объек для создания много уровневого объектв
	служит это для категорий ПРиме: $category->section_id echo $catedoty->section->title*/
	protected static function addSubObject($data, $class, $field_out, $field_in) {
		/*
			data - массив данных
			class - класс
			field_out - выходное поле (section)
			field_in - входное поле (section_id)
		*/
		
		//создаем массив для нормирования массива для всех id
		$ids = array();
		
		//создаем итерацию данных
		foreach ($data as $value) {
			//обращаемся методу который решает сложные проблемы user->section_id
			$ids[] = self::getComplexValue($value, $field_in);
		}
		//если id нету возвращаем пустой массив
		if (count($ids) == 0) return array();
		//получаем все оббъекты по данному id
		$new_data = $class::getAllOnIDs($ids);
		//если вернулся 0 просто возвращаем данные пустой массив практически
		if (count($new_data) == 0) return $data;
		
		//если данные начинаем преобразования
		foreach ($data as $id => $value) {
			if (isset($new_data[self::getComplexValue($value, $field_in)])) $data[$id]->$field_out = $new_data[self::getComplexValue($value, $field_in)];
			else $value->$field_out = null;
		}
		return $data;
	}
	
	/*метод решает сложные вопросы запрос к объекту длинный пример: user->section_id*/
	protected static function getComplexValue($obj, $field) {
		//проверка если поле имеет сложную конструкцию -> то мы ее разбиваем на массив
		if (strpos($field, "->") !== false) $field = explode("->", $field);
		//если это массив
		if (is_array($field)) {			
			$value = $obj;
			//добираемся до самого значение
			foreach ($field as $f) $value = $value->{$f};
		}
		//если не массив
		else $value = $obj->$field;
		return $value;
	}
	
	/*метод который ищет объекты по id*/
	public static function getAllOnIDs($ids) {
		//получить все записи по полую id с параматрами $ids
		return self::getAllOnIDsField($ids, "id");
	}
	
	/*получает все данные по данному полую в параметрах*/
	public static function getAllOnIDsField($ids, $field) {
		//узнаем класс от куда был вызван данный метод
		$class = get_called_class();
		//создаем объект select из класса AbstractSelect
		$select = new Select();
		//обращаемся уже к методу from данного класаса и получаем все записи по перечисленным id
		$select->from($class::$table, "*")
			->whereIn($field, $ids);
		//обращаемся к AbstractDataBase с сформированным запросом для получения данных из БД	
		$data = self::$db->select($select);
		//создаем массив объекта из записей полученных с БД
		return AbstractObjectDB::buildMultiple($class, $data);
	}
	
	/*загрузить по полю с каким либо значениями
	например: загрузить пользователя с таким-то login*/
	protected function loadOnField($field, $value) {
		/*
			field - имя поля
			value - его значение
		*/
		
		//объявлем объект select из класса AbstractSelect
		$select = new Select();
		//формирум запрос
		$select->from($this->table_name, "*")
			->where ("`$field` = ".self::$db->getSQ(), array($value));
		//обращаемся к AbstractDataBase	
		$row = self::$db->selectRow($select);
		if ($row) {
			if ($this->init($row)) return $this->postLoad();
		}
		return false;
	}
	
	/*добавляет новое свойство в объект*/
	protected function add($field, $validator, $type = null, $default = null) {
		/*
			field - название поля
			validator - валидатор для проверки данных
			type - TYPE_TIMESTAMP или TYPE_IP
			default - значение по умолчанию
		*/
		//сохраняем занчение 
		$this->properties[$field] = array("value" => $default, "validator" => $validator, "type" => in_array($type, self::$types)? $type : null);
	}
	
	/*методы отвечающие за обработку событий*/
	//отвечает за проверки данных
	protected function preInsert() {
		return $this->validate();
	}
	
	protected function postInsert() {
		return true;
	}
	
	protected function preUpdate() {
		return $this->validate();
	}
	
	protected function postUpdate() {
		return true;
	}
	
	protected function preDelete() {
		return true;
	}
	
	protected function postDelete() {
		return true;
	}
	
	protected function postInit() {
		return true;
	}
	
	protected function preValidate() {
		return true;
	}
	
	protected function postValidate() {
		return true;
	}
	
	protected function postLoad() {
		return true;
	}
	
	/*метод для получения даты*/
	public function getDate($date = false) {
		//если дата не передана то берем данное время
		if (!$date) $date = time();
		//возвращаем уже преобразованное время под наш формат
		return strftime($this->format_date, $date);
	}
	
	/*номер дня*/
	protected static function getDay($date = false) {
		//преобразуем дату в строку
		$date = strtotime($date);
		//возвращаем день
		return date("d", $date);
	}
	//поисковый метод
	protected static function searchObjects($select, $class, $fields, $words, $min_len) {
		$words = mb_strtolower($words);
		$words = preg_replace("/ {2,}/", " ", $words);
		if ($words == "") return array();
		$array_words = explode(" ", $words);
		$temp = array();
		foreach ($array_words as $value) {
			if (strlen($value) >= $min_len) $temp[] = $value;
		}
		$array_words = $temp;
		if (count($array_words) == 0) return array();
		foreach ($array_words as $value) {
			$where = "";
			$params = array();
			for ($i = 0; $i < count($fields); $i++) {
				$where .= "`".$fields[$i]."` LIKE ".self::$db->getSQ();
				$params[] = "%$value%";
				if (($i + 1) != count($fields)) $where .= "OR";
			}
			$select->where("($where)", $params, true);
		}
		$results = self::$db->select($select);
		if (!$results) return array();
		$results = ObjectDB::buildMultiple($class, $results);
		//сортируем по реаливатности поиска
		foreach ($results as $result) {
			for ($j = 0; $j < count($fields); $j++) {
				$result->$fields[$j] = mb_strtolower(strip_tags($result->$fields[$j]));
			}
			$data[$result->id] = $result;
			$data[$result->id]->relevant = self::getRelevantForSearch($result, $fields, $array_words);
		}
		uasort($data, array("AbstractObjectDB", "compareRelevant"));
		return $data;
	}
	
	private static function getRelevantForSearch($result, $fields, $array_words) {
		$relevant = 0;
		for ($i = 0; $i < count($fields); $i++)
			for ($j = 0; $j < count($array_words); $j++)
				$relevant += substr_count($result->$fields[$i], $array_words[$j]);
		return $relevant;
	}
	
	private static function compareRelevant($value_1, $value_2) {
		return $value_1->relevant < $value_2->relevant;
	}
	
	//метод для получения ip
	protected function getIP() {
		return $_SERVER["REMOTE_ADDR"];
	}
	
	//преобразовывает сроку в хеш
	protected static function hash($str, $secret = "") {
		//соединям строку с секретным словом и хешируем это все возвращаем
		return md5($str.$secret);
	}
	//получение уникального id
	protected function getKey() {
		return uniqid();
	}
	
	//получение поля выборки
	private function getSelectFields() {
		//присваеваем ключи
		$fields = array_keys($this->properties);
		//добавляем в массив еще одно значение
		array_push($fields, "id");
		//все это возвращаем
		return $fields;
	}
	
	//проверка данных, перед обнавлениями записей и добавления новых 
	private function validate() {
		//если метод вернет false выбрасываем исключение
		if (!$this->preValidate()) throw new Exception();
		//создаем массив с будущими проверками
		$v = array();
		//создаем еще один массив для ошибок
		$errors = array();
		//перебираем свойства
		foreach ($this->properties as $key => $value) {
			//записываем объект валидатора с параметрам, который передается в коснтруктор само value
			//new ValidateName("maxim")	
			$v[$key] = new $value["validator"]($value["value"]);
		}
		//ищем ошибки при создании конструктора он ищет сразу ошибки
		foreach ($v as $key => $validator) {
			//если ошибки есть то 
			if (!$validator->isValid()) $errors[$key] = $validator->getErrors();
		}
		//проверка есть ли ошибки
		if (count($errors) == 0) {
			if (!$this->postValidate()) throw new Exception();
			return true;
		}
		else throw new ValidatorException($errors);
	}
}
?>