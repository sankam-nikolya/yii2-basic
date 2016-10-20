<?php

namespace common\models\query;

use \common\models\TinyPng;

/**
 * This is the ActiveQuery class for [[\common\models\TinyPng]].
 *
 * @see \common\models\TinyPng
 */
class TinyPngQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere(['status' => TinyPng::STATUS_ACTIVE]);
        return $this;
    }

    public function not_active()
    {
        $this->andWhere(['status' => TinyPng::STATUS_NOT_ACTIVE]);
        return $this;
    }

    /**
     * @inheritdoc
     * @return \common\models\TinyPng[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\TinyPng|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
