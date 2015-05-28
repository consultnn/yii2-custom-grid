<?php

namespace consultnn\grid\plugins;

use yii\grid\DataColumn;

class Settings extends AbstractPlugin
{
    const DATA_COLUMN_ID = 'column-id';
    const DATA_COLUMN_LABEL = 'column-label';

    public $columnLabels = [];

    public $viewPath = 'settings.php';

    public $url;

    public $settings;

    public $activeColumns = [];


    public function init()
    {
        $this->grid->pluginSections['{settings}'] = $this;

        if (isset($this->settings['columns'])) {
            $this->activeColumns = $this->settings['columns'];
        }

        parent::init();
    }

    public function initColumns()
    {
        if (empty($this->columnLabels)) {
            foreach ($this->grid->columns as $key => $column) {
                $id = $label = null;
                if (($column instanceof DataColumn)) {
                    /** @var \yii\grid\DataColumn $column */
                    $oldSortable = $column->enableSorting;
                    $column->enableSorting = false;
                    $method = new \ReflectionMethod($column::className(), 'renderHeaderCellContent');
                    $method->setAccessible(true);
                    $label = $method->invoke($column);
                    $id = $column->attribute;
                    $column->enableSorting = $oldSortable;
                }

                if (isset($column->headerOptions['data'][self::DATA_COLUMN_ID])) {
                    $id = $column->headerOptions['data'][self::DATA_COLUMN_ID];
                }

                if (isset($column->headerOptions['data'][self::DATA_COLUMN_LABEL])) {
                    $label = $column->headerOptions['data'][self::DATA_COLUMN_LABEL];
                } elseif (empty($label)) {
                    if (!empty($column->label)) {
                        $label = $column->label;
                    } elseif (!empty($column->header)) {
                        $label = $column->header;
                    }
                }

                if (!empty($id) && !empty($label)) {
                    $this->columnLabels[$id] = $label;

                    if (!empty($this->activeColumns) && !in_array($id, $this->activeColumns)) {
                        unset($this->grid->columns[$key]);
                    }
                }
            }
        }

        if (empty($this->activeColumns)) {
            $this->activeColumns = array_keys($this->columnLabels);
        }
    }

    public function run()
    {
        $this->initColumns();
        $this->registerClientScript();
        return $this->render($this->viewPath, ['widget' => $this]);
    }

    protected function registerClientScript()
    {
        $view = $this->getView();

        $js = "jQuery('#{$this->grid->id} #column-settings-button').on('click', function(event){
            $('#column-settings').toggle();
            event.preventDefault();
        });";
        $view->registerJs($js);
    }

}