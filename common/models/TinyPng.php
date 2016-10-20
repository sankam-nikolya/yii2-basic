<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%tinypng_keys}}".
 *
 * @property integer $id
 * @property string $key
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $valid_from
 * @property integer $status
 */
class TinyPng extends \yii\db\ActiveRecord
{
    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tinypng_keys}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key'], 'required'],
            [['created_at', 'updated_at', 'status'], 'integer'],
            [['key'], 'string', 'max' => 254],
            ['valid_from', 'default', 'value' => function() { return date('d.m.Y 00:00:00');}],
            ['valid_from', 'filter', 'filter' => 'strtotime', 'skipOnEmpty' => true]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'key' => Yii::t('backend', 'Key'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated At'),
            'valid_from' => Yii::t('backend', 'Valid From'),
            'status' => Yii::t('backend', 'Published'),
        ];
    }


    /**
     * @inheritdoc
     * @return \common\models\query\TinyPngQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\TinyPngQuery(get_called_class());
    }
}
