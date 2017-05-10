-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Май 10 2017 г., 12:57
-- Версия сервера: 5.5.25
-- Версия PHP: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `a178`
--

-- --------------------------------------------------------

--
-- Структура таблицы `myit_articles`
--

CREATE TABLE IF NOT EXISTS `myit_articles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `img` varchar(255) NOT NULL,
  `intro` text NOT NULL,
  `full` text NOT NULL,
  `section_id` int(11) unsigned DEFAULT NULL,
  `cat_id` int(11) unsigned DEFAULT NULL,
  `date` int(11) unsigned NOT NULL,
  `meta_desc` varchar(255) NOT NULL,
  `meta_key` varchar(255) NOT NULL,
  `id_part` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Дамп данных таблицы `myit_articles`
--

INSERT INTO `myit_articles` (`id`, `title`, `img`, `intro`, `full`, `section_id`, `cat_id`, `date`, `meta_desc`, `meta_key`, `id_part`) VALUES
(1, 'Запчасть 1', 'img.jpg', 'Короткое описание', 'Длииииииииное описание', 1, 1, 1402084346, 'Запчасть 1', 'запчасть, запчасти', 1),
(2, 'Запчасть 2', 'img.jpg', 'Короткое описание', 'Длииииииииное описание', 1, 1, 1402084286, 'Запчасть 2', 'запчасть, запчасти', 1),
(3, 'Запчасть 3', 'img.jpg', 'Короткое описание', 'Длииииииииное описание', 1, 1, 1402084285, 'Запчасть 3', 'запчасть, запчасти', 2),
(4, 'Запчасть 4', 'img.jpg', 'Короткое описание\n\n', 'Длииииииииное описание\n\n', 1, 1, 1402084280, 'Запчасть 4', 'запчасть, запчасти', 2),
(5, 'Запчасть 5', 'img.jpg', 'Короткое описание\n', 'Длииииииииное описание\n\n', 1, 1, 1402084279, 'Запчасть 5', 'запчасть, запчасти', 3);

-- --------------------------------------------------------

--
-- Структура таблицы `myit_categories`
--

CREATE TABLE IF NOT EXISTS `myit_categories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `img` varchar(255) NOT NULL,
  `section_id` int(11) unsigned NOT NULL,
  `description` text NOT NULL,
  `meta_desc` varchar(255) NOT NULL,
  `meta_key` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `myit_categories`
--

INSERT INTO `myit_categories` (`id`, `title`, `img`, `section_id`, `description`, `meta_desc`, `meta_key`) VALUES
(1, 'HTML Основы', 'html-osnovy.jpg', 1, '<p>В данном разделе вы познакомитесь с основами HTML</p>', 'Множество статей по основам HTML. Рассматриваются теги HTML, множество примеров их использования.', 'html основы, сайт html, создание сайта на html'),
(2, 'CSS Основы', 'css-osnovy.jpg', 2, '<p><span class="tutor">CSS(Cascading Style Sheets)</span> - это каскадные таблицы стилей, которая позволяет нам с вами создавать внешний вид страниц сайта.</p>\n				<p>Данная рубрика научит вас, как с помощью каскадных таблиц стилей можно легко управлять дизайном сайта, и вам даже не понадобиться <span class="tutor">"учебник по CSS"</span>.</p>\n				<h2>Уроки бесплатные и служат для начинающих Web-мастеров, все материалы изложены в максимальной простой форме, так что дерзайте! :)</h2>', 'Материалы по основам CSS - каскадным таблицам стилей. Рассматриваются селекторы CSS и хаки для браузеров.', 'css, dhtml, селекторы css, хаки для браузеров');

-- --------------------------------------------------------

--
-- Структура таблицы `myit_comments`
--

CREATE TABLE IF NOT EXISTS `myit_comments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `article_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `parent_id` int(11) unsigned DEFAULT NULL,
  `text` text NOT NULL,
  `date` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=41 ;

-- --------------------------------------------------------

--
-- Структура таблицы `myit_courses`
--

CREATE TABLE IF NOT EXISTS `myit_courses` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) unsigned NOT NULL,
  `header` varchar(100) NOT NULL,
  `sub_header` varchar(100) NOT NULL,
  `img` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `did` int(10) unsigned NOT NULL,
  `latest` tinyint(1) unsigned NOT NULL,
  `section_ids` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `myit_courses`
--

