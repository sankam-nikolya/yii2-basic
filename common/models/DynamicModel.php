<?php

namespace common\models;

class DynamicModel extends \yii\base\DynamicModel
{
    private $_attributeLabels;

    public function defineAttribute($name, $value = null)
    {
        $this->_attributes[$name] = $value;
        return $this;
    }

    public function defineLabel($name, $label)
    {
        $this->_attributeLabels[$name] = $label;
    }

    public function attributeLabels()
    {
        return $this->_attributeLabels;
    }
}
