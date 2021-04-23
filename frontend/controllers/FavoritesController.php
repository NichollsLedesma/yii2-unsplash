<?php

namespace frontend\controllers;

use DateTime;
use Exception;
use Yii;
use frontend\models\Favorites;
use frontend\models\UnsplashSearchForm;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FavoritesController implements the CRUD actions for Favorites model.
 */
class FavoritesController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Favorites models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Favorites::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Favorites model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Favorites model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Favorites();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Favorites model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Favorites model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Favorites model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Favorites the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Favorites::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionAdd(string $photoId)
    {
        $userId = Yii::$app->user->id;
       
        if (!$userId) {
            var_dump("no user logged");
            return;
        }
        $isFavorite = Favorites::find()
            ->where([
                'photo_id' => $photoId,
                'user_id' => $userId
                ])
            ->exists();

        if ($isFavorite) {
            Yii::$app->session->setFlash("warning", "photo already added.");
        } else {
            $photo = UnsplashController::searchOne($photoId);
            $now = date_timestamp_get(new DateTime());
            $newFavorite = new Favorites();
            try {
                $newFavorite->photo_id = $photoId;
                $newFavorite->user_id = $userId;
                $newFavorite->url = $photo["urls"]["small"];
                $newFavorite->title = "title default";
                $newFavorite->description = $photo["description"];
                $newFavorite->created_at = $now;
                $newFavorite->updated_at = $now;
                
                $newFavorite->save();
                Yii::$app->session->setFlash("success", "favorite added.");
            } catch (Exception $e) {
                var_dump($e->getMessage());
                Yii::$app->session->setFlash("error", "favorite not added.");
            }
        }

        return $this->redirect(array("unsplash/index"));
    }

    public function actionDownload(string $photoId)
    {
        $photo = UnsplashController::downloadOne($photoId);
        echo "<pre>";
        var_dump($photo);
        echo "</pre>";

        if($photo){
            //  TOD: download file and redirect
        }
    }
}
