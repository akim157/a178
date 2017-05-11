<?php
/*класс формирует запросы select*/
abstract class AbstractSelect {
	
	//объект abstractdatabase
	private $db;
	//из какой таблицы происходит выборка
	private $from = "";
	//предикат услоивия выборки
	private $where = "";
	//сортировка
	private $order = "";
	//лимит количества извлекающих записей
	private $limit = "";
	
	private $join = "";
	
	/*конструктор принимает объект database*/
	public function __construct($db){
		//иницилизируем его
		$this->db = $db;		
	}
	
	/*метод записывает в объект select from*/
	public function from($table_name, $fields){
		/*
			table_name - название таблицы
			fields - название полей таблицы
		*/
		
		//получаем название таблицы с перфиксом с помощью метода из объекта db(AbstractDatabase)
		$table_name = $this->db->getTableName($table_name);
		/*формируем from
		прииер: select (`hnre`, `hren`) From hren*/
		//создаем пустую переменную
		$from = "";
		//проверка если поле равно * то соотвественно переменной присваем тоже значение
		if($fields == "*") $from = "*";
		else {
			for($i = 0; $i < count($fields); $i++){
				//провека является ли извлекаемое поле функцией count(id)
				//если есть скобка то это функция
				if(($pos_1 = strpos($fields[$i], "(")) !== false){
					//ищем закрывающию скобку
					$pos_2 = strpos($fields[$i], ")");
					//формируем from
					//пример count(id) в count(`id`)
					$from .= substr($fields[$i], 0, $pos_1)."(`".substr($fields[$i], $pos_1 + 1, $pos_2 - $pos_1 - 1)."`),";					
				}
				//если не функция то просто перечислям через запятую
				//else $from .= "`".$fields[$i]."`,";
				else $from .= $fields[$i].",";
			}
			//удаляю последнюю запятую
			$from = substr($from, 0, -1);
		}
		//к сформированому from добавляем название таблицы
		$from .= " FROM `$table_name`";
		//после формирование записываем в поле значение from
		$this->from = $from;
		//возвращаем объект AbstractSelect, текущий объект
		return $this;
	}
	
	/*метод, который добавляет придикат*/
	public function where($where, $values = array(), $and = true){
		/*
			where - само условие (`link` = ?)
			values - значение
			and - дополнительное условие если true, то and иначе or
		*/		
		//проверка на передачу запроса
		if($where){
			//обращаемся к классу AbstractDatabase и заменяем ? на значение для безопасности данных
			$where = $this->db->getQuery($where, $values);			
			//формируем where
			$this->addWhere($where, $and);
		}
		return $this;	
	}
	
	/*`id` IN (5,3,7) перечисление значений*/
	public function whereIn($field, $values, $and = true){
		/*
			field - поле в таблице
			values - его значение
			and - and или or
		*/
		
		/*формируем where*/
		$where = "`$field` IN (";
		foreach($values as $value){
			//ставим знак вопроса
			$where .= $this->db->getSQ().",";
		}
		//удаляем послдений символ запятую
		$where = substr($where, 0, -1);
		//закрываем условие скобкой
		$where .= ")";
		//вызываем метод where результат возвращаем
		return $this->where($where, $values, $and);
	}
	//FIND_IN_SET sql используется для поиска подстроки среди списка строк
	//пример: ищем из 5,6,7 - 7
	public function whereFIS($col_name, $value, $and = true) {
		$where = "FIND_IN_SET (".$this->db->getSQ().", `$col_name`) > 0";
		return $this->where($where, array($value), $and);
	}
	
	/*метод для сортировки*/
	public function order($field, $ask = true){
		/*
			field - название полей
			ask - сортировка по возрастанию asc, desc по убыванию
		*/
		
		//если field является массивом
		if(is_array($field)){
			//формируем сортировку
			$this->order = "ORDER BY ";
			//проверка является ask массивом
			if(!is_array($ask)){
				//создаем пустой массив
				$temp = array();
				//присваем ему одинаковые значения
				for($i = 0; $i < count($field); $i++) $temp[] = $ask;
				//пересохраняем переменную
				$ask = $temp;
			}
			//перебираем поля для сортировка
			for($i = 0; $i < count($field); $i++){
				//выводим поле 
				$this->order .= "`".$field[$i]."`";
				//проверка на сортировку
				if(!$ask[$i]) $this->order .= " DESC,";
				else $this->order .= ",";
			}
			//убираем последнию запятую
			$this->order = substr($this->order, 0, -1);
		}
		else {
			//если одно поле
			$this->order = "ORDER BY `$field`";
			//проверка если false
			if(!$ask) $this->order .= " DESC";
		}
		//возвращаем объект
		return $this;
	}
	public function join($id = 0){
		if($id < 1) return false;
		$join = 'a left join `myit_parts_types` p on a.id_part = p.id left join `myit_marka` m on p.id_marka = m.id where a.id_part = '.$id;
		$this->join = $join;
		return $this;
	}
	/*метод отвечающий за количества записей*/
	public function limit($count, $offset = 0){
		/*
			count - количество записей
			offset - отвечающий за смещение
		*/
		
		//преобразуем параметр в число, чтобы не было проблем, чтобы не было sql инъекции
		$count = (int) $count;
		$offset = (int) $offset;
		//проверка чтобы параметры были только положительными
		if($count < 0 || $offset < 0) return false;
		//формируем поле
		$this->limit = " LIMIT $offset, $count";
		//возвращаем объект
		return $this;
	}
	
	/*извлекать случайные записи*/
	public function rand(){
		$this->order = "ORDER BY RAND()";
		return $this;
	}
	
	/*преобразования объекта в строку для вывода*/
	public function __toString(){
		//если поле from true формируем запрос select
		if($this->from) $ret = "SELECT ".$this->from." ".$this->join." ".$this->where." ".$this->order." ".$this->limit;
		else $ret = "";
		//возвращаем сфорсмированный запрос select в виде строки для объекта
		return $ret;
	}
	
	/*метод формирует where в окончательный вид*/
	private function addWhere($where, $and){
		/*
			where - условие
			and - если true, то and иначе or
		*/
		
		//проверка если уже придекат в полях
		if($this->where){
			//проверка на and или or
			if($and) $this->where .= " AND ";
			else $this->where .= " OR ";
			$this->where .= $where;
		}
		else $this->where = "WHERE $where";
	}
}
?>