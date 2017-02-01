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
use yii\helpers\Json;
use yii\helpers\VarDumper;
use yii\db\Query;
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

        return $this->redirect(['cart']);
    }

    public function actionCart()
    {
        //view the cart of product
        $cart = Cart::find()->where("is_buy is null")->all();
        //for dropdown shipping
        $items = ArrayHelper::map(Country::find()->all(), 'id', 'name');

        return $this->render('cart', ['model' => $cart, 'items'=>$items]);
    }

    public function actionCheckoutprocess()
    {
        $list = $_POST['idcart'];

        if (count($list == 1 )){
            $priceeach = Cart::find()->where(['id' => $_POST['idcart']])->one();
            $totalprice = ($priceeach->price*$priceeach->quantitybeli);

        }else {

            foreach ($list as $value) {
                $priceeach = Cart::find()->where(['id' => $value])->one();
                $totalprice += ($priceeach->price*$priceeach->quantitybeli);
            }
        }

        // return $totalprice;

        $voucher = $_POST['voucher'];
        

        //checkvoucher is inputted
        if ($voucher != ""){
            //checkvoucher in table
            $checkvoucher = Voucher::find()->where(['name' => $voucher])->one();
            $checkvoucherid = $checkvoucher->id;
            if($checkvoucher === null){
                $checkvoucherid = "Voucher is not exist!";
                $totalprice = $totalprice;
            }
            else{
                if ($checkvoucher->type == 1 && count($list) >= 2) {
                    //this one is percentage

                    $dis = $totalprice;
                    $dis *= ($checkvoucher->discount/100);
                    $totalprice -=$dis;
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

        $totalprice = $totalprice + $shippingfee;

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


        if (count($list == 1 )){
            $f=$list['0'];
            $myUpdate = "UPDATE addtocart
            SET is_buy = 1, checkoutid = $maxid->id
            where id = $f";

            $lala = Yii::$app->db->createCommand($myUpdate)->execute();
            
        }else {
            foreach ($list as $value) {
                $myUpdate = "UPDATE addtocart
                SET is_buy = 1, checkoutid = $maxid->id
                where id = $value";

                $lala = Yii::$app->db->createCommand($myUpdate)->execute();
            }
        }
        return $this->redirect(['checkout']);
    }

    public function actionCheckout()
    {
        //view the cart of product
        // $checkout = Checkout::find()->all();
        $query = new Query;
        $query->select([
            'c.id',
         'v.name as voucher', 
         'cn.name as shipping', 
         'c.shippingfee as shippingfee', 
         'c.discount as discount',
         'c.discounttype as discounttype',
         'c.totalprice as totalprice'
         ])
            ->from('checkout c')
            ->leftJoin('voucher v', 'c.voucher = v.id')
            ->leftJoin('country cn', 'c.shipping = cn.id');
            // echo $query->createCommand()->sql;
            // echo $query->createCommand()->getRawSql();
        $command = $query->createCommand();
        $checkout = $command->queryAll();
        // var_dump($checkout);
        // VarDumper::dump($checkout);
        $y = array();

        
        // return $query->id;
        // echo $query->createCommand()->sql;
        // echo $query->createCommand()->getRawSql();
        // return $t;

        foreach ($checkout as $value) {
            $cart = Cart::find()->where('is_buy = 1')->all();
            // VarDumper::dump($cart);
            $t = $value['id'];
            $y[$t] = $cart;
            // return $y[$value->id]->id;
        }

        return $this->render('checkout', ['jajaja' => $checkout, 'model'=>$y]);
    }

    //ajax checking shipping fee
    public function actionShippingopt()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->get();
            $testing = count($data['idcart']);
            $totalprice = $data['totalprice'];
            $voucher = $data['voucher'];

            $checkshipping = Country::find()->where(['id' => $data['country']])->one();
            $descriptionship = $checkshipping->description;

            if ($shipping == 1 && ( $totalprice < 150 || $testing  < 2)) {
                $shippingfee = $checkshipping->fee;
            }elseif ($shipping == 3 && $totalprice < 300) {
                $shippingfee = $checkshipping->fee;
            }elseif ($shipping == 2 && $totalprice < 300) {
                $shippingfee = $checkshipping->fee;
            }else{
                $shippingfee = 0;
            }

            if ($voucher != ""){
            //checkvoucher in table
                $checkvoucher = Voucher::find()->where(['name' => $voucher])->one();
                $checkvoucherid = $checkvoucher->id;
                if($checkvoucher === null){
                    $checkvoucherid = "Voucher is not exist!";
                    $totalprice = $totalprice;
                }
                else{
                    if ($checkvoucher->type == 1 && count($list) >= 2) {
                    //this one is percentage
                        $dis = $totalprice;
                        $dis *= ($checkvoucher->discount/100);
                        $totalprice -=$dis;
                        $descriptionvoucher = $checkvoucher->description;
                    }
                    elseif ($checkvoucher->type == 2 && $totalprice >= 100){
                    //this one is for ringgit
                        $dis = $totalprice;
                        $dis -= $checkvoucher->discount ;
                        $totalprice =$dis;
                        $descriptionvoucher = $checkvoucher->description;
                    }
                    else{
                        $dis = 0;
                        $totalprice = $totalprice;
                        $descriptionvoucher = "none";
                    }
                }
            }
            else
            {
                //voucher is not inputted
                $dis = 0;
                $descriptionvoucher = "none1";
                $totalprice = $totalprice;

            }

            $totalpricefinal = $totalprice + $shippingfee;

            // return $shippingfee;
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $response_values = array(
                'totalpricefinal' => $totalpricefinal,
                'shippingfee' =>  $shippingfee,
                'dis'=>$dis,
                'descriptionvoucher'=>$descriptionvoucher,
                'descriptionship'=>$descriptionship
                );

            return $response_values;
                // return CJSON::encode($response_values);
            // return Response::json($response_values);
        }
    }

    //checking voucher
    public function actionVouchopt()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->get();
            $testing = count($data['idcart']);
            $totalprice = $data['totalprice'];
            $voucher = $data['voucher'];

            $checkshipping = Country::find()->where(['id' => $data['country']])->one();
            $descriptionship = $checkshipping->description;

            if ($shipping == 1 && ( $totalprice < 150 || $testing  < 2)) {
                $shippingfee = $checkshipping->fee;
            }elseif ($shipping == 3 && $totalprice < 300) {
                $shippingfee = $checkshipping->fee;
            }elseif ($shipping == 2 && $totalprice < 300) {
                $shippingfee = $checkshipping->fee;
            }else{
                $shippingfee = 0;
            }

            if ($voucher != ""){
            //checkvoucher in table
                $checkvoucher = Voucher::find()->where(['name' => $voucher])->one();
                $checkvoucherid = $checkvoucher->id;
                if($checkvoucher === null){
                    $checkvoucherid = "Voucher is not exist!";
                    $totalprice = $totalprice;
                }
                else{
                    if ($checkvoucher->type == 1 && count($list) >= 2) {
                    //this one is percentage
                        $dis = $totalprice;
                        $dis *= ($checkvoucher->discount/100);
                        $totalprice -=$dis;
                        $descriptionvoucher = $checkvoucher->description;
                    }
                    elseif ($checkvoucher->type == 2 && $totalprice >= 100){
                    //this one is for ringgit
                        $dis = $totalprice;
                        $dis -= $checkvoucher->discount ;
                        $totalprice = $dis;
                        $descriptionvoucher = $checkvoucher->description;
                    }
                    else{
                        $dis = 0;
                        $totalprice = $totalprice;
                        $descriptionvoucher = "none1";
                    }
                }
            }
            else
            {
            //voucher is not inputted
                $dis = 0;
                $descriptionvoucher = "none2";
                $totalprice = $totalprice;

            }

            $totalpricefinal = $totalprice + $shippingfee;

            // return $shippingfee;
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $response_values = array(
                'totalpricefinal' => $totalpricefinal,
                'shippingfee' =>  $shippingfee,
                'dis'=>$dis,
                'descriptionvoucher'=>$descriptionvoucher,
                'descriptionship'=>$descriptionship
                );

            return $response_values;
                // return CJSON::encode($response_values);
            // return Response::json($response_values);
        }
    }
}

