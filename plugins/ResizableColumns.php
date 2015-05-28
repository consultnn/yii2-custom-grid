<?php

namespace consultnn\grid\plugins;

use consultnn\grid\GridView;
use consultnn\grid\plugins\assets\ResizableColumnsAsset;
use yii\helpers\Json;
use yii\web\JsExpression;

class ResizableColumns extends AbstractPlugin
{
    const DATA_COLUMNS_ID = 'resizable-columns-id';
    const DATA_COLUMN_ID = 'resizable-column-id';
    const STORAGE_LOCAL = 'local';

    public $storage = self::STORAGE_LOCAL;

    private $_selector ;

    /**
     * @var array widget plugin options
     */
    public $pluginOptions = [];

    public $pluginEvents = [];

    public function init()
    {
        if (!isset($this->grid->tableOptions['data'][self::DATA_COLUMNS_ID])) {
            $this->grid->tableOptions['data'][self::DATA_COLUMNS_ID] = $this->getId();
        }
        $this->_selector = 'table[data-'.self::DATA_COLUMNS_ID."=\"{$this->id}\"]";
        parent::init();

        $this->grid->on(GridView::EVENT_AFTER_INIT, [$this, 'addColumnsId']);
        $this->grid->on(GridView::EVENT_AFTER_RUN, [$this, 'run']);
    }

    public function addColumnsId()
    {
        foreach ($this->grid->columns as $column) {
            /** @var \yii\grid\Column $column */
            if (!isset($column->headerOptions['data'][self::DATA_COLUMN_ID]) && isset($column->attribute)) {
                $column->headerOptions['data'][self::DATA_COLUMN_ID] = $column->attribute;
            }
        }
    }

    public function run()
    {
        $this->registerClientScript();
        parent::run();
    }

    protected function registerClientScript()
    {
        $view = $this->getView();
        $asset = ResizableColumnsAsset::register($view);

        switch ($this->storage) {
            case self::STORAGE_LOCAL:
                $asset->useLocalStorage = true;
                if (!isset($this->pluginOptions['store'])) {
                    $this->pluginOptions['store'] = new JsExpression('store');
                }
                break;
        }

        $options = empty($this->pluginOptions) ? '' : Json::encode($this->pluginOptions);
        $js = "jQuery('{$this->_selector}').resizableColumns({$options});";
        $view->registerJs($js);
    }

}