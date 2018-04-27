<div id="alert_success" class="alert success" style="display: <?php if(isset($huomio_vihrea)){ echo "block;"; } else { echo "none;"; } ?>">
	<span class="closebtn">&#10004;</span> 
	<strong id="success_huomio"><?php if(isset($huomio_vihrea)) { echo $huomio_vihrea; } ?></strong>
	<strong style="display: none;" id="success_save"><?php if($self=="hakemus.php"){ echo HAKEMUS_TALLENNETTU; } else { echo LOMAKE_TALLENNETTU; } ?></strong>
	<div id="success_time" class="inlineblock"></div>
	<?php unset($huomio_vihrea); ?>
</div>