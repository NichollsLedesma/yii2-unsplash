<?php

namespace frontend\models;

use common\models\User;
use Yii;

/**
 * This is the model class for table "favorites".
 *
 * @property int $id
 * @property string $photo_id
 * @property string $url
 * @property string $title
 * @property string|null $description
 * @property int $user_id
 * @property int $created_at
 * @property int $updated_at
 *
 * @property User $user
 */
class Favorites extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'favorites';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['url', 'title'], 'required'],
            [['url', 'title'], 'string', 'max' => 255],
            ['description', 'string']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'photo_id' => 'Photo ID',
            'url' => 'Url',
            'title' => 'Title',
            'description' => 'Description',
            'user_id' => 'User ID',
            // 'created_at' => 'Created At',
            // 'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
