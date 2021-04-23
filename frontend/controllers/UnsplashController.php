<?php

namespace frontend\controllers;

use yii\httpclient\Client;
use Exception;
use frontend\models\UnsplashSearchForm;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * UnsplashController implements the CRUD actions for Favorites model.
 */
class UnsplashController extends Controller
{

     /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
           
        ];
    }


    public function actionIndex()
    {
          return $this->render('index');
    }

    public function actionSearch()
    {
        $search = Yii::$app->request->post()["search"];
        $server = "https://api.unsplash.com/";
        $clientId = "Fvl6_IMfndHATC4uEIs5XDwdSFbnBaLam_PWIHSOq-o";
        $client = new Client(['baseUrl' => $server]);
        $response = $client->get('search/photos', ['client_id' => $clientId, 'query' => $search])->send();
      
        $photos = [];

        if ($response->isOk) {
            $photos = $response->getData()["results"];
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        return [
            "search" =>$search,
            "data" => $photos
        ];
        
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
