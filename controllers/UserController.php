<?php

namespace app\controllers;

use Yii;
use DateTime;
use app\models\User;
use app\models\UserSearch;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
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
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();
        if ($model->load(Yii::$app->request->post())) {
            $post = $_POST['User'];
            $model->uploaded_image = UploadedFile::getInstance($model, 'uploaded_image');
            $model->profile_image = $model->uploadImage();
            $model->date_of_birth = $this->dateToDatabase($post['dateForScreen']);
            $model->password = password_hash($post['password'],PASSWORD_DEFAULT);
            $model->authKey = md5(random_bytes(5));
            $model->accessToken = password_hash(random_bytes(10), PASSWORD_DEFAULT);

            if($model->save(false)){
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->socialToArray();
        if ($model->load(Yii::$app->request->post())) {
            $post = $_POST['User'];
            $model->uploaded_image = UploadedFile::getInstances($model, 'uploaded_image');
            $model->profile_image = $model->uploadImage();
            $model->date_of_birth = $this->dateToDatabase($post['dateForScreen']);
            $model->password = password_hash($post['password'],PASSWORD_DEFAULT);
            if(!$model->authKey || !$model->accessToken){
                $model->authKey = md5(random_bytes(5));
                $model->accessToken = password_hash(random_bytes(10), PASSWORD_DEFAULT);
            }
            if($model->save(FALSE)){
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing User model.
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
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            $dateFromDb = date_create($model["date_of_birth"]);
            $model["dateForScreen"] = date_format($dateFromDb, 'd/m/Y');
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    private function dateToDatabase($dateStr){
        $dateArray= explode('/', $dateStr);
        $time = strtotime($dateArray[1].'/'.$dateArray[0].'/'.$dateArray[2]);
        return date('Y-m-d',$time);
    }
}
