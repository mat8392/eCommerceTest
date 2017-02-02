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
								$d = 0;
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
						<strong>Select the receiver's country : </strong> <?= Html::dropDownList('shipping', null, $items, ['prompt' => 'Please Select','id' => 'shipping']) ?>
						<p id="desship"></p>
					</label>
					<label>
						<strong>Type Your Voucher : </strong><?= Html::input('text', 'voucher', null, ['id' => 'voucher']) ?>
						<span><input type="button" onclick="doSomething()" value="Apply" /></span>
						<p id="desvouch"></p>
					</label>
					<hr>
					<p class="cart-total right">
						<strong>Sub-Total</strong>:	RM <?= number_format((float)$d, 2, '.', '');?><br>
						<strong>You Save</strong>: <span id="coupon">N/A</span><br>
						<strong>Ship Fee</strong>: <span id="pTest">N/A</span><br>
						<strong>Total</strong>: <span id="total"><?= number_format((float)$d, 2, '.', '');?></span><br>
					</p>
					<hr/>
					<p class="buttons center">
						<a href="index.php?r=product"><input type="button" href="index.php?r=product" value="Continue Shopping" class="btn btn-inverse" /></a>&nbsp;<button class="btn btn-inverse" type="submit" id="checkout">Checkout</button>
					</p>					
				</div>
			</div>
			<?= Html::endForm() ?>
		</section>			
	</div>
	<script type="text/javascript">
		$(document).ready(function() {

			$("#shipping").change(function () {
				var idcart = $("input[id='idcart']")
				.map(function(){return $(this).val();}).get();
				$.ajax({
					url: 'index.php?r=product/shippingopt',
					type: 'GET',
					data: { 'country': $(this).val(), 'idcart': idcart, totalprice: <?= $d; ?>, 'voucher': $("#voucher").val()},
					datatype: "JSON",
					success: function(data) {
						// alert(JSON.parse(data.shippingfee));
						$('#pTest').text(parseFloat(data.shippingfee).toFixed(2));
						$('#coupon').text(parseFloat(data.dis).toFixed(2));
						$('#total').text(parseFloat(data.totalpricefinal).toFixed(2));
						$('#desvouch').text(data.descriptionvoucher);
						$('#desship').text(data.descriptionship);
					},
					error: function(passParams){
						alert(passParams.error);
					}
				});
			});
		});

		function doSomething() 
		{ 
			console.log($("#voucher").val());
			console.log($("#shipping").val());
			var idcart = $("input[id='idcart']")
			.map(function(){return $(this).val();}).get();
			$.ajax({
				url: 'index.php?r=product/vouchopt',
				type: 'GET',
				data: { 'country': $("#shipping").val(), 'idcart': idcart, totalprice: <?= $d; ?>, 'voucher': $("#voucher").val()},
				datatype: "JSON",
				success: function(data) {
						// alert(JSON.parse(data.shippingfee));
						$('#pTest').text(parseFloat(data.shippingfee).toFixed(2));
						$('#coupon').text(parseFloat(data.dis).toFixed(2));
						$('#total').text(parseFloat(data.totalpricefinal).toFixed(2));
						$('#desvouch').text(data.descriptionvoucher);
						$('#desship').text(data.descriptionship);
					},
					error: function(passParams){
						alert(passParams.error);
					}
				});
		} 
	</script>		