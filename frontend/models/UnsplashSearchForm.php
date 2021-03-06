<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

class UnsplashSearchForm extends Model
{
    public $search;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ["search", "required"],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'search' => 'Search',
        ];
    }
}
