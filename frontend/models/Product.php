<?php
namespace app\models;
 
use Yii;
 
class Product extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'productlist';
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