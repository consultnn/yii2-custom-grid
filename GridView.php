<?php

namespace consultnn\grid;

use Yii;

class GridView extends \yii\grid\GridView
{
    const EVENT_AFTER_INIT = 'afterInit';
    const EVENT_BEFORE_RUN = 'beforeRun';
    const EVENT_AFTER_RUN = 'afterRun';

    public $pluginSections = [];

    public $layout = '{summary}{settings}\n{items}\n{pager}';
    /**
     * @var array|plugins\AbstractPlugin[]
     */
    public $plugins = [];

    public function init()
    {
        $this->initPlugins();

        parent::init();

        $this->trigger(self::EVENT_AFTER_INIT);
    }

    public function run()
    {
        $this->trigger(self::EVENT_BEFORE_RUN);
        parent::run();
        $this->trigger(self::EVENT_AFTER_RUN);
    }

    protected function initPlugins()
    {
        foreach ($this->plugins as $key => $pluginOptions) {
            if (is_string($pluginOptions)) {
                $pluginOptions = [
                    'class' => $pluginOptions
                ];
            }
            $this->plugins[$key] = Yii::createObject(array_merge(
                [
                    'grid' => $this,
                    'id' => $this->id
                ],
                $pluginOptions
            ));
        }
    }

    /**
     * @inheritdoc
     */
    public function renderSection($name)
    {
        if (isset($this->pluginSections[$name])) {
            return $this->pluginSections[$name]->run();
        } else {
            return parent::renderSection($name);
        }
    }
}