<?php
namespace frontend\controllers;

use Yii;
use app\models\Product;
use app\models\Country;
use app\models\Cart;
use yii\web\Controller;
use yii\helpers\ArrayHelper;

/**
 * manual CRUD
 **/
class ProductController extends Controller
{  
    public function actionIndex()
    {
        $product= Product::find()->all();

        return $this->render('index', ['model' => $product]);
    }

    public function actionDetail($id)
    {
        $productDetail = Product::find()->where(['id' => $id])->one();

        // $id not found in database
        if($productDetail === null)
            throw new NotFoundHttpException('The requested page does not exist.');

        $items = ArrayHelper::map(Country::find()->all(), 'id', 'name');

        return $this->render('productDetail', ['model' => $productDetail, 'items'=>$items]);
    }

    public function actionAddCart($id)
    {
        $cart = Cart::find()->all();

        return $this->render('productDetail', ['model' => $cart]);
    }

    public function actionCart($id)
    {
        $cart = Cart::find()->all();

        return $this->render('productDetail', ['model' => $cart]);
    }

    public function actionAdd()
    {
        return $_POST["quantitybeli"];

        // return $this->render('productDetail', ['model' => $cart]);
    }



    // public function actionAjax()
    // {
    //     if(isset( Yii::$app->request->post('test') ) )
    //     {
    //         $test = "Ajax Worked!";
    //     // do your query stuff here
    //     }
    //     else{
    //         $test = "Ajax failed";
    //         // do your query stuff here
    //     }
    //     // return Json    
    //     return \yii\helpers\Json::encode($test);
    // }

}