INSERT INTO `myit_courses` (`id`, `type`, `header`, `sub_header`, `img`, `link`, `text`, `did`, `latest`, `section_ids`) VALUES
(1, 1, 'Мой Видеокурс', 'Создание и раскрутка сайта от А до Я', 'kurs-cover.png', 'http://srs.myrusakov.ru/kurs?utm_source=MyRusakov.ru&amp;utm_medium=Banner&amp;utm_campaign=SRS-MyRusakov', '<p>Видеокурс "<b>Создание и Раскрутка сайта от А до Я</b>" - это 246 видеоуроков общей продолжительностью более 50-ти часов по теме создания, размещения в Интернете и раскрутке сайта.</p> <p>В уроке рассмотрены следующие необходимые любому <b>профессиональному Web-мастеру</b> языки: <b>HTML</b>, <b>CSS</b>, <b>JavaScript</b>, <b>PHP</b>, <b>SQL</b> (с использованием <b>MySQL</b>) и <b>XML</b>.</p> <p>Помимо этого в Видеокурсе рассматривается самая популярная система управления контентом - <b>Joomla</b>.</p> <p>Также в Видеокурсе "<b>Создание и Раскрутка сайта от А до Я</b>" показывается весь процесс создания реального сайта - <b>MyRusakov.ru</b>. Затем демонстрируется его размещение в Интернете, а также последующая раскрутка. Всё это записывается, и Вам остаётся лишь просто повторить, чтобы стать владельцем своего собственного сайта!</p> <p>И, наконец, почти к каждому уроку идут упражнения, которые позволяют закрепить полученные знания из урока уже на практике.</p>', 0, 0, '6,8,9');

-- --------------------------------------------------------

--
-- Структура таблицы `myit_marka`
--

CREATE TABLE IF NOT EXISTS `myit_marka` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `date` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Дамп данных таблицы `myit_marka`
--

INSERT INTO `myit_marka` (`id`, `name`, `date`) VALUES
(1, 'BMW', 1494326877),
(2, 'Audi', 1494326877),
(3, 'Lex', 1494326877),
(4, 'Lada', 1494326877),
(5, 'Toyota', 1494326877),
(6, 'Bently', 1494326877),
(7, 'Acura', 1494326877),
(8, 'Hreny', 1494326877);

-- --------------------------------------------------------

--
-- Структура таблицы `myit_menu`
--

