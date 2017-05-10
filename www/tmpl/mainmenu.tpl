<?php function printItem($item, &$items, $childrens, $active) { ?>
	<?php if(count($items) == 0) return; ?>
	<div>
		<a <?php if(in_array($item->id, $active)) { ?>class="active"<?php } ?> <?php if($item->external) { ?>rel="external"<?php } ?> href="<?=$item->link?>"><?=$item->title?></a>
		<?php
			while(true) {
				$key = array_search($item->id, $childrens);
				if(!$key) break;
				unset($childrens[$key]);
		?>
		<?=printItem($items[$key], $items, $childrens, $active) ?>		
		<?php } ?>
	</div>
<?php unset($items[$item->id]); } ?>
<div id="menu">
	<header>
		<h2 class="fon_head">Меню сайта:</h2>
	</header>
	<div class="menu" style="margin:10px 0 10px 0;">
		<?php foreach($items as $item) { ?>			
			<?=printItem($item, $items, $childrens, $active) ?>
		<?php } ?>
	</div>		
</div>
