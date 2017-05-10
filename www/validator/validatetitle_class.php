<?php
/*валидатор для проверки заголовков*/
class ValidateTitle extends Validator {
	//максимальная длинна
	const MAX_LEN = 100;
	//заголовок не задан
	const CODE_EMPTY = "ERROR_TITLE_EMPTY";
	//заголовок максимальная длинна
	const CODE_MAX_LEN = "ERROR_TITLE_MAX_LEN";
	
	protected function validate() {
		//берем данные из родителя
		$data = $this->data;
		//проверка на существование заголовка
		if (mb_strlen($data) == 0) $this->setError(self::CODE_EMPTY);
		//проверка на длинну заголовка
		elseif (mb_strlen($data) > self::MAX_LEN) $this->setError(self::CODE_MAX_LEN);
	}
	
}

?>