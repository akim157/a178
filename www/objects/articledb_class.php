<?php
/*класс служит для работы с объектом статьи и является дочерним классом ObjectDB и AbstractObjectDB*/
class ArticleDB extends ObjectDB {
	/*первым делом указываем название таблицы*/
	protected static $table = "articles";
	
	public function __construct() {
		/*
			обращаемся к родительскому классу ObjectDB и передаем параметр, название таблицы
			а он в свою очередь обращается к ядру AbstractObjectDB где также оращается к конструктуру и передает параметры 
			название таблицы и формат даты из config
		*/
		parent::__construct(self::$table);
		/*
			обращаемся к ядру AbstractObjectDB через родительский класс ObjectDB
			а именно к методу add, который добавляет свойства в объект
			первый параметром идет название таблицы, а вторым валидатор для проверки данных
			необязательным параметрами являются тип и значиеня по умолчанию
		*/
		$this->add("title", "ValidateTitle");
		$this->add("img", "ValidateIMG");
		$this->add("intro", "ValidateText");
		$this->add("full", "ValidateText");
		$this->add("section_id", "ValidateID");
		$this->add("cat_id", "ValidateID");
		/*3 параметром мы указали константу что это дата и дату по умолчанию из AbstractObjectDB*/
		$this->add("date", "ValidateDate", self::TYPE_TIMESTAMP, $this->getDate());
		$this->add("meta_desc", "ValidateMD");
		$this->add("meta_key", "ValidateMK");
		$this->add("number", "ValidateText");
		$this->add("state", "ValidateText");
	}
	//обработка события после инициализации
	protected function postInit() {
		/*чтобы сгенерировать полный путь к картинке а не только одно название как оно храниться в таблице*/
		//проверка если не null в таблице храниться то пересохраняем
		if (!is_null($this->img)) $this->img = Config::DIR_IMG_ARTICLES.$this->img;
		//формирование ссылки
		//первое название действия(метод), вторым контроль (по умолчанию Main)
		//третьи в качестве id параметров передаем id статьи
		//пример: /article?id=5
		$this->link = URL::get("article", "", array("id" => $this->id));
		return true;
	}
	
	protected function postLoad() {
		$this->postHandling();
		return true;
	}
	//метод будет выводить все статьи на сайте
	public static function getAllShow($count = false, $offset = false, $post_handling = false) {
		/*
			count - количество лимита для выборки
			offset - смещение
			post_handling - post обработка, она служит чтобы не загружать сервер и не подгружать все статьи если мы выводим не все
		*/
		//обращаемся к методу чтобы получить сформированный запрос
		$select = self::getBaseSelect();
		//добавляем к нему сортировку по дате
		$select->order("date", false);
		//проверка если параметр true то добавляем limit 
		if ($count) $select->limit($count, $offset);
		//обращаемся к родителю AbstractObjectDB к его сформированному объекту БД
		//и такде обращаемся к классу Select и его методу select с параметрами сформированного запроса 
		//и получаем данные из БД получаем массив данных
		$data = self::$db->select($select);
		//обращаемся к методу для создания из массива данных в массив объектов
		$articles = ObjectDB::buildMultiple(__CLASS__, $data);
		//если параметр true обращаемся к итерации
		if ($post_handling) foreach ($articles as $article) $article->postHandling();
		return $articles;
	}
	//метод для получения статей принадлежащих к определенному разделу
	public static function getAllOnPageAndSectionID($section_id, $count, $offset = false) {
		/*
			section_id - получаем id секции раздела
			count - количество лимита для выборки
			offset - дополнительный параметр для limit
		*/
		//с помощью метода формируем запрос
		$select = self::getBaseSelect();
		//добавляем к нему сорировку по дате
		//условия по id секции
		//и лимит
		$select->order("date", false)
			->where("`section_id` = ".self::$db->getSQ(), array($section_id))
			->limit($count, $offset);
		//получаем данные из бд	
		$data = self::$db->select($select);
		//превращаем массив данных в массив объектов
		$articles = ObjectDB::buildMultiple(__CLASS__, $data);
		foreach ($articles as $article) $article->postHandling();
		return $articles;
	}
	//получаем все статьи из категории
	public static function getAllOnSectionID($section_id, $order = false, $offset = false) {
		return self::getAllOnSectionOrCategory("section_id", $section_id, $order, $offset);
	}
	
	public static function getAllOnCatID($cat_id, $order = false, $offset = false) {
		return self::getAllOnSectionOrCategory("cat_id", $cat_id, $order, $offset);
	}
	//получаем все статьи из категории
	private static function getAllOnSectionOrCategory($field, $value, $order, $offset) {
		$select = self::getBaseSelect();
		$select->where("`$field` = ".self::$db->getSQ(), array($value))
			->order("date", $order);
		$data = self::$db->select($select);
		$articles = ObjectDB::buildMultiple(__CLASS__, $data);
		return $articles;
	}
	//предыдущая статья
	public function loadPrevArticle($article_db) {
		/*article_db - получаем объетк настоящий статьи*/
		//получить соседа
		$select = self::getBaseNeighbourSelect($article_db);
		//условие
		$select->where("`id` < ".self::$db->getSQ(), array($article_db->id))
			->order("date", false);
			//получаем строку
		$row = self::$db->selectRow($select);
		//иницилизируем данные
		return $this->init($row);
	}
	//следующая статья
	public function loadNextArticle($article_db) {
		$select = self::getBaseNeighbourSelect($article_db);
		$select->where("`id` > ".self::$db->getSQ(), array($article_db->id));
		$row = self::$db->selectRow($select);
		return $this->init($row);
	}
	
	public function search($words) {
		$select = self::getBaseSelect();
		$articles = self::searchObjects($select, __CLASS__, array("title", "number"), $words, Config::MIN_SEARCH_LEN);
		foreach ($articles as $article) $article->setSectionAndCategory();
		return $articles;
	}
	//получаем соседнею статью
	private static function getBaseNeighbourSelect($article_db) {
		$select = self::getBaseSelect();
		$select->where("`cat_id` = ".self::$db->getSQ(), array($article_db->cat_id))
			->order("date")
			->limit(1);
		return $select;
	}
	//перед валидацие нужно полный путь убрать и оставить как в базе название картинки
	protected function preValidate() {
		//если картинка не null
		//basename - возвращает последний компонент имени из указанного пути
		if (!is_null($this->img)) $this->img = basename($this->img);
		return true;
	}
	/*метод для получения много статей из бд*/
	private static function getBaseSelect() {
		//создаем объект select и передаем параметром объект БД
		$select = new Select(self::$db);
		//формируем запрос что нужны все данные поулчить
		$select->from(self::$table, "*");
		//возвращае сформированный запрос
		return $select;
	}
	
	private function setSectionAndCategory() {
		//создаем объект
		$section = new SectionDB();
		//загружаем по разделам из текущего объекта articledb в sectiondb
		$section->load($this->section_id);
		//создаем объект 
		$category = new CategoryDB();
		//загружаем категорию
		$category->load($this->cat_id);
		//если разделы существуют в бд то записываем в объект
		if ($section->isSaved()) $this->section = $section;
		if ($category->isSaved()) $this->category = $category;
		
	}
	
	private function postHandling() {
		//подгрузка по категории
		$this->setSectionAndCategory();
		//добавляем количество комментариев
		$this->count_comments = CommentDB::getCountOnArticleID($this->id);
		//день показа размещения
		$this->day_show = ObjectDB::getDay($this->date);
		//месяц размещения
		$this->month_show = ObjectDB::getMonth($this->date);
	}
	
}

?>