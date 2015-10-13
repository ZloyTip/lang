<?php
return array(
    'shop_lang_plugin_category' => array(
        'id' => array('int', 11, 'null' => 0),
        'lang' => array('varchar', 2, 'null' => 0, 'default' => ''),
        'name' => array('varchar', 255),
        'meta_title' => array('varchar', 255),
        'meta_keywords' => array('text'),
        'meta_description' => array('text'),
        'description' => array('text'),
        ':keys' => array(
            'PRIMARY' => array('id', 'lang'),
        ),
    ),
    'shop_lang_plugin_feature' => array(
        'id' => array('int', 11, 'null' => 0),
        'lang' => array('varchar', 2, 'null' => 0, 'default' => ''),
        'type' => array('varchar', 255, 'null' => 0, 'default' => ''),
        'value' => array('varchar', 255, 'null' => 0, 'default' => ''),
        ':keys' => array(
            'PRIMARY' => array('id', 'lang', 'type'),
        ),
    ),
    'shop_lang_plugin_product' => array(
        'id' => array('int', 11, 'null' => 0),
        'lang' => array('varchar', 2, 'null' => 0, 'default' => ''),
        'name' => array('varchar', 255),
        'summary' => array('text'),
        'meta_title' => array('varchar', 255),
        'meta_keywords' => array('text'),
        'meta_description' => array('text'),
        'description' => array('text'),
        ':keys' => array(
            'PRIMARY' => array('id', 'lang'),
        ),
    ),
);