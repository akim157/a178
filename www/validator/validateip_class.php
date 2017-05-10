<?php
/*класс для провеки IP адресов*/
class ValidateIP extends Validator {
	
	protected function validate() {
		//берем данные из родитеського класса
		$data = $this->data;
		//проверям если данных является 0 то мы его и возвращаем
		if ($data == 0) return;
		//проверка через регулярное вырожение
		if (!preg_match("/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/", $data)) $this->setError(self::CODE_UNKNOWN);
	}
	
}

?>