<?php
/*класс file служит для файлов которые прислали пользователи через форму*/	
class File {
	//метод загрузки файла на сервер
	public static function uploadIMG($file, $max_size, $dir, $root = false, $source_name = false) {
		/*
			file - файл
			max_size - максимальный размер файла
			dir - путь к директории для сохранения файла
			root - 
			source_name - 
		*/
		//создаем массив для типов(расширения) файлов, котрые не являются изображениями для проверки
		$blacklist = array(".php", ".phtml", ".php3", ".php4", ".html", ".htm");
		//проводим итерацию для проверки расширения загружаемого файла
		foreach ($blacklist as $item)
			//проверяем является ли расширения файла допустимым
			if (preg_match("/$item\$/i", $file["name"])) throw new Exception("ERROR_AVATAR_TYPE");
		//присваем к переменным характеристики файла	
		$type = $file["type"];
		$size = $file["size"];
		//если тип файла не являтся из перечисленных также выводим исключение
		if (($type != "image/jpg") && ($type != "image/jpeg") && ($type != "image/gif") && ($type != "image/png")) throw new Exception("ERROR_AVATAR_TYPE");
		//проверак на размер файла
		if ($size > $max_size) throw new Exception("ERROR_AVATAR_SIZE");
		//если source_name указан то мы берем это имя
		if ($source_name) $avatar_name = $file["name"];
		//если нет генерируем свое имя
		else $avatar_name = self::getName().".".substr($type, strlen("image/"));
		//получаем полный путь к картинке
		$upload_file = $dir.$avatar_name;
		//если это не корневая директория то мы полчаем корнеавую дирпекторию
		if (!$root) $upload_file = $_SERVER["DOCUMENT_ROOT"].$upload_file;
		//загружаем на сервер
		if (!move_uploaded_file($file["tmp_name"], $upload_file)) throw new Exception("UNKNOWN_ERROR");
		return $avatar_name;
	}
	//возвращает случайны ключ
	public static function getName() {
		return uniqid();
	}
	//удаление файла
	public static function delete($file, $root = false) {
		/*
			file - файл
			root - корневая директория
		*/
		//если false то получаем корневую директорию
		if (!$root) $file = $_SERVER["DOCUMENT_ROOT"].$file;
		//смотрим если файл есть то мы его удаляем
		if (file_exists($file)) unlink($file);
	}
	/*проверка на наличии файла на сервере*/
	public static function isExists($file, $root = false) {
		if (!$root) $file = $_SERVER["DOCUMENT_ROOT"].$file;
		return file_exists($file);
	}
}

?>