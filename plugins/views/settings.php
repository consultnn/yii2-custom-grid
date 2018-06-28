<?php
use yii\helpers\Html;
/** @var \yii\web\View $this */
/** @var \consultnn\grid\plugins\Settings $widget */

$this->registerCss('
    .settings-container {
        float: right;
    }

    .settings-container .summary {
        float:left;
    }

    .settings-container .settings-button {
        font-size: 24px;
        margin-bottom: 5px;
        color: black;
        cursor: pointer;
    }

    .settings-container .settings {
        position: fixed;
        display: none;
        background-color: #fff;
        z-index: 3;
        top: 50%;
        left: 50%;
        margin-top: -250px;
        margin-left: -225px;
        width: 450px;
    }

    .settings-container .settings label {
        width: 50%;
        float:left;
    }
');
?>
<div class="settings-container" id="<?= $widget->id ?>">
    <span class="settings-button fa fa-bars" id="settings-button"></span>
    <div class="settings card" id="settings">
        <div class="card-header">
            Настройки:
            <button type="button" class="close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="card-body">
            <div class="columns-setting">
                <div class="alert alert-warning" role="alert">
                    Можно выбрать не более <?= $widget->maxColumnsCount ?> колонок
                </div>
                <?php
                /** @var \consultnn\grid\plugins\Settings $widget */
                echo Html::beginForm($widget->url);
                echo Html::hiddenInput('storage_id', $widget->storageId);
                echo Html::checkboxList('settings[columns]', $widget->activeColumns, $widget->columnLabels);
                echo Html::submitButton('Сохранить', ['class' => 'btn btn-primary pull-right', 'id' => 'save-columns']);
                echo Html::endForm();
                ?>
            </div>
        </div>
    </div>
</div>
<div class="clear"></div>