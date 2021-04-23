<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

?>

<div>
    <div class="search-box">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'search')->input(['autofocus' => true]) ?>

        <div class="form-group">
            <?= Html::submitButton('index', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

    <div class="list-box">
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