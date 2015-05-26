<?php

namespace consultnn\grid;

class GridView extends \yii\grid\GridView
{

    public $plugins = [];

    /**
     * @var Columns
     */
    public $columnsLoader;

    public function init()
    {
        parent::init();
    }

    public function initColumns()
    {
        if (empty($this->columns) && !empty($this->columnsLoader)) {
            $this->columns = $this->columnsLoader->all();
        }
        parent::initColumns();
    }



}