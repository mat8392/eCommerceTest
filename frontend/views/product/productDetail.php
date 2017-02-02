
<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
?>	
<div id="wrapper" class="container">
	<section class="header_text sub">
		<h4><span>Product Detail</span></h4>
	</section>
	<?php //$form =ActiveForm::begin(['action' => ['builder/saveform'],'options' => ['method' => 'post']]) ?>
	<section class="main-content">				
		<div class="row">						
			<div class="span9">
				<div class="row">
					<div class="span9">
					</div>
				</div>
				<div class="row">
					<div class="span4">
						<a href='<?= $model->image ?>' class="thumbnail" data-fancybox-group="group1" title="Description 1"><img alt="" src='<?= $model->image ?>'></a>
					</div>
					<div class="span5">
						<address>
							<strong><?= $model->productname ?></strong><br>
							<span><?= $model->description ?></span><br>								
						</address>									
						<h4><strong>Price: RM <?= number_format((float)$model->price, 2, '.', '');?> &nbsp; <strike>RM <?= number_format((float)$model->priceretail, 2, '.', ''); ?></strike></strong></h4>
					</div>
					<div class="span5">
					<?= Html::beginForm(['product/addcart',  'id' => $model->id], 'post') ?>
							<label>Qty:</label>
							<input type="text" required class="span1 control-label required" name="quantitybeli" placeholder="1">
							<button class="btn btn-inverse" type="submit">Add to cart</button>
					<?= Html::endForm() ?>
					</div>							
				</div>
			</div>
		</div>
	</section>
	<?php //ActiveForm::end(); ?>		
</div>

<script type="text/javascript">
	function sendFirstCategory(){

        var test = "this is an ajax test";

        $.ajax({
            url: '<?php echo \Yii::$app->getUrlManager()->createUrl('cases/ajax') ?>',
            type: 'POST',
             data: { test: test },
             success: function(data) {
                 alert(data);

             }
         });
    }
</script>