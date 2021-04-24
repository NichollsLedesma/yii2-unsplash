<?php

namespace frontend\controllers;

use DateTime;
use Exception;
use Yii;
use frontend\models\Favorites;
use igogo5yo\uploadfromurl\UploadFromUrl;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\BaseFileHelper;
use yii\web\Response;
use ZipArchive;

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
        $model = $this->findModel($id);
        $userId = Yii::$app->user->id;
        $pathFavorite = $this->searchFilePath($model->photo_id, $userId);

        if ($pathFavorite) {
            unlink($pathFavorite);
        }
        $model->delete();

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
        $message = [
            "type" => "success",
            "message" => "favorite added."
        ];

        if (!$userId) {
            $message = [
                "type" => "error",
                "message" => "You need be logged."
            ];
        }
        $isFavorite = Favorites::find()
            ->where([
                'photo_id' => $photoId,
                'user_id' => $userId
            ])
            ->exists();

        if ($isFavorite) {
            $message = [
                "type" => "warning",
                "message" => "photo already added."
            ];
    
        } else {
            $photo = UnsplashController::searchOne($photoId);
            $url = $photo["urls"]["small"];
            $this->uploadUrlImage($url, $userId, $photoId);
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
            } catch (Exception $e) {
                $message = [
                    "type" => "error",
                    "message" => "something happend. Favorite not added."
                ];
            }
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $message;
    }

    public function actionDownload(string $photoId = null)
    {
        $userId = Yii::$app->user->id;

        if (!$userId) {
            Yii::$app->session->setFlash("error", "You need be logged.");

            return $this->goHome();
        }

        if ($photoId) {
            $fileToDownload = $this->searchFilePath($photoId, $userId);

            if (!$fileToDownload) {
                Yii::$app->session->setFlash("error", "Resource not found");

                return $this->redirect(array("favorites/index"));
            } else {
                Yii::$app->getResponse()->sendFile($fileToDownload);
            }
        } else {
            $path = $this->getPathBase($userId);
            $listFiles = glob($path);
            $zipname = time() . "_favorites.zip";
            $zip = new ZipArchive;
            $zip->open($zipname, ZipArchive::CREATE);

            foreach ($listFiles as $file) {
                $parts = explode(DIRECTORY_SEPARATOR, $file);
                $zip->addFile($file, end($parts));
            }

            $zip->close();
            Yii::$app->getResponse()->sendFile($zipname);
            chmod($zipname, 0744);
            unlink($zipname);
        }
    }

    private function getPathBase(string $userId)
    {
        return 'uploads' . DIRECTORY_SEPARATOR . $userId . DIRECTORY_SEPARATOR . "*";
    }

    private function searchFilePath(string $photoId, string $userId)
    {
        $fileToDownload = null;
        $path = $this->getPathBase($userId);
        $listFiles = glob($path);

        foreach ($listFiles as $filename) {
            if (str_contains($filename, $photoId)) {
                $fileToDownload = $filename;
            }
        }

        return $fileToDownload;
    }

    private function uploadUrlImage(string $url, int $userId, string $photoId)
    {
        $ext = $this->getExtension($url);
        $path = 'uploads' . DIRECTORY_SEPARATOR . $userId;
        BaseFileHelper::createDirectory($path);
        $file = UploadFromUrl::initWithUrl($url);
        $file->saveAs($path . DIRECTORY_SEPARATOR . "$photoId$ext");
    }

    private function getExtension(string $url)
    {
        return "." . explode("&", explode("&fm=", $url)[1], 2)[0];
    }
}