CREATE TABLE IF NOT EXISTS `myit_menu` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` int(11) unsigned NOT NULL,
  `title` varchar(100) NOT NULL,
  `link` varchar(255) NOT NULL,
  `parent_id` int(11) unsigned DEFAULT NULL,
  `external` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `myit_menu`
--

INSERT INTO `myit_menu` (`id`, `type`, `title`, `link`, `parent_id`, `external`) VALUES
(1, 2, 'каталог', '/catalog.html', NULL, 0),
(2, 2, 'помощь', '/help.html', NULL, 0),
(3, 2, 'контакты', '/contact.html', NULL, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `myit_parts_types`
--

CREATE TABLE IF NOT EXISTS `myit_parts_types` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `date` int(11) unsigned NOT NULL,
  `id_marka` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Дамп данных таблицы `myit_parts_types`
--

INSERT INTO `myit_parts_types` (`id`, `name`, `date`, `id_marka`) VALUES
(1, 'Запчасть 1', 1494332955, 1),
(2, 'Запчасти 2', 1494332955, 1),
(3, 'Запчасти 3', 1494332955, 2),
(4, 'Запчасти 4', 1494332955, 2),
(5, 'Запчасти 5', 1494332955, 3);

-- --------------------------------------------------------

--
-- Структура таблицы `myit_polls`
--

CREATE TABLE IF NOT EXISTS `myit_polls` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `state` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `myit_polls`
--

INSERT INTO `myit_polls` (`id`, `title`, `state`) VALUES
(1, 'Что Вас интересует больше?', 0),
(2, 'Посоветуйте тему для будущего видеокурса', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `myit_poll_data`
--

CREATE TABLE IF NOT EXISTS `myit_poll_data` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `poll_id` int(11) unsigned NOT NULL,
  `title` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `myit_poll_data`
--

INSERT INTO `myit_poll_data` (`id`, `poll_id`, `title`) VALUES
(1, 1, 'HTML'),
(2, 1, 'CSS');

-- --------------------------------------------------------

--
-- Структура таблицы `myit_poll_voters`
--

CREATE TABLE IF NOT EXISTS `myit_poll_voters` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `poll_data_id` int(11) unsigned NOT NULL,
  `ip` bigint(20) NOT NULL,
  `date` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `myit_poll_voters`
--

INSERT INTO `myit_poll_voters` (`id`, `poll_data_id`, `ip`, `date`) VALUES
(1, 11, 2130706433, 1399838478);

-- --------------------------------------------------------

--
-- Структура таблицы `myit_quotes`
--

CREATE TABLE IF NOT EXISTS `myit_quotes` (
  `id` int(11) NOT NULL,
  `author` varchar(100) NOT NULL,
  `text` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `myit_quotes`
--

INSERT INTO `myit_quotes` (`id`, `author`, `text`) VALUES
(1, 'Альберт Эйнштейн', 'Жизнь — как вождение велосипеда. Чтобы сохранить равновесие, ты должен двигаться.');

-- --------------------------------------------------------

--
-- Структура таблицы `myit_sections`
--

CREATE TABLE IF NOT EXISTS `myit_sections` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `img` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `meta_desc` varchar(255) NOT NULL,
  `meta_key` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `myit_sections`
--

INSERT INTO `myit_sections` (`id`, `title`, `img`, `description`, `meta_desc`, `meta_key`) VALUES
(1, 'HTML', 'html.jpg', '<p><span class="tutor">HTML</span> - это гипертекстовый язык разметки, на котором создаётся абсолютное большинство страниц в Интернете, можно сказать это скелет сайта строения поэтому его должен знать каждый даже если вы работаете на <span class="tutor">CMS</span>.</p>\n			<p>Поэтому данный раздел сайта является обязательным к изучению абсолютно для всех <span class="tutor">Web-мастеров</span>.</p>\n			<p>Данная рубрика заменит Вам полноценный «<span class="tutor">HTML учебник</span>». Здесь Вы сможете найти ответы на большинство вопросов, связанных с <span class="tutor">HTML</span>.</p>\n			<h2>Уроки бесплатные и служат для начинающих Web-мастеров.</h2>', 'Множество материала по основам HTML, а также по новому стандарту - HTML 5.', 'html, html 5, язык html, создать сайт на html, html теги'),
(2, 'CSS', 'css.jpg', '<p><span class="tutor">CSS(Cascading Style Sheets)</span> - это каскадные таблицы стилей, которая позволяет нам с вами создавать внешний вид страниц сайта.</p>\n				<p>Данная рубрика научит вас, как с помощью каскадных таблиц стилей можно легко управлять дизайном сайта, и вам даже не понадобиться <span class="tutor">"учебник по CSS"</span>.</p>\n				<h2>Уроки бесплатные и служат для начинающих Web-мастеров, все материалы изложены в максимальной простой форме, так что дерзайте! :)</h2>', 'Различная информация и материалы по CSS - каскадным таблицам стилей.', 'css, селекторы css, атрибуты css');

-- --------------------------------------------------------

--
-- Структура таблицы `myit_sef`
--

CREATE TABLE IF NOT EXISTS `myit_sef` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `link` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=27 ;

--
-- Дамп данных таблицы `myit_sef`
--

INSERT INTO `myit_sef` (`id`, `link`, `alias`) VALUES
(1, '/contact', 'contact'),
(2, '/register', 'register'),
(3, '/activate', 'activate'),
(4, '/sregister', 'sregister'),
(5, '/reset', 'reset'),
(6, '/sreset', 'sreset'),
(7, '/logout', 'logout'),
(8, '/remind', 'remind'),
(9, '/search', 'search'),
(10, '/user/editprofile', 'user/editprofile'),
(11, '/section?id=1', 'html'),
(12, '/section?id=2', 'css'),
(26, '/parts?id=1', 'bamper'),
(16, '/marki?id=1', 'bmw'),
(17, '/marki?id=2', 'audi'),
(18, '/marki?id=3', 'lex'),
(19, '/marki?id=4', 'lada'),
(20, '/marki?id=5', 'toyota'),
(21, '/catalog', 'catalog'),
(22, '/help', 'help'),
(23, '/marki?id=6', 'bently'),
(24, '/marki?id=7', 'acura'),
(25, '/marki?id=8', 'hreny');

-- --------------------------------------------------------

--
-- Структура таблицы `myit_users`
--

CREATE TABLE IF NOT EXISTS `myit_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(32) NOT NULL,
  `name` varchar(255) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `date_reg` int(11) unsigned NOT NULL,
  `activation` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
