<?php

class shopLangPluginHelper {


    public static function prepareProduct($product, $translate)
    {
        if($translate) {
            if(is_array($product)) {
                $product['name'] = $translate['name'];
                $product['summary'] = $translate['summary'];
                $product['meta_title'] = $translate['meta_title'];
                $product['meta_keywords'] = $translate['meta_keywords'];
                $product['meta_description'] = $translate['meta_description'];
                $product['description'] = $translate['description'];
            } elseif ($product instanceof shopProduct) {
                $product->name = $translate['name'];
                $product->summary = $translate['summary'];
                $product->meta_title = $translate['meta_title'];
                $product->meta_keywords = $translate['meta_keywords'];
                $product->meta_description = $translate['meta_description'];
                $product->description = $translate['description'];
            }
        }

        return $product;
    }



    public static function prepareCategory($category, $translate)
    {
        if($translate) {
            if(is_array($category)) {
                $category['name'] = $translate['name'];
                $category['meta_title'] = $translate['meta_title'];
                $category['meta_keywords'] = $translate['meta_keywords'];
                $category['meta_description'] = $translate['meta_description'];
                $category['description'] = $translate['description'];
            }
        }
        return $category;
    }

    public static function prepareMeta($translate)
    {
        wa()->getResponse()->setTitle($translate['meta_title']);
        wa()->getResponse()->setMeta('keywords', $translate['meta_keywords']);
        wa()->getResponse()->setMeta('description', $translate['meta_description']);
    }
}