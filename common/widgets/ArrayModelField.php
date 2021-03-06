<?php
/**
 * ArrayField class file.
 * @copyright (c) 2016, Pavel Bariev
 * @license http://www.opensource.org/licenses/bsd-license.php
 */

namespace common\widgets;


use yii\helpers\Html;
use yii\widgets\InputWidget;

/**
 * Description.
 *
 * Usage:
 * @author Pavel Bariev <bariew@yandex.ru>
 *
 */
class ArrayModelField extends InputWidget
{
    /**
     * @var \yii\db\ActiveRecord
     */
    public $model;
    public $form;
    /** @var  \yii\db\ActiveQuery */
    protected $relation;
    public $viewName;
    public $items;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->relation = $this->model->getRelation($this->attribute);
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $result = [];
        $class = $this->relation->modelClass;
        $form = $this->form;
        $template =  '<div class="template">'.$this->render($this->viewName, [
            'model' => new $class(), 'form' => $this->form, 'index' => 'myindex'
        ]).'</div>';
        $items = $this->items ?: $this->relation->all();
        foreach ($items as $index => $model) {
            $result[] = $this->render($this->viewName, compact('index', 'model', 'form'));
        }
        $result[] = Html::a(\Yii::t('app', ' Add'), '#', [
            'class' => 'btn btn-success glyphicon glyphicon-plus',
            'template' => $template,
            'onclick' => '
                $(this).before($(this).attr("template").replace(/myindex/g, "new-"+Date.now())); return false;'
        ]);
        return implode('', $result);
    }
}