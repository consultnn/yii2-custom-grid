<?php
/**
 * Created by PhpStorm.
 * User: a.talanin
 * Date: 01.12.15
 * Time: 8:52
 */

namespace consultnn\grid\plugins;

use consultnn\grid\GridView;

class RowsPerPage extends AbstractPlugin
{
    /** @var array $numberRows */
    public $numberRows;

    /** @var string $viewPath */
    public $viewPath = 'rows_per_page.php';

    /** @var string $url */
    public $url;

    /** @var SettingsStorageInterface $storage */
    public $storage;

    /** @var string */
    public $storageId;

    public function init()
    {
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