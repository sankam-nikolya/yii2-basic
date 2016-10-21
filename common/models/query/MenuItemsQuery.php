<?php

namespace common\models\query;

use \common\models\MenuItems;

/**
 * This is the ActiveQuery class for [[\common\models\MenuItems]].
 *
 * @see \common\models\MenuItems
 */
class MenuItemsQuery extends \yii\db\ActiveQuery
{
    public function only_users()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->andWhere(['author_id' => Yii::$app->user->id]);
        }
    }

    public function active()
    {
        $this->andWhere(['status' => MenuItems::STATUS_ACTIVE]);
        return $this;
    }

    public function not_active()
    {
        $this->andWhere(['status' => MenuItems::STATUS_NOT_ACTIVE]);
        return $this;
    }

    /**
     * @inheritdoc
     * @return \common\models\MenuItems[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\MenuItems|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
