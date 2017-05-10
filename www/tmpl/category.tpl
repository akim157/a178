<?php foreach($articles as $article) { ?>
	<section style="overflow: hidden;">
		<div class="article_img">
			<img src="<?=$article->img?>" alt="<?=$article->title?>" width="170" height="170" />
		</div>				
		<header style="margin-left: 187px;">
			<h2 class="fon_head">
				<a href="<?=$article->link?>"><?=$article->title?></a>
			</h2>
		</header>
		<?=$article->intro?>				
		<div class="clear"></div>
		<p class="fon_header">
			<span>Создан: <?=$article->date?></span>
			<span>&nbsp;|&nbsp;</span>
			<span>Автор: Фёдоров Максим</span>
			<span>&nbsp;|&nbsp;</span>
			<span><?=$article->count_comments_text?> <?=$article->count_comments?></span>
		</p>	
	</section>
<?php } ?>
<?=$pagination?>