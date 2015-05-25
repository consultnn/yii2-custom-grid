<?php
use yii\jui\DatePicker;

return [
    'view' => [
        '_id',
        'old_id',
        [
            'attribute' => 'longName',
            'value' => function($model) {
                /** @var $model \backend\models\CompanySearch */
                return $model->getLongNameLabel();
            }
        ],
        [
            'attribute' => 'chain_id',
            'value' => function($model) {
                if ($model->chain) {
                    return $model->chain->name;
                }
            }
        ],
        [
            'attribute' => 'employees',
            'value' => function ($model) {
                /** @var $model \backend\models\CompanySearch */
                return $model->getEmployeesLabel();
            },
            'filter' => $model::getEmployees(),
            'visible' => false
        ],
        'sphere',
        [
            'attribute' => 'phones',
            'value' => function($model) {
                /** @var $model \backend\models\CompanySearch */
                return $model->getPhonesLabel();
            },
            'format' => 'raw',
            'visible' => false
        ],
        [
            'attribute' => 'internet',
            'value' => function($model) {
                /** @var $model \backend\models\CompanySearch */
                return $model->getInternetLabel();
            },
            'format' => 'raw',
            'visible' => false
        ],
        'description',
        [
            'attribute' => 'updated_at',
            'format' => ['datetime', 'php:d.m.Y H:i:s'],
            'filter' => DatePicker::widget([
                'model'=>$model,
                'attribute'=>'updated_at',
                'language' => 'ru',
                'dateFormat' => 'php:d.m.Y',
            ]),
        ],
        [
            'attribute' => 'status',
            'value' => function ($model) {
                /** @var $model \backend\models\CompanySearch */
                return $model->getStatusLabel();
            },
            'filter' => $model::getStatuses()
        ],
    ],
    'editable' => [
        [
            'attribute' => 'name',
            'value' => self::activeTextInput(),
            'format' => 'raw'
        ]
    ],
    'action' => [
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{update}{delete}'
        ]
    ]
];
