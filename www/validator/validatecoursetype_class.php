<?php
/*валидатор для курсов*/
class ValidateCourseType extends Validator {
	//максимальное возможно число
	const MAX_COURSETYPE = 3;
	
	protected function validate() {
		//берем данные с родителя
		$data = $this->data;
		//если не числовой то неизвестная ошибка
		if (!is_int($data)) $this->setError(self::CODE_UNKNOWN);
		else {
			//если меньше 1 или больше 3 то неизвестная ошибка
			if (($data < 1) || ($data > self::MAX_COURSETYPE)) $this->setError(self::CODE_UNKNOWN);
		}
	}
	
}

?>