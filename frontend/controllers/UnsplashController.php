<?php

namespace frontend\controllers;

use yii\httpclient\Client;
use Exception;
use frontend\models\UnsplashSearchForm;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * UnsplashController implements the CRUD actions for Favorites model.
 */
class UnsplashController extends Controller
{
    public function actionIndex()
    {
        $model = new UnsplashSearchForm();
        $photos = [];
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $photos = $this->search($model->search);
        }

        return $this->render('index', [
            'model' => $model,
            'photos' => $photos
        ]);
    }


    public function search(string $search)
    {
        $server = "https://api.unsplash.com/";
        $clientId = "Fvl6_IMfndHATC4uEIs5XDwdSFbnBaLam_PWIHSOq-o";
        $client = new Client(['baseUrl' => $server]);
        $response = $client->get('search/photos', ['client_id' => $clientId, 'query' => $search])->send();
      
        $photos = [];

        if ($response->isOk) {
            $photos = $response->getData()["results"];
        }

        return $photos;
        
    }

    public static function searchOne(string $photoId)
    {
        $server = "https://api.unsplash.com/";
        $clientId = "Fvl6_IMfndHATC4uEIs5XDwdSFbnBaLam_PWIHSOq-o";
        $client = new Client(['baseUrl' => $server]);
        $response = $client->get("photos/$photoId", ['client_id' => $clientId])->send();
      
        if ($response->isOk) {
            $photo = $response->getData();
        }

        return $photo;
        
    }

    public static function downloadOne(string $photoId)
    {
        $server = "https://api.unsplash.com/";
        $clientId = "Fvl6_IMfndHATC4uEIs5XDwdSFbnBaLam_PWIHSOq-o";
        $client = new Client(['baseUrl' => $server]);
        $response = $client->get("photos/$photoId/download", ['client_id' => $clientId])->send();
      
        if ($response->isOk) {
            $photo = $response->getData();
        }

        return $photo["url"] ?? null;
        
    }
}
