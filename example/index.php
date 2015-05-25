<?php
use backend\widgets\GridView;
use backend\helpers\CompanyColumns;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var $model \backend\models\CompanySearch */

/** @var \yii\web\View $this */
$this->title = 'Справочник компаний';

\backend\assets\ColumnsAsset::register($this);
$this->registerJs("
 $(function(){
    $('table').resizableColumns({
        store: {
            set: function(name, val) {
                console.log(name, val);
            },
            get: function(name) {
                console.log(name);
            }
        },
        DATA_COLUMN_ID: 'column-id'
    });

    $('#column-settings-button').on('click', function(){
        $('#column-settings').toggle();
    });

  });
");

$this->registerCss("
    #column-settings-button {
        float: right;
        font-size: 24px;
        margin-bottom: 5px;
        color: black;
    }

    #column-settings {
        right: 0;
        position: absolute;
        display: none;
        background-color: #fff;
        margin-top: 29px;
        z-index: 1;
        width: 250px;
    }

    #column-settings label {
        width: 100%;
    }
");

echo Html::a(
    Html::tag('span', null, ['class' => 'glyphicon glyphicon-menu-hamburger', 'id' => 'column-settings-button']),
    '#'
);

echo Html::beginTag('div', ['class' => 'panel panel-default', 'id' => 'column-settings']);
echo Html::tag('div', 'Отображать столбцы:', ['class' => 'panel-heading']);
echo Html::beginTag('div', ['class' => 'panel-body']);
$tableId = Yii::$app->controller->action->getUniqueId();
echo Html::beginForm(Url::to('/company/columns'));
echo Html::checkboxList("columns[{$tableId}]", CompanyColumns::active($model), CompanyColumns::labels($model));
echo Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'id' => 'save-columns']);
echo Html::endForm();

echo Html::endTag('div');
echo Html::endTag('div');

echo GridView::widget(
    [
        'dataProvider' => $model->search(),
        'tableOptions' => [
            'class' => 'table-resizable table table-striped table-bordered',
            'data' => ['resizable-columns-id' => 'company']
        ],
        'columns' => CompanyColumns::all($model),
        'plugins' => [

        ]
    ]
);