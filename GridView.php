<?php

namespace consultnn\grid;

class GridView extends \yii\grid\GridView
{

    public $layout = "{summary}\n{items}\n{pager}";

    public $plugins = [];

    public function init()
    {

        parent::init();
    }



}