<?php

return array(
    'main' =>  array(
        'title' => 'Main language',
        'description' => '2-letters code',
        'value' => 'ru',
        'control_type' => waHtmlControl::SELECT,
        'options_callback' => 'shopLangPlugin::getAvailableLanguages',
    ),
    'langs' => array(
        'title' => 'Available translations',
        'description' => '',
        'value' => array(
            'en', 'uk'
        ),
        'options_callback' => 'shopLangPlugin::getAvailableLanguages',
        'control_type' => waHtmlControl::GROUPBOX
    )
);