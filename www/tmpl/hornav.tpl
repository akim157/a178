<div class="track fon_head">
	Вы здесь: 
	<?php $first = true; foreach($data as $d) { ?>
		<?php if(!$first) { ?> <span>&nbsp;>&nbsp;</span> <?php } ?>
		<?php if($d->link) { ?><a href="<?=$d->link?>"><?=$d->title?></a><?php } else { ?><?=$d->title?><?php } ?>		
	<?php $first = false; } ?>	
</div>