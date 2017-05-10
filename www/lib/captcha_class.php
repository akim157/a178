<?php
/*класс генерирующий капчу*/	
class Captcha {
	/*Пишем константы характеристики*/
	//ширина картинки
	const WIDTH = 100;
	//высота
	const HEIGHT = 60;
	//размер шрифта
	const FONT_SIZE = 16;
	//количество символов
	const LET_AMOUNT = 4;
	//шум в капче
	const BG_LET_AMOUNT = 30;
	//путь к шрифту
	const FONT = "fonts/verdana.ttf";
	
	//массив букв вывода капчи
	private static $letters = array("a", "b", "c", "d", "e", "f", "g");
	//массив с различными цветами, составляющие цвета (0 - 255)
	private static $colors = array(90, 110, 130, 150, 170, 190, 210);
	
	//метод выводит капчу
	public static function generate() {
		//проверит начата ли сессия
		if (!session_id()) session_start();
		//Создание нового полноцветного изображения
		$src = imagecreatetruecolor(self::WIDTH, self::HEIGHT);
		//выделение цвета для изображения. Цвет фона белый
		$bg = imagecolorallocate($src, 255, 255, 255);
		//Производит заливку, начиная с заданных координат
		imagefill($src, 0, 0, $bg);
		
		//создаем шум
		for ($i = 0; $i < self::BG_LET_AMOUNT; $i++) {
			//работает аналогично функции imagecolorallocate(), но еще добавляет к цвету параметр alpha, отвечающий за прозрачность.
			$color = imagecolorallocatealpha($src, rand(0, 255), rand(0, 255), rand(0, 255), 100);
			//буква случайная
			$letter = self::$letters[rand(0, count(self::$letters) - 1)];
			//размер буквы случайный
			$size = rand(self::FONT_SIZE - 2, self::FONT_SIZE + 2);
			//выводим буквы, записывает текст на изображение с использованием шрифтов TrueType.
			imagettftext($src, $size, rand(0, 45), rand(self::WIDTH * 0.1, self::WIDTH * 0.9), rand(self::HEIGHT * 0.1, self::HEIGHT * 0.9), $color, self::FONT, $letter);
		}
		$code = "";
		//создаем буквы капчи
		for ($i = 0; $i < self::LET_AMOUNT; $i++) {
			//работает аналогично функции imagecolorallocate(), но еще добавляет к цвету параметр alpha, отвечающий за прозрачность.
			$color = imagecolorallocatealpha($src, self::$colors[rand(0, count(self::$colors) - 1)],
				self::$colors[rand(0, count(self::$colors) - 1)],
				self::$colors[rand(0, count(self::$colors) - 1)], rand(20, 40));
			//буква случайная	
			$letter = self::$letters[rand(0, count(self::$letters) - 1)];
			//размер буквы случайный
			$size = rand(self::FONT_SIZE * 2 - 2, self::FONT_SIZE * 2 + 2);
			//координата x
			$x = ($i + 1) * self::FONT_SIZE + rand(1, 5);
			//координата y
			$y = ((self::HEIGHT * 2) / 3) + rand(0, 5);
			//выводим буквы, записывает текст на изображение с использованием шрифтов TrueType.
			imagettftext($src, $size, rand(0, 15), $x, $y, $color, self::FONT, $letter);
			$code .= $letter;
		}
		//записываем в сессию 
		$_SESSION["rand_code"] = $code;
		//отправляем заголовок, что будем выводить
		header("Content-type: image/gif");
		//выводит изображение в браузер или файл
		imagegif($src);
	}
	
	//проверки капчи
	public static function check($code) {
		if (!session_id()) session_start();
		//сравниваем переданный код с тем, что находиться в сессии
		return ($code === $_SESSION["rand_code"]);
	}
}

?>