<?php


class shopLangPluginProductModel extends waModel {

    protected $table = 'shop_lang_plugin_product';
    protected static $_cache = array();

    public function save($product_id, $langs)
    {
		if(is_array($langs))
        foreach ($langs as $lang => $data) {
            $data['id'] = $product_id;
            $data['lang'] = $lang;
            $this->insert($data, 1);
        }
    }

    public function getById($id, $lang = null)
    {
        if($lang) {
            return $this->query('SELECT l.* FROM shop_product p LEFT JOIN shop_lang_plugin_product l ON p.id = l.id AND lang = ? WHERE p.id = ?', $lang, $id)->fetch();
        }

        return $this->select('*')->where('id = ?', $id)->fetchAll('lang');
    }

    public function getFromList($id, $ids, $lang) {
        if(empty(self::$_cache[$id])) {
            $data = $this->query('SELECT p.id, l.name, l.summary, l.meta_title, l.meta_keywords, l.meta_description, l.description
                FROM shop_product p LEFT JOIN shop_lang_plugin_product l ON p.id = l.id AND lang = ? WHERE p.id IN(?)', $lang, $ids)->fetchAll('id');
            self::$_cache += $data;
        }
        return self::$_cache[$id];
    }
}