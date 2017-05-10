<?php
	//указываем кодировку
	mb_internal_encoding("UTF-8");
	//вывод всех ошибок
	error_reporting(E_ALL);
	//выводились на экран
	ini_set("display_errors", 1);
	
	//настроиваем пути, чтобы php знал где искать файлы
	//get_include_path - берем стандартные пути, которые находятся в файле php.ini
	//с помощью константы PATH_SEPARATOR указываем другие директории
	set_include_path(get_include_path().PATH_SEPARATOR."core".PATH_SEPARATOR."lib".PATH_SEPARATOR."objects".PATH_SEPARATOR."validator".PATH_SEPARATOR."controllers".PATH_SEPARATOR."modules");
	//автозагружка класса (расширение классов)	
	spl_autoload_extensions("_class.php");
	//запускаем
	spl_autoload_register();
	
	/*формируем константы*/
	//гавлное меню
	define("MAINMENU", 1);
	//верхнее меню
	define("TOPMENU", 2);
	//размер
	define("KB_B", 1024);
	define("PAY_COURSE", 1);
	define("FREE_COURSE", 2);
	define("ONLINE_COURSE", 3);
	
	//создаем один экземпляр к БД объект для работы с нашей БД
	AbstractObjectDB::setDB(DataBase::getDBO());	
?>