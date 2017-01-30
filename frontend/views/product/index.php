<?php
use yii\helpers\Html;
?>

<section class="main-content">
    <div class="row">
        <div class="span12">                                                    
            <div class="row">
                <div class="span12">
                    <h4 class="title">
                        <span class="pull-left"><span class="text"><span class="line">Feature <strong>Products</strong></span></span></span>
                    </h4>
                    <div id="myCarousel" class="myCarousel carousel slide">
                        <div class="carousel-inner">
                            <div class="active item">
                                <ul class="thumbnails">
                                    <?php foreach($model as $field){ ?>                 
                                    <li class="span3">
                                        <div class="product-box">
                                            <span class="sale_tag"></span>
                                            <p><?= Html::a("<img src=' $field->image ' />", ['detail', 'id' => $field->id]); ?></p>
                                            <a class="title"><?= $field->productname; ?></a><br/>
                                            <a class="category"><?= $field->description; ?></a>
                                            <p class="price">RM <?= number_format((float)$field->price, 2, '.', '');?></p>
                                            <p class="price"><strike>RM <?= number_format((float)$field->priceretail, 2, '.', '');?></strike></p>
                                        </div>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>                          
                    </div>
                </div>                      
            </div>      
        </div>              
    </div>
</section>