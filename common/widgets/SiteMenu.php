<?php
namespace common\widgets;

use common\models\Menu;
use common\models\MenuItems;
use yii\base\InvalidConfigException;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Class DbMenu
 * Usage:
 * echo common\widgets\SiteMenu::widget([
 *      'menu'=>'stored-menu-key',
 *      ... other options from \yii\widgets\Menu
 * ])
 * @package common\widgets
 */
class SiteMenu extends \yii\widgets\Menu
{

    /**
     * @var string Menu to find menu
     */
    public $menu;

    /**
     * @var string
     */
    public $linkTemplate = "<a href=\"{url}\"{linkOptions}>{icon}{label}{right-icon}{badge}</a>";
    /**
     * @var string
     */
    public $labelTemplate = "{icon}{label}{badge}";

    /**
     * @var string
     */
    public $badgeTag = 'span';
    /**
     * @var string
     */
    public $badgeClass = 'label pull-right';
    /**
     * @var string
     */
    public $badgeBgClass;

    /**
     * @var string
     */
    public $parentRightIcon = '<b class="caret"></b>';

    /**
     * @var integer
     */
    private $max_level = 1;

    /**
     * @var string
     */
    private $show_icons = false;

    public function init()
    {
        $cacheKey = [
            Menu::className(),
            $this->menu
        ];
        $this->items = Yii::$app->cache->get($cacheKey);
        if ($this->items === false) {

            $this->items = $this->getMenuTree($this->menu);
            
            Yii::$app->cache->set($cacheKey, $this->items, Yii::$app->keyStorage->get('frontend.widget.cachetime', 60*60*24));
        }
    }

    private function getMenuTree($menu_id) {
        $menu = Menu::findOne(['slug'=>$menu_id]);

        if(!$menu || $menu->status == Menu::STATUS_NOT_ACTIVE) {
            return false;
        }

        $this->max_level = (int) $menu->max_levels;
        $this->show_icons = boolval($menu->icons);

        $items = MenuItems::find()->where(['menu_id' => $menu->id, 'level' => 1])->active()->orderBy(['order' => SORT_ASC])->all();

        return $this->buildMenuTree($items);
    }

    private function buildMenuTree($items, $level = 1) {

        if($level > $this->max_level) {
            return false;
        }

        $results = [];

        foreach ($items as $key => $item) {

            if($item->status == MenuItems::STATUS_ACTIVE) {
                $result = [];
                $linkOptions = [];

                $result['label'] = $item->title;

                if($item->link_type == MenuItems::CONTROLLER_LINK) {
                    $result['url'] = [$item->url];

                    switch ($item->visible) {
                        case MenuItems::VISIBLE_USERS:
                            $result['visible'] = 'administrator';
                            break;
                        case MenuItems::VISIBLE_MANAGER:
                            $result['visible'] = 'manager';
                            break;
                        case MenuItems::VISIBLE_ADMINISTRATOR:
                            $result['visible'] = 'user';
                            break;
                        case MenuItems::VISIBLE_ALL:
                        default:
                            break;
                    }

                    $result['active'] = $item->active;
                } else {
                    $result['url'] = $item->url;
                }

                if($this->show_icons && !empty($item->icon)) {
                    $result['icon'] = $item->icon;
                }

                if($item->target == 1) {
                    $linkOptions['target'] = '_blank';
                    $result['linkOptions'] = $linkOptions;
                }

                if($item->show_child == 1) {
                    $children = $item->child;

                    if(!empty($children)) {
                        $result['url'] = '#';
                        $result['options'] = ['class'=>'dropdown'];

                        $linkOptions['class'] = 'dropdown-toggle';
                        $linkOptions['data-toggle'] = 'dropdown';
                        $linkOptions['role'] = 'button';

                        $result['linkOptions'] = $linkOptions;

                        $child_items = $this->buildMenuTree($children, $level++);

                        if(!empty($child_items)) {
                            $result['items'] = $child_items;
                        }

                    }
                }

                $results[] = $result;
            }
        }

        return $results;
    }

    /**
     * @inheritdoc
     */
    protected function renderItem($item)
    {
        $item['badgeOptions'] = isset($item['badgeOptions']) ? $item['badgeOptions'] : [];

        if (!ArrayHelper::getValue($item, 'badgeOptions.class')) {
            $bg = isset($item['badgeBgClass']) ? $item['badgeBgClass'] : $this->badgeBgClass;
            $item['badgeOptions']['class'] = $this->badgeClass.' '.$bg;
        }

        if (isset($item['items']) && !isset($item['right-icon'])) {
            $item['right-icon'] = $this->parentRightIcon;
        }

        if (isset($item['url'])) {
            $template = ArrayHelper::getValue($item, 'template', $this->linkTemplate);

            return strtr($template, [
                '{badge}'=> isset($item['badge'])
                    ? Html::tag(
                        'span',
                        Html::tag('small', $item['badge'], $item['badgeOptions']),
                        ['class' => 'pull-right']
                    )
                    : '',
                '{icon}'=>isset($item['icon']) ? $item['icon'] : '',
                '{right-icon}'=>isset($item['right-icon']) ? $item['right-icon'] : '',
                '{url}' => Url::to($item['url']),
                '{label}' => $item['label'],
                '{linkOptions}' => isset($item['linkOptions']) ? Html::renderTagAttributes($item['linkOptions']) : '',
            ]);
        } else {
            $template = ArrayHelper::getValue($item, 'template', $this->labelTemplate);
            
            return strtr($template, [
                '{badge}'=> isset($item['badge'])
                    ? Html::tag('small', $item['badge'], $item['badgeOptions'])
                    : '',
                '{icon}'=>isset($item['icon']) ? $item['icon'] : '',
                '{right-icon}'=>isset($item['right-icon']) ? $item['right-icon'] : '',
                '{label}' => $item['label'],
            ]);
        }
    }

    protected function isItemActive($item)
    {
        if (isset($item['active'])) {
            $item['active'] = Yii::$app->controller->id == $item['active'];
        }

        return parent::isItemActive($item);
    }

    protected function normalizeItems($items, &$active)
    {
        foreach ($items as $i => $item) {
            if (isset($item['visible']) && !Yii::$app->user->can($item['visible'])) {
                unset($items[$i]);
                continue;
            }
        }

        return parent::normalizeItems($items, $active);
    }
}
