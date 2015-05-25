<?php

namespace consultnn\grid;

use yii\base\Object;
use yii\base\UnknownPropertyException;
use yii\helpers\Html;
use yii\base\Model;

class Columns extends Object
{
    public $config = [];

    public $model;

    public static function editable()
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
    public static function all()
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

    public static function active(Model $model)
    {
        $ids = [];
        foreach (self::view($model) as $column) {
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

    public static function labels(Model $model)
    {
        $labels = [];
        foreach (self::view($model) as $column) {
            $labels[self::getId($column)] = self::getLabel($model, $column);
        }
        return $labels;
    }

    private static function getId($column)
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

    private static function getLabel(Model $model, $column)
    {
        if (is_string($column)) {
            $label = $model->getAttributeLabel($column);
        } elseif (isset($column['attribute'])) {
            $label = $model->getAttributeLabel($column['attribute']);
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
