Yii2-Custom-Grid Module
===========

Plugin system for Yii2 GridView

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Add repository
```
{
  "type": "vcs",
  "url":  "https://github.com/consultnn/yii2-custom-grid.git"
},
```
and dependence

```
"consultnn/yii2-custom-grid": "*"
```

to the require section of your `composer.json` file.
## Existing plug-ins
* [Settings](docs/resizable-columns.md)
* [ResizableColumns](docs/settings.md)

## Configuration
1) Add grid view in your view with plugins parameter

```
echo consultnn\grid\GridView::widget(
    [
        'dataProvider' => $dataProvider,
        'filterModel' => $model,
        'plugins' => [
            \consultnn\grid\plugins\ResizableColumns::className(),
            [
                'class' => \consultnn\grid\plugins\Settings::className(),
                'url' => \yii\helpers\Url::to('/company/settings'),
                'storage' => Yii::$app->user->identity->settings,
                'activeColumns' => ['_id', 'name', 'type']
            ]
        ]
    ]
);
```

2) Plugins attribute contains array of enabled component plugins.

About every supported plugin you may read in [plugins docs](docs/plugins/index.md)

## License
Auth module is released under the MIT License. See the bundled `LICENSE.md` for details.
