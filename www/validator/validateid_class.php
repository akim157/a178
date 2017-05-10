<?php
/*валидатор для проверки id*/
class ValidateID extends Validator {
	
	protected function validate() {
		//запрашиваем данные у родителя Validator и переводим его в число
		$data = (int) $this->data;
		//если данные не null и не число и меньше 0, то неизвестная ошибка
		if (!is_null($data) && ((!is_int($data)) || ($data < 0))) $this->setError(self::CODE_UNKNOWN);
	}
	
}

?>