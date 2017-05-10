<?php
/*класс отвечающий за различные запросы POST и GET за хранения и безопасность*/
class Request {
	
	private static $sef_data = array();
	//поля для данных
	private $data;
		
	public function __construct() {
		//данные пропускаем через метод, который устраняет уязвимости
		$this->data = $this->xss(array_merge($_REQUEST, self::$sef_data));
	}
	
	public static function addSEFData($sef_data) {
		self::$sef_data = $sef_data;
	}
	
	/*по доступу при обращении приме: $this->request->id
	при таком обращении будем поподать сюда*/
	public function __get($name) {
		//если данный элемент существует то мы его возвращаем
		if (isset($this->data[$name])) return $this->data[$name];
	}
	
	//метод решает проблему данных с уязвимостью при get и post запросах
	private function xss($data) {
		//проверяем массив ли это
		if (is_array($data)) {
			//создаем пустой массив
			$escaped = array();
			//проводим итерацию с данными
			foreach ($data as $key => $value) {
				//если массив является двоичный то пропускаем его снова через метод
				$escaped[$key] = $this->xss($value);
			}
			return $escaped;
		}
		//если это не массив то удаляем пробелы в данных и 
		//Преобразует специальные символы в HTML сущности
		return trim(htmlspecialchars($data));
	}
}
?>