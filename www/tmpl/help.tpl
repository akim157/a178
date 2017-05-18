<div class="col-md-10 help  col-md-push-1">
    <h1 class="text-center">Форма VIN-запроса</h1>
    <h4>Данные автомобиля</h4>
    <div class="alert alert-warning warning-extended-vin">
        <span class="close">X</span>
        <span class="block_vin"></span>
    </div>
    <form class="text-center" onsubmit="return formVin();" action="help.html" method="post">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <select name="marka" id="mrka" class="form-control form-border">
                        <option value="0">Выберите марку автомабиля</option>
                        <option value="1">Marka 1</option>
                        <option value="2">Marka 2</option>
                        <option value="3">Marka 3</option>
                        <option value="4">Marka 4</option>
                        <option value="5">Marka 5</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <input type="text" class="form-control form-border" id="exampleInputModel" placeholder="Модель" name="model">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <select name="year" id="year" class="form-control form-border">
                        <option value="0">Выпуск</option>
                        <option value="2001">2001</option>
                        <option value="2002">2002</option>
                        <option value="2003">2003</option>
                        <option value="2004">2004</option>
                        <option value="2005">2005</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <input type="text" class="form-control form-border" id="exampleInputVin" placeholder="VIN-код" name="vin">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <select name="body_type" id="body_type" class="form-control">
                        <option value="0">Тип кузова</option>
                        <option value="Седан">Седан</option>
                        <option value="Универсал">Универсал</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <input type="text" class="form-control" id="exampleInputDriveType" placeholder="Тип/буквы двигателя" name="drive_type">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <select name="door" id="door" class="form-control">
                        <option value="0">Число дверей</option>
                        <option value="1">2</option>
                        <option value="2">4</option>
                        <option value="3">5</option>
                        <option value="4">7</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <select name="drive_unit" id="drive_unit" class="form-control">
                        <option value="0">Привод</option>
                        <option value="Передний">Передний</option>
                        <option value="Задний">Задний</option>
                        <option value="Полный">Полный</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group group-form-vin">
                    <label for="air_conditioning" class="col-sm-2 control-label">Кондинионер</label>
                    <select name="air_conditioning" id="air_conditioning" class="form-control">
                        <option value="0">--</option>
                        <option value="yes">Есть</option>
                        <option value="no">Нет</option>
                    </select>
                </div>
                <div class="form-group group-form-vin">
                    <label for="hydraulic_booster" class="col-sm-2 control-label">Гидроусилитель</label>
                    <select name="hydraulic_booster" id="hydraulic_booster" class="form-control">
                        <option value="0">--</option>
                        <option value="yes">Есть</option>
                        <option value="no">Нет</option>
                    </select>
                </div>
                <div class="form-group group-form-vin">
                    <label for="turbo" class="col-sm-2 control-label">Турбо</label>
                    <select name="turbo" id="turbo" class="form-control">
                        <option value="0">--</option>
                        <option value="yes">Есть</option>
                        <option value="no">Нет</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group capacity">
                    <input type="text" class="form-control" id="engine_capacity" placeholder="Объем двигателя" name="engine_capacity">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <select name="type_kpp" id="type_kpp" class="form-control">
                        <option value="0">Тип кпп</option>
                        <option value="1">Механическая</option>
                        <option value="2">Автоматическая</option>
                        <option value="3">Вариатор</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <textarea name="infa_dop" id="infa_dop" cols="10" rows="5" class="form-control" placeholder="Дополнительная информация"></textarea>
                </div>
            </div>
        </div>
        <h4>Названия или описания запчастей</h4>
        <div class="alert alert-warning warning-extended-search">
            <span class="close">X</span>
            <strong>Предупреждение!</strong> Нельзя удалять все строки.
        </div>
        <div class="row wrapper">
            <div class="description_parts abcd">
                <div class="col-md-4">
                    <div class="form-group">
                        <input type="text" name="part_name[]" placeholder="Наименование" class="form-control">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <input type="text" name="part_article[]" placeholder="Артикул" class="form-control">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <input type="text" name="part_count[]" placeholder="Кол-во" class="form-control">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <input type="text" name="part_note[]" placeholder="Примечание" class="form-control">
                    </div>
                </div>
                <div class="col-md-1 remove">
                                    <span id="remove" class="cursor">
                                        <i class="fa fa-minus" aria-hidden="true"></i>
                                    </span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                                <span id="add" class="cursor" data-toggle="tooltip" data-placement="bottom" title="Добавить новое описание запчастей">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                </span>
            </div>
        </div>
        <h4>Контактные данные</h4>
        <div class="alert alert-warning warning-extended-contact">
            <span class="close">X</span>
            <span class="block_vin"></span>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <input type="text" name="fio" id="fio" placeholder="Имя" class="form-control">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <input type="text" name="city" id="city" placeholder="Город" class="form-control">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <input type="text" name="phone" id="phone" placeholder="Контактный телефон" class="form-control bfh-phone"  data-format="+7 (ddd) ddd-dd-dd">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <input type="email" name="email" id="email" placeholder="e-mail" class="form-control">
                </div>
            </div>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" name="entity"> Юридическое лицо
            </label>
        </div>
        <button type="submit" class="btn btn-default btn-search">Отправить запрос</button>
    </form>
</div>