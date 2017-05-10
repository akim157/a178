<?php
/*класс для проверки mega тегов*/
class ValidateMD extends Validator {
	//максимальная длинна строки 
	const MAX_LEN = 255;
	//нету строки
	const CODE_EMPTY = "ERROR_MD_EMPTY";
	//привышен максимальный длинна строки
	const CODE_MAX_LEN = "ERROR_MD_MAX_LEN";
	
	protected function validate() {
		//берем данные из БД
		$data = $this->data;
		//проверка на есть ли строка
		if (mb_strlen($data) == 0) $this->setError(self::CODE_EMPTY);
		//проверка на максимальную длину
		if (mb_strlen($data) > self::MAX_LEN) $this->setError(self::CODE_MAX_LEN);
	}
	
}

?>