<?php
/*модуль для главного меню*/
class MainMenu extends Module {
	
	public function __construct() {
		parent::__construct();
		//активный uri
		$this->add("uri");
		//элементы меню
		$this->add("items", null, true);
	}
	//реализация
	public function preRender() {
		//добавляем подменю
		$this->add("childrens", null, true);
		//активные элементы подменю
		$this->add("active", null, true);
		$childrens = array();
		foreach ($this->items as $item) {
			//если родительский id
			if ($item->parent_id) {
				$childrens[$item->id] = $item->parent_id;
			}
		}
		//добавляем массив в свойства
		$this->childrens = $childrens;
		//получаем активный элемент
		$active = array();
		foreach ($this->items as $item) {
			if ($item->link == $this->uri) {
				$active[] = $item->id;
				if ($item->parent_id) {
					$parent_id = $item->parent_id;
					$active[] = $parent_id;
					//10 уровневое меню все элелменты выделить
					while ($parent_id) {
						$parent_id = $this->items[$parent_id]->parent_id;
						if ($parent_id) $active[] = $parent_id;
					}
				}
			}
		}
		$this->active = $active;
	}
	//объявляем tpl файл отвечающий за меню
	public function getTmplFile() {
		return "mainmenu";
	}
	
}

?>