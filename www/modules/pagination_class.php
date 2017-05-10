<?php
/*модуль для пагинации*/
class Pagination extends Module {
	
	public function __construct() {
		parent::__construct();
		//index.html
		$this->add("url");
		//шаблон для url стрниц пример: hren.html?page=
		$this->add("url_page");
		//общее количество элементов
		$this->add("count_elements");
		//количество элементов на странице
		$this->add("count_on_page");
		//количество выводимых страниц
		$this->add("count_show_pages");
		//активный url, текущая страница пример: index.html?page=
		$this->add("active");
	}
	//tpl файл
	public function getTmplFile() {
		return "pagination";
	}
	
}

?>