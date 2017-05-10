<?php
/*Класс для работы с БД*/
abstract class AbstractDataBase {
	
	/*Объявляем в классе 3 поля*/
	//индентификатор соединения с бд
	private $mysqli;
	//строка заменяющие значение в запросах бд
	private $sq;
	//префикс для таблиц бд
	private $prefix;
	
	/*Конструктор имеет protected чтобы не создавать много объектов
	в избежании множество подключений при одном пользователе*/
	protected function __construct($db_host, $db_user, $db_password, $db_name, $sq, $prefix){
		/*в параметрах указывается настройки для соединения с бд*/
		//db_host - имя хоста
		//db_user - имя пользователя БД
		//db_password - пароль для БД
		//db_name - имя БД
		//db_prefix - название префикса для таблиц
		
		/*создаем объект для подключения к бд*/		
		$this->mysqli = @new mysqli($db_host, $db_user, $db_password, $db_name);		
		//проверяем есть ли ошибки при подключении, если есть то сразу выходим
		if($this->mysqli->connect_errno) exit("Ошибка соединения с базой данных");
		/*Если ошибок нет то к полям присваеваем значения параметров передынных конструктуру*/
		$this->sq = $sq;
		$this->prefix = $prefix;
		
		//параметр для русскоязычных сайтов для времени
		$this->mysqli->query("SET lc_time_names = 'ru_RU'");
		
		//кодировка
		$this->mysqli->set_charset("utf8");		
	}
	
	/*Метод возвращает заменяющие значение в запросах
	это служит для безопасности, чтобы избежать sql инъекции
	пример:real_escape_string(x)*/
	public function getSQ(){
		return $this->sq;
	}
	
	/*Метод, принимает ?, который заменяет заничение в запросах,
	а вторым параметром массив уже со значениями
	query - len = ?
	params - arr(article?id=6)*/
	public function getQuery($query, $params){
		//проверяем есть ли параметры вообще
		if($params){
			$offset = 0;
			//длинна строки для безопасности '?'
			$len_sq = strlen($this->sq);
			//проводим итерацию и проверям каждое значения запроса
			for($i = 0; $i < count($params); $i++) {
				//ищем позицию вхождения для строки
				$pos = strpos($query, $this->sq, $offset);
				//проверка на null 
				if(is_null($params[$i])) $arg = "NULL";
				//если нет то проверям значение специальный символы и экранируем их
				else $arg = "'".$this->mysqli->real_escape_string($params[$i])."'";
				//заменям часть строки
				/*query - где заменям
				arg - что заменям
				pos - с какого символа заменям
				len_sq - длинна замены*/
				$query = substr_replace($query, $arg, $pos, $len_sq);
				//смещения, чтобы искать с новой позиции и небыло путоница со знакими '?'
				$offset = $pos + strlen($arg);
			}
		}
		//если нету, то просто вопрос возвращаем
		return $query;
	}
	
	/*метод для выборки таблиц значений
		AbstractSelect $select - данные передаются с класса AbstractSelect, там они формируются
	*/
	public function select(AbstractSelect $select){
		//параметром принимаем объект select запроса: SELECT * FROM `xyz_articles` ORDER BY `date` DESC LIMIT 0, 3
		
		//получаем результат из Метода getResultSet, где первым параметром идет объект запроса
		$result_set = $this->getResultSet($select, true, true);
		/*проверка на получения данных*/
		//если получили false то и возвращаем false
		if(!$result_set) return false;
		
		/*если получили данные то преобразовываем в массив*/
		//создаем пустой массив
		$array = array();
		//с помощью цикла преобзовываем объект в массив, если данные есть то сохраняем в массиве.
		while(($row = $result_set->fetch_assoc()) != false)
			$array[] = $row;
		//возвращаем полученный результат
		return $array;		
	}
	
	/*Метод, который позволяет получить строку из таблицы*/
	public function selectRow(AbstractSelect $select){
		//параметром так же является объект запроса		
		//получаем результат из Метода getResultSet, где первым параметром идет объект запроса
		$result_set = $this->getResultSet($select, false, true);
		/*проверка на получения данных*/
		//если получили false то и возвращаем false
		if(!$result_set) return false;
		//возвращаем полученный результат в одномерном массиве
		return $result_set->fetch_assoc();
	}
	
	/*Метод, возвращает значение колонки из таблицы БД*/
	public function selectCol(AbstractSelect $select){
		//параметром так же является объект запроса
		
		//получаем результат из Метода getResultSet, где первым параметром идет объект запроса
		$result_set = $this->getResultSet($select, true, true);
		/*проверка на получения данных*/
		//если получили false то и возвращаем false
		if(!$result_set) return false;
		
		//создаем пустой массив
		$array = array();
		while(($row = $result_set->fetch_assoc()) != false) {
			foreach($row as $value) {
				$array[] = $value;
				break;
			}	
		}
		//возвращаем уже полученный результат
		return $array;
	}
	
