<?php
namespace app\models;
 
use Yii;
 
class Checkout extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'checkout';
    }
     
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // [['full_name', 'address', 'phone'], 'required'],
            // [['full_name', 'address'], 'string', 'max' => 100],
            // [['phone'], 'string', 'max' => 15]
        ];
    }   
}