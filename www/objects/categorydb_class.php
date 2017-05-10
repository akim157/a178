<?php
/*класс для создания объекта для категорий, разделов сайта*/
class CategoryDB extends ObjectDB {
	/*объявляем название таблицы в бд*/
	protected static $table = "categories";
	
	public function __construct() {
		//передаем название таблицы родительскомц конструктору
		parent::__construct(self::$table);
		//добавляем свойства в объект (название стобца в бд, и валидатор для проверки)
		$this->add("title", "ValidateTitle");
		$this->add("img", "ValidateIMG");
		$this->add("section_id", "ValidateID");
		$this->add("description", "ValidateText");
		$this->add("meta_desc", "ValidateMD");
		$this->add("meta_key", "ValidateMK");
	}
		//проводим иницилизацию
	protected function postInit() {
		//добавляем полный путь картинки
		if (!is_null($this->img)) $this->img = Config::DIR_IMG_ARTICLES.$this->img;
		//формируем ссылку
		$this->link = URL::get("category", "", array("id" => $this->id));
		//создаем объект класса SectionDB
		//пример: $category_db->section->title - название категории, который принадлежит данный раздел
		$section = new SectionDB();
		/*загрузка объекта по id*/
		$section->load($this->section_id);
		$this->section = $section;
		return true;
	}
	//перед валидацие нужно полный путь убрать и оставить как в базе название картинки		
	protected function preValidate() {
		if (!is_null($this->img)) $this->img = basename($this->img);
		return true;
	}
	
}

?>