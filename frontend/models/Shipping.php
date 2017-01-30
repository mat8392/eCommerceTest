<?php
namespace app\models;
 
use Yii;
 
class Shipping extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shipping';
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