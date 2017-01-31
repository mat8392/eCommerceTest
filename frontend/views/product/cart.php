<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
?>
<div id="wrapper" class="container">				
	<section class="header_text sub">
		<h4><span>Shopping Cart</span></h4>
	</section>
	<section class="main-content">				
		<div class="row">
			<div class="span11">					
				<h4 class="title"><span class="text"><strong>Your</strong> Cart</span></h4>
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Image</th>
							<th>Product Name</th>
							<th>Quantity</th>
							<th>Unit Price</th>
							<th>Total</th>
						</tr>
					</thead>
					<tbody>
						<?= Html::beginForm(['product/checkoutprocess'], 'post') ?>
						<?php foreach($model as $field){ ?>		  
						<tr>
							<td>
								<?= Html::input('hidden', 'idcart[]', $field->id, ['id' => 'idcart']) ?>
								<a href="<?= $field->image ?>"><img alt="" src="<?= $field->image ?>"></a>
							</td>
							<td><?= $field->productname; ?></td>
							<td><?= $field->quantitybeli; ?></td>
							<td>RM <?= number_format((float)$field->price, 2, '.', '');?> <br> <strike>RM <?= number_format((float)$field->priceretail, 2, '.', '');?></strike></td>
							<td>
								<?php
								$a = $field->quantitybeli;
								$b = $field->price;
								$c = $a*$b;
								?>
								<?= number_format((float)$c, 2, '.', '');?>
							</td>
						</tr>
						<?php } ?>
						<tr>  
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>
								<?php
								foreach($model as $field){
									$a = $field->quantitybeli;
									$b = $field->price;
									$c = $a*$b;
									$d += $c;
								}
								?>
								<strong><?= number_format((float)$d, 2, '.', '');?></strong></td>
							</tr>		  
						</tbody>
					</table>
					<h4>What would you like to do next?</h4>
					<p>Choose if you have a discount code or reward points you want to use or would like to estimate your delivery cost.</p>
					<label>
						<strong>Select the receiver's country : </strong> <?= Html::dropDownList('shipping', null, $items, ['id' => 'shipping']) ?>
						<p id="pTest">Male</p>
					</label>
					<label>
						<strong>Type Your Voucher : </strong><?= Html::input('text', 'voucher', null) ?>

					</label>
					<hr>
					<p class="cart-total right">
						<strong>Sub-Total</strong>:	RM <?= number_format((float)$d, 2, '.', '');?><br>
						<strong>Coupon</strong>: $2.00<br>
						<strong>Ship Fee</strong>: $17.50<br>
						<strong>Total</strong>: $119.50<br>
					</p>
					<hr/>
					<p class="buttons center">
						<button class="btn btn-inverse" type="submit" id="checkout">Checkout</button>
					</p>					
				</div>
			</div>
			<?= Html::endForm() ?>
		</section>			
	</div>
	<script type="text/javascript">
	$(document).ready(function() {
		var csrfToken = $('meta[name="csrf-token"]').attr("content");
		var test ="test";
				// $("#shipping").change(function () {
				// 	$.ajax({
    //         			type: "POST",
    //         			url: "index.php?r=product/shippingopt", // Don't know asp/asp.net at all so you will have to do this bit
    //         			data: {
	   //          			searchname: 1 , 
    //              			searchby: 2 , 
				//             _csrf : '<?=Yii::$app->request->getCsrfToken()?>'
				//          },
    //         			success:function(data){
    //             			alert(data.asdasd);
    //         			}	
    //     			});

				// 	$('#pTest').text('test')
    // 			});
	$("#shipping").change(function () {
		var idcart = $("input[id='idcart']")
              .map(function(){return $(this).val();}).get();
		$.ajax({
			url: 'index.php?r=product/shippingopt',
			type: 'GET',
			data: { 'country': $(this).val(), 'idcart': idcart},
			datatype: "JSON",
			success: function(data) {
				$('#pTest').text(data);
			},
			error: function(passParams){
           		alert(passParams.error);
      		}
		});
	});
});
</script>		