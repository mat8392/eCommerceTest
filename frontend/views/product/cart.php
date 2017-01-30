
<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
?>	
<div id="wrapper" class="container">
	<section class="header_text sub">
		<h4><span>Product Detail</span></h4>
	</section>
	<section class="main-content">				
		<div class="row">						
			<div class="span9">
				<div class="row">
					<div class="span9">
					<strong>Select the receiver's country : </strong><span><?= Html::activeDropDownList($model, 'id',$items) ?></span><br>
					</div>
				</div>
				<div class="row">
					<div class="span4">
						<a href="<?= $field->image ?>" class="thumbnail" data-fancybox-group="group1" title="Description 1"><img alt="" src="themes/images/ladies/1.jpg"></a>
					</div>
					<div class="span5">
						<address>
							<strong><?= $model->productname ?></strong><br>
							<span><?= $model->description ?></span><br>								
						</address>									
						<h4><strong>Price: RM <?= $model->price ?></strong></h4>
					</div>						
				</div>
				<br>
			</div>
		</div>
	</section>			
</div>

<script type="text/javascript">
	function sendFirstCategory(){

        var test = "this is an ajax test";

        $.ajax({
            url: "<?php echo \Yii::$app->getUrlManager()->createUrl('cases/ajax') ?>",
            type: 'POST',
             data: { test: test },
             success: function(data) {
                 alert(data);

             }
         });
    }
</script>