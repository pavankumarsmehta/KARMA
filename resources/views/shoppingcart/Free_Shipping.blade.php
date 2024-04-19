<?php 
$amount = $SubTotal;
$maxAmount = 150; // Maximum amount for 100%

// Calculate the percentage
$percentage = ($amount / $maxAmount) * 100; 
if($percentage < 150)
{
	if($Total_Amount <= 150)
	{?>
	<div class="frshipping">
		<div class="h5">Free Shipping on Orders over $150</div>
		<div class="fnormal f12 mb-2 ">You are slightly far out from getting free shipping</div>
		<strong class="f12 dblock">Spend <?php if($percentage > 100) { echo "$0"; } else { echo Make_Price($maxAmount - $amount,true,false,$CurencySymbol); } ?> or more to qualify for free shipping</strong>
		<div class="prg-section">
			<div class="progressbar">
				<div class="progressbar-strip" role="progressbar" style="width: <?php if($percentage > 100) { echo 100; } else {echo $percentage; } ?>%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
			</div>
			<svg class="svg_shipping" aria-hidden="true" role="img" width="22" height="22" loading="lazy">
				<use href="#svg_shipping" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_shipping"></use>
			</svg>
		</div>
	</div>
	<?php 
	}
}
?>