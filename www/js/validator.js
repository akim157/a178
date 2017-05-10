/*скрипт для обрадотки данных из форм*/
//проверка формы (параметром получаем форму)
function checkForm(form) {
	//находим элементы с атрибутами и присваиваем их к переменной
	var elements = $(form).find("[data-type]");
	//создаем пустую переменную, которая будет содержить все ошибки
	var bad = "";
	//проводим интерацию, чтобы поработать с каждым элементом
	for (var i = 0; i < elements.length; i++)
		//обращаемся к функции (парметрами)
		bad += checkElement(elements.get(i));
		//если переменна пуста
	if (bad == "") {
		//текст котоырй будет появлятеся перед отправки формы, как подтверждение
		var t_confirm = $(form).find(["data-tconfirm"]).attr("data-tconfirm");
		//если данное сообщение есть то через метод выводим данное сообщение
		if (t_confirm) return confirm(t_confirm);
		return true;
	}
	alert(bad);
	return false;
}
//проверка элементов
function checkElement(element) {
	//получаем все параметры, которые были прописанны в классе jsvalidator
	var type = $(element).attr("data-type");
	var min_len = $(element).attr("data-minlen");
	var max_len = $(element).attr("data-maxlen");
	var t_min_len = $(element).attr("data-tminlen");
	var t_max_len = $(element).attr("data-tmaxlen");
	var t_empty = $(element).attr("data-tempty");
	var t_type = $(element).attr("data-ttype");
	var f_equal = $(element).attr("data-fequal");
	var t_equal = $(element).attr("data-tequal");
	var bad = "";
	//если тип пустой
	if (type == "") {
		//вызываем функцию и передаем данные
		bad += checkTextInput($(element).val(), min_len, max_len, t_empty, t_min_len, t_max_len);
		//проверка на эквивалентность
		bad += checkEqual($(element), f_equal, t_equal);
	}
	else if (type == "name") {
		bad += checkName($(element).val(), max_len, t_empty, t_type, t_max_len);
	}
	else if (type == "login") {
		bad += checkLogin($(element).val(), max_len, t_empty, t_type, t_max_len);
	}
	else if (type == "email") {
		bad += checkEmail($(element).val(), max_len, t_empty, t_type, t_max_len);
	}
	
	return bad;
}
//проверка текста в полях введенное пользователем
function checkTextInput(value, min_len, max_len, t_empty, t_min_len, t_max_len) {
	//если данное поле пустое
	if (value.length == 0) return t_empty + "\n";
	else {
		//если длинна минимальна
		if (value.length < min_len) return t_min_len + "\n";
		//если длинна превышенна
		if (max_len && value.length > max_len) return t_max_len + "\n";
	}
	return "";
}
//проверки имени
function checkName(name, max_len, t_empty, t_type, t_max_len) {
	//проверка на пустоту
	if (name.length == 0) return t_empty + "\n";
	//проверка на эквивалентность
	if (isContainQuotes(name)) return t_type + "\n";
	//проверка на максимальную длинну
	if (max_len && name.length > max_len) return t_max_len + "\n";
	return "";
}
//проверка логина
function checkLogin(login, max_len, t_empty, t_type, t_max_len) {
	//проверка на пустоту
	if (login.length == 0) return t_empty + "\n";
	//проверка на эквивалентность
	if (isContainQuotes(login)) return t_type + "\n";
	//проверка на максимальную длинну
	if (max_len && login.length > max_len) return t_max_len + "\n";
	return "";
}
//проверка почты
function checkEmail(email, max_len, t_empty, t_type, t_max_len) {
	//проверка указан ли почтв
	if (email.length == 0) return t_empty + "\n";
	//проверка на регулярное вырожение
	if (email.match(/^[a-z0-9_][a-z0-9\._-]*@([a-z0-9]+([a-z0-9-]*[a-z0-9]+)*\.)+[a-z]+$/i) == null) return t_type + "\n";
	//проверка на максимальную длинну
	if (max_len && email.length > max_len) return t_max_len + "\n";
	return "";
}
//функция для проверки эквивалентности
function isContainQuotes(string) {
	var array = new Array("\"", "'", "`", "&quot;", "&apos;");
	for (var i = 0; i < array.length; i++) {
		//в строке ищем элемент массива
		if (string.indexOf(array[i]) !== -1) return true;
	}
	return false;
}
//проверка на эквивалентость
function checkEqual(element, f_equal, t_equal) {
	if (f_equal == "") return "";
	//ищем форму среди родителя элемента
	var form = $(element).parents("form");
	//ищем поле с определенным именем
	var field = $(form).find("[name='" + f_equal + "']");
	//сравниваем
	if (element.val() != field.val()) return t_equal + "\n";
	return "";
}