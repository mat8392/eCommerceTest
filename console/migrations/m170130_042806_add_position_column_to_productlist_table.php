<?php

use yii\db\Migration;

/**
 * Handles adding position to table `productlist`.
 */
class m170130_042806_add_position_column_to_productlist_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('productlist', 'retailprice', $this->double());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('productlist', 'retailprice');
    }
}
