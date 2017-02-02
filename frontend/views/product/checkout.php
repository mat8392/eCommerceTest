<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
?>
<div id="wrapper" class="container">				
	<section class="header_text sub">
		<h4><span>Shopping Cart</span></h4>
	</section>
	<section class="main-content">				
		<div class="row">
		<!-- ibu dia -->
		<?php 
		$i = 1;
		foreach($jajaja as $check){ ?>	
			<div class="span11">					
				<h4 class="title"><span class="text"><strong>Your</strong> Order <?= $i++;?></span></h4>
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
						<?php $j = $check['id']; foreach($model[$j] as $field){ ?>		  
						<tr>
							<td>
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
								$d= 0;
								foreach($model[$j] as $field){
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
					<label>
						<strong>Select the receiver's country : <?= $check['shipping']; ?>
						<p id="desship"></p>
					</label>
					<label>
						<strong>Type Your Voucher : </strong><?= $check['voucher']; ?>

						<p id="desvouch"></p>
					</label>
					<hr>
					<p class="cart-total right">
						<strong>Sub-Total</strong>:	RM <?= number_format((float)$d, 2, '.', '');?><br>
						<strong>You Save</strong>: <span id="coupon">
						RM <?= number_format((float)$check['discount'], 2, '.', '');?>
						</span><br>
						<strong>Ship Fee</strong>: <span id="pTest">
						RM <?= number_format((float)$check['shippingfee'], 2, '.', '');?>
						</span><br>
						<strong>Total</strong>: <span id="total">
						<?= number_format((float)$check['totalprice'], 2, '.', '');?>
						</span><br>
					</p>
					<hr/>
					<p class="buttons center">
						<!-- <button class="btn btn-inverse" type="submit" id="checkout">Checkout</button> -->
					</p>					
				</div>
				<?php } ?>	
				<!-- ibu dia -->
			</div>
		</section>			
	</div>	