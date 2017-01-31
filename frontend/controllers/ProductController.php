<?php
namespace frontend\controllers;

use Yii;
use app\models\Product;
use app\models\Country;
use app\models\Cart;
use app\models\Voucher;
use app\models\Checkout;
use yii\web\Controller;
use yii\helpers\ArrayHelper;

/**
 * manual CRUD
 **/
class ProductController extends Controller
{  
    public function actionIndex()
    {
        //list all item in table product
        $product= Product::find()->all();

        return $this->render('index', ['model' => $product]);
    }

    public function actionDetail($id)
    {
        //list detail of the product according id given
        $productDetail = Product::find()->where(['id' => $id])->one();

        // $id not found in database
        if($productDetail === null)
            throw new NotFoundHttpException('The requested page does not exist.');

        $items = ArrayHelper::map(Country::find()->all(), 'id', 'name');

        // return $productDetail->image;

        return $this->render('productDetail', ['model' => $productDetail, 'items'=>$items]);
    }

    public function actionAddcart($id)
    {
        $quantitybeli = $_POST["quantitybeli"];
        //checking there are stock
        $productDetail = Product::find()->where(['id' => $id])
        ->andWhere(['>','quantity', 0])
        // ->andWhere(['<', 'quantity', $quantitybeli])
        ->one();

        // return $_POST["quantitybeli"];

        if($productDetail === null)
            throw new NotFoundHttpException('The items is out of stock.');

        //add to cart
        Yii::$app->db->createCommand()->insert('addtocart', [
            'productname' => $productDetail->productname,
            'quantitybeli' => $_POST["quantitybeli"],
            'price' => $productDetail->price,
            'priceretail' => $productDetail->priceretail,
            'description' => $productDetail->description,
            'image' => $productDetail->image
            ])->execute();

        return $this->redirect(['cart']);;
    }

    public function actionCart()
    {
        //view the cart of product
        $cart = Cart::find()->all();
        //for dropdown shipping
        $items = ArrayHelper::map(Country::find()->all(), 'id', 'name');

        return $this->render('cart', ['model' => $cart, 'items'=>$items]);
    }

    public function actionCheckoutprocess()
    {
        $list = $_POST['idcart'];

        foreach ($list as $value) {
            $priceeach = Cart::find()->where(['id' => $value])->one();
            $totalprice += ($priceeach->price*$priceeach->quantitybeli);
        }

        // return $totalprice;

        $voucher = $_POST['voucher'];
        

        //checkvoucher is inputted
        if ($voucher != ""){
            //checkvoucher in table
            $checkvoucher = Voucher::find()->where(['name' => $voucher])->one();
            $checkvoucherid = $checkvoucher->id;
            if($checkvoucher === null){
                throw new NotFoundHttpException('The items not exist.');
            }
            else{
                if ($checkvoucher->type == 1 && count($list) >= 2) {
                    //this one is percentage
                    $totalprice *= ($checkvoucher->discount/100);
                }
                elseif ($checkvoucher->type == 2 && $totalprice >= 100){
                    //this one is for ringgit
                    $totalprice -= $checkvoucher->discount ;
                }
                else{
                    $totalprice = $totalprice;
                }
            }
        }
        else
        {
            //voucher is not inputted
            $checkvoucherid = null;
            $totalprice = $totalprice;

        }


        $shipping = $_POST['shipping'];
        $checkshipping = Country::find()->where(['id' => $shipping])->one();

        if ($shipping == 1 && ( $totalprice < 150 || count($list) < 2)) {
            $shippingfee = 10;
        }elseif ($shipping == 3 && $totalprice < 300) {
            $shippingfee = 25;
        }elseif ($shipping == 2 && $totalprice < 300) {
            $shippingfee = 20;
        }else{
            $shippingfee = 0;
        }

        Yii::$app->db->createCommand()->insert('checkout', [
            'voucher' => $checkvoucher->id,
            'shipping' => $shipping,
            'shippingfee' => $shippingfee,
            'discount' => $checkvoucher->discount,
            'discounttype' =>$voucher->type,
            'totalprice' => $totalprice
            ])->execute();

        $maxid = Checkout::find()->where(['id' => Checkout::find()->max('id')])->one();
        $maxid->id;

        foreach ($list as $value) {
            $myUpdate = "UPDATE addtocart
            SET is_buy = 1, checkoutid = $maxid->id
            where id = $value";

            $lala = Yii::$app->db->createCommand($myUpdate)->execute();
        }
        

        return $this->render(['checkout']);
    }

    public function actionCheckout()
    {
        $product = Checkout::find()->all();

        return $this->render('checkout', ['model' => $product]);
    }

    //ajax checking shipping fee
    public function actionShippingopt()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->get();
            $testing = count($data['idcart']);
            $totalprice = $data['totalprice'];

            if ($shipping == 1 && ( $totalprice < 150 || $testing  < 2)) {
                $shippingfee = 10;
            }elseif ($shipping == 3 && $totalprice < 300) {
                $shippingfee = 25;
            }elseif ($shipping == 2 && $totalprice < 300) {
                $shippingfee = 20;
            }else{
                $shippingfee = 0;
            }

            $checkshipping = Country::find()->where(['id' => $data['country']])->one();

            return $shippingfee;
            //     $response_values = array(
    //                 'country' => $checkshipping->name,
    //                 'fee' =>  $checkshipping->fee,
    //                 'description'=>$checkshipping->description
    //                 );
            // return Response::json($response_values);
        }
    }
}

