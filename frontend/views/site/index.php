<?php

/* @var $this yii\web\View */

use yii\bootstrap\Carousel;

$this->title = 'My Yii Application';
?>
<div class="site-index">

<?= Carousel::widget([
            'items' => $collection
        ]); ?>
</div>