	/*извлечь содержимое ячейки*/
	public function selectCell(AbstractSelect $select){
		//параметром так же является объект запроса
		
		//получаем результат из Метода getResultSet, где первым параметром идет объект запроса
		$result_set = $this->getResultSet($select, false, true);
		/*проверка на получения данных*/
		//если получили false то и возвращаем false
		if(!$result_set) return false;
		
		//выберам все значение массива
		$arr = array_values($result_set->fetch_assoc());
		//возвращаем только первое значение
		return $arr[0];
	}
	
	/*метод для формирования запроса INSERT*/
	public function insert($table_name, $row){
		/*table_name - название таблицы, куда должны пойти данные
		row - сами данные, ключи название столбца*/
		
		//проверка на данные, если данных нет то возвращаем false
		if(count($row) == 0) return false;
		//получаем название таблицы с префиксом, с помощью метода getTableName
		$table_name = $this->getTableName($table_name);
		/*формируем поля
		ПРИМЕР: INSERT INTO TABLE (ID,HREN) VALUES (10, HREN)*/
		//поля
		$fields = "(";
		//значение полей
		$values = "VALUES (";
		//формируем массив параметров для отправки getQuery для безопасности
		$params = array();
		foreach($row as $key => $value) {
			//название полей
			$fields .= "`$key`,";
			//присваеваем знак ?
			$values .= $this->sq.",";
			//значение поля
			$params[] = $value;
		}
		//удаляем ',' в конце строки
		$fields = substr($fields, 0, -1);
		$values = substr($values, 0, -1);
		//закрываем скобки в строках
		$fields .= ")";
		$values .= ")";
		//формирум сам запрос
		$query = "INSERT INTO `$table_name` $fields $values";
		//отпарвляем полученный результат методу query, что он вернет то и возвращаем
		return $this->query($query, $params);
	}
	
	/*Метод для формирования запроса Update*/
	public function update($table_name, $row, $where = false, $params = array()){
		/*table_name - название таблицы
		row - значение полей и их наздвание
		where - дополнительное условие
		params - параметры WHERE ID = 5*/
		
		//проверка на данные, если данных нет то возвращаем false
		if(count($row) == 0) return false;
		//получаем название таблицы с префиксом, с помощью метода getTableName
		$table_name = $this->getTableName($table_name);
		/*создаем запрос update
		пример: UPDATE TABLE SET FIELD = VALUE WHERE = VALUE*/
		$query = "UPDATE `$table_name` SET ";
		//создаем пустой массив для параметров добавления
		$params_add = array();
		foreach($row as $key => $value) {
			//знак ? 
			$query .= "`$key` = ".$this->sq.",";
			$params_add[] = $value;
		}
		//удаляем в строке последнюю ','
		$query = substr($query, 0, -1);
		//проверяем есть ли условие
		if($where){
			//объединям массивы в один			
			$params = array_merge($params_add, $params);
			$query .= " WHERE $where";
		}		
		//полученный результат возвращаем
		return $this->query($query, $params);
	}
	
	/*метод для формирования запроса delete*/
	public function delete($table_name, $where = false, $params = array()){
		/*table_name - название таблицы		
		where - дополнительное условие
		params - параметры WHERE ID = 5
		Пример: DELETE FROM TABLE WHERE HREN = HREN*/
			
		//получаем название таблицы с префиксом, с помощью метода getTableName
		$table_name = $this->getTableName($table_name);
		//формируем запрос
		$query = "DELETE FROM `$table_name`";
		//проверка есть ли условия
		if($where){
			$query .= " WHERE $where";
		}
		//выполням запрос и возвращаем результат
		return $this->query($query, $params);
	}
	
	/*метод для получения полного названия таблицы в БД*/
	public function getTableName($table_name){
		return $this->prefix.$table_name;
	}
	
	/*метод куда приходят запросы для выполнения*/
	private function query($query, $params = false){
		/*query - сам запрос
		params - параметры для запроса, значения заместо знака ?*/
		
		//обращаемя к БД и выполням запрос, запросы не связанные с выборкой (SELECT)		
		$success = $this->mysqli->query($this->getQuery($query, $params));
		//проверка если данных нет то false
		if(!$success) return false;
		//проверка на вставку записи если это не insert то true
		if($this->mysqli->insert_id === 0) return true;
		//возвращаем id последней вставленой записи
		return $this->mysqli->insert_id;
	}
	
	/*query для select*/
	private function getResultSet(AbstractSelect $select, $zero, $one){
		/*select - запрос select объект
		zero - булевский параметр 0 записей в качестве результатов
		one - одна запись в качестве результатов*/
		
		//отправляем на выполнение т.к параметр уже проверин
		$result_set = $this->mysqli->query($select);
		//проверка результата, если пусто то возвращаем false
		if(!$result_set) return false;
		//проверка если zero false и запрос вернул 0 количество рядов запрос то возвращаем false
		if((!$zero) && ($result_set->num_rows == 0)) return false;
		//если one false и запрос вурнул 1 количество рядов
		if((!$one) && ($result_set->num_rows == 1)) return false;
		return $result_set;
	}
	
	/*метод выполняется при исщизновении объекта*/
	public function __destruct(){
		//проверка на ошибки, если их нет, то мы закрываем mysqli, чтобы освободить память
		if(($this->mysqli) && (!$this->mysqli->connect_errno)) $this->mysqli->close();
	}
}
?>