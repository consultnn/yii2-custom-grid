<?php
use yii\helpers\Html;
/** @var \yii\web\View $this */
$this->registerCss("
    .summary {
        float:left;
    }
    .settings-container {
        float: right;
    }

    #column-settings-button {
        font-size: 24px;
        margin-bottom: 5px;
        color: black;
        cursor: pointer;
    }

    #column-settings {
        right: 0;
        position: absolute;
        display: none;
        background-color: #fff;
        z-index: 1;
        width: 250px;
    }

    #column-settings label {
        width: 100%;
    }
");
?>
<div class="settings-container">
    <span id="column-settings-button" class="glyphicon glyphicon-menu-hamburger" ></span>
    <div class="panel panel-default" id="column-settings">
        <div class="panel-heading">
            Settings:
        </div>
        <div class="panel-body">
            <?php

            /** @var \consultnn\grid\plugins\Settings $widget */
            echo Html::beginForm($widget->url);
            echo Html::hiddenInput('table_id', $widget->id);
            echo Html::checkboxList("columns", $widget->activeColumns, $widget->columnLabels);
            echo Html::submitButton('Save', ['class' => 'btn btn-primary', 'id' => 'save-columns']);
            echo Html::endForm();
            ?>
        </div>
    </div>
</div>
<div class="clearfix"></div>