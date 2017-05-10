<?php
/*Класс служит для настройки сайта, его конфигурации*/
//Создаем константы
abstract class Config {
	
	//название сайта
	const SITENAME = "а178.рф";
	//сектерный ключ для хеширования паролей
	const SECRET = "HREN5";
	//адрес сайта
	const ADDRESS = "http://a178.loc";
	//ФИО программиста
	const ADM_NAME = "Максим Фёдоров";
	//email программиста
	const ADM_EMAIL = "root@a178.ru";
	
	//секретный ключ для API запросов
	const API_KEY = "DKEL39DL";
	
	/*Настройки БД*/
	//имя хоста
	const DB_HOST = "localhost";
	//имя пользователя
	const DB_USER = "root";
	//пароль
	const DB_PASSWORD = "";
	//имя БД
	const DB_NAME = "a178";
	//префикс для таблиц
	const DB_PREFIX = "myit_";
	//хрень для замены в запросах для безопасноси
	const DB_SYM_QUERY = "?";
	
	//путь к изображениям
	const DIR_IMG = "/img/";
	//путь к изображениям статей
	const DIR_IMG_ARTICLES = "/img/articles/";
	//путь к аватаркам
	const DIR_AVATAR = "/img/avatar/";
	//путь к директориям tmpl нужен полный физический путь
	const DIR_TMPL = "/home/a178.loc/www/tmpl/";
	//путь к сообщениям email
	const DIR_EMAILS = "/home/a178.loc/www/tmpl/emails/";
	
	//
	const LAYOUT = "main";
	//файл для текстовых сообщейний
	const FILE_MESSAGES = "/home/a178.loc/www/text/messages.ini";
	
	//формат даты
	const FORMAT_DATE = "%d.%m.%Y %H:%M:%S";
	
	//количество выводимых статей на странице
	const COUNT_ARTICLES_ON_PAGE = 10;
	//количество выводимых страниц на пагинации
	const COUNT_SHOW_PAGES = 10;
	
	/*Поиск*/
	//минимальное количество символов для поиска
	const MIN_SEARCH_LEN = 3;
	//длинна строки для вывода в поиске
	const LEN_SEARCH_RES = 255;
	
	/*аватарки*/
	//имя аватарки по умолчанию
	const DEFAULT_AVATAR = "default.png";
	//максимальный размер аватраки 50 килобайт
	const MAX_SIZE_AVATAR = 51200;
	
	//расширение для чпу ссылок
	const SEF_SUFFIX = ".html";
	
}
?>