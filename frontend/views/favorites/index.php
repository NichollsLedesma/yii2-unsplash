<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Favorites';
$this->params['breadcrumbs'][] = $this->title;

function formatUrl($url)
{
    $length = 100;
    
    if(strlen($url) < $length){
        return $url;
    }

    return substr($url,0,$length) . "...";
}

function formatDescription($description)
{
    $length = 25;

    if(!$description){
        return "No description";
    }
    
    if(strlen($description) < $length){
        return $description;
    }

    return substr($description,0,$length) . "...";

}

?>
<div class="favorites-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <!--    <p>
        <?= Html::a('Create Favorites', ['create'], ['class' => 'btn btn-success']) ?>
    </p> -->


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            // 'photo_id',
            // [
            //     'attribute' => 'url:url',
            //     'label' => 'Link',
            //     'value' => function ($data) {
            //         return format($data->url);
            //     },
            // ],
            'title',
            // 'description:ntext',
            [
                'attribute' => 'description',
                'label' => 'Description',
                'value' => function ($data) {
                    return formatDescription($data->description);
                },
            ],
            'created_at:datetime',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>