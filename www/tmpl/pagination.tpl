<?php
	$count_pages = ceil($count_elements / $count_on_page);
	if ($count_pages > 1) {
		$left = $active - 1;
		$right = $count_pages - $active;
		if ($left < floor($count_show_pages / 2)) $start = 1;
		else $start = $active - floor($count_show_pages / 2);
		$end = $start + $count_show_pages - 1;
		if ($end > $count_pages) {
			$start -= ($end - $count_pages);
			$end = $count_pages;
			if ($start < 1) $start = 1;
		}
?>
	<div id="pagination">
		<?php if ($active != 1) { ?>
			<a href="<?=$url?>" title="Первая" class="sub">Первая</a>
			<a href="<?php if ($active == 2) { ?><?=$url?><?php } else { ?><?=$url_page.($active - 1)?><?php } ?>" title="Предыдущая" class="sub">Предыдущая</a>
		<?php } else { ?>
			<span class="notsub">Первая</span>
			<span class="notsub">Предыдущая</span>
		<?php } ?>
		<?php for ($i = $start; $i <= $end; $i++) { ?>
			<?php if ($i == $active) { ?><span class="notsub"><?=$i?></span><?php } else { ?><a href="<?php if ($i == 1) { ?><?=$url?><?php } else { ?><?=$url_page.$i.".html"?><?php } ?>" class="sub" ><?=$i?></a><?php } ?>
		<?php } ?>
		<?php if ($active != $count_pages) { ?>
			<a href="<?=$url_page.($active + 1).".html"?>" title="Следующая" class="sub">Следующая</a>
			<a href="<?=$url_page.$count_pages.".html"?>" title="Последняя" class="sub">Последняя</a>		
		<?php } else { ?>
			<span class="notsub">Следующая</span>
			<span class="notsub">Последняя</span>
		<?php } ?>			
	</div>
<?php } ?>