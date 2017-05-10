<?php
/*модуль дл€ разделов будет содеражать в себе статьи и блок навигации*/
class Catalog extends Module {

    public function __construct() {
        parent::__construct();
        //массив статей
        $this->add("id");
        $this->add("name", null, true);
        $this->add("date", null, true);
    }

    //объ€вл€ем tpl
    public function getTmplFile() {
        return "catalog";
    }

}

?>