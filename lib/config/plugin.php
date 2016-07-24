<?php


return array(
    'name' => 'Lang',
    'description' => 'lang',
    'vendor'=>'972539',
    'version'=>'1.0.0',
    'img'=>'img/lang.png',
    'settings' => true,
	'handlers'=> array(
        'backend_product' => 'backendProduct',
        'product_save' => 'productSave',
        'backend_category_dialog' => 'backendCategoryDialog',
        'category_save' => 'categorySave'
    ),

);
