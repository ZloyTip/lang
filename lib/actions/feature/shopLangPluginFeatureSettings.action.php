<?php


class shopLangPluginFeatureSettingsAction extends waViewAction {

    public function execute()
    {
        $this->setLayout(new shopBackendLayout());

        if($data = waRequest::post('lang_plugin')) {
            $this->save($data);
        }

        $plugin = wa('shop')->getPlugin('lang');
        $lang = $plugin->getSettings('langs');
        $this->view->assign('langs', $lang);

        $fm = new shopFeatureModel();
        $features = $fm->getAll();
        $features = $fm->getValues($features, true);
        $this->view->assign('features', $features);


        $lm = new shopLangPluginFeatureModel();
        $data = $lm->getTree();
        $this->view->assign('data', $data);

    }

    protected function save($data)
    {
        $model = new shopLangPluginFeatureModel();
        foreach($data as $lang => $dat)
            foreach($dat as $type => $da)
                foreach($da as $id => $value)
                    $model->insert(array(
                        'id' => $id,
                        'lang' => $lang,
                        'type' => $type,
                        'value' => $value
                    ), 1);

    }

}