<section id="content">
	<div class="container content_catalog">
		<div class="row">
			<div class="col-md-12">
				<h1 class="fon_header">Поиск: <?=$query?></h1>
				<?php if ($error_len) { ?><p class="message">Слишком короткий поисковый запрос!</p><?php } ?>
				<div id="search_result">
					<p>Что искали: <b><?=$query?></b></p>
					<p>Всего найдено: <b><?=count($data)?></b> записей</p>
					<?php $number = 0; foreach ($data as $d) { $number++; ?>
					<div class="search_item">
						<div class="article_info">
							<ul>
								<li><?=$number?>. <a href="<?=$d->link?>"><?=$d->title?></a></li>
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
		</div>
	</div>
</section>