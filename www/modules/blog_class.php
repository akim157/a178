<?php
/*модуль для разделов будет содеражать в себе статьи и блок навигации*/
class Blog extends Module {
	
	public function __construct() {
		parent::__construct();
		//массив статей
		$this->add("articles", null, true);
		$this->add("more_articles", null, true);
		$this->add("pagination");
		$this->add("marki");
	}
	
	public function preRender() {
		//вызварть склонение слов комментарии
		foreach ($this->articles as $article) {
			$article->count_comments_text = $this->numberOf($article->count_comments, array("комментарий", "комментария", "комментариев"));
		}
	}
	//объявляем tpl
	public function getTmplFile() {
		return "blog";
	}
	
}

?>