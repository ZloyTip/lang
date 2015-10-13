<?php


class shopLangPluginCategoryModel extends waModel {

    protected $table = 'shop_lang_plugin_category';
    protected static $_cache = array();

    public function save($category_id, $langs)
    {
        foreach ($langs as $lang => $data) {
            $data['id'] = $category_id;
            $data['lang'] = $lang;
            $this->insert($data, 1);
        }
    }

    public function getById($id, $lang = null)
    {
        if($lang) {
            return $this->query('SELECT l.* FROM shop_category c LEFT JOIN shop_lang_plugin_category l ON c.id = l.id AND lang = ? WHERE c.id = ?', $lang, $id)->fetch();
        }

        return $this->select('*')->where('id = ?', $id)->fetchAll('lang');
    }

    public function getFromList($id, $ids, $lang) {
        if(empty(self::$_cache[$id])) {
            $data = $this->query('SELECT c.id, l.name, l.meta_title, l.meta_keywords, l.meta_description, l.description
                FROM shop_category c LEFT JOIN shop_lang_plugin_category l ON c.id = l.id AND lang = ? WHERE c.id IN(?)', $lang, $ids)->fetchAll('id');
            self::$_cache += $data;
        }
        return self::$_cache[$id];
    }
}