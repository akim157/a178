<?php
/*валидатор для проверки имен*/
class ValidateName extends Validator {
	//максимальная длинна имени
	const MAX_LEN = 100;
	//имя не заданно
	const CODE_EMPTY = "ERROR_NAME_EMPTY";
	//не верно указано имя
	const CODE_INVALID = "ERROR_NAME_INVALID";
	//макимальная длинна имени превышенно
	const CODE_MAX_LEN = "ERROR_NAME_MAX_LEN";
	
	protected function validate() {
		//берем данные из родитеского класса
		$data = $this->data;
		//провера есть ли данные
		if (mb_strlen($data) == 0) $this->setError(self::CODE_EMPTY);
		else {
			//проверка на длинну имени
			if (mb_strlen($data) > self::MAX_LEN) $this->setError(self::CODE_MAX_LEN);
			//проверка на корректность имени используем родительский метод
			elseif ($this->isContainQuotes($data)) $this->setError(self::CODE_INVALID);
		}
	}
	
}

?>