<?php

class shopLangPluginProductSettingsAction extends waViewAction{

    public function execute()
    {
        $plugin = wa('shop')->getPlugin('lang');
        $lang = $plugin->getSettings('langs');
        $this->view->assign('langs', $lang);

        $model = new shopLangPluginProductModel();
        $data = $model->getById(waRequest::get('id', 0, waRequest::TYPE_INT));
        $this->view->assign('data', $data);
    }
}