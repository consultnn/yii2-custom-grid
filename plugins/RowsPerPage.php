<?php

namespace consultnn\grid\plugins;

use consultnn\grid\GridView;
use yii\base\Exception;
use yii\helpers\ArrayHelper;

/**
 * Class RowsPerPage
 * @package consultnn\grid\plugins
 */
class RowsPerPage extends AbstractPlugin
{
    public $viewPath = 'rows_per_page.php';

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
     * Storage key for save plugin settings
     *
     * @var string
     */
    public $storageId;

    /**
     * {@inheritdoc}
     * @throws Exception
     */
    public function init()
    {
        if (!$this->storageId) {
            $this->storageId = $this->id . '-rows-per-page';
        }

        if (!$this->storage) {
            throw new Exception('Storage not initialized');
        }

        $this->grid->pluginSections['{rows_per_page}'] = $this;

        $this->numberRows = array_combine($this->numberRows, $this->numberRows);

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

        return $this->render($this->viewPath, [
            'numberRows' => $this->numberRows,
            'widget' => $this
        ]);
    }

    protected function initRowsPerPage()
    {
        $settings = $this->storage->get($this->storageId);
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
        return ArrayHelper::getValue(
            $this->storage->get($this->storageId),
            'rows-per-page'
        );
    }
}