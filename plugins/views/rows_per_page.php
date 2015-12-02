<?php
use yii\helpers\Html;

/** @var \yii\web\View $this */
/** @var \consultnn\grid\plugins\RowsPerPage $widget */
$this->registerCss("
    .rows-per-page select {
        display: inline-block;
        float: right;
    }
");
?>
<div class="rows-per-page">
    <?php
    /** @var $numberRows array */
    echo Html::beginForm($widget->url);
    echo Html::hiddenInput('storage_id', $widget->storageId);
    echo Html::dropDownList('settings[rows-per-page]', $widget->getRowsPerPage(), $numberRows);
    echo Html::endForm();
    ?>
</div>