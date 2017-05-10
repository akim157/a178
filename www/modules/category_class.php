<?php
/*Модуль для категорий*/
class Category extends Module {
	
	public function __construct() {
		parent::__construct();
		$this->add("category");
		$this->add("articles", null, true);
		$this->add("pagination");
	}
	public function preRender() {
		//вызварть склонение слов комментарии
		foreach ($this->articles as $article) {
			$article->count_comments_text = $this->numberOf($article->count_comments, array("комментарий", "комментария", "комментариев"));
		}
	}
	public function getTmplFile() {
		return "category";
	}
	
}

?>