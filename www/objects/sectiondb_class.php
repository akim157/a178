<?php
/*класс служит для создания объекта для секций разделов*/
class SectionDB extends ObjectDB {
	//объявлем название таблицы
	protected static $table = "sections";
	
	public function __construct() {
		//обращаемся к родителю ObjectDB и передаем ему название таблицы
		parent::__construct(self::$table);
		//добавляем название полей таблицы и валидаторы для проверки
		$this->add("title", "ValidateTitle");
		$this->add("img", "ValidateIMG");
		$this->add("description", "ValidateText");
		$this->add("meta_desc", "ValidateMD");
		$this->add("meta_key", "ValidateMK");
	}
	//проводим иницилизацию
	protected function postInit() {
		if (!is_null($this->img)) $this->img = Config::DIR_IMG_ARTICLES.$this->img;
		$this->link = URL::get("section", "", array("id" => $this->id));
		return true;
	}
	//перед валидацие нужно полный путь убрать и оставить как в базе название картинки
	protected function preValidate() {
		if (!is_null($this->img)) $this->img = basename($this->img);
		return true;
	}
	//измение api поля title
	public function accessEdit($auth_user, $field) {
		if ($field == "title") return false;
		return false;
	}
	
	public function accessDelete($auth_user) {
		return false;
	}
	
}

?>