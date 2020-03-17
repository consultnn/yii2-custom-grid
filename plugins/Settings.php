<?php

namespace consultnn\grid\plugins;

use yii\grid\DataColumn;

class Settings extends AbstractPlugin
{
    const DATA_COLUMN_ID = 'column-id';
    const DATA_COLUMN_LABEL = 'column-label';

    /**
     * @var int
     */
    public $maxColumnsCount = 7;

    /**
     * @var string[]
     */
    public $columnLabels = [];

    /**
     * @var string
     */
    public $viewPath = 'settings.php';

    public $url;

    /**
     * @var SettingsStorageInterface
     */
    public $storage;

    /**
     * @var string[]
     */
    public $activeColumns;

    /** @var string[] */
    public $forceColumns;
    /**
     * @var string
     */
    public $storageId;

    /**
     * @inheritdoc
     */
    public function init()
    {
        if (empty($this->storageId)) {
            $this->storageId = $this->id . '-settings';
        }

        $this->grid->pluginSections['{settings}'] = $this;

        $this->initFromStorage();

        parent::init();
    }

    protected function initFromStorage()
    {
        if ($this->storage && $this->storage->has($this->storageId)) {
            $settings = $this->storage->get($this->storageId);
            if (isset($settings['columns'])) {
                $this->activeColumns = $settings['columns'];
            }
            $this->activeColumns = array_merge($this->activeColumns, $this->forceColumns ?: []);
        }
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

                    if (is_array($this->activeColumns) && !in_array($id, $this->activeColumns)) {
                        unset($this->grid->columns[$key]);
                    }
                }
            }

            if (!is_array($this->activeColumns)) {
                $this->activeColumns = array_keys($this->columnLabels);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->initColumns();
        $this->registerClientScript();

        return $this->render($this->viewPath, ['widget' => $this]);
    }

    protected function registerClientScript()
    {
        $view = $this->getView();

        $js = "$('#{$this->id} .settings-button').on('click', function(event){
            $('#{$this->id} .settings').toggle(true);
            event.preventDefault();
            setMaxColumns();
        });

        $('#{$this->id} .close').on('click', function() {
            $('#{$this->id} .settings').toggle(false)
        });

        var checkboxSelector = '#{$this->id} .columns-setting input[type = checkbox]';
        $(checkboxSelector).on('change', function(event){
            setMaxColumns();
        })

        function setMaxColumns() {
            if ($(checkboxSelector+':checked').length >= {$this->maxColumnsCount}) {
                $(checkboxSelector+':not(:checked)').prop( 'disabled', true );
            } else {
                $(checkboxSelector+':not(:checked)').prop( 'disabled', false );
            }
        }
        ";

        $view->registerJs($js);
    }

}