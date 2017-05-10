<?php
/*валидатор проверка текста*/
class ValidateText extends Validator {
	//максимальный длинна текста
	const MAX_LEN = 50000;
	//текста нету
	const CODE_EMPTY = "ERROR_TEXT_EMPTY";
	//максимальная длинна тексат
	const CODE_MAX_LEN = "ERROR_TEXT_MAX_LEN";
	
	protected function validate() {
		//берем занчение от родителя
		$data = $this->data;
		//если текста нет
		if (mb_strlen($data) == 0) $this->setError(self::CODE_EMPTY);
		//если тект привышает макимальную длинну
		elseif (mb_strlen($data) > self::MAX_LEN) $this->setError(self::CODE_MAX_LEN);
	}
	
}

?>