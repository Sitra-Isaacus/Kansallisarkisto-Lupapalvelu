<div id="alert" class="alert" style="display: <?php if(isset($huomio_punainen)){ echo "block;"; } else { echo "none;"; } ?>">
	<span class="closebtn">!</span> 
	<strong><?php if(isset($huomio_punainen)) echo $huomio_punainen; ?></strong>
	<div id="error_time" class="inlineblock"></div>
	<?php unset($huomio_punainen); ?>
</div>