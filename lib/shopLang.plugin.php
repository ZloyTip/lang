<?php

class shopLangPlugin extends shopPlugin{

    private static $lang;
    private static $url;

    public static function currentLang()
    {
        if(empty(self::$lang)) {
            /**
             * @var shopPlugin $plugin
             */
            $plugin = wa('shop')->getPlugin('lang');
            $plugin->getSettings('main');
            $url = wa()->getConfig()->getCurrentUrl();

            foreach($plugin->getSettings('langs') as $lang) {
                if(preg_match('/^\/'.$lang.'\//', $url))
                    self::$lang = $lang;
            }

            if(empty(self::$lang)) {
                self::$lang = $plugin->getSettings('main');
            };
        }
        return self::$lang;
    }

    public static function getCurrency()
    {
        return self::currentLang() == 'ru' ? 'грн.' : 'uah';

    }

    public static function waUrl()
    {
        if(empty(self::$url)) {

            $plugin = wa('shop')->getPlugin('lang');
            if(self::currentLang() == $plugin->getSettings('main')) {
                self::$url = wa()->getUrl();
            } else {
                self::$url = wa()->getUrl().self::currentLang().'/';
            }
        }
        return self::$url;
    }


    public static function getAvailableLanguages()
    {
        static $options;
        if($options !== null) {
            return $options;
        }
        
        $langs = waLocale::getAll('info');

        $options = array();
        foreach ($langs as $code => $lang) {
            $code = substr($code, 0, 2);
            $options[$code] = ifempty($lang['name'],'').' ('.$code.')';
        }
        return $options;

    }

    /*
     * HOOK handlers
     */

    public function backendCategoryDialog($category)
    {
        $view = wa()->getView();
        $view->assign('category', $category);

        $plugin = wa('shop')->getPlugin('lang');
        $lang = $plugin->getSettings('langs');
        $view->assign('langs', $lang);

        $model = new shopLangPluginCategoryModel();
        $data = $model->getById($category['id']);
        $view->assign('data', $data);
        return $view->fetch('plugins/lang/templates/hooks/BackendCategoryDialog.html');
    }

    public function categorySave($category)
    {
        if(!empty($category['id'])) {
            $model = new shopLangPluginCategoryModel();
            $model->save($category['id'], waRequest::post('lang_plugin'));
        }
    }

    public function backendProduct($product) {
        $view = wa()->getView();
        $view->assign('product', $product);
        $html = $view->fetch('plugins/lang/templates/hooks/BackendProduct.html');
        return array('edit_section_li' => $html);
    }

    public function productSave($product)
    {
        if(!empty($product['data']) && !empty($product['data']['id'])) {
            $model = new shopLangPluginProductModel();
            $model->save($product['data']['id'], waRequest::post('lang_plugin'));
        }
    }

    /*
     * Frontend helpers
     */

    public static function frontendCategory(&$category)
    {
        $plugin = wa('shop')->getPlugin('lang');
        $main = $plugin->getSettings('main');
        if(self::currentLang() !== $main) {
            $model = new shopLangPluginCategoryModel();
            $data = $model->getById($category['id'], self::currentLang());
            $category = shopLangPluginHelper::prepareProduct($category, $data);
            shopLangPluginHelper::prepareMeta($data);
        }
    }
    public static function frontendCategoryList(&$category, $categories)
    {
        $plugin = wa('shop')->getPlugin('lang');
        $main = $plugin->getSettings('main');
        if(self::currentLang() !== $main) {
            $model = new shopLangPluginCategoryModel();
            $ids = array_keys($categories);
            $data = $model->getFromList($category['id'], $ids, self::currentLang());
            $category = shopLangPluginHelper::prepareProduct($category, $data);
        }
    }

    public static function frontendProduct(&$product, &$features, &$features_selectable)
    {
        $plugin = wa('shop')->getPlugin('lang');
        $main = $plugin->getSettings('main');
        if(self::currentLang() !== $main) {
            $model = new shopLangPluginProductModel();
            $data = $model->getById($product['id'], self::currentLang());
            $product = shopLangPluginHelper::prepareProduct($product, $data);

            $fm = new shopLangPluginFeatureModel();
            $product->features = $fm->getValues($product['id'], self::currentLang());

            if(!empty($features)) {
                $features = $fm->getFeatures($features, self::currentLang());
            }
            if(!empty($features_selectable)) {
                $features_selectable = $fm->getFeatures($features_selectable, self::currentLang());
            }

            shopLangPluginHelper::prepareMeta($data);
        }
    }

    public static function frontendProductList(&$product, $products)
    {
        $plugin = wa('shop')->getPlugin('lang');
        $main = $plugin->getSettings('main');
        if(self::currentLang() !== $main) {
            $model = new shopLangPluginProductModel();
            $product_ids = array_keys($products);
            $data = $model->getFromList($product['id'], $product_ids, self::currentLang());
            $product = shopLangPluginHelper::prepareProduct($product, $data);
        }
    }

    public static function frontendFeatureList(&$features)
    {
        $plugin = wa('shop')->getPlugin('lang');
        $main = $plugin->getSettings('main');
        if(self::currentLang() !== $main) {
            $fm = new shopLangPluginFeatureModel();
            $features = $fm->getFeatures($features, self::currentLang());
        }
    }


}