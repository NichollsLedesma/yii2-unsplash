<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\BaseStringHelper;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Favorites';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="favorites-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <!--    <p>
        <?= Html::a('Create Favorites', ['create'], ['class' => 'btn btn-success']) ?>
    </p> -->
    <p>
        <?= Html::a(Yii::t('app', 'Download as Zip'), ['favorites/download'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            // 'url:url',
            // [
            //     'attribute' => 'url:url',
            //     'label' => 'Link',
            //     'value' => function ($data) {
            //         return format($data->url);
            //     },
            // ],
            'title',
            [
                'attribute' => 'description',
                'label' => 'Description',
                'value' => function ($data) {
                    return BaseStringHelper::truncate($data->description, 25, '...');
                },
            ],

            'created_at:datetime',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>