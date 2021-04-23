<?php

use yii\helpers\Html;
use yii\helpers\Url;
?>


<div>
    <div class="container-photos">
        <div class="unsplash-photos">
            <h1>List Photos</h1>
            <?php foreach ($photos as $photo) { ?>
                <div class="box-photo">
                    <a target="_blank" href="<?= $photo["urls"]["small"] ?>">
                        <img src="<?= $photo["urls"]["small"] ?>" alt="photo" width="600" height="400">
                    </a>
                    <div class="description"><?= $photo["description"] ?? $photo["alt_description"] ?></div>
                    <?= Html::a(Yii::t('app', 'Add favorites'), ['favorites/add', 'photoId' => $photo["id"]], ['class' => 'btn btn-success']) ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>