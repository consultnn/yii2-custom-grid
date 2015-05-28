<?php

namespace consultnn\grid\plugins\assets;

use yii\web\AssetBundle;

class ResizableColumnsAsset extends AssetBundle
{
    public $sourcePath = '@bower';

    public $useLocalStorage = false;

    public $js = [
        '/jquery-resizable-columns/dist/jquery.resizableColumns.js',
    ];

    public $css = ['/jquery-resizable-columns/dist/jquery.resizableColumns.css'];

    public $depends = [
        'yii\web\JqueryAsset',
    ];

    public function registerAssetFiles($view)
    {
        if ($this->useLocalStorage) {
            $this->js[] = '/store-js/store.min.js';
        }

        parent::registerAssetFiles($view);
    }
}