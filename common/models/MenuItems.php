<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "{{%menu_items}}".
 *
 * @property integer $id
 * @property integer $menu_id
 * @property integer $parent_id
 * @property integer $link_type
 * @property string $title
 * @property string $icon
 * @property string $url
 * @property integer $show_child
 * @property integer $visible
 * @property string $active
 * @property integer $order
 * @property integer $author_id
 * @property integer $updater_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $status
 *
 * @property User $author
 * @property Menu $menu
 * @property MenuItems $parent
 * @property MenuItems[] $menuItems
 * @property User $updater
 */
class MenuItems extends \yii\db\ActiveRecord
{
    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE = 1;

    const CONTROLLER_LINK = 0;
    const OTHER_LINK = 1;

    const VISIBLE_ALL = 0;
    const VISIBLE_USERS = 1;
    const VISIBLE_MANAGER = 2;
    const VISIBLE_ADMINISTRATOR = 3;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%menu_items}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'author_id',
                'updatedByAttribute' => 'updater_id',
            ],
            'sortable' => [
                'class' => \kotchuprik\sortable\behaviors\Sortable::className(),
                'query' => self::find(),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['menu_id', 'link_type', 'title'], 'required'],
            [['menu_id', 'parent_id', 'link_type', 'show_child', 'visible', 'order', 'author_id', 'updater_id', 'created_at', 'updated_at', 'status', 'level', 'target'], 'integer'],
            ['parent_id', 'validateLevel'],
            ['parent_id', 'validateParents'],
            [['title', 'icon', 'url', 'active'], 'string', 'max' => 255],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['author_id' => 'id']],
            [['menu_id'], 'exist', 'skipOnError' => true, 'targetClass' => Menu::className(), 'targetAttribute' => ['menu_id' => 'id']],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => MenuItems::className(), 'targetAttribute' => ['parent_id' => 'id']],
            [['updater_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updater_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'menu_id' => Yii::t('backend', 'Menu'),
            'parent_id' => Yii::t('backend', 'Parent'),
            'link_type' => Yii::t('backend', 'Menu Item Type'),
            'title' => Yii::t('backend', 'Title'),
            'icon' => Yii::t('backend', 'Icon'),
            'url' => Yii::t('backend', 'Url'),
            'show_child' => Yii::t('backend', 'Show Child'),
            'visible' => Yii::t('backend', 'Visible for'),
            'active' => Yii::t('backend', 'Active Controller'),
            'order' => Yii::t('backend', 'Order'),
            'author_id' => Yii::t('backend', 'Author'),
            'updater_id' => Yii::t('backend', 'Updater'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated At'),
            'status' => Yii::t('backend', 'Published'),
            'level' => Yii::t('backend', 'Level'),
            'target' => Yii::t('backend', 'Open in ne window'),
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
     
            $this->level = $this->getItemLevel((int) $this->parent_id);

            if($insert) {
                $max = (int) self::find()->max('[[order]]');
                $this->order = $max + 1;
            }
            
            return true;
        }
        return false;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'author_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenu()
    {
        return $this->hasOne(Menu::className(), ['id' => 'menu_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(MenuItems::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChild()
    {
        return $this->hasMany(MenuItems::className(), ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdater()
    {
        return $this->hasOne(User::className(), ['id' => 'updater_id']);
    }

    public function getLinkTypes() {
        return [
               MenuItems::CONTROLLER_LINK => Yii::t('backend', 'Controller link'),
               MenuItems::OTHER_LINK => Yii::t('backend', 'Other link')
            ];
    }

    public function getLinkVisibilities() {
        return [
               MenuItems::VISIBLE_ALL => Yii::t('backend', 'Visible for all'),
               MenuItems::VISIBLE_USERS => Yii::t('backend', 'Visible for users'),
               MenuItems::VISIBLE_MANAGER => Yii::t('backend', 'Visible for mangers'),
               MenuItems::VISIBLE_ADMINISTRATOR => Yii::t('backend', 'Visible only for administrators')
            ];
    }

    public function getParentsList($menu_id = 0) {
        if(empty($menu_id)) {
            return MenuItems::find()->where(['OR', ['=', 'parent_id', 0], ['IS', 'parent_id', (new \yii\db\Expression('Null'))]]);
        } else {
            return MenuItems::find()->where(['OR', ['=', 'parent_id', 0], ['IS', 'parent_id', (new \yii\db\Expression('Null'))]])->andWhere(['menu_id' => $menu_id]);
        }
    }

    public function validateParents($attribute, $params)
    {
        if(!empty($this->id)) {
            if(!$this->canBeParent($this->$attribute, $this->id)) {
                $this->addError($attribute, Yii::t('backend', 'Item can\'t be descendant of its descendants'));
            }
        }
    }

    public function validateLevel($attribute, $params)
    {
        $this->level = $this->getItemLevel((int) $this->$attribute);

        $menu = Menu::findOne($this->menu_id);

        if($menu && $this->level > $menu->max_levels) {
            $this->addError($attribute, Yii::t('backend', 'Maximum nesting level for this menu is reached'));
        }
    }

    public function canBeParent($parent_id, $id, $item = null) {
        if(empty($id)) {
            return true;
        }
        
        if(empty($item)) {
            $item = $this;
        }

        if(empty($item->parent)) {
            return true;
        } else {
            if($item->parent->parent_id == $id) {
                return false;
            } else {
                return $this->canBeParent($item->parent->parent_id, $id, $item->parent);
            }
        }
    }

    public function getItemLevel($parent_id, $level = 1) {
        if(empty($parent_id)) {
            return $level;
        }

        $parent = MenuItems::findOne($parent_id);
        $level++;

        if(empty($parent) || empty($parent->parent_id)) {
            return $level;
        } else {
            return $this->getItemLevel($parent->parent_id, $level);
        }
    }

    /**
     * @inheritdoc
     * @return \common\models\query\MenuItemsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\MenuItemsQuery(get_called_class());
    }
}
