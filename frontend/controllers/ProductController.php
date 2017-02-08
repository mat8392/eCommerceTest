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
        $voucher = $_POST['voucher'];
        $totalprice = 0;

        $quantitybeli = Cart::find()->where(['id' => $list])->sum('quantitybeli');

        if (count($list) == 1 ){
            $priceeach = Cart::find()->where(['id' => $_POST['idcart']])->one();
            $totalprice = ($priceeach->price*$priceeach->quantitybeli);

        }else {
        // return VarDumper::dump($list);
            foreach ($list as $value) {
                $priceeach = Cart::find()->where(['id' => $value])->one();
                $totalprice += ($priceeach->price*$priceeach->quantitybeli);
            }
        }

        //checkvoucher is inputted
        if ($voucher != ""){

            //checkvoucher in table
            $checkvoucher = Voucher::find()->where(['name' => $voucher])->one();

            if($checkvoucher === null){

                $dis = 0;
                $checkvoucherid = null;
                $totalprice = $totalprice;
                $descriptionvoucher  = "None";
                $vouchertype = null;

            }
            else{
                if ($checkvoucher->type == 1 && $quantitybeli >= 2) {
                    //this one is percentage
                    // $dis = $checkvoucher->discount;
                    $dis = $totalprice;
                    $dis *= ($checkvoucher->discount/100);
                    $totalprice -=$dis;
                    $descriptionvoucher = $checkvoucher->description;
                    $vouchertype = $checkvoucher->type;
                }
                elseif ($checkvoucher->type == 2 && $totalprice >= 100){
                    //this one is for ringgit
                    $dis = $checkvoucher->discount;
                    $totalprice -= $dis ;
                    $descriptionvoucher = $checkvoucher->description;
                    $vouchertype = $checkvoucher->type;
                }
                else{
                    $dis = 0;
                    $totalprice = $totalprice;
                    $descriptionvoucher = "none1";
                    $vouchertype = null;

                }
            }
        }
        else{
        //voucher is not inputted
            $dis = 0;
            $descriptionvoucher = "none2";
            $totalprice = $totalprice;
            $checkvoucherid = null;
            $vouchertype = null;
        }




        $shipping = $_POST['shipping'];
        $checkshipping = Country::find()->where(['id' => $shipping])->one();

        if ($shipping == 1 && ( $totalprice < 150 || $quantitybeli < 2)) {
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
            'voucher' => $checkvoucherid,
            'shipping' => $shipping,
            'shippingfee' => $shippingfee,
            'discount' => $dis,
            'discounttype' =>$vouchertype,
            'totalprice' => $totalprice
            ])->execute();

        $maxid = Checkout::find()->where(['id' => Checkout::find()->max('id')])->one();
        $maxid->id;


        if (count($list) == 1){
            $f=$list['0'];
            $myUpdate = "UPDATE addtocart
            SET is_buy = 1, checkoutid = $maxid->id
            where id = $f";

            $lala = Yii::$app->db->createCommand($myUpdate)->execute();

        }
        else {
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
            $t = $value['id'];
            $cart = Cart::find()->where('is_buy = 1')->andWhere("checkoutid = $t")->all();
            // VarDumper::dump($cart);            
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
            $shipping = $data['country'];
            $idcart = $data['idcart'];

            //check quantity buy
            $quantitybeli = Cart::find()->where(['id' => $idcart])->sum('quantitybeli');

            if ($shipping != ""){
                $checkshipping = Country::find()->where(['id' => $data['country']])->one();
                $descriptionship = $checkshipping->description;

                if ($shipping == 1 && ( $totalprice < 150 || $quantitybeli  < 2)) {
                    $shippingfee = $checkshipping->fee;
                }elseif ($shipping == 3 && $totalprice < 300) {
                    $shippingfee = $checkshipping->fee;
                }elseif ($shipping == 2 && $totalprice < 300) {
                    $shippingfee = $checkshipping->fee;
                }else{
                    $shippingfee = 0;
                }
            }else{
                $shippingfee = 0;
                $descriptionship = "Not Available";
            }

            if ($voucher != ""){
            //checkvoucher in table
                $checkvoucher = Voucher::find()->where(['name' => $voucher])->one();

                if($checkvoucher === null){
                    $dis = 0;
                    $checkvoucherid = null;
                    $totalprice = $totalprice;
                    $descriptionvoucher  = "Voucher is Invalid";
                }
                else{
                    if ($checkvoucher->type == 1 && $quantitybeli >= 2) {
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
                        $descriptionvoucher = "Check again your cart. ".$checkvoucher->description;
                    }
                }
            }
            else
            {
                //voucher is not inputted
                $dis = 0;
                $descriptionvoucher = "Voucher is not inputted";
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
            $shipping = $data['country'];
            $idcart = $data['idcart'];

            //check quantity buy
            $quantitybeli = Cart::find()->where(['id' => $idcart])->sum('quantitybeli');

            if ($shipping != ""){
                $checkshipping = Country::find()->where(['id' => $data['country']])->one();
                $descriptionship = $checkshipping->description;

                if ($shipping == 1 && ( $totalprice < 150 || $quantitybeli  < 2)) {
                    $shippingfee = $checkshipping->fee;
                }elseif ($shipping == 3 && $totalprice < 300) {
                    $shippingfee = $checkshipping->fee;
                }elseif ($shipping == 2 && $totalprice < 300) {
                    $shippingfee = $checkshipping->fee;
                }else{
                    $shippingfee = 0;
                }
            }else{
                $shippingfee = 0;
                $descriptionship = "Not Available";
            }

            if ($voucher != ""){
            //checkvoucher in table
                $checkvoucher = Voucher::find()->where(['name' => $voucher])->one();


                if($checkvoucher === null){
                    $dis = 0;
                    $checkvoucherid = null;
                    $totalprice = $totalprice;
                    $descriptionvoucher  = "Voucher is Invalid";
                }
                else{
                    if ($checkvoucher->type == 1 && $quantitybeli >= 2) {
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
                        $descriptionvoucher = "Check again your cart. ".$checkvoucher->description;
                    }
                }
            }
            else
            {
            //voucher is not inputted
                $dis = 0;
                $descriptionvoucher = "Voucher is not Inputted";
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

