<?php
/*валидатор для проверки ключевых слов в мега тегах*/
class ValidateMK extends Validator {
	//максимальная длинна строки
	const MAX_LEN = 255;
	//незаданы ключевые слова
	const CODE_EMPTY = "ERROR_MK_EMPTY";
	//превышенна макимальная длинна строки
	const CODE_MAX_LEN = "ERROR_MK_MAX_LEN";
	
	protected function validate() {
		//береме данные из родительского класса
		$data = $this->data;
		//если данных нет
		if (mb_strlen($data) == 0) $this->setError(self::CODE_EMPTY);
		//если превышен строковой лимит
		if (mb_strlen($data) > self::MAX_LEN) $this->setError(self::CODE_MAX_LEN);
	}
	
}

?>