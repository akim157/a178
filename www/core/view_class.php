<?php
/*класс шабланизатор*/
class View {
	//директория где храняться tpl файлы
	private $dir_tmpl;
	
	public function __construct($dir_tmpl) {
		//в параметрах храниться путь к tpl файлам
		$this->dir_tmpl = $dir_tmpl;
	}
	
	//
	public function render($file, $params, $return = false) {
		/*
			file - название tpl файла
			params - параметры, которые нужно подставить в файл
			return - нужно ли результаты метода возвращать или выводить на экран
		*/
		
		//формируем путь к файлу пример: tmpl/hren.tpl
		$template = $this->dir_tmpl.$file.".tpl";
		//Импортировать переменные из массива в текущую символьную таблицу.
		//извлекает массив и превращает их в переменные
		extract($params);
		//собираем а буффер, но пока не выводим содержимое файла
		ob_start();
		//подставляем данный файл
		include($template);
		//если требуется вернуть мы его возвращаем из буффера и очищаем его
		if ($return) return ob_get_clean();
		//если нет то выводим также и очищаем
		else echo ob_get_clean();
	}
}

?>