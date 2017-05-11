<?php function note($data) { ?>
	<?php
		$count_data = count($data);
		if($count_data == 1) echo 'запись';
		if($count_data > 1 and $count_data < 10) echo 'записи';
		if($count_data > 10) echo 'записей';
	?>
<?php } ?>
<div class="col-md-12">
	<h1 class="fon_header">Поиск: <?=$query?></h1>
	<?php if ($error_len) { ?><p class="message">Слишком короткий поисковый запрос!</p><?php } ?>
	<div id="search_result">
		<p>Всего найдено: <b><?=count($data)?></b> <?php note($data); ?></p>
		<?php $number = 0; foreach ($data as $d) { $number++; ?>
		<div class="search_item">
			<div class="article_info">
				<ul class="search_markery">
					<li class="cell-catalog"><?=$number?>. <a href="<?=$d->link?>"><?=$d->title?></a></li>
					<?php if (isset($d->section) || isset($d->category)) { ?>
					<li><?=$d->description?></li>
					<?php } ?>
				</ul>
				<div class="clear"></div>
			</div>
		</div>
		<?php } ?>
	</div>
</div>
