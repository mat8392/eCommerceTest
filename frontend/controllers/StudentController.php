<?php
namespace frontend\controllers;
 
use Yii;
use app\models\Student;
use yii\web\Controller;
 
/**
 * manual CRUD
 **/
class StudentController extends Controller
{  
    /**
     * Create
     */
    public function actionCreate()
    {
        $model = new Student();
 
        // new record
        if($model->load(Yii::$app->request->post()) && $model->save()){
            return $this->redirect(['index']);
        }
                 
        return $this->render('create', ['model' => $model]);
    }

    /**
     * Read
     */
    public function actionIndex()
    {
        $student = Student::find()->all();
         
        return $this->render('index', ['model' => $student]);
    }

    public function actionDetail()
    {
        $model = new Student();
                 
        return $this->render('product_detail');
    }   


     /**
     * Edit
     * @param integer $id
     */
    public function actionEdit($id)
    {
        $model = Student::find()->where(['id' => $id])->one();
 
        // $id not found in database
        if($model === null)
            throw new NotFoundHttpException('The requested page does not exist.');
         
        // update record
        if($model->load(Yii::$app->request->post()) && $model->save()){
            return $this->redirect(['index']);
        }
         
        return $this->render('edit', ['model' => $model]);
    }

    /**
     * Delete
     * @param integer $id
     */
     public function actionDelete($id)
     {
         $model = Student::findOne($id);
         
        // $id not found in database
        if($model === null)
            throw new NotFoundHttpException('The requested page does not exist.');
             
        // delete record
        $model->delete();
         
        return $this->redirect(['index']);
     }
}