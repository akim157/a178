<?php
/*класс объект для курсов*/
class CourseDB extends ObjectDB {
	//объявлем название таблицы
	protected static $table = "courses";
	
	public function __construct() {
		//обращаемся к родительскому конструктору и передаем параметры
		parent::__construct(self::$table);
		//добавляем свойства в объект и валидатор
		$this->add("type", "ValidateCourseType");
		$this->add("header", "ValidateTitle");
		$this->add("sub_header", "ValidateTitle");
		$this->add("img", "ValidateIMG");
		$this->add("link", "ValidateURL");
		$this->add("text", "ValidateText");
		$this->add("did", "ValidateID");
		$this->add("latest", "ValidateBoolean");
		$this->add("section_ids", "ValidateIDs");
	}
	//иницилизирование
	protected function postInit() {
		$this->img = Config::DIR_IMG.$this->img;
		return true;
	}
	//по id раздела будет загружать определенный курс
	public function loadOnSectionID($section_id, $type) {
		/*
			section_id - id разделов где должен выводиться курсы
			type - тип
		*/
		//создаем объект select
		$select = new Select();
		//формируем объект
		$select->from(self::$table, "*")
			->where("`type` = ".self::$db->getSQ(), array($type))
			//берем новинки
			->where("`latest` = ".self::$db->getSQ(), array(1))
			->rand();
			//получаем данные с бд
		$data_1 = self::$db->select($select);
		//создам еще один объект
		$select = new Select();
		//формируем запрос
		$select->from(self::$table, "*")
			->where("`type` = ".self::$db->getSQ(), array($type));
		//проверка есть ли id разделов
		if ($section_id) $select->whereFIS("section_ids", $section_id);
		$select->rand();
		//получаем данные с со второго запроса
		$data_2 = self::$db->select($select);
		//формируем один массив данных
		$data = array_merge($data_1, $data_2);
		//проверка если данные пусты
		if (count($data) == 0) {
			$select = new Select();
			$select->from(self::$table, "*")
				->where("`type` = ".self::$db->getSQ(), array($type))
				->rand();
			$data = self::$db->select($select);
		}
		//создаем многомассивный объект
		$data = ObjectDB::buildMultiple(__CLASS__, $data);
		//сортируем массив, используя функцию для сравнения элементов
		//обращаемся к классу у которого методо compare
		uasort($data, array(__CLASS__, "compare"));
		//извлекаем первый элемент массива
		$first = array_shift($data);
		//загружаем в объект первый элемент передовая параметром id этого элемента
		$this->load($first->id);
	}
	//метод сравнения для сортировки
	private function compare($value_1, $value_2) {
		if ($value_1->latest != $value_2->latest) return $value_1->latest < $value_2->latest;
		if ($value_1->type == $value_2->type) return 0;
		return $value_1->type > $value->type;
	}
	
}

?>