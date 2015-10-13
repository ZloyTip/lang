<?php


class shopLangPluginFeatureModel extends waModel {

    protected $table = 'shop_lang_plugin_feature';

    public function getTree()
    {
        $res = array();
        $data = $this->getAll();
        foreach($data as $d) {
            if(empty($res[$d['lang']])) $res[$d['lang']] = array();
            if(empty($res[$d['lang']][$d['type']])) $res[$d['lang']][$d['type']] = array();

            $res[$d['lang']][$d['type']][$d['id']] = $d['value'];
        }
        return $res;
    }


    public function getFeatures($features, $lang)
    {
        $feature_ids = array();
        foreach($features as &$feature) {
            $feature_ids[] = $feature['id'];
            if(!empty($feature['values'])) {
                $value_ids = array_keys($feature['values']);
                $feature_values_tr = $this->select('id, value')->where('type = ? AND lang = ? AND id IN(?)', $feature['type'], $lang, $value_ids)->fetchAll('id', true);

                foreach($feature['values'] as $vid => $value) {
                    if(!empty($feature_values_tr[$vid])) {
                        $feature['values'][$vid] = $feature_values_tr[$vid];
                    }
                }
            }
        }
        if($feature_ids) {
            $tr = $this->where('type = "feature" AND lang = ? AND id IN (?)', $lang, $feature_ids)->fetchAll('id');

            foreach($features as &$feature) {
                if(!empty($tr[$feature['id']]) && !empty($tr[$feature['id']]['value']))
                    $feature['name'] = $tr[$feature['id']]['value'];
            }
        }
        return $features;
    }

    public function getValues($product_id, $lang, $sku_id = null, $type_id = null)
    {
        $sql = "SELECT ".($type_id ? 'tf.sort, ' : '')."f.code, f.type, f.multiple, pf.*
                FROM shop_product_features pf";
        $sql .= " JOIN shop_feature f ON (pf.feature_id = f.id)";
        if ($type_id) {
            $sql .= " LEFT JOIN shop_type_features tf ON ((tf.feature_id = IFNULL(f.parent_id,f.id)) AND (tf.type_id=i:type_id))";
        }
        $sql .= " WHERE pf.product_id = i:id AND ";
        if ($sku_id) {
            if ($sku_id > 0) {
                $sql .= '(pf.sku_id = i:sku_id OR pf.sku_id IS NULL) ORDER BY pf.sku_id';
            } else {
                $sql .= '(pf.sku_id = i:sku_id) ORDER BY pf.sku_id';
                $sku_id = -$sku_id;
            }
        } else {
            $sql .= 'pf.sku_id IS NULL';
        }

        if ($type_id) {
            $sql .= " ORDER BY tf.sort";
        }
        $features = $storages = array();
        $params = array(
            'id'      => $product_id,
            'sku_id'  => $sku_id,
            'type_id' => $type_id,
        );
        $data = $this->query($sql, $params);

        $result = array();
        foreach ($data as $row) {
            if ($sku_id && $row['code'] == 'weight' && !$row['sku_id']) {
                continue;
            }
            $features[$row['feature_id']] = array(
                'code'     => $row['code'],
                'multiple' => $row['multiple'],
            );
            if (preg_match('/^(.+)\.[0-2]$/', $row['code'], $matches)) {
                $result[$matches[1]] = null;
            } else {
                $result[$row['code']] = null;
            }
            $type = preg_replace('/\..*$/', '', $row['type']);
            if ($type == shopFeatureModel::TYPE_BOOLEAN) {
                /**
                 * @var shopFeatureValuesBooleanModel $model
                 */
                $model = shopFeatureModel::getValuesModel($type);
                $values = $model->getValues('id', $row['feature_value_id']);
                $result[$row['code']] = reset($values);
            } elseif ($type == shopFeatureModel::TYPE_DIVIDER) {
                /**
                 * @var shopFeatureValuesDividerModel $model
                 */
                $model = shopFeatureModel::getValuesModel($type);
                $values = $model->getValues('id', $row['feature_value_id']);
                $result[$row['code']] = reset($values);
            } else {
                if ($sku_id) {
                    $storages[$type][$row['feature_id']] = $row['feature_value_id'];
                } else {
                    $storages[$type][] = $row['feature_value_id'];
                }
            }

        }

        foreach ($storages as $type => $value_ids) {
            $model = shopFeatureModel::getValuesModel($type);
            $feature_values = $model->getValues('id', $value_ids);

            $feature_values_tr = $this->select('id, value')->where('type = ? AND lang = ? AND id IN(?)', $type, $lang, $value_ids)->fetchAll('id', true);


            foreach ($feature_values as $feature_id => $values) {
                foreach($values as $vid => $value) {
                    if(!empty($feature_values_tr[$vid])) {
                        $values[$vid] = $feature_values_tr[$vid];
                    }
                }
                if (isset($features[$feature_id])) {
                    $f = $features[$feature_id];
                    $result[$f['code']] = ($sku_id || empty($f['multiple'])) ? reset($values) : $values;
                } else {
                    //obsolete feature value
                }
            }
        }


        /**
         * composite fields workaround
         */
        $composite = array_filter(array_keys($result), create_function('$a', 'return preg_match("/\.0$/",$a);'));
        foreach ($composite as $code) {
            $code = preg_replace('/\.0$/', '', $code);
            $result[$code] = new shopCompositeValue($code, $result);
        }

        return $result;
    }
}