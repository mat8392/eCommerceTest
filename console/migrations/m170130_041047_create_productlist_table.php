<?php

use yii\db\Migration;

/**
 * Handles the creation of table `productlist`.
 */
class m170130_041047_create_productlist_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('productlist', [
            'id' => $this->primaryKey(),
            'productname' => $this->string(),
            'image' => $this->string(),
            'description' => $this->string(),
            'price' => $this->double(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('productlist');
    }
}
