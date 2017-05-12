<?php
/*����� ������ ��� ������ � �������� ������ � �������� �������� ������� ObjectDB � AbstractObjectDB*/
class CatalogDB extends ObjectDB {
    /*������ ����� ��������� �������� �������*/
    protected static $table = "marka";

    public function __construct() {
        /*
            ���������� � ������������� ������ ObjectDB � �������� ��������, �������� �������
            � �� � ���� ������� ���������� � ���� AbstractObjectDB ��� ����� ��������� � ������������ � �������� ���������
            �������� ������� � ������ ���� �� config
        */
        parent::__construct(self::$table);
        /*
            ���������� � ���� AbstractObjectDB ����� ������������ ����� ObjectDB
            � ������ � ������ add, ������� ��������� �������� � ������
            ������ ���������� ���� �������� �������, � ������ ��������� ��� �������� ������
            �������������� ����������� �������� ��� � �������� �� ���������
        */
        $this->add("id", "ValidateID");
        $this->add("name", "ValidateText");
        /*3 ���������� �� ������� ��������� ��� ��� ���� � ���� �� ��������� �� AbstractObjectDB*/
        $this->add("date", "ValidateDate", self::TYPE_TIMESTAMP, $this->getDate());
    }
    //��������� ������� ����� �������������
    protected function postInit() {
        /*����� ������������� ������ ���� � �������� � �� ������ ���� �������� ��� ��� ��������� � �������*/
        //�������� ���� �� null � ������� ��������� �� �������������
        if (!is_null($this->img)) $this->img = Config::DIR_IMG_ARTICLES.$this->img;
        //������������ ������
        //������ �������� ��������(�����), ������ �������� (�� ��������� Main)
        //������ � �������� id ���������� �������� id ������
        //������: /article?id=5
        $this->link = URL::get("marki", "", array("id" => $this->id));
        return true;
    }

    protected function postLoad() {
        $this->postHandling();
        return true;
    }
    //����� ��� ��������� ���� �������
    public static function getAll($getObj = true, $post_handling = false){
        $select = self::getBaseSelect();
        $catalog = self::$db->select($select);
        if($getObj) $catalog = ObjectDB::buildMultiple(__CLASS__, $catalog);
        if ($post_handling) foreach ($catalog as $article) $article->postHandling();
        return $catalog;
    }
    public static function getAllShowSearch(){
        $select = self::getBaseSelect();
        $select->order("name", true);
        $data = self::$db->select($select);
        $articles = ObjectDB::buildMultiple(__CLASS__, $data);
        return $articles;
    }
    //����� ����� �������� ��� ������ �� �����
    public static function getAllShow($count = false, $offset = false, $post_handling = false) {
        /*
            count - ���������� ������ ��� �������
            offset - ��������
            post_handling - post ���������, ��� ������ ����� �� ��������� ������ � �� ���������� ��� ������ ���� �� ������� �� ���
        */
        //���������� � ������ ����� �������� �������������� ������
        $select = self::getBaseSelect();
        //��������� � ���� ���������� �� ����
        $select->order("date", false);
        //�������� ���� �������� true �� ��������� limit
        if ($count) $select->limit($count, $offset);
        //���������� � �������� AbstractObjectDB � ��� ��������������� ������� ��
        //� ����� ���������� � ������ Select � ��� ������ select � ����������� ��������������� �������
        //� �������� ������ �� �� �������� ������ ������
        $data = self::$db->select($select);
        //���������� � ������ ��� �������� �� ������� ������ � ������ ��������
        $articles = ObjectDB::buildMultiple(__CLASS__, $data);
        //���� �������� true ���������� � ��������
        if ($post_handling) foreach ($articles as $article) $article->postHandling();
        return $articles;
    }
    //����� ��� ��������� ������ ������������� � ������������� �������
    public static function getAllOnPageAndSectionID($section_id, $count, $offset = false) {
        /*
            section_id - �������� id ������ �������
            count - ���������� ������ ��� �������
            offset - �������������� �������� ��� limit
        */
        //� ������� ������ ��������� ������
        $select = self::getBaseSelect();
        //��������� � ���� ��������� �� ����
        //������� �� id ������
        //� �����
        $select->order("date", false)
            ->where("`section_id` = ".self::$db->getSQ(), array($section_id))
            ->limit($count, $offset);
        //�������� ������ �� ��
        $data = self::$db->select($select);
        //���������� ������ ������ � ������ ��������
        $articles = ObjectDB::buildMultiple(__CLASS__, $data);
        foreach ($articles as $article) $article->postHandling();
        return $articles;
    }
    //�������� ��� ������ �� ���������
    public static function getAllOnSectionID($section_id, $order = false, $offset = false) {
        return self::getAllOnSectionOrCategory("section_id", $section_id, $order, $offset);
    }

