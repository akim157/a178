<?php
/*������ ��� �������� ����� ���������� � ���� ������ � ���� ���������*/
class Catalog extends Module {

    public function __construct() {
        parent::__construct();
        //������ ������
        $this->add("id");
        $this->add("name", null, true);
        $this->add("date", null, true);
    }

    //��������� tpl
    public function getTmplFile() {
        return "catalog";
    }

}

?>