<?php
/*валидатор для проверки даты*/
class ValidateDate extends Validator {
	
	protected function validate() {
		//обращаемся к родительскому классу для получения данных
		$data = $this->data;
		//если данные не null и не являются датой то не известная ошибка
		if (!is_null($data) && strtotime($data) === false) $this->setError(self::CODE_UNKNOWN);
	}
	
}

?>