    public static function getAllOnCatID($cat_id, $order = false, $offset = false) {
        return self::getAllOnSectionOrCategory("cat_id", $cat_id, $order, $offset);
    }
    //�������� ��� ������ �� ���������
    private static function getAllOnSectionOrCategory($field, $value, $order, $offset) {
        $select = self::getBaseSelect();
        $select->where("`$field` = ".self::$db->getSQ(), array($value))
            ->order("date", $order);
        $data = self::$db->select($select);
        $articles = ObjectDB::buildMultiple(__CLASS__, $data);
        return $articles;
    }
    //���������� ������
    public function loadPrevArticle($article_db) {
        /*article_db - �������� ������ ��������� ������*/
        //�������� ������
        $select = self::getBaseNeighbourSelect($article_db);
        //�������
        $select->where("`id` < ".self::$db->getSQ(), array($article_db->id))
            ->order("date", false);
        //�������� ������
        $row = self::$db->selectRow($select);
        //������������� ������
        return $this->init($row);
    }
    //��������� ������
    public function loadNextArticle($article_db) {
        $select = self::getBaseNeighbourSelect($article_db);
        $select->where("`id` > ".self::$db->getSQ(), array($article_db->id));
        $row = self::$db->selectRow($select);
        return $this->init($row);
    }

    public function search($words) {
        $select = self::getBaseSelect();
        $articles = self::searchObjects($select, __CLASS__, array("title", "full"), $words, Config::MIN_SEARCH_LEN);
        foreach ($articles as $article) $article->setSectionAndCategory();
        return $articles;
    }
    //�������� �������� ������
    private static function getBaseNeighbourSelect($article_db) {
        $select = self::getBaseSelect();
        $select->where("`cat_id` = ".self::$db->getSQ(), array($article_db->cat_id))
            ->order("date")
            ->limit(1);
        return $select;
    }
    //����� ��������� ����� ������ ���� ������ � �������� ��� � ���� �������� ��������
    protected function preValidate() {
        //���� �������� �� null
        //basename - ���������� ��������� ��������� ����� �� ���������� ����
        if (!is_null($this->img)) $this->img = basename($this->img);
        return true;
    }
    /*����� ��� ��������� ����� ������ �� ��*/
    private static function getBaseSelect() {
        //������� ������ select � �������� ���������� ������ ��
        $select = new Select(self::$db);
        //��������� ������ ��� ����� ��� ������ ��������
        $select->from(self::$table, "*");
        //��������� �������������� ������
        return $select;
    }

    private function setSectionAndCategory() {
        //������� ������
        $section = new SectionDB();
        //��������� �� �������� �� �������� ������� articledb � sectiondb
        $section->load($this->section_id);
        //������� ������
        $category = new CategoryDB();
        //��������� ���������
        $category->load($this->cat_id);
        //���� ������� ���������� � �� �� ���������� � ������
        if ($section->isSaved()) $this->section = $section;
        if ($category->isSaved()) $this->category = $category;

    }

    private function postHandling() {
        //��������� �� ���������
        $this->setSectionAndCategory();
        //��������� ���������� ������������
        $this->count_comments = CommentDB::getCountOnArticleID($this->id);
        //���� ������ ����������
        $this->day_show = ObjectDB::getDay($this->date);
        //����� ����������
        $this->month_show = ObjectDB::getMonth($this->date);
    }

}

?>