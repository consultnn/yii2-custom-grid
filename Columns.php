<?php

namespace consultnn\grid;

use yii\base\Object;
use yii\base\UnknownPropertyException;
use yii\helpers\Html;

class Columns extends Object
{
    public $config = [];

    /**
     * @var \yii\base\Model
     */
    public $model;

    public function editable()
    {
        return [

        ];
    }

    private static function activeTextInput()
    {
        return function ($model, $key, $index, $column) {
            return Html::activeTextInput($model, "[{$key}]{$column->attribute}");
        };
    }



    /**
     * @return array
     * @throws \Exception
     */
    public function all()
    {
        return array_merge(
            self::view(),
            self::action()
        );
    }

    /**
     * @return array
     * @throws \Exception
     */
    private function view()
    {
        return ;
    }

    public function active()
    {
        $ids = [];
        foreach (self::view() as $column) {
            if (!isset($column['visible']) || (isset($column['visible']) && $column['visible'])) {
                $ids[] = self::getId($column);
            }
        }
        return $ids;
    }

    private function action()
    {
        return [];
    }

    public function labels()
    {
        $labels = [];
        foreach (self::view() as $column) {
            $labels[self::getId($column)] = self::getLabel($this->model, $column);
        }
        return $labels;
    }

    private function getId($column)
    {
        if (is_string($column)) {
            $id = $column;
        } elseif (isset($column['attribute'])) {
          $id = $column['attribute'];
        } elseif (isset($column['headerOptions']['data-column-id'])) {
            $id = $column['headerOptions']['data-column-id'];
        } else {
            throw new UnknownPropertyException('column must have identifier');
        }

        return $id;
    }

    private function getLabel($column)
    {
        if (is_string($column)) {
            $label = $this->model->getAttributeLabel($column);
        } elseif (isset($column['attribute'])) {
            $label = $this->model->getAttributeLabel($column['attribute']);
        } elseif (isset($column['label'])) {
            $label = $column['label'];
        } elseif (isset($column['header'])) {
            $label = $column['header'];
        } else {
            throw new UnknownPropertyException('column must have label');
        }

        return $label;
    }


}
