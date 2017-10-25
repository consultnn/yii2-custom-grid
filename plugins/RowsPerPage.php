<?php

namespace consultnn\grid\plugins;

use consultnn\grid\GridView;

/**
 * Class RowsPerPage
 * @package consultnn\grid\plugins
 */
class RowsPerPage extends AbstractPlugin
{
    /**
     * Available number rows for select
     *
     * @var array $numberRows
     */
    public $numberRows = [20, 50, 100];

    /**
     * Url path for save selected count of rows
     * @var string $url
     */
    public $url;

    /**
     * @var SettingsStorageInterface $storage
     */
    public $storage;

    /**
     * @var string
     */
    public $storageId;
    
    /**
    * View file
    */
    public $viewFile = 'rows_per_page';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        if (!$this->viewFile) {
            $this->viewFile = 'rows_per_page.php';
        }

        if (empty($this->storageId)) {
            $this->storageId = $this->id . '-rows-per-page';
        }

        $this->grid->pluginSections['{rows_per_page}'] = $this;

        foreach ($this->numberRows as $key => $number) {
            $this->numberRows[$number] = $number;
            unset($this->numberRows[$key]);
        }

        $this->grid->on(GridView::EVENT_AFTER_INIT, [$this, 'initRowsPerPage']);

        parent::init();
    }

    /**
     * {@inheritdoc}
     * @throws \yii\base\InvalidParamException
     */
    public function run()
    {
        $this->registerClientScript();

        return $this->render($this->viewFile, [
            'numberRows' => $this->numberRows,
            'widget' => $this
        ]);
    }

    protected function initRowsPerPage()
    {
        $settings = \Yii::$app->user->getSettingsStorage()->get('company-rows-per-page');
        $pagination = $this->grid->dataProvider->getPagination();
        $pagination->setPageSize($settings['rows-per-page']);
    }

    protected function registerClientScript()
    {
        $view = $this->getView();
        $js = "$('.rows-per-page select').on('change', function(e) {
            $(this).parents('form').submit();
            e.preventDefault();
        });";
        $view->registerJs($js);
    }

    public function getRowsPerPage()
    {
        $settings = $this->storage->get($this->storageId);
        return $settings['rows-per-page'];
    }
}
