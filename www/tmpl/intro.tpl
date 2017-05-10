<div class="main">
	<article class="fon_header">
		<?php if (isset($hornav)) { ?><?=$hornav?><?php } ?>
		<h1 class="h1"><?=$obj->title?></h1>						
		<img src="<?=$obj->img?>" alt="<?=$obj->title?>" class="border_img" />		
		<?=$obj->description?>
	</article>
</div>