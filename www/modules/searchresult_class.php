<?php
/*модуль для поисковой страницы*/
class SearchResult extends ModuleHornav {
	
	public function __construct() {
		parent::__construct();
		//поисковй запрос
		$this->add("query");
		//поле где ищеться инфа
		$this->add("field");
		//ошибка что короткий поисковый запрос
		$this->add("error_len", false);
		//набор данных
		$this->add("data", null, true);
	}
	//инициализация
	protected function preRender() {
		$query = $this->query;
		//приводим к нижнему регистру
		$query = mb_strtolower($query);
		//заменим все пробелы на один пробел
		$query = preg_replace("/ {2,}/", " ", $query);
		//добавляем свойство дискпришен в каждый из элементов
		foreach ($this->data as $d) $d->description = $this->getDescription($d->{$this->field}, $query);
	}
	//поиск
	private function getDescription($text, $query) {
		//динна поискового запроса
		$len = Config::LEN_SEARCH_RES;
		//если длинна текста больше
		if (strlen($text) > $len) {
			$i = 0;
			//получаем массив слов
			$k = 0;
			$array_words = explode(" ", $query);
			$pos = array();
			//перебираем слова
			foreach ($array_words as $key => $value) {
				//ищет вхождение в текст причем со смещением 
				while (strpos($text, $value, $i) !== false) {
					$pos[$k] = strpos($text, $value, $i);
					$i += $pos[$k] + 1;
					if ($i < strlen($text)) $i = strlen($text);
					$k++;
				}
				$i = 0;
			}
			//от какого символа до есть вхождение
			if (count($pos) != 0) {
				if (count($pos) > 1) {
					$k = 0;
					$max = 1;
					//максимальная частота
					$max_freq = array();
					//перебираем элементы
					for ($i = 0; $i < count($pos); $i++) {
						$k = 1;
						$sum = 0;
						$temp_freq[$k - 1] = $pos[$i];
						for ($j = $i; $j < count($pos) - 1; $j++) {
							//дистанция между вхожденями
							$sum += $pos[$j + 1] - $pos[$j];
							if ($sum <= $len) $k++;
							else break;
							$temp_freq[$k] = $pos[$j + 1];
						}
						if ($k > $max) {
							$max = $k;
							$max_freq = $temp_freq;
						}
					}
					if (count($max_freq) == 0) {
						$max = 0;
						$max_freq[] = $pos[0];
					}
				}
				else {
					$max = 0;
					$max_freq = $pos;
				}
				//расстояние между первым элементом и последним
				$free_space = $len - ($max_freq[$max] - $max_freq[0]);
				$start = $max_freq[0] - $free_space / 2;
				$end = $max_freq[$max] + $free_space / 2;
				if ($start < 0) {
					$end -= $start;
					$start = 0;
				}
				if ($end > strlen($text)) {
					$start -= ($end - strlen($text));
					$end = strlen($text);
				}
			}
			else {
				$start = 0;
				$end = $len;
			}
			while (!preg_match("/[[:space:]]/", substr($text, $start - 1, 1)) && ($start - 1) > 0)
				$start--;
			while (!preg_match("/[[:space:]]/", substr($text, $end, 1)) && $end < strlen($text))
				$end++;
		}
		else {
			$start = 0;
			$end = strlen($text);
		}
		if ($start == 1) $start = 0;
		if ($start < 1) $st_d = "";
		else $st_d = "... ";
		if ($end == strlen($text)) $end_d = "";
		else $end_d = " ...";
		$description = substr($text, $start, $end - $start);
		$description = $st_d.$description.$end_d;
		//заменить поисковый запрос в этом кусочке текста
		return $this->selectSearchWords($description, $query);
	}
	//заменяем посковое слово
	private function selectSearchWords($description, $query) {
		$array_words = explode(" ", $query);
		foreach ($array_words as $value) {
			$description = preg_replace("/".$value."/i", "<span>$value</span>", $description);
		}
		return $description;
	}
	
	public function getTmplFile() {
		return "search_result";
	}
	
}

?>